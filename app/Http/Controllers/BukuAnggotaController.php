<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;

class BukuAnggotaController extends Controller
{
    /**
     * Display a listing of approved members.
     * Menampilkan anggota terbaru yang sudah diapprove
     */
    public function index(Request $request)
    {
        $query = Anggota::approved(); // Hanya tampilkan anggota yang sudah diapprove
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nrp', 'like', "%{$search}%")
                  ->orWhere('angkatan', 'like', "%{$search}%")
                  ->orWhere('nama_perusahaan', 'like', "%{$search}%")
                  ->orWhere('trade_mark', 'like', "%{$search}%")
                  ->orWhereHas('katalog', function($kq) use ($search) {
                      $kq->where('business_field', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter by bidang usaha jika diperlukan (melalui relasi katalog)
        if ($request->filled('bidang_usaha')) {
            $query->whereHas('katalog', function($q) use ($request) {
                $q->where('business_field', $request->bidang_usaha);
            });
        }
        
        // Ambil data dengan pagination - Urutkan data terbaru
        $anggotas = $query->latest('approved_at')->paginate(12); // 12 items per page
        
        // Ambil list bidang usaha untuk filter (dari tabel katalog)
        $bidangUsahaList = \App\Models\Katalog::active()
                                            ->distinct()
                                            ->pluck('business_field')
                                            ->filter()
                                            ->sort()
                                            ->values();
        
        return view('pages.buku-anggota', compact('anggotas', 'bidangUsahaList'));
    }
    
    /**
     * Display the specified member detail.
     */
    public function show(Anggota $anggota)
    {
        // Pastikan hanya anggota yang approved yang bisa dilihat
        if ($anggota->status !== 'approved') {
            abort(404);
        }
        
        return view('pages.details.buku-detail', compact('anggota'));
    }
}