{{-- resources/views/admin/layouts/admin-layout.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <title>@yield('title', 'Dashboard Admin') - Karang Taruna</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    {{-- Admin Styles --}}
    <link rel="stylesheet" href="{{ asset('asita-assets/css/admin-layout.css') }}">

    {{-- Additional Page Styles --}}
    @stack('styles')

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
                            @php
                                $category = auth()->guard('admin')->user()->category;
                                $roleDisplay = match($category) {
                                    'super_admin' => 'Super Admin',
                                    'bpd' => 'BPD',
                                    'bpc' => 'BPC',
                                    default => 'Admin'
                                };
                            @endphp
                            {{ $roleDisplay }}
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
    </script>

    @stack('scripts')
</body>

</html>