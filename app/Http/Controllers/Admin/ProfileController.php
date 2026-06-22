<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $admin = auth()->guard('admin')->user();
        
        return view('admin.profile.index', [
            'admin' => $admin,
            'activeMenu' => 'profile'
        ]);
    }

    public function update(Request $request)
    {
        $admin = auth()->guard('admin')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:admins,username,' . $admin->id],
            'email' => ['required', 'email', 'max:255', 'unique:admins,email,' . $admin->id],
        ]);

        $admin->update($validated);

        return redirect()->route('admin.profile')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $admin = auth()->guard('admin')->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password:admin'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $admin->update([
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()->route('admin.profile')
            ->with('success', 'Password berhasil diperbarui!');
    }

    public function updatePhoto(Request $request)
    {
        $admin = auth()->guard('admin')->user();

        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ], [
            'photo.required' => 'Silakan pilih foto terlebih dahulu.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format foto harus jpeg, png, atau jpg.',
            'photo.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        // Delete old photo if exists
        if ($admin->photo && Storage::disk('public')->exists($admin->photo)) {
            Storage::disk('public')->delete($admin->photo);
        }

        // Store new photo
        $path = $request->file('photo')->store('admin-photos', 'public');

        $admin->update(['photo' => $path]);

        return redirect()->route('admin.profile')
            ->with('success', 'Foto profil berhasil diperbarui!');
    }

    public function deletePhoto()
    {
        $admin = auth()->guard('admin')->user();

        if ($admin->photo && Storage::disk('public')->exists($admin->photo)) {
            Storage::disk('public')->delete($admin->photo);
        }

        $admin->update(['photo' => null]);

        return redirect()->route('admin.profile')
            ->with('success', 'Foto profil berhasil dihapus!');
    }
}