<?php
// app/Models/Organisasi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Organisasi extends Model
{
    use HasFactory;

    protected $table = 'organisasi';

    protected $fillable = [
        'nama',
        'jabatan',
        'foto',
        'kategori',
        'urutan',
        'aktif'
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    // Accessor for photo URL
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return Storage::url($this->foto);
        }
        return asset('images/photo.jpg'); // default photo
    }

    // Accessor for kategori label
    public function getKategoriLabelAttribute()
    {
        $labels = [
            'ketua_umum' => 'Ketua Umum',
            'wakil_ketua_umum' => 'Wakil Ketua Umum',
            'ketua_bidang' => 'Ketua Bidang',
            'sekretaris_umum' => 'Sekretaris Umum',
            'wakil_sekretaris_umum' => 'Wakil Sekretaris Umum',
        ];

        return $labels[$this->kategori] ?? $this->kategori;
    }

    // Scope untuk kategori
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    // Scope untuk yang aktif
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    // Scope untuk ordering
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan', 'asc')->orderBy('created_at', 'asc');
    }
}