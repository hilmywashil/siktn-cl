<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_program',
        'kategori',
        'program_kerja_id',
        'status',
        'periode_mulai',
        'periode_selesai',
        'pic',
        'target_output',
        'anggaran',
        'gambar',
        'mitra',
        'jabatan_id'
    ];

    /**
     * Get the full URL for the image.
     */
    public function getGambarUrlAttribute()
    {
        if ($this->gambar && \Illuminate\Support\Facades\Storage::disk('public')->exists('programs/' . $this->gambar)) {
            return asset('storage/programs/' . $this->gambar);
        }
        return asset('assets-front/images/logo_karang_taruna.png'); // Add a default placeholder if needed
    }

    protected $casts = [
        'periode_mulai' => 'date',
        'periode_selesai' => 'date',
        'anggaran' => 'decimal:2',
    ];

    /**
     * Get the jabatan associated with the program (khusus kategori Bidang / Program Kerja)
     */
    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class);
    }

    /**
     * Relasi ke Program Kerja Induk (Khusus Kategori CSR)
     */
    public function programKerja(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'program_kerja_id');
    }

    /**
     * Relasi ke Program CSR Pendukung (Khusus Kategori Bidang / Program Kerja)
     */
    public function csrPrograms(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Program::class, 'program_kerja_id');
    }

    /**
     * Get the anggota participating in this program.
     */
    public function peserta(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        $relation = $this->belongsToMany(Anggota::class, 'program_anggota', 'program_id', 'anggota_id');
        if (\Illuminate\Support\Facades\Schema::hasColumn('program_anggota', 'status')) {
            $relation->withPivot('status');
        }
        return $relation->withTimestamps();
    }
}
