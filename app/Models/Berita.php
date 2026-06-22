<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Berita extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'slug',
        'konten',
        'gambar',
        'is_populer',
        'is_active',
        'views',
        'tanggal_publish',
    ];

    protected $casts = [
        'is_populer' => 'boolean',
        'is_active' => 'boolean',
        'tanggal_publish' => 'date',
    ];

    // Auto generate slug dari judul
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($berita) {
            if (empty($berita->slug)) {
                $berita->slug = Str::slug($berita->judul);
                
                // Pastikan slug unik
                $count = 1;
                $originalSlug = $berita->slug;
                while (static::where('slug', $berita->slug)->exists()) {
                    $berita->slug = $originalSlug . '-' . $count;
                    $count++;
                }
            }
        });

        static::updating(function ($berita) {
            if ($berita->isDirty('judul')) {
                $berita->slug = Str::slug($berita->judul);
                
                // Pastikan slug unik (exclude current record)
                $count = 1;
                $originalSlug = $berita->slug;
                while (static::where('slug', $berita->slug)->where('id', '!=', $berita->id)->exists()) {
                    $berita->slug = $originalSlug . '-' . $count;
                    $count++;
                }
            }
        });

        // Hapus gambar saat berita dihapus
        static::deleting(function ($berita) {
            if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
                Storage::disk('public')->delete($berita->gambar);
            }
        });
    }

    // Accessor untuk URL gambar
    public function getGambarUrlAttribute()
    {
        if ($this->gambar) {
            return asset('storage/' . $this->gambar);
        }
        return asset('images/missions/mission-1.png'); // Default image
    }

    // Scope untuk berita aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk berita populer
    public function scopePopuler($query)
    {
        return $query->where('is_populer', true);
    }

    // Scope untuk ordering by tanggal publish
    public function scopeLatestPublish($query)
    {
        return $query->orderBy('tanggal_publish', 'desc');
    }

    // Increment views
    public function incrementViews()
    {
        $this->increment('views');
    }

    // Format tanggal Indonesia
    public function getTanggalFormatAttribute()
    {
        return $this->tanggal_publish->locale('id')->isoFormat('D MMMM YYYY');
    }
}