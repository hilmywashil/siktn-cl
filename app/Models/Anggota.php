<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Anggota extends Authenticatable
{
    use HasFactory;

    protected $table = 'anggota';

    protected $fillable = [
        'email_website_perusahaan',
        'password',
        'initial_password',
        'nama_perusahaan',
        'trade_mark',
        'tanggal_lahir',
        'alamat_kantor',
        'telepon_wa_perusahaan',
        'nama_pimpinan',
        'alamat_pimpinan',
        'telepon_wa_pimpinan',
        'email_pimpinan',
        'akte_notaris',
        'nomor_induk_berusaha_tdup',
        'npwp_perusahaan',
        'produk_usaha_yang_akan_dijual',
        'surat_permohonan',
        'akte_pendirian_perusahaan',
        'nib_atau_tdup',
        'ktp_pimpinan',
        'npwp_perusahaan_file',
        'status',
        'rejection_reason',
        'approved_at',
        'approved_by',
        
        // Personal Fields
        'nrp',
        'angkatan',
        'nama_lengkap',
        'gender',
        'tempat_lahir_personal',
        'agama',
        'no_telp',
        'alamat_domisili',
        'kode_pos',
        'email',
        'no_ktp',
        'foto_ktp',
        'foto_diri',
        'sfc_hipmi',
        'ref_hipmi',
        'aktif_org_lain',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'initial_password',
    ];

    protected $casts = [
        'id' => 'integer',
        'produk_usaha_yang_akan_dijual' => 'array',
        'tanggal_lahir' => 'date',
        'approved_at' => 'datetime',
    ];

    public function katalog()
    {
        return $this->hasOne(Katalog::class, 'anggota_id');
    }

    public function getSuratPermohonanUrlAttribute()
    {
        return $this->surat_permohonan ? Storage::url($this->surat_permohonan) : null;
    }

    public function getAktePendirianPerusahaanUrlAttribute()
    {
        return $this->akte_pendirian_perusahaan ? Storage::url($this->akte_pendirian_perusahaan) : null;
    }

    public function getNibAtauTdupUrlAttribute()
    {
        return $this->nib_atau_tdup ? Storage::url($this->nib_atau_tdup) : null;
    }

    public function getKtpPimpinanUrlAttribute()
    {
        return $this->ktp_pimpinan ? Storage::url($this->ktp_pimpinan) : null;
    }

    public function getNpwpPerusahaanFileUrlAttribute()
    {
        return $this->npwp_perusahaan_file ? Storage::url($this->npwp_perusahaan_file) : null;
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
}