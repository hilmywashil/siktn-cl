<?php

namespace App\Http\Controllers\Admin\Sekretariat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\SuratAuditLog;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminNotification;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SuratController extends Controller
{
    /**
     * Tampilkan Daftar Surat (Masuk & Keluar) dengan 2 Tab Utama (Masuk / Keluar) & 3 Klasifikasi (Internal / Eksternal / Penting)
     */
    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        $tipe = $request->get('tipe', 'masuk'); // masuk, keluar
        if (!in_array($tipe, ['masuk', 'keluar'])) {
            $tipe = 'masuk';
        }

        $klasifikasi = $request->get('klasifikasi', 'internal'); // internal, eksternal, penting
        if (!in_array($klasifikasi, ['internal', 'eksternal', 'penting'])) {
            $klasifikasi = 'internal';
        }

        $query = Surat::query()->where('tipe', $tipe)->where('klasifikasi', $klasifikasi);

        // Filter Status (Pending TTD, Terbit, Revisi, Draft)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('pengirim_tujuan', 'like', "%{$search}%");
            });
        }

        $surats = $query->orderBy('tanggal', 'desc')->paginate(10)->appends($request->query());

        // Count Statistics
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        $totalTerbitBulanIni = Surat::where('status', 'Terbit')
            ->whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->count();

        $totalPendingTTD = Surat::where('status', 'Pending TTD')->count();

        // Counts per Primary & Secondary Tabs
        $countMasuk = Surat::where('tipe', 'masuk')->count();
        $countKeluar = Surat::where('tipe', 'keluar')->count();

        $countInternal = Surat::where('tipe', $tipe)->where('klasifikasi', 'internal')->count();
        $countEksternal = Surat::where('tipe', $tipe)->where('klasifikasi', 'eksternal')->count();
        $countPenting = Surat::where('tipe', $tipe)->where('klasifikasi', 'penting')->count();
        $countPentingPending = Surat::where('tipe', $tipe)->where('klasifikasi', 'penting')->where('status', 'Pending TTD')->count();

        return view('admin.sekretariat.surat.index', [
            'activeMenu' => 'sekretariat_surat_' . $tipe,
            'admin' => $admin,
            'surats' => $surats,
            'tipe' => $tipe,
            'klasifikasi' => $klasifikasi,
            'totalTerbitBulanIni' => $totalTerbitBulanIni,
            'totalPendingTTD' => $totalPendingTTD,
            'countMasuk' => $countMasuk,
            'countKeluar' => $countKeluar,
            'countInternal' => $countInternal,
            'countEksternal' => $countEksternal,
            'countPenting' => $countPenting,
            'countPentingPending' => $countPentingPending,
        ]);
    }

    /**
     * Simpan Surat Baru (Upload File PDF/Word / Drive)
     */
    public function store(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'tipe' => 'required|in:masuk,keluar',
            'klasifikasi' => 'required|in:internal,eksternal,penting',
            'nomor_surat' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'perihal' => 'required|string',
            'pengirim_tujuan' => 'required|string|max:255',
            'file_lampiran' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // Max 10MB
            'link_drive' => 'nullable|url',
        ]);

        $fileLampiranPath = null;
        if ($request->hasFile('file_lampiran')) {
            $fileLampiranPath = $request->file('file_lampiran')->store('surat_lampiran', 'public');
        }

        $status = $request->get('status', 'Pending TTD');
        if (!in_array($status, ['Pending TTD', 'Terbit', 'Revisi', 'Draft'])) {
            $status = 'Pending TTD';
        }

        $surat = Surat::create([
            'tipe' => $validated['tipe'],
            'klasifikasi' => $validated['klasifikasi'],
            'nomor_surat' => $validated['nomor_surat'],
            'tanggal' => $validated['tanggal'],
            'perihal' => $validated['perihal'],
            'pengirim_tujuan' => $validated['pengirim_tujuan'],
            'status' => $status,
            'file_lampiran' => $fileLampiranPath,
            'link_drive' => $validated['link_drive'] ?? null,
            'created_by' => $admin->id,
        ]);

        // Audit Trail Log (12b)
        SuratAuditLog::create([
            'surat_id' => $surat->id,
            'admin_id' => $admin->id,
            'admin_name' => $admin->name,
            'action' => 'Dibuat',
            'new_status' => $status,
            'notes' => "Surat {$surat->tipe} diunggah oleh Sekretariat (" . ($fileLampiranPath ? "File PDF/Word terlampir" : "Drive Link") . ")",
        ]);

        // Notifikasi ke Pimpinan jika status Pending TTD (12a)
        if ($status === 'Pending TTD') {
            $pimpinans = Admin::whereIn('category', ['pimpinan', 'super_admin'])->get();
            if ($pimpinans->count() > 0) {
                Notification::send($pimpinans, new AdminNotification(
                    'surat_pending',
                    'Surat Membutuhkan TTD',
                    "Surat '{$surat->nomor_surat}' ({$surat->perihal}) membutuhkan persetujuan/TTD Pimpinan."
                ));
            }
        }

        return redirect()->route('admin.sekretariat.surat.index', ['tipe' => $surat->tipe, 'klasifikasi' => $surat->klasifikasi])
            ->with('success', "Surat {$surat->tipe} '{$surat->nomor_surat}' berhasil ditambahkan.");
    }

    /**
     * Update Status Surat (Persetujuan Pimpinan: Terbit, Revisi, Draft)
     */
    public function updateStatus(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        $surat = Surat::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Pending TTD,Terbit,Revisi,Draft',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $surat->status;
        $newStatus = $request->status;

        $surat->update([
            'status' => $newStatus,
        ]);

        // Audit Trail Log (12b)
        SuratAuditLog::create([
            'surat_id' => $surat->id,
            'admin_id' => $admin->id,
            'admin_name' => $admin->name,
            'action' => 'Perubahan Status',
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'notes' => $request->notes ?? "Status diubah dari {$oldStatus} ke {$newStatus}",
        ]);

        // Notifikasi ke Sekretariat / Creator ketika Surat Terbit (TTD) atau Revisi
        if (in_array($newStatus, ['Terbit', 'Revisi'])) {
            $recipients = collect();
            if ($surat->created_by) {
                $creator = Admin::find($surat->created_by);
                if ($creator) $recipients->push($creator);
            }
            $pnktAdmins = Admin::whereIn('category', ['pnkt', 'super_admin'])->get();
            $recipients = $recipients->merge($pnktAdmins)->unique('id');

            if ($recipients->count() > 0) {
                if ($newStatus === 'Terbit') {
                    Notification::send($recipients, new AdminNotification(
                        'surat_terbit',
                        'Surat Telah Ditandatangani',
                        "Surat '{$surat->nomor_surat}' ({$surat->perihal}) telah disetujui & ditandatangani oleh Pimpinan."
                    ));
                } elseif ($newStatus === 'Revisi') {
                    $notesText = $request->notes ? ": " . $request->notes : ".";
                    Notification::send($recipients, new AdminNotification(
                        'surat_revisi',
                        'Surat Membutuhkan Revisi',
                        "Surat '{$surat->nomor_surat}' ({$surat->perihal}) membutuhkan revisi{$notesText}"
                    ));
                }
            }
        }

        return redirect()->back()->with('success', "Status surat '{$surat->nomor_surat}' berhasil diubah menjadi {$newStatus}.");
    }

    /**
     * Hapus Surat
     */
    public function destroy($id)
    {
        $admin = Auth::guard('admin')->user();
        $surat = Surat::findOrFail($id);

        // Audit Log sebelum hapus
        SuratAuditLog::create([
            'surat_id' => $surat->id,
            'admin_id' => $admin->id,
            'admin_name' => $admin->name,
            'action' => 'Dihapus',
            'old_status' => $surat->status,
            'notes' => "Surat '{$surat->nomor_surat}' dihapus",
        ]);

        if ($surat->file_lampiran && Storage::disk('public')->exists($surat->file_lampiran)) {
            Storage::disk('public')->delete($surat->file_lampiran);
        }

        $surat->delete();

        return redirect()->back()->with('success', 'Surat berhasil dihapus.');
    }

    /**
     * Detail & Log Audit Trail Surat (JSON for modal view)
     */
    public function auditTrail($id)
    {
        $surat = Surat::with(['auditLogs', 'creator'])->findOrFail($id);
        return response()->json([
            'surat' => $surat,
            'audit_logs' => $surat->auditLogs,
        ]);
    }
}
