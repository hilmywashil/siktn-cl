<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Anggota</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: #f5f6fa;
        font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    .profile-container {
        display: grid;
        grid-template-columns: 300px 1fr;
        min-height: 100vh;
        gap: 0;
    }

    /* ============= SIDEBAR ============= */
    .sidebar {
        background: linear-gradient(180deg, #2a348d 0%, #1a1f5c 100%);
        padding: 0;
        position: sticky;
        top: 0;
        height: 100vh;
        overflow-y: auto;
        box-shadow: 4px 0 20px rgba(42, 52, 141, 0.15);
        display: flex;
        flex-direction: column;
    }

    .sidebar::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 2px;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }



    /* ============= NAV LINK ============= */
    .nav-link-home {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 12px 24px;
        margin: 0 24px 12px 24px;
        background: rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        border-radius: 2px;
        transition: all 0.3s;
        border: 1px solid rgba(255, 255, 255, 0.2);
        letter-spacing: 0.3px;
    }

    .nav-link-home:hover {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.3);
        color: #ffffff;
    }

    .nav-link-home svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        stroke-width: 2;
        fill: none;
    }

    /* ============= MENU ============= */
    .sidebar-menu {
        list-style: none;
        padding: 32px 0;
        flex: 1;
    }

    .sidebar-menu-item {
        margin-bottom: 4px;
    }

    .sidebar-menu-link {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 24px;
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        background: transparent;
        width: 100%;
        text-align: left;
        position: relative;
        border-left: 3px solid transparent;
        letter-spacing: 0.3px;
    }

    .sidebar-menu-link::after {
        content: '';
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-top: 6px solid transparent;
        border-bottom: 6px solid transparent;
        border-right: 6px solid #f5f6fa;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .sidebar-menu-link:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #ffffff;
    }

    .sidebar-menu-link.active {
        background: rgba(255, 255, 255, 0.12);
        color: #ffffff;
        border-left-color: #fbbf24;
    }

    .sidebar-menu-link.active::after {
        opacity: 1;
    }

    .sidebar-menu-link svg {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
        stroke: currentColor;
        stroke-width: 2;
        fill: none;
    }

    .sidebar-footer {
        padding: 24px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        margin-top: auto;
    }

    .btn-logout {
        width: 100%;
        padding: 12px;
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 2px;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .btn-logout:hover {
        background: rgba(239, 68, 68, 0.25);
        border-color: #ef4444;
    }

    /* ============= MAIN CONTENT ============= */
    .main-content {
        padding: 0;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        background: #f5f6fa;
    }

    .content-wrapper {
        padding: 40px 48px;
        flex: 1;
    }

    .section {
        background: white;
        border-radius: 2px;
        padding: 36px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        border: 1px solid #e5e7eb;
        margin-bottom: 24px;
        display: none;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .section.active {
        display: block;
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 28px;
        margin-bottom: 24px;
    }

    .field {
        display: flex;
        flex-direction: column;
    }

    .field label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 10px;
        letter-spacing: 0.8px;
    }

    .field div {
        font-size: 15px;
        color: #1e293b;
        line-height: 1.6;
        word-break: break-word;
        font-weight: 500;
    }

    .field div.empty {
        color: #9ca3af;
        font-style: italic;
    }

    /* ============= DOCUMENTS ============= */
    .doc-box {
        border: 1px solid #e5e7eb;
        border-radius: 2px;
        overflow: hidden;
        background: #fafafa;
        margin-bottom: 24px;
        transition: all 0.3s;
    }

    .doc-box:hover {
        border-color: #2a348d;
        box-shadow: 0 4px 16px rgba(42, 52, 141, 0.1);
    }

    .doc-box label {
        font-size: 13px;
        font-weight: 700;
        color: #2a348d;
        display: block;
        padding: 16px 20px;
        background: #f8f9fa;
        border-bottom: 1px solid #e5e7eb;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .doc-box img {
        width: 100%;
        height: 300px;
        object-fit: cover;
        display: block;
        background: #f1f5f9;
    }

    .pdf-preview {
        width: 100%;
        height: 420px;
        border: none;
        display: block;
        background: #525659;
    }

    .doc-actions {
        padding: 16px;
        background: #fafafa;
        border-top: 1px solid #e5e7eb;
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 20px;
        background: #2a348d;
        color: white;
        border-radius: 2px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .btn-action:hover {
        background: #1a1f5c;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(42, 52, 141, 0.3);
    }

    .btn-action.secondary {
        background: transparent;
        color: #2a348d;
        border: 1px solid #2a348d;
    }

    .btn-action.secondary:hover {
        background: #2a348d;
        color: white;
    }

    /* ============= PRODUCTS ============= */
    .produk-list {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .produk-item {
        padding: 10px 18px;
        background: #ffffff;
        color: #2a348d;
        border-radius: 2px;
        font-size: 13px;
        font-weight: 600;
        border: 1px solid #2a348d;
        transition: all 0.3s;
        letter-spacing: 0.3px;
    }

    .produk-item:hover {
        background: #2a348d;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(42, 52, 141, 0.2);
    }

    .section-box {
        background: #f8f9fa;
        padding: 28px;
        border-radius: 2px;
        margin-bottom: 32px;
        border-left: 4px solid #2a348d;
    }

    .section-box h2 {
        font-size: 16px;
        font-weight: 700;
        color: #2a348d;
        margin-bottom: 24px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* ============= RESPONSIVE ============= */
    @media (max-width: 1024px) {
        .profile-container {
            grid-template-columns: 280px 1fr;
        }

        .content-wrapper {
            padding: 32px 36px;
        }

        .section {
            padding: 28px;
        }

        .grid {
            grid-template-columns: 1fr;
        }
    }

            @media (max-width: 768px) {
        .profile-container {
            grid-template-columns: 1fr;
        }

        .sidebar {
            height: auto;
            position: relative;
            border-right: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0;
        }

        .nav-link-home {
            margin: 0 16px 12px 16px !important;
            padding: 12px 20px;
            font-size: 12px;
        }

        .sidebar-menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            padding: 16px 12px;
            gap: 4px;
        }

        .sidebar-menu-link {
            padding: 12px 16px;
            font-size: 13px;
            justify-content: center;
            text-align: center;
            border-left: none;
            border-bottom: 2px solid transparent;
        }

        .sidebar-menu-link.active {
            border-left: none;
            border-bottom-color: #fbbf24;
        }

        .sidebar-menu-link::after {
            display: none;
        }

        .sidebar-menu-link svg {
            width: 18px;
            height: 18px;
        }

        .sidebar-footer {
            padding: 16px;
        }

        .btn-logout {
            padding: 11px;
            font-size: 12px;
        }

        .content-wrapper {
            padding: 20px 16px;
        }

        .section {
            padding: 20px;
            margin-bottom: 16px;
        }

        .section-box {
            padding: 20px;
            margin-bottom: 20px;
        }

        .grid {
            gap: 20px;
        }

        .doc-box img {
            height: 220px;
        }

        .pdf-preview {
            height: 320px;
        }
    }

    @media (max-width: 480px) {
        .sidebar-menu {
            grid-template-columns: repeat(2, 1fr);
        }

        .grid {
            grid-template-columns: 1fr;
        }

        .btn-action {
            flex: 1;
            justify-content: center;
        }
    }
</style>

<div class="profile-container">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item">
                <button class="sidebar-menu-link active" onclick="showSection('informasi-umum', event)">
                    <svg viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <span>Informasi Umum</span>
                </button>
            </li>

            <li class="sidebar-menu-item">
                <a href="{{ route('anggota.katalog.index') }}" class="sidebar-menu-link">
                    <svg viewBox="0 0 24 24">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                    </svg>
                    <span>E-Katalog</span>
                </a>
            </li>

            <li class="sidebar-menu-item">
                <button class="sidebar-menu-link" onclick="showSection('dokumen', event)">
                    <svg viewBox="0 0 24 24">
                        <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                        <polyline points="13 2 13 9 20 9"></polyline>
                    </svg>
                    <span>Dokumen</span>
                </button>
            </li>
        </ul>

        <div class="sidebar-footer">
            <a href="{{ route('home') }}" class="nav-link-home">
                <svg viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                <span>Beranda</span>
            </a>
            <form action="{{ route('anggota.logout') }}" method="POST" style="width: 100%;">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="content-wrapper">
            <!-- INFORMASI UMUM -->
            <div id="informasi-umum" class="section active">
                <!-- Welcome Banner -->
                <div style="background: linear-gradient(135deg, #2a348d 0%, #1a1f5c 100%); padding: 32px 40px; border-radius: 4px; margin-bottom: 32px; box-shadow: 0 4px 20px rgba(42, 52, 141, 0.2); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                    <div>
                        <h1 style="font-size: 28px; font-weight: 700; color: #ffffff; margin-bottom: 8px; letter-spacing: -0.3px;">
                            Selamat Datang, {{ $anggota->nama_pimpinan }}
                        </h1>
                        <p style="font-size: 16px; color: rgba(255, 255, 255, 0.85); font-weight: 500; letter-spacing: 0.2px;">
                            Perusahaan {{ $anggota->nama_perusahaan }}
                        </p>
                    </div>
                    <div>
                        <span style="display: inline-block; padding: 10px 24px; border-radius: 4px; font-size: 12px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; 
                        @if($anggota->status == 'pending')
                            background: rgba(251, 191, 36, 0.2); color: #fbbf24; border: 2px solid #fbbf24;
                        @elseif($anggota->status == 'approved')
                            background: rgba(34, 197, 94, 0.2); color: #22c55e; border: 2px solid #22c55e;
                        @else
                            background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 2px solid #ef4444;
                        @endif
                        ">
                            {{ ucfirst($anggota->status) }}
                        </span>
                    </div>
                </div>

                <!-- DATA PIMPINAN -->
                <div class="section-box">
                    <h2>Data Pimpinan</h2>
                    <div class="grid">
                        <div class="field">
                            <label>Nama Pimpinan</label>
                            <div>{{ $anggota->nama_pimpinan }}</div>
                        </div>
                        <div class="field">
                            <label>Email</label>
                            <div>{{ $anggota->email_pimpinan }}</div>
                        </div>
                        <div class="field">
                            <label>Nomor WhatsApp</label>
                            <div>{{ $anggota->telepon_wa_pimpinan }}</div>
                        </div>
                        <div class="field">
                            <label>Alamat</label>
                            <div>{{ $anggota->alamat_pimpinan }}</div>
                        </div>
                    </div>
                </div>

                <!-- DATA PERUSAHAAN -->
                <div class="section-box">
                    <h2>Data Perusahaan</h2>
                    <div class="grid">
                        <div class="field">
                            <label>Nama Perusahaan</label>
                            <div>{{ $anggota->nama_perusahaan }}</div>
                        </div>
                        <div class="field">
                            <label>Trade Mark</label>
                            <div>{{ $anggota->trade_mark }}</div>
                        </div>
                        <div class="field">
                            <label>Tanggal Lahir Perusahaan</label>
                            <div>{{ $anggota->tanggal_lahir ? $anggota->tanggal_lahir->format('d F Y') : '-' }}</div>
                        </div>
                        <div class="field">
                            <label>Email Perusahaan</label>
                            <div>{{ $anggota->email_website_perusahaan }}</div>
                        </div>
                        <div class="field">
                            <label>Telepon Perusahaan</label>
                            <div>{{ $anggota->telepon_wa_perusahaan }}</div>
                        </div>
                        <div class="field">
                            <label>Alamat Kantor</label>
                            <div>{{ $anggota->alamat_kantor }}</div>
                        </div>
                    </div>
                </div>

                <!-- LEGALITAS -->
                <div class="section-box">
                    <h2>Legalitas</h2>
                    <div class="grid">
                        <div class="field">
                            <label>Akte Notaris</label>
                            <div>{{ $anggota->akte_notaris ?? '-' }}</div>
                        </div>
                        <div class="field">
                            <label>NIB / TDUP</label>
                            <div>{{ $anggota->nomor_induk_berusaha_tdup ?? '-' }}</div>
                        </div>
                        <div class="field">
                            <label>NPWP Perusahaan</label>
                            <div>{{ $anggota->npwp_perusahaan ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <!-- PRODUK USAHA -->
                <div class="section-box">
                    <h2>Produk Usaha yang Dijual</h2>
                    @if($anggota->produk_usaha_yang_akan_dijual && count($anggota->produk_usaha_yang_akan_dijual) > 0)
                    <div class="produk-list">
                        @foreach($anggota->produk_usaha_yang_akan_dijual as $produk)
                            <span class="produk-item">{{ $produk }}</span>
                        @endforeach
                    </div>
                    @else
                    <p style="color: #9ca3af; font-style: italic;">Belum ada produk yang dipilih</p>
                    @endif
                </div>
            </div>

            <!-- DOKUMEN -->
            <div id="dokumen" class="section">
                <div class="doc-box">
                    <label>Surat Permohonan</label>
                    <iframe src="{{ Storage::url($anggota->surat_permohonan) }}" 
                            class="pdf-preview"
                            title="Surat Permohonan"></iframe>
                    <div class="doc-actions">
                        <a href="{{ Storage::url($anggota->surat_permohonan) }}" 
                           target="_blank" 
                           class="btn-action secondary">
                            Lihat PDF
                        </a>
                        <a href="{{ Storage::url($anggota->surat_permohonan) }}" 
                           download 
                           class="btn-action">
                            Download
                        </a>
                    </div>
                </div>

                <div class="doc-box">
                    <label>Akte Pendirian Perusahaan</label>
                    <iframe src="{{ Storage::url($anggota->akte_pendirian_perusahaan) }}" 
                            class="pdf-preview"
                            title="Akte Pendirian"></iframe>
                    <div class="doc-actions">
                        <a href="{{ Storage::url($anggota->akte_pendirian_perusahaan) }}" 
                           target="_blank" 
                           class="btn-action secondary">
                            Lihat PDF
                        </a>
                        <a href="{{ Storage::url($anggota->akte_pendirian_perusahaan) }}" 
                           download 
                           class="btn-action">
                            Download
                        </a>
                    </div>
                </div>

                <div class="doc-box">
                    <label>NIB / TDUP</label>
                    <iframe src="{{ Storage::url($anggota->nib_atau_tdup) }}" 
                            class="pdf-preview"
                            title="NIB/TDUP"></iframe>
                    <div class="doc-actions">
                        <a href="{{ Storage::url($anggota->nib_atau_tdup) }}" 
                           target="_blank" 
                           class="btn-action secondary">
                            Lihat PDF
                        </a>
                        <a href="{{ Storage::url($anggota->nib_atau_tdup) }}" 
                           download 
                           class="btn-action">
                            Download
                        </a>
                    </div>
                </div>

                <div class="doc-box">
                    <label>KTP Pimpinan</label>
                    <img src="{{ Storage::url($anggota->ktp_pimpinan) }}" alt="KTP Pimpinan">
                    <div class="doc-actions">
                        <a href="{{ Storage::url($anggota->ktp_pimpinan) }}" 
                           target="_blank" 
                           class="btn-action secondary">
                            Lihat Gambar
                        </a>
                        <a href="{{ Storage::url($anggota->ktp_pimpinan) }}" 
                           download 
                           class="btn-action">
                            Download
                        </a>
                    </div>
                </div>

                <div class="doc-box">
                    <label>NPWP Perusahaan</label>
                    <iframe src="{{ Storage::url($anggota->npwp_perusahaan_file) }}" 
                            class="pdf-preview"
                            title="NPWP Perusahaan"></iframe>
                    <div class="doc-actions">
                        <a href="{{ Storage::url($anggota->npwp_perusahaan_file) }}" 
                           target="_blank" 
                           class="btn-action secondary">
                            Lihat PDF
                        </a>
                        <a href="{{ Storage::url($anggota->npwp_perusahaan_file) }}" 
                           download 
                           class="btn-action">
                            Download
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
const sections = [
    { id: 'informasi-umum', title: 'Informasi Umum', subtitle: 'Data lengkap pimpinan, perusahaan, legalitas, dan produk' },
    { id: 'dokumen', title: 'Dokumen Pendaftaran', subtitle: 'Dokumen registrasi dan perizinan' }
];

function showSection(sectionId, e) {
    e.preventDefault();
    
    // Hide all sections
    document.querySelectorAll('.section').forEach(section => {
        section.classList.remove('active');
    });

    // Remove active from all menu links
    document.querySelectorAll('.sidebar-menu-link').forEach(link => {
        link.classList.remove('active');
    });

    // Show selected section
    document.getElementById(sectionId).classList.add('active');

    // Add active to clicked menu link
    e.target.closest('.sidebar-menu-link').classList.add('active');

    // Update header
    const sectionData = sections.find(s => s.id === sectionId);
    if (sectionData) {
        document.getElementById('headerTitle').textContent = sectionData.title;
        document.getElementById('headerSubtitle').textContent = sectionData.subtitle;
    }

    // Smooth scroll on mobile
    if (window.innerWidth < 768) {
        document.querySelector('.main-content').scrollIntoView({ behavior: 'smooth' });
    }
}
</script>

</body>
</html>