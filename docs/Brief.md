# SIKTN (Sistem Informasi Karang Taruna Nasional) - Project Brief

## 1. Alur & Hak Akses (Role Management)

### Tingkatan Role & Approval
*   **Super Admin**: Akses penuh ke seluruh sistem tanpa batas.
*   **Pimpinan**: Bisa memantau KPI (kelengkapan profil, E-Katalog) dan melihat seluruh modul operasional (read-only atau laporan) tanpa akses pengaturan admin.
*   **PNKT (Pusat / Nasional)**: Mengelola master data, berita, program, dan memvalidasi data pengurus provinsi (PPKT).
*   **PPKT (Provinsi)**: Mengelola dan memvalidasi data tingkat provinsi dan wilayah di bawahnya (PKKT).
*   **PKKT (Kabupaten/Kota)**: Mengelola data di tingkat daerahnya sendiri.
*   *Catatan:* Sekretariat berfungsi untuk "approve berdasarkan tingkatannya". Jika yang mengajukan adalah tingkat provinsi, maka yang menyetujui adalah Sekretariat Provinsi (PPKT), dst.

### Alur Pendaftaran & Kelengkapan Profil Anggota
1. **Input Awal**: PNKT/Sekretariat mendaftarkan *username* anggota pengurus (hanya untuk menerbitkan *credential*).
2. **Distribusi Kredensial**: Sekretariat memberikan kredensial login (dilakukan secara manual via Grup WhatsApp).
3. **Login & Notifikasi**: Anggota login ke *dashboard* dan mendapat notifikasi peringatan wajib untuk merubah *password* dan melengkapi profil.
4. **Validasi Profil**: Setelah profil dilengkapi, data akan divalidasi/disetujui oleh Sekretariat sesuai tingkatannya.
5. **Penerbitan KTA**: E-KTA (ukuran standar kartu) terbit secara otomatis *setelah* profil anggota dinyatakan lengkap dan divalidasi.
6. **KPI Pemantauan**: Sekretariat, Pengurus, dan Pimpinan dapat melihat KPI/Dashboard indikator terkait mana anggota yang sudah atau belum melengkapi profil dan e-katalog.

---

## 2. Struktur Navigasi & Modul (Dashboard Admin)

**Menu Navigation Sidebar:**
*   **Dashboard**
*   **Berita**
    *   New Post
    *   List Post
*   **Sekretariat**
    *   Surat Keluar
    *   Surat Masuk
    *   Surat Keputusan
    *   Notulensi
*   **Organisasi**
    *   Temu Karya
    *   Temu Karya Caretaker
*   **Program**
    *   CSR
    *   Bidang
*   **Agenda**
*   **E-Katalog**
*   **Database**
    *   Struktur Pengurus
    *   Anggota
    *   Kontak
*   **Pengaturan [Admin]**
    *   Profil Organisasi
    *   Notifikasi
    *   Periode Kepengurusan
*   **Manajemen User & Role**

---

## 3. Detail Kebutuhan Modul

### 3.1 Dashboard
*   **Akses**: Pimpinan, Sekretariat Nasional.
*   **Summary Cards (Top)**: Total Surat Keluar, Total Surat Revisi/Pending, Total Temu Karya Selesai, Temu Karya Pending, Total Wilayah Aktif vs Belum Temu Karya.
*   **Grafik Tren**: Grafik surat keluar/masuk per bulan (Line/Bar Chart).
*   **Map Wilayah Indonesia**: Visualisasi interaktif pemetaan wilayah (Sudah Temu Karya / Belum / Caretaker) dibedakan dengan warna dan fitur filter per level wilayah.
*   **Kalender Agenda**: Preview kalender bulanan (*read-only*), jika diklik mengarah ke halaman manajemen Agenda.
*   **KPI Panel**: Laporan kelengkapan profil dan e-Katalog anggota.

### 3.2 Berita
*   **Akses**: Pimpinan, Sekretariat Nasional (Diinput khusus oleh PNKT, referensi mirip CMS HIPMI).
*   **Fitur CMS**: Buat Post (Judul, Isi, Gambar, Kategori, Tag) & List Post.
*   **Status Post**: Draft / Published / Archived.
*   **Schedule Publish**: Terdapat opsi untuk menentukan tanggal & jam tayang berita.

### 3.3 Sekretariat (Surat-Menyurat & Notulensi)
*   **Akses**: Pimpinan, Sekretariat Nasional.
*   **Surat Keluar & Surat Masuk**:
    *   Field: Nomor Surat (Input Manual), Tanggal, Perihal, Pengirim/Tujuan, Status (Draft / Terbit / Revisi). *Status revisi menggantikan pending*.
    *   Lampiran: Link Google Drive (Bisa PDF/Word).
    *   Kategori Tampilan (Tabs): Internal, Eksternal, Penting (Agar pimpinan lebih aware secara psikologis).
*   **Surat Keputusan (SK)**:
    *   Field: Tanggal Berlaku, Tanggal Berakhir, Status (Aktif / Tidak Aktif).
    *   *Reminder System*: Notifikasi otomatis akan muncul 6 bulan sebelum masa berlaku SK habis.
*   **Notulensi**: Form input hasil rapat, bisa ditautkan (*linked*) ke kegiatan di modul Agenda, dan menyertakan lampiran file (Google Drive).
*   **Audit Trail / Log Aktivitas**: Fitur log untuk melacak riwayat perubahan/edit surat (khusus dipantau Sekretariat).

