# 🏛️ SIKTN Admin UI & Development Mandatory Rules

## 🎨 UI/UX Design System Enforcement:
Setiap kali melakukan refactoring atau membuat halaman admin baru di SIKTN, **WAJIB AKURAT DAN PERSISIS** mengikuti dokumen pedoman [SIKTN_ADMIN_UI_BENCHMARK.md](file:///d:/Gawe/SIKTN/siktn-cl/SIKTN_ADMIN_UI_BENCHMARK.md):

1. **Select2 Custom Styling**:
   - Seluruh `<select>` filter/form WAJIB menggunakan Select2 (`.select2-basic`) dengan micro-animation `select2DropdownFadeIn`.
   - Warna terpilh (highlight) WAJIB navy `#022648` dengan font putih tebal.

2. **Action Dropdown Trigger (`⋮`)**:
   - Seluruh tabel admin WAJIB menggunakan **Action Dropdown Trigger Titik Tiga (`⋮`)** (`.btn-aksi-trigger` & `.aksi-dropdown`).
   - Tidak diperbolehkan menyisipkan tombol aksi polos horizontal yang membuat tabel berantakan.

3. **Summary Stat Cards**:
   - Setiap halaman list/management utama WAJIB memiliki **Stat Cards** di bagian atas dengan garis aksen kiri 4px (`.stat-card`).

4. **SweetAlert2 Modal & Toast Mandatory**:
   - DILARANG MENGGUNAKAN `confirm()` atau `alert()` bawaan browser.
   - Seluruh konfirmasi hapus/status WAJIB menggunakan **SweetAlert2 Confirm Modal** (`Swal.fire({...})`).
   - Seluruh tombol Export/Download Excel WAJIB menyertakan `Toast.fire({ icon: 'success', title: '...' })`.

5. **Warna & Tipografi**:
   - Gunakan CSS Scoping `.admin-ui-scope` atau `.ekatalog-scope`.
   - Gunakan warna `--navy: #022648`, `--gold: #b7830f`, `--radius-md: 6px`.
   - Font WAJIB keluarga `Inter` atau `Montserrat`.
