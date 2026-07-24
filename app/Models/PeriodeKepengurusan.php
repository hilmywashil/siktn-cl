<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeKepengurusan extends Model
{
    use HasFactory;

    protected $table = 'periode_kepengurusans';

    protected $fillable = [
        'nama_periode',
        'tahun_mulai',
        'tahun_selesai',
        'nomor_sk',
        'keterangan',
        'is_aktif',
    ];

    protected $casts = [
        'tahun_mulai' => 'integer',
        'tahun_selesai' => 'integer',
        'is_aktif' => 'boolean',
    ];

    public function organisasi()
    {
        return $this->hasMany(Organisasi::class, 'periode_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }
}
