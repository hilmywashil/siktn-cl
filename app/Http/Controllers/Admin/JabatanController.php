<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use App\Traits\LogsAdminActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JabatanController extends Controller
{
    use LogsAdminActivity;
    /**
     * Check if the authenticated admin has authorization.
     */
    private function checkAuthorization()
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin->isSuperAdmin() && !$admin->isPNKT()) {
            abort(403, 'Akses ditolak. Hanya Super Admin dan PNKT yang berhak mengelola Master Jabatan.');
        }
    }

    public function index()
    {
        $jabatans = Jabatan::orderBy('urutan', 'asc')->get();
        
        // Group by urutan untuk mengatasi urutan kembar (misal ada dua jabatan dengan urutan '1')
        $nodesByUrutan = [];
        foreach ($jabatans as $jabatan) {
            $jabatan->children = collect();
            if (!isset($nodesByUrutan[$jabatan->urutan])) {
                $nodesByUrutan[$jabatan->urutan] = [];
            }
            $nodesByUrutan[$jabatan->urutan][] = $jabatan;
        }

        $jabatanTree = collect();

        // Bangun struktur pohon (tree)
        foreach ($jabatans as $jabatan) {
            $parts = explode('.', $jabatan->urutan);
            if (count($parts) > 1) {
                array_pop($parts);
                $parentUrutan = implode('.', $parts);
                
                // Jika parent ditemukan, masukkan sebagai anak ke parent pertama yang cocok
                if (isset($nodesByUrutan[$parentUrutan])) {
                    $nodesByUrutan[$parentUrutan][0]->children->push($jabatan);
                } else {
                    // Jika parent tidak ada, anggap sebagai root
                    $jabatanTree->push($jabatan);
                }
            } else {
                // Tidak ada titik = root (tingkat teratas)
                $jabatanTree->push($jabatan);
            }
        }
        // === Generate Suggestions for Dropdown ===
        $suggestions = [];
        $allUrutans = $jabatans->pluck('urutan')->toArray();

        // 1. Suggest Next Root
        $roots = array_filter($allUrutans, function($u) { return !str_contains($u, '.'); });
        $maxRoot = 0;
        foreach ($roots as $root) {
            if (is_numeric($root) && $root > $maxRoot) $maxRoot = (int)$root;
        }
        $nextRoot = (string)($maxRoot + 1);
        $suggestions[] = [
            'id' => $nextRoot,
            'text' => $nextRoot . ' (Jabatan Utama Baru)',
            'group' => '🌟 Buat Angka Baru'
        ];

        // 2. Suggest Next Sibling & First Child for each existing
        foreach ($jabatans as $jabatan) {
            $u = $jabatan->urutan;
            $rootPrefix = explode('.', $u)[0];
            $groupName = '▶ Kumpulan Angka ' . $rootPrefix;
            
            // Suggest First Child
            $childAlpha = $u . '.a';
            
            if (!in_array($childAlpha, $allUrutans)) {
                $suggestions[] = [
                    'id' => $childAlpha, 
                    'text' => $childAlpha . ' (Sub dari ' . $jabatan->nama_jabatan . ')',
                    'group' => $groupName
                ];
            }

            // Suggest Next Sibling
            $parts = explode('.', $u);
            $last = array_pop($parts);
            $parent = implode('.', $parts);
            
            $nextStr = $last;
            if (is_numeric($last)) {
                $nextStr = (int)$last + 1;
            } else {
                $nextStr = ++$nextStr; // Increment alphabet 'a' to 'b'
            }
            
            $nextSibling = $parent ? ($parent . '.' . $nextStr) : (string)$nextStr;
            
            if (!in_array($nextSibling, $allUrutans)) {
                // Find parent name if it exists
                $parentName = '';
                if ($parent) {
                    $parentModel = $jabatans->firstWhere('urutan', $parent);
                    if ($parentModel) {
                        $parentName = ' (Sub dari ' . $parentModel->nama_jabatan . ')';
                    }
                }
                
                $suggestions[] = [
                    'id' => $nextSibling, 
                    'text' => $nextSibling . $parentName,
                    'group' => $groupName
                ];
            }
        }
        
        // Remove duplicates
        $uniqueSuggestions = [];
        $seen = [];
        foreach ($suggestions as $sug) {
            if (!in_array($sug['id'], $seen)) {
                $seen[] = $sug['id'];
                $uniqueSuggestions[] = $sug;
            }
        }
        
        // Sort numerically/naturally by ID to keep the internal order logic clean
        usort($uniqueSuggestions, function($a, $b) {
            return strnatcmp($a['id'], $b['id']);
        });

        return view('admin.jabatan.index', [
            'jabatans' => $jabatans,
            'jabatanTree' => $jabatanTree,
            'uniqueSuggestions' => $uniqueSuggestions,
            'activeMenu' => 'jabatan'
        ]);
    }

    public function create()
    {
        $this->checkAuthorization();
        
        return view('admin.jabatan.create', [
            'activeMenu' => 'jabatan'
        ]);
    }

    public function store(Request $request)
    {
        $this->checkAuthorization();
        
        $request->validate([
            'nama_jabatan' => 'required|string|max:255|unique:jabatans,nama_jabatan',
            'urutan' => 'required|string|max:50'
        ], [
            'nama_jabatan.required' => 'Nama jabatan wajib diisi.',
            'nama_jabatan.unique' => 'Nama jabatan ini sudah ada.',
            'urutan.required' => 'Urutan jabatan wajib diisi.',
            'urutan.string' => 'Urutan jabatan harus berupa teks atau angka berformat.'
        ]);

        Jabatan::create($request->only('nama_jabatan', 'urutan'));

        $jab = Jabatan::latest()->first();
        $this->logActivity('jabatan', 'Tambah', $jab?->id, $request->nama_jabatan);

        return redirect()->route('admin.jabatan.index')->with('success', 'Master Jabatan berhasil ditambahkan.');
    }

    public function edit(Jabatan $jabatan)
    {
        $this->checkAuthorization();
        
        return view('admin.jabatan.edit', [
            'jabatan' => $jabatan,
            'activeMenu' => 'jabatan'
        ]);
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $this->checkAuthorization();
        
        $request->validate([
            'nama_jabatan' => 'required|string|max:255|unique:jabatans,nama_jabatan,' . $jabatan->id,
            'urutan' => 'required|string|max:50'
        ], [
            'nama_jabatan.required' => 'Nama jabatan wajib diisi.',
            'nama_jabatan.unique' => 'Nama jabatan ini sudah ada.',
            'urutan.required' => 'Urutan jabatan wajib diisi.',
            'urutan.string' => 'Urutan jabatan harus berupa teks atau angka berformat.'
        ]);

        $jabatan->update($request->only('nama_jabatan', 'urutan'));

        $this->logActivity('jabatan', 'Edit', $jabatan->id, $jabatan->nama_jabatan);

        return redirect()->route('admin.jabatan.index')->with('success', 'Master Jabatan berhasil diperbarui.');
    }

    public function destroy(Jabatan $jabatan)
    {
        $this->checkAuthorization();
        
        $label = $jabatan->nama_jabatan;
        $id = $jabatan->id;
        $jabatan->delete();

        $this->logActivity('jabatan', 'Hapus', $id, $label);

        return redirect()->route('admin.jabatan.index')->with('success', 'Master Jabatan berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $this->checkAuthorization();

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:jabatans,id'
        ]);

        Jabatan::whereIn('id', $request->ids)->delete();

        return redirect()->route('admin.jabatan.index')->with('success', count($request->ids) . ' Master Jabatan berhasil dihapus.');
    }
}
