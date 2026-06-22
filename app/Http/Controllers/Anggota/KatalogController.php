<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Katalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class KatalogController extends Controller
{
    public function index()
    {
        $anggota = Auth::guard('anggota')->user();
        // MULTIPLE KATALOG: Ambil semua katalog milik anggota ini
        $katalogs = Katalog::where('anggota_id', $anggota->id)->latest()->get();
        
        return view('anggota.katalog.index', compact('anggota', 'katalogs'));
    }

    public function create()
    {
        $anggota = Auth::guard('anggota')->user();
        
        // VALIDASI: Hanya anggota approved yang bisa submit katalog
        if ($anggota->status !== 'approved') {
            return redirect()->route('anggota.katalog.index')
                ->with('error', 'Hanya anggota terverifikasi yang dapat menambahkan katalog.');
        }
        
        // MULTIPLE KATALOG: Tidak ada lagi validasi existing katalog
        return view('anggota.katalog.create', compact('anggota'));
    }

    public function store(Request $request)
    {
        $anggota = Auth::guard('anggota')->user();
        
        // VALIDASI: Hanya anggota approved
        if ($anggota->status !== 'approved') {
            return back()->with('error', 'Hanya anggota terverifikasi yang dapat menambahkan katalog.');
        }
        
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'business_field' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'map_embed_url' => 'nullable|string',
        ]);

        $data = [
            'anggota_id' => $anggota->id,
            'company_name' => $request->company_name,
            'business_field' => $request->business_field,
            'description' => $request->description,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'is_active' => true,
            'created_by_type' => 'anggota',
            'created_by_id' => $anggota->id,
            'status' => 'pending',
        ];

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

        Log::info('New katalog created by anggota', [
            'katalog_id' => $katalog->id,
            'anggota_id' => $anggota->id,
        ]);

        return redirect()->route('anggota.katalog.index')
            ->with('success', 'Katalog berhasil ditambahkan dan menunggu persetujuan admin.');
    }

    public function edit(Katalog $katalog)
    {
        $anggota = Auth::guard('anggota')->user();
        
        // VALIDASI: Hanya anggota approved
        if ($anggota->status !== 'approved') {
            return redirect()->route('anggota.katalog.index')
                ->with('error', 'Hanya anggota terverifikasi yang dapat mengedit katalog.');
        }
        
        // VALIDASI: Hanya bisa edit katalog milik sendiri
        if ((int) $katalog->anggota_id !== (int) $anggota->id) {
            abort(403, 'Anda tidak memiliki akses ke katalog ini.');
        }
        
        return view('anggota.katalog.edit', compact('anggota', 'katalog'));
    }

    public function update(Request $request, Katalog $katalog)
    {
        $anggota = Auth::guard('anggota')->user();
        
        // VALIDASI: Hanya anggota approved
        if ($anggota->status !== 'approved') {
            return back()->with('error', 'Hanya anggota terverifikasi yang dapat mengedit katalog.');
        }
        
        // VALIDASI: Hanya bisa edit katalog milik sendiri
        if ((int) $katalog->anggota_id !== (int) $anggota->id) {
            abort(403, 'Anda tidak memiliki akses ke katalog ini.');
        }

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'business_field' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'map_embed_url' => 'nullable|string',
        ]);

        $data = [
            'company_name' => $request->company_name,
            'business_field' => $request->business_field,
            'description' => $request->description,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
        ];

        // Cek perubahan signifikan untuk reset ke pending
        if ($katalog->status === 'approved') {
            $significantChanges = 
                $katalog->company_name !== $request->company_name ||
                $katalog->business_field !== $request->business_field ||
                $katalog->description !== $request->description ||
                $request->hasFile('logo') ||
                $request->hasFile('images');
            
            if ($significantChanges) {
                $data['status'] = 'pending';
                $data['approved_by'] = null;
                $data['approved_at'] = null;
                $data['rejection_reason'] = null;
            }
        } else {
            $data['status'] = 'pending';
            $data['rejection_reason'] = null;
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
            
            if ($embedUrl === null) {
                return back()
                    ->withInput()
                    ->withErrors(['map_embed_url' => 'Format kode embed tidak valid.']);
            }
            
            $data['map_embed_url'] = $embedUrl;
        }

        $katalog->update($data);
        
        $message = isset($data['status']) && $data['status'] === 'pending' 
            ? 'Katalog berhasil diperbarui dan menunggu persetujuan admin.'
            : 'Katalog berhasil diperbarui!';

        Log::info('Katalog updated', ['katalog_id' => $katalog->id]);

        return redirect()->route('anggota.katalog.index')->with('success', $message);
    }

    public function destroy(Katalog $katalog)
    {
        $anggota = Auth::guard('anggota')->user();
        
        // VALIDASI: Hanya anggota approved
        if ($anggota->status !== 'approved') {
            return back()->with('error', 'Hanya anggota terverifikasi yang dapat menghapus katalog.');
        }
        
        // VALIDASI: Hanya bisa hapus katalog milik sendiri
        if ((int) $katalog->anggota_id !== (int) $anggota->id) {
            abort(403, 'Anda tidak memiliki akses ke katalog ini.');
        }

        // Delete files
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

        Log::info('Katalog deleted', [
            'katalog_id' => $katalog->id,
            'anggota_id' => $anggota->id,
        ]);

        $katalog->delete();

        return redirect()->route('anggota.katalog.index')
            ->with('success', 'Katalog berhasil dihapus.');
    }

    public function toggleStatus(Katalog $katalog)
    {
        $anggota = Auth::guard('anggota')->user();
        
        // VALIDASI: Hanya anggota approved
        if ($anggota->status !== 'approved') {
            return back()->with('error', 'Hanya anggota terverifikasi yang dapat mengaktifkan/menonaktifkan katalog.');
        }
        
        // VALIDASI: Hanya bisa toggle katalog milik sendiri
        if ((int) $katalog->anggota_id !== (int) $anggota->id) {
            abort(403, 'Anda tidak memiliki akses ke katalog ini.');
        }

        // Hanya bisa toggle jika sudah approved
        if ($katalog->status !== 'approved') {
            return back()->with('error', 'Katalog harus disetujui admin terlebih dahulu.');
        }
        
        $newStatus = !$katalog->is_active;
        $katalog->update(['is_active' => $newStatus]);
        
        $status = $newStatus ? 'diaktifkan' : 'dinonaktifkan';
        
        Log::info('Katalog status toggled', [
            'katalog_id' => $katalog->id,
            'new_status' => $newStatus,
        ]);
        
        return back()->with('success', "Katalog berhasil {$status}.");
    }

    public function deleteImage(Request $request, Katalog $katalog)
    {
        $request->validate([
            'image_index' => 'required|integer|min:0',
        ]);

        $anggota = Auth::guard('anggota')->user();
        
        // VALIDASI: Hanya anggota approved
        if ($anggota->status !== 'approved') {
            return back()->with('error', 'Hanya anggota terverifikasi yang dapat menghapus gambar.');
        }
        
        // VALIDASI: Hanya bisa hapus gambar dari katalog milik sendiri
        if ((int) $katalog->anggota_id !== (int) $anggota->id) {
            abort(403, 'Anda tidak memiliki akses ke katalog ini.');
        }

        $images = $katalog->images ?? [];

        if (isset($images[$request->image_index])) {
            if (Storage::disk('public')->exists($images[$request->image_index])) {
                Storage::disk('public')->delete($images[$request->image_index]);
            }
            unset($images[$request->image_index]);
            $katalog->update(['images' => array_values($images)]);
            
            return back()->with('success', 'Gambar berhasil dihapus.');
        }

        return back()->with('error', 'Gambar tidak ditemukan.');
    }

    /**
     * Extract Google Maps Embed URL
     */
    private function extractGoogleMapsEmbedUrl($input)
    {
        if (empty($input)) {
            return null;
        }

        $input = trim($input);

        // Extract dari iframe src attribute
        if (preg_match('/src=["\']([^"\']+)["\']/', $input, $matches)) {
            $url = $matches[1];
            
            if (strpos($url, 'google.com/maps/embed') !== false || 
                (strpos($url, 'google.com/maps') !== false && strpos($url, 'output=embed') !== false)) {
                return $url;
            }
        }

        // Jika langsung URL embed
        if (strpos($input, 'google.com/maps/embed') !== false || 
            (strpos($input, 'google.com/maps') !== false && strpos($input, 'output=embed') !== false)) {
            return $input;
        }

        return null;
    }
}