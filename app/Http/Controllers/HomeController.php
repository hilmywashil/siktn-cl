<?php

namespace App\Http\Controllers;

use App\Models\Katalog;
use App\Models\Misi;
use App\Models\Anggota;
use App\Models\Berita;
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

        // Berita
        $kegiatanBerita = Berita::active()
            ->latestPublish()
            ->take(5)
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
            'kegiatanBerita',
            'dokumentasiBerita',
            'tataKelola',
            'programLayanan'
        ));
    }
}
