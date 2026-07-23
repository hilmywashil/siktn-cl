<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Traits\LogsAdminActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TrashController extends Controller
{
    use LogsAdminActivity;
    /**
     * Tampilkan data sampah (hanya untuk Super Admin)
     */
    public function index()
    {
        // Pastikan hanya super_admin yang bisa akses
        if (!auth('admin')->user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Fitur ini hanya untuk Super Admin.');
        }

        // Ambil data anggota yang di-soft-delete
        $anggotas = Anggota::onlyTrashed()->orderBy('deleted_at', 'desc')->get();

        return view('admin.trash.index', compact('anggotas'));
    }

    /**
     * Restore banyak anggota sekaligus (Bulk Restore)
     */
    public function bulkRestoreAnggota(Request $request)
    {
        if (!auth('admin')->user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Fitur ini hanya untuk Super Admin.');
        }

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer'
        ]);

        $restoredCount = count($request->ids);
        Anggota::onlyTrashed()->whereIn('id', $request->ids)->restore();

        $this->logActivity('anggota', 'Restore Massal', null, "{$restoredCount} data anggota");

        return response()->json([
            'status' => 'success',
            'message' => $restoredCount . ' data anggota berhasil dikembalikan.'
        ]);
    }

    /**
     * Hapus permanen banyak anggota sekaligus (Bulk Force Delete)
     */
    public function bulkForceDeleteAnggota(Request $request)
    {
        if (!auth('admin')->user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Fitur ini hanya untuk Super Admin.');
        }

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer'
        ]);

        $anggotas = Anggota::onlyTrashed()->whereIn('id', $request->ids)->get();

        foreach ($anggotas as $anggota) {
            // Hapus file-file yang terkait
            $files = [
                $anggota->surat_permohonan,
                $anggota->akte_pendirian_perusahaan,
                $anggota->nib_atau_tdup,
                $anggota->ktp_pimpinan,
                $anggota->npwp_perusahaan_file,
                $anggota->foto_diri,
                $anggota->foto_ktp,
            ];

            foreach ($files as $file) {
                if ($file && Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            }

            $anggota->forceDelete();
        }

        $forcedCount = count($request->ids);

        $this->logActivity('anggota', 'Hapus Permanen Massal', null, "{$forcedCount} data anggota");

        return response()->json([
            'status' => 'deleted',
            'message' => $forcedCount . ' data anggota berhasil dihapus permanen.'
        ]);
    }
}
