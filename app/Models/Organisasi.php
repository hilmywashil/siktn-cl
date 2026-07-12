<?php
// app/Models/Organisasi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

class Organisasi extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope('valid_anggota', function (Builder $builder) {
            $builder->where(function ($query) {
                $query->whereNull('anggota_id')
                      ->orWhereHas('anggota'); // Hanya tampilkan jika anggotanya belum dihapus (soft delete)
            });
        });
    }

    protected $table = 'organisasi';

    protected $fillable = [
        'anggota_id',
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

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
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