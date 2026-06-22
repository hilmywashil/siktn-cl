<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use Illuminate\Http\Request;

class UmkmManagementController extends Controller
{
    /**
     * Display a listing of UMKM submissions
     */
    public function index(Request $request)
    {
        $query = Umkm::query();

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_usaha', 'like', "%{$search}%")
                    ->orWhere('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nomor_hp', 'like', "%{$search}%");
            });
        }

        // Sort by latest
        $umkms = $query->latest()->paginate(10);

        // Statistics
        $stats = [
            'total' => Umkm::count(),
            'pending' => Umkm::where('status', 'pending')->count(),
            'approved' => Umkm::where('status', 'approved')->count(),
            'rejected' => Umkm::where('status', 'rejected')->count(),
        ];

        return view('admin.umkm.index', compact('umkms', 'stats'));
    }

    /**
     * Display the specified UMKM
     */
    public function show($id)
    {
        $umkm = Umkm::findOrFail($id);
        return view('admin.umkm.show', compact('umkm'));
    }

    /**
     * Approve UMKM
     */
    public function approve($id)
    {
        $umkm = Umkm::findOrFail($id);
        
        $umkm->update([
            'status' => 'approved',
            'verified_at' => now(),
            'verified_by' => auth()->guard('admin')->id()
        ]);

        return redirect()->back()->with('success', 'UMKM berhasil disetujui!');
    }

    /**
     * Reject UMKM
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $umkm = Umkm::findOrFail($id);
        
        $umkm->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'verified_at' => now(),
            'verified_by' => auth()->guard('admin')->id()
        ]);

        return redirect()->back()->with('success', 'UMKM berhasil ditolak!');
    }

    /**
     * Delete UMKM
     */
    public function destroy($id)
    {
        $umkm = Umkm::findOrFail($id);
        $umkm->delete();

        return redirect()->route('admin.umkm.index')->with('success', 'Data UMKM berhasil dihapus!');
    }

    /**
     * Export UMKM data to CSV
     */
    public function export(Request $request)
    {
        $query = Umkm::query();

        // Apply same filters as index page
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_usaha', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nomor_hp', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $umkms = $query->orderBy('created_at', 'desc')->get();

        // Set filename dengan timestamp
        $filename = 'umkm_export_' . date('Y-m-d_His') . '.csv';
        
        // Headers untuk CSV download
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        $callback = function() use ($umkms) {
            $file = fopen('php://output', 'w');
            
            // Add BOM untuk proper UTF-8 encoding di Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // CSV Headers
            fputcsv($file, [
                'No',
                'Nama Usaha',
                'Bidang Usaha',
                'Status Legalitas',
                'Jenis Legalitas',
                'Tahun Berdiri',
                'Nama Lengkap Pemilik',
                'Jenis Kelamin',
                'Tanggal Lahir',
                'Nomor HP',
                'Email',
                'Alamat Domisili',
                'Platform Digital',
                'Platform',
                'Pendapatan',
                'Pembiayaan',
                'Sumber Pembiayaan',
                'Tujuan',
                'Pelatihan',
                'Status Verifikasi',
                'Alasan Penolakan',
                'Tanggal Daftar',
                'Tanggal Verifikasi'
            ]);

            // CSV Data
            foreach ($umkms as $index => $umkm) {
                fputcsv($file, [
                    $index + 1,
                    $umkm->nama_usaha,
                    $umkm->bidang_usaha,
                    $umkm->status_legalitas ?? '-',
                    $umkm->jenis_legalitas ?? '-',
                    $umkm->tahun_berdiri,
                    $umkm->nama_lengkap,
                    $umkm->jenis_kelamin,
                    $umkm->tanggal_lahir ? date('d-m-Y', strtotime($umkm->tanggal_lahir)) : '-',
                    $umkm->nomor_hp,
                    $umkm->email,
                    $umkm->alamat_domisili,
                    $umkm->platform_digital ?? '-',
                    is_array($umkm->platform) ? implode(', ', $umkm->platform) : $umkm->platform,
                    $umkm->pendapatan ?? '-',
                    $umkm->pembiayaan ?? '-',
                    $umkm->sumber_pembiayaan ?? '-',
                    $umkm->tujuan ?? '-',
                    $umkm->pelatihan ?? '-',
                    ucfirst($umkm->status),
                    $umkm->rejection_reason ?? '-',
                    $umkm->created_at->format('d-m-Y H:i:s'),
                    $umkm->verified_at ? $umkm->verified_at->format('d-m-Y H:i:s') : '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}