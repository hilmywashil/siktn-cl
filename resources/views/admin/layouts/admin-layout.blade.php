{{-- resources/views/admin/layouts/admin-layout.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <title>@yield('title', 'Dashboard Admin') - Karang Taruna</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    {{-- Admin Styles --}}
    <link rel="stylesheet" href="{{ asset('assets-front/css/admin-layout.css') }}">

    {{-- Additional Page Styles --}}
    @stack('styles')

    {{-- Select2 CSS --}}
    <link href="{{ asset('vendor/select2/select2.min.css') }}" rel="stylesheet" />

    {{-- SweetAlert2 --}}
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <style>
        /* SweetAlert2 Global Customization for SIKTN */
        .swal2-popup {
            font-family: 'Montserrat', sans-serif !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2) !important;
        }
        
        .swal2-title {
            color: #0a2540 !important;
            font-size: 1.25rem !important;
            font-weight: 700 !important;
        }
        
        .swal2-html-container {
            color: #6b7280 !important;
            font-size: 0.95rem !important;
        }
        
        .swal2-actions {
            gap: 0.75rem !important;
        }
        
        .swal2-confirm {
            background-color: #022648 !important; /* Navy Blue SIKTN */
            color: white !important;
            border-radius: 8px !important;
            padding: 0.625rem 1.5rem !important;
            font-weight: 600 !important;
            transition: all 0.2s !important;
        }
        
        .swal2-confirm:hover {
            background-color: #1c2780 !important;
            box-shadow: 0 4px 12px rgba(11, 19, 84, 0.3) !important;
        }
        
        .swal2-cancel {
            background-color: #f3f4f6 !important;
            color: #374151 !important;
            border-radius: 8px !important;
            padding: 0.625rem 1.5rem !important;
            font-weight: 600 !important;
            transition: all 0.2s !important;
        }
        
        .swal2-cancel:hover {
            background-color: #e5e7eb !important;
        }
        
        .swal2-confirm.btn-restore {
            background-color: #10b981 !important;
        }
        
        .swal2-confirm.btn-danger {
            background-color: #dc2626 !important; /* Red for Delete */
        }
        
        .swal2-confirm.btn-danger:hover {
            background-color: #b91c1c !important; 
        }
        
        /* SweetAlert2 TOAST Customization (for flash messages) */
        .swal2-toast {
            background: #ffffff !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
            border-left: 4px solid #C59217 !important;
            padding: 12px 20px !important;
            border-radius: 8px !important;
        }
        .swal2-toast.swal2-icon-success { border-left-color: #10b981 !important; }
        .swal2-toast.swal2-icon-error { border-left-color: #dc2626 !important; }
        .swal2-toast.swal2-toast-deleted { border-left-color: #dc2626 !important; }
        .swal2-toast .swal2-title { font-size: 0.95rem !important; margin-left: 10px !important; font-weight: 600 !important; }
    </style>

    <style>
        /* Logout Confirmation Modal */
        .logout-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .logout-modal.active {
            display: flex;
        }

        .logout-modal-content {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logout-modal-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .logout-modal-icon {
            width: 48px;
            height: 48px;
            background: #fee2e2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logout-modal-icon svg {
            width: 24px;
            height: 24px;
            stroke: #dc2626;
            fill: none;
            stroke-width: 2;
        }

        .logout-modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0a2540;
        }

        .logout-modal-text {
            color: #6b7280;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .logout-modal-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
        }

        .modal-btn {
            padding: 0.625rem 1.5rem;
            border-radius: 8px;
            border: none;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Montserrat', sans-serif;
        }

        .modal-btn-cancel {
            background: #f3f4f6;
            color: #374151;
        }

        .modal-btn-cancel:hover {
            background: #e5e7eb;
        }

        .modal-btn-confirm {
            background: #dc2626;
            color: white;
        }

        .modal-btn-confirm:hover {
            background: #b91c1c;
        }

        /* Topbar User Profile */
        .topbar-user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: #f9fafb;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none;
        }

        .topbar-user-profile:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .topbar-user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ffd700;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0a2540;
            font-weight: 700;
            font-size: 0.875rem;
            flex-shrink: 0;
            overflow: hidden;
        }

        .topbar-user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .topbar-user-details {
            display: flex;
            flex-direction: column;
        }

        .topbar-user-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: #0a2540;
            line-height: 1.2;
        }

        .topbar-user-role {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 500;
        }

        @media (max-width: 1024px) {
            .topbar-user-profile {
                display: none;
            }
        }

        /* Select2 Customization for SIKTN */
        .select2-container--default .select2-selection--single {
            height: 46px;
            padding: 0.35rem 0.75rem;
            font-size: 0.875rem;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            color: #0a2540;
            background-color: #f9fafb;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #ffd700;
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.2);
            background-color: #ffffff;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #0a2540;
            padding-left: 0;
            line-height: normal;
        }
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #6b7280;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px;
            right: 10px;
        }
        .select2-dropdown {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            font-family: 'Montserrat', sans-serif;
            font-size: 0.875rem;
            z-index: 9999;
        }
        .select2-search--dropdown .select2-search__field {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 0.5rem;
            font-family: 'Montserrat', sans-serif;
            outline: none;
        }
        .select2-search--dropdown .select2-search__field:focus {
            border-color: #ffd700;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected],
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: #0a2540;
            color: white;
        }
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #f3f4f6;
            color: #0a2540;
            font-weight: 600;
        }
        .select2-results__option {
            padding: 8px 12px;
        }
    </style>
    <style>
        /* Birthday Sidebar Styles */
        .birthday-fab {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #C59217;
            color: white;
            border: none;
            box-shadow: 0 4px 12px rgba(197, 146, 23, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 99;
            transition: all 0.3s;
        }
        
        .birthday-fab:hover {
            transform: scale(1.05) translateY(-5px);
            box-shadow: 0 6px 16px rgba(197, 146, 23, 0.5);
        }
        
        .birthday-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: white;
            font-size: 0.75rem;
            font-weight: 700;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }
        
        .birthday-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.4);
            z-index: 100;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }
        
        .birthday-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .birthday-sidebar {
            position: fixed;
            top: 0; right: -400px;
            width: 400px; max-width: 100%;
            height: 100vh;
            background: white;
            z-index: 101;
            box-shadow: -4px 0 24px rgba(0,0,0,0.15);
            transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }
        
        .birthday-sidebar.active {
            right: 0;
        }
        
        .birthday-sidebar-header {
            display: flex; justify-content: space-between; align-items: center;
            padding: 1.5rem;
            border-bottom: 2px solid #f3f4f6;
        }
        
        .birthday-close-btn {
            background: transparent; border: none; color: #6b7280;
            cursor: pointer; padding: 0.5rem; border-radius: 8px; transition: all 0.2s;
            display: flex; align-items: center; justify-content: center;
        }
        
        .birthday-close-btn:hover { background: #f3f4f6; color: #ef4444; }
        
        .birthday-sidebar-body {
            padding: 1.5rem; overflow-y: auto; flex: 1;
        }
    </style>
</head>

<body>
    {{-- Sidebar Component --}}
    @include('admin.components.sidebar', ['admin' => auth()->guard('admin')->user(), 'activeMenu' => $activeMenu ?? 'dashboard'])

    <div class="main-content">
        {{-- Topbar --}}
        <div class="topbar">
            <div class="topbar-left">
                <h2>@yield('page-title', 'Dashboard')</h2>
                <div class="topbar-subtitle">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                </div>
            </div>
            <div class="topbar-actions">
                {{-- User Profile in Topbar --}}
                <a href="{{ route('admin.profile') }}" class="topbar-user-profile">
                    <div class="topbar-user-avatar">
                        @if(auth()->guard('admin')->user()->photo)
                            <img src="{{ auth()->guard('admin')->user()->photo_url }}"
                                alt="{{ auth()->guard('admin')->user()->name }}">
                        @else
                            {{ strtoupper(substr(auth()->guard('admin')->user()->name ?? 'A', 0, 2)) }}
                        @endif
                    </div>
                    <div class="topbar-user-details">
                        <div class="topbar-user-name">{{ auth()->guard('admin')->user()->name ?? 'Admin' }}</div>
                        <div class="topbar-user-role">
                            {{ auth()->guard('admin')->user()->role_display_name }}
                        </div>
                    </div>
                </a>

                <button type="button" class="logout-btn" onclick="showLogoutModal()">Logout</button>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="content">
            @yield('content')
        </div>
    </div>

    <!-- Floating Action Button for Birthdays -->
    <button type="button" class="birthday-fab" onclick="toggleBirthdaySidebar()">
        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-8a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8"></path>
            <path d="M4 16s.5-1 2-1 2.5 2 4 2 2.5-2 4-2 2.5 2 4 2 2 1 2 1"></path>
            <path d="M2 21h20"></path>
            <path d="M7 8v3"></path>
            <path d="M12 8v3"></path>
            <path d="M17 8v3"></path>
            <path d="M7 4h.01"></path>
            <path d="M12 4h.01"></path>
            <path d="M17 4h.01"></path>
        </svg>
        @if(isset($upcomingBirthdays) && $upcomingBirthdays->isNotEmpty())
            <span class="birthday-badge">{{ $upcomingBirthdays->count() }}</span>
        @endif
    </button>

    <!-- Slide-in Sidebar (Offcanvas) for Birthdays -->
    <div class="birthday-overlay" id="birthdaySidebarOverlay" onclick="toggleBirthdaySidebar()"></div>
    <div class="birthday-sidebar" id="birthdaySidebar">
        <div class="birthday-sidebar-header">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="background: rgba(197, 146, 23, 0.1); color: #C59217; padding: 0.5rem; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-8a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8"></path>
                        <path d="M4 16s.5-1 2-1 2.5 2 4 2 2.5-2 4-2 2.5 2 4 2 2 1 2 1"></path>
                        <path d="M2 21h20"></path>
                        <path d="M7 8v3"></path>
                        <path d="M12 8v3"></path>
                        <path d="M17 8v3"></path>
                        <path d="M7 4h.01"></path>
                        <path d="M12 4h.01"></path>
                        <path d="M17 4h.01"></path>
                    </svg>
                </div>
                <h3 style="font-size: 1.125rem; font-weight: 700; color: #0a2540; margin: 0;">Ulang Tahun Anggota Terdekat</h3>
            </div>
            <button type="button" class="birthday-close-btn" onclick="toggleBirthdaySidebar()">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" fill="none" stroke-width="2">
                    <path d="M18 6L6 18M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="birthday-sidebar-body">
            <div class="birthday-list" style="display: flex; flex-direction: column; gap: 1rem;">
                @if(isset($upcomingBirthdays) && $upcomingBirthdays->isNotEmpty())
                    @foreach($upcomingBirthdays as $bd)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 1rem; border-bottom: 1px solid #f3f4f6;">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                @if(isset($bd['foto']) && $bd['foto'])
                                    <div style="width: 42px; height: 42px; border-radius: 50%; overflow: hidden; flex-shrink: 0; border: 2px solid #C59217;">
                                        <img src="{{ $bd['foto'] }}" alt="{{ $bd['nama'] }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                @else
                                    <div style="width: 42px; height: 42px; border-radius: 50%; background: #C59217; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9375rem; flex-shrink: 0;">
                                        {{ strtoupper(substr($bd['nama'], 0, 2)) }}
                                    </div>
                                @endif
                                <div>
                                    <div style="font-weight: 700; color: #0a2540; font-size: 0.9375rem; margin-bottom: 0.125rem;">{{ $bd['nama'] }}</div>
                                    <div style="color: #6b7280; font-size: 0.8125rem;">Anggota</div>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-weight: 700; color: #374151; font-size: 0.875rem;">{{ date('d M', strtotime($bd['tanggal'])) }}</div>
                                <div style="color: #C59217; font-size: 0.75rem; font-weight: 700; background: rgba(197, 146, 23, 0.1); padding: 0.15rem 0.5rem; border-radius: 4px; display: inline-block; margin-top: 0.25rem;">{{ $bd['hari'] }}</div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div style="text-align: center; color: #6b7280; padding: 2rem 0; font-size: 0.875rem;">
                        <svg viewBox="0 0 24 24" width="48" height="48" stroke="#d1d5db" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 1rem;">
                            <path d="M20 21v-8a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8"></path>
                            <path d="M4 16s.5-1 2-1 2.5 2 4 2 2.5-2 4-2 2.5 2 4 2 2 1 2 1"></path>
                            <path d="M2 21h20"></path>
                            <path d="M7 8v3"></path>
                            <path d="M12 8v3"></path>
                            <path d="M17 8v3"></path>
                            <path d="M7 4h.01"></path>
                            <path d="M12 4h.01"></path>
                            <path d="M17 4h.01"></path>
                        </svg>
                        <p>Belum ada anggota yang berulang tahun dalam waktu dekat.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Logout Confirmation Modal --}}
    <div class="logout-modal" id="logoutModal">
        <div class="logout-modal-content">
            <div class="logout-modal-header">
                <div class="logout-modal-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" y1="12" x2="9" y2="12" />
                    </svg>
                </div>
                <h3 class="logout-modal-title">Konfirmasi Logout</h3>
            </div>
            <p class="logout-modal-text">
                Apakah Anda yakin ingin keluar dari dashboard admin? Anda harus login kembali untuk mengakses halaman
                ini.
            </p>
            <div class="logout-modal-actions">
                <button type="button" class="modal-btn modal-btn-cancel" onclick="hideLogoutModal()">Batal</button>
                <button type="button" class="modal-btn modal-btn-confirm" onclick="confirmLogout()">Ya, Logout</button>
            </div>
        </div>
    </div>

    {{-- Hidden Logout Form --}}
    <form id="logoutForm" action="{{ route('admin.logout') }}" method="post" style="display: none;">
        @csrf
    </form>

    <script>
        function showLogoutModal() {
            document.getElementById('logoutModal').classList.add('active');
        }

        function hideLogoutModal() {
            document.getElementById('logoutModal').classList.remove('active');
        }

        function confirmLogout() {
            document.getElementById('logoutForm').submit();
        }

        // Close modal when clicking outside
        document.getElementById('logoutModal').addEventListener('click', function (e) {
            if (e.target === this) {
                hideLogoutModal();
            }
        });

        // Close modal with ESC key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                hideLogoutModal();
            }
        });

        function toggleBirthdaySidebar() {
            document.getElementById('birthdaySidebar').classList.toggle('active');
            document.getElementById('birthdaySidebarOverlay').classList.toggle('active');
        }
    </script>
    
    {{-- Global Toast Setup --}}
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
    </script>

    {{-- Flash Messages Notification --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            });
        </script>
    @endif
    
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Toast.fire({
                    icon: 'error',
                    title: "{{ session('error') }}"
                });
            });
        </script>
    @endif
    
    @if(session('deleted'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Toast.fire({
                    icon: 'success',
                    iconColor: '#dc2626',
                    title: "{{ session('deleted') }}",
                    customClass: {
                        popup: 'swal2-toast-deleted'
                    }
                });
            });
        </script>
    @endif
    
    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Validasi Gagal!',
                    text: 'Mohon periksa kembali form isian Anda. Ada beberapa data yang belum sesuai.',
                    icon: 'warning',
                    confirmButtonText: 'Periksa Form',
                    customClass: {
                        confirmButton: 'swal2-confirm btn-danger'
                    }
                });
            });
        </script>
    @endif
    
    {{-- jQuery (Required for Select2) --}}
    <script src="{{ asset('vendor/jquery/jquery-3.7.1.min.js') }}"></script>
    {{-- Select2 JS --}}
    <script src="{{ asset('vendor/select2/select2.min.js') }}"></script>

    @stack('scripts')
</body>

</html>