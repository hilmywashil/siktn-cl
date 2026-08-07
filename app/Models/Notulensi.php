<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notulensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'agenda_id',
        'judul_rapat',
        'tanggal_rapat',
        'pemimpin_rapat',
        'ringkasan_hasil',
        'file_pdf',
        'foto_dokumentasi',
        'link_drive',
        'created_by',
    ];

    protected $casts = [
        'foto_dokumentasi' => 'array',
    ];

    public function agenda()
    {
        return $this->belongsTo(Agenda::class);
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
