<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Umkm extends Model
{
    use HasFactory;

    protected $fillable = [
        'anggota_id', // ✨ TAMBAHAN BARU
        'nama_usaha',
        'bidang_usaha',
        'status_legalitas',
        'jenis_legalitas',
        'tahun_berdiri',
        'nama_lengkap',
        'jenis_kelamin',
        'tanggal_lahir',
        'nomor_hp',
        'email',
        'alamat_domisili',
        'platform_digital',
        'platform',
        'pendapatan',
        'pembiayaan',
        'sumber_pembiayaan',
        'tujuan',
        'pelatihan',
        'status',
        'rejection_reason',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'platform' => 'array',
        'tanggal_lahir' => 'date',
        'verified_at' => 'datetime',
    ];

    // =====================================================
    // RELATIONSHIPS
    // =====================================================

    /**
     * Relasi ke Anggota (Many to One)
     * Setiap UMKM dimiliki oleh satu anggota
     */
    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    /**
     * Relasi ke Admin yang memverifikasi (Many to One)
     */
    public function verifiedBy()
    {
        return $this->belongsTo(Admin::class, 'verified_by');
    }

    // =====================================================
    // SCOPES
    // =====================================================

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

    // =====================================================
    // ACCESSORS
    // =====================================================

    /**
     * Get status label yang readable
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu Verifikasi',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
        ];

        return $labels[$this->status] ?? 'Tidak Diketahui';
    }

    /**
     * Get badge color berdasarkan status
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge badge-warning">Menunggu Verifikasi</span>',
            'approved' => '<span class="badge badge-success">Disetujui</span>',
            'rejected' => '<span class="badge badge-danger">Ditolak</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge badge-secondary">Tidak Diketahui</span>';
    }

    // =====================================================
    // HELPER METHODS
    // =====================================================

    /**
     * Check apakah UMKM sudah diverifikasi
     */
    public function isVerified()
    {
        return $this->status === 'approved' && $this->verified_at !== null;
    }

    /**
     * Check apakah UMKM ditolak
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Check apakah UMKM masih pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }
}