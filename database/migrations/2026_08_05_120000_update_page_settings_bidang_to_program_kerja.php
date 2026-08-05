<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\PageSetting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        PageSetting::where('key', 'bidang_banner_title')->update([
            'value' => 'Program Kerja SIKTN: Menggerakkan Organisasi Melalui Aksi Nyata'
        ]);

        PageSetting::where('key', 'bidang_banner_desc')->update([
            'value' => 'Program Kerja SIKTN dirancang untuk mendukung pengembangan organisasi, meningkatkan kapasitas anggota, memperkuat kolaborasi, serta mewujudkan program kerja yang terarah, terukur, dan memberikan dampak positif bagi seluruh pemangku kepentingan.'
        ]);

        PageSetting::where('key', 'bidang_about_desc1')->update([
            'value' => 'Program Kerja SIKTN merupakan rangkaian kegiatan strategis yang dirancang untuk mendukung pencapaian visi dan misi organisasi melalui pengembangan kapasitas anggota, penguatan kelembagaan, peningkatan kolaborasi, serta pelaksanaan program kerja yang memberikan manfaat bagi organisasi dan masyarakat. Setiap bidang memiliki peran penting dalam menjalankan fungsi organisasi secara profesional, terstruktur, dan berkelanjutan.'
        ]);

        PageSetting::where('key', 'bidang_about_desc2')->update([
            'value' => '<p>Program Kerja menjadi wadah bagi pengurus dan anggota untuk berkontribusi secara aktif sesuai dengan tugas, fungsi, dan keahlian masing-masing. Melalui berbagai program kerja yang terencana, setiap bidang berupaya menciptakan inovasi, memperkuat sinergi, dan meningkatkan kualitas organisasi secara keseluruhan.</p><p>Program ini dilaksanakan mulai dari tingkat nasional, provinsi, hingga kabupaten/kota guna memastikan pemerataan manfaat dan keterlibatan seluruh anggota.</p>'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
