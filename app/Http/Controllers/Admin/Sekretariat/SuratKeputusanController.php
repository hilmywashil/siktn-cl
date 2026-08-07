<?php

namespace App\Http\Controllers\Admin\Sekretariat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuratKeputusan;
use App\Traits\LogsAdminActivity;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminNotification;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SuratKeputusanController extends Controller
{
    use LogsAdminActivity;
    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        $query = SuratKeputusan::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_sk', 'like', "%{$search}%")
                  ->orWhere('judul_sk', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $sks = $query->orderBy('tanggal_berakhir', 'asc')->paginate(10)->appends($request->query());

        // Check for SK expiring within 6 months (180 days)
        $sixMonthsLater = Carbon::now()->addMonths(6);
        $expiringSks = SuratKeputusan::where('status', 'Aktif')
            ->whereBetween('tanggal_berakhir', [Carbon::now(), $sixMonthsLater])
            ->get();

        $existingNomorSks = SuratKeputusan::pluck('nomor_sk')->toArray();

        return view('admin.sekretariat.sk.index', [
            'activeMenu' => 'sekretariat_sk',
            'admin' => $admin,
            'sks' => $sks,
            'expiringSks' => $expiringSks,
            'existingNomorSks' => $existingNomorSks,
        ]);
    }

    public function store(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'nomor_sk' => 'required|string|max:255',
            'judul_sk' => 'required|string|max:255',
            'tanggal_berlaku' => 'required|date',
            'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_berlaku',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'link_drive' => 'nullable|url',
            'keterangan' => 'nullable|string',
        ]);

        $sk = SuratKeputusan::create([
            'nomor_sk' => $validated['nomor_sk'],
            'judul_sk' => $validated['judul_sk'],
            'tanggal_berlaku' => $validated['tanggal_berlaku'],
            'tanggal_berakhir' => $validated['tanggal_berakhir'],
            'status' => $validated['status'],
            'link_drive' => $validated['link_drive'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
            'created_by' => $admin->id,
        ]);

        // Cek jika SK yang baru dibuat akan berakhir dalam <= 7 hari (1 minggu)
        $now = Carbon::now()->startOfDay();
        $endDate = Carbon::parse($sk->tanggal_berakhir)->startOfDay();
        $daysUntilExpired = (int) $now->diffInDays($endDate, false);

        if ($sk->status === 'Aktif' && $daysUntilExpired >= 0 && $daysUntilExpired <= 7) {
            $admins = Admin::whereIn('category', ['super_admin', 'pimpinan', 'pnkt'])->get();
            if ($admins->count() > 0) {
                Notification::send($admins, new AdminNotification(
                    'sk_expired',
                    'Pengingat Masa Berlaku SK',
                    "Surat Keputusan '{$sk->nomor_sk}' ({$sk->judul_sk}) akan habis masa berlakunya dalam {$daysUntilExpired} hari lagi.",
                    ['sk_id' => $sk->id]
                ));
            }
        }

        $this->logActivity('sk', 'Tambah', $sk->id, $sk->nomor_sk . ' - ' . $sk->judul_sk, 'Status: ' . $sk->status);

        return redirect()->route('admin.sekretariat.sk.index')->with('success', 'Surat Keputusan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $sk = SuratKeputusan::findOrFail($id);

        $validated = $request->validate([
            'nomor_sk' => 'required|string|max:255',
            'judul_sk' => 'required|string|max:255',
            'tanggal_berlaku' => 'required|date',
            'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_berlaku',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'link_drive' => 'nullable|url',
            'keterangan' => 'nullable|string',
        ]);

        $sk->update($validated);

        // Auto mark as read old sk_expired notifications if extended date is safe (>7 days) or inactive
        $now = Carbon::now()->startOfDay();
        $endDate = Carbon::parse($sk->tanggal_berakhir)->startOfDay();
        $daysUntilExpired = (int) $now->diffInDays($endDate, false);

        if ($sk->status === 'Tidak Aktif' || $daysUntilExpired > 7) {
            $escapedNomorSk = str_replace('/', '\\/', $sk->nomor_sk);
            \Illuminate\Support\Facades\DB::table('notifications')
                ->whereNull('read_at')
                ->where('data', 'like', '%"type":"sk_expired"%')
                ->where(function ($q) use ($sk, $escapedNomorSk) {
                    $q->where('data', 'like', '%"sk_id":' . $sk->id . '%')
                      ->orWhere('data', 'like', '%' . $sk->nomor_sk . '%')
                      ->orWhere('data', 'like', '%' . $escapedNomorSk . '%')
                      ->orWhere('data', 'like', '%' . $sk->judul_sk . '%');
                })
                ->update(['read_at' => now()]);
        }

        $this->logActivity('sk', 'Edit', $sk->id, $sk->nomor_sk . ' - ' . $sk->judul_sk, 'Status: ' . $sk->status);

        return redirect()->route('admin.sekretariat.sk.index')->with('success', 'Surat Keputusan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sk = SuratKeputusan::findOrFail($id);
        $label = $sk->nomor_sk . ' - ' . $sk->judul_sk;
        $skId = $sk->id;
        $sk->delete();

        $this->logActivity('sk', 'Hapus', $skId, $label);

        return redirect()->route('admin.sekretariat.sk.index')->with('success', 'Surat Keputusan berhasil dihapus.');
    }

    /**
     * Hapus Banyak SK (Bulk Delete)
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:surat_keputusans,id',
        ]);

        $items = SuratKeputusan::whereIn('id', $request->ids)->get();
        $count = $items->count();

        foreach ($items as $sk) {
            if ($sk->file_sk) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($sk->file_sk);
            }
            $this->logActivity('sk', 'Hapus (Bulk)', $sk->id, $sk->nomor_sk . ' - ' . $sk->judul_sk);
            $sk->delete();
        }

        return redirect()->back()->with('success', "{$count} Surat Keputusan berhasil dihapus.");
    }

    /**
     * Download Banyak SK sekaligus (Bulk Download ZIP)
     */
    public function bulkDownload(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:surat_keputusans,id',
        ]);

        $sks = SuratKeputusan::whereIn('id', $request->ids)->get();
        if ($sks->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada SK terpilih.');
        }

        $zipName = 'sk-terpilih-' . time() . '.zip';
        $tempDir = storage_path('app/public/temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }
        $zipPath = $tempDir . '/' . $zipName;

        $zip = new \ZipArchive;
        $fileCount = 0;
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            foreach ($sks as $sk) {
                if ($sk->file_sk && \Illuminate\Support\Facades\Storage::disk('public')->exists($sk->file_sk)) {
                    $fullPath = \Illuminate\Support\Facades\Storage::disk('public')->path($sk->file_sk);
                    $extension = pathinfo($fullPath, PATHINFO_EXTENSION);
                    $cleanName = \Illuminate\Support\Str::slug($sk->nomor_sk . '-' . $sk->judul_sk);
                    $inZipName = $cleanName . '.' . $extension;
                    $zip->addFile($fullPath, $inZipName);
                    $fileCount++;
                }
            }
            $zip->close();
        }

        if ($fileCount === 0) {
            if (file_exists($zipPath)) unlink($zipPath);
            return redirect()->back()->with('error', 'SK yang dipilih tidak memiliki berkas dokumen terunggah.');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    /**
     * Export Data Surat Keputusan (SK) ke Excel (.xls)
     */
    public function export(Request $request)
    {
        $query = SuratKeputusan::query();

        if ($request->filled('ids') && is_array($request->ids)) {
            $query->whereIn('id', $request->ids);
        } else {
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nomor_sk', 'like', "%{$search}%")
                      ->orWhere('judul_sk', 'like', "%{$search}%")
                      ->orWhere('keterangan', 'like', "%{$search}%");
                });
            }
        }

        $sks = $query->orderBy('created_at', 'desc')->get();

        $fileName = 'Data_Surat_Keputusan_SIKTN_' . date('Ymd_His') . '.xls';

        $html = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
        $html .= '<head><meta charset="utf-8"><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Surat Keputusan SIKTN</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head>';
        $html .= '<body style="font-family: Arial, sans-serif;">';

        $html .= '<table style="border-collapse: collapse; width: 100%;">';

        // Header Title
        $html .= '<tr><td colspan="8" style="font-size: 16pt; font-weight: bold; color: #022648; text-align: center; padding: 12px; background-color: #f8fafc; border: 1px solid #cbd5e1;">DATA SURAT KEPUTUSAN (SK) - SIKTN</td></tr>';
        $html .= '<tr><td colspan="8" style="font-size: 9pt; color: #64748b; text-align: right; padding: 6px; font-style: italic;">Tanggal Export: ' . date('d F Y H:i:s') . ' WIB</td></tr>';
        $html .= '<tr><td colspan="8" style="height: 10px;"></td></tr>';

        // Table Header
        $html .= '<thead><tr style="background-color: #022648; color: #ffffff; font-weight: bold; text-align: center;">';
        $html .= '<th style="border: 1px solid #01162f; padding: 10px; width: 50px;">NO</th>';
        $html .= '<th style="border: 1px solid #01162f; padding: 10px; width: 220px;">NOMOR SK</th>';
        $html .= '<th style="border: 1px solid #01162f; padding: 10px; width: 320px;">JUDUL SURAT KEPUTUSAN</th>';
        $html .= '<th style="border: 1px solid #01162f; padding: 10px; width: 140px;">TANGGAL BERLAKU</th>';
        $html .= '<th style="border: 1px solid #01162f; padding: 10px; width: 140px;">TANGGAL BERAKHIR</th>';
        $html .= '<th style="border: 1px solid #01162f; padding: 10px; width: 300px;">LINK GOOGLE DRIVE</th>';
        $html .= '<th style="border: 1px solid #01162f; padding: 10px; width: 120px;">STATUS</th>';
        $html .= '<th style="border: 1px solid #01162f; padding: 10px; width: 250px;">KETERANGAN</th>';
        $html .= '</tr></thead>';

        $html .= '<tbody>';

        if ($sks->count() > 0) {
            foreach ($sks as $index => $sk) {
                $bgColor = ($index % 2 == 0) ? '#ffffff' : '#f8fafc';
                $statusColor = $sk->status == 'Aktif' ? '#059669' : '#dc2626';

                $html .= '<tr style="background-color: ' . $bgColor . ';">';
                $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center;">' . ($index + 1) . '</td>';
                $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; font-weight: bold; color: #022648;">' . htmlspecialchars($sk->nomor_sk) . '</td>';
                $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; font-weight: bold;">' . htmlspecialchars($sk->judul_sk) . '</td>';
                $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center;">' . \Carbon\Carbon::parse($sk->tanggal_berlaku)->format('Y-m-d') . '</td>';
                $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center;">' . \Carbon\Carbon::parse($sk->tanggal_berakhir)->format('Y-m-d') . '</td>';
                $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; color: #2563eb;">' . htmlspecialchars($sk->link_drive ?? '-') . '</td>';
                $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px; text-align: center; font-weight: bold; color: ' . $statusColor . ';">' . $sk->status . '</td>';
                $html .= '<td style="border: 1px solid #cbd5e1; padding: 8px;">' . htmlspecialchars($sk->keterangan ?? '-') . '</td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr><td colspan="8" style="border: 1px solid #cbd5e1; padding: 20px; text-align: center; color: #64748b;">Tidak ada data Surat Keputusan.</td></tr>';
        }

        $html .= '</tbody></table></body></html>';

        $this->logActivity('sk', 'Export Excel', null, "Export {$sks->count()} SK");

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /**
     * Download Template Contoh Excel Import SK
     */
    public function downloadTemplate()
    {
        $filePath = public_path('templates/Template_Import_Surat_Keputusan.xls');
        if (!file_exists($filePath)) {
            $filePath = base_path('TemplateSurat.xls');
        }

        if (file_exists($filePath)) {
            return response()->download($filePath, 'Template_Import_Surat_Keputusan.xls', [
                'Content-Type' => 'application/vnd.ms-excel',
            ]);
        }

        return redirect()->back()->with('error', 'Berkas template contoh tidak ditemukan.');
    }

    /**
     * Import Data SK dari Berkas Excel/CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'sk_rows' => 'required|string',
        ], [
            'sk_rows.required' => 'Daftar data SK wajib diisi (File kosong atau tidak terbaca).',
        ]);

        $rows = json_decode($request->sk_rows, true);

        if (!is_array($rows) || empty($rows)) {
            return redirect()->back()->with('error', 'Format data tidak valid atau berkas kosong.');
        }

        $importedCount = 0;

        foreach ($rows as $row) {
            $nomorSk = trim($row['nomor_sk'] ?? $row['nomor'] ?? '');
            $judulSk = trim($row['judul_sk'] ?? $row['judul'] ?? '');
            $rawBerlaku = trim($row['tanggal_berlaku'] ?? $row['berlaku'] ?? '');
            $rawBerakhir = trim($row['tanggal_berakhir'] ?? $row['berakhir'] ?? '');

            $tglBerlaku = $this->parseDateToYmd($rawBerlaku, date('Y-m-d'));
            $tglBerakhir = $this->parseDateToYmd($rawBerakhir, date('Y-m-d', strtotime('+3 years')));
            $linkDrive = trim($row['link_drive'] ?? $row['link'] ?? '');
            $status = trim($row['status'] ?? 'Aktif');
            $keterangan = trim($row['keterangan'] ?? '');

            if (empty($nomorSk) || empty($judulSk)) continue;
            if (strtolower($nomorSk) === 'nomor sk' || strtolower($nomorSk) === 'no') continue;

            SuratKeputusan::create([
                'nomor_sk' => $nomorSk,
                'judul_sk' => $judulSk,
                'tanggal_berlaku' => $tglBerlaku,
                'tanggal_berakhir' => $tglBerakhir,
                'link_drive' => $linkDrive ?: null,
                'status' => in_array($status, ['Aktif', 'Tidak Aktif']) ? $status : 'Aktif',
                'keterangan' => $keterangan ?: null,
            ]);

            $importedCount++;
        }

        $this->logActivity('sk', 'Import Excel', null, "Berhasil mengimport {$importedCount} SK baru");

        return redirect()->route('admin.sekretariat.sk.index')->with('success', "Berhasil meng-import {$importedCount} Surat Keputusan baru!");
    }

    /**
     * Helper to parse any incoming Excel date format into YYYY-MM-DD
     */
    private function parseDateToYmd($dateStr, $default = null)
    {
        if (empty($dateStr)) return $default ?: date('Y-m-d');
        $dateStr = trim($dateStr);

        // Standard YYYY-MM-DD
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateStr)) {
            return $dateStr;
        }

        // Serial Excel timestamp (e.g. 46244)
        if (is_numeric($dateStr) && floatval($dateStr) > 30000) {
            $unixTimestamp = (floatval($dateStr) - 25569) * 86400;
            return date('Y-m-d', $unixTimestamp);
        }

        // Check M/D/YY or M/D/YYYY or D/M/Y or M-D-Y (e.g. 12/31/26, 8/10/26, 12/31/2026)
        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{2,4})$/', $dateStr, $matches)) {
            $p1 = intval($matches[1]);
            $p2 = intval($matches[2]);
            $y = intval($matches[3]);
            if ($y < 100) $y += 2000;

            // If p1 > 12, p1 is day, p2 is month (D/M/Y)
            if ($p1 > 12 && $p2 <= 12) {
                if (checkdate($p2, $p1, $y)) {
                    return sprintf('%04d-%02d-%02d', $y, $p2, $p1);
                }
            } else {
                // Assume p1 is month, p2 is day (US format M/D/Y)
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
