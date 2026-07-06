# Catatan Pengerjaan SIKTN

Ini adalah catatan langkah demi langkah dari pengerjaan proyek SIKTN.

## Yang Sudah Dikerjakan (Done)

### 05 Juli 2026
- Membuat file .env baru dari .env.example
- Setting database menggunakan MySQL dengan nama siktn_cl
- Mengubah nama aplikasi (APP_NAME) menjadi SIKTN
- Menyesuaikan menu navigasi di footer (Home, Organisasi, Program, E-Katalog, Berita)
- Mengubah teks Powered by di footer menjadi Alcomedia.id
- Menganalisis kebutuhan fitur SIKTN
- Menentukan 6 role utama: Super Admin, Pimpinan, PNKT, PPKT, PKKT, dan Pengurus

## Yang Akan Dikerjakan (Next)
- [x] Install Spatie untuk sistem Role & Permission
- [x] Merombak database admin/role untuk menyesuaikan dengan peran SIKTN (Spatie terintegrasi + ubah `category` enum menjadi string)
- [x] Memperbarui form `Tambah Admin` dengan role SIKTN (Super Admin, Pimpinan, PNKT, PPKT, PKKT)
- [ ] Merombak database profil pengurus (tambah field NIK, Sosmed; hapus field lama)
- [ ] Membuat fitur pendaftaran akun pengurus oleh Sekretariat
