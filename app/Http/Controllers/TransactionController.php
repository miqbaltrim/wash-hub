<?php

namespace App\Http\Controllers;

use App\Models\CustomerProfile;
use App\Models\RewardClaim;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\TransactionDetail;
use App\Models\TransactionHead;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = TransactionHead::with(['customerProfile.user', 'cashier', 'details']);

        if ($request->filled('date_from')) {
            $query->where('transaction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('transaction_date', '<=', $request->date_to);
        }
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }
        if ($request->filled('wash_status')) {
            $query->where('wash_status', $request->wash_status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'ilike', "%{$search}%")
                  ->orWhere('plate_number', 'ilike', "%{$search}%");
            });
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $categories = ServiceCategory::where('is_active', true)
            ->with(['activeServices'])
            ->orderBy('sort_order')
            ->get();

        $customers = CustomerProfile::with('user', 'vehicles')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('transactions.create', compact('categories', 'customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_profile_id' => 'nullable|exists:customer_profiles,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'plate_number' => 'required|string|max:20',
            'vehicle_type' => 'required|in:motor,mobil,suv,truck,bus',
            'payment_method' => 'required|in:cash,debit,credit_card,ewallet,transfer,free_reward',
            'payment_amount' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'is_reward_claim' => 'boolean',
            'notes' => 'nullable|string',
            // Detail items
            'services' => 'required|array|min:1',
            'services.*.service_id' => 'required|exists:services,id',
            'services.*.qty' => 'required|integer|min:1',
            'services.*.discount' => 'nullable|numeric|min:0',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $transaction = DB::transaction(function () use ($validated, $request, $user) {
            // ── Create HEAD ──
            $head = TransactionHead::create([
                'invoice_number' => TransactionHead::generateInvoiceNumber(),
                'customer_profile_id' => $validated['customer_profile_id'] ?? null,
                'vehicle_id' => $validated['vehicle_id'] ?? null,
                'cashier_id' => $user->id,
                'plate_number' => strtoupper($validated['plate_number']),
                'vehicle_type' => $validated['vehicle_type'],
                'transaction_date' => now()->toDateString(),
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'paid',
                'discount_percent' => $validated['discount_percent'] ?? 0,
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'is_reward_claim' => $request->boolean('is_reward_claim'),
                'notes' => $validated['notes'] ?? null,
                'wash_status' => 'waiting',
            ]);

            // ── Create DETAILS ──
            $totalPoints = 0;
            foreach ($validated['services'] as $item) {
                $service = Service::findOrFail($item['service_id']);

                TransactionDetail::create([
                    'transaction_head_id' => $head->id,
                    'service_id' => $service->id,
                    'service_name' => $service->name,
                    'service_category' => $service->category->name ?? '',
                    'unit_price' => $service->price,
                    'qty' => $item['qty'],
                    'discount' => $item['discount'] ?? 0,
                ]);

                $totalPoints += $service->points_earned * $item['qty'];
            }

            // ── Recalculate totals ──
            $head->calculateTotals();

            // ── Handle payment amount & change ──
            $paymentAmount = $validated['payment_amount'] ?? $head->grand_total;
            $head->update([
                'payment_amount' => $paymentAmount,
                'change_amount' => max(0, $paymentAmount - $head->grand_total),
            ]);

            // ── Handle REWARD CLAIM (free wash) ──
            if ($request->boolean('is_reward_claim') && $head->customer_profile_id) {
                $customer = CustomerProfile::find($head->customer_profile_id);
                if ($customer && $customer->getAvailableFreeWashes() > 0) {
                    RewardClaim::create([
                        'customer_profile_id' => $customer->id,
                        'transaction_head_id' => $head->id,
                        'reward_type' => 'free_wash',
                        'washes_required' => 10,
                        'washes_at_claim' => $customer->total_washes,
                        'status' => 'used',
                        'claimed_at' => now(),
                        'used_at' => now(),
                    ]);

                    $head->update([
                        'grand_total' => 0,
                        'payment_amount' => 0,
                        'change_amount' => 0,
                        'payment_method' => 'free_reward',
                    ]);
                }
            }

            // ── Add POINTS & WASH COUNT to customer ──
            if ($head->customer_profile_id && !$head->is_reward_claim) {
                $customer = CustomerProfile::find($head->customer_profile_id);
                $customer->increment('total_washes');
                $customer->addPoints($totalPoints, $head->id, "Points dari transaksi #{$head->invoice_number}");
                $head->update(['points_earned' => $totalPoints]);
            }

            return $head;
        });

        return redirect()->route('transactions.show', $transaction)
            ->with('success', 'Transaksi berhasil dibuat!');
    }

    public function show(TransactionHead $transaction)
    {
        $transaction->load([
            'customerProfile.user', 'vehicle', 'cashier',
            'details.service', 'pointHistories', 'rewardClaim',
        ]);

        return view('transactions.show', compact('transaction'));
    }

    // ── Update wash status ──
    public function updateStatus(Request $request, TransactionHead $transaction)
    {
        $validated = $request->validate([
            'wash_status' => 'required|in:waiting,in_progress,done,picked_up',
        ]);

        $transaction->update($validated);

        return back()->with('success', 'Status berhasil diupdate!');
    }

    // ── Cancel transaction ──
    public function cancel(TransactionHead $transaction)
    {
        DB::transaction(function () use ($transaction) {
            // Rollback points
            if ($transaction->customer_profile_id && $transaction->points_earned > 0) {
                $customer = $transaction->customerProfile;
                $customer->decrement('total_points', $transaction->points_earned);
                $customer->decrement('total_washes');

                $customer->pointHistories()->create([
                    'transaction_head_id' => $transaction->id,
                    'type' => 'adjusted',
                    'points' => $transaction->points_earned,
                    'balance_after' => $customer->total_points,
                    'description' => "Rollback - Transaksi #{$transaction->invoice_number} dibatalkan",
                ]);
            }

            $transaction->update([
                'payment_status' => 'cancelled',
                'wash_status' => 'picked_up',
            ]);
        });

        return back()->with('success', 'Transaksi berhasil dibatalkan!');
    }

    // ── THERMAL PRINT ──
    public function printReceipt(TransactionHead $transaction)
    {
        $transaction->load(['customerProfile.user', 'details', 'cashier']);

        return view('transactions.receipt', compact('transaction'));
    }

    // ── API: Search customer ──
    public function searchCustomer(Request $request)
    {
        $search = $request->get('q', '');

        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $customers = CustomerProfile::with('user', 'vehicles')
            ->where(function ($q) use ($search) {
                $q->where('member_code', 'ilike', "%{$search}%")
                  ->orWhere('phone', 'ilike', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'ilike', "%{$search}%"));
            })
            ->limit(10)
            ->get();

        return response()->json($customers);
    }
}