<?php

namespace App\Providers;

use App\Models\Admin;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // ✨ Gate untuk manage admins (hanya Super Admin)
        Gate::define('manage-admins', function (?Admin $admin) {
            return $admin && $admin->isSuperAdmin();
        });

        // Gate untuk approve anggota (BPC dan Super Admin)
        Gate::define('approve-anggota', function (?Admin $admin, $anggota = null) {
            if (!$admin) {
                return false;
            }

            if ($admin->isSuperAdmin()) {
                return true;
            }

            if ($admin->isBPC() && $anggota) {
                return $admin->domisili === $anggota->domisili;
            }

            return false;
        });

        // Gate untuk manage content (Super Admin dan BPD)
        Gate::define('manage-content', function (?Admin $admin) {
            return $admin && ($admin->isSuperAdmin() || $admin->isBPD());
        });
    }
}