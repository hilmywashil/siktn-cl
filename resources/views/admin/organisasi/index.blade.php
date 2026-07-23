{{-- resources/views/admin/organisasi/index.blade.php --}}
@extends('admin.layouts.admin-layout')

@section('title', 'Kelola Organisasi')
@section('page-title', 'Kelola Organisasi')

@push('styles')
<style>
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

    /* Main Container Card */
    .content-card {
        background: white;
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        border: 1px solid var(--gray-200);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .card-header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        padding-bottom: 0.875rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .card-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--navy);
        margin: 0;
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

    /* Table Styling */
    .table-container {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        background: var(--gray-50);
        padding: 0.875rem 1rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--gray-700);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid var(--gray-200);
    }

    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--gray-100);
        font-size: 0.875rem;
        color: var(--gray-900);
        vertical-align: middle;
    }

    .avatar-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .avatar {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        object-fit: cover;
        border: 2px solid var(--gray-200);
    }

    .avatar-info h4 {
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--navy);
        margin: 0 0 0.15rem 0;
    }

    .avatar-info p {
        font-size: 0.75rem;
        color: var(--gray-500);
        margin: 0;
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.35rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .badge-status.approved { background: #d1fae5; color: #065f46; }
    .badge-status.rejected { background: #fee2e2; color: #991b1b; }

    .kategori-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-block;
        background: #eff6ff;
        color: #1e40af;
    }

    /* Action Trigger (⋮) & Floating Dropdown */
    .aksi-wrapper {
        position: relative;
        display: inline-block;
    }

    .btn-aksi-trigger {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--navy);
        color: #ffffff;
        border: none;
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 3px rgba(2, 38, 72, 0.12);
    }

    .btn-aksi-trigger:hover {
        background: var(--navy-light);
        transform: scale(1.08) translateY(-1px);
        box-shadow: 0 4px 12px rgba(2, 38, 72, 0.25);
    }

    .aksi-dropdown {
        display: block;
        position: fixed;
        min-width: 170px;
        background: #ffffff;
        border: 1px solid var(--gray-200);
        border-radius: var(--radius-md);
        box-shadow: 0 14px 32px rgba(2, 38, 72, 0.18);
        padding: 6px;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-8px) scale(0.96);
        transition: opacity 0.18s cubic-bezier(0.16, 1, 0.3, 1), transform 0.18s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.18s;
        pointer-events: none;
    }

    .aksi-dropdown.is-open {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
        pointer-events: auto;
    }

    .aksi-item {
        display: flex;
        align-items: center;
        gap: 9px;
        width: 100%;
        padding: 0.55rem 0.65rem;
        font-size: 0.8125rem;
        font-weight: 600;
        border-radius: var(--radius-sm);
        color: var(--gray-900);
        text-decoration: none !important;
        border: none;
        background: transparent;
        text-align: left;
        cursor: pointer;
        transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .aksi-item:hover {
        background: var(--gray-100);
        transform: translateX(4px);
    }

    .aksi-item.aksi-edit:hover { color: var(--navy); }
    .aksi-item.aksi-delete:hover { color: var(--red); background: #fef2f2; }

    .aksi-divider {
        height: 1px;
        background: var(--gray-200);
        margin: 4px 0;
    }

    /* Org Tree - CSS ASLI PERSIS */
    .org-tree {
        text-align: center;
        display: block;
        margin: 0 auto;
        width: max-content;
        min-width: max-content;
        padding: 1.5rem 3rem 1rem 3rem;
    }
    .org-tree ul {
        padding-top: 20px;
        position: relative;
        transition: all 0.5s;
        padding-left: 0;
        display: flex;
        justify-content: center;
        margin: 0;
    }
    .org-tree li {
        text-align: center;
        list-style-type: none;
        position: relative;
        padding: 30px 20px 0 20px;
        transition: all 0.5s;
    }
    .org-tree li::before, .org-tree li::after {
        content: '';
        position: absolute; top: 0; right: 50%;
        border-top: 2px solid #cbd5e1;
        width: 50%; height: 30px;
    }
    .org-tree li::after { right: auto; left: 50%; border-left: 2px solid #cbd5e1; }
    .org-tree li:only-child::after, .org-tree li:only-child::before { display: none; }
    .org-tree li:only-child { padding-top: 0; }
    .org-tree li:first-child::before, .org-tree li:last-child::after { border: 0 none; }
    .org-tree li:last-child::before { border-right: 2px solid #cbd5e1; border-radius: 0 5px 0 0; }
    .org-tree li:first-child::after { border-radius: 5px 0 0 0; }
    .org-tree ul ul::before {
        content: '';
        position: absolute; top: 0; left: 50%;
        border-left: 2px solid #cbd5e1;
        width: 0; height: 30px;
    }

    .org-tree-node {
        border: 2px solid #e2e8f0;
        border-top: 4px solid #0a2540;
        padding: 0.75rem 1rem 0.75rem 1rem;
        display: inline-block;
        border-radius: 8px;
        background: white;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        min-width: 160px;
        max-width: 220px;
        position: relative;
        z-index: 1;
        margin-bottom: 20px;
        text-align: center;
        vertical-align: top;
    }
    .org-tree-node.empty-node {
        border-top-color: #cbd5e1;
        opacity: 0.7;
    }
    .org-node-urutan {
        position: absolute;
        top: -10px;
        left: 50%;
        transform: translateX(-50%);
        background: #0a2540;
        color: white;
        font-size: 0.65rem;
        padding: 2px 8px;
        border-radius: 12px;
        white-space: nowrap;
        z-index: 2;
    }
    .org-node-jabatan {
        font-size: 0.8rem;
        font-weight: 700;
        color: #0a2540;
        margin-top: 0.5rem;
        margin-bottom: 0.2rem;
    }
    .org-member-mini-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.3rem;
        padding-top: 0.2rem;
    }
    .org-avatar-mini {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e2e8f0;
    }
    .org-avatar-placeholder-mini {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #0a2540;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
    }
    .org-name-mini {
        font-size: 0.85rem;
        font-weight: 700;
        color: #0a2540;
        margin: 0;
    }
    .org-jabatan-mini {
        font-size: 0.7rem;
        color: #6b7280;
        margin: 0;
    }
    .org-add-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #475569;
        font-size: 1.1rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s;
        border: 2px solid #cbd5e1;
    }
    .org-add-btn:hover { background: #0a2540; color: white; border-color: #0a2540; }

    .sibling-btn {
        position: absolute;
        right: -12px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .sibling-btn:hover { background: #0ea5e9; border-color: #0ea5e9; color: white; }

    .child-btn {
        position: absolute;
        bottom: -12px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .child-btn:hover { background: #10b981; border-color: #10b981; color: white; }

    .org-tree-wrapper {
        overflow: auto;
        max-height: 550px;
        width: 100%;
        display: block;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fafbfc;
        padding-bottom: 0.5rem;
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
                <h4>Total Pengurus</h4>
                <div class="value">{{ number_format($organisasi->count()) }}</div>
            </div>
        </div>

        <div class="stat-card active">
            <div class="stat-icon" style="background: #ecfdf5; color: #059669;">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <div class="stat-info">
                <h4>Pengurus Aktif</h4>
                <div class="value">{{ number_format($organisasi->where('aktif', true)->count()) }}</div>
            </div>
        </div>
    </div>

    <!-- Bagan Struktur Organisasi -->
    <div class="content-card" style="margin-bottom: 2rem;">
        <div class="card-header-flex">
            <div>
                <h3 class="card-title">Struktur Hirarki Organisasi</h3>
                <p style="color: var(--gray-500); font-size: 0.85rem; margin: 0.2rem 0 0 0;">
                    Bagan organisasi berdasarkan susunan jabatan. Klik tanda <strong>+</strong> pada node untuk menambah anggota.
                </p>
            </div>
        </div>
        
        <div class="org-tree-wrapper">
            @if(isset($jabatanTree) && $jabatanTree->count() > 0)
                <div class="org-tree">
                    <ul>
                        @foreach($jabatanTree as $root)
                            @include('admin.organisasi.partials.tree-node', ['node' => $root])
                        @endforeach
                    </ul>
                </div>
            @else
                <div style="text-align: center; padding: 2.5rem; color: var(--gray-500);">
                    <p>Belum ada jabatan. <a href="{{ route('admin.jabatan.index') }}" style="color: var(--navy); font-weight: 700;">Tambah Jabatan</a> terlebih dahulu.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Tabel Daftar Pengurus -->
    <div class="content-card">
        <div class="card-header-flex">
            <h3 class="card-title">Daftar Anggota Pengurus Organisasi</h3>
            @if(auth()->guard('admin')->user()->isSuperAdmin() || auth()->guard('admin')->user()->isPNKT())
            <a href="{{ route('admin.organisasi.create') }}" class="btn-solid-navy">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Tambah Anggota
            </a>
            @endif
        </div>

        <div class="table-container">
            @if($organisasi->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama & Jabatan</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            @if(auth()->guard('admin')->user()->isSuperAdmin() || auth()->guard('admin')->user()->isPNKT())
                            <th style="text-align: center; width: 80px;">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($organisasi as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="avatar-cell">
                                        <img src="{{ $item->foto_url }}" alt="{{ $item->nama }}" class="avatar">
                                        <div class="avatar-info">
                                            <h4>{{ $item->nama }}</h4>
                                            <p>{{ $item->jabatan }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="kategori-badge">{{ $item->kategori_label }}</span>
                                </td>
                                <td>
                                    <span class="badge-status {{ $item->aktif ? 'approved' : 'rejected' }}">
                                        ● {{ $item->aktif ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                @if(auth()->guard('admin')->user()->isSuperAdmin() || auth()->guard('admin')->user()->isPNKT())
                                <td style="text-align: center;">
                                    <!-- Action Dropdown Trigger (⋮) -->
                                    <div class="aksi-wrapper">
                                        <button type="button" class="btn-aksi-trigger" data-target="dropdown-org-{{ $item->id }}" aria-label="Menu Aksi">
                                            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                                                <circle cx="12" cy="5" r="1.75"></circle>
                                                <circle cx="12" cy="12" r="1.75"></circle>
                                                <circle cx="12" cy="19" r="1.75"></circle>
                                            </svg>
                                        </button>

                                        <div class="aksi-dropdown" id="dropdown-org-{{ $item->id }}">
                                            <a href="{{ route('admin.organisasi.edit', $item) }}" class="aksi-item aksi-edit">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                Edit Anggota
                                            </a>
                                            <div class="aksi-divider"></div>
                                            <button type="button" class="aksi-item aksi-delete" onclick="triggerDeleteOrg({{ $item->id }}, '{{ addslashes($item->nama) }}')">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                Hapus Anggota
                                            </button>
                                            <form id="delete-form-org-{{ $item->id }}" action="{{ route('admin.organisasi.destroy', $item) }}" method="POST" style="display:none;">
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
            @else
                <div style="text-align: center; padding: 3rem; color: var(--gray-500);">
                    <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 1rem; opacity: 0.5;">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <h3 style="margin: 0 0 0.5rem 0; color: var(--navy); font-weight: 700;">Belum ada data pengurus</h3>
                    <p style="margin: 0; font-size: 0.875rem;">Mulai tambahkan anggota struktur organisasi Karang Taruna.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let activeDropdown = null;

        // Position & Toggle Dropdown Trigger (⋮)
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
                    dropdown.style.left = (rect.right - 170) + 'px';
                    dropdown.classList.add('is-open');
                    activeDropdown = dropdown;
                }
            });
        });

        // Close dropdown on click outside
        document.addEventListener('click', function () {
            if (activeDropdown) {
                activeDropdown.classList.remove('is-open');
                activeDropdown = null;
            }
        });

        // Close dropdown on scroll
        window.addEventListener('scroll', function () {
            if (activeDropdown) {
                activeDropdown.classList.remove('is-open');
                activeDropdown = null;
            }
        }, true);
    });

    function triggerDeleteOrg(id, name) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus Anggota Pengurus?',
                text: `Apakah Anda yakin ingin menghapus ${name} dari struktur organisasi?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#f3f4f6',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-org-' + id).submit();
                }
            });
        } else if (confirm(`Yakin ingin menghapus ${name} dari struktur organisasi?`)) {
            document.getElementById('delete-form-org-' + id).submit();
        }
    }
</script>
@endpush