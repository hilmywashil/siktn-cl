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
}
