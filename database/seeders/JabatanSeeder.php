<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jabatan;

class JabatanSeeder extends Seeder
{
    public function run(): void
    {
        $jabatans = [
            ['nama_jabatan' => 'Ketua Umum', 'urutan' => 1],
            ['nama_jabatan' => 'Wakil Ketua Umum', 'urutan' => 2],
            ['nama_jabatan' => 'Sekretaris Jenderal', 'urutan' => 3],
            ['nama_jabatan' => 'Wakil Sekretaris Jenderal', 'urutan' => 4],
            ['nama_jabatan' => 'Bendahara Umum', 'urutan' => 5],
            ['nama_jabatan' => 'Wakil Bendahara Umum', 'urutan' => 6],
            ['nama_jabatan' => 'Ketua Bidang', 'urutan' => 7],
            ['nama_jabatan' => 'Pengurus Harian', 'urutan' => 8],
            ['nama_jabatan' => 'Anggota Aktif', 'urutan' => 9],
        ];

        foreach ($jabatans as $item) {
            Jabatan::create($item);
        }
    }
}
