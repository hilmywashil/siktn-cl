<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
            PeriodeKepengurusanSeeder::class,
            JabatanSeeder::class,
            KategoriEkatalogSeeder::class,
            AnggotaSeeder::class,
            PageSettingSeeder::class,
            StrategicPlanSeeder::class,
            ProgramSeeder::class,
        ]);
    }
}