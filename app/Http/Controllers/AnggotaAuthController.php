<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;   
use App\Models\Anggota;

class AnggotaAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('anggota')->check()) {
            return redirect()->route('profile-anggota');
        }

       
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

     
        $anggota = Anggota::where($loginType, $request->login)->first();

        if (!$anggota) {
            return back()->withErrors([
                'login' => 'Username atau Email tidak terdaftar.'
            ])->withInput();
        }

     
        if (!Hash::check($request->password, $anggota->password)) {
            return back()->withErrors([
                'password' => 'Password salah.'
            ])->withInput();
        }

      
        Auth::guard('anggota')->login($anggota, $request->filled('remember'));
        
     
        $request->session()->regenerate();

        return redirect()->intended(route('profile-anggota'))
            ->with('success', 'Selamat datang, ' . ($anggota->nama_lengkap ?? $anggota->username));
    }

    public function logout(Request $request)
    {
        Auth::guard('anggota')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Anda telah logout.');
    }
}