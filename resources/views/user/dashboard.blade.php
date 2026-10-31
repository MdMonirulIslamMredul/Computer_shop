@extends('frontend.layouts.app')

@section('title', 'গ্রাহক ড্যাশবোর্ড | ' . ($settings->site_name ?? 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার'))

@section('content')
<div class="py-5 bg-light">
    <div class="container">
        {{-- Profile Header Card --}}
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="rounded-circle p-3 bg-success text-white fs-3 d-flex align-items-center justify-content-center" style="width: 58px; height: 58px;">
                        <i class="bi bi-person-fill"></i>
                    </span>
                    <div>
                        <h4 class="fw-bold mb-1 text-dark">{{ $user->name }}</h4>
                        <span class="text-muted small"><i class="bi bi-envelope me-1"></i>{{ $user->email }}</span>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('orders.create') }}" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-plus-circle me-1"></i> নতুন সেবা অর্ডার করুন
                    </a>
                    <a href="{{ route('orders.track') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-search me-1"></i> ট্র্যাকিং
                    </a>
                </div>
            </div>
        </div>

        {{-- My Orders List --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-receipt-cutoff text-success me-2"></i> আমার কাজের অর্ডার ও অগ্রগতি তালিকা
                </h5>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ট্র্যাকিং আইডি</th>
                            <th>সেবার নাম</th>
                            <th>তারিখ</th>
                            <th>ফি</th>
                            <th>পেমেন্ট</th>
                            <th>কাজের অগ্রগতি</th>
                            <th>ডাউনলোড / স্লিপ</th>
                            <th class="text-end pe-4">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $ord)
                            <tr>
                                <td class="ps-4">
                                    <strong class="font-monospace text-primary">{{ $ord->tracking_id }}</strong>
                                </td>
                                <td><span class="fw-semibold text-dark">{{ $ord->service_title }}</span></td>
                                <td class="small text-muted">{{ $ord->created_at->format('d M, Y') }}</td>
                                <td><strong class="text-dark">৳{{ number_format($ord->total_fee, 2) }}</strong></td>
                                <td>
                                    <span class="badge {{ $ord->payment_status_badge }}">{{ $ord->payment_status_label }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $ord->status_badge }}">{{ $ord->status_label }}</span>
                                </td>
                                <td>
                                    @if($ord->completed_file_path)
                                        <a href="{{ route('orders.download', $ord->tracking_id) }}" class="btn btn-sm btn-success rounded-pill px-3">
                                            <i class="bi bi-download me-1"></i> কপি ডাউনলোড
                                        </a>
                                    @else
                                        <span class="text-muted small">প্রক্রিয়াধীন</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('orders.track', ['search' => $ord->tracking_id]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        ট্র্যাক করুন
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 text-muted"></i>
                                    আপনি এখনও কোনো অনলাইন সেবার অর্ডার করেননি।
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
                <div class="card-footer bg-white border-top py-3 d-flex justify-content-center">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
