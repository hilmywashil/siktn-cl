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

    {{-- Flatpickr CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


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

        /* Global Flatpickr Customization to match form styles */
        .flatpickr-calendar {
            font-family: 'Montserrat', sans-serif;
            border: 1px solid #e5e7eb;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
        }
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange, .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover, .flatpickr-day.selected.prevMonthDay, .flatpickr-day.startRange.prevMonthDay, .flatpickr-day.endRange.prevMonthDay, .flatpickr-day.selected.nextMonthDay, .flatpickr-day.startRange.nextMonthDay, .flatpickr-day.endRange.nextMonthDay {
            background: #0a2540;
            border-color: #0a2540;
        }
        .flatpickr-day.selected:hover {
            background: #ffd700;
            border-color: #ffd700;
            color: #0a2540;
        }
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

        /* Notification Dropdown for Admin */
        .notification-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
            margin-right: 15px;
        }
        .notification-btn {
            background: transparent;
            border: none;
            cursor: pointer;
            position: relative;
            font-size: 1.25rem;
            color: #6b7280;
            padding: 8px;
            display: flex;
            align-items: center;
            transition: color 0.2s;
        }
        .notification-btn:hover {
            color: #0a2540;
        }
        .notification-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            background: #d60b1c;
            color: white;
            font-size: 0.65rem;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 50%;
            border: 2px solid white;
        }
        .notification-dropdown {
            position: absolute;
            top: 120%;
            right: -10px;
            width: 320px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
            border: 1px solid rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px) scale(0.98);
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            pointer-events: none;
        }
        .notification-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
            pointer-events: auto;
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

        /* Surat Notif & Slide-Over Drawer Modal Styles */
        .surat-notif-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
            margin-right: 8px;
        }
        .surat-notif-btn {
            background: transparent;
            border: none;
            cursor: pointer;
            position: relative;
            font-size: 1.25rem;
            color: #6b7280;
            padding: 8px;
            display: flex;
            align-items: center;
            transition: color 0.2s;
        }
        .surat-notif-btn:hover {
            color: #022648;
        }
        .surat-notif-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            background: #dc2626;
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 50%;
            border: 2px solid white;
        }
        .surat-notif-dropdown {
            position: absolute;
            top: 120%;
            right: -10px;
            width: 360px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
            border: 1px solid rgba(0,0,0,0.08);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px) scale(0.98);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            pointer-events: none;
        }
        .surat-notif-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }
        .surat-tabs {
            display: flex;
            background: #f8f9fc;
            border-bottom: 1px solid #e5e7eb;
            padding: 6px 8px 0 8px;
            gap: 4px;
        }
        .surat-tab-item {
            flex: 1;
            text-align: center;
            padding: 8px 4px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #6b7280;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            transition: all 0.2s;
            user-select: none;
        }
        .surat-tab-item.active {
            color: #022648;
            border-bottom-color: #022648;
        }

        /* Slide-Over Drawer Modal Styles */
        .surat-drawer-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(2, 38, 72, 0.5);
            backdrop-filter: blur(2px);
            z-index: 10000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        .surat-drawer-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        .surat-drawer {
            position: fixed;
            top: 0;
            right: -720px;
            width: 700px;
            max-width: 95vw;
            height: 100vh;
            background: #ffffff;
            z-index: 10001;
            box-shadow: -8px 0 32px rgba(0,0,0,0.18);
            transition: right 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
        }
        .surat-drawer.active {
            right: 0;
        }
        .surat-drawer-header {
            padding: 1.25rem 1.5rem;
            background: #022648;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .surat-drawer-body {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }
        .surat-drawer-footer {
            padding: 1rem 1.5rem;
            background: #f8f9fc;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 0.75rem;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
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

        /* Scroll to Top Button */
        #scrollTop {
            position: fixed;
            bottom: 2rem;
            right: 6.5rem; /* Pindah ke kiri agar tidak nabrak tombol ulang tahun */
            width: 50px;
            height: 50px;
            background-color: #0a2540;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        #scrollTop.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        #scrollTop:hover {
            background-color: #ffd700;
            color: #0a2540;
        }

        #scrollTop svg {
            width: 24px;
            height: 24px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
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
            <div class="topbar-actions" style="display: flex; align-items: center;">
                {{-- Admin Notifications --}}
                @php
                    $adminUser = auth()->guard('admin')->user();
                    $notifSettings = $adminUser->notification_settings ?? [
                        'surat_pending' => true,
                        'sk_expired' => true,
                        'new_anggota' => true,
                        'new_katalog' => true,
                        'auto_open_login' => true,
                    ];

                    $unreadNotifications = $adminUser->unreadNotifications;
                    
                    // Filter berdasarkan waktu 24 jam & sakelar preferensi user (12d)
                    $filteredNotifications = $adminUser->notifications()
                        ->where(function($q) {
                            $q->whereNull('read_at')
                              ->orWhere('read_at', '>=', \Carbon\Carbon::now()->subDay());
                        })
                        ->get()
                        ->filter(function($n) use ($notifSettings) {
                            $type = $n->data['type'] ?? '';
                            if (in_array($type, ['surat_pending', 'surat_terbit', 'surat_revisi']) && !($notifSettings['surat_pending'] ?? true)) return false;
                            if ($type === 'sk_expired' && !($notifSettings['sk_expired'] ?? true)) return false;
                            if ($type === 'new_anggota' && !($notifSettings['new_anggota'] ?? true)) return false;
                            if ($type === 'new_katalog' && !($notifSettings['new_katalog'] ?? true)) return false;
                            return true;
                        })
                        ->take(10);

                    // Recent Activity Logs (12b)
                    $recentActivityLogs = \App\Models\AdminActivityLog::orderBy('created_at', 'desc')->take(20)->get();
                @endphp

                {{-- Surat Keluar Notification Icon (3 Kategori Klasifikasi) --}}
                <div class="surat-notif-wrapper">
                    <button class="surat-notif-btn" id="suratNotifBtn" title="Notifikasi Surat Keluar">
                        <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>
                        <span class="surat-notif-badge" id="suratNotifBadge" style="display: none;">0</span>
                    </button>
                    <div class="surat-notif-dropdown" id="suratNotifDropdown">
                        <div class="notification-header" style="background: #022648; color: white;">
                            <h4 style="color: white; font-size: 0.9rem; font-weight: 700; display: flex; align-items: center; gap: 6px;">
                                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" fill="none" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                Notifikasi Surat
                            </h4>
                            <span style="font-size: 0.7rem; background: rgba(255,255,255,0.18); padding: 2px 8px; border-radius: 4px; color: #ffd700; font-weight: 700;" id="suratPendingHeaderCount">0 Pending</span>
                        </div>
                        <div class="surat-tabs">
                            <div class="surat-tab-item active" id="suratTabInternal" onclick="switchSuratTab('internal', this)" style="display: flex; align-items: center; justify-content: center; gap: 4px;">
                                <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" fill="none" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                Internal (<span id="countTabInternal">0</span>)
                            </div>
                            <div class="surat-tab-item" id="suratTabEksternal" onclick="switchSuratTab('eksternal', this)" style="display: flex; align-items: center; justify-content: center; gap: 4px;">
                                <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                                Eksternal (<span id="countTabEksternal">0</span>)
                            </div>
                            <div class="surat-tab-item" id="suratTabPenting" onclick="switchSuratTab('penting', this)" style="display: flex; align-items: center; justify-content: center; gap: 4px;">
                                <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" fill="none" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                                Penting (<span id="countTabPenting">0</span>)
                            </div>
                        </div>
                        <div class="notification-body" style="max-height: 320px;" id="suratNotifBody">
                            <div class="notification-empty">Memuat notifikasi surat...</div>
                        </div>
                        <div style="padding: 10px; text-align: center; border-top: 1px solid #e5e7eb; background: #f8f9fc;">
                            <a href="{{ route('admin.sekretariat.surat.index', ['tipe' => 'masuk']) }}" style="font-size: 0.8rem; font-weight: 700; color: #022648; text-decoration: none;">Lihat Semua Surat &rarr;</a>
                        </div>
                    </div>
                </div>

                <div class="notification-wrapper">
                    <button class="notification-btn" id="notificationBtn">
                        <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                        @if($unreadNotifications->count() > 0)
                            <span class="notification-badge">{{ $unreadNotifications->count() }}</span>
                        @endif
                    </button>
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-header">
                            <h4>Notifikasi</h4>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                @if($unreadNotifications->count() > 0)
                                    <form action="{{ route('admin.notifications.readAll') }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" style="background:none;border:none;color:#c59217;font-size:0.75rem;cursor:pointer;font-weight:600;">Tandai dibaca</button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.settings.notifications') }}" title="Pengaturan Preferensi Notifikasi" style="color: #6b7280; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; padding: 2px; border-radius: 4px; transition: color 0.2s;" onmouseover="this.style.color='#0a2540'" onmouseout="this.style.color='#6b7280'">
                                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="3"></circle>
                                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div class="notification-body">
                            @forelse($filteredNotifications as $notification)
                                <div class="notification-item {{ $notification->read_at ? '' : 'unread' }}">
                                    <div class="notification-item-title">
                                        @if($notification->data['type'] == 'new_anggota')
                                            <svg viewBox="0 0 24 24" width="14" height="14" stroke="#10b981" fill="none" stroke-width="2" style="margin-right: 4px; vertical-align: middle;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                        @elseif($notification->data['type'] == 'new_katalog')
                                            <svg viewBox="0 0 24 24" width="14" height="14" stroke="#3b82f6" fill="none" stroke-width="2" style="margin-right: 4px; vertical-align: middle;"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                                        @elseif($notification->data['type'] == 'surat_pending')
                                            <svg viewBox="0 0 24 24" width="14" height="14" stroke="#d97706" fill="none" stroke-width="2" style="margin-right: 4px; vertical-align: middle;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                                        @elseif($notification->data['type'] == 'surat_terbit')
                                            <svg viewBox="0 0 24 24" width="14" height="14" stroke="#059669" fill="none" stroke-width="2" style="margin-right: 4px; vertical-align: middle;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                        @elseif($notification->data['type'] == 'surat_revisi')
                                            <svg viewBox="0 0 24 24" width="14" height="14" stroke="#dc2626" fill="none" stroke-width="2" style="margin-right: 4px; vertical-align: middle;"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                        @elseif($notification->data['type'] == 'sk_expired')
                                            <svg viewBox="0 0 24 24" width="14" height="14" stroke="#dc2626" fill="none" stroke-width="2" style="margin-right: 4px; vertical-align: middle;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                        @else
                                            <svg viewBox="0 0 24 24" width="14" height="14" stroke="#c59217" fill="none" stroke-width="2" style="margin-right: 4px; vertical-align: middle;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                        @endif
                                        {{ $notification->data['title'] ?? 'Notifikasi' }}
                                    </div>
                                    <div class="notification-item-message">
                                        {{ $notification->data['message'] ?? '' }}
                                    </div>
                                    <div class="notification-item-time">{{ $notification->created_at->diffForHumans() }}</div>
                                </div>
                            @empty
                                <div class="notification-empty">
                                    Belum ada notifikasi masuk.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

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

    <!-- Scroll to Top Button -->
    <div id="scrollTop" title="Ke Atas">
        <svg viewBox="0 0 24 24">
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </div>

    <!-- SLIDE-OVER DRAWER MODAL PREVIEW SURAT -->
    <div class="surat-drawer-overlay" id="suratDrawerOverlay" onclick="closeSuratDrawer()"></div>
    <div class="surat-drawer" id="suratDrawer">
        <div class="surat-drawer-header">
            <div>
                <span id="drawerKlasifikasiBadge" style="background: rgba(255,255,255,0.2); font-size: 0.7rem; font-weight: 700; padding: 2px 8px; border-radius: 4px; text-transform: uppercase;">SURAT KELUAR</span>
                <h3 id="drawerNomorSurat" style="margin: 4px 0 0 0; font-size: 1.1rem; color: white; font-weight: 800;">-</h3>
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <span id="drawerStatusBadge" style="font-size: 0.75rem; padding: 4px 10px; border-radius: 50px; font-weight: 700; background: #f59e0b; color: white;">Pending TTD</span>
                <button type="button" onclick="closeSuratDrawer()" style="background: transparent; border: none; color: white; font-size: 1.5rem; cursor: pointer; padding: 0 4px; line-height: 1;">&times;</button>
            </div>
        </div>

        <div class="surat-drawer-body">
            <!-- RINGKASAN SURAT -->
            <div style="background: #f8f9fc; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem; display: flex; flex-direction: column; gap: 0.5rem;">
                <div style="display: flex; justify-content: space-between;">
                    <span style="font-size: 0.8rem; color: #6b7280; font-weight: 600;">Perihal:</span>
                    <span style="font-size: 0.8rem; color: #6b7280; font-weight: 600;" id="drawerTanggal">-</span>
                </div>
                <div style="font-size: 0.95rem; font-weight: 700; color: #022648;" id="drawerPerihal">-</div>
                <div style="font-size: 0.8rem; color: #374151; margin-top: 4px;">
                    <strong>Pengirim / Tujuan:</strong> <span id="drawerPengirimTujuan">-</span>
                </div>
            </div>

            <!-- DOCUMENT VIEWER CONTAINER -->
            <div style="flex: 1; min-height: 420px; display: flex; flex-direction: column; border: 1px solid #d1d5db; border-radius: 8px; overflow: hidden; background: #525659;">
                <div style="background: #1e293b; color: white; padding: 8px 14px; font-size: 0.8rem; font-weight: 700; display: flex; justify-content: space-between; align-items: center;">
                    <span>📄 Pratinjau Dokumen Surat</span>
                    <span id="drawerFileTypeLabel" style="font-size: 0.7rem; color: #94a3b8;">Viewer Dokumen</span>
                </div>
                <div id="drawerViewerContent" style="flex: 1; display: flex; align-items: center; justify-content: center; color: white; position: relative;">
                    <div style="text-align: center; padding: 2rem;">
                        <svg viewBox="0 0 24 24" width="40" height="40" stroke="#94a3b8" fill="none" stroke-width="2" style="margin-bottom: 8px;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                        <p style="font-size: 0.85rem; color: #cbd5e1; margin: 0;">Pilih surat untuk melihat dokumen</p>
                    </div>
                </div>
            </div>

            <!-- FORM HIDDEN UNTUK UPLOAD FILE BER-TTD -->
            <form id="uploadSignedForm" style="display: none;" enctype="multipart/form-data">
                @csrf
                <input type="file" id="signedFileInput" name="signed_file" accept=".pdf,.doc,.docx" onchange="submitSignedFile()">
            </form>
        </div>

        <div class="surat-drawer-footer">
            <button type="button" id="btnDownloadSurat" class="modal-btn modal-btn-cancel" style="display: inline-flex; align-items: center; gap: 6px;" onclick="downloadSuratFile()">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" fill="none" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                Download File
            </button>

            <button type="button" class="modal-btn" style="background: #f59e0b; color: white; display: inline-flex; align-items: center; gap: 6px;" onclick="triggerUploadSigned()">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" fill="none" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                Upload File Ber-TTD
            </button>

            @if(auth()->guard('admin')->user()->isSuperAdmin() || auth()->guard('admin')->user()->isPimpinan() || auth()->guard('admin')->user()->isPNKT())
                <button type="button" id="btnApproveTTD" class="modal-btn" style="background: #022648; color: white; display: inline-flex; align-items: center; gap: 6px;" onclick="approveSuratTTD()">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" fill="none" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                    TTD & Terbitkan Instan
                </button>
            @endif
        </div>
    </div>

    {{-- jQuery (Required for Select2) --}}
    <script src="{{ asset('vendor/jquery/jquery-3.7.1.min.js') }}"></script>
    {{-- Select2 JS --}}
    <script src="{{ asset('vendor/select2/select2.min.js') }}"></script>

    {{-- Flatpickr JS --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

    <script>
        // Global Sidebar Dropdown Toggle Handler
        function toggleDropdown(element) {
            element.classList.toggle('active');
            const submenu = element.nextElementSibling;
            if (submenu) {
                submenu.classList.toggle('active');
            }
        }

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
        const logoutModalEl = document.getElementById('logoutModal');
        if (logoutModalEl) {
            logoutModalEl.addEventListener('click', function (e) {
                if (e.target === this) {
                    hideLogoutModal();
                }
            });
        }

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

        // Scroll to Top Logic
        const scrollTopBtn = document.getElementById('scrollTop');
        if (scrollTopBtn) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > 300) {
                    scrollTopBtn.classList.add('show');
                } else {
                    scrollTopBtn.classList.remove('show');
                }
            });

            scrollTopBtn.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        // General Notification Dropdown
        const notifBtn = document.getElementById('notificationBtn');
        const notifDropdown = document.getElementById('notificationDropdown');
        if (notifBtn && notifDropdown) {
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

        // Surat Notif & Slide-Over Drawer Logic
        let currentSuratId = null;
        let currentSuratData = null;
        let suratNotifFeedData = null;
        let currentSuratTab = 'internal';

        const suratNotifBtn = document.getElementById('suratNotifBtn');
        const suratNotifDropdown = document.getElementById('suratNotifDropdown');

        if (suratNotifBtn && suratNotifDropdown) {
            suratNotifBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                suratNotifDropdown.classList.toggle('show');
                if (suratNotifDropdown.classList.contains('show')) {
                    loadSuratNotifData();
                }
            });

            document.addEventListener('click', function(e) {
                if (!suratNotifBtn.contains(e.target) && !suratNotifDropdown.contains(e.target)) {
                    suratNotifDropdown.classList.remove('show');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            fetchSuratHeaderCount();
        });

        function fetchSuratHeaderCount() {
            fetch("{{ route('admin.sekretariat.surat.notifications') }}")
                .then(r => r.json())
                .then(data => {
                    suratNotifFeedData = data;
                    updateSuratBadges(data);
                })
                .catch(err => console.error("Error fetching surat counts:", err));
        }

        function updateSuratBadges(data) {
            const badge = document.getElementById('suratNotifBadge');
            const pendingHeader = document.getElementById('suratPendingHeaderCount');
            const countInternal = document.getElementById('countTabInternal');
            const countEksternal = document.getElementById('countTabEksternal');
            const countPenting = document.getElementById('countTabPenting');

            if (badge) {
                if (data.pending_count > 0) {
                    badge.textContent = data.pending_count;
                    badge.style.display = 'block';
                } else {
                    badge.style.display = 'none';
                }
            }
            if (pendingHeader) pendingHeader.textContent = `${data.pending_count} Pending`;
            if (countInternal) countInternal.textContent = data.counts.internal;
            if (countEksternal) countEksternal.textContent = data.counts.eksternal;
            if (countPenting) countPenting.textContent = data.counts.penting;
        }

        function loadSuratNotifData() {
            const notifBody = document.getElementById('suratNotifBody');
            if (!notifBody) return;
            notifBody.innerHTML = '<div class="notification-empty">Memuat notifikasi surat...</div>';

            fetch("{{ route('admin.sekretariat.surat.notifications') }}")
                .then(r => r.json())
                .then(data => {
                    suratNotifFeedData = data;
                    updateSuratBadges(data);
                    renderSuratNotifList(currentSuratTab);
                })
                .catch(err => {
                    notifBody.innerHTML = '<div class="notification-empty" style="color: #dc2626;">Gagal memuat notifikasi.</div>';
                });
        }

        function switchSuratTab(tab, el) {
            currentSuratTab = tab;
            document.querySelectorAll('.surat-tab-item').forEach(item => item.classList.remove('active'));
            if (el) el.classList.add('active');
            renderSuratNotifList(tab);
        }

        function renderSuratNotifList(tab) {
            const notifBody = document.getElementById('suratNotifBody');
            if (!notifBody) return;

            if (!suratNotifFeedData || !suratNotifFeedData[tab] || suratNotifFeedData[tab].length === 0) {
                notifBody.innerHTML = `<div class="notification-empty">Belum ada surat keluar kategori ${tab}.</div>`;
                return;
            }

            let html = '';
            suratNotifFeedData[tab].forEach(item => {
                let statusBadgeColor = '#f59e0b';
                if (item.status === 'Terbit') statusBadgeColor = '#10b981';
                if (item.status === 'Revisi') statusBadgeColor = '#dc2626';

                html += `
                    <div class="notification-item" onclick="openSuratDrawer(${item.id})" style="cursor: pointer; padding: 12px 16px;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px;">
                            <span style="font-size: 0.8rem; font-weight: 800; color: #022648; display: flex; align-items: center; gap: 4px;">
                                <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" fill="none" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                                ${item.nomor_surat}
                            </span>
                            <span style="font-size: 0.65rem; padding: 2px 6px; border-radius: 4px; background: ${statusBadgeColor}; color: white; font-weight: 700;">${item.status}</span>
                        </div>
                        <div class="notification-item-message" style="font-weight: 600; color: #1e293b; font-size: 0.825rem;">${item.perihal}</div>
                        <div style="display: flex; justify-content: space-between; font-size: 0.7rem; color: #94a3b8; margin-top: 4px;">
                            <span>${item.pengirim_tujuan}</span>
                            <span>${item.tanggal}</span>
                        </div>
                    </div>
                `;
            });
            notifBody.innerHTML = html;
        }

        // SLIDE-OVER DRAWER MODAL FUNCTIONS
        function openSuratDrawer(id) {
            currentSuratId = id;
            document.getElementById('suratDrawerOverlay').classList.add('active');
            document.getElementById('suratDrawer').classList.add('active');
            if (suratNotifDropdown) suratNotifDropdown.classList.remove('show');

            const viewerContent = document.getElementById('drawerViewerContent');
            viewerContent.innerHTML = '<div style="text-align: center; color: white; padding: 2rem;">Memuat pratinjau dokumen...</div>';

            fetch(`/admin/sekretariat/surat/${id}/audit-trail`)
                .then(r => r.json())
                .then(data => {
                    const surat = data.surat;
                    currentSuratData = surat;

                    document.getElementById('drawerNomorSurat').textContent = surat.nomor_surat;
                    document.getElementById('drawerKlasifikasiBadge').textContent = `SURAT KELUAR • ${surat.klasifikasi.toUpperCase()}`;
                    document.getElementById('drawerPerihal').textContent = surat.perihal;
                    document.getElementById('drawerPengirimTujuan').textContent = surat.pengirim_tujuan;
                    document.getElementById('drawerTanggal').textContent = surat.tanggal;

                    const statusBadge = document.getElementById('drawerStatusBadge');
                    statusBadge.textContent = surat.status;
                    if (surat.status === 'Terbit') statusBadge.style.background = '#10b981';
                    else if (surat.status === 'Revisi') statusBadge.style.background = '#dc2626';
                    else statusBadge.style.background = '#f59e0b';

                    const fileLabel = document.getElementById('drawerFileTypeLabel');
                    
                    if (data.file_url) {
                        if (data.is_pdf) {
                            fileLabel.textContent = 'Native PDF Document Viewer';
                            viewerContent.innerHTML = `<iframe src="${data.file_url}" style="width: 100%; height: 100%; border: none;"></iframe>`;
                        } else if (data.is_word) {
                            fileLabel.textContent = 'Google Docs / Office Online Viewer (Word)';
                            const docViewerUrl = `https://docs.google.com/viewer?url=${encodeURIComponent(data.file_url)}&embedded=true`;
                            viewerContent.innerHTML = `<iframe src="${docViewerUrl}" style="width: 100%; height: 100%; border: none;"></iframe>`;
                        } else {
                            fileLabel.textContent = 'File Document';
                            viewerContent.innerHTML = `<div style="text-align:center; padding: 2rem; color: white;">
                                <p>File berformat khusus.</p>
                                <a href="${data.file_url}" target="_blank" style="color: #ffd700; font-weight: 700;">Buka Dokumen di Tab Baru &rarr;</a>
                            </div>`;
                        }
                    } else if (surat.link_drive) {
                        fileLabel.textContent = 'Google Drive Link';
                        viewerContent.innerHTML = `<div style="text-align:center; padding: 2rem; color: white;">
                            <p style="margin-bottom: 1rem;">Dokumen ini tersimpan di Google Drive.</p>
                            <a href="${surat.link_drive}" target="_blank" class="modal-btn" style="background: #ffd700; color: #022648; text-decoration: none; font-weight: 700;">🔗 Buka Google Drive &rarr;</a>
                        </div>`;
                    } else {
                        fileLabel.textContent = 'Tanpa Lampiran File';
                        viewerContent.innerHTML = `<div style="text-align:center; padding: 2rem; color: white;"><p>Belum ada lampiran file untuk surat ini.</p></div>`;
                    }
                })
                .catch(err => {
                    console.error("Error loading drawer:", err);
                    viewerContent.innerHTML = '<div style="color: #ef4444; padding: 2rem; text-align: center;">Gagal memuat detail surat.</div>';
                });
        }

        function closeSuratDrawer() {
            document.getElementById('suratDrawerOverlay').classList.remove('active');
            document.getElementById('suratDrawer').classList.remove('active');
            currentSuratId = null;
            currentSuratData = null;
        }

        function downloadSuratFile() {
            if (!currentSuratData || (!currentSuratData.file_lampiran && !currentSuratData.link_drive)) {
                Toast.fire({ icon: 'warning', title: 'Surat ini tidak memiliki lampiran file.' });
                return;
            }
            if (currentSuratData.file_lampiran) {
                window.open(`/storage/${currentSuratData.file_lampiran}`, '_blank');
            } else if (currentSuratData.link_drive) {
                window.open(currentSuratData.link_drive, '_blank');
            }
        }

        function triggerUploadSigned() {
            if (!currentSuratId) return;
            document.getElementById('signedFileInput').click();
        }

        function submitSignedFile() {
            const input = document.getElementById('signedFileInput');
            if (!input.files || input.files.length === 0 || !currentSuratId) return;

            const formData = new FormData();
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('signed_file', input.files[0]);

            Swal.fire({
                title: 'Mengunggah File Ber-TTD...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch(`/admin/sekretariat/surat/${currentSuratId}/upload-signed`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                input.value = '';
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'File Ber-TTD Berhasil Di-Upload!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    openSuratDrawer(currentSuratId);
                    fetchSuratHeaderCount();
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal Upload', text: data.message || 'Terjadi kesalahan.' });
                }
            })
            .catch(err => {
                input.value = '';
                console.error(err);
                Swal.fire({ icon: 'error', title: 'Kesalahan Sistem', text: 'Gagal mengunggah file bertanda tangan.' });
            });
        }

        function approveSuratTTD() {
            if (!currentSuratId) return;

            Swal.fire({
                title: 'Konfirmasi TTD & Terbit',
                text: `Apakah Anda yakin ingin menyetujui & menandatangani Surat Keluar '${currentSuratData?.nomor_surat || ''}'?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, TTD & Terbitkan',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'swal2-confirm',
                    cancelButton: 'swal2-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/sekretariat/surat/${currentSuratId}/status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            status: 'Terbit',
                            notes: 'Disetujui & Diberikan TTD Digital dari Slide-Over Preview'
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            Toast.fire({ icon: 'success', title: data.message });
                            openSuratDrawer(currentSuratId);
                            fetchSuratHeaderCount();
                        } else {
                            Toast.fire({ icon: 'error', title: 'Gagal mengubah status.' });
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Toast.fire({ icon: 'error', title: 'Terjadi kesalahan sistem.' });
                    });
                }
            });
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

    <script>
        $(document).ready(function() {
            if (typeof flatpickr !== 'undefined') {
                flatpickr(".datepicker", {
                    locale: "id",
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "d M Y",
                    allowInput: true
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>