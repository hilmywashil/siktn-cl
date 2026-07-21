<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriEatalog extends Model
{
    use HasFactory;

    protected $table = 'kategori_ekatalog';

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the katalogs for this kategori.
     */
    public function katalogs(): HasMany
    {
        return $this->hasMany(Katalog::class);
    }

    /**
     * Scope a query to only include active kategoris.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kategori) {
            if (empty($kategori->slug)) {
                $kategori->slug = \Illuminate\Support\Str::slug($kategori->nama);
            }
        });

        static::updating(function ($kategori) {
            if ($kategori->isDirty('nama') && !$kategori->isDirty('slug')) {
                $kategori->slug = \Illuminate\Support\Str::slug($kategori->nama);
            }
        });
    }
}
