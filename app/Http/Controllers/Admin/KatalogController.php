<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Katalog;
use App\Models\KategoriEatalog;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class KatalogController extends Controller
{
    private function checkAuthorization($viewOnly = false)
    {
        $admin = auth()->guard('admin')->user();
        if (!$admin->canManageContent()) {
            if ($viewOnly && $admin->isPimpinan()) {
                return; // Pimpinan allowed for view only
            }
            abort(403, 'Akses ditolak: Anda tidak memiliki hak akses ke fitur ini.');
        }
    }

    public function index(Request $request)
    {
        $this->checkAuthorization(true);

        $query = Katalog::with('kategori');

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by kategori
        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori_id', $request->kategori);
        }

        // Filter by wilayah
        if ($request->filled('wilayah') && $request->wilayah !== '') {
            $query->where('wilayah', 'like', '%' . $request->wilayah . '%');
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('business_field', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $katalogs = $query->latest()->paginate(15);

        // Get kategoris for dropdown
        $kategoris = KategoriEatalog::active()->orderBy('nama')->get();

        // Get unique wilayah for filter
        $wilayahList = Katalog::whereNotNull('wilayah')
            ->where('wilayah', '!=', '')
            ->distinct()
            ->pluck('wilayah')
            ->sort()
            ->values();

        // Statistics
        $stats = [
            'total' => Katalog::count(),
            'pending' => Katalog::where('status', 'pending')->count(),
            'revision' => Katalog::where('status', 'revision')->count(),
            'approved' => Katalog::where('status', 'approved')->count(),
            'rejected' => Katalog::where('status', 'rejected')->count(),
        ];

        return view('admin.katalog.index', compact('katalogs', 'stats', 'kategoris', 'wilayahList'));
    }

    public function create()
    {
        $this->checkAuthorization();

        try {
            $anggotas = Anggota::where('status', 'approved')
                ->orderBy('nama_lengkap')
                ->get();
        } catch (\Exception $e) {
            $anggotas = collect([]);
        }

        $kategoris = KategoriEatalog::active()->orderBy('nama')->get();

        return view('admin.katalog.create', compact('anggotas', 'kategoris'));
    }

    public function store(Request $request)
    {
        $this->checkAuthorization();

        $validated = $request->validate([
            'anggota_id' => 'nullable|integer',
            'company_name' => 'required|string|max:255',
            'business_field' => 'required|string|max:255',
            'kategori_id' => 'nullable|exists:kategori_ekatalog,id',
            'harga' => 'nullable|string|max:50',
            'description' => 'required|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'required|string|max:500',
            'wilayah' => 'nullable|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'website_url' => 'nullable|url|max:255',
            'marketplace_url' => 'nullable|url|max:255',
            'map_embed_url' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = [
            'company_name' => $request->company_name,
            'business_field' => $request->business_field,
            'kategori_id' => $request->kategori_id,
            'harga' => $request->harga,
            'description' => $request->description,
            'address' => $request->address,
            'wilayah' => $request->wilayah,
            'phone' => $request->phone,
            'email' => $request->email,
            'website_url' => $request->website_url,
            'marketplace_url' => $request->marketplace_url,
            'is_active' => $request->has('is_active'),
            'created_by_type' => 'admin',
            'created_by_id' => Auth::guard('admin')->id(),
            'status' => 'approved',
            'approved_by' => Auth::guard('admin')->id(),
            'approved_at' => now(),
        ];

        if ($request->filled('anggota_id')) {
            $data['anggota_id'] = $request->anggota_id;
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('katalog/logos', 'public');
        }

        // Handle multiple images upload (max 3)
        if ($request->hasFile('images')) {
            $imagePaths = [];
            $imageFiles = array_slice($request->file('images'), 0, 3);
            foreach ($imageFiles as $image) {
                $imagePaths[] = $image->store('katalog/images', 'public');
            }
            $data['images'] = $imagePaths;
        }

        // Process Google Maps Embed URL
        if ($request->filled('map_embed_url')) {
            $embedUrl = $this->extractGoogleMapsEmbedUrl($request->map_embed_url);
            if ($embedUrl !== null) {
                $data['map_embed_url'] = $embedUrl;
            }
        }

        $katalog = Katalog::create($data);

        Log::info('Katalog created by admin', [
            'katalog_id' => $katalog->id,
            'admin_id' => Auth::guard('admin')->id(),
            'company_name' => $katalog->company_name,
        ]);

        return redirect()
            ->route('admin.katalog.index')
            ->with('success', 'Katalog berhasil ditambahkan!');
    }

    public function show(Katalog $katalog)
    {
        $this->checkAuthorization(true);

        try {
            $katalog->load('anggota', 'kategori');
        } catch (\Exception $e) {
            Log::warning('Failed to load relationships', [
                'katalog_id' => $katalog->id,
                'error' => $e->getMessage()
            ]);
        }

        return view('admin.katalog.show', compact('katalog'));
    }

    public function edit(Katalog $katalog)
    {
        $this->checkAuthorization();

        try {
            $anggotas = Anggota::where('status', 'approved')
                ->orderBy('nama_lengkap')
                ->get();
        } catch (\Exception $e) {
            $anggotas = collect([]);
        }

        $kategoris = KategoriEatalog::active()->orderBy('nama')->get();

        return view('admin.katalog.edit', compact('katalog', 'anggotas', 'kategoris'));
    }

    public function update(Request $request, Katalog $katalog)
    {
        $this->checkAuthorization();

        $validated = $request->validate([
            'anggota_id' => 'nullable|integer',
            'company_name' => 'required|string|max:255',
            'business_field' => 'required|string|max:255',
            'kategori_id' => 'nullable|exists:kategori_ekatalog,id',
            'harga' => 'nullable|string|max:50',
            'description' => 'required|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'required|string|max:500',
            'wilayah' => 'nullable|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'website_url' => 'nullable|url|max:255',
            'marketplace_url' => 'nullable|url|max:255',
            'map_embed_url' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = [
            'company_name' => $request->company_name,
            'business_field' => $request->business_field,
            'kategori_id' => $request->kategori_id,
            'harga' => $request->harga,
            'description' => $request->description,
            'address' => $request->address,
            'wilayah' => $request->wilayah,
            'phone' => $request->phone,
            'email' => $request->email,
            'website_url' => $request->website_url,
            'marketplace_url' => $request->marketplace_url,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->filled('anggota_id')) {
            $data['anggota_id'] = $request->anggota_id;
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($katalog->logo && Storage::disk('public')->exists($katalog->logo)) {
                Storage::disk('public')->delete($katalog->logo);
            }
            $data['logo'] = $request->file('logo')->store('katalog/logos', 'public');
        }

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            if ($katalog->images) {
                foreach ($katalog->images as $oldImage) {
                    if (Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }

            $imagePaths = [];
            $imageFiles = array_slice($request->file('images'), 0, 3);
            foreach ($imageFiles as $image) {
                $imagePaths[] = $image->store('katalog/images', 'public');
            }
            $data['images'] = $imagePaths;
        }

        // Process Google Maps Embed URL
        if ($request->filled('map_embed_url')) {
            $embedUrl = $this->extractGoogleMapsEmbedUrl($request->map_embed_url);
            if ($embedUrl !== null) {
                $data['map_embed_url'] = $embedUrl;
            }
        }

        $katalog->update($data);

        Log::info('Katalog updated by admin', [
            'katalog_id' => $katalog->id,
            'admin_id' => Auth::guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.katalog.index')
            ->with('success', 'Katalog berhasil diperbarui!');
    }

    public function destroy(Katalog $katalog)
    {
        $this->checkAuthorization();

        if ($katalog->logo && Storage::disk('public')->exists($katalog->logo)) {
            Storage::disk('public')->delete($katalog->logo);
        }

        if ($katalog->images) {
            foreach ($katalog->images as $image) {
                if (Storage::disk('public')->exists($image)) {
                    Storage::disk('public')->delete($image);
                }
            }
        }

        Log::info('Katalog deleted by admin', [
            'katalog_id' => $katalog->id,
            'company_name' => $katalog->company_name,
            'admin_id' => Auth::guard('admin')->id(),
        ]);

        $katalog->delete();

        return redirect()
            ->route('admin.katalog.index')
            ->with('success', 'Katalog berhasil dihapus!');
    }

    public function approve(Katalog $katalog)
    {
        $this->checkAuthorization();

        if ($katalog->status === 'approved') {
            return back()->with('info', 'Katalog sudah disetujui sebelumnya.');
        }

        $katalog->update([
            'status' => 'approved',
            'approved_by' => Auth::guard('admin')->id(),
            'approved_at' => now(),
            'rejection_reason' => null,
            'revision_notes' => null,
            'is_active' => true,
        ]);

        Log::info('Katalog approved', [
            'katalog_id' => $katalog->id,
            'company_name' => $katalog->company_name,
            'admin_id' => Auth::guard('admin')->id(),
        ]);

        return back()->with('success', "Katalog {$katalog->company_name} berhasil disetujui!");
    }

    public function revision(Request $request, Katalog $katalog)
    {
        $this->checkAuthorization();

        $request->validate([
            'revision_notes' => 'required|string|max:500',
        ], [
            'revision_notes.required' => 'Catatan revisi wajib diisi.',
        ]);

        if ($katalog->status === 'rejected') {
            return back()->with('info', 'Katalog sudah ditolak. Tidak bisa diubah ke revisi.');
        }

        $katalog->update([
            'status' => 'revision',
            'revision_notes' => $request->revision_notes,
            'is_active' => false,
        ]);

        Log::info('Katalog sent to revision', [
            'katalog_id' => $katalog->id,
            'company_name' => $katalog->company_name,
            'notes' => $request->revision_notes,
            'admin_id' => Auth::guard('admin')->id(),
        ]);

        return back()->with('success', "Katalog {$katalog->company_name} dikirim untuk direvisi.");
    }

    public function reject(Request $request, Katalog $katalog)
    {
        $this->checkAuthorization();

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        if ($katalog->status === 'rejected') {
            return back()->with('info', 'Katalog sudah ditolak sebelumnya.');
        }

        $katalog->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'revision_notes' => null,
            'approved_by' => null,
            'approved_at' => null,
            'is_active' => false,
        ]);

        Log::info('Katalog rejected', [
            'katalog_id' => $katalog->id,
            'company_name' => $katalog->company_name,
            'reason' => $request->rejection_reason,
            'admin_id' => Auth::guard('admin')->id(),
        ]);

        return back()->with('success', "Katalog {$katalog->company_name} ditolak.");
    }

    public function toggleStatus(Katalog $katalog)
    {
        $this->checkAuthorization();

        $newStatus = !$katalog->is_active;

        $katalog->update(['is_active' => $newStatus]);

        $statusText = $newStatus ? 'diaktifkan' : 'dinonaktifkan';

        Log::info('Katalog status toggled by admin', [
            'katalog_id' => $katalog->id,
            'new_status' => $newStatus,
            'admin_id' => Auth::guard('admin')->id(),
        ]);

        return back()->with('success', "Katalog berhasil {$statusText}.");
    }

    // ============================================
    // CRUD Kategori E-Katalog
    // ============================================

    public function kategoriIndex()
    {
        $this->checkAuthorization();

        $kategoris = KategoriEatalog::orderBy('nama')->get();

        return view('admin.katalog.kategori', compact('kategoris'));
    }

    public function kategoriStore(Request $request)
    {
        $this->checkAuthorization();

        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:kategori_ekatalog,nama',
            'deskripsi' => 'nullable|string|max:255',
        ], [
            'nama.unique' => 'Nama kategori sudah ada.',
        ]);

        KategoriEatalog::create([
            'nama' => $request->nama,
            'slug' => \Illuminate\Support\Str::slug($request->nama),
            'deskripsi' => $request->deskripsi,
            'is_active' => true,
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function kategoriUpdate(Request $request, KategoriEatalog $kategori)
    {
        $this->checkAuthorization();

        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:kategori_ekatalog,nama,' . $kategori->id,
            'deskripsi' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ], [
            'nama.unique' => 'Nama kategori sudah ada.',
        ]);

        $kategori->update([
            'nama' => $request->nama,
            'slug' => \Illuminate\Support\Str::slug($request->nama),
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function kategoriDestroy(KategoriEatalog $kategori)
    {
        $this->checkAuthorization();

        // Check if kategori has katalogs
        if ($kategori->katalogs()->count() > 0) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih terhubung dengan katalog.');
        }

        $kategori->delete();

        return back()->with('success', 'Kategori berhasil dihapus!');
    }

    /**
     * Extract Google Maps Embed URL - STRICT VALIDATION
     */
    private function extractGoogleMapsEmbedUrl($input)
    {
        if (empty($input)) {
            return null;
        }

        $input = trim($input);

        if (preg_match('/src=["\']([^"\']+)["\']/', $input, $matches)) {
            $url = $matches[1];
            if (strpos($url, 'google.com/maps/embed') !== false ||
                (strpos($url, 'google.com/maps') !== false && strpos($url, 'output=embed') !== false)) {
                return $url;
            }
        }

        if (strpos($input, 'google.com/maps/embed') !== false ||
            (strpos($input, 'google.com/maps') !== false && strpos($input, 'output=embed') !== false)) {
            return $input;
        }

        Log::warning('Invalid Google Maps embed format detected', [
            'input' => substr($input, 0, 100),
            'admin_id' => Auth::guard('admin')->id(),
        ]);

        return null;
    }
}
