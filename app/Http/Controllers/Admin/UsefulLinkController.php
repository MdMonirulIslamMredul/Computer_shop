<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\UsefulLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UsefulLinkController extends Controller
{
    /**
     * Display a listing of useful links.
     */
    public function index(Request $request)
    {
        $category = $request->input('category', 'all');
        $search = trim($request->input('search', ''));

        $query = UsefulLink::with('service')->orderBy('sort_order')->orderBy('id');

        if ($category !== 'all') {
            $query->where('category', $category);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('external_url', 'like', "%{$search}%");
            });
        }

        $links = $query->paginate(15)->withQueryString();

        $categories = UsefulLink::select('category')->distinct()->pluck('category')->toArray();

        return view('admin.useful-links.index', compact('links', 'categories', 'category', 'search'));
    }

    /**
     * Show form for creating a new useful link.
     */
    public function create()
    {
        $services = Service::where('status', true)->orderBy('title')->get();
        return view('admin.useful-links.create', compact('services'));
    }

    /**
     * Store a newly created useful link.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:useful_links,slug',
            'category' => 'required|string|max:100',
            'short_description' => 'nullable|string|max:1000',
            'long_description' => 'nullable|string',
            'required_documents' => 'nullable|string',
            'external_url' => 'required|url|max:500',
            'service_id' => 'nullable|exists:services,id',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
            'is_featured' => 'nullable|boolean',
            'status' => 'nullable|boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(4);
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['status'] = $request->has('status');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        UsefulLink::create($validated);

        return redirect()->route('admin.useful-links.index')->with('success', 'প্রয়োজনীয় লিংক সফলভাবে যুক্ত করা হয়েছে।');
    }

    /**
     * Show form for editing an existing useful link.
     */
    public function edit($id)
    {
        $link = UsefulLink::findOrFail($id);
        $services = Service::where('status', true)->orderBy('title')->get();

        return view('admin.useful-links.edit', compact('link', 'services'));
    }

    /**
     * Update an existing useful link.
     */
    public function update(Request $request, $id)
    {
        $link = UsefulLink::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:useful_links,slug,' . $link->id,
            'category' => 'required|string|max:100',
            'short_description' => 'nullable|string|max:1000',
            'long_description' => 'nullable|string',
            'required_documents' => 'nullable|string',
            'external_url' => 'required|url|max:500',
            'service_id' => 'nullable|exists:services,id',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
            'is_featured' => 'nullable|boolean',
            'status' => 'nullable|boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['status'] = $request->has('status');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $link->update($validated);

        return redirect()->route('admin.useful-links.index')->with('success', 'প্রয়োজনীয় লিংক সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * Delete a useful link.
     */
    public function destroy($id)
    {
        $link = UsefulLink::findOrFail($id);
        $link->delete();

        return redirect()->route('admin.useful-links.index')->with('success', 'প্রয়োজনীয় লিংক সফলভাবে মুছে ফেলা হয়েছে।');
    }
}

