<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $wilayahJabar = [
            'Bandung',
            'Bandung Barat',
            'Bekasi',
            'Bogor',
            'Ciamis',
            'Cianjur',
            'Cirebon',
            'Garut',
            'Indramayu',
            'Karawang',
            'Kuningan',
            'Majalengka',
            'Pangandaran',
            'Purwakarta',
            'Subang',
            'Sukabumi',
            'Sumedang',
            'Tasikmalaya',
            
            // KOTA (9)
            'Kota Bandung',
            'Kota Banjar',
            'Kota Bekasi',
            'Kota Bogor',
            'Kota Cimahi',
            'Kota Cirebon',
            'Kota Depok',
            'Kota Sukabumi',
            'Kota Tasikmalaya',
        ];

        // Create BPC untuk setiap Kabupaten/Kota
        foreach ($wilayahJabar as $wilayah) {
            $username = 'bpc_' . strtolower(str_replace(' ', '_', $wilayah));
            $email = 'bpc.' . strtolower(str_replace(' ', '', $wilayah)) . '@hipmi.com';
            $name = str_contains($wilayah, 'Kota') 
                ? 'Admin BPC ' . $wilayah 
                : 'Admin BPC Kabupaten ' . $wilayah;
            
            // Password = username@2025
            // Contoh: bpc_bandung@2025, bpc_kota_bandung@2025
            $password = $username . '@2025';
            
            Admin::create([
                'name' => $name,
                'username' => $username,
                'email' => $email,
                'password' => Hash::make($password),
                'category' => 'bpc',
                'domisili' => $wilayah,
            ]);
        }

        // BPD Jawa Barat
        Admin::create([
            'name' => 'Admin BPD Jawa Barat',
            'username' => 'adminbpd',
            'email' => 'adminbpd@hipmi.com',
            'password' => Hash::make('adminbpd@2025'),
            'category' => 'bpd',
            'domisili' => null,
        ]);
    }
}