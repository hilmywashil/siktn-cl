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

        $listWilayahDb = Berita::active()->whereNotNull('wilayah')->distinct()->pluck('wilayah')->toArray();
        $provinces = ["Nasional", "Aceh","Sumatera Utara","Sumatera Barat","Riau","Jambi","Sumatera Selatan","Bengkulu","Lampung","Kepulauan Bangka Belitung","Kepulauan Riau","Dki Jakarta","Jawa Barat","Jawa Tengah","Di Yogyakarta","Jawa Timur","Banten","Bali","Nusa Tenggara Barat","Nusa Tenggara Timur","Kalimantan Barat","Kalimantan Tengah","Kalimantan Selatan","Kalimantan Timur","Kalimantan Utara","Sulawesi Utara","Sulawesi Tengah","Sulawesi Selatan","Sulawesi Tenggara","Gorontalo","Sulawesi Barat","Maluku","Maluku Utara","Papua Barat","Papua"];
        $listWilayah = array_unique(array_merge($provinces, $listWilayahDb));

        return view('pages.berita', compact(
            'beritaUtama',
            'beritas',
            'beritaPopuler',
            'beritaTerbaru',
            'search',
            'wilayahSelected',
            'listWilayah'
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