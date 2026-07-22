<?php

namespace App\Http\Controllers\Admin\Sekretariat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuratKeputusan;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminNotification;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SuratKeputusanController extends Controller
{
    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        $query = SuratKeputusan::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_sk', 'like', "%{$search}%")
                  ->orWhere('judul_sk', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $sks = $query->orderBy('tanggal_berakhir', 'asc')->paginate(10)->appends($request->query());

        // Check for SK expiring within 6 months (180 days)
        $sixMonthsLater = Carbon::now()->addMonths(6);
        $expiringSks = SuratKeputusan::where('status', 'Aktif')
            ->whereBetween('tanggal_berakhir', [Carbon::now(), $sixMonthsLater])
            ->get();

        return view('admin.sekretariat.sk.index', [
            'activeMenu' => 'sekretariat_sk',
            'admin' => $admin,
            'sks' => $sks,
            'expiringSks' => $expiringSks,
        ]);
    }

    public function store(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'nomor_sk' => 'required|string|max:255',
            'judul_sk' => 'required|string|max:255',
            'tanggal_berlaku' => 'required|date',
            'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_berlaku',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'link_drive' => 'nullable|url',
            'keterangan' => 'nullable|string',
        ]);

        $sk = SuratKeputusan::create([
            'nomor_sk' => $validated['nomor_sk'],
            'judul_sk' => $validated['judul_sk'],
            'tanggal_berlaku' => $validated['tanggal_berlaku'],
            'tanggal_berakhir' => $validated['tanggal_berakhir'],
            'status' => $validated['status'],
            'link_drive' => $validated['link_drive'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
            'created_by' => $admin->id,
        ]);

        // Cek jika SK yang baru dibuat akan berakhir dalam <= 6 bulan
        $daysUntilExpired = Carbon::now()->diffInDays(Carbon::parse($sk->tanggal_berakhir), false);
        if ($sk->status === 'Aktif' && $daysUntilExpired >= 0 && $daysUntilExpired <= 180) {
            $admins = Admin::whereIn('category', ['super_admin', 'pimpinan', 'pnkt'])->get();
            if ($admins->count() > 0) {
                Notification::send($admins, new AdminNotification(
                    'sk_expired',
                    'Pengingat Masa Berlaku SK',
                    "Surat Keputusan '{$sk->nomor_sk}' ({$sk->judul_sk}) akan habis masa berlakunya dalam {$daysUntilExpired} hari lagi."
                ));
            }
        }

        return redirect()->route('admin.sekretariat.sk.index')->with('success', 'Surat Keputusan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $sk = SuratKeputusan::findOrFail($id);

        $validated = $request->validate([
            'nomor_sk' => 'required|string|max:255',
            'judul_sk' => 'required|string|max:255',
            'tanggal_berlaku' => 'required|date',
            'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_berlaku',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'link_drive' => 'nullable|url',
            'keterangan' => 'nullable|string',
        ]);

        $sk->update($validated);

        return redirect()->route('admin.sekretariat.sk.index')->with('success', 'Surat Keputusan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $sk = SuratKeputusan::findOrFail($id);
        $sk->delete();

        return redirect()->route('admin.sekretariat.sk.index')->with('success', 'Surat Keputusan berhasil dihapus.');
    }
}
