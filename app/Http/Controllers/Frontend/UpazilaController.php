<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Upazila;
use Illuminate\Http\Request;

class UpazilaController extends Controller
{
    /**
     * Display the specified upazila page.
     */
    public function show(Upazila $upazila)
    {
        // If not active, only admin can preview
        if (!$upazila->is_active) {
            $user = auth()->user();
            if (!$user || !$user->is_admin) {
                abort(404);
            }
        }

        $otherPages = Upazila::where('is_active', true)
            ->where('id', '!=', $upazila->id)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get(['id', 'title', 'slug', 'banner_image']);

        return view('frontend.upazila.show', compact('upazila', 'otherPages'));
    }
}

