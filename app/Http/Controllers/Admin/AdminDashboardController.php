<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Katalog;
use App\Models\Organisasi;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminDashboardController extends Controller
{
   public function index(): View
    {
        $admin = Auth::guard('admin')->user();
        
        // Dashboard untuk PKKT/BPC - hanya lihat statistik anggota di domisilinya
        if ($admin->isPKKT()) {
            $totalAnggota = Anggota::where('domisili', $admin->domisili)->count();
            $pendingAnggota = Anggota::where('domisili', $admin->domisili)->where('status', 'pending')->count();
            $approvedAnggota = Anggota::where('domisili', $admin->domisili)->where('status', 'approved')->count();
            $rejectedAnggota = Anggota::where('domisili', $admin->domisili)->where('status', 'rejected')->count();
            
            // 5 Anggota terbaru dari domisili BPC
            $recentAnggota = Anggota::where('domisili', $admin->domisili)
                ->latest()
                ->take(5)
                ->get();
            
            return view('admin.dashboard', compact(
                'admin',
                'totalAnggota',
                'pendingAnggota',
                'approvedAnggota',
                'rejectedAnggota',
                'recentAnggota'
            ));
        }
        
        // Dashboard untuk BPD & Super Admin (kelola seluruh web)
        $totalAdmins = Admin::count();
        $adminsBPC = Admin::where('category', 'bpc')->count();
        $adminsBPD = Admin::where('category', 'bpd')->count();
        $adminsSuperAdmin = Admin::where('category', 'super_admin')->count();
        $recentAdmins = Admin::latest()->take(5)->get();
        
        $totalKatalog = Katalog::where('is_active', true)->count();
        $totalKatalogInactive = Katalog::where('is_active', false)->count();
        $recentKatalogs = Katalog::where('is_active', true)->latest()->take(5)->get();
        
        // Statistik Anggota untuk BPD/Super Admin (dari semua domisili)
        $totalAnggotaApproved = Anggota::where('status', 'approved')->count();
        $totalAnggotaPending = Anggota::where('status', 'pending')->count();
        $totalAnggotaRejected = Anggota::where('status', 'rejected')->count();
        $totalAnggotaAll = Anggota::count();
        
        // 5 Anggota terbaru dari SEMUA domisili
        $recentAnggota = Anggota::latest()->take(5)->get();
        
        // Struktur Organisasi ASITA
        $totalOrganisasi = Organisasi::where('aktif', true)->count();
        $organisasiByKategori = [
            'ketua_umum' => Organisasi::aktif()->kategori('ketua_umum')->count(),
            'wakil_ketua_umum' => Organisasi::aktif()->kategori('wakil_ketua_umum')->count(),
            'ketua_bidang' => Organisasi::aktif()->kategori('ketua_bidang')->count(),
            'sekretaris_umum' => Organisasi::aktif()->kategori('sekretaris_umum')->count(),
            'wakil_sekretaris_umum' => Organisasi::aktif()->kategori('wakil_sekretaris_umum')->count(),
        ];
        $recentOrganisasi = Organisasi::aktif()->ordered()->take(5)->get();
        
        // Statistik Berita
        $totalBerita = \App\Models\Berita::count();
        $totalBeritaAktif = \App\Models\Berita::where('is_active', true)->count();
        $totalBeritaPopuler = \App\Models\Berita::where('is_populer', true)->count();
        $recentBerita = \App\Models\Berita::latest()->take(5)->get();
        
        return view('admin.dashboard', compact(
            'admin',
            'totalAdmins',
            'adminsBPC',
            'adminsBPD',
            'adminsSuperAdmin',
            'recentAdmins',
            'totalKatalog',
            'totalKatalogInactive',
            'recentKatalogs',
            'totalAnggotaApproved',
            'totalAnggotaPending',
            'totalAnggotaRejected',
            'totalAnggotaAll',
            'recentAnggota',
            'totalOrganisasi',
            'organisasiByKategori',
            'recentOrganisasi',
            'totalBerita',
            'totalBeritaAktif',
            'totalBeritaPopuler',
            'recentBerita'
        ));
    }
    
    public function infoAdmin(Request $request): View
    {
        $admin = Auth::guard('admin')->user();
        
        // Hanya Super Admin yang bisa akses
        if (!$admin->canManageAdmins()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
        
        $query = Admin::query();

        // Filter by category
        if ($request->has('role') && $request->role != '') {
            $query->where('category', $request->role);
        }

        // Search by name, email, username
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }
        
        $admins = $query->latest()->paginate(10)->appends($request->query());
        
        return view('admin.info-admin', compact('admin', 'admins'));
    }
    
    public function createAdmin(): View
    {
        $admin = Auth::guard('admin')->user();
        
        // Hanya Super Admin yang bisa akses
        if (!$admin->canManageAdmins()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
        
        return view('admin.create-admin', compact('admin'));
    }
    
    public function storeAdmin(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin->canManageAdmins()) {
            abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:admins',
            'email' => 'required|string|email|max:255|unique:admins',
            'no_hp' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'category' => 'required|in:super_admin,pimpinan,pnkt,ppkt,pkkt',
            'domisili' => 'required_if:category,ppkt,pkkt|nullable|string|max:255',
        ], [
            'domisili.required_if' => 'Domisili wajib diisi untuk wilayah PPKT / PKKT.',
        ]);

        $newAdmin = Admin::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'] ?? null,
            'is_active' => true,
            'password' => Hash::make($validated['password']),
            'category' => $validated['category'],
            'domisili' => in_array($validated['category'], ['ppkt', 'pkkt']) ? $validated['domisili'] : null,
        ]);
        
        $newAdmin->assignRole($validated['category']);

        return redirect()->route('admin.info-admin')
            ->with('success', 'Admin berhasil ditambahkan!')
            ->with('created_credentials', [
                'username' => $validated['username'],
                'password' => $validated['password'],
                'name' => $validated['name'],
                'role' => $newAdmin->role_display_name
            ]);
    }
    
    public function editAdmin(Admin $admin): View
    {
        $currentAdmin = Auth::guard('admin')->user();
        
        // Hanya Super Admin yang bisa akses
        if (!$currentAdmin->canManageAdmins()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
        
        return view('admin.edit-admin', compact('admin', 'currentAdmin'));
    }
    
    public function updateAdmin(Request $request, Admin $admin)
    {
        $currentAdmin = Auth::guard('admin')->user();
        
        if (!$currentAdmin->canManageAdmins()) {
            abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:admins,username,' . $admin->id,
            'email' => 'required|string|email|max:255|unique:admins,email,' . $admin->id,
            'no_hp' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'category' => 'required|in:super_admin,pimpinan,pnkt,ppkt,pkkt',
            'domisili' => 'required_if:category,ppkt,pkkt|nullable|string|max:255',
        ], [
            'domisili.required_if' => 'Domisili wajib diisi untuk wilayah PPKT / PKKT.',
        ]);

        $admin->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'] ?? null,
            'category' => $validated['category'],
            'domisili' => in_array($validated['category'], ['ppkt', 'pkkt']) ? $validated['domisili'] : null,
        ]);

        if ($request->filled('password')) {
            $admin->update(['password' => Hash::make($validated['password'])]);
        }
        
        $admin->syncRoles([$validated['category']]);

        return redirect()->route('admin.info-admin')->with('success', 'Admin berhasil diupdate!');
    }
    
    public function deleteAdmin(Admin $admin)
    {
        $currentAdmin = Auth::guard('admin')->user();
        
        // Hanya Super Admin yang bisa akses
        if (!$currentAdmin->canManageAdmins()) {
            abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }
        
        // Prevent deleting self
        if ($admin->id === $currentAdmin->id) {
            return redirect()->route('admin.info-admin')->with('error', 'Anda tidak bisa menghapus akun Anda sendiri!');
        }
        
        $admin->delete();
        return redirect()->route('admin.info-admin')->with('success', 'Admin berhasil dihapus!');
    }
}