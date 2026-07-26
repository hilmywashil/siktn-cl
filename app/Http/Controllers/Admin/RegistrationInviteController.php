<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegistrationInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RegistrationInviteController extends Controller
{
    private function checkAuthorization()
    {
        $admin = auth()->guard('admin')->user();
        if (!$admin || !in_array($admin->category, ['super_admin', 'pnkt'])) {
            abort(403, 'Hanya Superadmin dan Admin PNKT yang memiliki akses membuat link undangan.');
        }
    }

    public function generate(Request $request)
    {
        $this->checkAuthorization();

        $request->validate([
            'duration' => 'required|in:30m,1h,6h,12h,1d,3d,7d,30d',
            'max_uses' => 'required|integer|min:1|max:1000',
        ]);

        $durationMap = [
            '30m' => Carbon::now()->addMinutes(30),
            '1h'  => Carbon::now()->addHours(1),
            '6h'  => Carbon::now()->addHours(6),
            '12h' => Carbon::now()->addHours(12),
            '1d'  => Carbon::now()->addDays(1),
            '3d'  => Carbon::now()->addDays(3),
            '7d'  => Carbon::now()->addDays(7),
            '30d' => Carbon::now()->addDays(30),
        ];

        $expiresAt = $durationMap[$request->duration] ?? Carbon::now()->addDays(1);
        $token = Str::random(32);

        $invite = RegistrationInvite::create([
            'token' => $token,
            'created_by' => auth()->guard('admin')->id(),
            'expires_at' => $expiresAt,
            'max_uses' => $request->max_uses,
            'uses_count' => 0,
            'is_active' => true,
        ]);

        $url = route('member-register', ['token' => $invite->token]);

        return redirect()->back()->with([
            'success' => 'Link Undangan Pendaftaran Berhasil Di-generate!',
            'generated_invite_url' => $url,
            'invite_expires_at' => $expiresAt->translatedFormat('d F Y H:i'),
        ]);
    }

    public function destroy(RegistrationInvite $invite)
    {
        $this->checkAuthorization();
        $invite->delete();

        return redirect()->back()->with('success', 'Link undangan pendaftaran berhasil dihapus.');
    }
}
