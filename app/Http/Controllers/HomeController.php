<?php

namespace App\Http\Controllers;

use App\Models\Katalog;
use App\Models\Misi;
use App\Models\Anggota;
use App\Models\Berita;
use App\Models\Organisasi;
use App\Models\StrategicPlan;

class HomeController extends Controller
{
    public function index()
    {
        // Katalog aktif
        $katalogs = Katalog::where('is_active', true)
            ->latest()
            ->take(10)
            ->get();

        $totalKatalog = Katalog::where('is_active', true)->count();

        // Misi aktif
        $misi = Misi::active()->ordered()->get();

        // Anggota approved
        $totalAnggota = Anggota::approved()->count();

        $anggotaList = Anggota::approved()
            ->orderByDesc('approved_at')
            ->take(10)
            ->get();
        $organisasiList = Organisasi::where('aktif', true)
            ->orderByRaw("
        FIELD(kategori,
            'ketua_umum',
            'wakil_ketua_umum',
            'ketua_bidang',
            'sekretaris_umum',
            'wakil_sekretaris_umum'
        )
    ")
            ->take(10)
            ->get();
        $kegiatanBeritaLastest = Berita::active()->latest()->first();
        $kegiatanBeritaLast = Berita::active()
            ->latest()
            ->skip(1)
            ->take(3)
            ->get();

        $dokumentasiBerita = Berita::active()
            ->latestPublish()
            ->take(7)
            ->get();

        // Strategic Plan
        $tataKelola = StrategicPlan::active()
            ->tataKelola()
            ->ordered()
            ->take(6)
            ->get();

        $programLayanan = StrategicPlan::active()
            ->programLayanan()
            ->ordered()
            ->take(8)
            ->get();

        return view('pages.home', compact(
            'katalogs',
            'totalKatalog',
            'misi',
            'totalAnggota',
            'anggotaList',
            'kegiatanBeritaLast',
            'kegiatanBeritaLastest',
            'dokumentasiBerita',
            'tataKelola',
            'programLayanan',
            'organisasiList'
        ));
    }
}
