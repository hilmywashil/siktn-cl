@extends('layouts.app')

@section('title', 'Berita')

@section('hero-background', asset('assets-front/images/hero_bg.jpg'))
@section('page-title', 'INFORMASI DAN KEGIATAN TERBARU')
@section('page-subtitle', 'Corps Alumni Akademi Ilmu Pelayaran')
@section('page-description', 'Berita organisasi, program kerja, kegiatan sosial, dan perkembangan terbaru anggota SIKTN.')
@section('hero-buttons', 'hide')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    @keyframes select2DropdownFadeIn {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .select2-container--default .select2-selection--single {
        height: 42px;
        padding: 6px 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        color: #022648;
        background-color: #ffffff;
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #022648;
        font-weight: 600;
        line-height: 28px;
        padding-left: 0;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px;
        right: 8px;
    }
    .select2-dropdown {
        border-radius: 8px;
        border: 1px solid #d1d5db;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        animation: select2DropdownFadeIn 0.2s ease-out forwards;
        overflow: hidden;
        z-index: 9999;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #022648 !important;
        color: #ffffff !important;
        font-weight: 700;
    }
    .select2-container--default .select2-results__option[aria-selected="true"] {
        background-color: #f1f5f9;
        color: #022648;
        font-weight: 700;
    }
</style>
@endpush

@section('content')
    @include('layouts.components.hero')
    <section class="wrapper-white-1">
        <div style="width: 100%; max-width: 1300px; display: flex; flex-direction: column; align-items: center; gap: 2rem;">
            <div style="width: 100%;" data-aos="fade-up">
                <form method="GET" action="{{ route('berita') }}" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; background: #ffffff; padding: 1.25rem; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.04); width: 100%;">
                    <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; flex: 1;">
                        <!-- Search Input -->
                        <div style="position: relative; min-width: 260px; flex: 1;">
                            <i class="fa fa-search" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.9rem;"></i>
                            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari berita atau kata kunci..." style="width: 100%; padding: 0.6rem 1rem 0.6rem 2.5rem; border-radius: 8px; border: 1px solid #d1d5db; font-size: 0.875rem; font-weight: 500; color: #022648; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#022648'" onblur="this.style.borderColor='#d1d5db'">
                        </div>

                        <!-- Select2 Region Filter -->
                        <div style="display: flex; align-items: center; gap: 0.5rem; min-width: 240px;">
                            <label style="font-weight: 700; color: #022648; font-size: 0.875rem; display: flex; align-items: center; gap: 6px; white-space: nowrap;">
                                <i class="fa fa-map-marker-alt" style="color: #c59217;"></i> Wilayah:
                            </label>
                            <select name="wilayah" id="wilayahSelect" class="select2-basic" style="width: 100%;">
                                @if(isset($daftarProvinsi))
                                    @foreach($daftarProvinsi as $pKey => $pVal)
                                        @php $val = ($pKey === 'Semua') ? '' : $pKey; @endphp
                                        <option value="{{ $val }}" {{ ($wilayahSelected ?? '') === $val ? 'selected' : '' }}>
                                            {{ $pVal }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" style="padding: 0.6rem 1.25rem; background: #022648; color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 0.875rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: background 0.2s;" onmouseover="this.style.background='#043666'" onmouseout="this.style.background='#022648'">
                            <i class="fa fa-search"></i> Cari
                        </button>
                    </div>

                    @if($search || $wilayahSelected)
                        <a href="{{ route('berita') }}" style="font-size: 0.825rem; color: #dc2626; font-weight: 700; text-decoration: underline; display: inline-flex; align-items: center; gap: 4px;">
                            <i class="fa fa-times"></i> Reset Filter
                        </a>
                    @endif
                </form>
            </div>

            <div class="berita-page-section" data-aos="fade-up" style="width: 100%;">
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
                            <p>Belum ada berita yang dipublikasikan untuk pencarian atau wilayah ini.</p>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof jQuery !== 'undefined' && typeof $.fn.select2 !== 'undefined') {
            $('#wilayahSelect').select2({
                placeholder: '-- Semua Wilayah --',
                allowClear: true,
                dropdownAutoWidth: true,
                width: '100%'
            }).on('change', function() {
                $(this).closest('form').submit();
            });
        }
    });
</script>
@endpush