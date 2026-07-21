@extends('layouts.app')

@section('title', 'Program CSR')

@push('styles')
<style>
    .program-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
        width: 100%;
        text-align: left;
    }

    .program-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        border: 1px solid #e5e7eb;
    }

    .program-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.1);
    }

    .program-img-wrapper {
        position: relative;
        width: 100%;
        height: 200px;
        background: white;
        border-bottom: 1px solid #e5e7eb;
        padding: 1rem;
    }

    .program-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
    }

    .program-card:hover .program-img {
        transform: scale(1.05);
    }

    .program-status {
        position: absolute;
        top: 12px;
        right: 12px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.725rem;
        font-weight: 700;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        z-index: 2;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .status-selesai { background: #10b981; }
    .status-berjalan { background: #f59e0b; }
    .status-perencanaan { background: #6b7280; }

    .program-content {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        text-align: left;
    }

    .program-title {
        font-size: 1.125rem;
        font-weight: 800;
        color: #0a2540;
        margin-bottom: 1rem;
        line-height: 1.4;
    }

    .program-meta {
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
        margin-bottom: 1rem;
        font-size: 0.85rem;
        color: #4b5563;
    }

    .meta-item {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .meta-icon {
        color: #C59217;
        width: 16px;
        margin-top: 2px;
    }

    .program-desc {
        font-size: 0.875rem;
        color: #6b7280;
        line-height: 1.6;
        margin-bottom: 1.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex-grow: 1;
    }

    .program-footer {
        padding-top: 1rem;
        border-top: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8125rem;
        font-weight: 700;
        color: #0a2540;
    }

    .pic-avatar {
        width: 24px;
        height: 24px;
        background: #e5e7eb;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        font-size: 10px;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem;
        background: #f9fafb;
        border-radius: 12px;
        color: #6b7280;
        font-weight: 500;
    }
</style>
@endpush

@section('content')
    <section class="wrapper-white-1">
        <div class="white-page-banner-section" data-aos="fade-up">
            <h1>{{ $settings['banner_title'] }}</h1>
            <p>{{ $settings['banner_desc'] }}</p>
        </div>
    </section>
    <section class="wrapper-white-1">
        <div class="about-section" data-aos="fade-up">
            <div class="left">
                <img src="{{ $settings['about_image'] ? asset('storage/' . $settings['about_image']) : asset('assets-front/images/about_image.jpg') }}" alt="Tentang Karang Taruna">
            </div>
            <div class="right">
                <h2>{{ $settings['about_title'] }}</h2>
                <p>{{ $settings['about_desc1'] }}</p>
                <h2>Tentang Program</h2>
                <div>
                    {!! $settings['about_desc2'] !!}
                </div>
            </div>
        </div>
    </section>
    <section class="wrapper-blue-1">
        <div class="stats-section" data-aos="fade-up">
            <div class="grid">
                <div class="item">
                    <div class="icon">
                        <img src="{{ asset('assets-front/images/icons/user-group.png') }}" alt="Icon">
                    </div>
                    <h2>200+</h2>
                    <p>Total Anggota</p>
                </div>
                <div class="item">
                    <div class="icon">
                        <img src="{{ asset('assets-front/images/icons/location.png') }}" alt="Icon">
                    </div>
                    <h2>150+</h2>
                    <p>Total Wilayah</p>
                </div>
                <div class="item">
                    <div class="icon">
                        <img src="{{ asset('assets-front/images/icons/puzzle.png') }}" alt="Icon">
                    </div>
                    <h2>20</h2>
                    <p>Total Program</p>
                </div>
                <div class="item">
                    <div class="icon">
                        <img src="{{ asset('assets-front/images/icons/catalog.png') }}" alt="Icon">
                    </div>
                    <h2>120+</h2>
                    <p>Total E-Catalog</p>
                </div>
            </div>
        </div>
    </section>
    <section class="wrapper-lightblue">
        <div class="tujuan-section" data-aos="fade-up">
            <h1>Tujuan Program</h1>
            <div class="grid">
                @foreach($settings['tujuan'] as $tujuan)
                <div class="item">
                    <h2>{{ $tujuan['title'] }}</h2>
                    <p>{{ $tujuan['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    <section class="wrapper-white-1">
        <div class="tujuan-section" data-aos="fade-up">
            <h1>Fokus Program CSR</h1>
            <div class="grid">
                @foreach($settings['fokus'] as $fokus)
                <div class="item-2">
                    <img src="{{ str_starts_with($fokus['icon'], 'page_settings/') ? asset('storage/' . $fokus['icon']) : asset($fokus['icon']) }}" alt="">
                    <h2>{{ $fokus['title'] }}</h2>
                    {!! $fokus['desc'] !!}
                </div>
                @endforeach
            </div>
        </div>
    </section>
    <section class="wrapper-white-1">
        <div class="tujuan-section" data-aos="fade-up">
            <h1>Daftar Program CSR</h1>
            <p style="text-align: center; color: #6b7280; margin-bottom: 2rem;">Inisiatif dan kontribusi nyata yang sedang atau telah dilaksanakan.</p>
            
            @if($programs->count() > 0)
                <div class="program-grid">
                    @foreach($programs as $program)
                        <div class="program-card">
                            <div class="program-img-wrapper">
                                @php
                                    $statusClass = 'status-perencanaan';
                                    if($program->status == 'Selesai') $statusClass = 'status-selesai';
                                    if($program->status == 'Berjalan') $statusClass = 'status-berjalan';
                                @endphp
                                <span class="program-status {{ $statusClass }}">{{ $program->status }}</span>
                                <img src="{{ $program->gambar_url }}" alt="{{ $program->nama_program }}" class="program-img">
                            </div>
                            <div class="program-content">
                                <h3 class="program-title">{{ $program->nama_program }}</h3>
                                
                                <div class="program-meta">
                                    <div class="meta-item">
                                        <i class="fa fa-calendar meta-icon"></i>
                                        <span>{{ \Carbon\Carbon::parse($program->periode_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($program->periode_selesai)->format('d M Y') }}</span>
                                    </div>
                                    @if($program->mitra)
                                    <div class="meta-item">
                                        <i class="fa fa-building meta-icon"></i>
                                        <span>Mitra: <strong>{{ $program->mitra }}</strong></span>
                                    </div>
                                    @endif
                                    @if($program->anggaran)
                                    <div class="meta-item">
                                        <i class="fa fa-money-bill-wave meta-icon"></i>
                                        <span>Rp {{ number_format($program->anggaran, 0, ',', '.') }}</span>
                                    </div>
                                    @endif
                                </div>

                                <div class="program-desc">
                                    {{ $program->target_output }}
                                </div>

                                <div class="program-footer">
                                    <div class="pic-avatar"><i class="fa fa-user"></i></div>
                                    <span>PIC: {{ $program->pic }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    Belum ada program CSR yang ditambahkan.
                </div>
            @endif
        </div>
    </section>
    <section class="wrapper-lightblue">
        <div class="about-section" data-aos="fade-up">
            <div class="left">
                <img src="{{ asset('assets-front/images/bg_5.jpg') }}" alt="Tentang Karang Taruna">
            </div>
            <div class="right">
                <h2>Mari Menjadi Bagian<br>dari Perubahan Positif
                </h2>
                <p>Setiap kontribusi yang diberikan memiliki arti besar bagi masyarakat yang membutuhkan. Bergabunglah
                    bersama SIKTN dalam mewujudkan program-program sosial yang memberikan manfaat nyata dan berkelanjutan
                    bagi Indonesia.</p>
                <a href="{{ route('contact') }}" class="btn-yellow-text-black">Hubungi Kami </a>
            </div>
        </div>
    </section>
@endsection