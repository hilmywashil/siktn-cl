<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;
use App\Models\Jabatan;
use Carbon\Carbon;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $jabatan = Jabatan::first();
        $jabatanId = $jabatan ? $jabatan->id : null;

        // Program Kerja 1
        $p1 = Program::updateOrCreate(
            ['nama_program' => 'Pelatihan & Sertifikasi Digital Entrepreneurship Pemuda'],
            [
                'kategori' => 'Bidang',
                'jabatan_id' => $jabatanId,
                'target_output' => 'Melatih 500 pemuda Karang Taruna dalam kemampuan pemasaran digital, branding produk UMKM, dan manajemen keuangan usaha.',
                'periode_mulai' => Carbon::now()->startOfMonth(),
                'periode_selesai' => Carbon::now()->addMonths(3)->endOfMonth(),
                'pic' => 'Ahmad Pratama, S.Kom',
                'gambar_url' => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=800&q=80',
                'status' => 'aktif',
            ]
        );

        // Program Kerja 2
        $p2 = Program::updateOrCreate(
            ['nama_program' => 'Bakti Sosial & Pemberdayaan Ekonomi Desa Berbasis Karang Taruna'],
            [
                'kategori' => 'Bidang',
                'jabatan_id' => $jabatanId,
                'target_output' => 'Penyaluran bantuan sembako, pemeriksaan kesehatan gratis, dan pendampingan pembentukan Koperasi Pemuda di 10 desa sasaran.',
                'periode_mulai' => Carbon::now()->subMonth(),
                'periode_selesai' => Carbon::now()->addMonths(2),
                'pic' => 'Budi Santoso, S.T.',
                'gambar_url' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&w=800&q=80',
                'status' => 'aktif',
            ]
        );

        // Program Kerja 3
        $p3 = Program::updateOrCreate(
            ['nama_program' => 'Gerakan Karang Taruna Go Green & Pengolahan Sampah Organik'],
            [
                'kategori' => 'Bidang',
                'jabatan_id' => $jabatanId,
                'target_output' => 'Penanaman 1.000 bibit pohon produktif dan pembentukan Bank Sampah pemuda di setiap wilayah percontohan.',
                'periode_mulai' => Carbon::now()->addMonth(),
                'periode_selesai' => Carbon::now()->addMonths(5),
                'pic' => 'Dewi Lestari, S.Pd',
                'gambar_url' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=80',
                'status' => 'aktif',
            ]
        );

        // Program CSR 1 (Terkoneksi ke Program Kerja 1)
        Program::updateOrCreate(
            ['nama_program' => 'Sponsor Utama Program Sertifikasi Digital UMKM Pemuda'],
            [
                'kategori' => 'CSR',
                'program_kerja_id' => $p1->id,
                'target_output' => 'Dukungan pendanaan pelatihan, perlengkapan modul digital, dan beasiswa sertifikasi BNSP bagi 100 peserta terbaik.',
                'periode_mulai' => Carbon::now()->startOfMonth(),
                'periode_selesai' => Carbon::now()->addMonths(3)->endOfMonth(),
                'pic' => 'PT Bank Mandiri (Persero) Tbk',
                'gambar_url' => 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&w=800&q=80',
                'status' => 'aktif',
            ]
        );
    }
}
