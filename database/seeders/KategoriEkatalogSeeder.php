<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriEatalog;
use Illuminate\Support\Str;

class KategoriEkatalogSeeder extends Seeder
{
    public function run(): void
    {
        $kategoriList = [
            'Makanan & Minuman (F&B)',
            'Teknologi & Informasi',
            'Jasa & Konsultan',
            'Kerajinan & Kriya',
            'Fashion & Tekstil',
            'Agribisnis & Pertanian',
            'Perdagangan & Manufaktur',
            'Properti & Konstruksi',
        ];

        foreach ($kategoriList as $nama) {
            KategoriEatalog::firstOrCreate([
                'slug' => Str::slug($nama),
            ], [
                'nama' => $nama,
                'deskripsi' => 'Kategori produk & jasa usaha ' . $nama,
                'is_active' => true,
            ]);
        }
    }
}
