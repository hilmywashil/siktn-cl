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
     * Get the jabatan associated with the program (khusus kategori Bidang)
     */
    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class);
    }
}
