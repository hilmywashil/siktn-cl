<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            // --- 2 SUPER ADMIN ---
            [
                'name' => 'Super Admin Utama SIKTN',
                'username' => 'superadmin',
                'email' => 'superadmin@siktn.com',
                'password' => Hash::make('superadmin@2025'),
                'category' => 'super_admin',
                'domisili' => 'Nasional',
                'is_active' => true,
                'role' => 'super_admin',
            ],
            [
                'name' => 'Super Admin Cadangan SIKTN',
                'username' => 'superadmin2',
                'email' => 'superadmin2@siktn.com',
                'password' => Hash::make('password123'),
                'category' => 'super_admin',
                'domisili' => 'Nasional',
                'is_active' => true,
                'role' => 'super_admin',
            ],

            // --- 1 PIMPINAN ---
            [
                'name' => 'Ketua Umum / Pimpinan SIKTN',
                'username' => 'pimpinan',
                'email' => 'pimpinan@siktn.com',
                'password' => Hash::make('password123'),
                'category' => 'pimpinan',
                'domisili' => 'Nasional',
                'is_active' => true,
                'role' => 'pimpinan',
            ],

            // --- 1 ADMIN PNKT (NASIONAL) ---
            [
                'name' => 'Admin PNKT (Nasional)',
                'username' => 'admin.pnkt',
                'email' => 'pnkt@siktn.com',
                'password' => Hash::make('password123'),
                'category' => 'pnkt',
                'domisili' => 'Nasional',
                'is_active' => true,
                'role' => 'pnkt',
            ],

            // --- ADMIN PPKT (PROVINSI) ---
            [
                'name' => 'Admin PPKT Jawa Barat',
                'username' => 'admin.ppkt.jabar',
                'email' => 'ppkt.jabar@siktn.com',
                'password' => Hash::make('password123'),
                'category' => 'ppkt',
                'domisili' => 'Jawa Barat',
                'is_active' => true,
                'role' => 'ppkt',
            ],
            [
                'name' => 'Admin PPKT Jawa Tengah',
                'username' => 'admin.ppkt.jateng',
                'email' => 'ppkt.jateng@siktn.com',
                'password' => Hash::make('password123'),
                'category' => 'ppkt',
                'domisili' => 'Jawa Tengah',
                'is_active' => true,
                'role' => 'ppkt',
            ],
            [
                'name' => 'Admin PPKT Jawa Timur',
                'username' => 'admin.ppkt.jatim',
                'email' => 'ppkt.jatim@siktn.com',
                'password' => Hash::make('password123'),
                'category' => 'ppkt',
                'domisili' => 'Jawa Timur',
                'is_active' => true,
                'role' => 'ppkt',
            ],
            [
                'name' => 'Admin PPKT DKI Jakarta',
                'username' => 'admin.ppkt.dkijakarta',
                'email' => 'ppkt.dkijakarta@siktn.com',
                'password' => Hash::make('password123'),
                'category' => 'ppkt',
                'domisili' => 'DKI Jakarta',
                'is_active' => true,
                'role' => 'ppkt',
            ],
            [
                'name' => 'Admin PPKT Banten',
                'username' => 'admin.ppkt.banten',
                'email' => 'ppkt.banten@siktn.com',
                'password' => Hash::make('password123'),
                'category' => 'ppkt',
                'domisili' => 'Banten',
                'is_active' => true,
                'role' => 'ppkt',
            ],
            [
                'name' => 'Admin PPKT Sumatera Utara',
                'username' => 'admin.ppkt.sumut',
                'email' => 'ppkt.sumut@siktn.com',
                'password' => Hash::make('password123'),
                'category' => 'ppkt',
                'domisili' => 'Sumatera Utara',
                'is_active' => true,
                'role' => 'ppkt',
            ],

            // --- ADMIN PKKT (KABUPATEN / KOTA) ---
            [
                'name' => 'Admin PKKT Kota Bandung',
                'username' => 'admin.pkkt.bandung',
                'email' => 'pkkt.bandung@siktn.com',
                'password' => Hash::make('password123'),
                'category' => 'pkkt',
                'domisili' => 'Kota Bandung',
                'is_active' => true,
                'role' => 'pkkt',
            ],
            [
                'name' => 'Admin PKKT Kabupaten Bandung',
                'username' => 'admin.pkkt.kabbandung',
                'email' => 'pkkt.kabbandung@siktn.com',
                'password' => Hash::make('password123'),
                'category' => 'pkkt',
                'domisili' => 'Kabupaten Bandung',
                'is_active' => true,
                'role' => 'pkkt',
            ],
            [
                'name' => 'Admin PKKT Kota Surabaya',
                'username' => 'admin.pkkt.surabaya',
                'email' => 'pkkt.surabaya@siktn.com',
                'password' => Hash::make('password123'),
                'category' => 'pkkt',
                'domisili' => 'Kota Surabaya',
                'is_active' => true,
                'role' => 'pkkt',
            ],
            [
                'name' => 'Admin PKKT Kota Semarang',
                'username' => 'admin.pkkt.semarang',
                'email' => 'pkkt.semarang@siktn.com',
                'password' => Hash::make('password123'),
                'category' => 'pkkt',
                'domisili' => 'Kota Semarang',
                'is_active' => true,
                'role' => 'pkkt',
            ],
            [
                'name' => 'Admin PKKT Kota Medan',
                'username' => 'admin.pkkt.medan',
                'email' => 'pkkt.medan@siktn.com',
                'password' => Hash::make('password123'),
                'category' => 'pkkt',
                'domisili' => 'Kota Medan',
                'is_active' => true,
                'role' => 'pkkt',
            ],
            [
                'name' => 'Admin PKKT Jakarta Selatan',
                'username' => 'admin.pkkt.jaksel',
                'email' => 'pkkt.jaksel@siktn.com',
                'password' => Hash::make('password123'),
                'category' => 'pkkt',
                'domisili' => 'Jakarta Selatan',
                'is_active' => true,
                'role' => 'pkkt',
            ],
        ];

        foreach ($admins as $data) {
            $roleName = $data['role'];
            unset($data['role']);

            $admin = Admin::updateOrCreate(
                ['username' => $data['username']],
                $data
            );

            // Assign Spatie Role
            $role = Role::where('name', $roleName)->where('guard_name', 'admin')->first();
            if ($role) {
                $admin->syncRoles([$role]);
            }
        }
    }
}