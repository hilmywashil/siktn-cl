@extends('layouts.app')

@section('title', 'Program CSR')

@section('content')
    <section class="wrapper-white-1">
        <div class="white-page-banner-section" data-aos="fade-up">
            <h1>Program CSR SIKTN untuk Membangun Dampak yang Berkelanjutan</h1>
            <p>Melalui berbagai inisiatif Corporate Social Responsibility (CSR), SIKTN berkomitmen memberikan kontribusi
                nyata bagi masyarakat, lingkungan, dan pembangunan sosial yang berkelanjutan melalui kolaborasi anggota dan
                mitra organisasi.</p>
        </div>
    </section>
    <section class="wrapper-white-1">
        <div class="about-section" data-aos="fade-up">
            <div class="left">
                <img src="{{ asset('assets-front/images/about_image.jpg') }}" alt="Tentang Karang Taruna">
            </div>
            <div class="right">
                <h2>Membangun Kepedulian,<br>Menciptakan Dampak Nyata
                </h2>
                <p>Program Corporate Social Responsibility (CSR) SIKTN merupakan wujud komitmen organisasi dalam
                    memberikan kontribusi positif bagi masyarakat, lingkungan, dan pembangunan berkelanjutan. Melalui
                    kolaborasi antara pengurus, anggota, mitra, dan berbagai pemangku kepentingan, SIKTN berupaya
                    menghadirkan program-program yang memberikan manfaat nyata dan berkelanjutan bagi masyarakat luas.
                </p>
                <h2>Tentang Program</h2>
                <div>
                    <p>Program CSR SIKTN dirancang sebagai sarana pengabdian dan kepedulian sosial yang berfokus pada
                        peningkatan kualitas hidup masyarakat melalui berbagai kegiatan di bidang pendidikan, kesehatan,
                        ekonomi, sosial, dan lingkungan.</p>
                    <p>Setiap program dilaksanakan dengan prinsip transparansi, akuntabilitas,
                        keberlanjutan, dan kolaborasi guna memastikan manfaat yang dihasilkan dapat dirasakan secara luas
                        dan
                        berjangka panjang.</p>
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
                    <h2>Meningkatkan Kesejahteraan Masyarakat</h2>
                    <p>Mendorong terciptanya masyarakat yang lebih mandiri dan sejahtera melalui berbagai kegiatan sosial
                        dan pemberdayaan.</p>
                </div>
                <div class="item">
                    <h2>Mendukung Pembangunan Berkelanjutan</h2>
                    <p>Berpartisipasi aktif dalam mendukung program pembangunan yang berorientasi pada keberlanjutan sosial,
                        ekonomi, dan lingkungan.</p>
                </div>
                <div class="item">
                    <h2>Memperkuat Kepedulian Sosial Anggota</h2>
                    <p>Menumbuhkan semangat gotong royong dan kepedulian sosial di kalangan anggota SIKTN.</p>
                </div>
                <div class="item">
                    <h2>Membangun Kolaborasi Positif</h2>
                    <p>Menciptakan sinergi antara organisasi, pemerintah, dunia usaha, dan masyarakat dalam mewujudkan
                        perubahan yang lebih baik.</p>
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
                    <img src="{{ asset('assets-front/images/icons/education.png') }}" alt="">
                    <h2>Pendidikan</h2>
                    <p>Mendukung peningkatan kualitas pendidikan melalui berbagai kegiatan seperti:</p>
                    <ul>
                        <li>Pemberian beasiswa pendidikan</li>
                        <li>Bantuan perlengkapan sekolah</li>
                        <li>Pelatihan keterampilan dan pengembangan kompetensi</li>
                        <li>Seminar dan workshop edukatif</li>
                        <li>Program literasi digital</li>
                    </ul>
                </div>
                <div class="item-2">
                    <img src="{{ asset('assets-front/images/icons/heart-rate.png') }}" alt="">
                    <h2>Kesehatan</h2>
                    <p>Meningkatkan kesadaran dan akses masyarakat terhadap layanan kesehatan melalui:</p>
                    <ul>
                        <li>Pemeriksaan kesehatan gratis</li>
                        <li>Donor darah</li>
                        <li>Pelatihan keterampilan dan pengembangan kompetensi</li>
                        <li>Edukasi kesehatan masyarakat</li>
                        <li>Bantuan alat kesehatan</li>
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
                <h2>Mari Menjadi Bagian<br>dari Perubahan Positif
                </h2>
                <p>Setiap kontribusi yang diberikan memiliki arti besar bagi masyarakat yang membutuhkan. Bergabunglah
                    bersama SIKTN dalam mewujudkan program-program sosial yang memberikan manfaat nyata dan berkelanjutan
                    bagi Indonesia.</p>
                <a href="{{ route('contact') }}" class="btn-yellow-text-black">Hubungi Kami </a>
            </div>
        </div>
    </section>
@endsection