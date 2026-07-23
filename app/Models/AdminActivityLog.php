<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminActivityLog extends Model
{
    protected $fillable = [
        'module',
        'action',
        'record_id',
        'record_label',
        'admin_id',
        'admin_name',
        'detail',
        'ip_address',
    ];

    /**
     * Label warna per module (untuk badge di view)
     */
    public static $moduleColors = [
        'anggota'    => '#059669',
        'berita'     => '#2563eb',
        'program'    => '#7c3aed',
        'agenda'     => '#d97706',
        'katalog'    => '#0891b2',
        'surat'      => '#ea580c',
        'sk'         => '#dc2626',
        'notulensi'  => '#9333ea',
        'organisasi' => '#0284c7',
        'jabatan'    => '#16a34a',
        'strategic'  => '#b7830f',
        'temu-karya' => '#be185d',
        'misi'       => '#0369a1',
        'admin'      => '#374151',
        'default'    => '#6b7280',
    ];

    public function getModuleColorAttribute(): string
    {
        return self::$moduleColors[$this->module] ?? self::$moduleColors['default'];
    }

    /**
     * Label module yang lebih ramah dibaca
     */
    public static $moduleLabels = [
        'anggota'    => 'Anggota',
        'berita'     => 'Berita',
        'program'    => 'Program',
        'agenda'     => 'Agenda',
        'katalog'    => 'E-Katalog',
        'surat'      => 'Surat',
        'sk'         => 'Surat Keputusan',
        'notulensi'  => 'Notulensi',
        'organisasi' => 'Organisasi',
        'jabatan'    => 'Jabatan',
        'strategic'  => 'Strategic Plan',
        'temu-karya' => 'Temu Karya',
        'misi'       => 'Misi',
        'admin'      => 'Manajemen Admin',
        'default'    => 'Sistem',
    ];

    public function getModuleLabelAttribute(): string
    {
        return self::$moduleLabels[$this->module] ?? ucfirst($this->module);
    }
}
