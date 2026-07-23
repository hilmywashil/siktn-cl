@extends('admin.layouts.admin-layout')

@section('title', 'Log Aktivitas / Audit Trail - SIKTN Admin')
@section('page-title', 'Pengaturan - Log Aktivitas Admin')

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
    .stat-card.tambah-card::before { background: var(--green); }
    .stat-card.edit-card::before   { background: var(--blue); }
    .stat-card.hapus-card::before  { background: var(--red); }
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
        display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
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

    /* Table Container */
    .table-container {
        background: white; border-radius: var(--radius-lg); border: 1px solid var(--gray-200);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden;
    }
    .table-wrapper { overflow-x: auto; }
    .data-table { width: 100%; border-collapse: collapse; min-width: 900px; }
    .data-table thead { background: var(--gray-50); border-bottom: 1px solid var(--gray-200); }
    .data-table th {
        padding: 0.875rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 700;
        color: var(--gray-700); text-transform: uppercase; letter-spacing: 0.05em;
    }
    .data-table td { padding: 0.95rem 1rem; border-bottom: 1px solid var(--gray-100); vertical-align: middle; font-size: 0.875rem; }
    .data-table tbody tr:hover { background: var(--gray-50); }

    /* Module & Action Badges */
    .badge-mod {
        font-size: 0.72rem; font-weight: 700; padding: 3px 9px; border-radius: 9999px; display: inline-block;
    }
    .badge-action {
        font-size: 0.72rem; font-weight: 700; padding: 2px 7px; border-radius: 4px; display: inline-block;
    }
    .badge-act-Tambah     { background: #d1fae5; color: #065f46; }
    .badge-act-Edit       { background: #dbeafe; color: #1e40af; }
    .badge-act-Hapus      { background: #fee2e2; color: #991b1b; }
    .badge-act-Approve    { background: #d1fae5; color: #065f46; }
    .badge-act-Tolak      { background: #fee2e2; color: #991b1b; }
    .badge-act-Status     { background: #fef3c7; color: #92400e; }
    .badge-act-default    { background: var(--gray-100); color: var(--gray-700); }

    /* Empty State */
    .empty-state { text-align: center; padding: 4rem 2rem; color: var(--gray-500); }
    .empty-state svg { width: 56px; height: 56px; margin-bottom: 1rem; opacity: 0.4; }

    /* Custom Pagination */
    .pag-wrapper {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 1rem; padding: 1.25rem 1rem; border-top: 1px solid var(--gray-200);
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
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="stat-info">
                <h4>Total Log Recorded</h4>
                <div class="value">{{ number_format($stats['total']) }}</div>
            </div>
        </div>

        <div class="stat-card tambah-card">
            <div class="stat-icon" style="background: #ecfdf5; color: #059669;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            </div>
            <div class="stat-info">
                <h4>Aksi Tambah</h4>
                <div class="value" style="color: var(--green);">{{ number_format($stats['tambah']) }}</div>
            </div>
        </div>

        <div class="stat-card edit-card">
            <div class="stat-icon" style="background: #eff6ff; color: #2563eb;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            </div>
            <div class="stat-info">
                <h4>Aksi Edit</h4>
                <div class="value" style="color: var(--blue);">{{ number_format($stats['edit']) }}</div>
            </div>
        </div>

        <div class="stat-card hapus-card">
            <div class="stat-icon" style="background: #fee2e2; color: #dc2626;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
            </div>
            <div class="stat-info">
                <h4>Aksi Hapus</h4>
                <div class="value" style="color: var(--red);">{{ number_format($stats['hapus']) }}</div>
            </div>
        </div>
    </div>

    <!-- Export Buttons Benchmark -->
    <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-bottom: 1rem;">
        <a href="{{ route('admin.settings.activity-logs.export-pdf', request()->query()) }}" target="_blank" class="btn-outline-secondary" onclick="Toast.fire({ icon: 'success', title: 'Dokumen Printable PDF Log sedang disiapkan...' })">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
            Export PDF
        </a>
        <a href="{{ route('admin.settings.activity-logs.export-txt', request()->query()) }}" class="btn-outline-secondary" onclick="Toast.fire({ icon: 'success', title: 'File TXT Log Aktivitas sedang diunduh...' })">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            Export TXT
        </a>
    </div>

    <!-- Filter Card -->
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.settings.activity-logs') }}">
            <div class="filter-grid">
                <div class="form-group">
                    <label for="search">Cari Admin / Judul Record</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Ketik kata kunci..." value="{{ request('search') }}">
                </div>

                <div class="form-group">
                    <label for="module">Filter Modul</label>
                    <select name="module" id="module" class="form-control select2-basic">
                        <option value="">-- Semua Modul --</option>
                        @foreach($modules as $key => $label)
                            <option value="{{ $key }}" {{ request('module') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="action">Filter Aksi</label>
                    <select name="action" id="action" class="form-control select2-basic">
                        <option value="">-- Semua Aksi --</option>
                        @foreach($actions as $act)
                            <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>{{ $act }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="date">Filter Tanggal</label>
                    <input type="text" name="date" id="date" class="form-control datepicker" style="background: white;" placeholder="Pilih tanggal..." value="{{ request('date') }}">
                </div>

                <div style="display: flex; gap: 0.5rem; align-items: flex-end;">
                    <button type="submit" class="btn-solid-navy" style="white-space: nowrap;">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'module', 'action', 'date']))
                        <a href="{{ route('admin.settings.activity-logs') }}" class="btn-outline-secondary">Reset</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="table-container">
        @if($logs->count() > 0)
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 170px;">WAKTU & TANGGAL</th>
                        <th style="width: 130px;">MODUL</th>
                        <th style="width: 110px;">AKSI</th>
                        <th>RECORD / DOKUMEN</th>
                        <th style="width: 160px;">PELAKU (ADMIN)</th>
                        <th>KETERANGAN / DETAIL</th>
                        <th style="width: 120px;">IP ADDRESS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td style="color: var(--gray-700); font-weight: 500;">
                            {{ $log->created_at->format('d M Y, H:i') }} WIB
                            <div style="font-size: 0.75rem; color: var(--gray-500);">{{ $log->created_at->diffForHumans() }}</div>
                        </td>
                        <td>
                            <span class="badge-mod" style="background: {{ $log->module_color }}22; color: {{ $log->module_color }};">
                                {{ $log->module_label }}
                            </span>
                        </td>
                        <td>
                            @php
                                $actClass = 'badge-act-' . $log->action;
                                if (!in_array($log->action, ['Tambah', 'Edit', 'Hapus', 'Approve', 'Tolak'])) {
                                    $actClass = 'badge-act-Status';
                                }
                            @endphp
                            <span class="badge-action {{ $actClass }}">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td>
                            <strong style="color: var(--navy);">{{ $log->record_label ?? '-' }}</strong>
                            @if($log->record_id)
                                <span style="font-size: 0.75rem; color: var(--gray-500);"> (#{{ $log->record_id }})</span>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight: 600; color: var(--gray-900);">{{ $log->admin_name }}</div>
                        </td>
                        <td style="color: var(--gray-700);">
                            {{ $log->detail ?? '-' }}
                        </td>
                        <td style="font-size: 0.8rem; color: var(--gray-500); font-family: monospace;">
                            {{ $log->ip_address ?? '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Custom Pagination --}}
        @if($logs->hasPages())
        @php
            $currentPage = $logs->currentPage();
            $lastPage    = $logs->lastPage();
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
            <span class="pag-info">Menampilkan {{ $logs->firstItem() }}–{{ $logs->lastItem() }} dari {{ $logs->total() }} log recorded</span>
            <div class="pag-buttons">
                @if($logs->onFirstPage())
                    <span class="pag-btn pag-disabled"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></span>
                @else
                    <a href="{{ $logs->previousPageUrl() }}" class="pag-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></a>
                @endif

                @foreach($pages as $page)
                    @if($page === '...')
                        <span class="pag-ellipsis">···</span>
                    @elseif($page === $currentPage)
                        <span class="pag-btn pag-active">{{ $page }}</span>
                    @else
                        <a href="{{ $logs->url($page) }}" class="pag-btn">{{ $page }}</a>
                    @endif
                @endforeach

                @if($logs->hasMorePages())
                    <a href="{{ $logs->nextPageUrl() }}" class="pag-btn"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></a>
                @else
                    <span class="pag-btn pag-disabled"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></span>
                @endif
            </div>
        </div>
        @endif

        @else
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h4 style="font-size: 1rem; font-weight: 700; color: var(--gray-700); margin-bottom: 0.5rem;">Belum Ada Log Aktivitas</h4>
            <p style="font-size: 0.875rem;">Seluruh aktivitas perubahan data admin akan terekam secara otomatis di sini.</p>
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2-basic').select2({ minimumResultsForSearch: -1, width: '100%' });
        }

        if (typeof flatpickr !== 'undefined') {
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                allowInput: true
            });
        }
    });
</script>
@endpush
