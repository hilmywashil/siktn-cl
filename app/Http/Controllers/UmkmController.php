<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UmkmController extends Controller
{
    /**
     * Tampilkan form registrasi UMKM
     * MANUAL CHECK LOGIN - TIDAK PAKAI MIDDLEWARE
     */
    public function create()
    {
        // ✅ CEK MANUAL apakah user sudah login
        if (!Auth::guard('anggota')->check()) {
            return redirect()->route('anggota.login')
                ->with('error', 'Anda harus login terlebih dahulu untuk mendaftar UMKM.')
                ->with('intended', route('umkm'));
        }

        return view('pages.registrasi-umkm');
    }

    /**
     * Simpan data registrasi UMKM
     */
    public function store(Request $request)
    {
        // ✅ CEK MANUAL apakah user sudah login
        if (!Auth::guard('anggota')->check()) {
            return redirect()->route('anggota.login')
                ->with('error', 'Anda harus login terlebih dahulu untuk mendaftar UMKM.');
        }

        $anggota = Auth::guard('anggota')->user();

        // Validasi input
        $validated = $request->validate([
            // Data Usaha
            'nama_usaha' => 'required|string|max:255',
            'bidang_usaha' => 'required|in:Makanan & Minuman,Fashion,Kerajinan / Handmade,Pertanian / Perikanan,Jasa,Lainnya',
            'status_legalitas' => 'required|in:Sudah Berizin,Belum Berizin',
            'jenis_legalitas' => 'nullable|in:NIB,PIRT,Halal,BPOM,Sertifikat Lainnya,Lainnya',
            'tahun_berdiri' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            
            // Data Pribadi
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'required|date|before:today',
            'nomor_hp' => 'required|string|min:10|max:15|regex:/^[0-9]+$/',
            'email' => 'required|email|max:255',
            'alamat_domisili' => 'required|string',
            
            // Data Lainnya
            'platform_digital' => 'required|in:Ya,Tidak',
            'platform' => 'required|array|min:1',
            'platform.*' => 'in:Instagram,Facebook,Tiktok,Shopee,Tokopedia,Website Sendiri,Lainnya',
            'pendapatan' => 'required|in:1 juta s/d 10 juta,10 juta s/d 100 juta,100 juta s/d 500 juta,500 juta s/d 1 miliar,Lebih dari 1 miliar',
            'pembiayaan' => 'nullable|in:Ya,Tidak',
            'sumber_pembiayaan' => 'nullable|in:Bank,Fintech,Koperasi,Lainnya',
            'tujuan' => 'required|in:Meningkatkan penjualan online,Mendapatkan akses pembiayaan,Meningkatkan literasi keuangan,Mencari mitra atau jaringan,Lainnya',
            'pelatihan' => 'required|string',
        ]);

        try {
            // Tambahkan anggota_id dan status default
            $validated['anggota_id'] = $anggota->id;
            $validated['status'] = 'pending';
            
            // Simpan data ke database
            $umkm = Umkm::create($validated);

            Log::info('UMKM Registration Success', [
                'umkm_id' => $umkm->id,
                'anggota_id' => $anggota->id,
                'nama_usaha' => $validated['nama_usaha']
            ]);

            return redirect()->route('umkm')
                ->with('success', 'Terima kasih! Pendaftaran UMKM Anda berhasil disimpan. Tim kami akan segera menghubungi Anda.');
                
        } catch (\Exception $e) {
            Log::error('UMKM Registration Error', [
                'error' => $e->getMessage(),
                'anggota_id' => $anggota->id
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }
}