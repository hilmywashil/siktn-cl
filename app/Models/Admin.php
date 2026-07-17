<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'username',
        'email',
        'photo',
        'password',
        'category',
        'domisili',
        'is_active',
        'no_hp',
        'foto_profil',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'username', 'email', 'category', 'domisili'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Admin {$eventName}");
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->category === 'super_admin' || $this->hasRole('super_admin');
    }

    public function isPimpinan(): bool
    {
        return $this->category === 'pimpinan' || $this->hasRole('pimpinan');
    }

    public function isPNKT(): bool
    {
        return $this->category === 'pnkt' || $this->hasRole('pnkt');
    }

    public function isPPKT(): bool
    {
        return $this->category === 'ppkt' || $this->hasRole('ppkt') || $this->category === 'bpd';
    }

    public function isPKKT(): bool
    {
        return $this->category === 'pkkt' || $this->hasRole('pkkt') || $this->category === 'bpc';
    }

    // Fallback legacy (agar view lama tidak error sebelum direfactor)
    public function isBPC(): bool
    {
        return $this->isPKKT();
    }

    public function isBPD(): bool
    {
        return $this->isPPKT();
    }

    public function canManageAdmins(): bool
    {
        return $this->isSuperAdmin();
    }

    public function canManageContent(): bool
    {
        // Pimpinan, Super Admin, PNKT bisa kelola konten (berita, katalog, dsb)
        return $this->isSuperAdmin() || $this->isPimpinan() || $this->isPNKT();
    }

    public function canApproveAnggota(): bool
    {
        // Sekretariat (PNKT, PPKT, PKKT) punya wewenang approval
        return $this->isPNKT() || $this->isPPKT() || $this->isPKKT();
    }

    public function canApproveAnggotaByDomisili($domisili): bool
    {
        // Jika PNKT atau Super Admin, bisa saja punya hak, tapi sesuai brief "berdasarkan tingkatan"
        // Untuk sekarang, kita kembalikan cek domisili untuk PPKT dan PKKT
        if ($this->isSuperAdmin() || $this->isPNKT()) {
            return true; 
        }
        
        return $this->domisili === $domisili;
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            return Storage::url($this->photo);
        }
        
        return '';
    }

    public function getInitialsAttribute(): string
    {
        return strtoupper(substr($this->name, 0, 2));
    }

    public function getRoleDisplayNameAttribute(): string
    {
        $role = $this->getRoleNames()->first() ?? $this->category;
        
        $roleNames = [
            'super_admin' => 'Super Admin',
            'pimpinan' => 'Pimpinan',
            'pnkt' => 'PNKT (Sekretariat Nasional)',
            'ppkt' => 'PPKT (Sekretariat Provinsi)',
            'pkkt' => 'PKKT (Sekretariat Kab/Kota)',
            'bpc' => 'BPC',
            'bpd' => 'BPD',
        ];

        $baseName = $roleNames[$role] ?? strtoupper(str_replace('_', ' ', $role));
        
        // Tambahkan info domisili jika ada dan rolenya berkaitan dengan wilayah
        if (in_array($role, ['ppkt', 'pkkt', 'bpc', 'bpd']) && !empty($this->domisili)) {
            return $baseName . ' - ' . $this->domisili;
        }
        
        return $baseName;
    }

    public function approvedAnggotas()
    {
        return $this->hasMany(Anggota::class, 'approved_by');
    }
}