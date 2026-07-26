<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemuKarya extends Model
{
    use HasFactory;

    protected $table = 'temu_karyas';

    protected $fillable = [
        'jenis',
        'wilayah',
        'level',
        'tanggal_pelaksanaan',
        'lokasi',
        'jumlah_peserta',
        'foto_dokumentasi',
        'catatan',
        'file_sk',
        'link_drive',
        'surat_keputusan_id',
        'status',
        'created_by',
    ];

    protected $casts = [
        'tanggal_pelaksanaan' => 'date',
        'jumlah_peserta' => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function suratKeputusan()
    {
        return $this->belongsTo(SuratKeputusan::class, 'surat_keputusan_id');
    }

    public function getFotoDokumentasiListAttribute(): array
    {
        if (empty($this->foto_dokumentasi)) {
            return [];
        }
        $decoded = json_decode($this->foto_dokumentasi, true);
        if (is_array($decoded)) {
            return array_map(fn($path) => \Illuminate\Support\Facades\Storage::url($path), $decoded);
        }
        return [\Illuminate\Support\Facades\Storage::url($this->foto_dokumentasi)];
    }
}
