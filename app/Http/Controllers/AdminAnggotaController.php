<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminAnggotaController extends Controller
{
    /**
     * Tampilkan list anggota (untuk BPC dan BPD - READ ONLY)
     */
    public function listAnggota(Request $request)
    {
        $admin = auth()->guard('admin')->user();
        $status = $request->get('status', 'all');
        $domisili = $request->get('domisili', 'all');
        
        // Query base
        $query = Anggota::query();
        
        // Filter berdasarkan kategori admin
        if ($admin->category === 'bpc') {
            // BPC hanya bisa lihat anggota di domisilinya
            $query->where('domisili', $admin->domisili);
            
            $stats = [
                'total' => Anggota::where('domisili', $admin->domisili)->count(),
                'pending' => Anggota::where('domisili', $admin->domisili)->where('status', 'pending')->count(),
                'approved' => Anggota::where('domisili', $admin->domisili)->where('status', 'approved')->count(),
                'rejected' => Anggota::where('domisili', $admin->domisili)->where('status', 'rejected')->count(),
            ];
            
            $domisiliList = null;
            
        } elseif ($admin->category === 'bpd') {
            // BPD bisa lihat semua anggota
            if ($domisili !== 'all') {
                $query->where('domisili', $domisili);
            }
            
            // Stats untuk BPD
            if ($domisili === 'all') {
                $stats = [
                    'total' => Anggota::count(),
                    'pending' => Anggota::where('status', 'pending')->count(),
                    'approved' => Anggota::where('status', 'approved')->count(),
                    'rejected' => Anggota::where('status', 'rejected')->count(),
                ];
            } else {
                $stats = [
                    'total' => Anggota::where('domisili', $domisili)->count(),
                    'pending' => Anggota::where('domisili', $domisili)->where('status', 'pending')->count(),
                    'approved' => Anggota::where('domisili', $domisili)->where('status', 'approved')->count(),
                    'rejected' => Anggota::where('domisili', $domisili)->where('status', 'rejected')->count(),
                ];
            }
            
            // List domisili untuk dropdown
            $domisiliList = Admin::where('category', 'bpc')
                ->whereNotNull('domisili')
                ->orderBy('domisili')
                ->pluck('domisili')
                ->unique()
                ->values();
        }
        
        // Filter berdasarkan status
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        // Get data dengan pagination
        $anggota = $query->latest()->paginate(15)->appends([
            'status' => $status,
            'domisili' => $domisili
        ]);
        
        return view('admin.anggota.list', compact(
            'anggota',
            'stats',
            'status',
            'domisili',
            'domisiliList'
        ));
    }

    /**
     * Tampilkan detail anggota (READ ONLY)
     */
    public function showAnggota(Anggota $anggota)
    {
        $admin = auth()->guard('admin')->user();
        
        // BPC hanya bisa lihat anggota di domisilinya
        if ($admin->category === 'bpc' && $anggota->domisili !== $admin->domisili) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data ini.');
        }
        
        return view('admin.anggota.show-readonly', compact('anggota'));
    }
    
}