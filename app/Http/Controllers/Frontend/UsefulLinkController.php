<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\UsefulLink;
use Illuminate\Http\Request;

class UsefulLinkController extends Controller
{
    /**
     * Display the useful links catalog with search and category filtering.
     */
    public function index(Request $request)
    {
        $categories = [
            'সকল',
            'চাকরির আবেদন',
            'এনআইডি ও জন্মনিবন্ধন',
            'ভর্তি ও শিক্ষা',
            'পাসপোর্ট ও ভিসা',
            'সরকারি ও অন্যান্য',
        ];

        $currentCategory = $request->input('category', 'সকল');
        $search = trim($request->input('search', ''));

        $query = UsefulLink::with('service')
            ->where('status', true);

        if ($currentCategory !== 'সকল' && in_array($currentCategory, $categories)) {
            $query->where('category', $currentCategory);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('long_description', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $links = $query->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(12)
            ->withQueryString();

        $featuredLinks = UsefulLink::where('status', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        $settings = Setting::first();

        return view('frontend.useful-links.index', compact(
            'links',
            'categories',
            'currentCategory',
            'search',
            'featuredLinks',
            'settings'
        ));
    }

    /**
     * Detailed view of a useful link.
     */
    public function show(string $slug)
    {
        $link = UsefulLink::with('service')
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        $relatedLinks = UsefulLink::where('status', true)
            ->where('category', $link->category)
            ->where('id', '!=', $link->id)
            ->take(4)
            ->get();

        $settings = Setting::first();

        return view('frontend.useful-links.show', compact('link', 'relatedLinks', 'settings'));
    }
}

