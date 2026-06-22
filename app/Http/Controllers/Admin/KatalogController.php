<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Katalog;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        // FIXED: Hapus with('anggota') karena bikin error
        $query = Katalog::query();

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
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

        // Statistics
        $stats = [
            'total' => Katalog::count(),
            'pending' => Katalog::where('status', 'pending')->count(),
            'approved' => Katalog::where('status', 'approved')->count(),
            'rejected' => Katalog::where('status', 'rejected')->count(),
        ];

        return view('admin.katalog.index', compact('katalogs', 'stats'));
    }

    public function create()
    {
        // Safe: Hanya ambil anggota yang approved
        try {
            $anggotas = Anggota::where('status', 'approved')
                ->orderBy('nama_perusahaan')
                ->get();
        } catch (\Exception $e) {
            // Jika tabel anggotas bermasalah, return empty collection
            $anggotas = collect([]);
        }
            
        return view('admin.katalog.create', compact('anggotas'));
    }

    public function store(Request $request)
    {
        // FIXED: Hapus validasi exists karena tabel anggotas belum ada
        $validated = $request->validate([
            'anggota_id' => 'nullable|integer',
            'company_name' => 'required|string|max:255',
            'business_field' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'map_embed_url' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = [
            'company_name' => $request->company_name,
            'business_field' => $request->business_field,
            'description' => $request->description,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'is_active' => $request->has('is_active'),
            'created_by_type' => 'admin',
            'created_by_id' => Auth::guard('admin')->id(),
            'status' => 'approved', // Admin-created katalog langsung approved
            'approved_by' => Auth::guard('admin')->id(),
            'approved_at' => now(),
        ];

        // Optional: Link to anggota if selected
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
            
            if ($embedUrl === null) {
                return back()
                    ->withInput()
                    ->withErrors(['map_embed_url' => 'Format kode embed tidak valid. Pastikan Anda meng-copy kode iframe dari Google Maps.']);
            }
            
            $data['map_embed_url'] = $embedUrl;
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
        // FIXED: Load anggota secara opsional dengan try-catch
        try {
            $katalog->load('anggota');
        } catch (\Exception $e) {
            // Jika gagal load anggota, lanjut tanpa error
            Log::warning('Failed to load anggota relationship', [
                'katalog_id' => $katalog->id,
                'error' => $e->getMessage()
            ]);
        }
        
        return view('admin.katalog.show', compact('katalog'));
    }

    public function edit(Katalog $katalog)
    {
        // Safe: Ambil anggota dengan error handling
        try {
            $anggotas = Anggota::where('status', 'approved')
                ->orderBy('nama_perusahaan')
                ->get();
        } catch (\Exception $e) {
            $anggotas = collect([]);
        }
            
        return view('admin.katalog.edit', compact('katalog', 'anggotas'));
    }

    public function update(Request $request, Katalog $katalog)
    {
        // FIXED: Hapus validasi exists
        $validated = $request->validate([
            'anggota_id' => 'nullable|integer',
            'company_name' => 'required|string|max:255',
            'business_field' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'map_embed_url' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = [
            'company_name' => $request->company_name,
            'business_field' => $request->business_field,
            'description' => $request->description,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'is_active' => $request->has('is_active'),
        ];

        // Optional: Update anggota link
        if ($request->filled('anggota_id')) {
            $data['anggota_id'] = $request->anggota_id;
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($katalog->logo && Storage::disk('public')->exists($katalog->logo)) {
                Storage::disk('public')->delete($katalog->logo);
            }
            $data['logo'] = $request->file('logo')->store('katalog/logos', 'public');
        }

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            // Delete old images
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
            
            if ($embedUrl === null) {
                return back()
                    ->withInput()
                    ->withErrors(['map_embed_url' => 'Format kode embed tidak valid. Pastikan Anda meng-copy kode iframe dari Google Maps.']);
            }
            
            $data['map_embed_url'] = $embedUrl;
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
        // Delete associated files
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
        if ($katalog->status === 'approved') {
            return back()->with('info', 'Katalog sudah disetujui sebelumnya.');
        }

        $katalog->update([
            'status' => 'approved',
            'approved_by' => Auth::guard('admin')->id(),
            'approved_at' => now(),
            'rejection_reason' => null,
            'is_active' => true, // Aktifkan otomatis saat approve
        ]);

        Log::info('Katalog approved', [
            'katalog_id' => $katalog->id,
            'company_name' => $katalog->company_name,
            'admin_id' => Auth::guard('admin')->id(),
        ]);

        // TODO: Send notification to anggota (optional)
        
        return back()->with('success', "Katalog {$katalog->company_name} berhasil disetujui!");
    }

    public function reject(Request $request, Katalog $katalog)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        if ($katalog->status === 'rejected') {
            return back()->with('info', 'Katalog sudah ditolak sebelumnya.');
        }

        $katalog->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => null,
            'approved_at' => null,
            'is_active' => false, // Nonaktifkan saat reject
        ]);

        Log::info('Katalog rejected', [
            'katalog_id' => $katalog->id,
            'company_name' => $katalog->company_name,
            'reason' => $request->rejection_reason,
            'admin_id' => Auth::guard('admin')->id(),
        ]);

        // TODO: Send notification to anggota with rejection reason (optional)

        return back()->with('success', "Katalog {$katalog->company_name} ditolak.");
    }

    public function toggleStatus(Katalog $katalog)
    {
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

    /**
     * Extract Google Maps Embed URL - STRICT VALIDATION
     * Hanya menerima kode iframe embed dari Google Maps
     */
    private function extractGoogleMapsEmbedUrl($input)
    {
        if (empty($input)) {
            return null;
        }

        $input = trim($input);

        // Validasi 1: Extract dari iframe src attribute
        if (preg_match('/src=["\']([^"\']+)["\']/', $input, $matches)) {
            $url = $matches[1];
            
            // Harus mengandung google.com/maps/embed atau output=embed
            if (strpos($url, 'google.com/maps/embed') !== false || 
                (strpos($url, 'google.com/maps') !== false && strpos($url, 'output=embed') !== false)) {
                return $url;
            }
        }

        // Validasi 2: Jika langsung URL embed (tanpa tag iframe)
        if (strpos($input, 'google.com/maps/embed') !== false || 
            (strpos($input, 'google.com/maps') !== false && strpos($input, 'output=embed') !== false)) {
            return $input;
        }

        // Jika tidak memenuhi kriteria di atas, return null (invalid)
        Log::warning('Invalid Google Maps embed format detected by admin', [
            'input' => substr($input, 0, 100), // Log first 100 chars only
            'admin_id' => Auth::guard('admin')->id(),
        ]);
        
        return null;
    }
}