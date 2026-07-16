<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // CSR PAGE
            [
                'key' => 'csr_banner_title',
                'value' => 'Program CSR SIKTN untuk Membangun Dampak yang Berkelanjutan',
            ],
            [
                'key' => 'csr_banner_desc',
                'value' => 'Melalui berbagai inisiatif Corporate Social Responsibility (CSR), SIKTN berkomitmen memberikan kontribusi nyata bagi masyarakat, lingkungan, dan pembangunan sosial yang berkelanjutan melalui kolaborasi anggota dan mitra organisasi.',
            ],
            [
                'key' => 'csr_about_title',
                'value' => 'Membangun Kepedulian, Menciptakan Dampak Nyata',
            ],
            [
                'key' => 'csr_about_desc1',
                'value' => 'Program Corporate Social Responsibility (CSR) SIKTN merupakan wujud komitmen organisasi dalam memberikan kontribusi positif bagi masyarakat, lingkungan, dan pembangunan berkelanjutan. Melalui kolaborasi antara pengurus, anggota, mitra, dan berbagai pemangku kepentingan, SIKTN berupaya menghadirkan program-program yang memberikan manfaat nyata dan berkelanjutan bagi masyarakat luas.',
            ],
            [
                'key' => 'csr_about_desc2',
                'value' => '<p>Program CSR SIKTN dirancang sebagai sarana pengabdian dan kepedulian sosial yang berfokus pada peningkatan kualitas hidup masyarakat melalui berbagai kegiatan di bidang pendidikan, kesehatan, ekonomi, sosial, dan lingkungan.</p><p>Setiap program dilaksanakan dengan prinsip transparansi, akuntabilitas, keberlanjutan, dan kolaborasi guna memastikan manfaat yang dihasilkan dapat dirasakan secara luas dan berjangka panjang.</p>',
            ],
            [
                'key' => 'csr_tujuan_json',
                'value' => json_encode([
                    [
                        'title' => 'Meningkatkan Kesejahteraan Masyarakat',
                        'desc' => 'Mendorong terciptanya masyarakat yang lebih mandiri dan sejahtera melalui berbagai kegiatan sosial dan pemberdayaan.'
                    ],
                    [
                        'title' => 'Mendukung Pembangunan Berkelanjutan',
                        'desc' => 'Berpartisipasi aktif dalam mendukung program pembangunan yang berorientasi pada keberlanjutan sosial, ekonomi, dan lingkungan.'
                    ],
                    [
                        'title' => 'Memperkuat Kepedulian Sosial Anggota',
                        'desc' => 'Menumbuhkan semangat gotong royong dan kepedulian sosial di kalangan anggota SIKTN.'
                    ],
                    [
                        'title' => 'Membangun Kolaborasi Positif',
                        'desc' => 'Menciptakan sinergi antara organisasi, pemerintah, dunia usaha, dan masyarakat dalam mewujudkan perubahan yang lebih baik.'
                    ]
                ]),
            ],
            [
                'key' => 'csr_fokus_json',
                'value' => json_encode([
                    [
                        'icon' => 'assets-front/images/icons/education.png',
                        'title' => 'Pendidikan',
                        'desc' => '<p>Mendukung peningkatan kualitas pendidikan melalui berbagai kegiatan seperti:</p><ul><li>Pemberian beasiswa pendidikan</li><li>Bantuan perlengkapan sekolah</li><li>Pelatihan keterampilan dan pengembangan kompetensi</li><li>Seminar dan workshop edukatif</li><li>Program literasi digital</li></ul>'
                    ],
                    [
                        'icon' => 'assets-front/images/icons/heart-rate.png',
                        'title' => 'Kesehatan',
                        'desc' => '<p>Meningkatkan kesadaran dan akses masyarakat terhadap layanan kesehatan melalui:</p><ul><li>Pemeriksaan kesehatan gratis</li><li>Donor darah</li><li>Pelatihan keterampilan dan pengembangan kompetensi</li><li>Edukasi kesehatan masyarakat</li><li>Bantuan alat kesehatan</li></ul>'
                    ]
                ]),
            ],

            // BIDANG PAGE
            [
                'key' => 'bidang_banner_title',
                'value' => 'Program Bidang SIKTN: Menggerakkan Organisasi Melalui Aksi Nyata',
            ],
            [
                'key' => 'bidang_banner_desc',
                'value' => 'Program Bidang SIKTN dirancang untuk mendukung pengembangan organisasi, meningkatkan kapasitas anggota, memperkuat kolaborasi, serta mewujudkan program kerja yang terarah, terukur, dan memberikan dampak positif bagi seluruh pemangku kepentingan.',
            ],
            [
                'key' => 'bidang_about_title',
                'value' => 'Menggerakkan Organisasi Melalui Program Kerja yang Terarah dan Berdampak',
            ],
            [
                'key' => 'bidang_about_desc1',
                'value' => 'Program Bidang SIKTN merupakan rangkaian kegiatan strategis yang dirancang untuk mendukung pencapaian visi dan misi organisasi melalui pengembangan kapasitas anggota, penguatan kelembagaan, peningkatan kolaborasi, serta pelaksanaan program kerja yang memberikan manfaat bagi organisasi dan masyarakat. Setiap bidang memiliki peran penting dalam menjalankan fungsi organisasi secara profesional, terstruktur, dan berkelanjutan.',
            ],
            [
                'key' => 'bidang_about_desc2',
                'value' => '<p>Program Bidang menjadi wadah bagi pengurus dan anggota untuk berkontribusi secara aktif sesuai dengan tugas, fungsi, dan keahlian masing-masing. Melalui berbagai program kerja yang terencana, setiap bidang berupaya menciptakan inovasi, memperkuat sinergi, dan meningkatkan kualitas organisasi secara keseluruhan.</p><p>Program ini dilaksanakan mulai dari tingkat nasional, provinsi, hingga kabupaten/kota guna memastikan pemerataan manfaat dan keterlibatan seluruh anggota.</p>',
            ],
            [
                'key' => 'bidang_tujuan_json',
                'value' => json_encode([
                    [
                        'title' => 'Meningkatkan Kapasitas Organisasi',
                        'desc' => 'Membangun organisasi yang profesional, adaptif, dan siap menghadapi tantangan masa depan.'
                    ]
                ]),
            ],
            [
                'key' => 'bidang_fokus_json',
                'value' => json_encode([
                    [
                        'icon' => 'assets-front/images/icons/user-system.png',
                        'title' => 'Bidang Organisasi dan Kelembagaan',
                        'desc' => '<p>Bertanggung jawab dalam penguatan tata kelola organisasi serta pengembangan struktur kelembagaan.</p><p>Program Kerja:</p><ul><li>Penguatan sistem administrasi organisasi</li><li>Pengembangan database anggota</li><li>Penyusunan regulasi dan SOP</li><li>Monitoring dan evaluasi organisasi</li><li>Digitalisasi tata kelola organisasi</li></ul>'
                    ]
                ]),
            ],
        ];

        foreach ($settings as $setting) {
            \App\Models\PageSetting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
}
