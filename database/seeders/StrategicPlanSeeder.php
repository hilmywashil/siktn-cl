<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StrategicPlan;

class StrategicPlanSeeder extends Seeder
{
    public function run(): void
    {
        $tataKelola = [
            'Platform Database Anggota',
            'Sistem Pelaporan Keuangan',
            'Rapat Pengurus dan Forum Bisnis',
            'Sekretariat ASITA Jabar',
            'Pengurus ASITA Jabar',
            'Pengembangan BPC se-Jawa Barat',
            'Advokasi dan Konsultasi Hukum',
            'Jaringan dan Kerjasama'
        ];

        foreach ($tataKelola as $index => $title) {
            StrategicPlan::create([
                'title' => $title,
                'description' => 'Deskripsi untuk ' . $title,
                'category' => 'tata_kelola',
                'order' => $index + 1,
                'is_active' => true
            ]);
        }

        $programLayanan = [
            'Kebijakan Publik',
            'Silaturahmi Lintas Generasi ASITA Jabar'
        ];

        foreach ($programLayanan as $index => $title) {
            StrategicPlan::create([
                'title' => $title,
                'description' => 'Deskripsi untuk ' . $title,
                'category' => 'program_layanan',
                'order' => $index + 1,
                'is_active' => true
            ]);
        }
    }
}