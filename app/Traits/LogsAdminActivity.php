<?php

namespace App\Traits;

use App\Models\AdminActivityLog;

trait LogsAdminActivity
{
    /**
     * Catat aktivitas admin ke tabel admin_activity_logs.
     *
     * @param  string       $module       Nama modul (anggota, berita, program, katalog, surat, sk, notulensi, dll)
     * @param  string       $action       Jenis aksi (Tambah, Edit, Hapus, Ubah Status, Approve, Tolak, dll)
     * @param  int|null     $recordId     ID record yang diubah
     * @param  string|null  $recordLabel  Nama/judul/nomor record
     * @param  string|null  $detail       Keterangan tambahan (misal: "Draft -> Published")
     */
    protected function logActivity(
        string $module,
        string $action,
        ?int $recordId = null,
        ?string $recordLabel = null,
        ?string $detail = null
    ): void {
        try {
            $admin = auth()->guard('admin')->user();

            AdminActivityLog::create([
                'module'       => $module,
                'action'       => $action,
                'record_id'    => $recordId,
                'record_label' => $recordLabel,
                'admin_id'     => $admin?->id,
                'admin_name'   => $admin?->name ?? 'System',
                'detail'       => $detail,
                'ip_address'   => request()->ip(),
            ]);
        } catch (\Throwable $e) {
            // Jangan sampai gagal log merusak flow utama
            \Illuminate\Support\Facades\Log::warning('AdminActivityLog failed: ' . $e->getMessage());
        }
    }
}
