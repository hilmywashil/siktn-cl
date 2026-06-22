<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'photo',
        'password',
        'category',
        'domisili', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->category === 'super_admin';
    }

    public function isBPC(): bool
    {
        return $this->category === 'bpc';
    }

    public function isBPD(): bool
    {
        return $this->category === 'bpd';
    }

    public function canManageAdmins(): bool
    {
        return $this->isSuperAdmin();
    }

    public function canManageContent(): bool
    {
        return $this->isSuperAdmin() || $this->isBPD();
    }

    public function canApproveAnggota(): bool
    {
        return $this->category === 'bpc';
    }

    public function canApproveAnggotaByDomisili($domisili): bool
    {
        if ($this->category === 'bpd' || $this->category === 'super_admin') {
            return false; 
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

    public function approvedAnggotas()
    {
        return $this->hasMany(Anggota::class, 'approved_by');
    }
}