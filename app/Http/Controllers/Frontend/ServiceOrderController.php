<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\OrderAttachment;
use App\Models\Service;
use App\Models\ServiceOrder;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceOrderController extends Controller
{
    /**
     * Show the service order submission form.
     */
    public function create(Request $request, ?string $serviceSlug = null)
    {
        $services = Service::where('status', true)
            ->where('is_online_orderable', true)
            ->orderBy('sort_order')
            ->get();

        $selectedService = null;
        if ($serviceSlug) {
            $selectedService = Service::where('slug', $serviceSlug)
                ->where('status', true)
                ->first();
        } elseif ($request->filled('service_id')) {
            $selectedService = Service::where('id', $request->service_id)
                ->where('status', true)
                ->first();
        }

        $settings = Setting::first();

        return view('frontend.orders.create', compact('services', 'selectedService', 'settings'));
    }

    /**
     * Store a newly placed service order.
     */
    public function store(Request $request)
    {
        $isManualService = $request->input('service_id') === 'other' || $request->input('service_id') === 'custom';

        $rules = [
            'service_id' => $isManualService ? 'required|in:other,custom' : 'required|exists:services,id',
            'custom_service_title' => $isManualService ? 'required|string|max:255' : 'nullable|string|max:255',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'customer_address' => 'nullable|string|max:500',
            'customer_notes' => 'nullable|string|max:2000',
            'payment_method' => 'required|in:bkash,nagad,rocket,shop_cash',
            'payment_number' => $isManualService ? 'nullable|string|max:30' : 'nullable|string|max:30|required_unless:payment_method,shop_cash',
            'transaction_id' => $isManualService ? 'nullable|string|max:60' : 'nullable|string|max:60|required_unless:payment_method,shop_cash',
            'applicant_photo' => 'nullable|image|max:5120|mimes:jpeg,png,jpg,webp',
            'attachments' => 'required|array|min:2',
            'attachments.*' => 'required|file|max:12288|mimes:jpeg,png,jpg,webp,pdf,doc,docx',
        ];

        $validated = $request->validate($rules, [
            'service_id.required' => 'অনুগ্রহ করে একটি সেবা নির্বাচন করুন।',
            'custom_service_title.required' => 'অনুগ্রহ করে আপনার কাঙ্ক্ষিত সেবার নামটি বাংলায় লিখুন।',
            'customer_name.required' => 'আপনার নাম প্রদান করুন।',
            'customer_phone.required' => 'যোগাযোগের জন্য সক্রিয় মোবাইল নম্বর দিন।',
            'payment_number.required_unless' => 'টাকা পাঠানোর বিকাশ/নগদ নম্বর প্রদান করুন।',
            'transaction_id.required_unless' => 'পেমেন্টের TrxID প্রদান করুন।',
            'applicant_photo.image' => 'আবেদনকারীর ছবি একটি বৈধ ইমেজ ফাইল (JPG, PNG, WebP) হতে হবে।',
            'applicant_photo.max' => 'আবেদনকারীর ছবির সাইজ সর্বোচ্চ ৫ মেগাবাইট হতে পারবে।',
            'attachments.required' => 'আবেদনের জন্য প্রয়োজনীয় ফাইল আপলোড করা আবশ্যক (নূন্যতম ২টি ফাইল)।',
            'attachments.min' => 'অনলাইনে আবেদনের জন্য কমপক্ষে ২টি ফাইল (যেমন: NID/জন্মনিবন্ধন, সনদ, স্বাক্ষর ইত্যাদি) আপলোড করতে হবে।',
            'attachments.*.max' => 'প্রতিটি ফাইলের সাইজ ১২ এমবি এর কম হতে হবে।',
            'attachments.*.mimes' => 'ফাইলগুলো অবশ্যই JPG, PNG, WebP, PDF, Doc বা Docx ফরম্যাটে হতে হবে।',
        ]);

        if ($isManualService) {
            $service = null;
            $serviceId = null;
            $serviceTitle = trim($validated['custom_service_title']);
            $serviceFee = 0.00;
            $govtFee = 0.00;
            $totalFee = 0.00;
            $paymentMethod = 'shop_cash';
        } else {
            $service = Service::findOrFail($validated['service_id']);
            $serviceId = $service->id;
            $serviceTitle = $service->title;
            $serviceFee = (float) $service->service_fee;
            $govtFee = (float) $service->govt_fee;
            $totalFee = $serviceFee + $govtFee;
            $paymentMethod = $validated['payment_method'];
        }

        // Collect custom application data
        $applicationData = [
            'applicant_name_bn' => $request->input('app_applicant_name_bn', $validated['customer_name']),
            'applicant_name_en' => $request->input('app_applicant_name_en'),
            'father_name_bn' => $request->input('app_father_name_bn'),
            'father_name_en' => $request->input('app_father_name_en'),
            'mother_name_bn' => $request->input('app_mother_name_bn'),
            'mother_name_en' => $request->input('app_mother_name_en'),
            'spouse_name_bn' => $request->input('app_spouse_name_bn'),
            'spouse_name_en' => $request->input('app_spouse_name_en'),
            'dob' => $request->input('app_dob'),
            'nid_birth_reg_no' => $request->input('app_nid_birth_reg_no'),
            'job_post_or_circular' => $request->input('app_job_post_or_circular'),
            'exam_roll_reg' => $request->input('app_exam_roll_reg'),
            'choice_list' => $request->input('app_choice_list'),
            'extra_details' => $request->input('app_extra_details'),
            'is_manual_service' => $isManualService ? 'হ্যাঁ (ম্যানুয়ালি লিখিত সেবা)' : null,
        ];

        // Filter out null or empty fields
        $applicationData = array_filter($applicationData, fn ($val) => ! is_null($val) && trim($val) !== '');

        // Generate unique readable tracking ID
        do {
            $trackingId = 'SLC-' . date('ymd') . '-' . strtoupper(Str::random(4));
        } while (ServiceOrder::where('tracking_id', $trackingId)->exists());

        $order = ServiceOrder::create([
            'tracking_id' => $trackingId,
            'user_id' => auth()->id(),
            'service_id' => $serviceId,
            'service_title' => $serviceTitle,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'] ?? null,
            'customer_address' => $validated['customer_address'] ?? null,
            'application_data' => $applicationData,
            'customer_notes' => $validated['customer_notes'] ?? null,
            'service_fee' => $serviceFee,
            'govt_fee' => $govtFee,
            'total_fee' => $totalFee,
            'payment_method' => $paymentMethod,
            'payment_number' => $validated['payment_number'] ?? null,
            'transaction_id' => $validated['transaction_id'] ?? null,
            'payment_status' => 'pending',
            'order_status' => 'pending',
            'admin_notes' => $isManualService
                ? 'গ্রাহক তালিকার বাইরে নতুন সেবা নিজে লিখেছেন। কোনো অগ্রিম ফি নেই, দোকানে এসে সরাসরি পরিশোধ করবেন।'
                : 'আপনার আবেদনটি গৃহীত হয়েছে। পেমেন্ট যাচাই শেষে কাজ শুরু করা হবে।',
        ]);

        // Upload applicant user photo if present
        if ($request->hasFile('applicant_photo')) {
            $photoFile = $request->file('applicant_photo');
            if ($photoFile && $photoFile->isValid()) {
                $photoPath = $photoFile->store("order_attachments/{$trackingId}", 'public');
                OrderAttachment::create([
                    'service_order_id' => $order->id,
                    'file_path' => $photoPath,
                    'file_name' => 'আবেদনকারীর ছবি (User Photo)',
                    'file_type' => 'photo',
                    'file_size' => $photoFile->getSize(),
                ]);
                $applicationData['applicant_photo'] = $photoPath;
                $order->application_data = $applicationData;
                $order->save();
            }
        }

        // Upload attachments (minimum 2 files)
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if ($file && $file->isValid()) {
                    $originalName = $file->getClientOriginalName();
                    $ext = strtolower($file->getClientOriginalExtension());
                    $type = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']) ? 'photo' : (in_array($ext, ['pdf']) ? 'pdf' : 'document');
                    $path = $file->store("order_attachments/{$trackingId}", 'public');

                    OrderAttachment::create([
                        'service_order_id' => $order->id,
                        'file_path' => $path,
                        'file_name' => $originalName,
                        'file_type' => $type,
                        'file_size' => $file->getSize(),
                    ]);
                }
            }
        }

        return redirect()->route('orders.confirmation', $order->tracking_id)
            ->with('success', 'আপনার আবেদনটি সফলভাবে সাবমিট হয়েছে!');
    }

    /**
     * Show order placement confirmation receipt.
     */
    public function confirmation(string $trackingId)
    {
        $order = ServiceOrder::with(['service', 'attachments'])
            ->where('tracking_id', $trackingId)
            ->firstOrFail();

        $settings = Setting::first();

        return view('frontend.orders.confirmation', compact('order', 'settings'));
    }

    /**
     * Public order tracking page.
     */
    public function track(Request $request)
    {
        $search = trim($request->input('search', ''));
        $order = null;
        $ordersList = null;

        if ($search !== '') {
            // Check if exact match by tracking ID
            $order = ServiceOrder::with(['service', 'attachments'])
                ->where('tracking_id', $search)
                ->first();

            // If not found, check if search by phone number
            if (! $order) {
                $ordersList = ServiceOrder::with(['service'])
                    ->where('customer_phone', $search)
                    ->latest()
                    ->get();

                if ($ordersList->count() === 1) {
                    $order = $ordersList->first()->load(['service', 'attachments']);
                    $ordersList = null;
                }
            }
        }

        $settings = Setting::first();

        return view('frontend.orders.track', compact('order', 'ordersList', 'search', 'settings'));
    }

    /**
     * Download completed file uploaded by admin.
     */
    public function downloadCompletedFile(string $trackingId)
    {
        $order = ServiceOrder::where('tracking_id', $trackingId)->firstOrFail();

        if (! $order->completed_file_path || ! Storage::disk('public')->exists($order->completed_file_path)) {
            return back()->with('error', 'সম্পূর্ণ ফাইলটি এখনও আপলোড করা হয়নি বা ফাইলটি পাওয়া যায়নি।');
        }

        $path = Storage::disk('public')->path($order->completed_file_path);
        return response()->download($path);
    }
}

