<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceOrder;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display customer database with search by mobile phone number or name.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search') ?? $request->input('phone') ?? '');

        $phoneQuery = ServiceOrder::query();

        if ($search !== '') {
            $cleanPhone = preg_replace('/[^0-9]/', '', $search);
            $phoneQuery->where(function ($q) use ($search, $cleanPhone) {
                $q->where('customer_phone', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('tracking_id', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");

                if ($cleanPhone !== '') {
                    $q->orWhere('customer_phone', 'like', "%{$cleanPhone}%");
                }
            });
        }

        // Paginate distinct customer phone numbers by latest order
        $paginatedPhones = $phoneQuery->select('customer_phone')
            ->selectRaw('MAX(created_at) as latest_created_at')
            ->groupBy('customer_phone')
            ->orderByDesc('latest_created_at')
            ->paginate(15)
            ->withQueryString();

        $phones = $paginatedPhones->pluck('customer_phone');

        // Fetch all orders with relations for the current page's customers
        $ordersByPhone = ServiceOrder::with(['attachments', 'service'])
            ->whereIn('customer_phone', $phones)
            ->latest()
            ->get()
            ->groupBy('customer_phone');

        // Build structured customer records
        $customers = $paginatedPhones->map(function ($row) use ($ordersByPhone) {
            $phone = $row->customer_phone;
            $customerOrders = $ordersByPhone->get($phone, collect());
            $latestOrder = $customerOrders->first();

            // Find photo from any of the customer's orders
            $photoUrl = null;
            foreach ($customerOrders as $ord) {
                if ($ord->applicant_photo_url) {
                    $photoUrl = $ord->applicant_photo_url;
                    break;
                }
            }

            $appData = $latestOrder?->application_data ?? [];

            // Aggregate all uploaded attachments across orders
            $allAttachments = $customerOrders->flatMap->attachments;

            return (object) [
                'phone' => $phone,
                'name_bn' => $appData['applicant_name_bn'] ?? $latestOrder?->customer_name ?? 'অজ্ঞাত নাম',
                'name_en' => $appData['applicant_name_en'] ?? null,
                'customer_name' => $latestOrder?->customer_name ?? 'অজ্ঞাত নাম',
                'email' => $latestOrder?->customer_email,
                'address' => $latestOrder?->customer_address,
                'photo_url' => $photoUrl,
                'father_name_bn' => $appData['father_name_bn'] ?? ($appData['father_name'] ?? null),
                'father_name_en' => $appData['father_name_en'] ?? null,
                'mother_name_bn' => $appData['mother_name_bn'] ?? ($appData['mother_name'] ?? null),
                'mother_name_en' => $appData['mother_name_en'] ?? null,
                'spouse_name_bn' => $appData['spouse_name_bn'] ?? null,
                'spouse_name_en' => $appData['spouse_name_en'] ?? null,
                'dob' => $appData['dob'] ?? null,
                'nid_birth_reg_no' => $appData['nid_birth_reg_no'] ?? null,
                'orders_count' => $customerOrders->count(),
                'total_spent' => $customerOrders->sum('total_fee'),
                'latest_order' => $latestOrder,
                'orders' => $customerOrders,
                'attachments' => $allAttachments,
                'latest_date' => $latestOrder?->created_at,
            ];
        });

        $stats = [
            'total_customers' => ServiceOrder::distinct('customer_phone')->count('customer_phone'),
            'total_orders' => ServiceOrder::count(),
            'total_revenue' => ServiceOrder::sum('total_fee'),
        ];

        return view('admin.customers.index', compact('customers', 'paginatedPhones', 'search', 'stats'));
    }

    /**
     * Show full profile and all submitted information for a customer.
     */
    public function show(string $phone)
    {
        $orders = ServiceOrder::with(['attachments', 'service'])
            ->where('customer_phone', $phone)
            ->latest()
            ->get();

        abort_if($orders->isEmpty(), 404, 'এই মোবাইল নম্বরের কোনো গ্রাহক খুঁজে পাওয়া যায়নি।');

        $latestOrder = $orders->first();

        // Find photo
        $photoUrl = null;
        foreach ($orders as $ord) {
            if ($ord->applicant_photo_url) {
                $photoUrl = $ord->applicant_photo_url;
                break;
            }
        }

        $appData = $latestOrder->application_data ?? [];
        $allAttachments = $orders->flatMap->attachments;

        $customer = (object) [
            'phone' => $phone,
            'name_bn' => $appData['applicant_name_bn'] ?? $latestOrder->customer_name,
            'name_en' => $appData['applicant_name_en'] ?? null,
            'customer_name' => $latestOrder->customer_name,
            'email' => $latestOrder->customer_email,
            'address' => $latestOrder->customer_address,
            'photo_url' => $photoUrl,
            'father_name_bn' => $appData['father_name_bn'] ?? ($appData['father_name'] ?? null),
            'father_name_en' => $appData['father_name_en'] ?? null,
            'mother_name_bn' => $appData['mother_name_bn'] ?? ($appData['mother_name'] ?? null),
            'mother_name_en' => $appData['mother_name_en'] ?? null,
            'spouse_name_bn' => $appData['spouse_name_bn'] ?? null,
            'spouse_name_en' => $appData['spouse_name_en'] ?? null,
            'dob' => $appData['dob'] ?? null,
            'nid_birth_reg_no' => $appData['nid_birth_reg_no'] ?? null,
            'orders_count' => $orders->count(),
            'total_spent' => $orders->sum('total_fee'),
            'latest_order' => $latestOrder,
            'orders' => $orders,
            'attachments' => $allAttachments,
            'latest_date' => $latestOrder->created_at,
        ];

        return view('admin.customers.show', compact('customer', 'orders', 'latestOrder'));
    }
}

