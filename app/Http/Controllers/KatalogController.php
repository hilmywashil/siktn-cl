<?php

namespace App\Http\Controllers;

use App\Models\Katalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KatalogController extends Controller
{

    public function letter(Request $request)
    {
        $letter = $request->get('letter');

        $katalogsletter = Katalog::active()->when($letter, function ($query) use ($letter) {
            $query->where('company_name', 'LIKE', $letter . '%');
        })
            ->orderBy('company_name')
            ->get()
            ->groupBy(function ($item) {
                return strtoupper(substr($item->company_name, 0, 1));
            });

        return view('pages.active-member', compact('katalogsletter', 'letter'));
    }

    public function index(Request $request)
    {
        $query = Katalog::active();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                    ->orWhere('business_field', 'like', "%{$search}%");
            });
        }

        $katalogs = $query
            ->orderBy('company_name', 'asc')
            ->paginate(50);

        return view('pages.ekatalog', compact('katalogs'));
    }

    public function show(Katalog $katalog)
    {
        if (!$katalog->is_active) {
            abort(404);
        }

        // Cek apakah user sudah login dan terverifikasi
        $canViewFullDetail = $this->canViewFullDetail();

        return view('pages.details.ekatalog-detail', compact('katalog', 'canViewFullDetail'));
    }

    /**
     * Cek apakah user bisa melihat detail lengkap
     * Hanya admin atau anggota yang terverifikasi
     */
    private function canViewFullDetail()
    {
        // Jika login sebagai admin
        if (Auth::guard('admin')->check()) {
            return true;
        }

        // Jika login sebagai anggota dan statusnya approved
        if (Auth::guard('anggota')->check()) {
            $anggota = Auth::guard('anggota')->user();
            return $anggota->status === 'approved';
        }

        // Jika tidak login atau tidak memenuhi syarat
        return false;
    }
}