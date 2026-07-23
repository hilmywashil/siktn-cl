<?php

use App\Http\Controllers\OutlineController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\OrganisasiController;
use App\Http\Controllers\Admin\TemuKaryaController;
use App\Http\Controllers\Admin\KatalogController as AdminKatalogController;
use App\Http\Controllers\Admin\MisiController;
use App\Http\Controllers\Admin\AnggotaManagementController;
use App\Http\Controllers\Admin\BeritaController as AdminBeritaController;
use App\Http\Controllers\Admin\StrategicPlanController;
use App\Http\Controllers\Admin\AgendaController;
use App\Http\Controllers\Admin\KontakController;
use App\Http\Controllers\Admin\Sekretariat\SuratController;
use App\Http\Controllers\Admin\Sekretariat\SuratKeputusanController;
use App\Http\Controllers\Admin\Sekretariat\NotulensiController;
use App\Http\Controllers\Admin\NotificationSettingController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AnggotaAuthController;
use App\Http\Controllers\Anggota\KatalogController as AnggotaKatalogController;
use App\Http\Controllers\BukuAnggotaController;
use App\Http\Controllers\BeritaController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PublicOrganisasiController;
use App\Http\Controllers\PublicProgramController;

// =====================================================
// ADMIN ROUTES
// =====================================================
Route::prefix('admin')->name('admin.')->group(function () {

    // Route yang TIDAK perlu login (guest routes)
    Route::middleware('guest:web')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])->name('login.post');
    });

    // Route yang HARUS login (protected with AdminMiddleware)
    Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
        // Dashboard
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Info Admin (BPD only)
        Route::get('info-admin', [AdminDashboardController::class, 'infoAdmin'])->name('info-admin');
        Route::get('export-admin', [AdminDashboardController::class, 'exportAdmin'])->name('export-admin');
        Route::get('create-admin', [AdminDashboardController::class, 'createAdmin'])->name('create-admin');
        Route::post('store-admin', [AdminDashboardController::class, 'storeAdmin'])->name('store-admin');
        Route::get('edit-admin/{admin}', [AdminDashboardController::class, 'editAdmin'])->name('edit-admin');
        Route::put('update-admin/{admin}', [AdminDashboardController::class, 'updateAdmin'])->name('update-admin');
        Route::delete('delete-admin/{admin}', [AdminDashboardController::class, 'deleteAdmin'])->name('delete-admin');
        Route::patch('toggle-active-admin/{admin}', [AdminDashboardController::class, 'toggleActiveAdmin'])->name('toggle-active-admin');
        Route::post('reset-password-admin/{admin}', [AdminDashboardController::class, 'resetPasswordAdmin'])->name('reset-password-admin');

        // Strategic Plan CRUD (Admin)
        Route::resource('strategic-plan', StrategicPlanController::class);

        // Agenda CRUD (Admin)
        Route::get('agenda/get-events', [AgendaController::class, 'getEvents'])->name('agenda.get-events');
        Route::get('agenda/holidays', [AgendaController::class, 'getHolidays'])->name('agenda.holidays');
        Route::get('agenda/export', [AgendaController::class, 'exportICal'])->name('agenda.export');
        Route::get('agenda/export-excel', [AgendaController::class, 'exportExcel'])->name('agenda.export-excel');
        Route::resource('agenda', AgendaController::class);

        // Sekretariat Module (Surat Masuk/Keluar, SK, Notulensi)
        Route::prefix('sekretariat')->name('sekretariat.')->group(function () {
            // Surat Masuk & Keluar
            Route::get('surat', [SuratController::class, 'index'])->name('surat.index');
            Route::post('surat', [SuratController::class, 'store'])->name('surat.store');
            Route::patch('surat/{id}/status', [SuratController::class, 'updateStatus'])->name('surat.update-status');
            Route::delete('surat/{id}', [SuratController::class, 'destroy'])->name('surat.destroy');
            Route::get('surat/{id}/audit-trail', [SuratController::class, 'auditTrail'])->name('surat.audit-trail');

            // Surat Keputusan (SK)
            Route::get('sk', [SuratKeputusanController::class, 'index'])->name('sk.index');
            Route::post('sk', [SuratKeputusanController::class, 'store'])->name('sk.store');
            Route::put('sk/{id}', [SuratKeputusanController::class, 'update'])->name('sk.update');
            Route::delete('sk/{id}', [SuratKeputusanController::class, 'destroy'])->name('sk.destroy');

            // Notulensi Rapat
            Route::get('notulensi', [NotulensiController::class, 'index'])->name('notulensi.index');
            Route::post('notulensi', [NotulensiController::class, 'store'])->name('notulensi.store');
            Route::put('notulensi/{id}', [NotulensiController::class, 'update'])->name('notulensi.update');
            Route::delete('notulensi/{id}', [NotulensiController::class, 'destroy'])->name('notulensi.destroy');
        });

        // Logout
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

        // Notifications
        Route::post('notifications/read-all', function () {
            auth()->guard('admin')->user()->unreadNotifications->markAsRead();
            return back();
        })->name('notifications.readAll');

        // Notification Preferences Settings (12d)
        Route::get('settings/notifications', [NotificationSettingController::class, 'edit'])->name('settings.notifications');
        Route::post('settings/notifications', [NotificationSettingController::class, 'update'])->name('settings.notifications.update');

        // Activity Logs / Audit Trail (12b)
        Route::get('settings/activity-logs', [\App\Http\Controllers\Admin\AdminActivityLogController::class, 'index'])->name('settings.activity-logs');
        Route::get('settings/activity-logs/export-pdf', [\App\Http\Controllers\Admin\AdminActivityLogController::class, 'exportPdf'])->name('settings.activity-logs.export-pdf');
        Route::get('settings/activity-logs/export-txt', [\App\Http\Controllers\Admin\AdminActivityLogController::class, 'exportTxt'])->name('settings.activity-logs.export-txt');

        // Profile
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
        Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');

        // Organisasi & Temu Karya CRUD
        Route::resource('organisasi', OrganisasiController::class);
        Route::get('temu-karya', [TemuKaryaController::class, 'index'])->name('temu-karya.index');
        Route::post('temu-karya', [TemuKaryaController::class, 'store'])->name('temu-karya.store');
        Route::put('temu-karya/{temuKarya}', [TemuKaryaController::class, 'update'])->name('temu-karya.update');
        Route::delete('temu-karya/{temuKarya}', [TemuKaryaController::class, 'destroy'])->name('temu-karya.destroy');

        // Program CRUD (Khusus PNKT / Sesuai Brief)
        Route::get('program/get-pics', [\App\Http\Controllers\Admin\ProgramController::class, 'getPicsByJabatan'])->name('program.get-pics');
        Route::patch('program/{program}/update-status', [\App\Http\Controllers\Admin\ProgramController::class, 'updateStatus'])->name('program.update-status');
        Route::delete('program/bulk-delete', [\App\Http\Controllers\Admin\ProgramController::class, 'bulkDestroy'])->name('program.bulk-delete');
        Route::resource('program', \App\Http\Controllers\Admin\ProgramController::class);
        
        // Pengaturan Halaman Program
        Route::get('program-settings', [\App\Http\Controllers\Admin\PageSettingController::class, 'edit'])->name('program.settings');
        Route::post('program-settings', [\App\Http\Controllers\Admin\PageSettingController::class, 'update'])->name('program.settings.update');

        // Master Jabatan CRUD (Super Admin & PNKT only, logic handled in controller)
        Route::delete('jabatan/bulk-delete', [\App\Http\Controllers\Admin\JabatanController::class, 'bulkDestroy'])->name('jabatan.bulk-delete');
        Route::resource('jabatan', \App\Http\Controllers\Admin\JabatanController::class);

        // Direktori Kontak
        Route::get('kontak/export', [KontakController::class, 'export'])->name('kontak.export');
        Route::get('kontak', [KontakController::class, 'index'])->name('kontak.index');

        // E-Katalog CRUD dengan Approval System (BPD only)
        Route::prefix('katalog')->name('katalog.')->group(function () {
            // List semua katalog
            Route::get('/', [AdminKatalogController::class, 'index'])->name('index');

            // Create & Store (jika admin ingin buat manual)
            Route::get('/create', [AdminKatalogController::class, 'create'])->name('create');
            Route::post('/', [AdminKatalogController::class, 'store'])->name('store');

            // Show detail katalog untuk review
            Route::get('/{katalog}', [AdminKatalogController::class, 'show'])->name('show');

            // Edit & Update
            Route::get('/{katalog}/edit', [AdminKatalogController::class, 'edit'])->name('edit');
            Route::put('/{katalog}', [AdminKatalogController::class, 'update'])->name('update');

            // Approval Actions
            Route::post('/{katalog}/approve', [AdminKatalogController::class, 'approve'])->name('approve');
            Route::post('/{katalog}/revision', [AdminKatalogController::class, 'revision'])->name('revision');
            Route::post('/{katalog}/reject', [AdminKatalogController::class, 'reject'])->name('reject');

            // Toggle active status
            Route::post('/{katalog}/toggle', [AdminKatalogController::class, 'toggleStatus'])->name('toggle');

            // Delete
            Route::delete('/{katalog}', [AdminKatalogController::class, 'destroy'])->name('destroy');

            // Kategori CRUD
            Route::get('/kategori', [AdminKatalogController::class, 'kategoriIndex'])->name('kategori');
            Route::post('/kategori', [AdminKatalogController::class, 'kategoriStore'])->name('kategori.store');
            Route::put('/kategori/{kategori}', [AdminKatalogController::class, 'kategoriUpdate'])->name('kategori.update');
            Route::delete('/kategori/{kategori}', [AdminKatalogController::class, 'kategoriDestroy'])->name('kategori.destroy');
        });

        // Misi CRUD (BPD only)
        Route::resource('misi', MisiController::class);

        // Berita CRUD (BPD only)
        Route::get('berita', [AdminBeritaController::class, 'index'])->name('berita.index');
        Route::get('berita/create', [AdminBeritaController::class, 'create'])->name('berita.create');
        Route::post('berita', [AdminBeritaController::class, 'store'])->name('berita.store');
        Route::get('berita/{id}/edit', [AdminBeritaController::class, 'edit'])->name('berita.edit');
        Route::put('berita/{id}', [AdminBeritaController::class, 'update'])->name('berita.update');
        Route::delete('berita/{id}', [AdminBeritaController::class, 'destroy'])->name('berita.destroy');

        // Anggota Management
        Route::prefix('anggota')->name('anggota.')->group(function () {
            Route::get('/', [AnggotaManagementController::class, 'index'])->name('index');
            Route::get('/create', [AnggotaManagementController::class, 'create'])->name('create');
            Route::post('/', [AnggotaManagementController::class, 'store'])->name('store');
            Route::get('/list', [AnggotaManagementController::class, 'index'])->name('list');
            Route::get('/export', [AnggotaManagementController::class, 'export'])->name('export');
            Route::get('/download-template', [AnggotaManagementController::class, 'downloadTemplate'])->name('download-template');
            Route::post('/import', [AnggotaManagementController::class, 'import'])->name('import');
            Route::get('/export-credentials', [AnggotaManagementController::class, 'exportCredentials'])->name('export-credentials');

            Route::post('/bulk-destroy', [AnggotaManagementController::class, 'bulkDestroy'])->name('bulk-destroy');

            Route::get('/{anggota}', [AnggotaManagementController::class, 'show'])->name('show');

            // Update data anggota
            Route::put('/{anggota}/update', [AnggotaManagementController::class, 'update'])->name('update');

            // Update password anggota
            Route::put('/{anggota}/update-password', [AnggotaManagementController::class, 'updatePassword'])->name('update-password');

            // Approve & Reject
            Route::post('/{anggota}/approve', [AnggotaManagementController::class, 'approve'])->name('approve');
            Route::post('/{anggota}/reject', [AnggotaManagementController::class, 'reject'])->name('reject');

            // Delete & Restore
            Route::delete('/{anggota}', [AnggotaManagementController::class, 'destroy'])->name('destroy');
            Route::put('/{anggota}/restore', [AnggotaManagementController::class, 'restore'])->name('restore');
            Route::delete('/{anggota}/force-delete', [AnggotaManagementController::class, 'forceDelete'])->name('force-delete');
        });

        // =====================================================
        // TRASH MANAGEMENT (SUPER ADMIN ONLY)
        // =====================================================
        Route::prefix('trash')->name('trash.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\TrashController::class, 'index'])->name('index');
            Route::post('/anggota/bulk-restore', [\App\Http\Controllers\Admin\TrashController::class, 'bulkRestoreAnggota'])->name('anggota.bulk-restore');
            Route::delete('/anggota/bulk-force-delete', [\App\Http\Controllers\Admin\TrashController::class, 'bulkForceDeleteAnggota'])->name('anggota.bulk-force-delete');
        });
    });
});

