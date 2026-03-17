<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerProfile;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = CustomerProfile::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('member_code', 'ilike', "%{$search}%")
                  ->orWhere('phone', 'ilike', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'ilike', "%{$search}%"));
            });
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'gender' => 'nullable|in:male,female',
            'birth_date' => 'nullable|date',
            // Vehicle (optional)
            'plate_number' => 'nullable|string|max:20',
            'vehicle_type' => 'nullable|in:motor,mobil,suv,truck,bus',
            'brand' => 'nullable|string|max:100',
            'vehicle_model' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'] ?? strtolower(str_replace(' ', '', $validated['name'])) . rand(100, 999) . '@washhub.local',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'phone' => $validated['phone'],
            ]);

            $profile = CustomerProfile::create([
                'user_id' => $user->id,
                'phone' => $validated['phone'],
                'address' => $validated['address'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'birth_date' => $validated['birth_date'] ?? null,
                'member_code' => CustomerProfile::generateMemberCode(),
            ]);

            if (!empty($validated['plate_number'])) {
                Vehicle::create([
                    'customer_profile_id' => $profile->id,
                    'plate_number' => strtoupper($validated['plate_number']),
                    'vehicle_type' => $validated['vehicle_type'] ?? 'mobil',
                    'brand' => $validated['brand'] ?? null,
                    'model' => $validated['vehicle_model'] ?? null,
                    'color' => $validated['color'] ?? null,
                ]);
            }
        });

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer berhasil ditambahkan!');
    }

    public function show(CustomerProfile $customer)
    {
        $customer->load([
            'user', 'vehicles',
            'transactions' => fn($q) => $q->with('details')->orderBy('created_at', 'desc')->limit(20),
            'pointHistories' => fn($q) => $q->orderBy('created_at', 'desc')->limit(20),
            'rewardClaims' => fn($q) => $q->orderBy('created_at', 'desc'),
        ]);

        return view('admin.customers.show', compact('customer'));
    }

    public function edit(CustomerProfile $customer)
    {
        $customer->load('user', 'vehicles');
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, CustomerProfile $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "nullable|email|unique:users,email,{$customer->user_id}",
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'gender' => 'nullable|in:male,female',
            'birth_date' => 'nullable|date',
        ]);

        DB::transaction(function () use ($validated, $customer) {
            $customer->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'] ?? $customer->user->email,
                'phone' => $validated['phone'],
            ]);

            $customer->update([
                'phone' => $validated['phone'],
                'address' => $validated['address'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'birth_date' => $validated['birth_date'] ?? null,
            ]);
        });

        return redirect()->route('admin.customers.show', $customer)
            ->with('success', 'Data customer berhasil diupdate!');
    }

    public function destroy(CustomerProfile $customer)
    {
        $customer->user->delete();
        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer berhasil dihapus!');
    }

    // ── Vehicle Management ──
    public function addVehicle(Request $request, CustomerProfile $customer)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|max:20',
            'vehicle_type' => 'required|in:motor,mobil,suv,truck,bus',
            'brand' => 'nullable|string|max:100',
            'vehicle_model' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
        ]);

        $customer->vehicles()->create([
            'plate_number' => strtoupper($validated['plate_number']),
            'vehicle_type' => $validated['vehicle_type'],
            'brand' => $validated['brand'] ?? null,
            'model' => $validated['vehicle_model'] ?? null,
            'color' => $validated['color'] ?? null,
            'year' => $validated['year'] ?? null,
        ]);

        return back()->with('success', 'Kendaraan berhasil ditambahkan!');
    }
}