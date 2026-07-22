<?php

namespace App\Http\Controllers\Admin\Sekretariat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notulensi;
use App\Models\Agenda;
use Illuminate\Support\Facades\Auth;

class NotulensiController extends Controller
{
    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        $query = Notulensi::with('agenda');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul_rapat', 'like', "%{$search}%")
                  ->orWhere('pemimpin_rapat', 'like', "%{$search}%")
                  ->orWhere('ringkasan_hasil', 'like', "%{$search}%");
            });
        }

        $notulensis = $query->orderBy('tanggal_rapat', 'desc')->paginate(10)->appends($request->query());
        $agendas = Agenda::orderBy('waktu_mulai', 'desc')->get();

        return view('admin.sekretariat.notulensi.index', [
            'activeMenu' => 'sekretariat_notulensi',
            'admin' => $admin,
            'notulensis' => $notulensis,
            'agendas' => $agendas,
        ]);
    }

    public function store(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'agenda_id' => 'nullable|exists:agendas,id',
            'judul_rapat' => 'required|string|max:255',
            'tanggal_rapat' => 'required|date',
            'pemimpin_rapat' => 'nullable|string|max:255',
            'ringkasan_hasil' => 'nullable|string',
            'link_drive' => 'nullable|url',
        ]);

        Notulensi::create([
            'agenda_id' => $validated['agenda_id'] ?? null,
            'judul_rapat' => $validated['judul_rapat'],
            'tanggal_rapat' => $validated['tanggal_rapat'],
            'pemimpin_rapat' => $validated['pemimpin_rapat'] ?? null,
            'ringkasan_hasil' => $validated['ringkasan_hasil'] ?? null,
            'link_drive' => $validated['link_drive'] ?? null,
            'created_by' => $admin->id,
        ]);

        return redirect()->route('admin.sekretariat.notulensi.index')->with('success', 'Notulensi Rapat berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $notulensi = Notulensi::findOrFail($id);

        $validated = $request->validate([
            'agenda_id' => 'nullable|exists:agendas,id',
            'judul_rapat' => 'required|string|max:255',
            'tanggal_rapat' => 'required|date',
            'pemimpin_rapat' => 'nullable|string|max:255',
            'ringkasan_hasil' => 'nullable|string',
            'link_drive' => 'nullable|url',
        ]);

        $notulensi->update($validated);

        return redirect()->route('admin.sekretariat.notulensi.index')->with('success', 'Notulensi Rapat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $notulensi = Notulensi::findOrFail($id);
        $notulensi->delete();

        return redirect()->route('admin.sekretariat.notulensi.index')->with('success', 'Notulensi Rapat berhasil dihapus.');
    }
}
