<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TemuKarya;
use App\Traits\LogsAdminActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Admin;
use App\Notifications\AdminNotification;
use Illuminate\Support\Facades\Notification;

class TemuKaryaController extends Controller
{
    use LogsAdminActivity;
    private function checkAuthorization()
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin || !($admin->isSuperAdmin() || $admin->isPimpinan() || $admin->isPNKT() || $admin->isPPKT() || $admin->isPKKT())) {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang untuk mengakses modul ini.');
        }
    }

    public function index(Request $request)
    {
        $this->checkAuthorization();
        $admin = Auth::guard('admin')->user();

        $query = TemuKarya::with('suratKeputusan');

        // Regional filter for PPKT / PKKT
        if (in_array($admin->category, ['ppkt', 'pkkt']) && !empty($admin->domisili)) {
            if ($admin->category === 'ppkt') {
                $query->where('wilayah', 'LIKE', "%{$admin->domisili}%");
            } else {
                $query->where('wilayah', $admin->domisili);
            }
        }

        // Filter Level Wilayah (Provinsi / Kab/Kota)
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Filter Jenis (temu_karya / caretaker)
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('wilayah', 'LIKE', "%{$search}%")
                  ->orWhere('lokasi', 'LIKE', "%{$search}%")
                  ->orWhere('catatan', 'LIKE', "%{$search}%");
            });
        }

        $temuKaryas = $query->latest()->get();
        $masterSks = \App\Models\SuratKeputusan::orderBy('created_at', 'desc')->get();

        // Summary Statistics
        $totalSelesai = TemuKarya::where('status', 'selesai')->count();
        $totalCaretaker = TemuKarya::where('jenis', 'caretaker')->orWhere('status', 'caretaker')->count();
        $totalPending = TemuKarya::where('status', 'pending')->count();

        // Asumsi total wilayah Karang Taruna Indonesia (38 Provinsi)
        $totalWilayahNasional = 38;
        $totalBelumTemuKarya = max(0, $totalWilayahNasional - $totalSelesai);

        return view('admin.organisasi.temu-karya', [
            'activeMenu' => 'organisasi',
            'activeSubMenu' => $request->jenis === 'caretaker' ? 'caretaker' : 'temu-karya',
            'temuKaryas' => $temuKaryas,
            'masterSks' => $masterSks,
            'totalSelesai' => $totalSelesai,
            'totalCaretaker' => $totalCaretaker,
            'totalPending' => $totalPending,
            'totalBelumTemuKarya' => $totalBelumTemuKarya,
        ]);
    }

    public function store(Request $request)
    {
        $this->checkAuthorization();

        $validated = $request->validate([
            'jenis' => 'required|in:temu_karya,caretaker',
            'wilayah' => 'required|string|max:255',
            'level' => 'required|in:provinsi,kab_kota',
            'tanggal_pelaksanaan' => 'nullable|date',
            'lokasi' => 'nullable|string|max:255',
            'jumlah_peserta' => 'nullable|integer|min:0',
            'catatan' => 'nullable|string',
            'status' => 'required|in:selesai,pending,caretaker',
            'foto_dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'file_sk' => 'nullable|mimes:pdf,doc,docx,jpg,png|max:5120',
            'link_drive' => 'nullable|url|max:500',
            'surat_keputusan_id' => 'nullable|exists:surat_keputusans,id',
        ]);

        if ($request->hasFile('foto_dokumentasi')) {
            $validated['foto_dokumentasi'] = $request->file('foto_dokumentasi')->store('temu_karya/foto', 'public');
        }

        if ($request->hasFile('file_sk')) {
            $validated['file_sk'] = $request->file('file_sk')->store('temu_karya/sk', 'public');
        }

        $validated['created_by'] = Auth::guard('admin')->id();
        $validated['jumlah_peserta'] = $validated['jumlah_peserta'] ?? 0;

        $tk = TemuKarya::create($validated);

        $this->logActivity('temu-karya', 'Tambah', $tk->id, $tk->wilayah, 'Jenis: ' . $tk->jenis);

        // Notifikasi ke Pimpinan jika status Pending
        if ($tk->status === 'pending') {
            $pimpinans = Admin::whereIn('category', ['pimpinan', 'super_admin'])->get();
            if ($pimpinans->count() > 0) {
                Notification::send($pimpinans, new AdminNotification(
                    'surat_pending', // Using generic pending type so it shows in notif badge
                    'Pelaporan Temu Karya Pending',
                    "Laporan Temu Karya/Caretaker untuk wilayah {$tk->wilayah} berstatus Pending dan menunggu peninjauan."
                ));
            }
        }

        return redirect()->back()->with('success', 'Data Temu Karya / Caretaker berhasil ditambahkan.');
    }

    public function update(Request $request, TemuKarya $temuKarya)
    {
        $this->checkAuthorization();

        $validated = $request->validate([
            'jenis' => 'required|in:temu_karya,caretaker',
            'wilayah' => 'required|string|max:255',
            'level' => 'required|in:provinsi,kab_kota',
            'tanggal_pelaksanaan' => 'nullable|date',
            'lokasi' => 'nullable|string|max:255',
            'jumlah_peserta' => 'nullable|integer|min:0',
            'catatan' => 'nullable|string',
            'status' => 'required|in:selesai,pending,caretaker',
            'foto_dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'file_sk' => 'nullable|mimes:pdf,doc,docx,jpg,png|max:5120',
            'link_drive' => 'nullable|url|max:500',
            'surat_keputusan_id' => 'nullable|exists:surat_keputusans,id',
        ]);

        if ($request->hasFile('foto_dokumentasi')) {
            if ($temuKarya->foto_dokumentasi) {
                Storage::disk('public')->delete($temuKarya->foto_dokumentasi);
            }
            $validated['foto_dokumentasi'] = $request->file('foto_dokumentasi')->store('temu_karya/foto', 'public');
        }

        if ($request->hasFile('file_sk')) {
            if ($temuKarya->file_sk) {
                Storage::disk('public')->delete($temuKarya->file_sk);
            }
            $validated['file_sk'] = $request->file('file_sk')->store('temu_karya/sk', 'public');
        }

        $validated['jumlah_peserta'] = $validated['jumlah_peserta'] ?? 0;

        $temuKarya->update($validated);

        $this->logActivity('temu-karya', 'Edit', $temuKarya->id, $temuKarya->wilayah, 'Status: ' . $temuKarya->status);

        // Notifikasi ke Pimpinan jika status diubah ke Pending
        if ($temuKarya->status === 'pending') {
            $pimpinans = Admin::whereIn('category', ['pimpinan', 'super_admin'])->get();
            if ($pimpinans->count() > 0) {
                Notification::send($pimpinans, new AdminNotification(
                    'surat_pending', // Using generic pending type so it shows in notif badge
                    'Pelaporan Temu Karya Pending',
                    "Laporan Temu Karya/Caretaker untuk wilayah {$temuKarya->wilayah} berstatus Pending dan menunggu peninjauan."
                ));
            }
        }

        return redirect()->back()->with('success', 'Data Temu Karya / Caretaker berhasil diperbarui.');
    }

    public function destroy(TemuKarya $temuKarya)
    {
        $this->checkAuthorization();

        if ($temuKarya->foto_dokumentasi) {
            Storage::disk('public')->delete($temuKarya->foto_dokumentasi);
        }
        if ($temuKarya->file_sk) {
            Storage::disk('public')->delete($temuKarya->file_sk);
        }

        $label = $temuKarya->wilayah;
        $tkId = $temuKarya->id;
        $temuKarya->delete();

        $this->logActivity('temu-karya', 'Hapus', $tkId, $label);

        return redirect()->back()->with('success', 'Data Temu Karya / Caretaker berhasil dihapus.');
    }
}
