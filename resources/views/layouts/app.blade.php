<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Karang Taruna</title>
    @include('layouts.components.link')
    @stack('styles')
</head>

<body>
    <header class="header-sticky" id="headerSticky">
        <div class="header-inner">
            <div class="logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('assets-front/images/logo_karang_taruna.png') }}" alt="Logo Karang Taruna">
                </a>
            </div>
            <div class="nav-link">
                <a href="{{ route('home') }}" @if(Route::currentRouteName() == 'home') class="active" @endif>Beranda</a>
                <a href="{{ route('about') }}" @if(Route::currentRouteName() == 'about') class="active" @endif>Tentang</a>
                <a href="{{ route('organisasi') }}" @if(Route::currentRouteName() == 'organisasi') class="active"
                @endif>Organisasi</a>
                <a href="{{ route('e-katalog') }}" @if(Route::currentRouteName() == 'e-katalog') class="active"
                @endif>E-Catalog</a>
                <a href="{{ route('berita') }}" @if(Route::currentRouteName() == 'berita') class="active"
                @endif>Berita</a>
            </div>
            <div class="buttons">
                @if(auth('admin')->check())
                    <a href="{{ route('admin.dashboard') }}" class="btn-yellow-border-black">Dashboard Admin</a>
                    <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-transparent-border-black"
                            style="cursor: pointer; padding: 12px 20px; border: 1px solid #000; border-radius: 7px; background: transparent; font-family: inherit; font-size: 14px; font-weight: 500;">
                            Keluar
                        </button>
                    </form>
                @elseif(auth('anggota')->check() || isset($anggota))
                    <a href="{{ route('profile-anggota') }}" class="btn-yellow-border-black">Dashboard</a>
                    <form action="{{ route('anggota.logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-transparent-border-black"
                            style="cursor: pointer; padding: 12px 20px; border: 1px solid #000; border-radius: 7px; background: transparent; font-family: inherit; font-size: 14px; font-weight: 500;">
                            Keluar
                        </button>
                    </form>
                @else
                    {{-- <a href="{{ route('join-us') }}" class="btn-transparent-border-black">Jadi Anggota</a> --}}
                    <a href="{{ route('anggota.login') }}" class="btn-yellow-border-black"><i class="fa fa-sign-in"
                            style="margin-right: 10px"></i> Dashboard Login</a>
                @endif
            </div>
        </div>
    </header>

    @yield('hero-section')

    @yield('content')

    <div id="scrollTop">
        <svg width="50" height="50">
            <circle cx="25" cy="25" r="22"></circle>
        </svg>
        <i class="fa-solid fa-arrow-up"></i>
    </div>

    @include('layouts.components.footer')
    @include('layouts.components.script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')
</body>

</html>