// =====================================================
// ANGGOTA AUTH ROUTES
// =====================================================
Route::prefix('anggota')->name('anggota.')->group(function () {
    // Guest Routes (belum login)
    Route::middleware('guest:anggota')->group(function () {
        Route::get('login', [AnggotaAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AnggotaAuthController::class, 'login'])->name('login.post');
    });

    // Protected Routes (harus login)
    Route::middleware('auth:anggota')->group(function () {
        Route::post('logout', [AnggotaAuthController::class, 'logout'])->name('logout');
        
        // Notifications
        Route::post('notifications/read-all', function () {
            auth('anggota')->user()->unreadNotifications->markAsRead();
            return back();
        })->name('notifications.readAll');
    });
});

// =====================================================
// PUBLIC ROUTES
// =====================================================

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Strategic Plan Public Route
Route::get('/strategic-plan/{strategicPlan}', [StrategicPlanController::class, 'show'])
    ->name('strategic-plan.detail');

// Agenda Public Feed (For Google Calendar Auto-Sync)
Route::get('/agenda/feed.ics', [AgendaController::class, 'publicICalFeed'])->name('agenda.public-feed');

// E-Katalog Public Routes
Route::get('/e-katalog', [KatalogController::class, 'index'])->name('e-katalog');
Route::get('/e-katalog/{katalog}', [KatalogController::class, 'show'])->name('e-katalog.detail');

