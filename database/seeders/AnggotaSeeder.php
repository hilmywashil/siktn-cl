<?php

namespace Database\Seeders;

use App\Models\Anggota;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class AnggotaSeeder extends Seeder
{
    public function run(): void
    {
        // 27 Kabupaten/Kota di Jawa Barat
        $domisiliList = [
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

        $legalitasUsaha = ['PT', 'CV', 'PT Perorangan'];
        $bidangUsaha = [
            'Teknologi Informasi',
            'Perdagangan',
            'Manufaktur',
            'Jasa Konstruksi',
            'Konsultan',
            'F&B',
            'Fashion',
            'Pendidikan',
            'Kesehatan',
            'Transportasi',
            'Properti',
            'Agribisnis',
        ];

        $usiaPerusahaan = ['< 1 tahun', '1-3 tahun', '3-5 tahun', '> 5 tahun'];
        $omsetPerusahaan = ['< 300 juta', '300 juta - 2,5 miliar', '2,5 miliar - 50 miliar', '> 50 miliar'];
        $agama = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];

        // Buat dummy data untuk setiap domisili (2-5 anggota per domisili)
        foreach ($domisiliList as $domisili) {
            $jumlahAnggota = rand(2, 5);
            
            for ($i = 1; $i <= $jumlahAnggota; $i++) {
                $namaUsaha = $this->generateNama();
                $status = ['pending', 'approved', 'rejected'][rand(0, 2)];
                
                Anggota::create([
                    // Data Pribadi
                    'nama_usaha' => $namaUsaha,
                    'jenis_kelamin' => rand(0, 1) ? 'Laki-laki' : 'Perempuan',
                    'tempat_lahir' => $domisili,
                    'tanggal_lahir' => now()->subYears(rand(25, 50))->format('Y-m-d'),
                    'agama' => $agama[array_rand($agama)],
                    'nomor_telepon' => '08' . rand(1000000000, 9999999999),
                    'domisili' => $domisili,
                    'alamat_domisili' => 'Jl. ' . $this->generateJalan() . ' No.' . rand(1, 100) . ', ' . $domisili,
                    'kode_pos' => rand(40000, 49999),
                    'email' => strtolower(str_replace(' ', '', $namaUsaha)) . rand(1, 999) . '@example.com',
                    'nomor_ktp' => $this->generateKTP(),
                    'foto_ktp' => 'anggota/ktp/dummy-ktp-' . rand(1, 100) . '.jpg',
                    'foto_diri' => 'anggota/foto/dummy-foto-' . rand(1, 100) . '.jpg',
                    
                    // Profile Perusahaan
                    'nama_usaha_perusahaan' => $legalitasUsaha[array_rand($legalitasUsaha)] . ' ' . $this->generateNamaPerusahaan(),
                    'legalitas_usaha' => $legalitasUsaha[array_rand($legalitasUsaha)],
                    'jabatan_usaha' => ['Direktur Utama', 'CEO', 'Managing Director', 'Owner'][rand(0, 3)],
                    'alamat_kantor' => 'Jl. ' . $this->generateJalan() . ' No.' . rand(1, 100) . ', ' . $domisili,
                    'bidang_usaha' => $bidangUsaha[array_rand($bidangUsaha)],
                    'brand_usaha' => $this->generateBrand(),
                    'jumlah_karyawan' => rand(5, 500),
                    'nomor_ktp_perusahaan' => $this->generateKTP(),
                    'usia_perusahaan' => $usiaPerusahaan[array_rand($usiaPerusahaan)],
                    'omset_perusahaan' => $omsetPerusahaan[array_rand($omsetPerusahaan)],
                    'npwp_perusahaan' => $this->generateNPWP(),
                    'no_nota_pendirian' => 'AHU-' . rand(1000000, 9999999) . '.AH.01.01',
                    'profile_perusahaan' => 'anggota/profile/dummy-profile-' . rand(1, 100) . '.pdf',
                    'logo_perusahaan' => 'anggota/logo/dummy-logo-' . rand(1, 100) . '.png',
                    
                    // Organisasi
                    'sfc_hipmi' => ['Teknologi & Inovasi', 'Ekonomi Kreatif', 'UMKM', 'Industri', 'Perdagangan'][rand(0, 4)],
                    'referensi_hipmi' => rand(0, 1) ? 'Ya' : 'Tidak',
                    'organisasi_lain' => rand(0, 1) ? 'Ya' : 'Tidak',
                    
                    // Status
                    'status' => $status,
                    'rejection_reason' => $status === 'rejected' ? 'Data tidak lengkap atau tidak sesuai persyaratan' : null,
                    'approved_at' => $status === 'approved' ? now()->subDays(rand(1, 30)) : null,
                    'approved_by' => $status !== 'pending' ? rand(1, 3) : null,
                ]);
            }
        }
    }

    // Helper functions untuk generate dummy data
    private function generateNama(): string
    {
        $namaDepan = ['Ahmad', 'Budi', 'Candra', 'Dewi', 'Eka', 'Fitri', 'Gani', 'Hani', 'Indra', 'Joko', 'Kartika', 'Lina', 'Maya', 'Nanda', 'Oki', 'Putri', 'Rizki', 'Sari', 'Toni', 'Udin', 'Vina', 'Wawan', 'Yanti', 'Zaki'];
        $namaBelakang = ['Pratama', 'Wijaya', 'Kusuma', 'Permana', 'Saputra', 'Nugraha', 'Hidayat', 'Ramadhan', 'Santoso', 'Wibowo', 'Gunawan', 'Setiawan', 'Firmansyah', 'Hakim', 'Rahman'];
        
        return $namaDepan[array_rand($namaDepan)] . ' ' . $namaBelakang[array_rand($namaBelakang)];
    }

    private function generateNamaPerusahaan(): string
    {
        $prefix = ['Maju', 'Jaya', 'Sukses', 'Prima', 'Gemilang', 'Sentosa', 'Abadi', 'Karya', 'Mega', 'Indo'];
        $suffix = ['Mandiri', 'Bersama', 'Sejahtera', 'Makmur', 'Utama', 'Nusantara', 'Internasional', 'Global', 'Persada', 'Raya'];
        
        return $prefix[array_rand($prefix)] . ' ' . $suffix[array_rand($suffix)];
    }

    private function generateBrand(): string
    {
        $brands = ['BrandTech', 'MegaStore', 'PrimaMart', 'EliteShop', 'TopQuality', 'BestChoice', 'SmartSolution', 'ProService', 'MaxValue', 'SuperGoods'];
        
        return $brands[array_rand($brands)];
    }

    private function generateJalan(): string
    {
        $jalan = ['Merdeka', 'Sudirman', 'Gatot Subroto', 'Ahmad Yani', 'Diponegoro', 'Raya Bandung', 'Asia Afrika', 'Soekarno Hatta', 'Cihampelas', 'Dago', 'Setiabudhi', 'Buah Batu', 'Kopo', 'Soreang'];
        
        return $jalan[array_rand($jalan)];
    }

    private function generateKTP(): string
    {
        return '32' . rand(10, 99) . rand(1000000000, 9999999999);
    }

    private function generateNPWP(): string
    {
        return rand(10, 99) . '.' . rand(100, 999) . '.' . rand(100, 999) . '.' . rand(1, 9) . '-' . rand(100, 999) . '.' . rand(100, 999);
    }
}