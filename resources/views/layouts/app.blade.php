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

        /* Notification Dropdown */
        .notification-wrapper {
            position: relative;
            display: inline-block;
            margin-right: 15px;
        }
        .notification-btn {
            background: transparent;
            border: none;
            cursor: pointer;
            position: relative;
            font-size: 1.2rem;
            color: #0a2540;
            padding: 8px;
            display: flex;
            align-items: center;
        }
        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: #d60b1c;
            color: white;
            font-size: 0.65rem;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 50%;
            transform: translate(25%, -25%);
            border: 2px solid white;
        }
        .notification-dropdown {
            position: absolute;
            top: 100%;
            right: -50px;
            width: 320px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            border: 1px solid rgba(0,0,0,0.05);
            display: none;
            flex-direction: column;
            z-index: 1000;
            margin-top: 10px;
            overflow: hidden;
        }
        .notification-dropdown.show {
            display: flex;
        }
        .notification-header {
            padding: 15px 20px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fc;
        }
        .notification-header h4 {
            margin: 0;
            font-size: 1rem;
            color: #0a2540;
            font-weight: 700;
        }
        .notification-body {
            max-height: 350px;
            overflow-y: auto;
        }
        .notification-item {
            padding: 15px 20px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            gap: 5px;
            text-decoration: none;
            transition: background 0.2s;
        }
        .notification-item:hover {
            background: #f8f9fc;
        }
        .notification-item.unread {
            background: rgba(197, 146, 23, 0.05);
        }
        .notification-item-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: #0a2540;
        }
        .notification-item-message {
            font-size: 0.8rem;
            color: #6b7280;
            line-height: 1.4;
        }
        .notification-item-time {
            font-size: 0.7rem;
            color: #9ca3af;
            margin-top: 4px;
        }
        .notification-empty {
            padding: 30px 20px;
            text-align: center;
            color: #6b7280;
            font-size: 0.85rem;
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
                    @php
                        $user = auth('anggota')->user() ?? $anggota;
                        $unreadNotifications = $user->unreadNotifications;
                    @endphp
                    <div class="notification-wrapper">
                        <button class="notification-btn" id="notificationBtn">
                            <i class="fa fa-bell"></i>
                            @if($unreadNotifications->count() > 0)
                                <span class="notification-badge">{{ $unreadNotifications->count() }}</span>
                            @endif
                        </button>
                        <div class="notification-dropdown" id="notificationDropdown">
                            <div class="notification-header">
                                <h4>Notifikasi</h4>
                                @if($unreadNotifications->count() > 0)
                                    <form action="{{ route('anggota.notifications.readAll') }}" method="POST">
                                        @csrf
                                        <button type="submit" style="background:none;border:none;color:#c59217;font-size:0.75rem;cursor:pointer;font-weight:600;">Tandai semua dibaca</button>
                                    </form>
                                @endif
                            </div>
                            <div class="notification-body">
                                @forelse($user->notifications->take(10) as $notification)
                                    <div class="notification-item {{ $notification->read_at ? '' : 'unread' }}">
                                        <div class="notification-item-title">
                                            @if($notification->data['status'] == 'approved')
                                                <i class="fa fa-check-circle" style="color:#10b981;margin-right:4px;"></i>
                                            @elseif($notification->data['status'] == 'rejected')
                                                <i class="fa fa-times-circle" style="color:#ef4444;margin-right:4px;"></i>
                                            @elseif($notification->data['status'] == 'revision')
                                                <i class="fa fa-exclamation-circle" style="color:#f59e0b;margin-right:4px;"></i>
                                            @endif
                                            {{ $notification->data['title'] }}
                                        </div>
                                        <div class="notification-item-message">
                                            {{ $notification->data['message'] }}
                                            @if(!empty($notification->data['notes']))
                                                <br><strong>Catatan:</strong> {{ $notification->data['notes'] }}
                                            @endif
                                        </div>
                                        <div class="notification-item-time">{{ $notification->created_at->diffForHumans() }}</div>
                                    </div>
                                @empty
                                    <div class="notification-empty">
                                        Belum ada notifikasi baru.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const notifBtn = document.getElementById('notificationBtn');
            const notifDropdown = document.getElementById('notificationDropdown');
            if(notifBtn && notifDropdown) {
                notifBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    notifDropdown.classList.toggle('show');
                });
                document.addEventListener('click', function(e) {
                    if (!notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
                        notifDropdown.classList.remove('show');
                    }
                });
                notifDropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        });
    </script>
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Toast.fire({
                    icon: 'success',
                    title: "{!! addslashes(session('success')) !!}"
                });
            });
        </script>
    @endif
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Toast.fire({
                    icon: 'error',
                    title: "{!! addslashes(session('error')) !!}"
                });
            });
        </script>
    @endif
    @if(session('info'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Toast.fire({
                    icon: 'info',
                    title: "{!! addslashes(session('info')) !!}"
                });
            });
        </script>
    @endif
</body>

</html>