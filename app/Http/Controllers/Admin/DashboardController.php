<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\Gallery;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Service;
use App\Models\ServiceOrder;
use App\Models\TeamMember;
use App\Models\UsefulLink;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'stats' => [
                'orders_total' => ServiceOrder::count(),
                'orders_pending' => ServiceOrder::where('order_status', 'pending')->count(),
                'orders_processing' => ServiceOrder::where('order_status', 'processing')->count(),
                'orders_completed' => ServiceOrder::where('order_status', 'completed')->count(),
                'orders_today' => ServiceOrder::whereDate('created_at', today())->count(),
                'useful_links' => UsefulLink::count(),
                'services' => Service::count(),
                'products' => Product::count(),
                'blogs' => Blog::count(),
                'contacts' => Contact::count(),
            ],
            'recentOrders' => ServiceOrder::with('service')->latest()->take(6)->get(),
            'recentContacts' => Contact::latest()->take(5)->get(),
        ]);
    }
}
