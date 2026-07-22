<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;

class Anggota extends Authenticatable
{
    use HasFactory, HasRoles, SoftDeletes, Prunable, Notifiable;

    protected $table = 'anggota';

    protected $fillable = [
        'username',
        'email',
        'password',
        'initial_password',
        'status',
        'rejection_reason',
        'approved_at',
        'approved_by',
        
        // Biodata Lengkap
        'nama_lengkap',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat_lengkap',
        'domisili',
        'jabatan',
        'pendidikan_terakhir',
        'pekerjaan',
        'riwayat_organisasi',
        'kompetensi',
        'no_hp',
        'foto_diri',
        
        // Sosial Media
        'instagram',
        'tiktok',
        'twitter',
        
        'updated_fields',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'initial_password',
    ];

    protected $casts = [
        'id' => 'integer',
        'tanggal_lahir' => 'date',
        'approved_at' => 'datetime',
        'updated_fields' => 'array',
    ];

    public function katalog()
    {
        return $this->hasOne(Katalog::class, 'anggota_id');
    }

    public function getFotoDiriUrlAttribute()
    {
        return $this->foto_diri ? Storage::url($this->foto_diri) : null;
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function approve($userId = null)
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $userId,
        ]);
    }

    public function reject($reason, $userId = null)
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'approved_by' => $userId,
        ]);
    }

    public function hasKatalog()
    {
        return $this->katalog()->exists();
    }

    public function hasActiveKatalog()
    {
        return $this->katalog()->where('is_active', true)->exists();
    }

    /**
     * Tentukan model mana yang dapat dihapus permanen secara otomatis.
     */
    public function prunable()
    {
        return static::where('deleted_at', '<=', now()->subDays(30));
    }
}