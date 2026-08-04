@extends('layouts.app')

@section('title', 'Dashboard Anggota')

@push('styles')
    <style>
        :root {
            --primary-blue: #022648;
            --primary-gradient: linear-gradient(135deg, #022648 0%, #305478 100%); /* Biru dongker gradasi putih/terang */
            --secondary-blue: #18227C;
            --accent-yellow: #C59217;
            --accent-red: #D60B1C;
            --text-dark: #022648;
            --text-grey: #6b7280;
            --bg-light: #F8F9FC;
        }

        /* ==============================================
        SELECT2 BENCHMARK STYLING (NAVY #022648 & MICRO-ANIMATION)
        ============================================== */
        @keyframes select2DropdownFadeIn {
            from { opacity: 0; transform: translateY(-8px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .select2-container--default .select2-selection--single {
            height: 42px !important;
            padding: 0.35rem 0.75rem !important;
            font-size: 0.875rem !important;
            font-weight: 600 !important;
            color: #022648 !important;
            background-color: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 6px !important;
            display: flex !important;
            align-items: center !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single:hover {
            border-color: #022648 !important;
            box-shadow: 0 3px 8px rgba(2, 38, 72, 0.1) !important;
        }

        .select2-dropdown {
            border: 1px solid #cbd5e1 !important;
            border-radius: 6px !important;
            font-size: 0.875rem !important;
            z-index: 99999 !important;
            box-shadow: 0 12px 28px rgba(2, 38, 72, 0.15) !important;
            margin-top: 4px !important;
            overflow: hidden !important;
            background-color: #ffffff !important;
        }

        .select2-container--open .select2-dropdown {
            animation: select2DropdownFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards !important;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #cbd5e1 !important;
            border-radius: 4px !important;
            padding: 6px 10px !important;
            font-size: 0.875rem !important;
            outline: none !important;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: #022648 !important;
            box-shadow: 0 0 0 2px rgba(2, 38, 72, 0.15) !important;
        }

        .select2-container--default .select2-results__option--selectable {
            color: #111827 !important;
            transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1) !important;
            padding: 0.5rem 0.75rem !important;
        }

        .select2-results__option--highlighted[aria-selected],
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
            background-color: #022648 !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            padding-left: 1.15rem !important;
        }

        /* Force Sticky Header to show at the top of the dashboard */
        .header-sticky {
            top: 0 !important;
            position: sticky !important;
            box-shadow: 0 4px 20px rgba(11, 19, 84, 0.05) !important;
            background-color: #ffffff !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .header-inner {
            max-width: 1400px !important;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 40px auto 80px auto;
            padding: 0 20px;
            font-family: 'Google Sans', sans-serif;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 30px;
        }

        /* ==============================================
        /* ==============================================
        SIDEBAR STYLING (SIKTN BENCHMARK CRISP 6PX RADIUS)
        ============================================== */
        .dashboard-sidebar {
            background: #ffffff;
            border-radius: 6px;
            padding: 24px 18px;
            box-shadow: 0 4px 14px rgba(2, 38, 72, 0.06);
            border: 1px solid #e5e7eb;
            height: fit-content;
        }

        .user-badge {
            text-align: center;
            margin-bottom: 22px;
            padding-bottom: 18px;
            border-bottom: 1px solid #f1f5f9;
        }

        .user-avatar-wrapper {
            width: 85px;
            height: 85px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 12px auto;
            border: 3px solid #b7830f;
            box-shadow: 0 4px 12px rgba(2, 38, 72, 0.15);
        }

        .user-avatar {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-name {
            font-size: 1.05rem;
            font-weight: 800;
            color: #022648;
            margin: 0 0 4px 0;
            letter-spacing: -0.2px;
        }

        .user-nrp {
            font-family: monospace;
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 0.68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 8px;
        }

        .status-pending {
            background-color: #fef3c7 !important;
            color: #b7830f !important;
            border: 1px solid #fde68a !important;
        }

        .status-approved {
            background-color: #dcfce7 !important;
            color: #059669 !important;
            border: 1px solid #bbf7d0 !important;
        }

        .status-rejected {
            background-color: #fee2e2 !important;
            color: #dc2626 !important;
            border: 1px solid #fca5a5 !important;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .sidebar-menu-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            width: 100%;
            border: none;
            background: transparent;
            font-size: 0.875rem;
            font-weight: 700;
            color: #475569;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            text-align: left;
            text-decoration: none;
        }

        .sidebar-menu-btn:hover {
            background-color: #f1f5f9;
            color: #022648;
            transform: translateX(3px);
        }

        .sidebar-menu-btn.active {
            background: #022648;
            color: #ffffff;
            border-left: 4px solid #b7830f;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .sidebar-menu-btn i {
            font-size: 0.95rem;
            width: 18px;
            text-align: center;
            opacity: 0.95;
        }

        /* ==============================================
        CONTENT SECTIONS (CRISP 6PX RADIUS)
        ============================================== */
        .dashboard-content {
            background: #ffffff;
            border-radius: 6px;
            padding: 30px 34px;
            box-shadow: 0 4px 14px rgba(2, 38, 72, 0.06);
            border: 1px solid #e5e7eb;
            min-height: 550px;
        }

        .content-section {
            display: none;
            animation: fadeIn 0.25s ease-out;
        }

        .content-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .section-title {
            font-size: 1.35rem;
            font-weight: 800;
            color: #022648;
            margin-bottom: 22px;
            border-bottom: 2.5px solid #b7830f;
            padding-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Grid info */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-top: 10px;
        }

        .info-card {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 20px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(2, 38, 72, 0.03);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .info-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: #b7830f;
            opacity: 0;
            transition: all 0.2s;
        }

        .info-card:hover {
            box-shadow: 0 6px 16px rgba(2, 38, 72, 0.08);
            transform: translateY(-2px);
            border-color: #cbd5e1;
        }

        .info-card:hover::before {
            opacity: 1;
        }

        .info-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(11, 19, 84, 0.04);
            color: var(--primary-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .info-card:hover .info-card-icon {
            background: var(--primary-blue);
            color: #ffffff;
            transform: scale(1.05) rotate(5deg);
        }

        .info-card-content {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .info-card-label {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--text-grey);
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .info-card-value {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .info-card-value.empty {
            color: #9CA3AF;
            font-style: italic;
        }

        /* ==============================================
        KTA PREVIEW STYLING (PREMIUM MODERN DESIGN)
        ============================================== */
        .kta-card-wrapper {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin: 30px 0;
            flex-wrap: wrap;
        }

        .kta-card {
            width: 330px;
            height: 520px;
            background: #ffffff;
            border-radius: 16px;
            position: relative;
            font-family: 'Inter', 'Google Sans', system-ui, -apple-system, sans-serif;
            box-shadow: 0 20px 40px -10px rgba(2, 38, 72, 0.2), 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(2, 38, 72, 0.12);
        }

        .kta-card * {
            box-sizing: border-box;
        }

        /* Front Side Elements */
        .kta-header-front {
            background: linear-gradient(135deg, #022648 0%, #083b6e 100%);
            color: #ffffff;
            text-align: center;
            padding: 24px 15px 32px;
            position: relative;
        }

        .kta-header-front h2 {
            font-size: 20px;
            font-weight: 800;
            margin: 0 0 4px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            line-height: 1.2;
            color: #ffffff;
        }

        .kta-header-front p {
            font-size: 10px;
            margin: 0;
            letter-spacing: 1.8px;
            font-weight: 700;
            color: #f3c350;
            text-transform: uppercase;
        }

        .kta-gold-bar {
            background: linear-gradient(90deg, #b7830f 0%, #f3c350 50%, #b7830f 100%);
            height: 5px;
            width: 100%;
        }

        .kta-front-curve {
            display: none;
        }

        .kta-logo-front {
            width: 95px;
            height: 95px;
            margin: -24px auto 6px;
            position: relative;
            z-index: 3;
            background: #ffffff;
            border-radius: 50%;
            padding: 4px;
            border: 3.5px solid #b7830f;
            box-shadow: 0 8px 20px rgba(2, 38, 72, 0.22);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .kta-logo-front img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
        }

        .kta-member-name {
            text-align: center;
            color: #022648;
            font-size: 16px;
            font-weight: 800;
            text-transform: uppercase;
            margin: 4px 12px 2px;
            line-height: 1.25;
            letter-spacing: 0.5px;
        }

        .kta-member-nik-container {
            text-align: center;
            margin-bottom: 4px;
        }

        .kta-member-nik-badge {
            display: inline-block;
            background: transparent;
            color: #022648;
            font-family: 'Consolas', 'Courier New', monospace;
            font-size: 12px;
            font-weight: 800;
            padding: 0;
            border: none;
            letter-spacing: 0.5px;
        }

        .kta-anchor-divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin: 6px 0 10px;
            color: #b7830f;
            font-size: 12px;
        }

        .kta-anchor-divider::before,
        .kta-anchor-divider::after {
            content: '';
            width: 70px;
            height: 1.5px;
            background: linear-gradient(90deg, transparent, #b7830f, transparent);
        }

        .kta-details-container {
            display: flex;
            padding: 0 18px;
            margin-top: 4px;
            gap: 14px;
            align-items: center;
        }

        .kta-photo-wrapper {
            width: 90px;
            height: 115px;
            border: 2px solid #cbd5e1;
            border-radius: 10px;
            background: #f8fafc;
            flex-shrink: 0;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        .kta-photo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .kta-details-list {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .kta-detail-row {
            display: flex;
            font-size: 9.5px;
            font-weight: 600;
            color: #1e293b;
            line-height: 1.3;
        }

        .kta-detail-label {
            width: 78px;
            flex-shrink: 0;
            color: #64748b;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 8.5px;
            letter-spacing: 0.3px;
        }

        .kta-detail-value {
            flex-grow: 1;
            color: #022648;
            font-weight: 700;
        }

        .kta-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #dcfce7;
            color: #15803d;
            font-size: 8.5px;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 4px;
            border: 1px solid #bbf7d0;
            letter-spacing: 0.3px;
        }

        .kta-bottom-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 18px 14px;
            margin-top: auto;
            position: relative;
            z-index: 5;
        }

        .kta-qr-code {
            width: 65px;
            height: 65px;
            background: #ffffff;
            padding: 4px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.06);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-sizing: border-box;
        }

        .kta-signature {
            text-align: center;
            color: #022648;
        }

        .kta-signature-img {
            height: 36px;
            margin-bottom: 2px;
            object-fit: contain;
        }

        .kta-signature-text {
            font-size: 8.5px;
            font-weight: 800;
            margin: 0;
            letter-spacing: 0.3px;
            color: #022648;
        }

        .kta-signature-subtext {
            font-size: 7.5px;
            color: #64748b;
            margin: 1px 0 0;
            font-weight: 600;
        }

        .kta-bottom-bar {
            height: 12px;
            background: linear-gradient(90deg, #022648 0%, #083b6e 70%, #b7830f 100%);
            width: 100%;
            margin-top: auto;
        }

        /* Back Side Elements */
        .kta-card-back {
            background: #ffffff;
            position: relative;
        }

        .kta-card-back .kta-header-front {
            padding: 20px 15px 24px;
        }

        .kta-card-back .kta-header-front h2 {
            font-size: 17px;
            letter-spacing: 1px;
            margin: 0;
        }

        .kta-back-content {
            padding: 24px 22px;
            position: relative;
            z-index: 2;
        }

        .kta-back-ketentuan-title {
            font-weight: 800;
            font-size: 11.5px;
            color: #022648;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kta-back-ketentuan-list {
            margin: 0;
            padding-left: 18px;
            font-size: 10.5px;
            color: #334155;
            line-height: 1.65;
            font-weight: 600;
        }

        .kta-back-ketentuan-list li {
            margin-bottom: 8px;
        }

        .kta-back-ketentuan-list li {
            margin-bottom: 8px;
        }

        .kta-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 180px;
            height: 180px;
            opacity: 0.1;
            z-index: 1;
            pointer-events: none;
        }

        .kta-back-footer {
            background: var(--primary-gradient);
            color: white;
            text-align: center;
            padding: 25px 20px 20px;
            margin-top: auto;
            position: relative;
        }

        .kta-back-footer-curve {
            position: absolute;
            top: -30px;
            left: -10%;
            width: 120%;
            height: 60px;
            background: var(--primary-gradient);
            border-radius: 50%;
            z-index: 1;
        }

        .kta-back-footer-content {
            position: relative;
            z-index: 2;
        }

        .kta-back-footer-title {
            color: var(--accent-yellow);
            font-size: 16px;
            font-weight: 700;
            margin: 0 0 3px;
        }

        .kta-back-footer-subtitle {
            font-family: 'Google Sans', sans-serif;
            font-size: 10px;
            font-weight: 600;
            margin: 0 0 15px;
        }

        .kta-back-address {
            font-family: 'Google Sans', sans-serif;
            font-size: 10px;
            margin-bottom: 15px;
            line-height: 1.4;
        }

        .kta-back-contact {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            font-family: 'Google Sans', sans-serif;
            font-size: 10px;
            margin-bottom: 15px;
        }

        .kta-back-contact i {
            margin-right: 5px;
        }

        .kta-back-validity {
            color: var(--accent-yellow);
            font-family: 'Google Sans', sans-serif;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.5px;
            line-height: 1.4;
        }

        /* ==============================================
        COMPANY INPUT FORM & LOCKED STATE (CRISP 6PX RADIUS)
        ============================================== */
        .locked-state-box {
            background-color: #FFFDF5;
            border: 1px dashed #F59E0B;
            border-radius: 6px;
            padding: 28px 24px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.02);
        }

        .locked-icon {
            font-size: 2.2rem;
            color: #D97706;
            margin-bottom: 12px;
        }

        .locked-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: #92400E;
            margin-bottom: 8px;
        }

        .locked-desc {
            font-size: 0.88rem;
            color: #B45309;
            line-height: 1.6;
            max-width: 520px;
            margin: 0 auto;
        }

        /* Form elements */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
            margin-bottom: 24px;
        }

        .form-grid.full-width {
            grid-template-columns: 1fr;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 0.72rem;
            font-weight: 800;
            color: #022648;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .form-control {
            padding: 10px 14px;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: #ffffff;
            color: #022648;
        }

        .form-control:focus {
            outline: none;
            border-color: #022648;
            box-shadow: 0 0 0 3px rgba(2, 38, 72, 0.1);
        }

        .btn-submit {
            background: #022648;
            color: #ffffff;
            border: none;
            padding: 11px 22px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background-color: #083b6e;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(2, 38, 72, 0.2);
        }

        .form-section-divider {
            font-family: 'Google Sans', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--secondary-blue);
            margin: 25px 0 10px 0;
            padding-bottom: 8px;
            border-bottom: 1px solid rgba(9, 11, 98, 0.06);
            grid-column: span 2;
        }

        /* File list */
        .file-upload-item {
            background-color: #F8F9FD;
            border: 1px solid rgba(9, 11, 98, 0.04);
            border-radius: 8px;
            padding: 12px 18px;
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .file-upload-info h5 {
            margin: 0;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .file-upload-info p {
            margin: 2px 0 0 0;
            font-size: 0.72rem;
            color: var(--text-grey);
        }

        .file-upload-link {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--secondary-blue);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .file-upload-link:hover {
            color: var(--primary-blue);
            text-decoration: underline;
        }

        /* Alert notifications */
        .alert-box {
            border-radius: 8px;
            padding: 14px 18px;
            margin-bottom: 25px;
            display: flex;
            gap: 12px;
            align-items: flex-start;
            font-size: 0.88rem;
        }

        .alert-success {
            background-color: #D1FAE5;
            color: #065F46;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .alert-danger {
            background-color: rgba(214, 11, 28, 0.06) !important;
            color: var(--accent-red) !important;
            border: 1px solid rgba(214, 11, 28, 0.15) !important;
        }

        @media (max-width: 991px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .info-grid, .form-grid {
                grid-template-columns: 1fr;
            }
            .form-section-divider {
                grid-column: span 1;
            }
            .kta-card {
                width: 100%;
                height: auto;
                aspect-ratio: 480 / 280;
            }
        }

        /* ============ FOTO UPLOAD AREA ============ */
        .foto-upload-wrapper {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px;
            background: #f8f9fc;
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            transition: border-color 0.2s;
        }
        .foto-upload-wrapper:hover { border-color: var(--accent-yellow); }
        .foto-avatar-preview {
            width: 90px; height: 90px; flex-shrink: 0;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid var(--accent-yellow);
            background: #e5e7eb;
            display: flex; align-items: center; justify-content: center;
        }
        .foto-avatar-preview img { width: 100%; height: 100%; object-fit: cover; }
        .foto-avatar-preview .avatar-placeholder { font-size: 2rem; color: #9ca3af; }
        .foto-upload-actions { flex: 1; }
        .foto-upload-actions p { font-size: 0.8rem; color: #6b7280; margin: 0 0 12px 0; line-height: 1.5; }
        .foto-btn-group { display: flex; gap: 10px; flex-wrap: wrap; }
        .foto-btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 9px 16px; border-radius: 8px; font-size: 0.85rem;
            font-weight: 600; cursor: pointer; border: none; transition: all 0.2s;
            font-family: 'Google Sans', sans-serif;
        }
        .foto-btn-file { background: var(--primary-blue); color: white; }
        .foto-btn-file:hover { background: var(--secondary-blue); }
        .foto-btn-camera { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; }
        .foto-btn-camera:hover { background: #e5e7eb; }
        .foto-btn-camera.hidden { display: none; }

        /* ==============================================
        CROPPER MODAL (file-crop only, solid background)
        ============================================== */
        .custom-crop-modal {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(2, 6, 23, 0.85);
            backdrop-filter: blur(4px);
            z-index: 99999;
            align-items: center; justify-content: center;
            padding: 20px; box-sizing: border-box;
        }
        .custom-crop-modal.active { display: flex; }
        .custom-crop-modal-content {
            background: #ffffff;
            border-radius: 16px;
            width: 100%; max-width: 480px;
            box-shadow: 0 25px 70px rgba(0,0,0,0.6);
            overflow: hidden;
            display: flex; flex-direction: column;
            position: relative;
            isolation: isolate;
        }
        .custom-crop-modal-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid #e5e7eb;
            background: #ffffff;
        }
        .custom-crop-modal-title {
            font-family: 'Google Sans', sans-serif;
            font-size: 1rem; font-weight: 700;
            color: var(--primary-blue); margin: 0;
            display: flex; align-items: center; gap: 8px;
        }
        .custom-crop-modal-close {
            background: #f3f4f6; border: none; cursor: pointer;
            color: #6b7280; font-size: 1.1rem; line-height: 1;
            width: 30px; height: 30px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s;
        }
        .custom-crop-modal-close:hover { background: #e5e7eb; color: #374151; }
        .img-container {
            position: relative; width: 100%;
            aspect-ratio: 1 / 1; max-height: 380px;
            background: #000000; overflow: hidden;
        }
        .img-container img { display: block; max-width: 100%; max-height: 100%; margin: 0 auto; }
        .custom-crop-modal-footer {
            padding: 16px 20px;
            display: flex; align-items: center; justify-content: flex-end; gap: 10px;
            border-top: 1px solid #e5e7eb; background: #ffffff;
        }
        .btn-cancel {
            background: white; color: #374151;
            border: 1px solid #d1d5db;
            padding: 10px 18px; border-radius: 8px;
            font-weight: 600; cursor: pointer; font-size: 0.85rem;
            font-family: 'Google Sans', sans-serif; transition: all 0.2s;
        }
        .btn-cancel:hover { background: #f3f4f6; }
        .btn-crop-save {
            background: var(--primary-blue); color: #ffffff;
            border: none; padding: 10px 20px; border-radius: 8px;
            font-weight: 600; cursor: pointer; font-size: 0.85rem;
            font-family: 'Google Sans', sans-serif;
            display: flex; align-items: center; gap: 8px; transition: all 0.2s;
        }
        .btn-crop-save:hover { background: var(--secondary-blue); }

        /* ==============================================
        FLOATING CAMERA POPUP (draggable, picture-in-picture style)
        ============================================== */
        .floating-camera {
            display: none; position: fixed;
            bottom: 30px; right: 30px;
            width: 320px;
            background: #111827; border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.08);
            z-index: 999999; overflow: hidden;
            cursor: grab; user-select: none;
            animation: camSlideIn 0.3s ease-out;
        }
        .floating-camera.active { display: block; }
        .floating-camera:active { cursor: grabbing; }
        @keyframes camSlideIn { from { opacity:0; transform: translateY(20px) scale(0.95); } to { opacity:1; transform: translateY(0) scale(1); } }
        .floating-camera-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 10px 14px; background: #1f2937;
        }
        .floating-camera-title {
            font-family: 'Google Sans', sans-serif;
            font-size: 0.8rem; font-weight: 600; color: #e5e7eb;
            display: flex; align-items: center; gap: 6px;
        }
        .floating-camera-title .rec-dot {
            width: 8px; height: 8px; border-radius: 50%; background: #ef4444;
            animation: recBlink 1.2s infinite;
        }
        @keyframes recBlink { 0%,100% { opacity:1; } 50% { opacity:0.3; } }
        .floating-camera-close {
            background: rgba(255,255,255,0.1); border: none; cursor: pointer;
            color: #9ca3af; width: 26px; height: 26px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem; transition: all 0.2s;
        }
        .floating-camera-close:hover { background: rgba(255,255,255,0.2); color: white; }
        .floating-camera-video-wrap {
            position: relative; width: 100%;
            aspect-ratio: 1/1; background: #000; overflow: hidden;
        }
        .floating-camera-video-wrap video {
            width: 100%; height: 100%; object-fit: cover;
            transform: scaleX(-1);
        }
        .floating-camera-video-wrap .face-guide {
            position: absolute; inset: 0;
            display: flex; align-items: center; justify-content: center;
            pointer-events: none;
        }
        .floating-camera-video-wrap .face-oval {
            width: 60%; aspect-ratio: 3/4;
            border: 2px dashed rgba(255,255,255,0.7);
            border-radius: 50%/42%;
            box-shadow: 0 0 0 2000px rgba(0,0,0,0.3);
        }
        .floating-camera-video-wrap .face-hint {
            position: absolute; bottom: 10px; left: 0; right: 0;
            text-align: center; color: white;
            font-family: 'Google Sans', sans-serif;
            font-size: 0.72rem; font-weight: 600;
            text-shadow: 0 1px 3px rgba(0,0,0,0.6);
        }
        .floating-camera-actions {
            display: flex; align-items: center; justify-content: center;
            padding: 14px; background: #1f2937;
        }
        .floating-shutter {
            width: 52px; height: 52px; border-radius: 50%;
            background: #ffffff; border: 3px solid #ef4444;
            cursor: pointer; padding: 0; transition: transform 0.15s;
            display: flex; align-items: center; justify-content: center;
        }
        .floating-shutter::before {
            content: ''; width: 40px; height: 40px; border-radius: 50%;
            background: #ef4444; transition: all 0.15s;
        }
        .floating-shutter:hover { transform: scale(1.08); }
        .floating-shutter:hover::before { background: #dc2626; }
        .floating-shutter:active { transform: scale(0.92); }

        /* Custom Toast Notification */
        .custom-toast {
            border-left: 6px solid #10b981 !important;
            border-radius: 8px !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
            padding: 12px 20px !important;
            margin-top: 90px !important; /* Supaya tidak tertutup / nabrak header sticky */
        }
        .custom-toast .swal2-title {
            color: #022648 !important;
            font-weight: 700 !important;
            font-size: 1.05rem !important;
            font-family: 'Google Sans', sans-serif !important;
        }

        /* ==============================================
        MEMBER HERO BANNER & STAT CARDS (SIKTN BENCHMARK)
        ============================================== */
        .member-hero-banner {
            background: linear-gradient(135deg, #022648 0%, #083b6e 60%, #01162f 100%);
            border-radius: 8px;
            padding: 26px 30px;
            margin-bottom: 24px;
            color: #ffffff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 12px 32px rgba(2, 38, 72, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .member-hero-banner::after {
            content: '';
            position: absolute;
            right: -60px;
            bottom: -60px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(183, 131, 15, 0.12);
            pointer-events: none;
        }

        .hero-banner-inner {
            display: flex;
            align-items: center;
            gap: 24px;
            position: relative;
            z-index: 2;
            flex-wrap: wrap;
        }

        .hero-avatar-wrapper {
            position: relative;
            flex-shrink: 0;
        }

        .hero-avatar-ring {
            width: 95px;
            height: 95px;
            border-radius: 50%;
            border: 3.5px solid #b7830f;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-avatar-initial {
            font-size: 2rem;
            font-weight: 800;
            color: #022648;
            background: #f1f5f9;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-edit-badge {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #b7830f;
            color: #ffffff;
            border: 2px solid #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .avatar-edit-badge:hover {
            background: #d49b16;
            transform: scale(1.1);
        }

        .hero-info-box {
            flex-grow: 1;
        }

        .hero-portal-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(183, 131, 15, 0.2);
            color: #f3c350;
            border: 1px solid rgba(243, 195, 80, 0.3);
            font-size: 10px;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 4px;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .hero-member-name {
            font-size: 1.65rem;
            font-weight: 800;
            margin: 0 0 8px 0;
            color: #ffffff;
            letter-spacing: -0.3px;
        }

        .hero-meta-list {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.85);
        }

        .hero-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
        }

        .hero-meta-item strong {
            color: #ffffff;
        }

        .hero-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .hero-status-pill.approved {
            background: rgba(34, 197, 94, 0.2);
            color: #4ade80;
            border: 1px solid rgba(74, 222, 128, 0.4);
        }

        .hero-status-pill.pending {
            background: rgba(234, 179, 8, 0.2);
            color: #fef08a;
            border: 1px solid rgba(254, 240, 138, 0.4);
        }

        /* STAT CARDS (SIKTN BENCHMARK) */
        .stat-cards-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 24px;
        }

        @media (max-width: 1024px) {
            .stat-cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .stat-cards-grid {
                grid-template-columns: 1fr;
            }
        }

        .stat-card {
            background: #ffffff;
            border-radius: 8px;
            padding: 16px 18px;
            box-shadow: 0 4px 14px rgba(2, 38, 72, 0.06);
            border: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 14px;
            position: relative;
            overflow: hidden;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(2, 38, 72, 0.12);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
        }

        .stat-card.approved::before { background: #059669; }
        .stat-card.gold::before { background: #b7830f; }
        .stat-card.total::before { background: #022648; }
        .stat-card.blue::before { background: #2563eb; }

        .stat-card-icon {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .stat-card.approved .stat-card-icon { background: #dcfce7; color: #059669; }
        .stat-card.gold .stat-card-icon { background: #fef3c7; color: #b7830f; }
        .stat-card.total .stat-card-icon { background: #e0e7ff; color: #022648; }
        .stat-card.blue .stat-card-icon { background: #dbeafe; color: #2563eb; }

        .stat-card-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .stat-card-label {
            font-size: 0.72rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card-value {
            font-size: 1.05rem;
            font-weight: 800;
            color: #022648;
            margin: 0;
            line-height: 1.2;
        }

        /* ACTION DROPDOWN TRIGGER (SIKTN BENCHMARK POINT 2) */
        .btn-aksi-trigger {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #022648;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 3px rgba(2, 38, 72, 0.12);
        }
        .btn-aksi-trigger:hover {
            background: #083b6e;
            transform: scale(1.08) translateY(-1px);
            box-shadow: 0 4px 12px rgba(2, 38, 72, 0.25);
        }
        .aksi-dropdown {
            display: block;
            position: fixed;
            min-width: 180px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            box-shadow: 0 12px 28px rgba(2, 38, 72, 0.18);
            padding: 6px;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px) scale(0.96);
            transition: opacity 0.18s cubic-bezier(0.16, 1, 0.3, 1), transform 0.18s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.18s;
            pointer-events: none;
        }
        .aksi-dropdown.is-open {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }
        .aksi-item {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            padding: 0.55rem 0.65rem;
            font-size: 0.8125rem;
            font-weight: 600;
            border-radius: 4px;
            color: #111827;
            text-decoration: none !important;
            border: none;
            background: transparent;
            text-align: left;
            cursor: pointer;
            transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .aksi-item:hover {
            background: #f3f4f6;
            transform: translateX(4px);
        }
        /* ==============================================
        SELECT2 CUSTOM STYLING & MICRO-ANIMATIONS (SIKTN BENCHMARK)
        ============================================== */
        @keyframes select2DropdownFadeIn {
            from {
                opacity: 0;
                transform: translateY(-8px) scale(0.97);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .select2-container--default .select2-selection--single {
            height: 42px !important;
            padding: 0.35rem 0.75rem !important;
            font-size: 0.875rem !important;
            font-weight: 600 !important;
            color: #022648 !important;
            background-color: #fff !important;
            border: 1px solid #d1d5db !important;
            border-radius: 6px !important;
            display: flex !important;
            align-items: center !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single:hover,
        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #022648 !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 3px 8px rgba(2, 38, 72, 0.12) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #022648 !important;
            font-weight: 600 !important;
            padding-left: 0 !important;
            line-height: normal !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #6b7280 !important;
            font-weight: 500 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
            right: 8px !important;
        }

        .select2-dropdown {
            border: 1px solid #d1d5db !important;
            border-radius: 6px !important;
            font-size: 0.875rem !important;
            z-index: 99999 !important;
            box-shadow: 0 12px 28px rgba(2, 38, 72, 0.18) !important;
            margin-top: 4px !important;
            overflow: hidden !important;
            background-color: #ffffff !important;
        }

        .select2-container--open .select2-dropdown {
            animation: select2DropdownFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards !important;
        }

        .select2-container--default .select2-results__option {
            padding: 8px 14px !important;
            font-size: 0.875rem !important;
            transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        /* HIGHLIGHTED OPTION (HOVER/NAVIGATE) - MUST BE NAVY #022648 WITH WHITE BOLD TEXT */
        .select2-container--default .select2-results__option--highlighted,
        .select2-container--default .select2-results__option--highlighted[aria-selected],
        .select2-container--default .select2-results__option--highlighted[aria-selected="true"],
        .select2-container--default .select2-results__option--highlighted[aria-selected="false"],
        .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable,
        .select2-results__option--highlighted,
        .select2-results__option--highlighted[aria-selected] {
            background-color: #022648 !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            padding-left: 1.15rem !important;
        }

        /* SELECTED OPTION */
        .select2-container--default .select2-results__option[aria-selected="true"],
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #f3f4f6 !important;
            color: #022648 !important;
            font-weight: 700 !important;
        }

        .select2-search--dropdown {
            padding: 8px !important;
        }

        .select2-search--dropdown .select2-search__field {
            border: 1px solid #d1d5db !important;
            border-radius: 6px !important;
            padding: 0.5rem 0.75rem !important;
            outline: none !important;
            font-size: 0.875rem !important;
        }

        .select2-search--dropdown .select2-search__field:focus {
            border-color: #022648 !important;
            box-shadow: 0 0 0 3px rgba(2, 38, 72, 0.1) !important;
        }

        .select2-results__group {
            padding: 8px 12px !important;
            font-size: 0.75rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            color: #022648 !important;
            font-weight: 800 !important;
            background-color: #f8fafc !important;
            border-bottom: 1px solid #e2e8f0 !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
@endpush

@section('content')
    <div class="dashboard-container admin-ui-scope">
        @if(session('password_success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Password Berubah!',
                        html: `
                            <p style="margin-bottom: 10px; font-size: 0.85rem; color: #475569; margin-top: 5px;">Ini password baru Anda, klik copy sebelum hilang:</p>
                            <div style="background: #f8fafc; padding: 8px 12px; border-radius: 6px; display: flex; justify-content: space-between; align-items: center; border: 1px dashed #cbd5e1;">
                                <span id="newPasswordText" style="font-size: 1rem; color: #0f172a; font-weight: bold;">{{ session('password_success') }}</span>
                                <button onclick="navigator.clipboard.writeText('{{ session('password_success') }}'); const btn = this; const oldHtml = btn.innerHTML; btn.innerHTML = '<i class=\\'fas fa-check\\'></i>'; btn.style.background = '#10b981'; setTimeout(() => { btn.innerHTML = oldHtml; btn.style.background = '#022648'; }, 2000);" style="background: #022648; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer; font-size: 12px; display: flex; align-items: center; gap: 4px; transition: all 0.2s;"><i class="fas fa-copy"></i> Copy</button>
                            </div>
                        `,
                        showConfirmButton: false,
                        timer: 15000, // 15 detik biar ada waktu buat ngopi
                        timerProgressBar: true,
                        customClass: {
                            popup: 'custom-toast'
                        }
                    });
                });
            </script>
        @endif

        @if(session('success'))
            <script>
                // Hapus cache foto sementara jika berhasil simpan
                localStorage.removeItem('temp_cropped_photo_{{ $anggota->id }}');
                
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: "{{ session('success') }}",
                        showConfirmButton: false,
                        timer: 3500,
                        timerProgressBar: true,
                        customClass: {
                            popup: 'custom-toast'
                        }
                    });
                });
            </script>
        @endif

        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: "{{ session('error') }}",
                        confirmButtonColor: '#022648'
                    });
                });
            </script>
        @endif

        @if($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal',
                        html: '<ul style="text-align: left; margin: 0; padding-left: 20px;">' +
                              @foreach($errors->all() as $error)
                                  '<li>{{ $error }}</li>' +
                              @endforeach
                              '</ul>',
                        confirmButtonColor: '#022648'
                    });
                });
            </script>
        @endif

        <!-- MEMBER HERO BANNER (PORTAL ANGGOTA RESMI) -->
        <div class="member-hero-banner">
            <div class="hero-banner-inner">
                <div class="hero-avatar-wrapper">
                    <div class="hero-avatar-ring">
                        @if($anggota->foto_diri && Storage::disk('public')->exists($anggota->foto_diri))
                            <img src="{{ Storage::url($anggota->foto_diri) }}" class="hero-avatar-img" alt="{{ $anggota->nama_lengkap }}">
                        @else
                            <div class="hero-avatar-initial">{{ strtoupper(substr($anggota->nama_lengkap ?? 'A', 0, 2)) }}</div>
                        @endif
                    </div>
                    <button type="button" class="avatar-edit-badge" onclick="document.getElementById('foto_diri').click();" title="Ubah Foto Profil">
                        <i class="fas fa-camera"></i>
                    </button>
                </div>
                <div class="hero-info-box">
                    <div class="hero-portal-badge">
                        <i class="fas fa-shield-alt"></i> PORTAL ANGGOTA RESMI | PNKT SIKTN
                    </div>
                    <h1 class="hero-member-name">{{ $anggota->nama_lengkap }}</h1>
                    <div class="hero-meta-list">
                        <span class="hero-meta-item"><i class="fas fa-id-badge" style="color: #f3c350;"></i> NIK: <strong>{{ $anggota->nik ?? '-' }}</strong></span>
                        <span class="hero-meta-item"><i class="fas fa-user-tie" style="color: #60a5fa;"></i> Jabatan: <strong>{{ $anggota->jabatan ?? '-' }}</strong></span>
                        <span class="hero-status-pill @if($anggota->status == 'approved') approved @else pending @endif">
                            <i class="fas fa-circle" style="font-size: 6px;"></i> {{ strtoupper($anggota->status == 'approved' ? 'ANGGOTA AKTIF' : $anggota->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- STAT CARDS (SIKTN ADMIN UI BENCHMARK) -->
        <div class="stat-cards-grid">
            <div class="stat-card approved">
                <div class="stat-card-icon"><i class="fas fa-user-check"></i></div>
                <div class="stat-card-info">
                    <span class="stat-card-label">Status Keanggotaan</span>
                    <h3 class="stat-card-value">{{ $anggota->status == 'approved' ? 'Terverifikasi (Aktif)' : ucfirst($anggota->status) }}</h3>
                </div>
            </div>

            <div class="stat-card gold">
                <div class="stat-card-icon"><i class="fas fa-id-card"></i></div>
                <div class="stat-card-info">
                    <span class="stat-card-label">Nomor Induk Anggota</span>
                    <h3 class="stat-card-value" style="font-family: monospace;">{{ $anggota->nik ?? '-' }}</h3>
                </div>
            </div>

            <div class="stat-card total">
                <div class="stat-card-icon"><i class="fas fa-store"></i></div>
                <div class="stat-card-info">
                    <span class="stat-card-label">Katalog Saya</span>
                    <h3 class="stat-card-value">{{ \App\Models\Katalog::where('anggota_id', $anggota->id)->count() }} Produk</h3>
                </div>
            </div>

            <div class="stat-card blue">
                <div class="stat-card-icon"><i class="fas fa-qrcode"></i></div>
                <div class="stat-card-info">
                    <span class="stat-card-label">KTA Digital</span>
                    <h3 class="stat-card-value">{{ $anggota->status == 'approved' ? 'Siap Unduh / Cetak' : 'Belum Divalidasi' }}</h3>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <!-- SIDEBAR -->
            <aside class="dashboard-sidebar">
                <div class="user-badge">
                    <div class="user-avatar-wrapper">
                        @if($anggota->foto_diri && Storage::disk('public')->exists($anggota->foto_diri))
                            <img src="{{ Storage::url($anggota->foto_diri) }}" class="user-avatar" alt="{{ $anggota->nama_lengkap }}">
                        @else
                            <div class="user-avatar-placeholder" style="width: 100%; height: 100%; background: #022648; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; font-weight: 800; font-family: 'Montserrat', sans-serif;">
                                {{ strtoupper(substr($anggota->nama_lengkap ?? 'A', 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <h4 class="user-name">{{ $anggota->nama_lengkap }}</h4>
                    <div class="user-nrp">{{ $anggota->jabatan ?? 'Anggota' }}</div>

                    @if($anggota->status == 'pending_profile')
                        <span class="status-badge status-pending"><i class="fas fa-exclamation-triangle"></i> LENGKAPI PROFIL</span>
                    @elseif($anggota->status == 'pending_verification' || $anggota->status == 'pending')
                        <span class="status-badge status-pending"><i class="fas fa-clock"></i> MENUNGGU VERIFIKASI</span>
                    @elseif($anggota->status == 'approved')
                        <span class="status-badge status-approved"><i class="fas fa-check-circle"></i> ANGGOTA AKTIF</span>
                    @else
                        <span class="status-badge status-rejected"><i class="fas fa-times-circle"></i> DITOLAK</span>
                    @endif
                </div>

                <nav class="sidebar-menu">
                    @if(!in_array($anggota->status, ['pending', 'pending_profile']))
                    <button class="sidebar-menu-btn active" onclick="switchTab('informasi-umum', this)">
                        <i class="fas fa-user-circle"></i> Informasi Umum
                    </button>
                    @endif

                    @if($anggota->status === 'approved')
                    <button class="sidebar-menu-btn" onclick="switchTab('kta-digital', this)">
                        <i class="fas fa-id-card"></i> KTA Digital
                    </button>
                    <button class="sidebar-menu-btn" onclick="switchTab('program-diikuti', this)">
                        <i class="fas fa-tasks"></i> Program
                    </button>
                    @endif
                    
                    @if(in_array($anggota->status, ['pending', 'pending_profile']))
                    <button class="sidebar-menu-btn active" onclick="switchTab('profil-perusahaan', this)">
                        <i class="fas fa-user-edit"></i> Lengkapi Profil
                    </button>
                    @endif

                    @if($anggota->status === 'approved')
                    <a href="{{ route('anggota.katalog.index') }}" class="sidebar-menu-btn">
                        <i class="fas fa-store"></i> Katalog Saya
                    </a>
                    @endif
                    <button class="sidebar-menu-btn" onclick="switchTab('keamanan', this)">
                        <i class="fas fa-lock"></i> Ubah Password
                    </button>

                    <form action="{{ route('anggota.logout') }}" method="POST" style="margin-top: 15px; width: 100%;">
                        @csrf
                        <button type="submit" class="sidebar-menu-btn" style="color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.2);">
                            <i class="fas fa-sign-out-alt"></i> Keluar / Logout
                        </button>
                    </form>
                </nav>
            </aside>

            <!-- MAIN CONTENT -->
            <main class="dashboard-content">

                <!-- ==============================================
                TAB: INFORMASI UMUM
                ============================================== -->
                <div id="informasi-umum" class="content-section @if(!in_array($anggota->status, ['pending', 'pending_profile'])) active @endif">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 10px;">
                        <h2 class="section-title" style="margin-bottom: 0;"><i class="fas fa-user"></i> Data Pribadi Anggota</h2>
                        <button type="button" class="btn-edit-profil" onclick="switchTab('profil-perusahaan', null)" style="background: #022648; color: #ffffff; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; font-size: 0.9rem; transition: all 0.2s;">
                            <i class="fas fa-edit"></i> Edit Profil
                        </button>
                    </div>

                    <div class="info-grid">
                        <div class="info-card">
                            <div class="info-card-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="info-card-content">
                                <span class="info-card-label">NIK</span>
                                <span class="info-card-value">{{ $anggota->nik ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-card-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="info-card-content">
                                <span class="info-card-label">Nama Lengkap</span>
                                <span class="info-card-value">{{ $anggota->nama_lengkap }}</span>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-card-icon">
                                <i class="fas fa-birthday-cake"></i>
                            </div>
                            <div class="info-card-content">
                                <span class="info-card-label">Tempat & Tanggal Lahir</span>
                                <span class="info-card-value">{{ $anggota->tempat_lahir ?? '-' }}, {{ $anggota->tanggal_lahir ? $anggota->tanggal_lahir->format('d F Y') : '-' }}</span>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-card-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="info-card-content">
                                <span class="info-card-label">Domisili</span>
                                <span class="info-card-value">{{ $anggota->domisili ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="info-card" style="grid-column: span 2;">
                            <div class="info-card-icon">
                                <i class="fas fa-home"></i>
                            </div>
                            <div class="info-card-content">
                                <span class="info-card-label">Alamat Lengkap</span>
                                <span class="info-card-value">{{ $anggota->alamat_lengkap ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-card-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="info-card-content">
                                <span class="info-card-label">Pendidikan Terakhir</span>
                                <span class="info-card-value">{{ $anggota->pendidikan_terakhir ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-card-icon">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <div class="info-card-content">
                                <span class="info-card-label">Pekerjaan</span>
                                <span class="info-card-value">{{ $anggota->pekerjaan ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-card-icon">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="info-card-content">
                                <span class="info-card-label">No. Telepon / WhatsApp</span>
                                <span class="info-card-value">{{ $anggota->no_hp ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-card-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-card-content">
                                <span class="info-card-label">Email</span>
                                <span class="info-card-value">{{ $anggota->email }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ==============================================
                TAB: KTA DIGITAL
                ============================================== -->
                <div id="kta-digital" class="content-section">
                    <h2 class="section-title"><i class="fas fa-id-card"></i> Kartu Tanda Anggota Digital</h2>

                    @if($anggota->status !== 'approved')
                        <div class="locked-state-box" style="background-color: #FEFCE8; border-color: #EAB308; margin-top: 20px;">
                            <div class="locked-icon" style="color: #CA8A04;">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h3 class="locked-title" style="color: #A16207;">KTA Belum Tersedia</h3>
                            <p class="locked-desc" style="color: #854D0E;">
                                Kartu Tanda Anggota (KTA Digital) resmi Karang Taruna Indonesia akan diterbitkan secara otomatis setelah status pendaftaran Anda diverifikasi dan <strong>di-ACC oleh Admin Wilayah (PPKT / PKKT)</strong>.
                            </p>
                        </div>
                    @else
                        <p style="color: var(--text-grey); font-size: 0.95rem; margin-bottom: 20px;">
                            Berikut adalah Kartu Tanda Anggota Digital resmi Anda. Silakan unduh atau cetak KTA untuk digunakan sebagai bukti tanda keanggotaan.
                        </p>

                        <div class="kta-card-wrapper" id="ktaCardToPrint">
                        <!-- Front Side KTA -->
                        <div class="kta-card" id="ktaCardFront">
                            <div class="kta-header-front">
                                <h2>KARANG TARUNA</h2>
                                <p>INDONESIA</p>
                            </div>
                            <div class="kta-gold-bar"></div>

                            <div class="kta-front-curve"></div>

                            <div class="kta-logo-front">
                                <img src="{{ asset('assets-front/images/logo_karang_taruna.png') }}" alt="Karang Taruna">
                            </div>

                            <div class="kta-member-name">{{ $anggota->nama_lengkap }}</div>
                            <div class="kta-member-nik-container">
                                <span class="kta-member-nik-badge">NIA: {{ $anggota->nik ?? $anggota->nrp ?? $anggota->username ?? '-' }}</span>
                            </div>

                            <div class="kta-anchor-divider">
                                <i class="fas fa-star"></i>
                            </div>

                            <div class="kta-details-container">
                                <div class="kta-photo-wrapper">
                                    @if($anggota->foto_diri)
                                        <img src="{{ Storage::url($anggota->foto_diri) }}" alt="{{ $anggota->nama_lengkap }}">
                                    @else
                                        <i class="fas fa-user text-muted" style="font-size: 2.2rem; color: #94a3b8;"></i>
                                    @endif
                                </div>

                                <div class="kta-details-list">
                                    <div class="kta-detail-row">
                                        <div class="kta-detail-label">USERNAME</div>
                                        <div class="kta-detail-value">: {{ $anggota->username ?? '-' }}</div>
                                    </div>
                                    <div class="kta-detail-row">
                                        <div class="kta-detail-label">NAMA</div>
                                        <div class="kta-detail-value">: {{ $anggota->nama_lengkap }}</div>
                                    </div>
                                    <div class="kta-detail-row">
                                        <div class="kta-detail-label">WILAYAH</div>
                                        <div class="kta-detail-value">: {{ $anggota->domisili ?? '-' }}</div>
                                    </div>
                                    <div class="kta-detail-row">
                                        <div class="kta-detail-label">E-MAIL</div>
                                        <div class="kta-detail-value">: {{ $anggota->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="kta-bottom-row">
                                <div class="kta-qr-code" id="ktaQrCodeContainer">
                                    {!! \App\Services\QrCodeSvg::generate(route('detail-buku', $anggota->id)) !!}
                                </div>
                                <div class="kta-signature">
                                    <img src="{{ asset('assets-front/images/signature_dummy.png') }}" alt="Signature" class="kta-signature-img" onerror="this.style.display='none'">
                                    <p class="kta-signature-text">KARANG TARUNA INDONESIA</p>
                                    <p class="kta-signature-subtext">KETUA UMUM</p>
                                </div>
                            </div>
                            <div class="kta-bottom-bar"></div>
                        </div>

                        <!-- Back Side KTA -->
                        <div class="kta-card kta-card-back" id="ktaCardBack">
                            <div class="kta-header-front">
                                <h2>KARTU TANDA ANGGOTA</h2>
                            </div>
                            <div class="kta-gold-bar"></div>

                            <div class="kta-back-content">
                                <img src="{{ asset('assets-front/images/logo_karang_taruna.png') }}" class="kta-watermark" alt="Watermark" onerror="this.src=''">

                                <div class="kta-back-ketentuan-title">Ketentuan:</div>
                                <ol class="kta-back-ketentuan-list">
                                    <li>Kartu ini merupakan tanda pengenal resmi anggota Karang Taruna.</li>
                                    <li>Kartu ini tidak dapat dipindahtangankan.</li>
                                    <li>Harap membawa kartu ini saat menghadiri kegiatan alumni.</li>
                                    <li>Apabila kartu ini hilang, segera laporkan ke sekretariat alumni.</li>
                                </ol>
                            </div>

                            <div class="kta-back-footer">
                                <div class="kta-back-footer-curve"></div>
                                <div class="kta-back-footer-content">
                                    <h3 class="kta-back-footer-title">KARANG TARUNA</h3>
                                    <h4 class="kta-back-footer-subtitle">INDONESIA</h4>

                                    <div class="kta-back-address">
                                        Jl. Raya Boulevard Barat No. 1, Kelapa<br>Gading, Jakarta Utara 14240
                                    </div>

                                    <div class="kta-back-contact">
                                        <span><i class="fas fa-globe"></i> www.karangtaruna.or.id</span>
                                        <span><i class="fas fa-envelope"></i> sekretariat@karangtaruna.or.id</span>
                                    </div>

                                    <div class="kta-back-validity">
                                        KARTU INI BERLAKU SELAMA ANGGOTA<br>TERDAFTAR SEBAGAI PENGURUS AKTIF
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                        <div style="text-align: center; margin-top: 25px;">
                            <button type="button" class="btn-submit" onclick="printKTA()" style="background-color: var(--primary-blue);">
                                <i class="fas fa-print"></i> Cetak Kartu Anggota (Print / PDF)
                            </button>
                        </div>
                    @endif
                </div>

                <!-- ==============================================
                TAB: PROGRAM DIIKUTI
                ============================================== -->
                <div id="program-diikuti" class="content-section">
                    <h2 class="section-title"><i class="fas fa-tasks"></i> Program Kerja yang Sedang Diikuti</h2>
                    
                    @if($anggota->status !== 'approved')
                        <div class="locked-state-box" style="background-color: #FEFCE8; border-color: #EAB308; margin-top: 20px;">
                            <div class="locked-icon" style="color: #CA8A04;">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h3 class="locked-title" style="color: #A16207;">Program Belum Tersedia</h3>
                            <p class="locked-desc" style="color: #854D0E;">
                                Fitur Program Kerja yang diikuti hanya tersedia untuk anggota aktif yang telah disetujui.
                            </p>
                        </div>
                    @else
                        <p style="color: var(--text-grey); font-size: 0.95rem; margin-bottom: 25px;">
                            Berikut adalah daftar program kerja Karang Taruna Indonesia yang sedang Anda ikuti saat ini.
                        </p>

                        @if(empty($programs) || $programs->isEmpty())
                            <div style="text-align: center; padding: 3rem 1.5rem; background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
                                <div style="width: 64px; height: 64px; background: #f1f5f9; color: #64748b; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1.25rem;">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                </div>
                                <h3 style="font-size: 1.1rem; font-weight: 700; color: #022648; margin-bottom: 0.5rem;">Belum Mengikuti Program</h3>
                                <p style="font-size: 0.875rem; color: #64748b; margin-bottom: 1.5rem; max-width: 400px; display: inline-block;">
                                    Anda belum terdaftar di program kerja manapun. Jelajahi program kerja aktif kami dan mulailah berpartisipasi.
                                </p>
                                <div>
                                    <a href="/program" style="display: inline-flex; align-items: center; gap: 8px; background: #022648; color: white; padding: 10px 20px; border-radius: 6px; font-weight: 700; font-size: 0.875rem; text-decoration: none; transition: background 0.2s;">
                                        Jelajahi Program Kerja
                                    </a>
                                </div>
                            </div>
                        @else
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                                @foreach($programs as $prog)
                                    @php
                                        $joinedAt = $prog->pivot->created_at ? \Carbon\Carbon::parse($prog->pivot->created_at)->translatedFormat('d F Y') : '-';
                                    @endphp
                                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.03); display: flex; flex-direction: column; height: 100%; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 24px rgba(2, 38, 72, 0.08)'" onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.03)'">
                                        <div style="height: 140px; position: relative; background: #cbd5e1; overflow: hidden;">
                                            <img src="{{ $prog->gambar_url }}" alt="{{ $prog->nama_program }}" style="width: 100%; height: 100%; object-fit: cover;">
                                            <span style="position: absolute; top: 12px; right: 12px; padding: 4px 10px; font-size: 0.7rem; font-weight: 700; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px;
                                                @if($prog->status === 'aktif') background:#dcfce7; color:#15803d;
                                                @elseif($prog->status === 'selesai') background:#dbeafe; color:#1d4ed8;
                                                @else background:#ffedd5; color:#c2410c; @endif">
                                                {{ strtoupper($prog->status) }}
                                            </span>
                                        </div>
                                        <div style="padding: 1.25rem; flex: 1; display: flex; flex-direction: column;">
                                            <div style="font-size: 0.725rem; font-weight: 700; color: #a3a3a3; text-transform: uppercase; margin-bottom: 4px;">{{ $prog->kategori ?? 'Program Kerja' }}</div>
                                            <h3 style="font-size: 1rem; font-weight: 800; color: #022648; line-height: 1.4; margin-bottom: 0.75rem;">{{ $prog->nama_program }}</h3>
                                            
                                            <div style="margin-top: auto; border-top: 1px solid #f1f5f9; padding-top: 0.85rem; font-size: 0.775rem; color: #64748b;">
                                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                                    <span>PIC Program:</span>
                                                    <strong style="color: #022648;">{{ $prog->pic ?? '-' }}</strong>
                                                </div>
                                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                                    <span>Periode:</span>
                                                    <strong style="color: #022648;">{{ $prog->periode_mulai ? $prog->periode_mulai->translatedFormat('M Y') : '-' }} - {{ $prog->periode_selesai ? $prog->periode_selesai->translatedFormat('M Y') : '-' }}</strong>
                                                </div>
                                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px; background: #f8fafc; padding: 4px 6px; border-radius: 4px; border: 1px solid #f1f5f9;">
                                                    <span>Terdaftar Pada:</span>
                                                    <strong style="color: #022648;">{{ $joinedAt }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>

                <!-- ==============================================
                TAB: LENGKAPI PROFIL (SIKTN)
                ============================================== -->
                <div id="profil-perusahaan" class="content-section @if(in_array($anggota->status, ['pending', 'pending_profile'])) active @endif">
                    <h2 class="section-title"><i class="fas fa-user-edit"></i> Lengkapi Profil Anggota</h2>

                    @if($anggota->status === 'approved')
                        <div class="locked-state-box" style="background-color: #F0FDF4; border-color: #22C55E; margin-bottom: 25px;">
                            <div class="locked-icon" style="color: #16A34A;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h3 class="locked-title" style="color: #15803D;">Profil Disetujui</h3>
                            <p class="locked-desc" style="color: #166534;">
                                Profil Anda telah disetujui oleh Sekretariat. Anda dapat memperbarui data jika ada perubahan.
                            </p>
                        </div>
                    @elseif($anggota->status === 'pending_verification')
                        <div class="locked-state-box" style="background-color: #FEFCE8; border-color: #EAB308; margin-bottom: 25px;">
                            <div class="locked-icon" style="color: #CA8A04;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h3 class="locked-title" style="color: #A16207;">Menunggu Verifikasi</h3>
                            <p class="locked-desc" style="color: #854D0E;">
                                Profil Anda sedang direview oleh Sekretariat. Anda masih dapat mengubah data selama belum disetujui.
                            </p>
                        </div>
                    @endif

                    <form action="{{ route('profile-anggota.update-profile') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-grid">
                            <div class="form-section-divider" style="margin-top: 0;">1. Data Pribadi</div>

                            <div class="form-group" style="grid-column: span 2;">
                                <label>Foto Profil <span style="color:red;">*</span></label>
                                <input type="file" name="foto_diri" id="foto_diri" accept="image/jpeg,image/png,image/jpg" style="display:none;" data-required="{{ !$anggota->foto_diri ? 'true' : 'false' }}">
                                <input type="hidden" name="foto_diri_base64" id="foto_diri_base64">

                                <div class="foto-upload-wrapper">
                                    <div class="foto-avatar-preview" id="foto_avatar_preview">
                                        @if($anggota->foto_diri)
                                            <img id="preview_image" src="{{ Storage::url($anggota->foto_diri) }}" alt="Preview">
                                        @else
                                            <span class="avatar-placeholder"><i class="fas fa-user"></i></span>
                                        @endif
                                    </div>
                                    <div class="foto-upload-actions">
                                        <p>Unggah foto profil Anda. Format: JPG, PNG. Setelah dipilih, Anda bisa menyesuaikan area potong (crop) menjadi persegi.</p>
                                        <div class="foto-btn-group">
                                            <button type="button" class="foto-btn foto-btn-file" onclick="document.getElementById('foto_diri').click()">
                                                <i class="fas fa-folder-open"></i> Pilih dari File
                                            </button>
                                            <button type="button" class="foto-btn foto-btn-camera" id="btnOpenCamera" style="background: #022648; color: #ffffff; padding: 8px 16px; border-radius: 6px; border: none; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                                                <i class="fas fa-camera"></i> Ambil Foto Kamera
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="nik">NIA (Nomor Induk Anggota) <span style="color:red;">*</span></label>
                                <input type="text" name="nik" id="nik" class="form-control" 
                                    value="{{ old('nik', $anggota->nik) }}" 
                                    required 
                                    placeholder="Masukkan Nomor Induk Anggota (NIA)">
                            </div>

                            <div class="form-group">
                                <label for="nama_lengkap">Nama Lengkap <span style="color:red;">*</span></label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $anggota->nama_lengkap) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="tempat_lahir">Tempat Lahir <span style="color:red;">*</span></label>
                                <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $anggota->tempat_lahir) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="text" name="tanggal_lahir" id="tanggal_lahir" class="form-control datepicker" value="{{ old('tanggal_lahir', $anggota->tanggal_lahir ? $anggota->tanggal_lahir->format('Y-m-d') : '') }}" required placeholder="Pilih tanggal">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="form-group" style="grid-column: span 2;">
                                <label for="alamat_lengkap">Alamat Lengkap <span style="color:red;">*</span></label>
                                <textarea name="alamat_lengkap" id="alamat_lengkap" class="form-control" rows="3" required>{{ old('alamat_lengkap', $anggota->alamat_lengkap ?? $anggota->alamat_domisili) }}</textarea>
                            </div>

                            <div class="form-section-divider">2. Informasi Organisasi & Profesi</div>

                            <div class="form-group">
                                 <label for="jabatan">Jabatan <span style="color:red;">*</span></label>
                                <select name="jabatan" id="jabatanSelect" class="form-control select2-basic" required style="width: 100%;">
                                    <option value="">-- Pilih Jabatan --</option>
                                    @php 
                                        $currentJabatan = old('jabatan', $anggota->jabatan ?? ''); 
                                        $jabatanFound = false;
                                    @endphp
                                    @if(isset($jabatans))
                                        @foreach($jabatans->unique('nama_jabatan') as $jab)
                                            @php 
                                                if($currentJabatan == $jab->nama_jabatan) $jabatanFound = true;
                                                $isKetuaUmum = (strcasecmp($jab->nama_jabatan, 'Ketua Umum') === 0);
                                            @endphp
                                            @if(!$isKetuaUmum || $currentJabatan == $jab->nama_jabatan)
                                                <option value="{{ $jab->nama_jabatan }}" {{ $currentJabatan == $jab->nama_jabatan ? 'selected' : '' }}>
                                                    {{ $jab->nama_jabatan }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endif
                                    
                                    {{-- Fallback jika jabatan lama anggota tidak ada di master jabatan --}}
                                    @if($currentJabatan && !$jabatanFound)
                                        <option value="{{ $currentJabatan }}" selected>{{ $currentJabatan }}</option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="domisili">Domisili <span style="color:red;">*</span></label>
                                @php
                                    $currentDomisili = trim(old('domisili', $anggota->domisili ?? ''));
                                    $provinces = ["Aceh","Sumatera Utara","Sumatera Barat","Riau","Jambi","Sumatera Selatan","Bengkulu","Lampung","Kepulauan Bangka Belitung","Kepulauan Riau","DKI Jakarta","Jawa Barat","Jawa Tengah","DI Yogyakarta","Jawa Timur","Banten","Bali","Nusa Tenggara Barat","Nusa Tenggara Timur","Kalimantan Barat","Kalimantan Tengah","Kalimantan Selatan","Kalimantan Timur","Kalimantan Utara","Sulawesi Utara","Sulawesi Tengah","Sulawesi Selatan","Sulawesi Tenggara","Gorontalo","Sulawesi Barat","Maluku","Maluku Utara","Papua Barat","Papua"];
                                    $regenciesJson = public_path('regencies.json');
                                    $regencies = file_exists($regenciesJson) ? json_decode(file_get_contents($regenciesJson), true) : [];
                                    
                                    $foundMatch = false;
                                    foreach($provinces as $p) {
                                        if (strcasecmp($currentDomisili, $p) === 0 || strcasecmp("Provinsi " . $currentDomisili, $p) === 0) {
                                            $foundMatch = true; break;
                                        }
                                    }
                                    if (!$foundMatch && !empty($regencies)) {
                                        foreach($regencies as $r) {
                                            if (strcasecmp($currentDomisili, $r) === 0) {
                                                $foundMatch = true; break;
                                            }
                                        }
                                    }
                                @endphp
                                <select name="domisili" id="domisiliSelect" class="form-control select2-basic" required style="width: 100%;">
                                    <option value="">-- Pilih Domisili --</option>
                                    @if(!empty($currentDomisili) && !$foundMatch)
                                        <option value="{{ $currentDomisili }}" selected>{{ $currentDomisili }}</option>
                                    @endif
                                    <optgroup label="── TINGKAT PROVINSI (PPKT) ──">
                                        @foreach($provinces as $prov)
                                            @php
                                                $isSelected = strcasecmp($currentDomisili, $prov) === 0 || strcasecmp("Provinsi " . $currentDomisili, $prov) === 0;
                                            @endphp
                                            <option value="{{ $prov }}" {{ $isSelected ? 'selected' : '' }}>Provinsi {{ $prov }}</option>
                                        @endforeach
                                    </optgroup>
                                    @if(!empty($regencies))
                                        <optgroup label="── TINGKAT KABUPATEN / KOTA (PKKT) ──">
                                            @foreach($regencies as $reg)
                                                @php
                                                    $isSelected = strcasecmp($currentDomisili, $reg) === 0;
                                                @endphp
                                                <option value="{{ $reg }}" {{ $isSelected ? 'selected' : '' }}>{{ $reg }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="pendidikan_terakhir">Pendidikan Terakhir <span style="color:red;">*</span></label>
                                <input type="text" name="pendidikan_terakhir" id="pendidikan_terakhir" class="form-control" value="{{ old('pendidikan_terakhir', $anggota->pendidikan_terakhir) }}" placeholder="Contoh: S1 Teknik Informatika" required>
                            </div>

                            <div class="form-group">
                                <label for="pekerjaan">Pekerjaan <span style="color:red;">*</span></label>
                                <input type="text" name="pekerjaan" id="pekerjaan" class="form-control" value="{{ old('pekerjaan', $anggota->pekerjaan) }}" required>
                            </div>

                            <div class="form-group" style="grid-column: span 2;">
                                <label for="riwayat_organisasi">Riwayat Organisasi <span style="color:red;">*</span></label>
                                <textarea name="riwayat_organisasi" id="riwayat_organisasi" class="form-control" rows="2" required>{{ old('riwayat_organisasi', $anggota->riwayat_organisasi) }}</textarea>
                            </div>

                            <div class="form-group" style="grid-column: span 2;">
                                <label for="kompetensi">Kompetensi / Keahlian <span style="color:red;">*</span></label>
                                <textarea name="kompetensi" id="kompetensi" class="form-control" rows="2" placeholder="Contoh: Desain Grafis, Manajemen Proyek" required>{{ old('kompetensi', $anggota->kompetensi) }}</textarea>
                            </div>

                            <div class="form-section-divider">3. Kontak & Sosial Media</div>

                            <div class="form-group">
                                <label for="no_hp">Nomor HP / WhatsApp <span style="color:red;">*</span></label>
                                <input type="text" name="no_hp" id="no_hp" class="form-control" value="{{ old('no_hp', $anggota->no_hp ?? $anggota->no_telp) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="email">Email <span style="color:red;">*</span></label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $anggota->email) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="instagram">Instagram (Opsional)</label>
                                <input type="text" name="instagram" id="instagram" class="form-control" value="{{ old('instagram', $anggota->instagram) }}" placeholder="Username tanpa @">
                            </div>

                            <div class="form-group">
                                <label for="tiktok">TikTok (Opsional)</label>
                                <input type="text" name="tiktok" id="tiktok" class="form-control" value="{{ old('tiktok', $anggota->tiktok) }}" placeholder="Username tanpa @">
                            </div>

                            <div class="form-group">
                                <label for="twitter">X / Twitter (Opsional)</label>
                                <input type="text" name="twitter" id="twitter" class="form-control" value="{{ old('twitter', $anggota->twitter) }}" placeholder="Username tanpa @">
                            </div>
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i>
                            {{ $anggota->status === 'pending_profile' ? 'Simpan & Ajukan Verifikasi' : 'Simpan Perubahan Profil' }}
                        </button>
                    </form>
                </div>

                <!-- ==============================================
                TAB: KEAMANAN (CHANGE PASSWORD)
                ============================================== -->
                <div id="keamanan" class="content-section">
                    <h2 class="section-title"><i class="fas fa-lock"></i> Pengaturan Keamanan Akun</h2>

                    <p style="color: var(--text-grey); font-size: 0.95rem; margin-bottom: 25px;">
                        Gunakan formulir di bawah ini jika Anda ingin memperbarui password login akun anggota Anda.
                    </p>

                    <form action="{{ route('profile-anggota.change-password') }}" method="POST" style="max-width: 500px;">
                        @csrf

                        <div class="form-group" style="margin-bottom: 18px;">
                            <label for="current_password">Password Saat Ini / Sementara <span style="color:red;">*</span></label>
                            <div style="position: relative;">
                                <input type="password" name="current_password" id="current_password" class="form-control" required placeholder="Masukkan password saat ini">
                                <i class="fas fa-eye" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #94a3b8; padding: 5px;" onclick="togglePasswordVisibility('current_password', this)" title="Tampilkan/Sembunyikan"></i>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 18px;">
                            <label for="new_password">Password Baru <span style="color:red;">*</span></label>
                            <div style="position: relative;">
                                <input type="password" name="new_password" id="new_password" class="form-control" required placeholder="Minimal 8 karakter">
                                <i class="fas fa-eye" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #94a3b8; padding: 5px;" onclick="togglePasswordVisibility('new_password', this)" title="Tampilkan/Sembunyikan"></i>
                            </div>
                            <div id="password-strength-container" style="margin-top: 8px; display: none;">
                                <div style="height: 5px; background: #e2e8f0; border-radius: 3px; overflow: hidden; margin-bottom: 4px;">
                                    <div id="password-strength-bar" style="height: 100%; width: 0%; transition: all 0.3s ease;"></div>
                                </div>
                                <div style="font-size: 0.75rem; text-align: right; font-weight: 600;" id="password-strength-text"></div>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 25px;">
                            <label for="new_password_confirmation">Konfirmasi Password Baru <span style="color:red;">*</span></label>
                            <div style="position: relative;">
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required placeholder="Ulangi password baru">
                                <i class="fas fa-eye" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #94a3b8; padding: 5px;" onclick="togglePasswordVisibility('new_password_confirmation', this)" title="Tampilkan/Sembunyikan"></i>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="fas fa-key"></i> Perbarui Password
                        </button>
                    </form>
                </div>

            </main>
        </div>
    </div>

    <!-- Modal Cropper (file-crop only) -->
    <div id="cropperModal" class="custom-crop-modal">
        <div class="custom-crop-modal-content">
            <div class="custom-crop-modal-header">
                <h3 class="custom-crop-modal-title"><i class="fas fa-crop-alt"></i> Sesuaikan Foto Profil</h3>
                <button type="button" class="custom-crop-modal-close" id="btnCancelCrop" title="Tutup">&times;</button>
            </div>
            <div class="img-container">
                <img id="image_to_crop" src="" alt="Foto">
            </div>
            <div class="custom-crop-modal-footer">
                <button type="button" class="btn-cancel" id="btnCancelCropFooter">Batal</button>
                <button type="button" class="btn-crop-save" id="btnCrop">
                    <i class="fas fa-check"></i> Crop & Simpan
                </button>
            </div>
        </div>
    </div>

    <!-- Floating Camera Popup (draggable, PiP style) -->
    <div class="floating-camera" id="floatingCamera">
        <div class="floating-camera-header" id="floatingCameraDragHandle">
            <div class="floating-camera-title"><span class="rec-dot"></span> Kamera</div>
            <button type="button" class="floating-camera-close" id="btnCloseFloatingCam">&times;</button>
        </div>
        <div class="floating-camera-video-wrap">
            <video id="floatingCamVideo" autoplay playsinline muted></video>
        </div>
        <div class="floating-camera-actions">
            <button type="button" class="floating-shutter" id="btnFloatingShutter" title="Ambil Foto"></button>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function switchTab(tabId, element) {
            // Hide all sections
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.remove('active');
            });

            // Deactivate all sidebar buttons
            document.querySelectorAll('.sidebar-menu-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab
            const targetSection = document.getElementById(tabId);
            if (targetSection) {
                targetSection.classList.add('active');
            }

            // Set active class to the clicked button
            if (element) {
                element.classList.add('active');
            }
        }

        function printKTA() {
            const cardHtml = document.getElementById('ktaCardToPrint').outerHTML;

            // Get original page styles
            let stylesHtml = '';
            document.querySelectorAll('style, link[rel="stylesheet"]').forEach(el => {
                stylesHtml += el.outerHTML;
            });
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Cetak KTA - Karang Taruna</title>
                        ${stylesHtml}
                        <style>
                            body { margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; height: 100vh; background: #ffffff; }
                            .kta-card { box-shadow: none !important; transform: scale(1.2); }
                            @media print { body { -webkit-print-color-adjust: exact; print-color-adjust: exact; } }
                        </style>
                    </head>
                    <body>
                        ${cardHtml}
                        <script>
                            window.onload = function() {
                                setTimeout(function() { window.print(); window.close(); }, 500);
                            };
                        <\/script>
                    </body>
                </html>
            `);
            printWindow.document.close();
        }
    </script>

    <!-- Dependencies (Local Assets for Maximum Speed) -->
    <script src="{{ asset('vendor/jquery/jquery-3.7.1.min.js') }}"></script>
    <link href="{{ asset('vendor/select2/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('vendor/select2/select2.min.js') }}"></script>
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('vendor/qrcode/qrcode.min.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    {{-- SIKTN Benchmark: Select2 init langsung tanpa wrapper jQuery ready --}}
    <script>
        // Inisialisasi Select2 langsung setelah script di-load
        window.addEventListener('load', function() {
            if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
                jQuery('.select2-basic').select2({
                    placeholder: '-- Pilih --',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: jQuery('body')
                });
            }
        });
    </script>



    <script>
        (function() {
            ['cropperModal', 'floatingCamera'].forEach(id => {
                const el = document.getElementById(id);
                if (el && el.parentElement !== document.body) document.body.appendChild(el);
            });
        })();

        document.addEventListener('DOMContentLoaded', function() {
            // Handle URL hash routing on page load
            const hash = window.location.hash.substring(1);
            if (hash) {
                const tabBtn = document.querySelector(`.sidebar-menu-btn[onclick*="${hash}"]`);
                if (tabBtn) {
                    switchTab(hash, tabBtn);
                }
            }

            // SIKTN Admin UI Benchmark: Floating Action Dropdown Trigger
            document.addEventListener('click', function(e) {
                const trigger = e.target.closest('.btn-aksi-trigger');
                if (trigger) {
                    e.stopPropagation();
                    const targetId = trigger.getAttribute('data-target');
                    const dropdown = document.getElementById(targetId);
                    
                    // Close all open dropdowns
                    document.querySelectorAll('.aksi-dropdown.is-open').forEach(d => {
                        if (d !== dropdown) d.classList.remove('is-open');
                    });
                    
                    if (dropdown) {
                        if (dropdown.classList.contains('is-open')) {
                            dropdown.classList.remove('is-open');
                        } else {
                            const rect = trigger.getBoundingClientRect();
                            dropdown.style.top = (rect.bottom + window.scrollY + 4) + 'px';
                            dropdown.style.left = (rect.right + window.scrollX - 180) + 'px';
                            dropdown.classList.add('is-open');
                        }
                    }
                } else if (!e.target.closest('.aksi-dropdown')) {
                    document.querySelectorAll('.aksi-dropdown.is-open').forEach(d => d.classList.remove('is-open'));
                }
            });

            let cropper;
            let camStream = null;

            const image        = document.getElementById('image_to_crop');
            const fileInput    = document.getElementById('foto_diri');
            const cropperModal = document.getElementById('cropperModal');
            const floatingCam  = document.getElementById('floatingCamera');
            const camVideo     = document.getElementById('floatingCamVideo');

            function closeCropperModal(clearFile = true) {
                cropperModal.classList.remove('active');
                if (cropper) { cropper.destroy(); cropper = null; }
                if (clearFile === true) fileInput.value = '';
            }

            function openCropperWithSrc(src) {
                cropperModal.classList.add('active');
                if (cropper) cropper.destroy();
                // Tunggu image load sebelum init Cropper agar dimensinya akurat
                image.onload = function() {
                    cropper = new Cropper(image, {
                        aspectRatio: NaN, // Bebas ukuran crop (custom ratio)
                        viewMode: 1,
                        autoCropArea: 0.9, guides: true,
                        movable: true, zoomable: true, background: false,
                    });
                };
                image.src = src;
            }

            fileInput.addEventListener('change', function(e) {
                if (e.target.files && e.target.files.length > 0) {
                    const reader = new FileReader();
                    reader.onload = evt => openCropperWithSrc(evt.target.result);
                    reader.readAsDataURL(e.target.files[0]);
                }
            });

            document.getElementById('btnCancelCrop').addEventListener('click', function() { closeCropperModal(true); });
            document.getElementById('btnCancelCropFooter').addEventListener('click', function() { closeCropperModal(true); });

            // Helper function to convert base64 to File object
            function dataURLtoFile(dataurl, filename) {
                let arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
                    bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
                while(n--){
                    u8arr[n] = bstr.charCodeAt(n);
                }
                return new File([u8arr], filename, {type:mime});
            }

            // Restore foto dari localStorage jika ada (Berguna saat halaman kereload karena validasi error)
            const savedPhoto = localStorage.getItem('temp_cropped_photo_{{ $anggota->id }}');
            if (savedPhoto) {
                // Gunakan hidden input sebagai backup yang dijamin work di semua browser
                document.getElementById('foto_diri_base64').value = savedPhoto;
                
                try {
                    const file = dataURLtoFile(savedPhoto, 'cropped_profile.jpg');
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    fileInput.files = dt.files;
                } catch (e) { console.warn("Browser tidak support DataTransfer"); }

                const prev = document.getElementById('preview_image');
                const wrap = document.querySelector('.foto-avatar-preview');
                if (prev) { prev.src = savedPhoto; }
                else if (wrap) { wrap.innerHTML = '<img id="preview_image" src="' + savedPhoto + '" alt="Preview">'; }
            }

            document.getElementById('btnCrop').addEventListener('click', function() {
                if (!cropper) return;
                const btn = this;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                btn.disabled = true;
                
                // Simpan base64 string untuk preview & restore
                const base64data = cropper.getCroppedCanvas({ width: 600, height: 600 }).toDataURL('image/jpeg', 0.92);
                
                cropper.getCroppedCanvas({ width: 600, height: 600 }).toBlob(function(blob) {
                    // Simpan ke hidden input
                    document.getElementById('foto_diri_base64').value = base64data;
                    
                    try {
                        const file = new File([blob], 'cropped_profile.jpg', { type: 'image/jpeg' });
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        fileInput.files = dt.files;
                    } catch (e) { console.warn("Browser tidak support DataTransfer"); }
                    
                    // Simpan ke localStorage agar tidak hilang saat reload validasi
                    localStorage.setItem('temp_cropped_photo_{{ $anggota->id }}', base64data);

                    const prev = document.getElementById('preview_image');
                    const wrap = document.querySelector('.foto-avatar-preview');
                    if (prev) { prev.src = base64data; }
                    else if (wrap) { wrap.innerHTML = '<img id="preview_image" src="' + base64data + '" alt="Preview">'; }
                    closeCropperModal(false); // Jangan clear file input karena ini save success!
                    btn.innerHTML = '<i class="fas fa-check"></i> Crop & Simpan';
                    btn.disabled = false;
                    
                    // Notifikasi sukses setelah crop (Toast Notification Custom)
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Foto profil berhasil disiapkan!',
                        showConfirmButton: false,
                        timer: 3500,
                        timerProgressBar: true,
                        customClass: {
                            popup: 'custom-toast'
                        }
                    });
                }, 'image/jpeg', 0.92);
            });

            function stopCam() {
                if (camStream) { camStream.getTracks().forEach(t => t.stop()); camStream = null; }
            }
            function closeFloatingCam() {
                floatingCam.classList.remove('active');
                stopCam();
            }

            document.getElementById('btnOpenCamera').addEventListener('click', function() {
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Akses Kamera Tidak Didukung',
                        text: 'Kamera tidak dapat diakses di browser atau koneksi HTTP Anda. Silakan gunakan opsi "Pilih dari File".',
                        confirmButtonColor: '#022648'
                    });
                    return;
                }

                floatingCam.classList.add('active');
                navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'user', width: { ideal: 720 }, height: { ideal: 720 } },
                    audio: false
                }).then(stream => {
                    camStream = stream;
                    camVideo.srcObject = stream;
                }).catch(err => {
                    closeFloatingCam();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tidak Dapat Mengakses Kamera',
                        text: 'Silakan izinkan akses kamera di browser Anda atau gunakan tombol "Pilih dari File". (Error: ' + err.message + ')',
                        confirmButtonColor: '#022648'
                    });
                });
            });

            document.getElementById('btnCloseFloatingCam').addEventListener('click', closeFloatingCam);

            document.getElementById('btnFloatingShutter').addEventListener('click', function() {
                if (!camStream) return;
                
                // Efek Flash Kamera (Jepretan)
                const flash = document.createElement('div');
                flash.style.position = 'fixed';
                flash.style.top = '0'; flash.style.left = '0'; flash.style.width = '100vw'; flash.style.height = '100vh';
                flash.style.backgroundColor = '#ffffff'; flash.style.zIndex = '999999'; flash.style.opacity = '1';
                flash.style.transition = 'opacity 0.6s ease-out';
                document.body.appendChild(flash);
                setTimeout(() => { flash.style.opacity = '0'; setTimeout(() => flash.remove(), 600); }, 50);

                // Ambil ukuran RAW video tanpa crop rasio (biar nggak gepeng)
                const canvas = document.createElement('canvas');
                canvas.width = camVideo.videoWidth;
                canvas.height = camVideo.videoHeight;
                const ctx = canvas.getContext('2d');
                
                // Flip horizontal
                ctx.translate(canvas.width, 0);
                ctx.scale(-1, 1);
                
                // Draw RAW frame
                ctx.drawImage(camVideo, 0, 0);
                
                const dataUrl = canvas.toDataURL('image/jpeg', 0.95);
                closeFloatingCam();
                openCropperWithSrc(dataUrl);
            });

            const handle = document.getElementById('floatingCameraDragHandle');
            let isDragging = false, dragOffX = 0, dragOffY = 0;
            handle.addEventListener('mousedown', function(e) {
                isDragging = true;
                const rect = floatingCam.getBoundingClientRect();
                dragOffX = e.clientX - rect.left;
                dragOffY = e.clientY - rect.top;
                e.preventDefault();
            });
            document.addEventListener('mousemove', function(e) {
                if (!isDragging) return;
                floatingCam.style.left = (e.clientX - dragOffX) + 'px';
                floatingCam.style.top = (e.clientY - dragOffY) + 'px';
                floatingCam.style.right = 'auto';
                floatingCam.style.bottom = 'auto';
            });
            document.addEventListener('mouseup', function() { isDragging = false; });
            handle.addEventListener('touchstart', function(e) {
                isDragging = true;
                const rect = floatingCam.getBoundingClientRect();
                const t = e.touches[0];
                dragOffX = t.clientX - rect.left;
                dragOffY = t.clientY - rect.top;
            }, { passive: true });
            document.addEventListener('touchmove', function(e) {
                if (!isDragging) return;
                const t = e.touches[0];
                floatingCam.style.left = (t.clientX - dragOffX) + 'px';
                floatingCam.style.top = (t.clientY - dragOffY) + 'px';
                floatingCam.style.right = 'auto';
                floatingCam.style.bottom = 'auto';
            }, { passive: true });
            document.addEventListener('touchend', function() { isDragging = false; });

            const profileForm = document.querySelector('form[action="{{ route('profile-anggota.update-profile') }}"]');
            if (profileForm) {
                const originalData = {
                    nik: {!! json_encode($anggota->nik ?? '') !!},
                    nama_lengkap: {!! json_encode($anggota->nama_lengkap ?? '') !!},
                    tempat_lahir: {!! json_encode($anggota->tempat_lahir ?? '') !!},
                    tanggal_lahir: {!! json_encode($anggota->tanggal_lahir ? $anggota->tanggal_lahir->format('Y-m-d') : '') !!},
                    domisili: {!! json_encode($anggota->domisili ?? '') !!},
                    alamat_lengkap: {!! json_encode($anggota->alamat_lengkap ?? $anggota->alamat_domisili ?? '') !!},
                    pendidikan_terakhir: {!! json_encode($anggota->pendidikan_terakhir ?? '') !!},
                    pekerjaan: {!! json_encode($anggota->pekerjaan ?? '') !!},
                    jabatan: {!! json_encode($anggota->jabatan ?? '') !!},
                    riwayat_organisasi: {!! json_encode($anggota->riwayat_organisasi ?? '') !!},
                    kompetensi: {!! json_encode($anggota->kompetensi ?? '') !!}
                };

                profileForm.addEventListener('submit', function(e) {
                    const hasBase64 = document.getElementById('foto_diri_base64').value.length > 0;
                    if (fileInput.getAttribute('data-required') === 'true' && fileInput.files.length === 0 && !hasBase64) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Foto Profil Wajib Diisi',
                            text: 'Silakan unggah atau ambil foto profil Anda terlebih dahulu sebelum menyimpan.',
                            confirmButtonColor: '#022648'
                        });
                        return;
                    }

                    @if($anggota->status === 'approved')
                    const formData = new FormData(profileForm);
                    let needsVerification = false;
                    
                    for (const [key, originalValue] of Object.entries(originalData)) {
                        let formValue = formData.get(key) || '';
                        if (formValue !== originalValue) {
                            needsVerification = true;
                            break;
                        }
                    }

                    if (needsVerification) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Yakin mau menyimpan?',
                            text: 'Perubahan pada Data Pribadi/Organisasi akan membuat status Anda kembali menjadi "Menunggu Verifikasi" dan KTA digital dinonaktifkan sementara.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#022648',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, Simpan Perubahan',
                            cancelButtonText: 'Batal',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                profileForm.submit();
                            }
                        });
                    }
                    @endif
                });
            }

            // Initialize Select2 for domisili (options already rendered server-side)
            $('#domisiliSelect').select2({
                placeholder: "-- Pilih Domisili --",
                allowClear: true,
                width: '100%'
            });

            // Initialize select2 for jabatan
            $('#jabatanSelect').select2({
                placeholder: "-- Pilih Jabatan --",
                width: '100%',
                minimumResultsForSearch: Infinity // Sembunyikan search bar karena option cuma sedikit
            });

        });

        // ==========================================
        // FITUR KEAMANAN PASSWORD (INTIP & KEKUATAN)
        // ==========================================
        function togglePasswordVisibility(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
                icon.style.color = '#022648'; // Warna aktif
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
                icon.style.color = '#94a3b8'; // Warna non-aktif
            }
        }

        const newPassInput = document.getElementById('new_password');
        if (newPassInput) {
            newPassInput.addEventListener('input', function() {
                const val = this.value;
                const container = document.getElementById('password-strength-container');
                const bar = document.getElementById('password-strength-bar');
                const text = document.getElementById('password-strength-text');
                
                if (val.length === 0) {
                    container.style.display = 'none';
                    return;
                }
                
                container.style.display = 'block';
                
                let score = 0;
                if (val.length >= 8) score++;
                if (/[A-Z]/.test(val)) score++;
                if (/[0-9]/.test(val)) score++;
                if (/[^A-Za-z0-9]/.test(val)) score++;
                
                let color = '';
                let label = '';
                let width = '';
                
                if (val.length < 8 || score <= 1) {
                    color = '#ef4444'; // Merah
                    label = 'Lemah (Minimal 8 Karakter)';
                    width = '33%';
                } else if (score === 2 || score === 3) {
                    color = '#f59e0b'; // Kuning
                    label = 'Sedang';
                    width = '66%';
                } else {
                    color = '#10b981'; // Hijau
                    label = 'Kuat & Aman';
                    width = '100%';
                }
                
                bar.style.width = width;
                bar.style.backgroundColor = color;
                text.textContent = label;
                text.style.color = color;
            });
        function closeProfileForceModal() {
            const modal = document.getElementById('profileForceModal');
            if (modal) {
                modal.classList.remove('active');
                modal.style.setProperty('display', 'none', 'important');
            }
        }
    </script>

    <style>
        /* Custom Profile Modal */
        .custom-profile-modal {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            align-items: center; justify-content: center;
            z-index: 9999;
            animation: fadeIn 0.3s;
        }
        .custom-profile-modal.active { display: flex; }
        .custom-profile-modal-content {
            background: white; border-radius: 12px; padding: 30px; max-width: 400px; width: 90%; text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            animation: slideUp 0.3s;
        }
        .custom-profile-modal-icon {
            width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;
            background: #fef3c7; color: #d97706;
        }
        .custom-profile-modal-title { font-size: 1.25rem; font-weight: 700; color: #022648; margin: 0 0 10px; font-family: 'Google Sans', sans-serif;}
        .custom-profile-modal-text { font-size: 0.9rem; color: #6b7280; margin-bottom: 25px; line-height: 1.6; }
        .custom-modal-btn { border: none; padding: 12px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; color: white; background: #022648; width: 100%; transition: all 0.2s;}
        .custom-modal-btn:hover { background: #18227C; }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    </style>

    @if(in_array($anggota->status, ['pending', 'pending_profile']))
    <div class="custom-profile-modal active" id="profileForceModal" onclick="if(event.target === this) closeProfileForceModal()">
        <div class="custom-profile-modal-content" onclick="event.stopPropagation()">
            <div class="custom-profile-modal-icon">
                <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="custom-profile-modal-title">Lengkapi Profil Anda</h3>
            <p class="custom-profile-modal-text">Halo <strong>{{ $anggota->nama_lengkap }}</strong>, silakan lengkapi biodata Anda terlebih dahulu. Fitur KTA Digital dan Katalog akan terbuka setelah data divalidasi oleh Sekretariat Nasional (PNKT).</p>
            <button type="button" class="custom-modal-btn" onclick="closeProfileForceModal()">Baik, Mengerti</button>
        </div>
    </div>
    @endif
@endpush