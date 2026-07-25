# 📋 Daftar Revisi & Development Log SIKTN (25 Juli 2026)

---

## 🎨 1. UI/UX & Global Branding
- [x] **Warna Grafik Tren Surat**: Ubah warna grafik tren surat menjadi *colorful* (referensi tampilan *Smadiment*), hindari warna hitam/gelap polos.
- [x] **Watermark Sidebar**: Tambahkan watermark `Powered by Alcomedia` di bagian paling bawah sidebar admin.
- [x] **Branding Header**: Ubah teks logo/header dari **"Karang Taruna Alumni"** menjadi **"Karang Taruna"**.
- [x] **Tombol Auth Navbar**: Ubah label tombol **"Dashboard Login"** pada header publik menjadi **"Login"**.

---

## 📰 2. Modul Berita
- [x] **Akses Berita PPKT**: Tambahkan akses kelola & tambah berita untuk Admin tingkat **PPKT**.
- [x] **Filter Kategori Wilayah di Publik**: Tambahkan dropdown filter **Kategori Wilayah** di halaman Berita publik.
- [x] **Sematkan Berita (Pin)**: Fitur *"Sematkan Berita"* hanya berlaku untuk tingkat **Nasional** saja (PPKT tidak perlu).

---

## 📁 3. Dokumen & Viewer Google Drive
- [ ] **Interactive Drive Preview**: Tautan Google Drive pada modal/drawer harus mendukung pratinjau langsung (*embedded iframe*), tombol perbesar (fullscreen), serta tombol akses sumber asli/download.

---

## 🏛️ 4. Modul Organisasi, Temu Karya & SK
- [ ] **Struktur Organisasi**: Tampilan struktur organisasi dikelompokkan / dibuat per **Provinsi**.
- [ ] **Hak Akses Temu Karya**: Fitur Temu Karya/Caretaker wajib dapat diakses oleh role `Superadmin`, `PNKT`, dan `Pimpinan`.
- [ ] **Keterkaitan SK & Temu Karya**: Menghubungkan data Surat Keputusan (SK) secara langsung dengan laporan Temu Karya / Caretaker.
- [ ] **Auto Detect Expired SK**: Sistem secara otomatis mendeteksi status masa aktif SK dan memberikan notifikasi pengingat sebelum masa bakti habis.

---

## 🪪 5. KTA Digital & Form Isian
- [x] **Penyesuaian Label KTA**: Hapus label *"Pengurus Nasional"* pada Kartu Tanda Anggota (KTA Digital).
- [x] **Form Isian Link Field**:
  - `Username`
  - `Nama`
  - `Wilayah` *(Dropdown)*
  - `E-mail`

---

## 🚀 6. Modul Program Kerja
- [ ] **Tombol Join Program**: Tambahkan tombol **"Join Program"** pada setiap halaman program.
  - **Jika sudah Login Anggota**: Memproses pendaftaran/join program.
  - **Jika Belum Login**: Otomatis redirect ke halaman **Login**.