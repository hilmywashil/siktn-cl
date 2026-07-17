<section class="wrapper-page" style="
        background-image:
        linear-gradient(#022648, #02264850),
        url('@yield('hero-background', asset('assets-front/images/hero_bg.jpg'))');
    ">
    <div class="page-banner">
        <div class="page-header">
            <div class="header-inner">
                <div class="logo">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('assets-front/images/logo_karang_taruna.png') }}" alt="Logo Karang Taruna">
                    </a>
                </div>
                <div class="nav-link">
                    <a href="{{ route('home') }}" @if(Route::currentRouteName() == 'home') class="active" @endif>Home</a>
                    <a href="{{ route('organisasi') }}" @if(Route::currentRouteName() == 'organisasi') class="active" @endif>Organisasi</a>
                    <div class="nav-dropdown">
                        <a href="{{ url('/program') }}" @if(request()->is('program*')) class="active" @endif>Program <i class="fa fa-caret-down" style="margin-left: 4px;"></i></a>
                        <div class="nav-dropdown-content">
                            <a href="{{ route('program.csr') }}">CSR</a>
                            <a href="{{ route('program.bidang') }}">Bidang</a>
                        </div>
                    </div>
                    {{-- <a href="{{ url('/agenda') }}" @if(request()->is('agenda*')) class="active" @endif>Agenda</a> --}}
                    <a href="{{ route('e-katalog') }}" @if(Route::currentRouteName() == 'e-katalog') class="active" @endif>E-Katalog</a>
                    <a href="{{ route('berita') }}" @if(Route::currentRouteName() == 'berita') class="active" @endif>Berita</a>
                </div>
                <div class="buttons">
                    {{-- <a href="{{ route('join-us') }}" class="btn-white"><i class="fa fa-user-plus"
                            style="margin-right: 10px"></i> Jadi Anggota</a> --}}
                    <a href="{{ route('anggota.login') }}" class="btn-yellow"><i class="fa fa-sign-in"
                            style="margin-right: 10px"></i> Dashboard Login</a>
                </div>
            </div>
        </div>
        <div class="page-title" data-aos="fade-up">
            <h1>@yield('page-title')</h1>
            <!-- <h2>@yield('page-subtitle')</h2> -->
            <p>@yield('page-description')</p>
            @if (trim($__env->yieldContent('hero-buttons')) == 'show')
                <div class="buttons">
                    <a href="{{ route('about') }}" class="btn-white">
                        Gabung Organisasi</a>
                    <a href="#contact" class="btn-yellow">Struktur
                        Organisasi</a>
                </div>
            @endif
        </div>
    </div>
</section>