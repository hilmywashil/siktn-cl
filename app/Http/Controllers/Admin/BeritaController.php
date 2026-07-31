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

        if ($admin->isPPKT() && !empty($admin->domisili)) {
            $query->where(function($q) use ($admin) {
                $q->where('wilayah', $admin->domisili)->orWhere('admin_id', $admin->id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('wilayah')) {
            $query->where('wilayah', $request->wilayah);
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
            'gambar' => 'required|image|mimes:jpeg,jpg,png,webp|max:10240',
            'kategori' => 'required|string',
            'wilayah' => 'nullable|string|max:255',
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

        // Tentukan wilayah, status & is_populer berdasarkan role
        $wilayahValue = $admin->isPPKT() ? ($admin->domisili ?? 'Nasional') : ($validated['wilayah'] ?? 'Nasional');
        $isPopulerValue = $admin->isPPKT() ? false : $request->has('is_populer');

        $statusValue = $validated['status'];
        if ($admin->isPPKT() && $statusValue === 'Published') {
            $statusValue = 'Pending_Review';
        }

        Berita::create([
            'admin_id' => $admin->id,
            'judul' => $validated['judul'],
            'konten' => $validated['konten'],
            'gambar' => $path,
            'kategori' => $validated['kategori'],
            'wilayah' => $wilayahValue,
            'tags' => $tagsArray,
            'status' => $statusValue,
            'tanggal_publish' => $validated['tanggal_publish'],
            'is_populer' => $isPopulerValue,
        ]);

        $berita = Berita::latest()->first();
        $this->logActivity('berita', 'Tambah', $berita?->id, $validated['judul'], 'Status: ' . $statusValue);

        if ($statusValue === 'Pending_Review') {
            $pnktAdmins = \App\Models\Admin::whereIn('category', ['super_admin', 'pimpinan', 'pnkt'])->get();
            if ($pnktAdmins->count() > 0) {
                \Illuminate\Support\Facades\Notification::send($pnktAdmins, new \App\Notifications\AdminNotification(
                    'berita_pending',
                    'Pengajuan Berita PPKT Menunggu ACC',
                    "Berita baru '{$validated['judul']}' dari PPKT {$wilayahValue} membutuhkan verifikasi dan persetujuan publikasi."
                ));
            }
            return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diajukan dan sedang menunggu persetujuan/ACC dari PNKT.');
        }

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $admin = auth()->guard('admin')->user();
        $this->checkAccess($admin);

        $berita = Berita::findOrFail($id);

        if ($admin->isPPKT() && !empty($admin->domisili) && $berita->wilayah !== $admin->domisili && $berita->admin_id !== $admin->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit berita wilayah lain.');
        }
        
        $kategorisDb = Berita::whereNotNull('kategori')->distinct()->pluck('kategori')->toArray();
        $kategoris = array_unique(array_merge(['Pengumuman', 'Kegiatan', 'Regulasi'], $kategorisDb));

        return view('admin.berita.edit', compact('admin', 'berita', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $admin = auth()->guard('admin')->user();
        $this->checkAccess($admin);

        $berita = Berita::findOrFail($id);

        if ($admin->isPPKT() && !empty($admin->domisili) && $berita->wilayah !== $admin->domisili && $berita->admin_id !== $admin->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah berita wilayah lain.');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:10240',
            'kategori' => 'required|string',
            'wilayah' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'status' => 'required|in:Draft,Published,Archived,Pending_Review',
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

        $wilayahValue = $admin->isPPKT() ? ($admin->domisili ?? $berita->wilayah) : ($validated['wilayah'] ?? 'Nasional');
        $isPopulerValue = $admin->isPPKT() ? false : $request->has('is_populer');

        $statusValue = $validated['status'];
        if ($admin->isPPKT() && $statusValue === 'Published') {
            $statusValue = 'Pending_Review';
        }

        $berita->update([
            'judul' => $validated['judul'],
            'konten' => $validated['konten'],
            'gambar' => $path,
            'kategori' => $validated['kategori'],
            'wilayah' => $wilayahValue,
            'tags' => $tagsArray,
            'status' => $statusValue,
            'tanggal_publish' => $validated['tanggal_publish'],
            'is_populer' => $isPopulerValue,
        ]);

        $this->logActivity('berita', 'Edit', $berita->id, $berita->judul, 'Status: ' . $statusValue);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diupdate!');
    }

    public function approve($id)
    {
        $admin = auth()->guard('admin')->user();
        if (!$admin || !($admin->isSuperAdmin() || $admin->isPimpinan() || $admin->isPNKT())) {
            abort(403, 'Akses ditolak: Hanya Pengurus Nasional yang dapat menyetujui berita.');
        }

        $berita = Berita::findOrFail($id);
        $berita->update(['status' => 'Published']);

        $this->logActivity('berita', 'Approve', $berita->id, $berita->judul, 'Status diubah ke Published');

        return redirect()->back()->with('success', 'Berita PPKT berhasil disetujui dan dipublikasikan!');
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

    public function uploadImage(Request $request)
    {
        $admin = auth()->guard('admin')->user();
        if (!$admin || !$admin->canManageContent()) {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('berita/konten', $filename, 'public');
            
            return response()->json([
                'url' => asset('storage/' . $path)
            ]);
        }

        return response()->json(['error' => 'File tidak ditemukan'], 400);
    }
}