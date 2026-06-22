@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('hero-background', asset('assets-front/images/hero_bg.jpg'))
@section('page-title', 'TENTANG KARANG TARUNA')
@section('page-subtitle', 'Karang Taruna')
@section('page-description', 'Dengan semangat kolaborasi, inovasi, dan profesionalisme, SIKTN menjadi wadah yang mempertemukan anggota dari berbagai wilayah untuk bersama-sama mengembangkan organisasi yang adaptif, berdaya saing, dan berkelanjutan.')
@section('hero-buttons', 'hide')

@section('content')
    @include('layouts.components.hero')
    <section class="wrapper-white-1">
        <div class="about-section" data-aos="fade-up">
            <div class="left">
                <img src="{{ asset('assets-front/images/about_image.jpg') }}" alt="">
            </div>
            <div class="right">
                <h2>Tentang Kami<br>Karang Taruna</h2>
                <div>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eligendi saepe, possimus ut in minus nam
                        illum placeat cupiditate animi fuga ipsa iusto dignissimos soluta, quaerat, magnam eveniet nostrum
                        error illo?
                    </p>
                    <p>
                        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nulla incidunt accusamus ab! Error sit
                        voluptatem doloribus repudiandae illo minus sed, debitis ratione magni? Quis eligendi odio amet,
                        explicabo sapiente quod!
                    </p>
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
        <div class="visi-misi-section" data-aos="fade-up">
            <div class="left">
                <div class="visi-container">
                    <h2>VISI KAMI</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                        et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                        aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit</p>
                </div>
                <div class="misi-container">
                    <h2>MISI KAMI</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                        et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                        aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit</p>
                </div>
            </div>
            <div class="right">
                <img src="{{ asset('assets-front/images/bg_5.jpg') }}" alt="">
            </div>
        </div>
    </section>
    <section class="wrapper-white-1">
        <div class="about-section" data-aos="fade-up">
            <div class="left">
                <img src="{{ asset('assets-front/images/foto_1.jpg') }}" alt="About Image">
            </div>
            <div class="right">
                <h2>Kenapa Harus<br>Bergabung Bersama Kami?</h2>
                <div>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eligendi saepe, possimus ut in minus nam
                        illum placeat cupiditate animi fuga ipsa iusto dignissimos soluta, quaerat, magnam eveniet nostrum
                        error illo?
                    </p>
                    <p>
                        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nulla incidunt accusamus ab! Error sit
                        voluptatem doloribus repudiandae illo minus sed, debitis ratione magni? Quis eligendi odio amet,
                        explicabo sapiente quod!
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="wrapper-image-3">
        <div class="bergabung-section" data-aos="fade-up">
            <h1>“Bersama Membangun Organisasi yang Lebih Kuat dan Terintegrasi”</h1>
            <a href="{{ route('contact') }}" class="btn-yellow"><i class="fa fa-phone" style="margin-right: 10px;"></i>
                Hubungi Sekretariat</a>
            {{-- <div class="buttons">
                <a href="{{ route('contact') }}" class="btn-yellow"><i class="fa fa-phone" style="margin-right: 10px;"></i>
                    Hubungi Sekretariat</a>
                <a href="{{ route('contact') }}" class="btn-yellow"><i class="fa fa-phone" style="margin-right: 10px;"></i>
                    Hubungi Sekretariat</a>
            </div> --}}
        </div>
    </section>
@endsection