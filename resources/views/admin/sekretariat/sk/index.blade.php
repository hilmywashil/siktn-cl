@extends('admin.layouts.admin-layout')

@section('title', 'Surat Keputusan (SK) - SIKTN Admin')
@section('page-title', 'Sekretariat - Surat Keputusan (SK)')

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

    /* Alert Banner */
    .alert-warning-custom {
        background: #fffbeb; border: 1px solid #fde68a; color: #92400e;
        padding: 1rem 1.25rem; border-radius: var(--radius-lg); margin-bottom: 1.5rem;
        display: flex; align-items: center; gap: 12px;
    }

    /* Stat Cards Benchmark */
    .stat-cards-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem; margin-bottom: 1.75rem;
    }
    .stat-card {
        background: #fff; border-radius: var(--radius-lg); padding: 1.25rem;
        border: 1px solid var(--gray-200); box-shadow: 0 1px 3px rgba(2,38,72,0.05);
        display: flex; align-items: center; gap: 1rem; position: relative; overflow: hidden;
    }
    .stat-card::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0;
        width: 4px; background: var(--navy);
    }
    .stat-card.active-card::before  { background: var(--green); }
    .stat-card.warning-card::before { background: var(--amber); }
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

    /* Buttons Benchmark */
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
        display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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

    /* Page Actions Row */
    .page-actions-row {
        display: flex; justify-content: flex-end; margin-bottom: 1.25rem; gap: 0.75rem;
    }

    /* Table Container */
    .table-container { background: white; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden; }
    .table-wrapper { overflow-x: auto; }
    .table { width: 100%; border-collapse: collapse; min-width: 860px; }
    .table thead { background: var(--gray-50); border-bottom: 1px solid var(--gray-200); }
    .table th { padding: 0.875rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: var(--gray-700); text-transform: uppercase; letter-spacing: 0.05em; }
    .table td { padding: 1rem; border-bottom: 1px solid var(--gray-100); font-size: 0.875rem; color: var(--gray-900); vertical-align: middle; }
    .table tbody tr:hover { background: var(--gray-50); }

    /* Status Badges */
    .status-badge { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.35rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; white-space: nowrap; }
    .status-badge.approved { background: #d1fae5; color: #065f46; }
    .status-badge.rejected { background: #fee2e2; color: #991b1b; }

    /* Action Trigger (⋮) */
    .aksi-wrapper { position: relative; display: inline-block; }
    .btn-aksi-trigger {
        width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;
        background: var(--navy); color: #ffffff; border: none; border-radius: var(--radius-md);
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
    .aksi-item.aksi-edit:hover { color: var(--blue); }
    .aksi-item.aksi-delete:hover { color: var(--red); background: #fef2f2; }
    .aksi-divider { height: 1px; background: var(--gray-200); margin: 4px 0; }

    /* Modal */
    .modal-overlay {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15,23,42,0.6); display: none;
        align-items: center; justify-content: center; z-index: 9999; padding: 1rem;
    }
    .modal-overlay.active { display: flex; }
    .modal-content {
        background: white; border-radius: var(--radius-lg); max-width: 550px; width: 100%;
        padding: 1.5rem; max-height: 90vh; overflow-y: auto;
    }
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
                <h4>Total SK Terbit</h4>
                <div class="value">{{ $sks->total() }}</div>
            </div>
        </div>

        <div class="stat-card active-card">
            <div class="stat-icon" style="background: #ecfdf5; color: #059669;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <div class="stat-info">
                <h4>SK Status Aktif</h4>
                <div class="value">{{ $sks->where('status', 'Aktif')->count() }}</div>
            </div>
        </div>

        @if(count($expiringSks) > 0)
        <div class="stat-card warning-card">
            <div class="stat-icon" style="background: #fffbeb; color: #d97706;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            </div>
            <div class="stat-info">
                <h4>Mendekati Kedaluwarsa (H-180)</h4>
                <div class="value" style="color: var(--amber);">{{ count($expiringSks) }}</div>
            </div>
        </div>
        @endif
    </div>

    <!-- Alert Banner Pengingat -->
    @if(count($expiringSks) > 0)
    <div class="alert-warning-custom">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <div>
            <strong>Perhatian (Pemberitahuan Sistem H-180 / 6 Bulan):</strong>
            Terdapat <strong>{{ count($expiringSks) }} Surat Keputusan (SK)</strong> yang masa berlakunya akan berakhir kurang dari 6 bulan lagi. Silakan periksa daftar SK di bawah.
        </div>
    </div>
    @endif

    <!-- Filter Box -->
    <div class="filter-card">
        <form action="{{ route('admin.sekretariat.sk.index') }}" method="GET">
            <div class="filter-grid">
                <div class="form-group">
                    <label for="search">Cari No. SK / Judul</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Ketik kata kunci..." value="{{ request('search') }}">
                </div>

                <div class="form-group">
                    <label for="status">Status SK</label>
                    <select name="status" id="status" class="form-control select2-basic">
                        <option value="">-- Semua Status --</option>
                        <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Tidak Aktif" {{ request('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                <div style="display: flex; gap: 0.5rem; align-items: flex-end;">
                    <button type="submit" class="btn-solid-navy" style="white-space: nowrap;">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Filter
                    </button>
                    @if(request()->anyFilled(['status', 'search']))
                        <a href="{{ route('admin.sekretariat.sk.index') }}" class="btn-outline-secondary">Reset</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Page Action -->
    <div class="page-actions-row">
        <button type="button" class="btn-solid-navy" onclick="openCreateModal()">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Tambah SK Baru
        </button>
    </div>

    <!-- Table Container -->
    <div class="table-container">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>NO. SK</th>
                        <th>JUDUL SURAT KEPUTUSAN</th>
                        <th>TANGGAL BERLAKU</th>
                        <th>TANGGAL BERAKHIR</th>
                        <th>STATUS</th>
                        <th>DOKUMEN (DRIVE)</th>
                        <th style="text-align: center; width: 80px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sks as $item)
                    @php
                        $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($item->tanggal_berakhir), false);
                        $isNearExpiring = $item->status == 'Aktif' && $daysLeft >= 0 && $daysLeft <= 180;
                    @endphp
                    <tr style="{{ $isNearExpiring ? 'background: #fffbeb;' : '' }}">
                        <td>
                            <strong style="color: var(--navy);">{{ $item->nomor_sk }}</strong>
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $item->judul_sk }}</div>
                            @if($item->keterangan)
                                <div style="font-size: 0.8rem; color: var(--gray-500);">{{ $item->keterangan }}</div>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_berlaku)->format('d M Y') }}</td>
                        <td>
                            <div>{{ \Carbon\Carbon::parse($item->tanggal_berakhir)->format('d M Y') }}</div>
                            @if($isNearExpiring)
                                <span style="font-size: 0.75rem; color: var(--amber); font-weight: 700;">({{ $daysLeft }} hari lagi)</span>
                            @endif
                        </td>
                        <td>
                            @if($item->status == 'Aktif')
                                <span class="status-badge approved">● Aktif</span>
                            @else
                                <span class="status-badge rejected">● Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            @if($item->link_drive)
                                <a href="{{ $item->link_drive }}" target="_blank" style="color: var(--blue); text-decoration: none; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 4px;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                    Buka Drive
                                </a>
                            @else
                                <span style="color: var(--gray-500); font-size: 0.8rem;">-</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <!-- Action Dropdown Trigger (⋮) -->
                            <div class="aksi-wrapper">
                                <button type="button" class="btn-aksi-trigger" data-target="dropdown-sk-{{ $item->id }}" aria-label="Menu Aksi">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                        <circle cx="12" cy="5" r="1.75"></circle>
                                        <circle cx="12" cy="12" r="1.75"></circle>
                                        <circle cx="12" cy="19" r="1.75"></circle>
                                    </svg>
                                </button>

                                <div class="aksi-dropdown" id="dropdown-sk-{{ $item->id }}">
                                    <button type="button" class="aksi-item aksi-edit" onclick="openEditModal({{ json_encode($item) }})">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        Edit SK
                                    </button>

                                    <div class="aksi-divider"></div>

                                    <button type="button" class="aksi-item aksi-delete" onclick="confirmDeleteSk({{ $item->id }}, '{{ addslashes($item->nomor_sk) }}')">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        Hapus SK
                                    </button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('admin.sekretariat.sk.destroy', $item->id) }}" method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 3rem; color: var(--gray-500);">Belum ada data Surat Keputusan (SK).</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top: 1rem;">
        {{ $sks->links() }}
    </div>

