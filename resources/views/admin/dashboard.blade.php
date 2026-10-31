@extends('admin.layouts.app')

@section('title', 'ড্যাশবোর্ড | সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1 text-dark">অ্যাডমিন ড্যাশবোর্ড</h3>
            <p class="text-muted mb-0">সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার - অনলাইন সার্ভিস সেন্টার</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
                <i class="bi bi-receipt me-1"></i> সকল অর্ডার দেখুন
            </a>
            <a href="{{ route('orders.create') }}" target="_blank" class="btn btn-outline-success">
                <i class="bi bi-plus-circle me-1"></i> নতুন অর্ডার ফরম
            </a>
        </div>
    </div>

    {{-- Order Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-semibold">আজকের নতুন অর্ডার</small>
                        <h2 class="fw-bold text-dark mb-0 mt-1">{{ $stats['orders_today'] ?? 0 }}</h2>
                    </div>
                    <span class="rounded-circle p-3 bg-info-subtle text-info fs-3">
                        <i class="bi bi-calendar2-day-fill"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-semibold">অপেক্ষমাণ অর্ডার (Pending)</small>
                        <h2 class="fw-bold text-warning mb-0 mt-1">{{ $stats['orders_pending'] ?? 0 }}</h2>
                    </div>
                    <span class="rounded-circle p-3 bg-warning-subtle text-warning fs-3">
                        <i class="bi bi-hourglass-split"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-semibold">প্রক্রিয়াধীন (Processing)</small>
                        <h2 class="fw-bold text-primary mb-0 mt-1">{{ $stats['orders_processing'] ?? 0 }}</h2>
                    </div>
                    <span class="rounded-circle p-3 bg-primary-subtle text-primary fs-3">
                        <i class="bi bi-gear-wide-connected"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-semibold">কাজ সম্পন্ন (Completed)</small>
                        <h2 class="fw-bold text-success mb-0 mt-1">{{ $stats['orders_completed'] ?? 0 }}</h2>
                    </div>
                    <span class="rounded-circle p-3 bg-success-subtle text-success fs-3">
                        <i class="bi bi-check-circle-fill"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Secondary Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <a href="{{ route('admin.services.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <small class="text-muted">মোট সেবাসমূহ</small>
                    <h4 class="fw-bold text-dark mb-0">{{ $stats['services'] }}</h4>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('admin.useful-links.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <small class="text-muted">প্রয়োজনীয় লিংক</small>
                    <h4 class="fw-bold text-dark mb-0">{{ $stats['useful_links'] ?? 0 }}</h4>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('admin.contacts.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                    <small class="text-muted">গ্রাহক মেসেজ</small>
                    <h4 class="fw-bold text-dark mb-0">{{ $stats['contacts'] }}</h4>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <small class="text-muted">সর্বমোট অর্ডার</small>
                <h4 class="fw-bold text-dark mb-0">{{ $stats['orders_total'] ?? 0 }}</h4>
            </div>
        </div>
    </div>

    {{-- Recent Orders Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-dark">
                <i class="bi bi-receipt-cutoff text-primary me-2"></i> সাম্প্রতিক কাজের অর্ডার সমূহ
            </h5>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                সকল অর্ডার দেখুন
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">ট্র্যাকিং আইডি</th>
                        <th>গ্রাহকের নাম ও ফোন</th>
                        <th>সেবা</th>
                        <th>ফি</th>
                        <th>পেমেন্ট</th>
                        <th>স্ট্যাটাস</th>
                        <th>তারিখ</th>
                        <th class="text-end pe-4">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders ?? [] as $ord)
                        <tr>
                            <td class="ps-4">
                                <strong class="font-monospace text-primary">{{ $ord->tracking_id }}</strong>
                            </td>
                            <td>
                                <strong class="text-dark d-block">{{ $ord->customer_name }}</strong>
                                <span class="small text-muted">{{ $ord->customer_phone }}</span>
                            </td>
                            <td>{{ $ord->service_title }}</td>
                            <td><strong class="text-success">৳{{ number_format($ord->total_fee, 2) }}</strong></td>
                            <td>
                                <span class="badge {{ $ord->payment_status_badge }}">{{ $ord->payment_status_label }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $ord->status_badge }}">{{ $ord->status_label }}</span>
                            </td>
                            <td class="small text-muted">{{ $ord->created_at->diffForHumans() }}</td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.orders.show', $ord->id) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                    <i class="bi bi-eye"></i> দেখুন
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                কোনো নতুন অর্ডার নেই।
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
