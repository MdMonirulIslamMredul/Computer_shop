@extends('frontend.layouts.app')

@section('title', 'অর্ডার ও কাজের অগ্রগতি ট্র্যাকিং | ' . ($settings->site_name ?? 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার'))

@push('styles')
<style>
    /* Stepper Styling */
    .tracking-stepper {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin: 40px 0 20px 0;
    }
    .tracking-stepper::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 20px;
        right: 20px;
        height: 4px;
        background: #e2e8f0;
        z-index: 1;
    }
    .step-item {
        position: relative;
        z-index: 2;
        text-align: center;
        flex: 1;
    }
    .step-circle {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #f1f5f9;
        border: 3px solid #cbd5e1;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin-bottom: 8px;
        transition: all 0.3s ease;
    }
    .step-item.active .step-circle {
        background: #059669;
        border-color: #059669;
        color: #fff;
        box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.2);
    }
    .step-item.completed .step-circle {
        background: #10b981;
        border-color: #10b981;
        color: #fff;
    }
    .step-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
        display: block;
    }
    .step-item.active .step-label {
        color: #059669;
        font-weight: 700;
    }
    @media (max-width: 767.98px) {
        .tracking-stepper {
            flex-direction: column;
            gap: 20px;
            margin: 20px 0;
        }
        .tracking-stepper::before {
            left: 20px;
            top: 10px;
            bottom: 10px;
            width: 4px;
            height: auto;
            right: auto;
        }
        .step-item {
            display: flex;
            align-items: center;
            text-align: left;
            gap: 15px;
        }
    }
</style>
@endpush

