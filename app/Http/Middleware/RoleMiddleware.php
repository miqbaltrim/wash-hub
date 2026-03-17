<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Breeze sudah handle auth redirect via 'auth' middleware
        // Jadi di sini kita cukup cek role saja
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda dinonaktifkan. Hubungi admin.',
            ]);
        }

        if (!in_array($user->role, $roles)) {
            // Redirect ke halaman sesuai role, bukan abort 403
            return match ($user->role) {
                'admin', 'cashier' => redirect()->route('dashboard'),
                'customer' => redirect()->route('customer.dashboard'),
                default => redirect()->route('login'),
            };
        }

        return $next($request);
    }
}