<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RegistrationInvite extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'created_by',
        'expires_at',
        'max_uses',
        'uses_count',
        'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && Carbon::now()->greaterThan($this->expires_at)) {
            return false;
        }

        if ($this->max_uses > 0 && $this->uses_count >= $this->max_uses) {
            return false;
        }

        return true;
    }
}
