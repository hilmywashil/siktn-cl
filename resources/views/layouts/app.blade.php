<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Karang Taruna</title>
    @include('layouts.components.link')
    
    {{-- Flatpickr CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        /* Global Flatpickr Customization for Frontend */
        .flatpickr-calendar {
            font-family: inherit;
            border: 1px solid #e5e7eb;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
        }
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange, .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover, .flatpickr-day.selected.prevMonthDay, .flatpickr-day.startRange.prevMonthDay, .flatpickr-day.endRange.prevMonthDay, .flatpickr-day.selected.nextMonthDay, .flatpickr-day.startRange.nextMonthDay, .flatpickr-day.endRange.nextMonthDay {
            background: #0a2540; /* SIKTN Blue */
            border-color: #0a2540;
        }
        .flatpickr-day.selected:hover {
            background: #ffd700; /* SIKTN Yellow */
            border-color: #ffd700;
            color: #0a2540;
        }
    </style>
    
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
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>

    {{-- Flatpickr JS --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr(".datepicker", {
                locale: "id",
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d M Y",
                allowInput: true
            });
        });
    </script>

    @stack('scripts')
</body>

</html>