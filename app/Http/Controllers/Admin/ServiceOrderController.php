<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceOrder;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceOrderController extends Controller
{
    /**
     * Display a listing of service orders with filtering and search.
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        $paymentStatus = $request->input('payment_status', 'all');
        $search = trim($request->input('search', ''));

        $query = ServiceOrder::with(['service', 'attachments'])->latest();

        if ($status !== 'all' && in_array($status, ['pending', 'processing', 'completed', 'delivered', 'cancelled'])) {
            $query->where('order_status', $status);
        }

        if ($paymentStatus !== 'all' && in_array($paymentStatus, ['pending', 'verified', 'rejected'])) {
            $query->where('payment_status', $paymentStatus);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('tracking_id', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('transaction_id', 'like', "%{$search}%")
                    ->orWhere('service_title', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => ServiceOrder::count(),
            'pending' => ServiceOrder::where('order_status', 'pending')->count(),
            'processing' => ServiceOrder::where('order_status', 'processing')->count(),
            'completed' => ServiceOrder::where('order_status', 'completed')->count(),
            'today' => ServiceOrder::whereDate('created_at', today())->count(),
            'unverified_payments' => ServiceOrder::where('payment_status', 'pending')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats', 'status', 'paymentStatus', 'search'));
    }

    /**
     * Display the specified service order details.
     */
    public function show($id)
    {
        $order = ServiceOrder::with(['service', 'attachments', 'user'])->findOrFail($id);
        $settings = Setting::first();

        return view('admin.orders.show', compact('order', 'settings'));
    }

    /**
     * Update order and payment status with notes.
     */
    public function updateStatus(Request $request, $id)
    {
        $order = ServiceOrder::findOrFail($id);

        $validated = $request->validate([
            'order_status' => 'required|in:pending,processing,completed,delivered,cancelled',
            'payment_status' => 'required|in:pending,verified,rejected',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $order->order_status = $validated['order_status'];
        $order->payment_status = $validated['payment_status'];
        $order->admin_notes = $validated['admin_notes'];

        if ($validated['order_status'] === 'completed' && ! $order->completed_at) {
            $order->completed_at = now();
        }

        $order->save();

        return back()->with('success', 'অর্ডারের অগ্রগতি ও পেমেন্ট স্ট্যাটাস সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * Quick toggle to verify bKash / Nagad payment.
     */
    public function verifyPayment($id)
    {
        $order = ServiceOrder::findOrFail($id);
        $order->payment_status = 'verified';
        if ($order->order_status === 'pending') {
            $order->order_status = 'processing';
            $order->admin_notes = 'পেমেন্ট সফলভাবে যাচাই হয়েছে। আপনার আবেদনের প্রক্রিয়া চলমান রয়েছে।';
        }
        $order->save();

        return back()->with('success', 'পেমেন্ট যাচাই সম্পন্ন হয়েছে এবং অর্ডারটি প্রক্রিয়াকরণে নেওয়া হয়েছে।');
    }

    /**
     * Upload the completed application file (PDF / Slip / Admit card) for the customer.
     */
    public function uploadCompletedFile(Request $request, $id)
    {
        $order = ServiceOrder::findOrFail($id);

        $request->validate([
            'completed_file' => 'required|file|max:20480|mimes:pdf,jpeg,png,jpg,webp,doc,docx',
        ], [
            'completed_file.required' => 'একটি ফাইল সিলেক্ট করুন।',
            'completed_file.max' => 'ফাইলের সাইজ সর্বোচ্চ ২০ মেগাবাইট হতে পারবে।',
        ]);

        if ($request->hasFile('completed_file')) {
            // Delete previous completed file if exists
            if ($order->completed_file_path && Storage::disk('public')->exists($order->completed_file_path)) {
                Storage::disk('public')->delete($order->completed_file_path);
            }

            $path = $request->file('completed_file')->store("completed_orders/{$order->tracking_id}", 'public');
            $order->completed_file_path = $path;
            $order->order_status = 'completed';
            $order->completed_at = now();
            $order->admin_notes = 'আপনার আবেদন সম্পন্ন হয়েছে! নিচে দেওয়া ডাউনলোড বাটন থেকে আপনার আবেদন কপি / প্রবেশপত্র সংগ্রহ করুন।';
            $order->save();
        }

        return back()->with('success', 'সম্পূর্ণ আবেদন ফাইল সফলভাবে আপলোড করা হয়েছে এবং অর্ডারটি সম্পন্ন চিহ্নিত হয়েছে।');
    }

    /**
     * Print slip for shop counter records.
     */
    public function printSlip($id)
    {
        $order = ServiceOrder::with(['service', 'attachments'])->findOrFail($id);
        $settings = Setting::first();

        return view('admin.orders.print', compact('order', 'settings'));
    }

    /**
     * Remove the specified order from database.
     */
    public function destroy($id)
    {
        $order = ServiceOrder::with('attachments')->findOrFail($id);

        // Delete files
        foreach ($order->attachments as $attachment) {
            if (Storage::disk('public')->exists($attachment->file_path)) {
                Storage::disk('public')->delete($attachment->file_path);
            }
        }
        if ($order->completed_file_path && Storage::disk('public')->exists($order->completed_file_path)) {
            Storage::disk('public')->delete($order->completed_file_path);
        }

        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'অর্ডারটি সফলভাবে মুছে ফেলা হয়েছে।');
    }
}

