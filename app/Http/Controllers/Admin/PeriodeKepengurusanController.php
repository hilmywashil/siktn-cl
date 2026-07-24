<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PeriodeKepengurusan;
use App\Traits\LogsAdminActivity;
use Illuminate\Support\Facades\DB;

class PeriodeKepengurusanController extends Controller
{
    use LogsAdminActivity;

    private function checkAuthorization()
    {
        $admin = auth()->guard('admin')->user();
        if (!$admin || (!$admin->isSuperAdmin() && !$admin->isPimpinan() && !$admin->isPNKT())) {
            abort(403, 'Akses ditolak: Fitur Manajemen Periode Kepengurusan hanya untuk Super Admin, Pimpinan, dan PNKT.');
        }
    }

    public function index()
    {
        $this->checkAuthorization();

        $periodes = PeriodeKepengurusan::withCount('organisasi')
            ->orderBy('is_aktif', 'desc')
            ->orderBy('tahun_mulai', 'desc')
            ->get();

        $totalPeriode = $periodes->count();
        $totalAktif = $periodes->where('is_aktif', true)->count();
        $totalArsip = $periodes->where('is_aktif', false)->count();

        return view('admin.settings.periode.index', [
            'activeMenu' => 'pengaturan_periode',
            'periodes' => $periodes,
            'totalPeriode' => $totalPeriode,
            'totalAktif' => $totalAktif,
            'totalArsip' => $totalArsip
        ]);
    }

    public function store(Request $request)
    {
        $this->checkAuthorization();

        $request->validate([
            'nama_periode' => 'required|string|max:255',
            'tahun_mulai' => 'required|integer|digits:4|min:1900|max:2100',
            'tahun_selesai' => 'required|integer|digits:4|gte:tahun_mulai|max:2100',
            'nomor_sk' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
            'is_aktif' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $isAktif = $request->boolean('is_aktif');

            if ($isAktif) {
                PeriodeKepengurusan::query()->update(['is_aktif' => false]);
            }

            // Jika belum ada periode sama sekali, jadikan otomatis aktif
            if (PeriodeKepengurusan::count() === 0) {
                $isAktif = true;
            }

            $periode = PeriodeKepengurusan::create([
                'nama_periode' => $request->nama_periode,
                'tahun_mulai' => $request->tahun_mulai,
                'tahun_selesai' => $request->tahun_selesai,
                'nomor_sk' => $request->nomor_sk,
                'keterangan' => $request->keterangan,
                'is_aktif' => $isAktif,
            ]);

            $this->logActivity('periode', 'Tambah', $periode->id, $periode->nama_periode);
            DB::commit();

            return redirect()->route('admin.settings.periode.index')
                ->with('success', 'Periode kepengurusan "' . $periode->nama_periode . '" berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan periode kepengurusan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, PeriodeKepengurusan $periode)
    {
        $this->checkAuthorization();

        $request->validate([
            'nama_periode' => 'required|string|max:255',
            'tahun_mulai' => 'required|integer|digits:4|min:1900|max:2100',
            'tahun_selesai' => 'required|integer|digits:4|gte:tahun_mulai|max:2100',
            'nomor_sk' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
            'is_aktif' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            $isAktif = $request->boolean('is_aktif');

            if ($isAktif && !$periode->is_aktif) {
                PeriodeKepengurusan::query()->where('id', '!=', $periode->id)->update(['is_aktif' => false]);
            }

            $periode->update([
                'nama_periode' => $request->nama_periode,
                'tahun_mulai' => $request->tahun_mulai,
                'tahun_selesai' => $request->tahun_selesai,
                'nomor_sk' => $request->nomor_sk,
                'keterangan' => $request->keterangan,
                'is_aktif' => $isAktif,
            ]);

            $this->logActivity('periode', 'Edit', $periode->id, $periode->nama_periode);
            DB::commit();

            return redirect()->route('admin.settings.periode.index')
                ->with('success', 'Periode kepengurusan "' . $periode->nama_periode . '" berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui periode kepengurusan: ' . $e->getMessage());
        }
    }

    public function setActive(PeriodeKepengurusan $periode)
    {
        $this->checkAuthorization();

        DB::beginTransaction();
        try {
            PeriodeKepengurusan::query()->update(['is_aktif' => false]);
            $periode->update(['is_aktif' => true]);

            $this->logActivity('periode', 'Ubah Status', $periode->id, $periode->nama_periode, 'Set Periode Aktif');
            DB::commit();

            return redirect()->route('admin.settings.periode.index')
                ->with('success', 'Status "' . $periode->nama_periode . '" kini ditetapkan sebagai Periode Aktif.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menetapkan periode aktif: ' . $e->getMessage());
        }
    }

    public function destroy(PeriodeKepengurusan $periode)
    {
        $this->checkAuthorization();

        if ($periode->is_aktif) {
            return redirect()->back()->with('error', 'Periode yang sedang AKTIF tidak dapat dihapus. Silakan set periode lain sebagai aktif terlebih dahulu.');
        }

        try {
            $nama = $periode->nama_periode;
            $id = $periode->id;
            $periode->delete();

            $this->logActivity('periode', 'Hapus', $id, $nama);

            return redirect()->route('admin.settings.periode.index')
                ->with('success', 'Periode kepengurusan "' . $nama . '" berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus periode kepengurusan: ' . $e->getMessage());
        }
    }
}
