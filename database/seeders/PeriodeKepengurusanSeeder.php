<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PeriodeKepengurusan;

class PeriodeKepengurusanSeeder extends Seeder
{
    public function run(): void
    {
        PeriodeKepengurusan::create([
            'nama_periode' => 'Masa Bhakti 2026 - 2031',
            'tahun_mulai' => 2026,
            'tahun_selesai' => 2031,
            'nomor_sk' => 'SK-PNKT/2026/001',
            'keterangan' => 'Periode Kepengurusan Utama SIKTN 2026-2031',
            'is_aktif' => true,
        ]);
    }
}
