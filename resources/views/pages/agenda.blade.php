@extends ('layouts.app')

@section('title', 'Agenda Kegiatan - Karang Taruna')

@section('hero-background', asset('assets-front/images/hero_bg.jpg'))
@section('page-title', 'AGENDA KEGIATAN PNKT')
@section('page-description', 'Jadwal & Agenda Resmi Pengurus Nasional Karang Taruna.')
@section('hero-buttons', 'hide')

@php
    // Dummy Data untuk render layout sementara
    $dummy_data = collect([
        (object)[
            'slug' => 'rapat-kerja-nasional',
            'gambar' => 'dummy', 
            'bidang_name' => 'Organisasi',
            'is_populer' => true,
            'judul' => 'Rapat Kerja Nasional Karang Taruna',
            'tanggal_publish' => now()->addDays(10),
            'konten' => 'Pembahasan program kerja strategis Pengurus Nasional Karang Taruna (PNKT) untuk periode mendatang. Kegiatan ini akan dihadiri oleh seluruh pengurus tingkat nasional.'
        ],
        (object)[
            'slug' => 'temu-karya',
            'gambar' => 'dummy',
            'bidang_name' => 'Kaderisasi',
            'is_populer' => false,
            'judul' => 'Temu Karya Karang Taruna',
            'tanggal_publish' => now()->addDays(20),
            'konten' => 'Kegiatan Temu Karya untuk mengevaluasi program kerja serta memilih kepengurusan baru di tingkat wilayah.'
        ],
        (object)[
            'slug' => 'bakti-sosial',
            'gambar' => 'dummy',
            'bidang_name' => 'Sosial',
            'is_populer' => true,
            'judul' => 'Bakti Sosial & Penghijauan',
            'tanggal_publish' => now()->addDays(30),
            'konten' => 'Kegiatan sosial dan penanaman pohon serentak di berbagai wilayah binaan Karang Taruna sebagai bentuk kepedulian lingkungan.'
        ],
        (object)[
            'slug' => 'webinar-kewirausahaan',
            'gambar' => 'dummy',
            'bidang_name' => 'Ekonomi',
            'is_populer' => false,
            'judul' => 'Webinar Kewirausahaan Pemuda',
            'tanggal_publish' => now()->addDays(40),
            'konten' => 'Pelatihan online untuk meningkatkan kapasitas kewirausahaan pemuda di tingkat nasional.'
        ]
    ]);
    $kegiatan = new \Illuminate\Pagination\LengthAwarePaginator($dummy_data, 4, 10);
@endphp

