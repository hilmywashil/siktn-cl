<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrategicPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'details',
        'category',
        'order',
        'is_active'
    ];

    protected $casts = [
        'details' => 'array',
        'is_active' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeTataKelola($query)
    {
        return $query->where('category', 'tata_kelola');
    }

    public function scopeProgramLayanan($query)
    {
        return $query->where('category', 'program_layanan');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}