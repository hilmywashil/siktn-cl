@extends('layouts.app')

@section('title', 'Struktur Organisasi')

@section('hero-background', asset('assets-front/images/about_image.jpg'))
@section('page-title', 'STRUKTUR ORGANISASI SIKTN')
@section('page-description', 'Mengenal jajaran kepengurusan dan struktur organisasi SIKTN secara transparan dan profesional.')
@section('hero-buttons', 'hide')

@section('content')
    @include('layouts.components.hero')
    <style>
        .public-tree-wrapper {
            position: relative;
            width: 100%;
            overflow: hidden;
            background: #ffffff;
            padding: 2rem 1rem 4rem 1rem;
            min-height: 500px;
            display: flex;
            justify-content: center;
        }

        .public-tree-container {
            transform-origin: top center;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            display: inline-block;
            margin: 0 auto;
            text-align: center;
        }

        .public-tree {
            text-align: center;
            display: inline-block;
            margin: 0 auto;
            width: max-content;
            min-width: max-content;
            padding: 1rem 2rem 3rem 2rem;
        }
        .public-tree ul {
            padding-top: 30px;
            position: relative;
            transition: all 0.5s;
            padding-left: 0;
            display: flex;
            justify-content: center;
            margin: 0;
        }
        .public-tree li {
            text-align: center;
            list-style-type: none;
            position: relative;
            padding: 30px 15px 0 15px;
            transition: all 0.5s;
        }
        /* Garis horizontal atas */
        .public-tree li::before, .public-tree li::after {
            content: '';
            position: absolute; top: 0; right: 50%;
            border-top: 3px solid #022648;
            width: 50%; height: 30px;
        }
        .public-tree li::after {
            right: auto; left: 50%;
            border-left: 3px solid #022648;
        }
        .public-tree li:only-child::after, .public-tree li:only-child::before {
            display: none;
        }
        .public-tree li:only-child { padding-top: 0; }
        .public-tree li:first-child::before, .public-tree li:last-child::after {
            border: 0 none;
        }
        .public-tree li:last-child::before {
            border-right: 3px solid #022648;
            border-radius: 0 8px 0 0;
        }
        .public-tree li:first-child::after {
            border-radius: 8px 0 0 0;
        }
        /* Garis vertikal ke anak */
        .public-tree ul ul::before {
            content: '';
            position: absolute; top: 0; left: 50%;
            border-left: 3px solid #022648;
            width: 0; height: 30px;
            transform: translateX(-50%);
        }
        .card { transition: all 0.3s; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px rgba(0,0,0,0.1) !important; }

        /* Floating Zoom Control Bar */
        .zoom-controls {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 999;
            background: #022648;
            border-radius: 50px;
            padding: 6px 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 10px 25px rgba(2, 38, 72, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
        }

        .zoom-btn {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .zoom-btn:hover {
            background: #b7830f;
            transform: scale(1.1);
        }

        .zoom-btn-text {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .zoom-btn-text:hover {
            background: #b7830f;
        }

        .zoom-indicator {
            color: white;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 0 8px;
            font-family: monospace;
        }
    </style>

    <style>
        .custom-periode-dropdown {
            position: relative;
            display: inline-block;
        }
        .custom-dropdown-trigger {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 0.45rem 0.95rem;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.825rem;
            font-weight: 700;
            color: #022648;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(2, 38, 72, 0.05);
        }
        .custom-dropdown-trigger:hover {
            border-color: #022648;
            box-shadow: 0 4px 12px rgba(2, 38, 72, 0.1);
        }
        .custom-dropdown-menu {
            position: absolute;
            right: 0;
            top: calc(100% + 6px);
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            box-shadow: 0 12px 30px rgba(2, 38, 72, 0.15);
            min-width: 250px;
            z-index: 9999;
            display: none;
            overflow: hidden;
            padding: 4px 0;
        }
        .custom-dropdown-menu.show {
            display: block;
            animation: select2DropdownFadeIn 0.15s ease-out;
        }
        .custom-dropdown-item {
            display: block;
            padding: 0.65rem 1rem;
            color: #022648;
            text-decoration: none !important;
            font-family: 'Montserrat', sans-serif;
            transition: all 0.15s ease;
            border-bottom: 1px solid #f1f5f9;
        }
        .custom-dropdown-item:last-child {
            border-bottom: none;
        }
        .custom-dropdown-item:hover {
            background: #f8fafc;
            color: #022648;
            padding-left: 1.15rem;
        }
        .custom-dropdown-item.selected {
            background: #022648 !important;
            color: #ffffff !important;
        }

        @keyframes select2DropdownFadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <section class="wrapper-white-1">
        <div class="organisasi-section" data-aos="fade-up" style="width: 100%;">
            {{-- Period Filter Header Bar --}}
            <div style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 1rem 2rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; width: 100%;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 38px; height: 38px; border-radius: 6px; background: #022648; display: flex; align-items: center; justify-content: center; color: #ffffff; flex-shrink: 0;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    </div>
                    <div>
                        <div style="font-size: 0.7rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Masa Bakti Kepengurusan</div>
                        <div style="font-size: 1.05rem; font-weight: 800; color: #022648;">
                            {{ $selectedPeriode ? $selectedPeriode->nama_periode : 'Periode Kepengurusan' }}
                            @if($selectedPeriode && $selectedPeriode->tahun_mulai)
                                <span style="font-size: 0.85rem; color: #64748b; font-weight: 600; margin-left: 4px;">({{ $selectedPeriode->tahun_mulai }} – {{ $selectedPeriode->tahun_selesai }})</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if(isset($allPeriodes) && $allPeriodes->count() > 0)
                <div style="display: flex; align-items: center; gap: 0.6rem;">
                    <span style="font-size: 0.825rem; font-weight: 700; color: #022648; white-space: nowrap;">Pilih Periode:</span>
                    <div class="custom-periode-dropdown">
                        <button type="button" class="custom-dropdown-trigger" onclick="togglePeriodeMenu(event)">
                            <span>{{ $selectedPeriode ? $selectedPeriode->nama_periode . ' (' . $selectedPeriode->tahun_mulai . ' – ' . $selectedPeriode->tahun_selesai . ')' : 'Pilih Periode' }}</span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </button>
                        <div class="custom-dropdown-menu" id="customPeriodeMenu">
                            @foreach($allPeriodes as $per)
                                <a href="{{ route('organisasi', ['periode_id' => $per->id]) }}" class="custom-dropdown-item {{ $selectedPeriode && $selectedPeriode->id == $per->id ? 'selected' : '' }}">
                                    <div style="font-weight: 700; font-size: 0.85rem;">{{ $per->nama_periode }}</div>
                                    <div style="font-size: 0.75rem; opacity: 0.85; font-weight: 600;">{{ $per->tahun_mulai }} – {{ $per->tahun_selesai }} {{ $per->is_aktif ? '• (Aktif Saat Ini)' : '' }}</div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            @if(isset($organisasiTree) && $organisasiTree->count() > 0)
                <div class="public-tree-wrapper" id="publicTreeWrapper">
                    <div class="public-tree-container" id="publicTreeContainer">
                        <div class="public-tree" id="publicTree">
                            <ul>
                                @foreach($organisasiTree as $root)
                                    @include('pages.partials.org-tree-node', ['node' => $root])
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Floating Zoom Toolbar -->
                <div class="zoom-controls">
                    <button class="zoom-btn" onclick="zoomIn()" title="Perbesar Tampilan">+</button>
                    <span class="zoom-indicator" id="zoomIndicator">100%</span>
                    <button class="zoom-btn" onclick="zoomOut()" title="Perkecil Tampilan">-</button>
                    <button class="zoom-btn-text" onclick="autoFitTree()" title="Paskan dengan Layar">Auto-Fit</button>
                    <button class="zoom-btn-text" onclick="resetZoom()" title="Kembali ke 100%">Reset</button>
                </div>
            @else
                <div style="text-align: center; padding: 50px 0; color: #6b7280;">
                    <p>Belum ada data struktur organisasi yang aktif.</p>
                </div>
            @endif
        </div>
    </section>

    <script>
        let currentScale = 1;
        let autoScaleValue = 1;

        function applyScale(scale) {
            currentScale = scale;
            const container = document.getElementById('publicTreeContainer');
            const wrapper = document.getElementById('publicTreeWrapper');
            const tree = document.getElementById('publicTree');
            const indicator = document.getElementById('zoomIndicator');

            if (!container || !wrapper || !tree) return;

            container.style.transform = `scale(${scale})`;
            indicator.innerText = Math.round(scale * 100) + '%';

            // Adjust wrapper height so there is no awkward gap after zooming out
            const unscaledHeight = tree.offsetHeight;
            const scaledHeight = unscaledHeight * scale;
            wrapper.style.height = (scaledHeight + 80) + 'px';
        }

        function autoFitTree() {
            const wrapper = document.getElementById('publicTreeWrapper');
            const tree = document.getElementById('publicTree');

            if (!wrapper || !tree) return;

            // Reset scaling temporarily to measure exact natural width
            const container = document.getElementById('publicTreeContainer');
            container.style.transform = 'scale(1)';

            const wrapperWidth = wrapper.clientWidth - 40; // padding offset
            const treeWidth = tree.scrollWidth || tree.offsetWidth;

            if (treeWidth > 0 && wrapperWidth > 0) {
                let calculatedScale = wrapperWidth / treeWidth;
                // Cap max auto-scale at 100% so normal trees don't get oversized
                if (calculatedScale > 1) calculatedScale = 1;
                // Cap min auto-scale at 25% for extreme layouts
                if (calculatedScale < 0.25) calculatedScale = 0.25;

                autoScaleValue = calculatedScale;
                applyScale(calculatedScale);
            }
        }

        function zoomIn() {
            let nextScale = currentScale + 0.1;
            if (nextScale > 1.8) nextScale = 1.8;
            applyScale(nextScale);
        }

        function zoomOut() {
            let nextScale = currentScale - 0.1;
            if (nextScale < 0.2) nextScale = 0.2;
            applyScale(nextScale);
        }

        function resetZoom() {
            applyScale(1);
        }

        function togglePeriodeMenu(event) {
            event.stopPropagation();
            const menu = document.getElementById('customPeriodeMenu');
            if (menu) {
                menu.classList.toggle('show');
            }
        }

        document.addEventListener('click', function(e) {
            const menu = document.getElementById('customPeriodeMenu');
            if (menu && !e.target.closest('.custom-periode-dropdown')) {
                menu.classList.remove('show');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(autoFitTree, 300);
            window.addEventListener('resize', function() {
                autoFitTree();
            });
        });

        window.addEventListener('load', function() {
            setTimeout(autoFitTree, 400);
        });
    </script>
@endsection