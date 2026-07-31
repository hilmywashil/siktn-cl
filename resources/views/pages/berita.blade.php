@extends('layouts.app')

@section('title', 'Berita')

@section('hero-background', asset('assets-front/images/hero_bg.jpg'))
@section('page-title', 'INFORMASI DAN KEGIATAN TERBARU')
@section('page-subtitle', 'Corps Alumni Akademi Ilmu Pelayaran')
@section('page-description', 'Berita organisasi, program kerja, kegiatan sosial, dan perkembangan terbaru anggota SIKTN.')
@section('hero-buttons', 'hide')

@section('content')
    @include('layouts.components.hero')
    <section class="wrapper-white-1">
        <div style="max-width: 1200px; margin: 0 auto 1.5rem; padding: 0 1rem;" data-aos="fade-up">
            <form method="GET" action="{{ route('berita') }}" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; background: #f8f9fc; padding: 1rem 1.25rem; border-radius: 12px; border: 1px solid #e5e7eb;">
                <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
                    <label style="font-weight: 700; color: #022648; font-size: 0.9rem; display: flex; align-items: center; gap: 6px;">
                        <i class="fa fa-map-marker-alt" style="color: #c59217;"></i> Filter Berita Wilayah:
                    </label>
                    <select name="wilayah" onchange="this.form.submit()" style="padding: 0.5rem 1rem; border-radius: 8px; border: 1px solid #d1d5db; font-size: 0.875rem; font-weight: 600; color: #022648; outline: none; background: white; cursor: pointer;">
                        <option value="">-- Semua Wilayah --</option>
                        @if(isset($listWilayah))
                            @foreach($listWilayah as $wil)
                                <option value="{{ $wil }}" {{ ($wilayahSelected ?? '') === $wil ? 'selected' : '' }}>
                                    {{ $wil }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                @if($search || $wilayahSelected)
                    <a href="{{ route('berita') }}" style="font-size: 0.825rem; color: #dc2626; font-weight: 700; text-decoration: underline;">
                        Reset Filter
                    </a>
                @endif
            </form>
        </div>

        <div class="berita-page-section" data-aos="fade-up">
            <div class="left">
                @forelse($beritas as $item)
                    <div class="item">
                        <div class="image">
                            <a href="{{ route('berita-detail', $item->slug) }}">
                                <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}">
                            </a>
                        </div>
                        <div class="content">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                                <span style="font-size: 0.72rem; font-weight: 700; color: #022648; background: #e0f2fe; padding: 2px 8px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fa fa-map-marker-alt" style="color: #022648;"></i> {{ $item->wilayah ?? 'Nasional' }}
                                </span>
                                <span style="font-size: 0.72rem; font-weight: 700; color: #6b7280; background: #f3f4f6; padding: 2px 8px; border-radius: 4px;">
                                    {{ $item->kategori }}
                                </span>
                            </div>
                            <a href="{{ route('berita-detail', $item->slug) }}">
                                <h2>{{ $item->judul }}</h2>
                            </a>
                            <p class="date">{{ $item->tanggal_format }}</p>
                            <p>{{ Str::limit(trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($item->konten)))), 200) }}</p>
                            <a href="{{ route('berita-detail', $item->slug) }}" class="btn">Baca Selengkapnya <i
                                    class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                @empty
                    <div style="padding: 40px; text-align: center; color: #6b7280; width: 100%;">
                        <p>Belum ada berita yang dipublikasikan untuk wilayah ini.</p>
                    </div>
                @endforelse
            </div>
            <div class="right">
                <a href="#" class="btn-navy">BERITA POPULER</a>
                <hr class="divider-line">
                @forelse($beritaPopuler as $item)
                    <div class="item">
                        <div class="content">
                            <a href="{{ route('berita-detail', $item->slug) }}">
                                <h2>{{ $item->judul }}</h2>
                            </a>
                            <p class="date">{{ $item->tanggal_format }}</p>
                            <p>{{ Str::limit(trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($item->konten)))), 150) }}</p>
                            <a href="{{ route('berita-detail', $item->slug) }}" class="btn">Baca Selengkapnya <i
                                    class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                    <hr class="divider-line">
                @empty
                    <div style="padding: 20px; text-align: center; color: #6b7280;">
                        <p>Belum ada berita populer.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    @if($beritas->hasPages())
        <section class="wrapper-pagination-white">
            <div class="pagination-section" data-aos="fade-up">
                {{ $beritas->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </section>
    @endif
@endsection