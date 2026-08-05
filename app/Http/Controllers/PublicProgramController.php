<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;

class PublicProgramController extends Controller
{
    public function csr()
    {
        $programs = Program::where('kategori', 'CSR')->orderBy('periode_mulai', 'desc')->get();

        $settings = [
            'banner_title' => \App\Models\PageSetting::getVal('csr_banner_title', 'Program CSR SIKTN untuk Membangun Dampak yang Berkelanjutan'),
            'banner_desc' => \App\Models\PageSetting::getVal('csr_banner_desc', 'Melalui berbagai inisiatif Corporate Social Responsibility (CSR)...'),
            'about_image' => \App\Models\PageSetting::getVal('csr_about_image'),
            'about_title' => \App\Models\PageSetting::getVal('csr_about_title', 'Membangun Kepedulian, Menciptakan Dampak Nyata'),
            'about_desc1' => \App\Models\PageSetting::getVal('csr_about_desc1', ''),
            'about_desc2' => \App\Models\PageSetting::getVal('csr_about_desc2', ''),
            'tujuan' => json_decode(\App\Models\PageSetting::getVal('csr_tujuan_json', '[]'), true) ?? [],
            'fokus' => json_decode(\App\Models\PageSetting::getVal('csr_fokus_json', '[]'), true) ?? [],
        ];
        return view('pages.program.csr', compact('programs', 'settings'));
    }

    public function csrDetail($id)
    {
        $program = Program::where('kategori', 'CSR')->with('programKerja')->where('id', $id)->firstOrFail();

        $settings = [
            'banner_title' => \App\Models\PageSetting::getVal('csr_banner_title', 'Program CSR SIKTN untuk Membangun Dampak yang Berkelanjutan'),
            'banner_desc' => \App\Models\PageSetting::getVal('csr_banner_desc', 'Melalui berbagai inisiatif Corporate Social Responsibility (CSR)...'),
            'about_image' => \App\Models\PageSetting::getVal('csr_about_image'),
            'about_title' => \App\Models\PageSetting::getVal('csr_about_title', 'Membangun Kepektif, Menciptakan Dampak Nyata'),
            'about_desc1' => \App\Models\PageSetting::getVal('csr_about_desc1', ''),
            'about_desc2' => \App\Models\PageSetting::getVal('csr_about_desc2', ''),
            'tujuan' => json_decode(\App\Models\PageSetting::getVal('csr_tujuan_json', '[]'), true) ?? [],
            'fokus' => json_decode(\App\Models\PageSetting::getVal('csr_fokus_json', '[]'), true) ?? [],
        ];
        return view('pages.program.csr-detail', compact('program', 'settings'));
    }

    public function bidang()
    {
        $programs = Program::where('kategori', 'Bidang')->with(['jabatan', 'csrPrograms'])->orderBy('periode_mulai', 'desc')->get();

        $settings = [
            'banner_title' => \App\Models\PageSetting::getVal('bidang_banner_title', 'Program Kerja SIKTN: Menggerakkan Organisasi Melalui Aksi Nyata'),
            'banner_desc' => \App\Models\PageSetting::getVal('bidang_banner_desc', 'Program Kerja SIKTN dirancang untuk mendukung pengembangan organisasi...'),
            'about_image' => \App\Models\PageSetting::getVal('bidang_about_image'),
            'about_title' => \App\Models\PageSetting::getVal('bidang_about_title', 'Menggerakkan Organisasi Melalui Program Kerja yang Terarah dan Berdampak'),
            'about_desc1' => \App\Models\PageSetting::getVal('bidang_about_desc1', ''),
            'about_desc2' => \App\Models\PageSetting::getVal('bidang_about_desc2', ''),
            'tujuan' => json_decode(\App\Models\PageSetting::getVal('bidang_tujuan_json', '[]'), true) ?? [],
            'fokus' => json_decode(\App\Models\PageSetting::getVal('bidang_fokus_json', '[]'), true) ?? [],
        ];
        return view('pages.program.bidang', compact('programs', 'settings'));
    }

    public function bidangDetail($id)
    {
        $program = Program::where('kategori', 'Bidang')->with(['jabatan', 'csrPrograms'])->where('id', $id)->firstOrFail();

        $settings = [
            'banner_title' => \App\Models\PageSetting::getVal('bidang_banner_title', 'Program Kerja SIKTN: Menggerakkan Organisasi Melalui Aksi Nyata'),
            'banner_desc' => \App\Models\PageSetting::getVal('bidang_banner_desc', 'Program Kerja SIKTN dirancang untuk mendukung pengembangan organisasi...'),
            'about_image' => \App\Models\PageSetting::getVal('bidang_about_image'),
            'about_title' => \App\Models\PageSetting::getVal('bidang_about_title', 'Menggerakkan Organisasi Melalui Program Kerja yang Terarah dan Berdampak'),
            'about_desc1' => \App\Models\PageSetting::getVal('bidang_about_desc1', ''),
            'about_desc2' => \App\Models\PageSetting::getVal('bidang_about_desc2', ''),
            'tujuan' => json_decode(\App\Models\PageSetting::getVal('bidang_tujuan_json', '[]'), true) ?? [],
            'fokus' => json_decode(\App\Models\PageSetting::getVal('bidang_fokus_json', '[]'), true) ?? [],
        ];
        return view('pages.program.bidang-detail', compact('program', 'settings'));
    }

    public function join($id)
    {
        if (!\Illuminate\Support\Facades\Auth::guard('anggota')->check()) {
            return redirect()->route('anggota.login')->with('error', 'Silakan login terlebih dahulu untuk mengikuti program kerja ini.');
        }

        $anggota = \Illuminate\Support\Facades\Auth::guard('anggota')->user();
        $program = Program::findOrFail($id);

        $anggota->programs()->syncWithoutDetaching([
            $program->id => ['status' => 'pending']
        ]);

        // Send notification to all admins
        try {
            $admins = \App\Models\Admin::all();
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\ProgramJoinNotification($anggota, $program));
            }
        } catch (\Throwable $e) {
            // Ignore notification error gracefully
        }

        return back()->with('success', 'Pendaftaran Anda berhasil dikirim! Pendaftaran Anda sedang menunggu persetujuan / ACC dari Admin.');
    }
}
