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
        'link_drive',
        'created_by',
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
