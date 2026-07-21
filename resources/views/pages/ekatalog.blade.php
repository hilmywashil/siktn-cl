@extends('layouts.app')

@section('title', 'E-Catalog')

@section('hero-background', asset('assets-front/images/bg_5.jpg'))
@section('page-title', 'E-CATALOG')
@section('page-subtitle', 'Corps Alumni Akademi Ilmu Pelayaran')
@section('page-description', 'Wadah resmi alumni STIP Jakarta untuk kolaborasi, karier, dan kontribusi nasional.')
@section('hero-buttons', 'hide')

@push('styles')
    <style>
        .search-container {
            max-width: 1000px;
            margin: auto;
            position: relative;
            z-index: 10;
            padding: 0 20px;
        }

        .search-form {
            display: flex;
            background: #ffffff;
            border-radius: 50px;
            padding: 8px 8px 8px 24px;
            box-shadow: 0 15px 35px rgba(9, 11, 98, 0.08);
            border: 1px solid rgba(9, 11, 98, 0.08);
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .search-form:focus-within {
            border-color: #090B62;
            box-shadow: 0 15px 35px rgba(9, 11, 98, 0.15);
            transform: translateY(-2px);
        }

        .search-input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 0.95rem;
            color: #04293B;
            font-family: 'Google Sans', sans-serif;
            background: transparent;
        }

        .search-input::placeholder {
            color: #9CA3AF;
        }

        .search-btn {
            background: #090B62;
            color: #ffffff;
            border: none;
            border-radius: 50px;
            padding: 12px 28px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            font-family: 'Google Sans', sans-serif;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(9, 11, 98, 0.2);
        }

        .search-btn:hover {
            background: #FFE701;
            color: #000000;
            box-shadow: 0 4px 10px rgba(255, 231, 1, 0.3);
        }

        .reset-btn {
            background: #EF4444;
            color: #ffffff;
            border: none;
            border-radius: 50px;
            padding: 12px 20px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: 'Google Sans', sans-serif;
            transition: all 0.2s ease;
        }

        .reset-btn:hover {
            background: #DC2626;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.01);
            border: 1px solid rgba(9, 11, 98, 0.05);
            width: 100%;
        }

        .empty-state i {
            font-size: 3.5rem;
            color: rgba(9, 11, 98, 0.15);
            margin-bottom: 20px;
            display: block;
        }

        .empty-state h3 {
            font-family: 'Google Sans', sans-serif;
            font-weight: 700;
            color: #090B62;
            font-size: 1.4rem;
            margin-bottom: 10px;
        }

        .empty-state p {
            font-size: 0.95rem;
            color: #000;
            max-width: 500px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* ===== CUSTOM DROPDOWN ===== */
        .custom-select {
            position: relative;
            flex-shrink: 0;
            max-width: 180px;
            border-left: 1px solid #e5e5e5;
        }

        .custom-select .select-trigger {
            padding: 10px 25px 10px 15px;
            font-size: 0.9rem;
            color: #04293B;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            white-space: nowrap;
            user-select: none;
            font-family: 'Google Sans', sans-serif;
        }

        .custom-select .select-trigger span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .custom-select .select-trigger i {
            font-size: 0.7rem;
            color: #090B62;
            transition: transform 0.2s ease;
            flex-shrink: 0;
        }

        .custom-select.open .select-trigger i {
            transform: rotate(180deg);
        }

        .custom-select .select-options {
            position: absolute;
            top: calc(100% + 10px);
            left: 0;
            min-width: 180px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(9, 11, 98, 0.15);
            border: 1px solid rgba(9, 11, 98, 0.08);
            list-style: none;
            margin: 0;
            padding: 8px;
            max-height: 260px;
            overflow-y: auto;
            z-index: 100;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: all 0.2s ease;
        }

        .custom-select.open .select-options {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .custom-select .select-options li {
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.9rem;
            color: #04293B;
            cursor: pointer;
            font-family: 'Google Sans', sans-serif;
            transition: all 0.15s ease;
        }

        .custom-select .select-options li:hover {
            background: #F0F0F6;
        }

        .custom-select .select-options li.selected {
            background: #090B62;
            color: #ffffff;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .search-form {
                flex-direction: column;
                align-items: stretch;
                border-radius: 12px;
                padding: 15px;
            }
            .custom-select {
                border-left: none;
                border-top: 1px solid #e5e7eb;
                max-width: none;
                width: 100%;
                margin-left: 0 !important;
            }
            .custom-select .select-options {
                left: 0;
                right: 0;
                min-width: auto;
            }
            .search-btn {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    @include('layouts.components.hero')

    <section style="background-color: #F0F0F6; padding: 50px 0px 0px 0px;">

        <!-- SEARCH BAR -->
        <div class="search-container" data-aos="fade-up">
            <form action="{{ route('e-katalog') }}" method="GET" class="search-form">
                <i class="fas fa-search" style="color: #090B62; opacity: 0.5;"></i>
                <input type="text" name="search" class="search-input" value="{{ request('search') }}"
                    placeholder="Cari nama perusahaan atau bidang usaha...">

                <!-- KATEGORI CUSTOM DROPDOWN -->
                <div class="custom-select" style="margin-left: 10px;">
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    <div class="select-trigger">
                        <span>
                            @php
                                $selectedKategori = $kategoris->firstWhere('id', request('kategori'));
                            @endphp
                            {{ $selectedKategori->nama ?? 'Semua Kategori' }}
                        </span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <ul class="select-options">
                        <li data-value="" class="{{ !request('kategori') ? 'selected' : '' }}">Semua Kategori</li>
                        @foreach($kategoris as $kat)
                            <li data-value="{{ $kat->id }}" class="{{ request('kategori') == $kat->id ? 'selected' : '' }}">{{ $kat->nama }}</li>
                        @endforeach
                    </ul>
                </div>

                <!-- WILAYAH CUSTOM DROPDOWN -->
                <div class="custom-select">
                    <input type="hidden" name="wilayah" value="{{ request('wilayah') }}">
                    <div class="select-trigger">
                        <span>{{ request('wilayah') ?: 'Semua Wilayah' }}</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <ul class="select-options">
                        <li data-value="" class="{{ !request('wilayah') ? 'selected' : '' }}">Semua Wilayah</li>
                        @foreach($wilayahList as $wil)
                            <li data-value="{{ $wil }}" class="{{ request('wilayah') == $wil ? 'selected' : '' }}">{{ $wil }}</li>
                        @endforeach
                    </ul>
                </div>

                @if(request()->filled('search') || request()->filled('kategori') || request()->filled('wilayah'))
                    <a href="{{ route('e-katalog') }}" class="reset-btn">Reset</a>
                @endif
                <button type="submit" class="search-btn">Cari</button>
            </form>
        </div>

    </section>

    <!-- MAIN GRID SECTION -->
    <section class="wrapper-white-1">
        <div class="katalog-section" data-aos="fade-up">
            @if($katalogs->count() > 0)
                <div class="grid">
                    @foreach($katalogs as $katalog)
                        <a href="{{ route('e-katalog.detail', $katalog->id) }}">
                            <div class="item">
                                <img src="{{ $katalog->logo_url }}" alt="{{ $katalog->company_name }}">
                                <div>
                                    <h3>{{ $katalog->company_name }}</h3>
                                    <p>{{ $katalog->business_field }}</p>
                                    @if($katalog->kategori)
                                        <span style="display: inline-block; background: #022648; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.7rem; margin-top: 4px;">{{ $katalog->kategori->nama }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-store-slash"></i>
                    <h3>Katalog Tidak Ditemukan</h3>
                    <p style="color: #000;">Belum ada katalog perusahaan terverifikasi yang cocok dengan kata kunci pencarian
                        Anda.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- PAGINATION SECTION -->
    @if($katalogs->hasPages())
        <section class="wrapper-pagination-white">
            <div class="pagination-section" data-aos="fade-up">
                {{ $katalogs->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </section>
    @endif
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.custom-select').forEach(function (wrapper) {
            const trigger = wrapper.querySelector('.select-trigger');
            const options = wrapper.querySelectorAll('.select-options li');
            const hiddenInput = wrapper.querySelector('input[type="hidden"]');
            const triggerText = trigger.querySelector('span');

            trigger.addEventListener('click', function (e) {
                e.stopPropagation();
                document.querySelectorAll('.custom-select').forEach(function (w) {
                    if (w !== wrapper) w.classList.remove('open');
                });
                wrapper.classList.toggle('open');
            });

            options.forEach(function (option) {
                option.addEventListener('click', function () {
                    hiddenInput.value = this.dataset.value;
                    triggerText.textContent = this.textContent.trim();
                    options.forEach(o => o.classList.remove('selected'));
                    this.classList.add('selected');
                    wrapper.classList.remove('open');
                });
            });
        });

        document.addEventListener('click', function () {
            document.querySelectorAll('.custom-select').forEach(w => w.classList.remove('open'));
        });
    </script>
@endpush