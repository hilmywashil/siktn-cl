<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Traits\LogsAdminActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    use LogsAdminActivity;
    private function checkAccess($admin)
    {
        if (!$admin || !$admin->canManageContent()) {
            abort(403, 'Anda tidak memiliki akses untuk kelola berita.');
        }
    }

    public function index(Request $request)
    {
        $admin = auth()->guard('admin')->user();
        $this->checkAccess($admin);
        
        $query = Berita::with('author')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $beritas = $query->paginate(10);
        $kategorisDb = Berita::whereNotNull('kategori')->distinct()->pluck('kategori')->toArray();
        $kategoris = array_unique(array_merge(['Pengumuman', 'Kegiatan', 'Regulasi'], $kategorisDb));
        
        return view('admin.berita.index', compact('admin', 'beritas', 'kategoris'));
    }

    public function create()
    {
        $admin = auth()->guard('admin')->user();
        $this->checkAccess($admin);

        $kategorisDb = Berita::whereNotNull('kategori')->distinct()->pluck('kategori')->toArray();
        $kategoris = array_unique(array_merge(['Pengumuman', 'Kegiatan', 'Regulasi'], $kategorisDb));

        return view('admin.berita.create', compact('admin', 'kategoris'));
    }

    public function store(Request $request)
    {
        $admin = auth()->guard('admin')->user();
        $this->checkAccess($admin);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'gambar' => 'required|image|mimes:jpeg,jpg,png,webp|max:2048',
            'kategori' => 'required|string',
            'tags' => 'nullable|string',
            'status' => 'required|in:Draft,Published,Archived',
            'tanggal_publish' => 'required|date',
            'is_populer' => 'nullable|boolean',
        ]);

        // Parse tags to array if provided as comma separated
        $tagsArray = [];
        if (!empty($validated['tags'])) {
            $tagsArray = array_map('trim', explode(',', $validated['tags']));
        }

        // Upload gambar
        $path = null;
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $filename = time() . '_' . Str::slug($request->judul) . '.' . $gambar->getClientOriginalExtension();
            $path = $gambar->storeAs('berita', $filename, 'public');
        }

        Berita::create([
            'admin_id' => $admin->id,
            'judul' => $validated['judul'],
            'konten' => $validated['konten'],
            'gambar' => $path,
            'kategori' => $validated['kategori'],
            'tags' => $tagsArray,
            'status' => $validated['status'],
            'tanggal_publish' => $validated['tanggal_publish'],
            'is_populer' => $request->has('is_populer'),
        ]);

        $berita = Berita::latest()->first();
        $this->logActivity('berita', 'Tambah', $berita?->id, $validated['judul'], 'Status: ' . $validated['status']);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $admin = auth()->guard('admin')->user();
        $this->checkAccess($admin);

        $berita = Berita::findOrFail($id);
        
        $kategorisDb = Berita::whereNotNull('kategori')->distinct()->pluck('kategori')->toArray();
        $kategoris = array_unique(array_merge(['Pengumuman', 'Kegiatan', 'Regulasi'], $kategorisDb));

        return view('admin.berita.edit', compact('admin', 'berita', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $admin = auth()->guard('admin')->user();
        $this->checkAccess($admin);

        $berita = Berita::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'kategori' => 'required|string',
            'tags' => 'nullable|string',
            'status' => 'required|in:Draft,Published,Archived',
            'tanggal_publish' => 'required|date',
            'is_populer' => 'nullable|boolean',
        ]);

        // Parse tags to array
        $tagsArray = [];
        if (!empty($validated['tags'])) {
            $tagsArray = array_map('trim', explode(',', $validated['tags']));
        }

        $path = $berita->gambar;
        if ($request->hasFile('gambar')) {
            if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
                Storage::disk('public')->delete($berita->gambar);
            }
            $gambar = $request->file('gambar');
            $filename = time() . '_' . Str::slug($request->judul) . '.' . $gambar->getClientOriginalExtension();
            $path = $gambar->storeAs('berita', $filename, 'public');
        }

        $berita->update([
            'judul' => $validated['judul'],
            'konten' => $validated['konten'],
            'gambar' => $path,
            'kategori' => $validated['kategori'],
            'tags' => $tagsArray,
            'status' => $validated['status'],
            'tanggal_publish' => $validated['tanggal_publish'],
            'is_populer' => $request->has('is_populer'),
        ]);

        $this->logActivity('berita', 'Edit', $berita->id, $berita->judul, 'Status: ' . $validated['status']);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diupdate!');
    }

    public function destroy($id)
    {
        $admin = auth()->guard('admin')->user();
        $this->checkAccess($admin);

        $berita = Berita::findOrFail($id);

        if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
            Storage::disk('public')->delete($berita->gambar);
        }

        $label = $berita->judul;
        $id = $berita->id;
        $berita->delete();

        $this->logActivity('berita', 'Hapus', $id, $label);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus!');
    }
}