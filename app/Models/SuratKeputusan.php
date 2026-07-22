<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKeputusan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_sk',
        'judul_sk',
        'tanggal_berlaku',
        'tanggal_berakhir',
        'status',
        'link_drive',
        'keterangan',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
