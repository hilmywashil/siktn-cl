<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRoles = [
            'super_admin',
            'pimpinan',
            'pnkt',
            'ppkt',
            'pkkt',
        ];

        $anggotaRoles = [
            'pengurus',
            'anggota',
        ];

        foreach ($adminRoles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'admin']);
        }

        foreach ($anggotaRoles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'anggota']);
        }
    }
}
