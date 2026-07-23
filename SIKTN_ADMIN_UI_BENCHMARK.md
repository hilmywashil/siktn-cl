# 🏛️ SIKTN Admin UI Benchmark & Design Standard

Dokumen ini merupakan panduan resmi standar UI/UX untuk seluruh halaman admin SIKTN (E-Katalog, UMKM, Berita, Organisasi, Sekretariat, dll). Seluruh pembuatan dan refactoring halaman admin **WAJIB** mengikuti standar ini.

---

## 🎨 1. Design Tokens & CSS Variables

Setiap halaman admin menggunakan CSS Scoping `.ekatalog-scope` (atau `.admin-ui-scope`) untuk menghindari konflik gaya global:

```css
.ekatalog-scope, .ekatalog-scope * {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    box-sizing: border-box;
}

.ekatalog-scope {
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
}
```

---

## 🔘 2. Tombol Aksi Trigger & Floating Action Dropdown Menu (`⋮`)

Untuk menghindari tabel berantakan akibat banyaknya tombol aksi horizontal yang terpotong di layar, seluruh tabel admin menggunakan **Action Dropdown Trigger (`⋮`)**:

```html
<td class="cell-aksi">
    <div class="aksi-wrapper">
        <button type="button" class="btn-aksi-trigger" data-target="dropdown-aksi-{{ $item->id }}" aria-label="Buka menu aksi">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                <circle cx="12" cy="5" r="1.75"></circle>
                <circle cx="12" cy="12" r="1.75"></circle>
                <circle cx="12" cy="19" r="1.75"></circle>
            </svg>
        </button>

        <div class="aksi-dropdown" id="dropdown-aksi-{{ $item->id }}">
            <a href="#" class="aksi-item aksi-view">Lihat Detail</a>
            <button type="button" class="aksi-item aksi-approve">Setujui</button>
            <button type="button" class="aksi-item aksi-revision">Kirim Revisi</button>
            <button type="button" class="aksi-item aksi-reject">Tolak</button>
            <a href="#" class="aksi-item aksi-edit">Edit</a>
            <div class="aksi-divider"></div>
            <button type="button" class="aksi-item aksi-delete">Hapus</button>
        </div>
    </div>
</td>
```

### CSS Animasi Micro-Transition Dropdown:
```css
.btn-aksi-trigger {
    width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;
    background: var(--navy); color: #fff; border: none; border-radius: var(--radius-md); cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 1px 3px rgba(2, 38, 72, 0.12);
}
.btn-aksi-trigger:hover {
    background: var(--navy-light); transform: scale(1.08) translateY(-1px); box-shadow: 0 4px 12px rgba(2, 38, 72, 0.25);
}

.aksi-dropdown {
    display: block; position: fixed; min-width: 190px; background: #fff; border: 1px solid var(--gray-200);
    border-radius: var(--radius-md); box-shadow: 0 14px 32px rgba(2, 38, 72, 0.18); padding: 6px; z-index: 1000;
    opacity: 0; visibility: hidden; transform: translateY(-8px) scale(0.96);
    transition: opacity 0.18s cubic-bezier(0.16, 1, 0.3, 1), transform 0.18s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.18s;
    pointer-events: none;
}
.aksi-dropdown.is-open {
    opacity: 1; visibility: visible; transform: translateY(0) scale(1); pointer-events: auto;
}

.aksi-item {
    display: flex; align-items: center; gap: 9px; width: 100%; padding: 0.55rem 0.65rem; font-size: 0.8125rem; font-weight: 600;
    border-radius: var(--radius-sm); color: var(--gray-900); text-decoration: none !important; border: none; background: transparent;
    text-align: left; cursor: pointer; transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1);
}
.aksi-item:hover { background: var(--gray-100); transform: translateX(4px); }
.aksi-item svg { flex-shrink: 0; transition: transform 0.18s ease; }
.aksi-item:hover svg { transform: scale(1.15); }
```

---

## 🎯 3. Select2 Custom Styling & Micro-Animations

```css
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
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); min-width: 140px;
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
```

---

## 📊 4. Stat Cards & Media Wrap

- **Stat Cards**: Memiliki icon box 42x42px dengan border aksen kiri 4px (`.stat-card.total`, `.pending`, `.revision`, `.approved`, `.rejected`).
- **Logo Wrap**: `.katalog-logo-wrap` (52x52px dengan border halus `var(--gray-200)`).
