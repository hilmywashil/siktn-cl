@extends('layouts.app')

@section('title', 'Program Organisasi')

@section('hero-background', asset('assets-front/images/hero_bg.jpg'))
@section('page-title', 'PROGRAM ORGANISASI')
@section('page-description', 'Program dan inisiatif Karang Taruna untuk pembangunan sosial, peningkatan kapasitas anggota, dan kolaborasi.')

@section('content')
    @include('layouts.components.hero')

    <section class="wrapper-lightblue">
        <div class="program-org-section" data-aos="fade-up">
            <div>
                <h2>PROGRAM ORGANISASI</h2>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quam dolorum deserunt sapiente ratione, repellendus tenetur magni nulla iste! Obcaecati neque molestiae nemo ab quis laborum, possimus magnam. Delectus, porro commodi!</p>
            </div>
            <div class="grid">
                <div class="item">
                    <img src="{{ asset('assets-front/images/icons/corporate.png') }}" alt="Icon">
                    <div class="content">
                        <h3>CSR</h3>
                        <p>Melalui berbagai inisiatif Corporate Social Responsibility (CSR), SIKTN berkomitmen memberikan kontribusi nyata bagi masyarakat, lingkungan, dan pembangunan sosial yang berkelanjutan melalui kolaborasi anggota dan mitra organisasi.</p>
                        <a href="{{ route('program.csr') }}">Lihat Detail →</a>
                    </div>
                </div>
                <div class="item">
                    <img src="{{ asset('assets-front/images/icons/system.png') }}" alt="Icon">
                    <div class="content">
                        <h3>BIDANG</h3>
                        <p>Program Bidang SIKTN dirancang untuk mendukung pengembangan organisasi, meningkatkan kapasitas anggota, memperkuat kolaborasi, serta mewujudkan program kerja yang terarah, terukur, dan memberikan dampak positif bagi seluruh pemangku kepentingan.</p>
                        <a href="{{ route('program.bidang') }}">Lihat Detail →</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
