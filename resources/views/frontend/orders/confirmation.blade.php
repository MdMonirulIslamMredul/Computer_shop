@extends('frontend.layouts.app')

@section('title', 'অর্ডার কনফার্মেশন | ' . $order->tracking_id)

@section('content')
<div class="py-5 bg-light min-vh-75 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-success text-white text-center py-4 border-0" style="background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;">
                        <div class="mb-2">
                            <span class="rounded-circle bg-white text-success d-inline-flex align-items-center justify-content-center shadow" style="width: 68px; height: 68px;">
                                <i class="bi bi-check-lg fs-1 fw-bold"></i>
                            </span>
                        </div>
                        <h3 class="fw-bold mb-1">আপনার আবেদনটি সফলভাবে গৃহীত হয়েছে!</h3>
                        <p class="mb-0 text-white-50">সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার-এ আপনার অর্ডারটি জমা হয়েছে।</p>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        {{-- Tracking ID Box --}}
                        <div class="p-4 bg-light rounded-4 text-center border mb-4">
                            <span class="text-muted small text-uppercase fw-semibold d-block mb-1">আপনার অর্ডার ট্র্যাকিং নম্বর (Tracking ID)</span>
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <span class="fs-2 fw-bolder text-success font-monospace" id="trackingIdText">{{ $order->tracking_id }}</span>
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="copyTrackingId()" title="কপি করুন">
                                    <i class="bi bi-clipboard" id="copyIcon"></i> <span id="copyText">কপি</span>
                                </button>
                            </div>
                            <small class="text-muted mt-2 d-block">
                                <i class="bi bi-shield-check text-success me-1"></i> এই ট্র্যাকিং নম্বর দিয়ে আপনি যেকোনো সময় আপনার কাজের অগ্রগতি ট্র্যাক করতে পারবেন।
                            </small>
                        </div>

                        {{-- Order Details Summary --}}
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <div class="p-3 border rounded-3 h-100 bg-white">
                                    <span class="text-muted small d-block">নির্বাচিত সেবা:</span>
                                    <strong class="text-dark fs-6">{{ $order->service_title }}</strong>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 border rounded-3 h-100 bg-white">
                                    <span class="text-muted small d-block">মোট ফি:</span>
                                    <strong class="text-success fs-5">৳{{ number_format($order->total_fee, 2) }}</strong>
                                    <span class="badge {{ $order->payment_status_badge }} ms-2">{{ $order->payment_status_label }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 border rounded-3 h-100 bg-white">
                                    <span class="text-muted small d-block">গ্রাহকের নাম ও ফোন:</span>
                                    <strong class="text-dark">{{ $order->customer_name }}</strong>
                                    <div class="text-muted small">{{ $order->customer_phone }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 border rounded-3 h-100 bg-white">
                                    <span class="text-muted small d-block">পেমেন্ট মেথড ও TrxID:</span>
                                    <strong class="text-dark">{{ $order->payment_method_name }}</strong>
                                    @if($order->transaction_id)
                                        <div class="font-monospace text-primary small">TrxID: {{ $order->transaction_id }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            <a href="{{ route('orders.track', ['search' => $order->tracking_id]) }}" class="btn btn-success btn-lg px-4 rounded-pill shadow-sm">
                                <i class="bi bi-search me-1"></i> অগ্রগতি ট্র্যাক করুন
                            </a>

                            @php
                                $waText = urlencode("হ্যালো, আমি সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার-এ একটি সেবা অর্ডার করেছি। আমার ট্র্যাকিং আইডি: {$order->tracking_id}। বিস্তারিত জানতে চাচ্ছি।");
                            @endphp
                            <a href="https://wa.me/8801711665098?text={{ $waText }}" target="_blank" class="btn btn-outline-success btn-lg px-4 rounded-pill">
                                <i class="bi bi-whatsapp me-1"></i> হোয়াটসঅ্যাপে মেসেজ দিন
                            </a>

                            <button type="button" class="btn btn-outline-secondary btn-lg px-4 rounded-pill" onclick="window.print()">
                                <i class="bi bi-printer me-1"></i> রসিদ প্রিন্ট করুন
                            </button>
                        </div>
                    </div>

                    <div class="card-footer bg-white text-center py-3 border-top small text-muted">
                        যেকোনো প্রয়োজনে কল করুন: <strong>{{ $settings->contact_phone ?? '01711-665098' }}</strong> | ঠিকানা: {{ $settings->contact_address ?? 'জনতা বাজার, আলেকজান্ডার, রামগতি, লক্ষ্মীপুর' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyTrackingId() {
        const text = document.getElementById('trackingIdText').textContent.trim();
        navigator.clipboard.writeText(text).then(() => {
            const copyIcon = document.getElementById('copyIcon');
            const copyText = document.getElementById('copyText');
            copyIcon.className = 'bi bi-check-lg text-success';
            copyText.textContent = 'কপি হয়েছে!';
            setTimeout(() => {
                copyIcon.className = 'bi bi-clipboard';
                copyText.textContent = 'কপি';
            }, 2500);
        });
    }
</script>
@endpush