</div>

<!-- Create Modal -->
<div class="modal-overlay" id="createModal">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="color: var(--navy); margin: 0; font-weight: 700;">Tambah Surat Keputusan (SK) Baru</h3>
            <button type="button" onclick="closeCreateModal()" style="background: none; border: none; font-size: 1.25rem; cursor: pointer; color: var(--gray-500);">&times;</button>
        </div>

        <form action="{{ route('admin.sekretariat.sk.store') }}" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom: 1rem;">
                <label>Nomor SK</label>
                <input type="text" name="nomor_sk" class="form-control" placeholder="Contoh: SK/005/PNKT/2026" required>
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label>Judul Surat Keputusan</label>
                <input type="text" name="judul_sk" class="form-control" placeholder="Judul penetapan SK..." required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label>Tanggal Berlaku</label>
                    <input type="text" name="tanggal_berlaku" class="form-control datepicker" style="background: white;" value="{{ date('Y-m-d') }}" placeholder="Pilih tanggal..." required>
                </div>
                <div class="form-group">
                    <label>Tanggal Berakhir</label>
                    <input type="text" name="tanggal_berakhir" class="form-control datepicker" style="background: white;" placeholder="Pilih tanggal berakhir..." required>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label>Status SK</label>
                <select name="status" class="form-control select2-basic" style="width: 100%;" required>
                    <option value="Aktif" selected>Aktif</option>
                    <option value="Tidak Aktif">Tidak Aktif</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label>Link Tautan Dokumen (Google Drive)</label>
                <input type="url" name="link_drive" class="form-control" placeholder="https://drive.google.com/file/d/...">
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label>Keterangan Tambahan</label>
                <textarea name="keterangan" class="form-control" style="height: 70px;" placeholder="Catatan tambahan..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                <button type="button" onclick="closeCreateModal()" class="btn-outline-secondary">Batal</button>
                <button type="submit" class="btn-solid-navy" onclick="if(typeof Toast !== 'undefined') Toast.fire({ icon: 'success', title: 'Surat Keputusan (SK) sedang disimpan...' })">Simpan SK</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editModal">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="color: var(--navy); margin: 0; font-weight: 700;">Edit Surat Keputusan (SK)</h3>
            <button type="button" onclick="closeEditModal()" style="background: none; border: none; font-size: 1.25rem; cursor: pointer; color: var(--gray-500);">&times;</button>
        </div>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group" style="margin-bottom: 1rem;">
                <label>Nomor SK</label>
                <input type="text" name="nomor_sk" id="editNomorSk" class="form-control" required>
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label>Judul Surat Keputusan</label>
                <input type="text" name="judul_sk" id="editJudulSk" class="form-control" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label>Tanggal Berlaku</label>
                    <input type="text" name="tanggal_berlaku" id="editTanggalBerlaku" class="form-control datepicker" style="background: white;" required>
                </div>
                <div class="form-group">
                    <label>Tanggal Berakhir</label>
                    <input type="text" name="tanggal_berakhir" id="editTanggalBerakhir" class="form-control datepicker" style="background: white;" required>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label>Status SK</label>
                <select name="status" id="editStatus" class="form-control select2-basic" style="width: 100%;" required>
                    <option value="Aktif">Aktif</option>
                    <option value="Tidak Aktif">Tidak Aktif</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 1rem;">
                <label>Link Tautan Dokumen (Google Drive)</label>
                <input type="url" name="link_drive" id="editLinkDrive" class="form-control">
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label>Keterangan Tambahan</label>
                <textarea name="keterangan" id="editKeterangan" class="form-control" style="height: 70px;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                <button type="button" onclick="closeEditModal()" class="btn-outline-secondary">Batal</button>
                <button type="submit" class="btn-solid-navy" onclick="if(typeof Toast !== 'undefined') Toast.fire({ icon: 'success', title: 'Surat Keputusan (SK) berhasil diperbarui...' })">Update SK</button>
            </div>
        </form>
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
            flatpickr(".datepicker", { dateFormat: "Y-m-d", allowInput: true });
        }

        let activeDropdown = null;

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
                    dropdown.style.top = (rect.bottom + 4) + 'px';
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
    });

    function openCreateModal() {
        document.getElementById('createModal').classList.add('active');
        if (typeof $.fn.select2 !== 'undefined') {
            $('#createModal .select2-basic').select2({ width: '100%', dropdownParent: $('#createModal') });
        }
    }
    function closeCreateModal() { document.getElementById('createModal').classList.remove('active'); }

    function openEditModal(item) {
        document.getElementById('editForm').action = "/admin/sekretariat/sk/" + item.id;
        document.getElementById('editNomorSk').value = item.nomor_sk;
        document.getElementById('editJudulSk').value = item.judul_sk;
        document.getElementById('editTanggalBerlaku').value = item.tanggal_berlaku;
        document.getElementById('editTanggalBerakhir').value = item.tanggal_berakhir;
        document.getElementById('editStatus').value = item.status;
        document.getElementById('editLinkDrive').value = item.link_drive || '';
        document.getElementById('editKeterangan').value = item.keterangan || '';
        document.getElementById('editModal').classList.add('active');
        if (typeof $.fn.select2 !== 'undefined') {
            $('#editModal .select2-basic').select2({ width: '100%', dropdownParent: $('#editModal') });
        }
    }
    function closeEditModal() { document.getElementById('editModal').classList.remove('active'); }

    function confirmDeleteSk(id, nomor) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus SK?',
                text: `Apakah Anda yakin ingin menghapus SK no. ${nomor}?`,
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
        } else if (confirm(`Apakah Anda yakin ingin menghapus SK no. ${nomor}?`)) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
