<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AnggotaManagementController extends Controller
{
    /**
     * Display a listing of anggota
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = Anggota::query();
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $anggota = $query->latest()->paginate(15);
        
        $stats = [
            'total' => Anggota::count(),
            'pending' => Anggota::where('status', 'pending')->count(),
            'approved' => Anggota::where('status', 'approved')->count(),
            'rejected' => Anggota::where('status', 'rejected')->count(),
        ];
        
        return view('admin.anggota.index', compact('anggota', 'stats', 'status'));
    }

    /**
     * Show detail anggota
     */
    public function show(Anggota $anggota)
    {
        return view('admin.anggota.show', compact('anggota'));
    }

    /**
     * Update data anggota
     */
    public function update(Request $request, Anggota $anggota)
    {
        $validated = $request->validate([
            // Data Perusahaan
            'nama_perusahaan' => 'required|string|max:255',
            'trade_mark' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'email_website_perusahaan' => 'required|email|max:255',
            'telepon_wa_perusahaan' => 'required|string|max:20',
            'alamat_kantor' => 'required|string',
            
            // Data Pimpinan
            'nama_pimpinan' => 'required|string|max:255',
            'email_pimpinan' => 'required|email|max:255',
            'telepon_wa_pimpinan' => 'required|string|max:20',
            'alamat_pimpinan' => 'required|string',
            
            // Legalitas
            'akte_notaris' => 'required|string|max:255',
            'nomor_induk_berusaha_tdup' => 'required|string|max:255',
            'npwp_perusahaan' => 'required|string|max:255',
        ]);

        $anggota->update($validated);

        return redirect()
            ->route('admin.anggota.show', $anggota)
            ->with('success', 'Data anggota berhasil diperbarui!');
    }

    /**
     * Update password anggota
     */
    public function updatePassword(Request $request, Anggota $anggota)
    {
        $request->validate([
            'new_password' => 'required|min:8|confirmed',
        ], [
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.min' => 'Password minimal 8 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        // Update password
        $anggota->update([
            'password' => Hash::make($request->new_password),
            'initial_password' => null, // Hapus initial password
        ]);

        return redirect()
            ->route('admin.anggota.show', $anggota)
            ->with('success', 'Password anggota berhasil diubah!');
    }

    /**
     * Approve anggota
     */
    public function approve(Anggota $anggota)
    {
        $admin = auth()->guard('admin')->user();

        $anggota->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $admin->id,
        ]);

        return redirect()
            ->route('admin.anggota.show', $anggota)
            ->with('success', 'Anggota berhasil disetujui!');
    }

    /**
     * Reject anggota
     */
    public function reject(Request $request, Anggota $anggota)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $admin = auth()->guard('admin')->user();

        $anggota->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => $admin->id,
        ]);

        return redirect()
            ->route('admin.anggota.show', $anggota)
            ->with('success', 'Pendaftaran anggota ditolak.');
    }

    /**
     * Delete anggota
     */
    public function destroy(Anggota $anggota)
    {
        // Hapus file-file yang terkait
        $files = [
            $anggota->surat_permohonan,
            $anggota->akte_pendirian_perusahaan,
            $anggota->nib_atau_tdup,
            $anggota->ktp_pimpinan,
            $anggota->npwp_perusahaan_file,
        ];

        foreach ($files as $file) {
            if ($file && \Storage::disk('public')->exists($file)) {
                \Storage::disk('public')->delete($file);
            }
        }

        $anggota->delete();

        return redirect()
            ->route('admin.anggota.index')
            ->with('success', 'Data anggota berhasil dihapus!');
    }
}