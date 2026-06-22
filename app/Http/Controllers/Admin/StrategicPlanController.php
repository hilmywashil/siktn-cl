<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StrategicPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StrategicPlanController extends Controller
{
    public function index()
    {
        $admin = auth()->guard('admin')->user();
        
        if (!$admin->canManageContent()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $plans = StrategicPlan::ordered()->get();
        return view('admin.strategic-plan.index', compact('admin', 'plans'));
    }

    public function create()
    {
        $admin = auth()->guard('admin')->user();
        
        if (!$admin->canManageContent()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return view('admin.strategic-plan.create', compact('admin'));
    }

    public function store(Request $request)
    {
        $admin = auth()->guard('admin')->user();
        
        if (!$admin->canManageContent()) {
            abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:tata_kelola,program_layanan',
            'order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        // Get max order if not provided
        if (!isset($validated['order'])) {
            $maxOrder = StrategicPlan::where('category', $validated['category'])->max('order') ?? 0;
            $validated['order'] = $maxOrder + 1;
        }

        $validated['is_active'] = $request->has('is_active');
        
        // Set details as empty array since we removed it from form
        $validated['details'] = [];

        StrategicPlan::create($validated);

        return redirect()->route('admin.strategic-plan.index')
            ->with('success', 'Strategic Plan berhasil ditambahkan!');
    }

    public function show(StrategicPlan $strategicPlan)
    {
        // Hanya tampilkan yang active untuk public
        if (!$strategicPlan->is_active) {
            abort(404);
        }
        
        return view('pages.details.strategic-plan-detail', [
            'plan' => $strategicPlan
        ]);
    }

    public function edit(StrategicPlan $strategicPlan)
    {
        $admin = auth()->guard('admin')->user();
        
        if (!$admin->canManageContent()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return view('admin.strategic-plan.edit', compact('admin', 'strategicPlan'));
    }

    public function update(Request $request, StrategicPlan $strategicPlan)
    {
        $admin = auth()->guard('admin')->user();
        
        if (!$admin->canManageContent()) {
            abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:tata_kelola,program_layanan',
            'order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        // Keep existing details or set as empty array
        $validated['details'] = $strategicPlan->details ?? [];

        $strategicPlan->update($validated);

        return redirect()->route('admin.strategic-plan.index')
            ->with('success', 'Strategic Plan berhasil diperbarui!');
    }

    public function destroy(StrategicPlan $strategicPlan)
    {
        $admin = auth()->guard('admin')->user();
        
        if (!$admin->canManageContent()) {
            abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }

        // Delete all icons if details exist
        if ($strategicPlan->details) {
            foreach ($strategicPlan->details as $detail) {
                if (isset($detail['icon']) && Storage::disk('public')->exists($detail['icon'])) {
                    Storage::disk('public')->delete($detail['icon']);
                }
            }
        }

        $strategicPlan->delete();

        return redirect()->route('admin.strategic-plan.index')
            ->with('success', 'Strategic Plan berhasil dihapus!');
    }
}