// Berita Public Routes
Route::get('/berita', [BeritaController::class, 'index'])->name('berita');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita-detail');

// Other Public Pages
Route::get('/organisasi', function () {
    $organisasi = \App\Models\Organisasi::aktif()
        ->orderBy('urutan', 'asc')
        ->get();

    $nodesByUrutan = [];
    foreach ($organisasi as $org) {
        $org->children = collect();
        if (!isset($nodesByUrutan[$org->urutan])) {
            $nodesByUrutan[$org->urutan] = [];
        }
        $nodesByUrutan[$org->urutan][] = $org;
    }

    $organisasiTree = collect();
    foreach ($organisasi as $org) {
        $parts = explode('.', $org->urutan);
        if (count($parts) > 1) {
            array_pop($parts);
            $parentUrutan = implode('.', $parts);
            if (isset($nodesByUrutan[$parentUrutan])) {
                $nodesByUrutan[$parentUrutan][0]->children->push($org);
            } else {
                $organisasiTree->push($org);
            }
        } else {
            $organisasiTree->push($org);
        }
    }

    return view('pages.organisasi', compact('organisasiTree'));
})->name('organisasi');
Route::get('/organisasi/{nama}', [PublicOrganisasiController::class, 'show'])
    ->name('organisasi.show');
