{{-- resources/views/admin/components/sidebar.blade.php --}}
@props(['admin', 'activeMenu' => 'dashboard'])

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="brand">
            <div class="brand-logo">
                <img src="{{ asset('assets-front/images/logo_karang_taruna.png') }}" alt="Karang Taruna Logo" width="75" height="75" style="object-fit: contain;">
            </div>
            <div class="brand-text" style="margin-left: 20px;">
                <div class="brand-title">Karang Taruna</div>
                <div class="brand-subtitle">ALUMNI</div>
            </div>
        </div>
    </div>

    <div class="sidebar-menu">
        <div class="menu-section">
            <div class="menu-label">Menu Utama</div>

            {{-- Dashboard Dropdown --}}
            <div class="menu-dropdown">
                <div class="menu-item has-dropdown {{ in_array($activeMenu, ['dashboard', 'info-admin', 'editor', 'anggota', 'list-anggota', 'jabatan']) ? 'active' : '' }}" onclick="toggleDropdown(this)">
                    <div style="display: flex; align-items: center; gap: 1rem; flex: 1;">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="7" rx="1" />
                            <rect x="14" y="3" width="7" height="7" rx="1" />
                            <rect x="3" y="14" width="7" height="7" rx="1" />
                            <rect x="14" y="14" width="7" height="7" rx="1" />
                        </svg>
                        <span>Dashboard</span>
                    </div>
                    <svg class="dropdown-icon" viewBox="0 0 24 24">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </div>
                <div class="submenu {{ in_array($activeMenu, ['dashboard', 'info-admin', 'editor', 'anggota', 'list-anggota', 'jabatan']) ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="submenu-item {{ $activeMenu === 'dashboard' ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <polyline points="9 11 12 14 22 4" />
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
                        </svg>
                        <span>Overview</span>
                    </a>

                    {{-- Menu Manajemen Anggota --}}
                    @if($admin->canApproveAnggota() || $admin->isSuperAdmin())
                    <a href="{{ route('admin.anggota.list') }}" class="submenu-item {{ in_array($activeMenu, ['anggota', 'list-anggota']) ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="16" y1="13" x2="8" y2="13" />
                            <line x1="16" y1="17" x2="8" y2="17" />
                            <polyline points="10 9 9 9 8 9" />
                        </svg>
                        <span>Kelola Anggota</span>
                    </a>
                    @endif

                    {{-- Menu Kelola Admin --}}
                    @if($admin->canManageAdmins())
                    <a href="{{ route('admin.info-admin') }}" class="submenu-item {{ $activeMenu === 'info-admin' ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="8.5" cy="7" r="4" />
                            <polyline points="17 11 19 13 23 9" />
                        </svg>
                        <span>Kelola Admin</span>
                    </a>
                    @endif
                    {{-- Menu Kelola Jabatan (Dipindah ke bawah Organisasi) --}}
                </div>
            </div>
        </div>

        <div class="menu-section">
            <div class="menu-label">Halaman Website</div>

            {{-- Beranda Dropdown --}}
            <div class="menu-dropdown">
                <div class="menu-item has-dropdown {{ in_array($activeMenu, ['beranda', 'misi']) ? 'active' : '' }}" onclick="toggleDropdown(this)">
                    <div style="display: flex; align-items: center; gap: 1rem; flex: 1;">
                        <svg viewBox="0 0 24 24">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                            <polyline points="9 22 9 12 15 12 15 22" />
                        </svg>
                        <span>Beranda</span>
                    </div>
                    <svg class="dropdown-icon" viewBox="0 0 24 24">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </div>
                <div class="submenu {{ in_array($activeMenu, ['beranda', 'misi']) ? 'active' : '' }}">
                    <a href="{{ route('home') }}" class="submenu-item" target="_blank">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="2" y1="12" x2="22" y2="12" />
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                        </svg>
                        <span>Lihat Halaman</span>
                    </a>
                    <!--@if($admin->canManageContent())-->
                    <!--<a href="{{ route('admin.misi.index') }}" class="submenu-item {{ $activeMenu === 'misi' ? 'active' : '' }}">-->
                    <!--    <svg viewBox="0 0 24 24">-->
                    <!--        <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />-->
                    <!--        <polyline points="14 2 14 8 20 8" />-->
                    <!--        <line x1="9" y1="15" x2="15" y2="15" />-->
                    <!--        <line x1="9" y1="11" x2="15" y2="11" />-->
                    <!--    </svg>-->
                    <!--    <span>Kelola Misi</span>-->
                    <!--</a>-->
                    <!--@endif-->
                    <!--@if($admin->canManageContent())-->
                    <!--<a href="{{ route('admin.strategic-plan.index') }}" class="submenu-item {{ $activeMenu === 'strategic-plan' ? 'active' : '' }}">-->
                    <!--    <svg viewBox="0 0 24 24">-->
                    <!--        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />-->
                    <!--        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />-->
                    <!--    </svg>-->
                    <!--    <span>Kelola Section Strategic & Plan</span>-->
                    <!--</a>-->
                    <!--@endif-->
                </div>
            </div>

            {{-- Organisasi Dropdown --}}
            <div class="menu-dropdown">
                <div class="menu-item has-dropdown {{ in_array($activeMenu, ['organisasi', 'jabatan']) ? 'active' : '' }}" onclick="toggleDropdown(this)">
                    <div style="display: flex; align-items: center; gap: 1rem; flex: 1;">
                        <svg viewBox="0 0 24 24">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                        <span>Organisasi</span>
                    </div>
                    <svg class="dropdown-icon" viewBox="0 0 24 24">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </div>
                <div class="submenu {{ in_array($activeMenu, ['organisasi', 'jabatan']) ? 'active' : '' }}">
                    <a href="{{ route('organisasi') }}" class="submenu-item" target="_blank">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="2" y1="12" x2="22" y2="12" />
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                        </svg>
                        <span>Lihat Halaman</span>
                    </a>
                    @if($admin->isSuperAdmin() || $admin->isPNKT())
                    <a href="{{ route('admin.organisasi.index') }}" class="submenu-item {{ $activeMenu === 'organisasi' ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                        <span>Kelola Organisasi</span>
                    </a>
                    
                    {{-- Menu Kelola Jabatan --}}
                    <a href="{{ route('admin.jabatan.index') }}" class="submenu-item {{ $activeMenu === 'jabatan' ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="8" y1="6" x2="21" y2="6"></line>
                            <line x1="8" y1="12" x2="21" y2="12"></line>
                            <line x1="8" y1="18" x2="21" y2="18"></line>
                            <line x1="3" y1="6" x2="3.01" y2="6"></line>
                            <line x1="3" y1="12" x2="3.01" y2="12"></line>
                            <line x1="3" y1="18" x2="3.01" y2="18"></line>
                        </svg>
                        <span>Kelola Jabatan</span>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Program Dropdown --}}
            <div class="menu-dropdown">
                <div class="menu-item has-dropdown {{ in_array($activeMenu, ['program', 'program_settings']) ? 'active' : '' }}" onclick="toggleDropdown(this)">
                    <div style="display: flex; align-items: center; gap: 1rem; flex: 1;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <span>Program</span>
                    </div>
                    <svg class="dropdown-icon" viewBox="0 0 24 24">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </div>
                <div class="submenu {{ in_array($activeMenu, ['program', 'program_settings']) ? 'active' : '' }}">
                    <a href="{{ route('program.csr') }}" class="submenu-item" target="_blank">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="2" y1="12" x2="22" y2="12" />
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                        </svg>
                        <span>Lihat CSR</span>
                    </a>
                    <a href="{{ route('program.bidang') }}" class="submenu-item" target="_blank">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="2" y1="12" x2="22" y2="12" />
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                        </svg>
                        <span>Lihat Bidang</span>
                    </a>
                    @if($admin->isSuperAdmin() || $admin->isPNKT())
                    <a href="{{ route('admin.program.index') }}" class="submenu-item {{ $activeMenu === 'program' ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                        <span>Kelola Program</span>
                    </a>
                    <a href="{{ route('admin.program.settings') }}" class="submenu-item {{ $activeMenu === 'program_settings' ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                        <span>Pengaturan Halaman</span>
                    </a>
                    @endif
                </div>
            </div>

            {{-- E-Katalog Dropdown --}}
            <div class="menu-dropdown">
                <div class="menu-item has-dropdown {{ $activeMenu === 'katalog' ? 'active' : '' }}" onclick="toggleDropdown(this)">
                    <div style="display: flex; align-items: center; gap: 1rem; flex: 1;">
                        <svg viewBox="0 0 24 24">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                        </svg>
                        <span>E-Katalog</span>
                    </div>
                    <svg class="dropdown-icon" viewBox="0 0 24 24">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </div>
                <div class="submenu {{ $activeMenu === 'katalog' ? 'active' : '' }}">
                    <a href="{{ route('e-katalog') }}" class="submenu-item" target="_blank">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="2" y1="12" x2="22" y2="12" />
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                        </svg>
                        <span>Lihat Halaman</span>
                    </a>
                    @if($admin->canManageContent())
                    <a href="{{ route('admin.katalog.index') }}" class="submenu-item {{ $activeMenu === 'katalog' ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                        <span>Kelola Data</span>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Berita Dropdown --}}
            <div class="menu-dropdown">
                <div class="menu-item has-dropdown {{ $activeMenu === 'berita' ? 'active' : '' }}" onclick="toggleDropdown(this)">
                    <div style="display: flex; align-items: center; gap: 1rem; flex: 1;">
                        <svg viewBox="0 0 24 24">
                            <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z" />
                            <polyline points="13 2 13 9 20 9" />
                        </svg>
                        <span>Berita</span>
                    </div>
                    <svg class="dropdown-icon" viewBox="0 0 24 24">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </div>
                <div class="submenu {{ $activeMenu === 'berita' ? 'active' : '' }}">
                    <a href="{{ route('berita') }}" class="submenu-item" target="_blank">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="2" y1="12" x2="22" y2="12" />
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                        </svg>
                        <span>Lihat Halaman</span>
                    </a>
                    @if($admin->canManageContent())
                    <a href="{{ route('admin.berita.index') }}" class="submenu-item {{ $activeMenu === 'berita' ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                        <span>Kelola Data</span>
                    </a>
                    @endif
                </div>
            </div>

    
            @if($admin->hasRole('super_admin'))
            <div class="menu-dropdown">
                <a href="{{ route('admin.trash.index') }}" class="menu-item {{ $activeMenu === 'trash' ? 'active' : '' }}" style="text-decoration: none;">
                    <div style="display: flex; align-items: center; gap: 1rem; flex: 1;">
                        <svg viewBox="0 0 24 24" style="color: #ef4444;">
                            <polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        <span style="color: #ef4444;">Data Terhapus</span>
                    </div>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Mobile Menu Toggle --}}
<button class="mobile-menu-toggle" id="mobileMenuToggle">
    <svg viewBox="0 0 24 24">
        <line x1="3" y1="12" x2="21" y2="12" />
        <line x1="3" y1="6" x2="21" y2="6" />
        <line x1="3" y1="18" x2="21" y2="18" />
    </svg>
</button>

<div class="mobile-overlay" id="mobileOverlay"></div>

@once
@push('scripts')
<script>
    const sidebar = document.getElementById('sidebar');
    const mobileToggle = document.getElementById('mobileMenuToggle');
    const mobileOverlay = document.getElementById('mobileOverlay');

    if (mobileToggle && mobileOverlay) {
        mobileToggle.addEventListener('click', () => {
            sidebar.classList.toggle('mobile-active');
            mobileOverlay.classList.toggle('active');
        });

        mobileOverlay.addEventListener('click', () => {
            sidebar.classList.remove('mobile-active');
            mobileOverlay.classList.remove('active');
        });
    }

    function toggleDropdown(element) {
        element.classList.toggle('active');
        const submenu = element.nextElementSibling;
        submenu.classList.toggle('active');
    }
</script>
@endpush
@endonce