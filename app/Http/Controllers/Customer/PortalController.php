<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalController extends Controller
{
    public function dashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $customer = $user->customerProfile;

        if (!$customer) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Profil customer tidak ditemukan. Hubungi admin.']);
        }

        $customer->load([
            'vehicles',
            'transactions' => fn($q) => $q->with('details')->orderBy('created_at', 'desc')->limit(10),
            'pointHistories' => fn($q) => $q->orderBy('created_at', 'desc')->limit(10),
            'rewardClaims' => fn($q) => $q->orderBy('created_at', 'desc'),
        ]);

        $washesInCycle = $customer->total_washes % 10;

        $stats = [
            'total_washes' => $customer->total_washes,
            'total_points' => $customer->total_points,
            'available_rewards' => $customer->getAvailableFreeWashes(),
            'washes_until_reward' => $washesInCycle === 0 && $customer->total_washes > 0 ? 0 : 10 - $washesInCycle,
            'progress_percent' => $washesInCycle * 10,
        ];

        return view('customer.dashboard', compact('customer', 'stats'));
    }

    public function transactions()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $customer = $user->customerProfile;

        $transactions = $customer->transactions()
            ->with('details')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('customer.transactions', compact('transactions', 'customer'));
    }

    public function pointHistory()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $customer = $user->customerProfile;

        $histories = $customer->pointHistories()
            ->with('transactionHead')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('customer.points', compact('histories', 'customer'));
    }

    public function rewards()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $customer = $user->customerProfile;

        $claims = $customer->rewardClaims()
            ->with('transactionHead')
            ->orderBy('created_at', 'desc')
            ->get();

        $washesInCycle = $customer->total_washes % 10;

        $stats = [
            'available_rewards' => $customer->getAvailableFreeWashes(),
            'total_washes' => $customer->total_washes,
            'washes_until_next' => $washesInCycle === 0 && $customer->total_washes > 0 ? 0 : 10 - $washesInCycle,
        ];

        return view('customer.rewards', compact('claims', 'stats', 'customer'));
    }

    public function claimReward()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $customer = $user->customerProfile;

        if ($customer->getAvailableFreeWashes() <= 0) {
            return back()->with('error', 'Anda belum memenuhi syarat untuk klaim reward.');
        }

        $customer->rewardClaims()->create([
            'reward_type' => 'free_wash',
            'washes_required' => 10,
            'washes_at_claim' => $customer->total_washes,
            'status' => 'claimed',
            'claimed_at' => now(),
        ]);

        return back()->with('success', 'Selamat! Anda berhasil klaim 1x cuci gratis. Tunjukkan ke kasir saat datang.');
    }
}