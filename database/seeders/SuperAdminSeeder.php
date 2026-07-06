<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@karangtaruna.org',
            'password' => Hash::make('karangtarunajaya'),
            'category' => 'super_admin',
            'domisili' => null,
        ]);

        // Assign Role Spatie
        $admin->assignRole('super_admin');
    }
}