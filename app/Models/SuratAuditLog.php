<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'surat_id',
        'admin_id',
        'admin_name',
        'action',
        'old_status',
        'new_status',
        'notes',
    ];

    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
