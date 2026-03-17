<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with('category');

        if ($request->filled('category')) {
            $query->where('service_category_id', $request->category);
        }
        if ($request->filled('search')) {
            $query->where('name', 'ilike', "%{$request->search}%");
        }

        $services = $query->orderBy('sort_order')->paginate(20);
        $categories = ServiceCategory::where('is_active', true)->get();

        return view('admin.services.index', compact('services', 'categories'));
    }

    public function create()
    {
        $categories = ServiceCategory::where('is_active', true)->get();
        return view('admin.services.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_category_id' => 'required|exists:service_categories,id',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'vehicle_type' => 'required|in:motor,mobil,suv,truck,bus,all',
            'points_earned' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        Service::create($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Layanan berhasil ditambahkan!');
    }

    public function edit(Service $service)
    {
        $categories = ServiceCategory::where('is_active', true)->get();
        return view('admin.services.edit', compact('service', 'categories'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'service_category_id' => 'required|exists:service_categories,id',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'vehicle_type' => 'required|in:motor,mobil,suv,truck,bus,all',
            'points_earned' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $service->update($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Layanan berhasil diupdate!');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')
            ->with('success', 'Layanan berhasil dihapus!');
    }

    // ── Public Pricelist ──
    public function pricelist()
    {
        $categories = ServiceCategory::where('is_active', true)
            ->with(['activeServices' => fn($q) => $q->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        return view('pricelist', compact('categories'));
    }
}