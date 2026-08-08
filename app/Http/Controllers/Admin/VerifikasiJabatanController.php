<?php
// app/Http/Controllers/Admin/VerifikasiJabatanController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Organisasi;
use App\Models\Jabatan;
use App\Helpers\WilayahHelper;
use App\Traits\LogsAdminActivity;
use Illuminate\Http\Request;

class VerifikasiJabatanController extends Controller
{
    use LogsAdminActivity;

    public function index(Request $request)
    {
        $currentUser = auth()->guard('admin')->user();
        
        // Hanya Super Admin & PNKT yang boleh mengakses verifikasi
        if (!$currentUser->isSuperAdmin() && !$currentUser->isPNKT() && $currentUser->category !== 'pnkt') {
            abort(403, 'Anda tidak memiliki wewenang untuk memverifikasi pengajuan jabatan.');
        }

        $query = Admin::where('status_jabatan', '!=', 'none')
            ->whereNotNull('jabatan_diajukan');

        if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status_jabatan', $request->status);
        }

        $pengajuans = $query->orderByRaw("FIELD(status_jabatan, 'pending', 'approved', 'rejected')")
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('admin.organisasi.verifikasi', compact('pengajuans'));
    }

    public function approve(Request $request, Admin $admin)
    {
        $currentUser = auth()->guard('admin')->user();
        
        if (!$currentUser->isSuperAdmin() && !$currentUser->isPNKT() && $currentUser->category !== 'pnkt') {
            abort(403, 'Akses ditolak.');
        }

        if (empty($admin->jabatan_diajukan)) {
            return back()->with('error', 'Admin ini belum mengajukan jabatan.');
        }

        $jabatanNama = $admin->jabatan_diajukan;

        // Update status Admin
        $admin->update([
            'status_jabatan' => 'approved',
            'keterangan_jabatan' => 'Pengajuan disetujui oleh ' . $currentUser->name . ' (' . strtoupper($currentUser->category) . ')',
        ]);

        // Auto-assign into Organisasi table
        $this->assignAdminToOrganisasi($admin, $jabatanNama);

        try {
            $admin->notify(new \App\Notifications\AdminNotification(
                'verifikasi_jabatan',
                'Pengajuan Jabatan Disetujui',
                'Pengajuan posisi ' . $jabatanNama . ' Anda telah disetujui oleh Pusat.',
                ['url' => route('admin.profile')]
            ));
        } catch (\Throwable $e) {}

        $this->logActivity('organisasi', 'ACC Jabatan Admin', $admin->id, $admin->name, "Jabatan '{$jabatanNama}' disetujui untuk {$admin->name}");

        return back()->with('success', "Pengajuan jabatan '{$jabatanNama}' untuk {$admin->name} berhasil di-ACC dan terpasang di Bagan Struktur Organisasi!");
    }

    public function reject(Request $request, Admin $admin)
    {
        $currentUser = auth()->guard('admin')->user();

        if (!$currentUser->isSuperAdmin() && !$currentUser->isPNKT() && $currentUser->category !== 'pnkt') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'alasan' => 'nullable|string|max:550',
        ]);

        $alasan = $request->input('alasan', 'Pengajuan jabatan ditolak oleh Pengurus Pusat.');

        $admin->update([
            'status_jabatan' => 'rejected',
            'keterangan_jabatan' => 'Ditolak: ' . $alasan,
        ]);

        try {
            $admin->notify(new \App\Notifications\AdminNotification(
                'verifikasi_jabatan',
                'Pengajuan Jabatan Ditolak',
                'Pengajuan posisi Anda ditolak. Catatan: ' . $alasan,
                ['url' => route('admin.profile')]
            ));
        } catch (\Throwable $e) {}

        $this->logActivity('organisasi', 'Tolak Jabatan Admin', $admin->id, $admin->name, "Pengajuan jabatan {$admin->name} ditolak. Catatan: {$alasan}");

        return back()->with('success', "Pengajuan jabatan untuk {$admin->name} telah ditolak.");
    }

    private function assignAdminToOrganisasi(Admin $admin, string $namaJabatan)
    {
        $jabatanObj = Jabatan::where('nama_jabatan', $namaJabatan)->first();
        $urutan = $jabatanObj ? $jabatanObj->urutan : '99';

        // Determine Wilayah (Provinsi & Kabupaten) based on admin domisili & role
        $provinsi = 'Nasional';
        $kabupaten = null;

        if ($admin->isPPKT() || $admin->category === 'bpd') {
            $provinsi = $admin->domisili ?? 'Jawa Barat';
        } elseif ($admin->isPKKT() || $admin->category === 'bpc') {
            $kabupaten = $admin->domisili ?? 'Kabupaten Bandung';
            $provinsi = WilayahHelper::getProvinsiFromDomisili($kabupaten);
        }

        // Cari atau buat di tabel organisasi
        $org = Organisasi::where('provinsi', $provinsi)
            ->where('kabupaten', $kabupaten)
            ->where(function($q) use ($namaJabatan, $urutan) {
                $q->where('urutan', $urutan)->orWhere('jabatan', $namaJabatan);
            })->first();

        if ($org) {
            $org->update([
                'nama' => $admin->name,
                'jabatan' => $namaJabatan,
                'foto' => $admin->photo ?? $admin->foto_profil,
                'aktif' => true,
            ]);
        } else {
            Organisasi::create([
                'nama' => $admin->name,
                'jabatan' => $namaJabatan,
                'urutan' => (string) $urutan,
                'kategori' => 'Pengurus Harian',
                'provinsi' => $provinsi,
                'kabupaten' => $kabupaten,
                'foto' => $admin->photo ?? $admin->foto_profil,
                'aktif' => true,
            ]);
        }
    }
}
