<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\LogsAdminActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationSettingController extends Controller
{
    use LogsAdminActivity;
    public function edit()
    {
        $admin = Auth::guard('admin')->user();
        $settings = $admin->notification_settings ?? [
            'surat_pending' => true,
            'sk_expired' => true,
            'new_anggota' => true,
            'new_katalog' => true,
            'auto_open_login' => true,
        ];

        return view('admin.settings.notifications', compact('admin', 'settings'));
    }

    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $settings = [
            'surat_pending' => $request->has('surat_pending'),
            'sk_expired' => $request->has('sk_expired'),
            'new_anggota' => $request->has('new_anggota'),
            'new_katalog' => $request->has('new_katalog'),
            'auto_open_login' => $request->has('auto_open_login'),
        ];

        $admin->update([
            'notification_settings' => $settings,
        ]);

        $this->logActivity('admin', 'Edit Preferensi Notifikasi', $admin->id, $admin->name);

        return redirect()->back()->with('success', 'Preferensi Pengaturan Notifikasi berhasil diperbarui!');
    }
}
