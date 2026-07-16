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

    public function bidang()
    {
        $programs = Program::where('kategori', 'Bidang')->with('jabatan')->orderBy('periode_mulai', 'desc')->get();
        $settings = [
            'banner_title' => \App\Models\PageSetting::getVal('bidang_banner_title', 'Program Bidang SIKTN: Menggerakkan Organisasi Melalui Aksi Nyata'),
            'banner_desc' => \App\Models\PageSetting::getVal('bidang_banner_desc', 'Program Bidang SIKTN dirancang untuk mendukung pengembangan organisasi...'),
            'about_image' => \App\Models\PageSetting::getVal('bidang_about_image'),
            'about_title' => \App\Models\PageSetting::getVal('bidang_about_title', 'Menggerakkan Organisasi Melalui Program Kerja yang Terarah dan Berdampak'),
            'about_desc1' => \App\Models\PageSetting::getVal('bidang_about_desc1', ''),
            'about_desc2' => \App\Models\PageSetting::getVal('bidang_about_desc2', ''),
            'tujuan' => json_decode(\App\Models\PageSetting::getVal('bidang_tujuan_json', '[]'), true) ?? [],
            'fokus' => json_decode(\App\Models\PageSetting::getVal('bidang_fokus_json', '[]'), true) ?? [],
        ];
        return view('pages.program.bidang', compact('programs', 'settings'));
    }
}
