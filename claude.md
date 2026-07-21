# 🤖 Panduan Penting untuk Claude (AI Assistant)

Halo Claude! Jika kamu membaca file ini, berarti kamu sedang ditugaskan untuk membantu pengguna mengembangkan aplikasi **SIKTN**. 

Tolong baca dan patuhi panduan di bawah ini dengan sangat ketat agar alur kerja pengguna tetap nyaman dan aman.

## 🛠️ Konteks Proyek
- **Framework Utama:** Laravel (PHP)
- **Frontend:** Blade Templates (Hanya menggunakan Blade, **TIDAK** menggunakan React/Inertia).
- **Fitur Utama Terkini:** 
  - Terdapat manajemen Role & Permission (sepertinya menggunakan Spatie).
  - Terdapat fitur CMS Admin (Berita, Organisasi, Anggota).
  - Menggunakan fitur Soft Deletes (Manajemen Trash) pada data-data penting.

## 🚨 ATURAN MUTLAK (ANTI-AUTO-EXECUTE PROTOCOL)
Pengguna memiliki aturan ketat yang **HARUS KAMU PATUHI TANPA PENGECUALIAN**. Bacalah baik-baik:

1. **JANGAN PERNAH** langsung mengeksekusi tool terminal, menulis blok kode penuh untuk dimodifikasi, atau langsung mengubah isi file segera setelah menerima perintah.
2. **SELALU BERHENTI DULU (PAUSE)** dan berikan penjelasan singkat, logis, dan jelas mengenai rencana kerjamu, arsitektur, atau pendekatanmu menggunakan *bullet points*.
3. Setelah menjelaskan pendekatanmu, kamu **WAJIB** meminta konfirmasi pengguna dengan pertanyaan seperti: 
   > *"Apakah pendekatan ini sudah sesuai dengan yang kamu inginkan, atau ada yang perlu disesuaikan sebelum saya eksekusi?"*
4. **TUNGGU** persetujuan eksplisit atau instruksi lebih lanjut dari pengguna sebelum mulai men-generate kode, membuat file, atau melakukan *refactoring*.
5. Fokus pada diskusi dan penyelarasan terlebih dahulu; eksekusi (menulis kode) **HANYA** terjadi setelah pengguna memberikan lampu hijau (seperti *"gas"*, *"oke"*, *"lanjut"*).

## 📂 Panduan Tambahan Saat Koding
- Selalu periksa kesesuaian antara penamaan URL di `routes/web.php` dan pemanggilan `route()` di dalam file `.blade.php` untuk menghindari error 404 atau 405 Method Not Allowed.
- Saat melakukan manipulasi database, perhatikan penggunaan Soft Deletes dan pastikan logika `TrashController` terintegrasi dengan baik jika sedang mengurusi data yang terhapus.

## 🎨 Komponen UI & Frontend Khusus
Pengguna sudah menyiapkan beberapa komponen frontend kustom yang **WAJIB** kamu gunakan (jangan install library baru jika sudah ada):
- **Toast / Flash Messages:** 
  Sudah terintegrasi dengan SweetAlert2 SIKTN secara global. Untuk memanggilnya dari sisi JavaScript, cukup gunakan:
  `Toast.fire({ icon: 'success', title: 'Pesan Berhasil!' });` (bisa pakai icon 'error', 'warning', dll).
  Atau jika dari sisi Controller, cukup *passing flash data* ke session: `return redirect()->route(...)->with('success', 'Pesan');`.
- **Dropdown / Modal:**
  Hindari penggunaan *library* *dropdown* tambahan. Gunakan *Vanilla JavaScript* dan *CSS classes* murni (seperti manipulasi `classList.toggle('active')`) yang sama persis polanya dengan komponen yang sudah ada (contoh: *Logout Modal* atau *Birthday Sidebar* di layout utama). Tiru saja *style* CSS dan animasi yang sudah berjalan!

## 💬 Gaya Komunikasi
- Gunakan bahasa Indonesia. Santai tapi fokus pada solusi (biasanya pengguna menggunakan sapaan santai seperti "bang", balaslah dengan natural).
- Berikan ringkasan singkat (summary) setelah kamu selesai mengerjakan sebuah task.
- Jangan *text-heavy* (bertele-tele). Jaga responmu agar tetap ringkas.

**Pesan untuk Claude:** 
Jika pengguna menyapamu dan memberikan tugas setelah ini, buktikan bahwa kamu sudah membaca panduan ini dengan mematuhi **ANTI-AUTO-EXECUTE PROTOCOL**!
