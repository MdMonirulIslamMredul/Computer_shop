<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Upazila;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpazilaController extends Controller
{
    /**
     * Display a listing of upazila pages.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        $query = Upazila::query()->orderBy('sort_order')->orderByDesc('id');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $upazilas = $query->paginate(10)->withQueryString();

        return view('admin.upazilas.index', compact('upazilas', 'search'));
    }

    /**
     * Show the form for creating a new upazila page.
     */
    public function create()
    {
        return view('admin.upazilas.create');
    }

    /**
     * Store a newly created upazila page in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateUpazila($request);

        $slug = !empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title']);

        // Ensure unique slug
        $originalSlug = $slug;
        $counter = 1;
        while (Upazila::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }
        $validated['slug'] = $slug;

        // Handle Image Uploads
        $imageFields = ['banner_image', 'image_1', 'image_2', 'image_3'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $validated[$field] = $request->file($field)->store('upazilas', 'public');
            } else {
                $validated[$field] = null;
            }
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Upazila::create($validated);

        return redirect()->route('admin.upazilas.index')->with('success', 'উপজেলা পেইজ সফলভাবে সংরক্ষণ করা হয়েছে (Upazila page created successfully).');
    }

    /**
     * Show the form for editing the specified upazila page.
     */
    public function edit(Upazila $upazila)
    {
        return view('admin.upazilas.edit', compact('upazila'));
    }

    /**
     * Update the specified upazila page in storage.
     */
    public function update(Request $request, Upazila $upazila)
    {
        $validated = $this->validateUpazila($request, $upazila->id);

        $slug = !empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title']);

        // Ensure unique slug except current
        $originalSlug = $slug;
        $counter = 1;
        while (Upazila::where('slug', $slug)->where('id', '!=', $upazila->id)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }
        $validated['slug'] = $slug;

        // Handle Image Uploads
        $imageFields = ['banner_image', 'image_1', 'image_2', 'image_3'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old image if exists
                if ($upazila->{$field} && Storage::disk('public')->exists($upazila->{$field})) {
                    Storage::disk('public')->delete($upazila->{$field});
                }
                $validated[$field] = $request->file($field)->store('upazilas', 'public');
            }
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $upazila->update($validated);

        return redirect()->route('admin.upazilas.index')->with('success', 'উপজেলা পেইজ সফলভাবে আপডেট করা হয়েছে (Upazila page updated successfully).');
    }

    /**
     * Remove the specified upazila page from storage.
     */
    public function destroy(Upazila $upazila)
    {
        // Delete all associated files
        $imageFields = ['banner_image', 'image_1', 'image_2', 'image_3'];
        foreach ($imageFields as $field) {
            if ($upazila->{$field} && Storage::disk('public')->exists($upazila->{$field})) {
                Storage::disk('public')->delete($upazila->{$field});
            }
        }

        $upazila->delete();

        return redirect()->route('admin.upazilas.index')->with('success', 'উপজেলা পেইজ সফলভাবে মুছে ফেলা হয়েছে (Upazila page deleted successfully).');
    }

    /**
     * Validate the upazila form request data.
     */
    protected function validateUpazila(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('upazilas', 'slug')->ignore($id),
            ],
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_1' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_2' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_3' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'detail_1' => 'nullable|string',
            'detail_2' => 'nullable|string',
            'detail_3' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);
    }
}

