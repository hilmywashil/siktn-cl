@extends('layouts.app')

@section('title', 'Program CSR')

@section('content')
    <section class="wrapper-white-1">
        <div class="white-page-banner-section" data-aos="fade-up">
            <h1>Program Bidang SIKTN: Menggerakkan Organisasi Melalui Aksi Nyata</h1>
            <p>Program Bidang SIKTN dirancang untuk mendukung pengembangan organisasi, meningkatkan kapasitas anggota,
                memperkuat kolaborasi, serta mewujudkan program kerja yang terarah, terukur, dan memberikan dampak positif
                bagi seluruh pemangku kepentingan.</p>
        </div>
    </section>
    <section class="wrapper-white-1">
        <div class="about-section" data-aos="fade-up">
            <div class="left">
                <img src="{{ asset('assets-front/images/about_image.jpg') }}" alt="Tentang Karang Taruna">
            </div>
            <div class="right">
                <h2>Menggerakkan Organisasi Melalui Program Kerja yang Terarah dan Berdampak
                </h2>
                <p>Program Bidang SIKTN merupakan rangkaian kegiatan strategis yang dirancang untuk mendukung pencapaian
                    visi dan misi organisasi melalui pengembangan kapasitas anggota, penguatan kelembagaan, peningkatan
                    kolaborasi, serta pelaksanaan program kerja yang memberikan manfaat bagi organisasi dan masyarakat.
                    Setiap bidang memiliki peran penting dalam menjalankan fungsi organisasi secara profesional,
                    terstruktur, dan berkelanjutan.
                </p>
                <h2>Tentang Program</h2>
                <div>
                    <p>Program Bidang menjadi wadah bagi pengurus dan anggota untuk berkontribusi secara aktif sesuai dengan
                        tugas, fungsi, dan keahlian masing-masing. Melalui berbagai program kerja yang terencana, setiap
                        bidang berupaya menciptakan inovasi, memperkuat sinergi, dan meningkatkan kualitas organisasi secara
                        keseluruhan.</p>
                    <p>
                        Program ini dilaksanakan mulai dari tingkat nasional, provinsi, hingga kabupaten/kota guna
                        memastikan pemerataan manfaat dan keterlibatan seluruh anggota.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="wrapper-blue-1">
        <div class="stats-section" data-aos="fade-up">
            <div class="grid">
                <div class="item">
                    <div class="icon">
                        <img src="{{ asset('assets-front/images/icons/user-group.png') }}" alt="Icon">
                    </div>
                    <h2>200+</h2>
                    <p>Total Anggota</p>
                </div>
                <div class="item">
                    <div class="icon">
                        <img src="{{ asset('assets-front/images/icons/location.png') }}" alt="Icon">
                    </div>
                    <h2>150+</h2>
                    <p>Total Wilayah</p>
                </div>
                <div class="item">
                    <div class="icon">
                        <img src="{{ asset('assets-front/images/icons/puzzle.png') }}" alt="Icon">
                    </div>
                    <h2>20</h2>
                    <p>Total Program</p>
                </div>
                <div class="item">
                    <div class="icon">
                        <img src="{{ asset('assets-front/images/icons/catalog.png') }}" alt="Icon">
                    </div>
                    <h2>120+</h2>
                    <p>Total E-Catalog</p>
                </div>
            </div>
        </div>
    </section>
    <section class="wrapper-lightblue">
        <div class="tujuan-section" data-aos="fade-up">
            <h1>Tujuan Program</h1>
            <div class="grid">
                <div class="item">
                    <h2>Meningkatkan Kapasitas Organisasi</h2>
                    <p>Membangun organisasi yang profesional, adaptif, dan siap menghadapi tantangan masa depan.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="wrapper-white-1">
        <div class="tujuan-section" data-aos="fade-up">
            <h1>Fokus Program CSR</h1>
            <div class="grid">
                {{-- LOOP AREA --}}
                <div class="item-2">
                    <img src="{{ asset('assets-front/images/icons/user-system.png') }}" alt="">
                    <h2>Bidang Organisasi dan Kelembagaan</h2>
                    <p>Bertanggung jawab dalam penguatan tata kelola organisasi serta pengembangan
                        struktur kelembagaan.</p>
                    <p>Program Kerja:</p>
                    <ul>
                        <li>Penguatan sistem administrasi organisasi</li>
                        <li>Pengembangan database anggota</li>
                        <li>Penyusunan regulasi dan SOP</li>
                        <li>Monitoring dan evaluasi organisasi</li>
                        <li>Digitalisasi tata kelola organisasi</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section class="wrapper-lightblue">
        <div class="about-section" data-aos="fade-up">
            <div class="left">
                <img src="{{ asset('assets-front/images/bg_5.jpg') }}" alt="Tentang Karang Taruna">
            </div>
            <div class="right">
                <h2>Mari Berkontribusi dalam<br>Program Bidang SIKTN
                </h2>
                <p>Setiap ide, inovasi, dan kontribusi anggota menjadi bagian penting dalam kemajuan organisasi.
                    Bergabunglah dalam berbagai program bidang dan ambil peran aktif dalam membangun organisasi yang lebih
                    profesional, modern, dan berdampak.</p>
                <a href="{{ route('contact') }}" class="btn-yellow-text-black">Hubungi Kami </a>
            </div>
        </div>
    </section>
@endsection