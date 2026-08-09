<?php

namespace App\Http\Controllers\Admin\Sekretariat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\SuratAuditLog;
use App\Traits\LogsAdminActivity;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminNotification;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SuratController extends Controller
{
    use LogsAdminActivity;
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

        // Regional Access Scope (PPKT & PKKT)
        if (!$admin->isSuperAdmin() && !$admin->isPimpinan() && !$admin->isPNKT() && !empty($admin->domisili)) {
            $query->where(function($q) use ($admin) {
                $q->where('created_by', $admin->id)
                  ->orWhereHas('creator', function($cq) use ($admin) {
                      $cq->where('domisili', $admin->domisili);
                  });
            });
        }

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

        // Count Statistics (Scoped)
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $statsQuery = Surat::query();
        if (!$admin->isSuperAdmin() && !$admin->isPimpinan() && !$admin->isPNKT() && !empty($admin->domisili)) {
            $statsQuery->where(function($q) use ($admin) {
                $q->where('created_by', $admin->id)
                  ->orWhereHas('creator', function($cq) use ($admin) {
                      $cq->where('domisili', $admin->domisili);
                  });
            });
        }
        
        $totalTerbitBulanIni = (clone $statsQuery)->where('status', 'Terbit')
            ->whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->count();

        $totalPendingTTD = (clone $statsQuery)->where('status', 'Pending TTD')->count();

        // Counts per Primary & Secondary Tabs (Scoped)
        $countMasuk = (clone $statsQuery)->where('tipe', 'masuk')->count();
        $countKeluar = (clone $statsQuery)->where('tipe', 'keluar')->count();

        $countInternal = (clone $statsQuery)->where('tipe', $tipe)->where('klasifikasi', 'internal')->count();
        $countEksternal = (clone $statsQuery)->where('tipe', $tipe)->where('klasifikasi', 'eksternal')->count();
        $countPenting = (clone $statsQuery)->where('tipe', $tipe)->where('klasifikasi', 'penting')->count();
        $countPentingPending = (clone $statsQuery)->where('tipe', $tipe)->where('klasifikasi', 'penting')->where('status', 'Pending TTD')->count();

        $existingNomorSurats = Surat::pluck('nomor_surat')->toArray();

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
            'existingNomorSurats' => $existingNomorSurats,
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

        $this->logActivity('surat', 'Tambah', $surat->id, $surat->nomor_surat . ' - ' . $surat->perihal, 'Status: ' . $status);

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

        $this->logActivity('surat', 'Ubah Status', $surat->id, $surat->nomor_surat . ' - ' . $surat->perihal, "Status: {$oldStatus} -> {$newStatus}");

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
        } elseif ($newStatus === 'Pending TTD') {
            // Notifikasi ke Pimpinan saat diubah dari Draft ke Pending TTD
            $pimpinans = Admin::whereIn('category', ['pimpinan', 'super_admin'])->get();
            if ($pimpinans->count() > 0) {
                Notification::send($pimpinans, new AdminNotification(
                    'surat_pending',
                    'Surat Membutuhkan TTD',
                    "Surat '{$surat->nomor_surat}' ({$surat->perihal}) baru saja diajukan dan membutuhkan persetujuan/TTD Pimpinan."
                ));
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Status surat '{$surat->nomor_surat}' berhasil diubah menjadi {$newStatus}.",
                'surat' => $surat,
            ]);
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

        $this->logActivity('surat', 'Hapus', $surat->id, $surat->nomor_surat . ' - ' . $surat->perihal);

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

        $fileUrl = null;
        $isWord = false;
        $isPdf = false;

        if ($surat->file_lampiran) {
            $fileUrl = asset('storage/' . $surat->file_lampiran);
            $ext = strtolower(pathinfo($surat->file_lampiran, PATHINFO_EXTENSION));
            if (in_array($ext, ['doc', 'docx'])) {
                $isWord = true;
            } elseif ($ext === 'pdf') {
                $isPdf = true;
            }
        }

        return response()->json([
            'surat' => $surat,
            'audit_logs' => $surat->auditLogs,
            'file_url' => $fileUrl,
            'is_word' => $isWord,
            'is_pdf' => $isPdf,
        ]);
    }

    /**
     * Feed Notifikasi Surat Keluar 3 Kategori (Internal, Eksternal, Penting) untuk Dropdown Topbar
     */
    public function notificationFeed()
    {
        $internalSurats = Surat::where('klasifikasi', 'internal')
            ->where('status', 'Pending TTD')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $eksternalSurats = Surat::where('klasifikasi', 'eksternal')
            ->where('status', 'Pending TTD')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $pentingSurats = Surat::where('klasifikasi', 'penting')
            ->where('status', 'Pending TTD')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $pendingCount = Surat::where('status', 'Pending TTD')
            ->count();

        $formatSurat = function($s) {
            $fileUrl = $s->file_lampiran ? asset('storage/' . $s->file_lampiran) : null;
            $ext = $s->file_lampiran ? strtolower(pathinfo($s->file_lampiran, PATHINFO_EXTENSION)) : '';
            return [
                'id' => $s->id,
                'nomor_surat' => $s->nomor_surat,
                'perihal' => $s->perihal,
                'pengirim_tujuan' => $s->pengirim_tujuan,
                'status' => $s->status,
                'klasifikasi' => $s->klasifikasi,
                'tanggal' => Carbon::parse($s->tanggal)->translatedFormat('d M Y'),
                'file_url' => $fileUrl,
                'link_drive' => $s->link_drive,
                'is_pdf' => $ext === 'pdf',
                'is_word' => in_array($ext, ['doc', 'docx']),
            ];
        };

        return response()->json([
            'pending_count' => $pendingCount,
            'internal' => $internalSurats->map($formatSurat),
            'eksternal' => $eksternalSurats->map($formatSurat),
            'penting' => $pentingSurats->map($formatSurat),
            'counts' => [
                'internal' => Surat::where('klasifikasi', 'internal')->where('status', 'Pending TTD')->count(),
                'eksternal' => Surat::where('klasifikasi', 'eksternal')->where('status', 'Pending TTD')->count(),
                'penting' => Surat::where('klasifikasi', 'penting')->where('status', 'Pending TTD')->count(),
            ]
        ]);
    }

    /**
     * Upload File Surat Bertanda Tangan Baru (File Ber-TTD) dari Slide-over
     */
    public function uploadSigned(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        $surat = Surat::findOrFail($id);

        $request->validate([
            'signed_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        // Hapus file lama jika ada
        if ($surat->file_lampiran && Storage::disk('public')->exists($surat->file_lampiran)) {
            Storage::disk('public')->delete($surat->file_lampiran);
        }

        $newPath = $request->file('signed_file')->store('surat_lampiran', 'public');
        $oldStatus = $surat->status;

        $surat->update([
            'file_lampiran' => $newPath,
            'status' => 'Terbit',
        ]);

        // Audit Trail Log
        SuratAuditLog::create([
            'surat_id' => $surat->id,
            'admin_id' => $admin->id,
            'admin_name' => $admin->name,
            'action' => 'Upload File Ber-TTD',
            'old_status' => $oldStatus,
            'new_status' => 'Terbit',
            'notes' => "File Surat bertanda tangan baru diunggah oleh {$admin->name}. Status otomatis berubah menjadi Terbit.",
        ]);

        $this->logActivity('surat', 'Upload TTD', $surat->id, $surat->nomor_surat . ' - ' . $surat->perihal, 'Upload file ber-TTD');

        $ext = strtolower(pathinfo($newPath, PATHINFO_EXTENSION));

        return response()->json([
            'success' => true,
            'message' => "File bertanda tangan untuk Surat '{$surat->nomor_surat}' berhasil di-upload!",
            'file_url' => asset('storage/' . $newPath),
            'is_pdf' => $ext === 'pdf',
            'is_word' => in_array($ext, ['doc', 'docx']),
            'status' => 'Terbit',
        ]);
    }

    /**
     * Hapus Banyak Surat (Bulk Delete)
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:surats,id',
        ]);

        $items = Surat::whereIn('id', $request->ids)->get();
        $count = $items->count();

        foreach ($items as $surat) {
            if ($surat->file_lampiran) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($surat->file_lampiran);
            }
            $this->logActivity('surat', 'Hapus (Bulk)', $surat->id, $surat->nomor_surat . ' - ' . $surat->perihal);
            $surat->delete();
        }

        return redirect()->back()->with('success', "{$count} surat berhasil dihapus.");
    }

    /**
     * Download Banyak Surat sekaligus (Bulk Download ZIP)
     */
    public function bulkDownload(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:surats,id',
        ]);

        $surats = Surat::whereIn('id', $request->ids)->get();
        if ($surats->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada surat terpilih.');
        }

        $zipName = 'surat-terpilih-' . time() . '.zip';
        $tempDir = storage_path('app/public/temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }
        $zipPath = $tempDir . '/' . $zipName;

        $zip = new \ZipArchive;
        $fileCount = 0;
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            foreach ($surats as $surat) {
                if ($surat->file_lampiran && \Illuminate\Support\Facades\Storage::disk('public')->exists($surat->file_lampiran)) {
                    $fullPath = \Illuminate\Support\Facades\Storage::disk('public')->path($surat->file_lampiran);
                    $extension = pathinfo($fullPath, PATHINFO_EXTENSION);
                    $cleanName = \Illuminate\Support\Str::slug($surat->nomor_surat . '-' . $surat->perihal);
                    $inZipName = $cleanName . '.' . $extension;
                    $zip->addFile($fullPath, $inZipName);
                    $fileCount++;
                }
            }
            $zip->close();
        }

        if ($fileCount === 0) {
            if (file_exists($zipPath)) unlink($zipPath);
            return redirect()->back()->with('error', 'Surat yang dipilih tidak memiliki berkas lampiran terunggah.');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    /**
     * Download Template Contoh Excel Import Surat Masuk/Keluar
     */
    public function downloadTemplate(Request $request)
    {
        $tipe = $request->get('tipe', 'masuk');
        if (!in_array($tipe, ['masuk', 'keluar'])) $tipe = 'masuk';

        $fileName = $tipe === 'masuk' ? 'Template_Import_Surat_Masuk.xls' : 'Template_Import_Surat_Keluar.xls';
        $filePath = public_path('templates/' . $fileName);
        $fallbackPath = public_path('templates/TemplateSuratMasuk.xls');
        $rootPath = base_path('TemplateSuratMasuk.xls');

        if (file_exists($filePath)) {
            return response()->download($filePath, $fileName, [
                'Content-Type' => 'application/vnd.ms-excel',
            ]);
        } elseif (file_exists($fallbackPath)) {
            return response()->download($fallbackPath, $fileName, [
                'Content-Type' => 'application/vnd.ms-excel',
            ]);
        } elseif (file_exists($rootPath)) {
            return response()->download($rootPath, $fileName, [
                'Content-Type' => 'application/vnd.ms-excel',
            ]);
        }

        $labelPengirim = $tipe === 'masuk' ? 'PENGIRIM' : 'TUJUAN';
        $html = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
        $html .= '<head><meta charset="utf-8"></head><body>';
        $html .= '<table style="font-family: Arial, sans-serif; border-collapse: collapse;">';
        $html .= '<tr><td colspan="9" style="text-align: center; font-size: 14pt; font-weight: bold; padding: 10px; color: #022648; background-color: #f1f5f9;">TEMPLATE IMPORT SURAT ' . strtoupper($tipe) . ' - SIKTN</td></tr>';
        $html .= '<thead><tr style="background-color: #022648; color: #b7830f; font-weight: bold; text-align: center;">';
        $html .= '<th>NO</th><th>NOMOR SURAT</th><th>PERIHAL</th><th>' . $labelPengirim . '</th><th>TANGGAL SURAT</th><th>KLASIFIKASI</th><th>STATUS</th><th>LINK GOOGLE DRIVE</th><th>KETERANGAN</th>';
        $html .= '</tr></thead><tbody>';
        $html .= '<tr style="background-color: #f8fafc;">';
        $html .= '<td>1</td><td>001/SRT-' . strtoupper(substr($tipe, 0, 1)) . '/PNKT/VIII/2026</td><td>Permohonan Audiensi Karang Taruna</td><td>Kementerian Pemuda dan Olahraga</td><td>2026-08-01</td><td>internal</td><td>Terbit</td><td>https://drive.google.com/file/d/example/view</td><td>Surat resmi organisasi</td>';
        $html .= '</tr></tbody></table></body></html>';

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /**
     * Import Data Massal Surat via Excel (JSON parsed from SheetJS)
     */
    public function import(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'surat_rows' => 'required|string',
            'tipe' => 'required|in:masuk,keluar',
        ]);

        $rows = json_decode($request->surat_rows, true);
        if (!is_array($rows) || empty($rows)) {
            return redirect()->back()->with('error', 'Format data tidak valid atau berkas kosong.');
        }

        $importedCount = 0;
        $tipe = $request->tipe;

        foreach ($rows as $row) {
            $nomorSurat = trim($row['nomor_surat'] ?? $row['nomor'] ?? '');
            $perihal = trim($row['perihal'] ?? '');
            $pengirimTujuan = trim($row['pengirim_tujuan'] ?? $row['pengirim'] ?? $row['tujuan'] ?? '');
            $rawTanggal = trim($row['tanggal'] ?? '');
            $klasifikasi = strtolower(trim($row['klasifikasi'] ?? 'internal'));
            $status = trim($row['status'] ?? 'Terbit');
            $linkDrive = trim($row['link_drive'] ?? $row['link'] ?? '');

            if (empty($nomorSurat) || empty($perihal)) continue;
            if (in_array(strtolower($nomorSurat), ['nomor surat', 'no', 'nomor'])) continue;

            if (!in_array($klasifikasi, ['internal', 'eksternal', 'penting'])) {
                $klasifikasi = 'internal';
            }
            if (!in_array($status, ['Pending TTD', 'Terbit', 'Revisi', 'Draft'])) {
                $status = 'Terbit';
            }

            $tanggal = $this->parseDateToYmd($rawTanggal, date('Y-m-d'));

            $surat = Surat::create([
                'tipe' => $tipe,
                'klasifikasi' => $klasifikasi,
                'nomor_surat' => $nomorSurat,
                'tanggal' => $tanggal,
                'perihal' => $perihal,
                'pengirim_tujuan' => $pengirimTujuan ?: 'Sekretariat',
                'status' => $status,
                'link_drive' => $linkDrive ?: null,
                'created_by' => $admin->id,
            ]);

            SuratAuditLog::create([
                'surat_id' => $surat->id,
                'admin_id' => $admin->id,
                'admin_name' => $admin->name,
                'action' => 'Import Excel',
                'new_status' => $status,
                'notes' => "Surat {$tipe} di-import secara massal via Excel",
            ]);

            $importedCount++;
        }

        $this->logActivity('surat', 'Import Excel', null, "Berhasil mengimport {$importedCount} Surat {$tipe} baru");

        return redirect()->route('admin.sekretariat.surat.index', ['tipe' => $tipe])
            ->with('success', "Berhasil meng-import {$importedCount} Surat " . ucfirst($tipe) . " baru!");
    }

    /**
     * Bulk Store Multiple PDF Surat Files with Individual Metadata
     */
    public function bulkStore(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'tipe' => 'required|in:masuk,keluar',
            'surats' => 'required|array|min:1',
            'surats.*.nomor_surat' => 'required|string|max:255',
            'surats.*.perihal' => 'required|string',
            'surats.*.pengirim_tujuan' => 'required|string|max:255',
            'surats.*.tanggal' => 'required|date',
            'surats.*.klasifikasi' => 'required|in:internal,eksternal,penting',
            'surats.*.status' => 'required|in:Pending TTD,Terbit,Revisi,Draft',
            'files' => 'nullable|array',
            'files.*' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $tipe = $request->tipe;
        $items = $request->surats;
        $files = $request->file('files') ?? [];
        $savedCount = 0;

        foreach ($items as $idx => $item) {
            $fileLampiranPath = null;
            if (isset($files[$idx]) && $files[$idx]->isValid()) {
                $fileLampiranPath = $files[$idx]->store('surat_lampiran', 'public');
            }

            $surat = Surat::create([
                'tipe' => $tipe,
                'klasifikasi' => $item['klasifikasi'],
                'nomor_surat' => $item['nomor_surat'],
                'tanggal' => $item['tanggal'],
                'perihal' => $item['perihal'],
                'pengirim_tujuan' => $item['pengirim_tujuan'],
                'status' => $item['status'],
                'file_lampiran' => $fileLampiranPath,
                'link_drive' => $item['link_drive'] ?? null,
                'created_by' => $admin->id,
            ]);

            SuratAuditLog::create([
                'surat_id' => $surat->id,
                'admin_id' => $admin->id,
                'admin_name' => $admin->name,
                'action' => 'Bulk Upload Multi-PDF',
                'new_status' => $item['status'],
                'notes' => "Surat {$tipe} diunggah via Bulk Upload PDF",
            ]);

            $savedCount++;
        }

        $this->logActivity('surat', 'Bulk Upload Multi-PDF', null, "Berhasil membuat {$savedCount} Surat {$tipe} baru via Bulk Upload");

        return redirect()->route('admin.sekretariat.surat.index', ['tipe' => $tipe])
            ->with('success', "Berhasil menambahkan {$savedCount} Surat " . ucfirst($tipe) . " baru sekaligus!");
    }

    /**
     * Helper to parse any incoming Excel date format into YYYY-MM-DD
     */
    private function parseDateToYmd($dateStr, $default = null)
    {
        if (empty($dateStr)) return $default ?: date('Y-m-d');
        $dateStr = trim($dateStr);

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateStr)) {
            return $dateStr;
        }

        if (is_numeric($dateStr) && floatval($dateStr) > 30000) {
            $unixTimestamp = (floatval($dateStr) - 25569) * 86400;
            return date('Y-m-d', $unixTimestamp);
        }

        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{2,4})$/', $dateStr, $matches)) {
            $p1 = intval($matches[1]);
            $p2 = intval($matches[2]);
            $y = intval($matches[3]);
            if ($y < 100) $y += 2000;

            if ($p1 > 12 && $p2 <= 12) {
                if (checkdate($p2, $p1, $y)) {
                    return sprintf('%04d-%02d-%02d', $y, $p2, $p1);
                }
            } else {
                if (checkdate($p1, $p2, $y)) {
                    return sprintf('%04d-%02d-%02d', $y, $p1, $p2);
                }
                if (checkdate($p2, $p1, $y)) {
                    return sprintf('%04d-%02d-%02d', $y, $p2, $p1);
                }
            }
        }

        $timestamp = strtotime($dateStr);
        if ($timestamp !== false && $timestamp > 0) {
            return date('Y-m-d', $timestamp);
        }

        return $default ?: date('Y-m-d');
    }
}
