<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin SIKTN
        Admin::create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@siktn.com',
            'password' => Hash::make('superadmin@2025'),
            'category' => 'super_admin',
            'domisili' => 'Nasional',
        ]);
    }
}