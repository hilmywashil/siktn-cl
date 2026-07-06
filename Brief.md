Navigation 
● Dashboard 
● Berita 
○ New Post 
○ List Post 
● Sekretariat 
○ Surat Keluar 
○ Surat Masuk 
○ Surat Keputusan 
○ Notulensi 
● Organisasi 
○ Temu Karya 
○ Temu Karya Caretaker 
● Program 
○ CSR 
○ Bidang 
● Agenda 
● E-Katalog 
● Database 
○ Struktur Pengurus 
○ Anggota 
○ Kontak 
● Pengaturan [Admin] 
SIKTN 
● Manajemen User & Role 
○ Profil Organisasi 
Review Kebutuhan Fitur dan Klasifikasi Scope SIKTN 
○ Notifikasi 
○ Periode Kepengurusan 
Dashboard 
Diakses oleh: Pimpinan, Sekretariat Nasional 
Summary Cards (bagian atas): 
● Total Surat Keluar 
● Total Surat Pending (butuh TTD) 
● Total Temu Karya Selesai 
● Temu Karya Pending 
● Total Wilayah Aktif vs Belum Temu Karya 
Grafik Tren : 
Grafik surat keluar/masuk per bulan (line chart atau bar chart) 
Map Wilayah Indonesia: 
● Visualisasi interaktif wilayah yang sudah dan belum menjalankan temu karya 
● Dibedakan warna: sudah temu karya / belum / caretaker 
● Filter per level wilayah (Provinsi / Kab/Kota) 
Kalender Agenda: 
● Preview kalender bulanan (read-only di Dashboard) 
● Klik event → redirect ke tab Agenda untuk detail 
Berita 
Diakses oleh: Pimpinan, Sekretariat Nasional 
Berfungsi seperti CMS standar untuk manajemen konten berita/pengumuman internal. 
Fitur: 
● Buat post baru (judul, isi, gambar, kategori, tag) 
● List post dengan filter status 
● Status post: Draft / Published / Archived 
● Schedule publish (tentukan tanggal & jam tayang) 
SIKTN 
● Kategori: Pengumuman, Kegiatan, Regulasi, dll 
Review Kebutuhan Fitur dan Klasifikasi Scope SIKTN 
Sekretariat 
Diakses oleh: Pimpinan, Sekretariat Nasional  
6a. Surat Keluar & Surat Masuk 
● Field yang perlu ada: nomor surat (otomatis/manual), tanggal, perihal, pengirim/tujuan, 
status (Draft  / Terbit  / Pending TTD, lampiran file (PDF/Word). 
Keterangan : Pending diganti menjadi revisi, status pending itu sudah default ketika dari 
sekertariat upload (hanya penyesuaian flow yang cacat) 
● Fitur tambahan: 
● Nomor surat otomatis— auto-increment berdasarkan tahun dan jenis surat 
Keterangan: Jadinya manual tidak otomatis 
● Upload lampiran — file PDF atau Word (berupa link google drive) 
● Ringkasan: jumlah surat terbit bulan ini, jumlah pending  
● Notifikasi ke Pimpinan apabila ada surat yang membutuhkan tanda tangan  
Keterangan: tedapat 3 jenis dalam 1 halaman surat Internal, eksternal, penting (agar pimpinan 
lebih aware, dan teknis secara psikologis) 
6b. Surat Keputusan (SK) 
● Field tambahan yang diperlukan: tanggal berlaku, tanggal berakhir, status (Aktif / Tidak 
Aktif / Kadaluwarsa).  
Keterangan : Kadaluwarsa di hapus jadi hanya aktif dan tidak aktif saja  
● Notifikasi otomatis dikirim 6 bulan sebelum masa berlaku SK habis  
● Notifikasi masuk ke Notifikasi Center dan (opsional) email  
Keterangan: email masuk ke pengembangan Fase 2 
6c. Notulensi 
● Form input notulensi rapat 
● Tautan ke Agenda — notulensi bisa dilinkkan ke agenda/rapat terkait (tambahan) 
● Upload file notulensi (berupa link google drive) 
SIKTN 
Organisasi 
Review Kebutuhan Fitur dan Klasifikasi Scope SIKTN 
Diakses oleh: Pimpinan, Sekretariat Nasional 
Summary Card: 
● Total wilayah yang belum melakukan temu karya  
● Filter per level wilayah (Provinsi /Kab/Kota)  
7a. Temu Karya 
Form submission temu karya, field yang diperlukan: wilayah, tanggal pelaksanaan, lokasi, jumlah 
peserta, dokumentasi foto, catatan.  
Keterangan: Terdapat Penambahan field untuk upload SK   
7b. Temu Karya Caretaker 
Program 
Diakses oleh: Pimpinan, Sekretariat Nasional, Pengurus 
Manajemen program yang sedang dijalankan, terbagi dua kategori: 
8a. Program CSR 
Program yang melibatkan pihak luar/donor/mitra. (ketik manual nama mitranya siapa) 
8b. Program Bidang 
Program internal per bidang organisasi. 
Field yang diperlukan (berlaku untuk keduanya): nama program, kategori, status (Perencanaan / 
Berjalan / Selesai), periode (tanggal mulai–selesai), PIC, target output, anggaran (opsional). 
Tambahan: Program Bidang sebaiknya bisa dilinkkan ke struktur bidang di Database Pengurus. 
Agenda 
Diakses oleh: Pimpinan, Sekretariat Nasional, Pengurus 
Manajemen jadwal kegiatan PNKT. 
Fitur: 
● Tampilan kalender bulanan (sebagai halaman penuh, berbeda dari preview di Dashboard) 
Keterangan : Dibuat seperti thearena (jadi sudah ada templates kalender dan bisa CRUD agenda) 
● Tambah/edit/hapus kegiatan 
● Field: judul, jenis kegiatan, tanggal & waktu, lokasi, deskripsi, PIC 
SIKTN 
Review Kebutuhan Fitur dan Klasifikasi Scope SIKTN 
● Jenis kegiatan: Meeting Internal, Temu Karya, Acara Publik, Webinar, dll 
● Agenda yang sudah lewat otomatis masuk ke arsip/history  
Keterangan : arsip disini maksudnya yang penting tidak tehapus 
● Opsional: export ke format kalender (Google Calendar / iCal) 
Keterangan : Jadi ketika di klik export itu bisa muncul agenda yang telah ditentukan, ke calender 
devices pengguna 
E-Katalog 
Diakses oleh: Semua (view), Anggota (submit), Sekretariat (approve) 
Tempat menampilkan produk/usaha anggota Karang Taruna. 
Field katalog: nama produk/usaha, deskripsi, foto, harga, kategori, wilayah, kontak pemilik 
Approval flow: 
● Anggota submit katalog 
● Sekretariat menerima notifikasi 
● Sekretariat review → Approve / Revisi / Tolak (tambahan: opsi Revisi sebelum tolak) 
● Jika diapprove, katalog tampil ke publik 
Database 
Diakses oleh: Semua (sesuai level masing-masing) 
11a. Struktur Pengurus 
Hierarki pengurus organisasi. 
11b. Anggota 
Database anggota Karang Taruna. 
Perlu ditentukan field mana yang wajib (required) vs opsional. 
11c. Kontak 
Perlu dikonfirmasi: apakah ini bagian dari data anggota, atau terpisah (misal: kontak 
eksternal/mitra organisasi)? 
Keterangan : hanya data pribadi tidak ada kontak eksternal/mitra organisasi 
Fitur tambahan: 
SIKTN 
Export data ke Excel/CSV untuk keperluan administrasi 
Fitur yang berlaku lintas seluruh modul: 
Review Kebutuhan Fitur dan Klasifikasi Scope SIKTN 
12a. Notifikasi Center 
● Semua notifikasi terpusat di satu tempat (bell icon di navbar): 
● Surat pending TTD 
● SK akan habis masa berlakunya (6 bulan sebelumnya) 
● E-Katalog pending review 
● (Opsional) notifikasi via email atau WhatsApp  
Keterangan : Masuk fase 2 
12b. Log Aktivitas / Audit Trail 
Mencatat siapa mengubah apa dan kapan. Penting untuk akuntabilitas organisasi dengan banyak 
admin. 
Keterangan : hanya untuk tracking surat siapa yang rubah,dll dan hanya untuk sekertariat 
12c. Manajemen User & Role 
● Halaman khusus admin untuk: 
● Tambah/edit/nonaktifkan akun user 
● Assign dan ubah role 
● Reset password 
12d. Pengaturan / Settings 
● Profil organisasi (nama, logo, alamat) 
● Pengaturan notifikasi (on/off per jenis notifikasi) 
Keterangan: jadi bisa menentukan apakah tampilan notifikasi terus mengikuti, dan terus muncul 
atau tetap muncul tapi tidak menganggu per jeni notifikasi 
● Manajemen periode kepengurusan 
Keterangan : seperti yang bagian di database 
SIKTN 
Review Kebutuhan Fitur dan Klasifikasi Scope SIKTN 
Keterangan Tamabahan 
• Flow awal masih sama seperti yang di brief sebelumnya : 
• Input username > terbit credential… (Cek brief sebelumnya) 
• Sekertariat itu ada bagiannya sekertariat provinsi dan sekertariat untuk kabupatenkota 
(Nama PNKT, dll ada di brief sebelumnya) 
• Sekertariat tersebut nantinya berfungsi untuk maksud “approve berdasarkan 
tingkatannya” misalkan yang mengajukan provinsi berarti yang approve sekertariat 
provisi begitupun untuk kabupaten kota secara hak akses sama yang membedakan hanya 
pengaturan approval berdasarkan tingkatannya saja 


Alur PNKT Sekertariat dan anggota pengurus: 
Sekertariat 
PNKT mengikutkan anggoa pengurus(hanya berupa 
username(sama seperti SMADIMENT, karna tujuannya hanya untuk terbit 
credential) > PNKT sekertariat memberikan credential dan link loginnya ke 
grup WhatsApp > Anggota pengurus login ke dashboard > di dashboard beri 
pemberitahuan untuk menyesuaikan profile (rubah credential dan 
melengkapi profile)) > anggota pengurus merubah credential dan 
melengkapi profile > Jika sudah melengkapi profile (approve oleh 
sekertariat PNKT) 
Note : untuk bagian “PNKT sekertariat memberikan credential dan 
link loginnya ke grup WhatsApp” ini dilakukan manual tidak melalui 
sistem (di tulis hanya karna untuk bayangan flow) 
2. Field profile anggota pengurus: - 
Foto(ada dua opsi: kamera/pilih file) - - - - - - - - - - - - 
NIK 
Nama Lengkap 
Tempat dan tanggal lahir 
Alamat lengkap 
Jabatan 
Pendidikan Terakhir 
Pekerjaan 
Riwayat organisasi 
Kompetensi 
Nomor HP/WhatsApp 
Email 
Sosial media ada tiga field (isntagram-tiktok-X)  
Note : Hanya sosial media yang opsional field lain wajib 
3. Struktur organisasi langsung muncul setelah melengkapi data dari anggota 
pengurus (dibuat fleksibel atau templates) dan tetap ada input manual nya 
4. Untuk jabatan diinput manual oleh sekertariat PNKT 
5. Untuk KTA terbit otomatis setelah anggota pengurus melengkapi profile 
dan sudah divalidasi oleh PNKT(ukuran KTA standar kartu) 
6. Terdapat pop up 1 minggu > 3 hari > H1 hari sebelum ulang tahun 
7. Navbar : (Home, organisasi, program, e-katalog, dan berita) 
8. Ada opsi en/in 
9. Untuk program masuk ke navbar bukan hanya berupa section di 
home/beranda 
10. Yang bisa akses bagian program adalah PNKT 
11. Ketika cursor berada di navbar program  akan muncul dua opsi yaitu : CSR 
& Bidang 
12. Untuk field program sama seperti hipmi, namun ada dua opsi: CSR dan 
Bidang 
13. E-KATALOG sama dengan HIPMI namun untuk tautan bisnis berupa opsi 
yaitu link website dan tautan online shope (jadi ada dua field) 
14. E-KATALOG dibagi menjadi berdasarkan wilayah (nasional, provinsi, dan 
kabupaten/kota) 
15. Validasi E-katalog dari PKKT oleh PPKT dan input E-Katalog oleh PPKT 
di validasi oleh PNKT dan PNKT (masih perlu di make sure) 
16. Berita untuk inputan sama dengan hipmi (berita diinput oleh PNKT) 
17. Sekertariat maupun anggota pengurus dan pimpinan bisa lihat berupa KPI 
(mana yang belum mana yang sudah melengkapi profile, E-Katalog, dll) 
18. Color palete ambil biru dongker gradasi putih