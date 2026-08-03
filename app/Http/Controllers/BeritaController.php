<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $wilayahSelected = $request->get('wilayah');

        $beritaUtamaQuery = Berita::active()->latestPublish();

        if ($search) {
            $beritaUtamaQuery->where(function ($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                    ->orWhere('konten', 'like', '%' . $search . '%');
            });
        }

        if ($wilayahSelected) {
            $beritaUtamaQuery->where('wilayah', $wilayahSelected);
        }

        $beritaUtama = $beritaUtamaQuery->first();

        $beritasQuery = Berita::active()->latestPublish();

        if ($search) {
            $beritasQuery->where(function ($query) use ($search) {
                $query->where('judul', 'like', '%' . $search . '%')
                    ->orWhere('konten', 'like', '%' . $search . '%');
            });
        }

        if ($wilayahSelected) {
            $beritasQuery->where('wilayah', $wilayahSelected);
        }

        $beritas = $beritasQuery->paginate(12);

        $beritaPopuler = Berita::active()
            ->populer()
            ->latestPublish()
            ->take(5)
            ->get();

        $beritaTerbaru = Berita::active()
            ->latestPublish()
            ->take(5)
            ->get();

        $daftarProvinsi = \App\Helpers\WilayahHelper::getDaftarProvinsi();

        return view('pages.berita', compact(
            'beritaUtama',
            'beritas',
            'beritaPopuler',
            'beritaTerbaru',
            'search',
            'wilayahSelected',
            'daftarProvinsi'
        ));
    }

    public function show($slug)
    {
        $berita = Berita::active()->where('slug', $slug)->firstOrFail();

        // Increment views
        $berita->incrementViews();

        // Berita populer (5 terbaru, exclude current)
        $beritaPopuler = Berita::active()
            ->populer()
            ->where('id', '!=', $berita->id)
            ->latestPublish()
            ->take(5)
            ->get();

        // Berita terbaru (5 terbaru, exclude current)
        $beritaTerbaru = Berita::active()
            ->where('id', '!=', $berita->id)
            ->latestPublish()
            ->take(5)
            ->get();

        return view('pages.details.berita-detail', compact(
            'berita',
            'beritaPopuler',
            'beritaTerbaru'
        ));
    }
}