Route::view('/informasi-kegiatan', 'pages.informasi-kegiatan')->name('informasi-kegiatan');

Route::view('/about', 'pages.about')->name('about');
Route::view('/vision-mission', 'pages.visi-misi')->name('vision-mission');
Route::view('/how-to-join', 'pages.how-to-join')->name('how-to-join');
Route::view('/contact', 'pages.contact')->name('contact');
Route::get('/program/csr', [PublicProgramController::class, 'csr'])->name('program.csr');
Route::get('/program/csr/{id}', [PublicProgramController::class, 'csrDetail'])->name('program.csr.detail');
Route::get('/program/bidang', [PublicProgramController::class, 'bidang'])->name('program.bidang');
Route::get('/program/bidang/{id}', [PublicProgramController::class, 'bidangDetail'])->name('program.bidang.detail');

// Agenda Public Route
Route::view('/agenda', 'pages.agenda')->name('agenda.public');
Route::view('/agenda/detail/{slug}', 'pages.agenda-detail')->name('agenda.detail');

// Active Member & Outline
Route::get('/active-member-asita', [KatalogController::class, 'letter'])->name('active-member');
Route::get('/outline-of-asita', [OutlineController::class, 'index'])->name('outline');

// Buku Anggota Routes
Route::get('/buku-informasi-anggota', [BukuAnggotaController::class, 'index'])->name('buku-anggota');
Route::get('/buku-informasi-anggota/{anggota}', [BukuAnggotaController::class, 'show'])->name('detail-buku');

