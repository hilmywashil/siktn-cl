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
            [
                'name' => 'Super Admin SIKTN',
                'username' => 'superadmin',
                'email' => 'superadmin@siktn.com',
                'password' => Hash::make('superadmin@2025'),
                'category' => 'super_admin',
                'domisili' => 'Nasional',
                'role' => 'super_admin',
            ],
            [
                'name' => 'Admin PNKT (Nasional)',
                'username' => 'admin.pnkt',
                'email' => 'pnkt@siktn.com',
                'password' => Hash::make('password123'),
                'category' => 'pnkt',
                'domisili' => 'Nasional',
                'role' => 'pnkt',
            ],
            [
                'name' => 'Admin PPKT (Jawa Barat)',
                'username' => 'admin.ppkt',
                'email' => 'ppkt@siktn.com',
                'password' => Hash::make('password123'),
                'category' => 'ppkt',
                'domisili' => 'Jawa Barat',
                'role' => 'ppkt',
            ],
            [
                'name' => 'Admin PKKT (Kota Bandung)',
                'username' => 'admin.pkkt',
                'email' => 'pkkt@siktn.com',
                'password' => Hash::make('password123'),
                'category' => 'pkkt',
                'domisili' => 'Kota Bandung',
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

            // Assign Spatie Role if role exists for 'admin' guard
            $role = Role::where('name', $roleName)->where('guard_name', 'admin')->first();
            if ($role) {
                $admin->assignRole($role);
            }
        }
    }
}