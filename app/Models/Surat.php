<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipe',
        'klasifikasi',
        'nomor_surat',
        'tanggal',
        'perihal',
        'pengirim_tujuan',
        'status',
        'link_drive',
        'file_lampiran',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function auditLogs()
    {
        return $this->hasMany(SuratAuditLog::class)->orderBy('created_at', 'desc');
    }
}