@section('content')
<div class="py-5 bg-light">
    <div class="container">
        {{-- Search Header Section --}}
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8 text-center">
                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-semibold mb-2">
                    <i class="bi bi-clock-history me-1"></i> লাইভ অগ্রগতি ট্র্যাকিং
                </span>
                <h2 class="fw-bold text-dark">কাজের অগ্রগতি ও অর্ডার ট্র্যাকিং</h2>
                <p class="text-muted">আপনার অর্ডারের ট্র্যাকিং নম্বর (যেমনঃ SLC-261029-XXXX) অথবা অর্ডারে ব্যবহৃত মোবাইল নম্বর লিখুন।</p>

                {{-- Search Box --}}
                <div class="card border-0 shadow-sm rounded-pill p-2 mb-4 bg-white">
                    <form action="{{ route('orders.track') }}" method="GET" class="d-flex align-items-center">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-0 ps-3">
                                <i class="bi bi-search text-success fs-5"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-0 shadow-none ps-2"
                                placeholder="ট্র্যাকিং আইডি (SLC-...) অথবা মোবাইল নম্বর লিখুন..." value="{{ $search }}" required autofocus>
                            <button type="submit" class="btn btn-success px-4 rounded-pill fw-semibold">
                                ট্র্যাক করুন
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Multiple Orders Found by Phone --}}
        @if(isset($ordersList) && $ordersList->count() > 0)
            <div class="row justify-content-center mb-4">
                <div class="col-lg-9">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="fw-bold mb-0 text-dark">
                                <i class="bi bi-list-check text-success me-2"></i> এই নম্বরে একাধিক অর্ডার পাওয়া গেছে ({{ $ordersList->count() }}টি)
                            </h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ট্র্যাকিং আইডি</th>
                                        <th>সেবার নাম</th>
                                        <th>তারিখ</th>
                                        <th>ফি</th>
                                        <th>স্ট্যাটাস</th>
                                        <th>অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ordersList as $ord)
                                        <tr>
                                            <td><strong class="font-monospace text-primary">{{ $ord->tracking_id }}</strong></td>
                                            <td>{{ $ord->service_title }}</td>
                                            <td>{{ $ord->created_at->format('d M, Y') }}</td>
                                            <td>৳{{ number_format($ord->total_fee, 2) }}</td>
                                            <td>
                                                <span class="badge {{ $ord->status_badge }}">{{ $ord->status_label }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('orders.track', ['search' => $ord->tracking_id]) }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                                    বিস্তারিত দেখুন
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Single Order Tracking Details --}}
        @if(isset($order))
            @php
                // Map status to progress step: 1: pending, 2: processing, 3: completed, 4: delivered
                $currentStep = 1;
                if ($order->order_status === 'processing' || $order->payment_status === 'verified') {
                    $currentStep = 2;
                }
                if ($order->order_status === 'completed') {
                    $currentStep = 3;
                }
                if ($order->order_status === 'delivered') {
                    $currentStep = 4;
                }
                if ($order->order_status === 'cancelled') {
                    $currentStep = -1;
                }
            @endphp

            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        {{-- Order Card Header --}}
                        <div class="card-header bg-white p-4 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <span class="text-muted small">ট্র্যাকিং নম্বর:</span>
                                <h4 class="fw-bolder text-success font-monospace mb-0">{{ $order->tracking_id }}</h4>
                            </div>
                            <div class="text-end">
                                <span class="badge {{ $order->status_badge }} px-3 py-2 fs-6 mb-1">{{ $order->status_label }}</span>
                                <div class="small text-muted">সাবমিট: {{ $order->created_at->format('d M, Y h:i A') }}</div>
                            </div>
                        </div>

                        <div class="card-body p-4 p-md-5">
                            {{-- Stepper Progress --}}
                            @if($order->order_status !== 'cancelled')
                                <div class="tracking-stepper">
                                    {{-- Step 1 --}}
                                    <div class="step-item {{ $currentStep >= 1 ? ($currentStep > 1 ? 'completed' : 'active') : '' }}">
                                        <div class="step-circle">
                                            @if($currentStep > 1) <i class="bi bi-check-lg"></i> @else 1 @endif
                                        </div>
                                        <div class="step-label">
                                            অর্ডার গৃহীত
                                            <small class="d-block text-muted fw-normal">তথ্য জমা হয়েছে</small>
                                        </div>
                                    </div>

                                    {{-- Step 2 --}}
                                    <div class="step-item {{ $currentStep >= 2 ? ($currentStep > 2 ? 'completed' : 'active') : '' }}">
                                        <div class="step-circle">
                                            @if($currentStep > 2) <i class="bi bi-check-lg"></i> @else 2 @endif
                                        </div>
                                        <div class="step-label">
                                            আবেদন প্রক্রিয়াধীন
                                            <small class="d-block text-muted fw-normal">অপারেটর কাজ করছেন</small>
                                        </div>
                                    </div>

                                    {{-- Step 3 --}}
                                    <div class="step-item {{ $currentStep >= 3 ? ($currentStep > 3 ? 'completed' : 'active') : '' }}">
                                        <div class="step-circle">
                                            @if($currentStep > 3) <i class="bi bi-check-lg"></i> @else 3 @endif
                                        </div>
                                        <div class="step-label">
                                            কাজ সম্পন্ন
                                            <small class="d-block text-muted fw-normal">ফাইল প্রস্তুত</small>
                                        </div>
                                    </div>

                                    {{-- Step 4 --}}
                                    <div class="step-item {{ $currentStep >= 4 ? 'completed active' : '' }}">
                                        <div class="step-circle">
                                            @if($currentStep >= 4) <i class="bi bi-check-lg"></i> @else 4 @endif
                                        </div>
                                        <div class="step-label">
                                            ডেলিভারি সম্পন্ন
                                            <small class="d-block text-muted fw-normal">গ্রাহক গ্রহণ করেছেন</small>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-danger rounded-4 p-4 text-center">
                                    <i class="bi bi-x-circle fs-1 d-block mb-2"></i>
                                    <h5 class="fw-bold">অর্ডারটি বাতিল করা হয়েছে</h5>
                                    <p class="mb-0">{{ $order->admin_notes ?? 'কোনো কারণে আপনার অর্ডারটি বাতিল করা হয়েছে। বিস্তারিত জানতে দোকানে যোগাযোগ করুন।' }}</p>
                                </div>
                            @endif

                            {{-- Admin Feedback / Notice --}}
                            @if($order->admin_notes)
                                <div class="alert alert-success border-0 rounded-4 p-3 d-flex align-items-center gap-3 my-4" style="background-color: #ecfdf5;">
                                    <i class="bi bi-chat-left-dots-fill fs-3 text-success"></i>
                                    <div>
                                        <strong class="text-success d-block mb-1">অপারেটর নোট / অগ্রগতি বার্তা:</strong>
                                        <span class="text-dark">{{ $order->admin_notes }}</span>
                                    </div>
                                </div>
                            @endif

                            {{-- Completed File Download Banner (Core Requirement) --}}
                            @if($order->completed_file_path)
                                <div class="card border-2 border-success bg-white shadow-sm rounded-4 p-4 text-center my-4">
                                    <div class="mb-2">
                                        <span class="badge rounded-circle p-3 bg-success text-white">
                                            <i class="bi bi-file-earmark-check fs-2"></i>
                                        </span>
                                    </div>
                                    <h4 class="fw-bold text-success mb-1">আপনার আবেদন কপি প্রস্তুত হয়েছে!</h4>
                                    <p class="text-muted mb-3">দোকানের অপারেটর আপনার কাজ সম্পন্ন করে ফাইলটি আপলোড করেছেন। নিচের বাটনে ক্লিক করে ফাইলটি ডাউনলোড বা সংরক্ষণ করুন।</p>
                                    <div>
                                        <a href="{{ route('orders.download', $order->tracking_id) }}" class="btn btn-success btn-lg px-4 py-2 rounded-pill shadow">
                                            <i class="bi bi-cloud-arrow-down-fill me-2"></i> সম্পূর্ণ আবেদন কপি ডাউনলোড করুন (Download Slip)
                                        </a>
                                    </div>
                                </div>
                            @endif

                            {{-- Order Information Breakdown --}}
                            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3 mt-4">
                                <i class="bi bi-info-circle text-primary me-2"></i> অর্ডারের বিস্তারিত তথ্য
                            </h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <span class="text-muted small d-block">সেবার নাম:</span>
                                        <strong class="text-dark fs-6">{{ $order->service_title }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <span class="text-muted small d-block">মোট ফি:</span>
                                        <strong class="text-success fs-5">৳{{ number_format($order->total_fee, 2) }}</strong>
                                        <span class="badge {{ $order->payment_status_badge }} ms-2">{{ $order->payment_status_label }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <span class="text-muted small d-block">গ্রাহকের নাম:</span>
                                        <strong class="text-dark">{{ $order->customer_name }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <span class="text-muted small d-block">মোবাইল নম্বর:</span>
                                        <strong class="text-dark">{{ $order->customer_phone }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <span class="text-muted small d-block">পেমেন্ট মেথড:</span>
                                        <strong class="text-dark">{{ $order->payment_method_name }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <span class="text-muted small d-block">Transaction ID:</span>
                                        <strong class="text-dark font-monospace">{{ $order->transaction_id ?? 'N/A' }}</strong>
                                    </div>
                                </div>
                            </div>

                            {{-- Submitted Application Data Details --}}
                            @if(!empty($order->application_data))
                                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                    <i class="bi bi-card-text text-secondary me-2"></i> আবেদন সংক্রান্ত প্রদানকৃত তথ্য
                                </h6>
                                <div class="row g-2 mb-4">
                                    @foreach($order->application_data as $key => $val)
                                        <div class="col-sm-6">
                                            <div class="p-2 border rounded bg-white">
                                                <small class="text-muted d-block">{{ \App\Models\ServiceOrder::getFieldLabel($key) }}:</small>
                                                @if($key === 'applicant_photo')
                                                    <a href="{{ asset('storage/' . $val) }}" target="_blank" class="d-inline-block mt-1">
                                                        <img src="{{ asset('storage/' . $val) }}" alt="Applicant Photo" class="img-thumbnail rounded" style="max-height: 70px;">
                                                    </a>
                                                @else
                                                    <span class="fw-semibold text-dark">{{ $val }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Attached Documents List --}}
                            @if($order->attachments->count() > 0)
                                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                    <i class="bi bi-paperclip text-secondary me-2"></i> আপনার সংযুক্ত ফাইলসমূহ ({{ $order->attachments->count() }}টি)
                                </h6>
                                <ul class="list-group list-group-flush mb-4">
                                    @foreach($order->attachments as $att)
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-file-earmark-text text-primary fs-5"></i>
                                                <span class="text-truncate" style="max-width: 280px;">{{ $att->file_name }}</span>
                                                <span class="badge bg-light text-muted">{{ $att->formatted_size }}</span>
                                            </div>
                                            <a href="{{ asset('storage/' . $att->file_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill">
                                                <i class="bi bi-eye"></i> দেখুন
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            {{-- Support & Print Actions --}}
                            <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center pt-3 border-top">
                                <a href="tel:01711665098" class="btn btn-outline-success rounded-pill px-3">
                                    <i class="bi bi-telephone me-1"></i> দোকানে কল করুন
                                </a>
                                <button type="button" class="btn btn-outline-secondary rounded-pill px-3" onclick="window.print()">
                                    <i class="bi bi-printer me-1"></i> প্রিন্ট কপি
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($search !== '')
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 text-center p-5">
                        <i class="bi bi-search text-warning fs-1 mb-3"></i>
                        <h4 class="fw-bold text-dark">কোনো অর্ডার পাওয়া যায়নি!</h4>
                        <p class="text-muted">"{{ $search }}" এর বিপরীতে কোনো তথ্য আমাদের ডেটাবেজে পাওয়া যায়নি। সঠিক ট্র্যাকিং নম্বর বা যে মোবাইল নম্বর দিয়ে অর্ডার করেছিলেন তা নিশ্চিত হয়ে পুনরায় চেষ্টা করুন।</p>
                        <div>
                            <a href="{{ route('orders.create') }}" class="btn btn-success rounded-pill px-4">
                                নতুন অর্ডার করতে এখানে ক্লিক করুন
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

