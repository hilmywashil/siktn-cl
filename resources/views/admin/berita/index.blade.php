{{-- resources/views/admin/berita/index.blade.php --}}
@extends('admin.layouts.admin-layout')

@section('title', 'Kelola Berita')
@section('page-title', 'Kelola Berita')

@php
$activeMenu = 'berita';
@endphp

@push('styles')
<style>
    @keyframes select2DropdownFadeIn {
        from { opacity: 0; transform: translateY(-8px) scale(0.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .select2-container--default .select2-selection--single {
        height: 40px; padding: 0.35rem 0.75rem; font-size: 0.8125rem; font-weight: 600;
        color: var(--navy); background-color: #fff; border: 1px solid var(--gray-300);
        border-radius: var(--radius-md); display: flex; align-items: center;
        transition: all 0.2s cubic-bezier(0.4,0,0.2,1);
    }
    .select2-dropdown {
        border: 1px solid var(--gray-300); border-radius: var(--radius-md); font-size: 0.8125rem;
        z-index: 9999; box-shadow: 0 12px 28px rgba(2,38,72,0.15); margin-top: 4px; overflow: hidden;
    }
    .select2-container--open .select2-dropdown {
        animation: select2DropdownFadeIn 0.2s cubic-bezier(0.16,1,0.3,1) forwards;
    }
    .select2-results__option--highlighted[aria-selected],
    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: #022648 !important; color: #ffffff !important; font-weight: 600 !important;
    }

    .admin-ui-scope {
        --navy: #022648; --navy-dark: #01162f; --navy-light: #0a3a6b;
        --gold: #b7830f; --green: #059669; --blue: #2563eb; --red: #dc2626; --amber: #d97706;
        --gray-50: #f9fafb; --gray-100: #f3f4f6; --gray-200: #e5e7eb; --gray-300: #d1d5db;
        --gray-500: #6b7280; --gray-700: #374151; --gray-900: #111827;
        --radius-sm: 4px; --radius-md: 6px; --radius-lg: 8px;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* Stat Cards Benchmark */
    .stat-cards-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem; margin-bottom: 1.75rem;
    }
    .stat-card {
        background: #fff; border-radius: var(--radius-lg); padding: 1.25rem;
        border: 1px solid var(--gray-200); box-shadow: 0 1px 3px rgba(2,38,72,0.05);
        display: flex; align-items: center; gap: 1rem; position: relative; overflow: hidden;
    }
    .stat-card::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: var(--navy);
    }
    .stat-card.published-card::before { background: var(--green); }
    .stat-card.draft-card::before     { background: var(--gray-500); }
    .stat-card.archived-card::before  { background: var(--red); }
    .stat-icon {
        width: 44px; height: 44px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; background: var(--gray-100); color: var(--navy);
    }
    .stat-info h4 {
        margin: 0; font-size: 0.75rem; color: var(--gray-500);
        font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .stat-info .value {
        font-size: 1.6rem; font-weight: 800; color: var(--navy); margin-top: 0.2rem;
        font-family: 'Montserrat', sans-serif;
    }

    /* Buttons */
    .btn-solid-navy {
        background: var(--navy); color: white; padding: 0.55rem 1.15rem;
        border-radius: var(--radius-md); font-weight: 600; font-size: 0.875rem;
        border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem;
        text-decoration: none; transition: all 0.2s cubic-bezier(0.4,0,0.2,1);
        box-shadow: 0 2px 4px rgba(2,38,72,0.12);
    }
    .btn-solid-navy:hover { background: var(--navy-light); color: white; transform: translateY(-1px); }
    .btn-outline-secondary {
        background: white; color: var(--gray-700); padding: 0.55rem 1.15rem;
        border-radius: var(--radius-md); font-weight: 600; font-size: 0.875rem;
        border: 1px solid var(--gray-300); cursor: pointer; display: inline-flex;
        align-items: center; gap: 0.5rem; text-decoration: none; transition: all 0.2s ease;
    }
    .btn-outline-secondary:hover { background: var(--gray-100); color: var(--navy); }

    /* Filter Card */
    .filter-card {
        background: white; border-radius: var(--radius-lg); padding: 1.25rem 1.5rem;
        border: 1px solid var(--gray-200); box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 1.5rem;
    }
    .filter-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1.25rem; align-items: flex-end;
    }
    .form-group { display: flex; flex-direction: column; gap: 0.4rem; }
    .form-group label { font-size: 0.85rem; font-weight: 700; color: var(--navy); }
    .form-control {
        padding: 0.55rem 0.875rem; border-radius: var(--radius-md);
        border: 1px solid var(--gray-300); font-size: 0.875rem; outline: none;
        background: white; transition: all 0.2s ease; width: 100%;
    }
    .form-control:focus { border-color: var(--navy); box-shadow: 0 0 0 3px rgba(2,38,72,0.1); }

    /* Filter Tabs Header */
    .filter-tabs-header {
        display: flex; justify-content: flex-end;
        align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem;
    }

    /* Table Container */
    .table-container {
        background: white; border-radius: var(--radius-lg); border: 1px solid var(--gray-200);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden;
    }
    .table-wrapper { overflow-x: auto; }
    .data-table { width: 100%; border-collapse: collapse; min-width: 820px; }
    .data-table thead { background: var(--gray-50); border-bottom: 1px solid var(--gray-200); }
    .data-table th {
        padding: 0.875rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 700;
        color: var(--gray-700); text-transform: uppercase; letter-spacing: 0.05em;
    }
    .data-table td { padding: 1rem; border-bottom: 1px solid var(--gray-100); vertical-align: middle; }
    .data-table tbody tr:hover { background: var(--gray-50); }

    /* Berita Thumbnail */
    .berita-image {
        width: 72px; height: 72px; border-radius: var(--radius-md);
        object-fit: cover; border: 1px solid var(--gray-200);
    }

    /* Badges */
    .badge {
        padding: 0.3rem 0.65rem; border-radius: 9999px; font-size: 0.72rem;
        font-weight: 700; display: inline-flex; align-items: center; gap: 0.25rem;
    }
    .badge-Draft     { background: var(--gray-100); color: #4b5563; }
    .badge-Published { background: #d1fae5; color: #065f46; }
    .badge-Archived  { background: #fee2e2; color: #991b1b; }
    .badge-populer   { background: #fef3c7; color: #92400e; }

    /* Action Trigger (⋮) */
    .aksi-wrapper { position: relative; display: inline-block; }
    .btn-aksi-trigger {
        width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;
        background: var(--navy); color: #fff; border: none; border-radius: var(--radius-md);
        cursor: pointer; transition: all 0.2s cubic-bezier(0.4,0,0.2,1);
        box-shadow: 0 1px 3px rgba(2,38,72,0.12);
    }
    .btn-aksi-trigger:hover { background: var(--navy-light); transform: scale(1.08) translateY(-1px); }
    .aksi-dropdown {
        display: block; position: fixed; min-width: 175px; background: #fff;
        border: 1px solid var(--gray-200); border-radius: var(--radius-md);
        box-shadow: 0 14px 32px rgba(2,38,72,0.18); padding: 6px; z-index: 9999;
        opacity: 0; visibility: hidden; transform: translateY(-8px) scale(0.96);
        transition: opacity 0.18s cubic-bezier(0.16,1,0.3,1), transform 0.18s cubic-bezier(0.16,1,0.3,1), visibility 0.18s;
        pointer-events: none;
    }
    .aksi-dropdown.is-open { opacity: 1; visibility: visible; transform: translateY(0) scale(1); pointer-events: auto; }
    .aksi-item {
        display: flex; align-items: center; gap: 9px; width: 100%;
        padding: 0.55rem 0.65rem; font-size: 0.8125rem; font-weight: 600;
        border-radius: var(--radius-sm); color: var(--gray-900); text-decoration: none !important;
        border: none; background: transparent; text-align: left; cursor: pointer;
        transition: all 0.18s cubic-bezier(0.4,0,0.2,1);
    }
    .aksi-item:hover { background: var(--gray-100); transform: translateX(4px); }
    .aksi-item.aksi-edit:hover   { color: var(--blue); }
    .aksi-item.aksi-delete:hover { color: var(--red); background: #fef2f2; }
    .aksi-divider { height: 1px; background: var(--gray-200); margin: 4px 0; }

    /* Empty State */
    .empty-state { text-align: center; padding: 4rem 2rem; color: var(--gray-500); }
    .empty-state svg { width: 56px; height: 56px; margin-bottom: 1rem; opacity: 0.4; }

    /* Custom Pagination */
    .pag-wrapper {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 1rem; padding: 1.25rem 1rem;
        border-top: 1px solid var(--gray-200);
    }
    .pag-info { font-size: 0.8125rem; color: var(--gray-500); }
    .pag-buttons { display: flex; gap: 6px; align-items: center; flex-wrap: wrap; }
    .pag-btn {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 36px; height: 36px; padding: 0 10px; border-radius: var(--radius-md);
        border: 1px solid var(--gray-200); background: white; color: var(--gray-700);
        font-size: 0.8125rem; cursor: pointer; transition: all 0.15s; text-decoration: none;
    }
    .pag-btn:hover { background: var(--gray-100); border-color: var(--gray-300); }
    .pag-btn.pag-active { background: var(--navy); color: white; border-color: var(--navy); font-weight: 600; pointer-events: none; }
    .pag-btn.pag-disabled { opacity: 0.35; pointer-events: none; cursor: default; }
    .pag-ellipsis { font-size: 0.8125rem; color: var(--gray-500); padding: 0 4px; line-height: 36px; }
</style>
@endpush

@section('content')
<div class="admin-ui-scope" style="padding-top: 0.5rem;">

    <!-- Stat Cards Top Benchmark -->
    <div class="stat-cards-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: #e0f2fe; color: #0369a1;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
            </div>
            <div class="stat-info">
                <h4>Total Berita</h4>
                <div class="value">{{ $beritas->total() }}</div>
            </div>
        </div>

        <div class="stat-card published-card">
            <div class="stat-icon" style="background: #ecfdf5; color: #059669;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <div class="stat-info">
                <h4>Published</h4>
                <div class="value" style="color: var(--green);">{{ $beritas->where('status', 'Published')->count() }}</div>
            </div>
        </div>

        <div class="stat-card draft-card">
            <div class="stat-icon" style="background: var(--gray-100); color: var(--gray-500);">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </div>
            <div class="stat-info">
                <h4>Draft</h4>
                <div class="value" style="color: var(--gray-500);">{{ $beritas->where('status', 'Draft')->count() }}</div>
            </div>
        </div>

        <div class="stat-card archived-card">
            <div class="stat-icon" style="background: #fee2e2; color: #dc2626;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="21 8 21 21 3 21 3 8"></polyline><rect x="1" y="3" width="22" height="5"></rect><line x1="10" y1="12" x2="14" y2="12"></line></svg>
            </div>
            <div class="stat-info">
                <h4>Archived</h4>
                <div class="value" style="color: var(--red);">{{ $beritas->where('status', 'Archived')->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Filter Box -->
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.berita.index') }}">
            <div class="filter-grid">
                <div class="form-group">
                    <label for="q">Cari Judul Berita</label>
                    <input type="text" name="q" id="q" class="form-control" placeholder="Ketik judul berita..." value="{{ request('q') }}">
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control select2-basic">
                        <option value="">-- Semua Status --</option>
                        <option value="Draft"     {{ request('status') == 'Draft'     ? 'selected' : '' }}>Draft</option>
                        <option value="Published" {{ request('status') == 'Published' ? 'selected' : '' }}>Published</option>
                        <option value="Archived"  {{ request('status') == 'Archived'  ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="kategori">Kategori</label>
                    <select name="kategori" id="kategori" class="form-control select2-basic">
                        <option value="">-- Semua Kategori --</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="display: flex; gap: 0.5rem; align-items: flex-end;">
                    <button type="submit" class="btn-solid-navy" style="white-space: nowrap;">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Filter
                    </button>
                    @if(request()->anyFilled(['q', 'status', 'kategori']))
                        <a href="{{ route('admin.berita.index') }}" class="btn-outline-secondary">Reset</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Actions Header -->
    @if(auth()->guard('admin')->user()->canManageContent())
    <div class="filter-tabs-header">
        <a href="{{ route('admin.berita.create') }}" class="btn-solid-navy">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah Berita
        </a>
    </div>
    @endif

    <!-- Table Container -->
    <div class="table-container">
        @if($beritas->count() > 0)
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 90px;">GAMBAR</th>
                        <th>KATEGORI & JUDUL</th>
                        <th>TGL & JAM TAYANG</th>
                        <th>STATUS</th>
                        <th>VIEWS</th>
                        @if(auth()->guard('admin')->user()->canManageContent())
                        <th style="text-align: center; width: 80px;">AKSI</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($beritas as $berita)
                    <tr>
                        <td>
                            <img src="{{ $berita->gambar_url }}" alt="{{ $berita->judul }}" class="berita-image">
                        </td>
                        <td>
                            <div style="font-size: 0.72rem; font-weight: 700; color: var(--navy); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.25rem;">{{ $berita->kategori }}</div>
                            <div style="font-weight: 600; color: var(--gray-900); line-height: 1.4;">{{ $berita->judul }}</div>
                        </td>
                        <td style="font-size: 0.875rem; color: var(--gray-500);">
                            {{ $berita->tanggal_publish ? $berita->tanggal_publish->format('d M Y, H:i') : '-' }}
                        </td>
                        <td>
                            <div style="display: flex; flex-direction: column; gap: 0.4rem; align-items: flex-start;">
                                <span class="badge badge-{{ $berita->status }}">● {{ $berita->status }}</span>
                                @if($berita->is_populer)
                                    <span class="badge badge-populer">Populer</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <strong style="color: var(--navy);">{{ number_format($berita->views) }}</strong>
                            <span style="font-size: 0.8rem; color: var(--gray-500);"> views</span>
                        </td>
                        @if(auth()->guard('admin')->user()->canManageContent())
                        <td style="text-align: center;">
                            <!-- Action Dropdown Trigger (⋮) -->
                            <div class="aksi-wrapper">
                                <button type="button" class="btn-aksi-trigger" data-target="dropdown-berita-{{ $berita->id }}" aria-label="Menu Aksi">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                        <circle cx="12" cy="5" r="1.75"></circle>
                                        <circle cx="12" cy="12" r="1.75"></circle>
                                        <circle cx="12" cy="19" r="1.75"></circle>
                                    </svg>
                                </button>

                                <div class="aksi-dropdown" id="dropdown-berita-{{ $berita->id }}">
                                    <a href="{{ route('admin.berita.edit', $berita->id) }}" class="aksi-item aksi-edit">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        Edit Berita
                                    </a>

                                    <div class="aksi-divider"></div>

                                    <button type="button" class="aksi-item aksi-delete btn-delete-sweetalert" data-id="{{ $berita->id }}" data-judul="{{ addslashes($berita->judul) }}">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        Hapus Berita
                                    </button>
                                    <form id="delete-form-{{ $berita->id }}" action="{{ route('admin.berita.destroy', $berita->id) }}" method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Custom Pagination --}}
        @if($beritas->hasPages())
        @php
            $currentPage = $beritas->currentPage();
            $lastPage    = $beritas->lastPage();
            $pages = [];
            if ($lastPage <= 7) {
                $pages = range(1, $lastPage);
            } else {
                $pages[] = 1;
                if ($currentPage > 3) $pages[] = '...';
                $start = max(2, $currentPage - 1);
                $end   = min($lastPage - 1, $currentPage + 1);
                foreach (range($start, $end) as $p) $pages[] = $p;
                if ($currentPage < $lastPage - 2) $pages[] = '...';
                $pages[] = $lastPage;
            }
        @endphp

        <div class="pag-wrapper">
            <span class="pag-info">Menampilkan {{ $beritas->firstItem() }}–{{ $beritas->lastItem() }} dari {{ $beritas->total() }} berita</span>
            <div class="pag-buttons">
                @if($beritas->onFirstPage())
                    <span class="pag-btn pag-disabled"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></span>
                @else
                    <a href="{{ $beritas->previousPageUrl() }}" class="pag-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></a>
                @endif

                @foreach($pages as $page)
                    @if($page === '...')
                        <span class="pag-ellipsis">···</span>
                    @elseif($page === $currentPage)
                        <span class="pag-btn pag-active">{{ $page }}</span>
                    @else
                        <a href="{{ $beritas->url($page) }}" class="pag-btn">{{ $page }}</a>
                    @endif
                @endforeach

                @if($beritas->hasMorePages())
                    <a href="{{ $beritas->nextPageUrl() }}" class="pag-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a>
                @else
                    <span class="pag-btn pag-disabled"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></span>
                @endif
            </div>
        </div>
        @endif

        @else
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z" />
                <polyline points="13 2 13 9 20 9" />
            </svg>
            <h4 style="font-size: 1rem; font-weight: 700; color: var(--gray-700); margin-bottom: 0.5rem;">Belum Ada Berita</h4>
            <p style="font-size: 0.875rem;">Mulai tambahkan berita pertama Anda</p>
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Select2 Init
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2-basic').select2({ minimumResultsForSearch: -1, width: '100%' });
        }

        let activeDropdown = null;

        // Action Dropdown Trigger (⋮)
        document.querySelectorAll('.btn-aksi-trigger').forEach(button => {
            button.addEventListener('click', function (e) {
                e.stopPropagation();
                const targetId = this.getAttribute('data-target');
                const dropdown = document.getElementById(targetId);

                if (activeDropdown && activeDropdown !== dropdown) {
                    activeDropdown.classList.remove('is-open');
                }

                if (dropdown.classList.contains('is-open')) {
                    dropdown.classList.remove('is-open');
                    activeDropdown = null;
                } else {
                    const rect = this.getBoundingClientRect();
                    dropdown.style.top  = (rect.bottom + 4) + 'px';
                    dropdown.style.left = (rect.right - 175) + 'px';
                    dropdown.classList.add('is-open');
                    activeDropdown = dropdown;
                }
            });
        });

        document.addEventListener('click', function () {
            if (activeDropdown) { activeDropdown.classList.remove('is-open'); activeDropdown = null; }
        });
        window.addEventListener('scroll', function () {
            if (activeDropdown) { activeDropdown.classList.remove('is-open'); activeDropdown = null; }
        }, true);

        // SweetAlert2 Delete Confirmation
        document.querySelectorAll('.btn-delete-sweetalert').forEach(button => {
            button.addEventListener('click', function () {
                const id    = this.getAttribute('data-id');
                const judul = this.getAttribute('data-judul');

                Swal.fire({
                    title: 'Hapus Berita?',
                    text: `Berita "${judul}" akan dihapus secara permanen!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#f3f4f6',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                });
            });
        });
    });
</script>
@endpush