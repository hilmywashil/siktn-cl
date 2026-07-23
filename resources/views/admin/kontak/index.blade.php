@extends('admin.layouts.admin-layout')

@section('title', 'Direktori Kontak Pengurus & Anggota')
@section('page-title', 'Direktori Kontak')

@push('styles')
<style>
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
        height: 40px; padding: 0.35rem 0.75rem; font-size: 0.8125rem; font-weight: 600;
        color: var(--navy); background-color: #fff; border: 1px solid var(--gray-300);
        border-radius: var(--radius-md); display: flex; align-items: center;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); min-width: 180px;
    }
    .select2-container--default .select2-selection--single:hover {
        border-color: var(--navy); transform: translateY(-1px); box-shadow: 0 3px 8px rgba(2, 38, 72, 0.1);
    }

    .select2-dropdown {
        border: 1px solid var(--gray-300); border-radius: var(--radius-md); font-size: 0.8125rem; z-index: 9999;
        box-shadow: 0 12px 28px rgba(2, 38, 72, 0.15); margin-top: 4px; overflow: hidden; background-color: #fff;
    }
    .select2-container--open .select2-dropdown {
        animation: select2DropdownFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .select2-container--default .select2-results__option--selectable {
        color: #111827 !important;
        transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1) !important;
        padding: 0.5rem 0.75rem !important;
    }
    .select2-results__option--highlighted[aria-selected],
    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: #022648 !important; color: #ffffff !important; font-weight: 600 !important;
        padding-left: 1.15rem !important;
    }

    .admin-ui-scope {
        --navy: #022648;
        --navy-dark: #01162f;
        --navy-light: #0a3a6b;
        --gold: #b7830f;
        --green: #059669;
        --blue: #2563eb;
        --red: #dc2626;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-500: #6b7280;
        --gray-700: #374151;
        --gray-900: #111827;
        --radius-sm: 4px;
        --radius-md: 6px;
        --radius-lg: 8px;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* Summary Stat Cards */
    .stat-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.75rem;
    }

    .stat-card {
        background: #ffffff;
        border-radius: var(--radius-lg);
        padding: 1.25rem;
        border: 1px solid var(--gray-200);
        box-shadow: 0 1px 3px rgba(2, 38, 72, 0.05);
        display: flex;
        align-items: center;
        gap: 1rem;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        background: var(--navy);
    }
    .stat-card.active::before { background: var(--green); }
    .stat-card.regional::before { background: var(--blue); }

    .stat-icon {
        width: 44px; height: 44px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        background: var(--gray-100);
        color: var(--navy);
    }

    .stat-info h4 {
        margin: 0;
        font-size: 0.75rem;
        color: var(--gray-500);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-info .value {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--navy);
        margin-top: 0.2rem;
        font-family: 'Montserrat', sans-serif;
    }

    .filter-card {
        background: white;
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        border: 1px solid var(--gray-200);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem;
        align-items: flex-end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .form-group label {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--navy);
    }

    .form-control {
        padding: 0.55rem 0.875rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--gray-300);
        font-size: 0.875rem;
        outline: none;
        background: white;
        transition: all 0.2s ease;
        width: 100%;
    }

    .form-control:focus {
        border-color: var(--navy);
        box-shadow: 0 0 0 3px rgba(2, 38, 72, 0.1);
    }

    .btn-solid-navy {
        background: var(--navy);
        color: white;
        padding: 0.55rem 1.15rem;
        border-radius: var(--radius-md);
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 4px rgba(2, 38, 72, 0.12);
    }

    .btn-solid-navy:hover {
        background: var(--navy-light);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(2, 38, 72, 0.2);
    }

    .btn-outline-secondary {
        background: white;
        color: var(--gray-700);
        padding: 0.55rem 1.15rem;
        border-radius: var(--radius-md);
        font-weight: 600;
        font-size: 0.875rem;
        border: 1px solid var(--gray-300);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-outline-secondary:hover {
        background: var(--gray-100);
        color: var(--navy);
    }

    .contact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .contact-card {
        background: white;
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        transition: transform 0.2s, box-shadow 0.2s;
        position: relative;
        border: 1px solid var(--gray-200);
    }

    .contact-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -4px rgba(2, 38, 72, 0.12);
        border-color: var(--gray-300);
    }

    .avatar-wrapper {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        overflow: hidden;
        margin-bottom: 1rem;
        border: 3px solid var(--gray-200);
        background: var(--gray-100);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .avatar-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-placeholder {
        font-size: 2rem;
        font-weight: 800;
        color: var(--navy);
        font-family: 'Montserrat', sans-serif;
    }

    .contact-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--navy);
        margin-bottom: 0.25rem;
    }

    .contact-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
        background: #eff6ff;
        color: #1e40af;
        margin-bottom: 0.75rem;
    }

    .contact-info {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: var(--gray-700);
        margin-bottom: 1.25rem;
        padding-top: 0.75rem;
        border-top: 1px solid var(--gray-100);
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: center;
        word-break: break-all;
    }

    .info-item svg {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
        color: var(--gray-500);
    }

    .contact-actions {
        width: 100%;
        display: flex;
        gap: 0.5rem;
        margin-top: auto;
    }

    .btn-wa {
        flex: 1;
        background: #25d366;
        color: white;
        padding: 0.55rem 1rem;
        border-radius: var(--radius-md);
        font-size: 0.85rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.375rem;
        transition: all 0.2s;
    }

    .btn-wa:hover {
        background: #1da851;
        color: white;
        transform: translateY(-1px);
    }

    .btn-email {
        background: var(--gray-100);
        color: var(--gray-700);
        padding: 0.55rem 0.75rem;
        border-radius: var(--radius-md);
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .btn-email:hover {
        background: var(--gray-200);
        color: var(--navy);
    }

    .empty-state {
        background: white;
        border-radius: var(--radius-lg);
        padding: 3rem;
        text-align: center;
        color: var(--gray-500);
        border: 1px solid var(--gray-200);
    }
</style>
@endpush

@section('content')
<div class="admin-ui-scope" style="padding-top: 0.5rem;">

    <!-- Stat Cards Top Benchmark -->
    <div class="stat-cards-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <div class="stat-info">
                <h4>Total Kontak Terdaftar</h4>
                <div class="value">{{ number_format($kontaks->total()) }}</div>
            </div>
        </div>

        <div class="stat-card regional">
            <div class="stat-icon" style="background: #eff6ff; color: #2563eb;">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path><path d="M2 12h20"></path></svg>
            </div>
            <div class="stat-info">
                <h4>Wilayah / Domisili</h4>
                <div class="value">{{ number_format(count($domisiliList)) }}</div>
            </div>
        </div>

        <div class="stat-card active">
            <div class="stat-icon" style="background: #ecfdf5; color: #059669;">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
            </div>
            <div class="stat-info">
                <h4>Kontak WhatsApp</h4>
                <div class="value">{{ number_format($kontaks->filter(fn($k) => !empty($k->no_hp))->count()) }}</div>
            </div>
        </div>
    </div>

    {{-- Filter Box --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.kontak.index') }}">
            <div class="filter-grid">
                <div class="form-group">
                    <label for="search">Cari Nama / Email / HP</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Ketik kata kunci..." value="{{ request('search') }}">
                </div>

                <div class="form-group">
                    <label for="domisili">Wilayah / Domisili</label>
                    <select name="domisili" id="domisili" class="form-control select2-basic">
                        <option value="">-- Semua Wilayah --</option>
                        @foreach($domisiliList as $d)
                            <option value="{{ $d }}" {{ request('domisili') == $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="jabatan">Jabatan</label>
                    <select name="jabatan" id="jabatan" class="form-control select2-basic">
                        <option value="">-- Semua Jabatan --</option>
                        @foreach($jabatanList as $j)
                            <option value="{{ $j }}" {{ request('jabatan') == $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    <button type="submit" class="btn-solid-navy">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.kontak.export', request()->query()) }}" class="btn-outline-secondary" title="Export Data Kontak ke Excel" onclick="Toast.fire({ icon: 'success', title: 'File Excel Direktori Kontak sedang diunduh...' })">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Export Excel
                    </a>
                    @if(request()->anyFilled(['search', 'domisili', 'jabatan']))
                        <a href="{{ route('admin.kontak.index') }}" class="btn-outline-secondary">Reset</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Contact Grid --}}
    @if($kontaks->count() > 0)
        <div class="contact-grid">
            @foreach($kontaks as $k)
                @php
                    // Format No. WhatsApp (ubah 08xx jadi 628xx)
                    $waNumber = preg_replace('/[^0-9]/', '', $k->no_hp ?? '');
                    if (str_starts_with($waNumber, '0')) {
                        $waNumber = '62' . substr($waNumber, 1);
                    }
                @endphp
                <div class="contact-card">
                    <div class="avatar-wrapper">
                        @if($k->foto_diri && Storage::disk('public')->exists($k->foto_diri))
                            <img src="{{ asset('storage/' . $k->foto_diri) }}" alt="{{ $k->nama_lengkap }}">
                        @else
                            <div class="avatar-placeholder">
                                {{ strtoupper(substr($k->nama_lengkap ?? 'A', 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <div class="contact-name">{{ $k->nama_lengkap ?? 'Anggota' }}</div>
                    <div class="contact-badge">{{ $k->jabatan ?? 'Anggota Karang Taruna' }}</div>

                    <div class="contact-info">
                        <div class="info-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span>{{ $k->domisili ?? 'Nasional' }}</span>
                        </div>

                        @if($k->email)
                        <div class="info-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <span>{{ $k->email }}</span>
                        </div>
                        @endif

                        @if($k->no_hp)
                        <div class="info-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <span>{{ $k->no_hp }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="contact-actions">
                        @if($waNumber)
                            <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="btn-wa">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-1.144 4.177 4.287-1.124z"/></svg>
                                WhatsApp
                            </a>
                        @endif
                        @if($k->email)
                            <a href="mailto:{{ $k->email }}" class="btn-email" title="Kirim Email">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $kontaks->links() }}
        </div>
    @else
        <div class="empty-state">
            <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 1rem; color: #94a3b8;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <h3 style="color: var(--navy); font-weight: 700; margin: 0 0 0.5rem 0;">Tidak ada data kontak ditemukan</h3>
            <p style="margin: 0; font-size: 0.875rem;">Coba ubah kata kunci pencarian atau filter domisili/jabatan.</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2-basic').select2({
                minimumResultsForSearch: -1,
                width: '100%'
            });
        }
    });
</script>
@endpush