// =====================================================
// JOIN US (PENDAFTARAN ANGGOTA)
// =====================================================

// Form Pendaftaran (Public - tapi redirect kalau sudah login)
Route::get('/join-us', function () {
    if (Auth::guard('anggota')->check()) {
        return redirect()->route('profile-anggota')->with('info', 'Anda sudah terdaftar sebagai anggota.');
    }
    return view('pages.join-us');
})->name('join-us');

// Submit Pendaftaran
Route::post('/join-us', [AnggotaController::class, 'store'])->name('jadi-anggota.store');

// Registration Success Page (Harus sudah login + ada session password)
Route::get('/registration-success', [AnggotaController::class, 'registrationSuccess'])
    ->middleware('auth:anggota')
    ->name('registration-success');

// =====================================================
// MEMBER REGISTER (ANGGOTA EXISTING, BELUM DAFTAR WEB)
// =====================================================

// Tampil form simpel untuk member yang sudah punya NIA tapi belum daftar web
Route::get('/member-register', [AnggotaController::class, 'showMemberRegister'])->name('member-register');

// Submit form member register
Route::post('/member-register', [AnggotaController::class, 'storeMember'])->name('member-register.store');

// =====================================================
// ANGGOTA PROFILE ROUTES (Protected)
// =====================================================
Route::middleware('auth:anggota')->group(function () {
    // ============= PROFILE DASHBOARD =============
    Route::get('/profile-anggota', [AnggotaController::class, 'profile'])->name('profile-anggota');

    // ============= PROFILE UPDATE ACTIONS =============
    Route::post('/profile-anggota/change-password', [AnggotaController::class, 'changePassword'])
        ->name('profile-anggota.change-password');

    Route::post('/profile-anggota/update-profile', [AnggotaController::class, 'updateProfile'])
        ->name('profile-anggota.update-profile');

    // ============= KATALOG MANAGEMENT (MULTIPLE KATALOG) =============
    // ============= KATALOG MANAGEMENT (MULTIPLE KATALOG) =============
    Route::prefix('profile-anggota/katalog')->name('anggota.katalog.')->group(function () {
        Route::get('/', [AnggotaKatalogController::class, 'index'])->name('index');
        Route::get('create', [AnggotaKatalogController::class, 'create'])->name('create');
        Route::post('create', [AnggotaKatalogController::class, 'store'])->name('store');
        Route::get('{katalog}/edit', [AnggotaKatalogController::class, 'edit'])->name('edit');
        Route::put('{katalog}', [AnggotaKatalogController::class, 'update'])->name('update');
        Route::delete('{katalog}', [AnggotaKatalogController::class, 'destroy'])->name('destroy');
        Route::delete('{katalog}/delete-image', [AnggotaKatalogController::class, 'deleteImage'])->name('delete-image');
        Route::post('{katalog}/toggle-status', [AnggotaKatalogController::class, 'toggleStatus'])->name('toggle-status');
    });
});