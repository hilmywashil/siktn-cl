<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated as admin
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $admin = Auth::guard('admin')->user();
        if (isset($admin->is_active) && !$admin->is_active) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login')
                ->with('error', 'Akun Anda telah dinonaktifkan oleh Pimpinan / Super Admin.');
        }

        return $next($request);
    }
}