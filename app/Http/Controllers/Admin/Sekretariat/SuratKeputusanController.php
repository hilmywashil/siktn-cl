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

        return view('admin.sekretariat.sk.index', [
            'activeMenu' => 'sekretariat_sk',
            'admin' => $admin,
            'sks' => $sks,
            'expiringSks' => $expiringSks,
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
}
