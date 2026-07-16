<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PageSetting;
use Illuminate\Support\Facades\Auth;

class PageSettingController extends Controller
{
    private function checkAuthorization()
    {
        $user = Auth::guard('admin')->user();
        if (!$user || (!$user->isSuperAdmin() && !$user->isPNKT())) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }

    public function edit()
    {
        $this->checkAuthorization();
        $activeMenu = 'program_settings';
        return view('admin.program.settings', compact('activeMenu'));
    }

    public function update(Request $request)
    {
        $this->checkAuthorization();
        
        $data = $request->except(['_token', '_method']);
        
        // Handle nested file uploads in JSON arrays (csr_fokus_json and bidang_fokus_json)
        $jsonKeys = ['csr_fokus_json', 'bidang_fokus_json'];
        foreach ($jsonKeys as $jkey) {
            if (isset($data[$jkey]) && is_array($data[$jkey])) {
                foreach ($data[$jkey] as $index => &$item) {
                    // Keep old icon if no new file is uploaded
                    if (isset($item['old_icon'])) {
                        $item['icon'] = $item['old_icon'];
                        unset($item['old_icon']);
                    } else {
                        $item['icon'] = $item['icon'] ?? '';
                    }

                    // Check if new file uploaded in this array index
                    if ($request->hasFile("{$jkey}.{$index}.icon")) {
                        $file = $request->file("{$jkey}.{$index}.icon");
                        $path = $file->store('page_settings/icons', 'public');
                        
                        // Delete old file if it was in storage
                        if (isset($item['icon']) && str_starts_with($item['icon'], 'page_settings/') && \Illuminate\Support\Facades\Storage::disk('public')->exists($item['icon'])) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($item['icon']);
                        }
                        
                        $item['icon'] = $path;
                    }
                }
            }
        }
        
        foreach ($data as $key => $value) {
            // Skip processing file fields if they are already processed as array
            if (in_array($key, $jsonKeys)) {
                PageSetting::updateOrCreate(
                    ['key' => $key],
                    ['value' => json_encode($value)]
                );
                continue;
            }

            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $path = $file->store('page_settings', 'public');
                
                // Get old value to delete if exists
                $oldSetting = PageSetting::where('key', $key)->first();
                if ($oldSetting && $oldSetting->value && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldSetting->value)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldSetting->value);
                }
                
                PageSetting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $path]
                );
            } else {
                PageSetting::updateOrCreate(
                    ['key' => $key],
                    ['value' => is_array($value) ? json_encode($value) : $value]
                );
            }
        }

        return redirect()->back()->with('success', 'Pengaturan halaman berhasil diperbarui.');
    }
}
