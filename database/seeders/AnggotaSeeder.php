<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\Katalog;
use App\Models\KategoriEatalog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AnggotaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. DEDICATED TEST ANGGOTA ACCOUNT (DaDANG)
        $testAnggota = Anggota::updateOrCreate(
            ['username' => 'dadang'],
            [
                'nama_lengkap' => 'DaDANG',
                'nik' => '3273011205950001',
                'tempat_lahir' => 'Kota Bandung',
                'tanggal_lahir' => '1995-05-12',
                'alamat_lengkap' => 'Jl. Asia Afrika No. 10, Kota Bandung',
                'domisili' => 'Kota Bandung',
                'jabatan' => 'Anggota Aktif',
                'pendidikan_terakhir' => 'S1 / Sarjana',
                'pekerjaan' => 'Wirausaha / CEO',
                'riwayat_organisasi' => 'Pengurus Karang Taruna Kota Bandung (2022-2026)',
                'kompetensi' => 'Digital Marketing, IT & Business Development',
                'no_hp' => '081234567890',
                'email' => 'dadang@siktn.com',
                'password' => Hash::make('password123'),
                'initial_password' => 'password123',
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => 1,
                'instagram' => '@dadang_siktn',
                'tiktok' => '@dadang_siktn',
            ]
        );

        // Assign Spatie Role for Anggota
        $roleAnggota = Role::where('name', 'anggota')->where('guard_name', 'anggota')->first();
        if ($roleAnggota && $testAnggota) {
            $testAnggota->assignRole($roleAnggota);
        }

        // Seed Katalog for DaDANG
        $kategoriIT = KategoriEatalog::where('nama', 'LIKE', '%Teknologi%')->first() ?? KategoriEatalog::first();
        Katalog::updateOrCreate(
            ['anggota_id' => $testAnggota->id],
            [
                'company_name' => 'PT Jaya Sukses Digital',
                'business_field' => 'Teknologi Informasi & Digital Marketing',
                'description' => 'Perusahaan konsultan IT dan Digital Marketing terpercaya di Kota Bandung yang melayani pembuatan website, aplikasi mobile, dan strategi pemasaran digital.',
                'address' => 'Jl. Asia Afrika No. 10, Kota Bandung',
                'wilayah' => 'Kota Bandung',
                'phone' => '081234567890',
                'email' => 'info@jayadigital.co.id',
                'website_url' => 'https://jayadigital.co.id',
                'kategori_id' => $kategoriIT ? $kategoriIT->id : null,
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => 1,
                'is_active' => true,
            ]
        );

        // 2. DUMMY ANGGOTA ACCOUNTS ACROSS JAWA BARAT
        $domisiliList = [
            'Kota Bandung', 'Bandung', 'Bandung Barat', 'Kota Cimahi',
            'Kota Bogor', 'Bogor', 'Kota Depok', 'Kota Bekasi', 'Bekasi',
            'Kota Cirebon', 'Cirebon', 'Garut', 'Tasikmalaya', 'Subang',
            'Sumedang', 'Sukabumi', 'Purwakarta', 'Karawang', 'Indramayu'
        ];

        $kategoriList = KategoriEatalog::all();

        foreach ($domisiliList as $index => $domisili) {
            $nama = $this->generateNama($index);
            $username = strtolower(str_replace(' ', '', $nama)) . ($index + 1);
            $status = ['approved', 'approved', 'pending', 'rejected'][$index % 4];

            $anggota = Anggota::updateOrCreate(
                ['username' => $username],
                [
                    'nama_lengkap' => $nama,
                    'nik' => '32' . rand(10, 99) . rand(1000000000, 9999999999),
                    'tempat_lahir' => $domisili,
                    'tanggal_lahir' => now()->subYears(rand(22, 45))->format('Y-m-d'),
                    'alamat_lengkap' => 'Jl. ' . $this->generateJalan() . ' No. ' . rand(1, 100) . ', ' . $domisili,
                    'domisili' => $domisili,
                    'jabatan' => ['Pengurus Harian', 'Anggota Aktif', 'Ketua Bidang'][$index % 3],
                    'pendidikan_terakhir' => ['SMA / Sederajat', 'D3 / Diploma', 'S1 / Sarjana', 'S2 / Magister'][$index % 4],
                    'pekerjaan' => ['Pelaku UMKM', 'Wirausaha', 'Karyawan Swasta', 'Professional'][$index % 4],
                    'riwayat_organisasi' => 'Pengurus Karang Taruna ' . $domisili,
                    'kompetensi' => 'Kewirausahaan, Manajemen Usaha, Pemberdayaan Pemuda',
                    'no_hp' => '08' . rand(1000000000, 9999999999),
                    'email' => $username . '@example.com',
                    'password' => Hash::make('password123'),
                    'initial_password' => 'password123',
                    'status' => $status,
                    'rejection_reason' => $status === 'rejected' ? 'Dokumen persyaratan belum lengkap' : null,
                    'approved_at' => $status === 'approved' ? now()->subDays(rand(1, 30)) : null,
                    'approved_by' => $status === 'approved' ? 1 : null,
                ]
            );

            if ($roleAnggota && $anggota) {
                $anggota->assignRole($roleAnggota);
            }

            // Create Katalog for approved anggota
            if ($status === 'approved' && $kategoriList->count() > 0) {
                $kat = $kategoriList[$index % $kategoriList->count()];
                $companyName = 'PT ' . $this->generateNamaPerusahaan($index);

                Katalog::updateOrCreate(
                    ['anggota_id' => $anggota->id],
                    [
                        'company_name' => $companyName,
                        'business_field' => $kat->nama,
                        'description' => 'Usaha ' . $kat->nama . ' terdepan di wilayah ' . $domisili . ' yang fokus pada kualitas produk dan kepuasan pelanggan.',
                        'address' => $anggota->alamat_lengkap,
                        'wilayah' => $domisili,
                        'phone' => $anggota->no_hp,
                        'email' => strtolower(str_replace(' ', '', $companyName)) . '@example.com',
                        'website_url' => 'https://' . strtolower(str_replace([' ', 'PT'], '', $companyName)) . '.com',
                        'kategori_id' => $kat->id,
                        'status' => 'approved',
                        'approved_at' => now(),
                        'approved_by' => 1,
                        'is_active' => true,
                    ]
                );
            }
        }
    }

    private function generateNama(int $index): string
    {
        $namaDepan = ['Ahmad', 'Budi', 'Candra', 'Dewi', 'Eka', 'Fitri', 'Gani', 'Hani', 'Indra', 'Joko', 'Kartika', 'Lina', 'Maya', 'Nanda', 'Oki', 'Putri', 'Rizki', 'Sari', 'Toni', 'Udin'];
        $namaBelakang = ['Pratama', 'Wijaya', 'Kusuma', 'Permana', 'Saputra', 'Nugraha', 'Hidayat', 'Ramadhan', 'Santoso', 'Wibowo'];
        
        return $namaDepan[$index % count($namaDepan)] . ' ' . $namaBelakang[$index % count($namaBelakang)];
    }

    private function generateNamaPerusahaan(int $index): string
    {
        $prefix = ['Maju', 'Jaya', 'Sukses', 'Prima', 'Gemilang', 'Sentosa', 'Abadi', 'Karya', 'Mega', 'Indo'];
        $suffix = ['Mandiri', 'Bersama', 'Sejahtera', 'Makmur', 'Utama', 'Nusantara', 'Internasional', 'Global', 'Persada', 'Raya'];
        
        return $prefix[$index % count($prefix)] . ' ' . $suffix[$index % count($suffix)];
    }

    private function generateJalan(): string
    {
        $jalan = ['Merdeka', 'Sudirman', 'Gatot Subroto', 'Ahmad Yani', 'Diponegoro', 'Raya Bandung', 'Asia Afrika', 'Soekarno Hatta', 'Cihampelas', 'Dago'];
        return $jalan[array_rand($jalan)];
    }
}