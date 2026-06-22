<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Katalog extends Model
{
    protected $fillable = [
        'anggota_id',
        'created_by_type', // 'admin' or 'anggota'
        'created_by_id',
        'company_name',
        'business_field',
        'description',
        'logo',
        'images',
        'address',
        'phone',
        'email',
        'map_embed_url',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
        'approved_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships - FIXED WITH withDefault()
    public function anggota()
    {
        return $this->belongsTo(Anggota::class)->withDefault([
            'nama_perusahaan' => 'Tidak Terhubung',
            'nama_pimpinan' => '-',
            'email' => '-',
        ]);
    }

    public function approvedBy()
    {
        return $this->belongsTo(Admin::class, 'approved_by')->withDefault([
            'name' => 'System',
        ]);
    }

    // Polymorphic relationship for creator
    public function creator()
    {
        return $this->morphTo(__FUNCTION__, 'created_by_type', 'created_by_id');
    }

    // Helper method to get creator (works for both Admin and Anggota)
    public function getCreatorNameAttribute()
    {
        if ($this->created_by_type === 'admin' && $this->created_by_id) {
            $admin = \App\Models\Admin::find($this->created_by_id);
            return $admin ? $admin->name : 'Admin (Deleted)';
        } elseif ($this->created_by_type === 'anggota' && $this->anggota_id) {
            return $this->anggota->nama_pimpinan ?? 'Anggota (Deleted)';
        }
        return 'Unknown';
    }

    public function isCreatedByAdmin()
    {
        return $this->created_by_type === 'admin';
    }

    public function isCreatedByAnggota()
    {
        return $this->created_by_type === 'anggota';
    }

    // Safe accessor for anggota name
    public function getAnggotaNameAttribute()
    {
        if (!$this->anggota_id) {
            return 'Tidak Terhubung';
        }
        
        return $this->anggota->nama_perusahaan ?? 'Anggota (Deleted)';
    }

    // Scopes
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

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where('status', 'approved');
    }

    // Status helpers
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    // Image accessors
    public function getLogoUrlAttribute()
    {
        if ($this->logo && Storage::disk('public')->exists($this->logo)) {
            return Storage::url($this->logo);
        }
        return asset('assets-front/images/logo_caaip.png');
    }

    public function getImagesUrlAttribute()
    {
        if (!$this->images || !is_array($this->images)) {
            return [];
        }

        return array_map(function($image) {
            return Storage::disk('public')->exists($image) 
                ? Storage::url($image) 
                : null;
        }, $this->images);
    }

    // Safe way to check if has valid anggota
    public function hasAnggota()
    {
        return $this->anggota_id && $this->anggota()->exists();
    }
}