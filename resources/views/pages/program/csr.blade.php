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
@endsection