@section('content')
    @include('layouts.components.hero')

    <section class="search-katalog" style="margin-top: 60px;">
        <!-- Search Box - UPDATED -->
        <div class="search-wrapper">
            <form action="{{ route('agenda.public') }}" method="GET" class="search-box-enhanced">
                <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                <input type="text" name="search" placeholder="Cari kegiatan berdasarkan judul..."
                    value="{{ request('search') }}" class="search-input">
                <button type="submit" class="search-button">
                    <i class="fa fa-search"></i>
                </button>
            </form>
        </div>

        <!-- Filter Section - UPDATED -->
        <div class="filter-section">
            <form action="{{ route('agenda.public') }}" method="GET" class="filter-form">
                <input type="hidden" name="search" value="{{ request('search') }}">

                <div class="filter-grid">
                    <!-- Filter Bidang -->
                    <div class="filter-item">
                        <label class="filter-label">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            Bidang
                        </label>
                        <select name="bidang" class="filter-select">
                            <option value="">Semua Bidang</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="bidang_{{ $i }}" {{ request('bidang') == "bidang_$i" ? 'selected' : '' }}>
                                    Bidang {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <!-- Filter Tanggal -->
                    <div class="filter-item">
                        <label class="filter-label">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            Tanggal
                        </label>
                        <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="filter-date">
                    </div>

                    <!-- Reset Button -->
                    @if(request('bidang') || request('tanggal') || request('search'))
                        <div class="filter-item filter-reset">
                            <a href="{{ route('agenda.public') }}" class="reset-btn">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                                    <path d="M21 3v5h-5"></path>
                                    <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                                    <path d="M3 21v-5h5"></path>
                                </svg>
                                Reset Filter
                            </a>
                        </div>
                    @endif
                </div>
            </form>
        </div>

        <script>
            // Auto submit saat bidang atau tanggal dipilih
            const filterForm = document.querySelector('.filter-form');
            const bidangSelect = filterForm?.querySelector('select[name="bidang"]');
            const tanggalInput = filterForm?.querySelector('input[name="tanggal"]');

            if (bidangSelect) {
                bidangSelect.addEventListener('change', function () {
                    filterForm.submit();
                });
            }

            if (tanggalInput) {
                tanggalInput.addEventListener('change', function () {
                    filterForm.submit();
                });
            }
        </script>
    </section>

    <section class="informasi-kegiatan">
        <div class="informasi-kegiatan-cards">
            @forelse($kegiatan as $item)
                <div class="informasi-kegiatan-card">
                    <a href="{{ route('agenda.detail', $item->slug) }}" class="informasi-kegiatan-card-image"
                        style="position: relative;">
                        <img src="{{ asset('assets-front/images/hero_bg.jpg') }}" alt="{{ $item->judul }}">
                        <div style="position: absolute; top: 0.75rem; right: 0.75rem; display: flex; gap: 0.5rem;">
                            <span
                                style="background: #0a2540; color: white; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                                {{ $item->bidang_name }}
                            </span>
                            @if($item->is_populer)
                                <span
                                    style="background: #ffd700; color: #0a2540; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                                    Populer
                                </span>
                            @endif
                        </div>
                    </a>
                    <div class="informasi-kegiatan-card-text">
                        <h3>{{ $item->judul }}</h3>
                        <p style="margin-bottom: 15px; font-size: 0.875rem; color: #6b7280;">
                            {{ $item->tanggal_publish->format('d F Y') }}</p>
                        <p style="color: #374151; line-height: 1.6;">{{ Str::limit(strip_tags($item->konten), 80, '...') }}</p>
                    </div>
                    <a href="{{ route('agenda.detail', $item->slug) }}" class="info-kegiatan-btn-more">Baca Selengkapnya</a>
                </div>
            @empty
                <div style="text-align: center; padding: 3rem; width: 100%; color: #6b7280;">
                    <p style="font-size: 1.125rem; font-weight: 600;">Belum ada kegiatan yang tersedia</p>
                    <p>Silakan cek kembali nanti</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($kegiatan->hasPages())
            <div style="display: flex; justify-content: center; margin-top: 2rem;">
                {{ $kegiatan->links() }}
            </div>
        @endif
    </section>

    <style>
        /* ========== UPDATED STYLES - SEARCH & FILTER ========== */

        .search-katalog {
            max-width: 1200px;
            margin: 0 auto 3rem;
            padding: 0 2rem;
        }

        /* Search Box Enhanced */
        .search-wrapper {
            margin-bottom: 1.5rem;
        }

        .search-box-enhanced {
            position: relative;
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(10, 37, 64, 0.12);
            display: flex;
            align-items: center;
            padding: 0.5rem;
            transition: all 0.3s ease;
        }

        .search-box-enhanced:focus-within {
            box-shadow: 0 12px 40px rgba(10, 37, 64, 0.18);
            transform: translateY(-2px);
        }

        .search-icon {
            position: absolute;
            left: 1.5rem;
            color: #9ca3af;
            transition: color 0.3s ease;
            pointer-events: none;
            z-index: 2;
        }

        .search-box-enhanced:focus-within .search-icon {
            color: #ffd700;
        }

        .search-input {
            flex: 1;
            border: none;
            outline: none;
            padding: 1rem 1rem 1rem 3.5rem;
            font-size: 1rem;
            color: #1f2937;
            font-family: 'Montserrat', sans-serif;
            background: transparent;
        }

        .search-input::placeholder {
            color: #9ca3af;
        }

        .search-button {
            background: linear-gradient(135deg, #0a2540 0%, #0d2f52 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9375rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(10, 37, 64, 0.3);
        }

        .search-button:hover {
            background: linear-gradient(135deg, #0d2f52 0%, #0a2540 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(10, 37, 64, 0.4);
        }

        /* Filter Section */
        .filter-section {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(10, 37, 64, 0.08);
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            align-items: end;
        }

        .filter-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .filter-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.25rem;
            font-family: 'Montserrat', sans-serif;
        }

        .filter-label svg {
            color: #ffd700;
        }

        .filter-select,
        .filter-date {
            width: 100%;
            padding: 0.875rem 1rem;
            border-radius: 10px;
            border: 2px solid #e5e7eb;
            background: #f9fafb;
            color: #1f2937;
            font-weight: 500;
            font-size: 0.9375rem;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='%230a2540' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-color: #f9fafb;
            padding-right: 2.5rem;
        }

        .filter-select:hover,
        .filter-date:hover {
            border-color: #ffd700;
            background: white;
        }

        .filter-select:focus,
        .filter-date:focus {
            outline: none;
            border-color: #0a2540;
            background: white;
            box-shadow: 0 0 0 3px rgba(10, 37, 64, 0.1);
        }

        .filter-date::-webkit-calendar-picker-indicator {
            cursor: pointer;
            opacity: 0.7;
        }

        .filter-reset {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .reset-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.875rem 1.5rem;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9375rem;
            font-family: 'Montserrat', sans-serif;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
            margin-top: 1.875rem;
        }

        .reset-btn:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.35);
        }

        .reset-btn svg {
            transition: transform 0.3s ease;
        }

        .reset-btn:hover svg {
            transform: rotate(180deg);
        }

        /* Responsive - Search & Filter */
        @media (max-width: 768px) {
            .search-katalog {
                padding: 0 1rem;
            }

            .search-box-enhanced {
                flex-direction: column;
                padding: 0.75rem;
                gap: 0.75rem;
            }

            .search-input {
                padding: 0.875rem 1rem;
            }

            .search-icon {
                display: none;
            }

            .search-button {
                width: 100%;
            }

            .filter-section {
                padding: 1.5rem;
            }

            .filter-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }

        /* ========== ORIGINAL STYLES - CARDS & OTHERS ========== */

        /* Base Grid Layout (Dipindah dari CSS lama) */
        .informasi-kegiatan-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto 4rem;
            padding: 0 2rem;
        }

        .informasi-kegiatan-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .informasi-kegiatan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .info-kegiatan-btn-more {
            display: inline-block;
            margin: 0 1.25rem 1.25rem;
            color: #0a2540;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s ease;
            margin-top: auto;
        }

        .info-kegiatan-btn-more:hover {
            color: #ffd700;
        }

        /* Fix thumbnail landscape aspect ratio */
        .informasi-kegiatan-card-image {
            width: 100%;
            aspect-ratio: 16 / 9;
            overflow: hidden;
            border-radius: 12px 12px 0 0;
            display: block;
        }

        .informasi-kegiatan-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .informasi-kegiatan-card-image:hover img {
            transform: scale(1.05);
        }

        /* Adjust card text padding */
        .informasi-kegiatan-card-text {
            padding: 1.25rem;
        }

        .informasi-kegiatan-card-text h3 {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .informasi-kegiatan-card-text p {
            font-size: 0.875rem;
            line-height: 1.6;
            color: #6b7280;
        }

        /* Ensure consistent card height */
        .informasi-kegiatan-card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .informasi-kegiatan-card-text {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .info-kegiatan-btn-more {
            margin-top: auto;
        }
    </style>
@endsection