### 3.4 Organisasi
*   **Akses**: Pimpinan, Sekretariat Nasional.
*   **Summary Card**: Total wilayah yang belum melakukan Temu Karya (dengan filter wilayah).
*   **Temu Karya**: Form submission (Wilayah, Tanggal, Lokasi, Jumlah Peserta, Dokumentasi Foto, Catatan, dan Penambahan field Upload SK).
*   **Temu Karya Caretaker**: Form administrasi khusus untuk pelaksana tugas (caretaker).

### 3.5 Program
*   **Akses**: Pimpinan, Sekretariat Nasional, Pengurus tingkat PNKT.
*   Terbagi menjadi dua kategori utama (Muncul sebagai dropdown di navbar Front-End):
    *   **CSR**: Program yang melibatkan pihak luar/mitra/donor (Input nama mitra dilakukan manual).
    *   **Bidang**: Program internal per divisi. Bisa ditautkan (*linked*) ke struktur bidang di Database Pengurus.
*   **Field Data (Berlaku untuk Keduanya)**: Nama Program, Kategori, Status (Perencanaan / Berjalan / Selesai), Periode (Mulai-Selesai), PIC, Target Output, Anggaran (Opsional).

### 3.6 Agenda
*   **Akses**: Pimpinan, Sekretariat Nasional, Pengurus.
*   **Tampilan Utama**: Halaman penuh (*Full Calendar*) dengan sistem CRUD kegiatan (Bisa referensi kalender/template interaktif).
*   **Field Kegiatan**: Judul, Jenis Kegiatan (Internal, Publik, Webinar, Temu Karya), Waktu/Tanggal, Lokasi, Deskripsi, PIC.
*   **History**: Agenda yang sudah lewat tenggat waktunya akan otomatis dipindahkan ke arsip (*History*).
*   **Export File**: Fitur eksport kalender (Google Calendar / iCal format) agar jadwal bisa terintegrasi ke *device* masing-masing pengguna.

### 3.7 E-Katalog
*   **Akses**: Publik (View), Anggota (Submit Data), Sekretariat (Verifikasi).
*   **Field Katalog**: Nama Produk/Usaha, Deskripsi, Foto, Harga, Kategori, Wilayah, Kontak Pemilik.
*   **Link Tautan Bisnis**: Wajib ada opsi input Link Website Perusahaan & Link Online Shop/Marketplace.
*   **Hierarki Approval**:
    *   Katalog dibagi berdasarkan wilayah (Nasional, Provinsi, Kab/Kota).
    *   Pengajuan PKKT divalidasi oleh PPKT.
    *   Pengajuan PPKT divalidasi oleh PNKT.
    *   Opsi Review: Approve / Revisi / Tolak (Harus melewati opsi revisi terlebih dahulu sebelum menolak).

### 3.8 Database (Anggota & Kepengurusan)
*   **Struktur Pengurus**: Hierarki organisasi dapat dimunculkan fleksibel/template sesuai data anggota, dengan opsi input manual.
*   **Master Jabatan**: Master data jabatan diinput manual oleh Sekretariat PNKT.
*   **Database Anggota**: Profil lengkap setiap pengurus/anggota.
    *   **Field Wajib**: Foto (Opsi Ambil dari Kamera atau Pilih File), NIK, Nama Lengkap, Tempat & Tanggal Lahir, Alamat Lengkap, Jabatan, Pendidikan Terakhir, Pekerjaan, Riwayat Organisasi, Kompetensi, Nomor HP/WhatsApp, Email.
    *   **Field Opsional**: Akun Sosial Media (Instagram, TikTok, X/Twitter).
*   **Fitur Ekspor**: Opsi menarik (*export*) data administrasi ke format Excel/CSV.
*   **Ulang Tahun Reminder**: Muncul notifikasi pop-up peringatan ulang tahun pengurus (Interval: 1 Minggu -> H-3 -> H-1).

### 3.9 Notifikasi & Pengaturan Pusat
*   **Notifikasi Center**: Seluruh notifikasi dikumpulkan terpusat pada icon Lonceng (Bell) di navbar. Berisi: Pengingat Surat Pending, SK Habis (6 Bulan sebelum batas), Notifikasi kelengkapan profil, dan E-Katalog pending.
*   **Manajemen User & Role**: Halaman admin khusus untuk kelola, assign role, nonaktifkan akun, dan reset password pengguna.
*   **Pengaturan Sistem**:
    *   **Profil Organisasi**: Nama, Logo, Alamat.
    *   **Notifikasi Toggles**: Hak untuk menonaktifkan/mengaktifkan tampilan jenis notifikasi tertentu secara spesifik agar sistem tidak mengganggu kenyamanan pengguna.
    *   **Periode Kepengurusan**: Manajemen masa bakti organisasi.

---

## 4. UI/UX & Kebutuhan Front-End Web
*   **Tema & Warna**: Menggunakan kombinasi warna *Navy Blue* (Biru Dongker) dengan gradasi Putih yang elegan.
*   **Struktur Navbar**: Home, Organisasi, Program (Bentuk Dropdown berisi: CSR & Bidang), E-Katalog, Berita.
*   *Catatan Tambahan*: Fitur Opsi Bahasa (EN/ID) **tidak jadi** dimasukkan dalam scope saat ini.