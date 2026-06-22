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

        // Ubah dari 'auth.login-anggota' menjadi 'auth.login'
        // karena view tersebut sudah handle keduanya (admin dan anggota)
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Cari anggota berdasarkan email_website_perusahaan
        $anggota = Anggota::where('email_website_perusahaan', $request->email)->first();

        if (!$anggota) {
            return back()->withErrors([
                'email' => 'Email tidak terdaftar.'
            ])->withInput();
        }

        // Cek password
        if (!Hash::check($request->password, $anggota->password)) {
            return back()->withErrors([
                'password' => 'Password salah.'
            ])->withInput();
        }

        // Login menggunakan guard anggota
        Auth::guard('anggota')->login($anggota, $request->filled('remember'));
        
        // Regenerate session untuk security
        $request->session()->regenerate();

        return redirect()->intended(route('profile-anggota'))
            ->with('success', 'Selamat datang, ' . $anggota->nama_perusahaan);
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