@extends('admin.layouts.app')

@section('title', 'কাজের অর্ডার ও ডাটা এন্ট্রি তালিকা')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1 text-dark">অনলাইন আবেদন ও কাজের অর্ডার সমূহ</h3>
            <p class="text-muted mb-0">গ্রাহকদের প্রেরিত অনলাইন আবেদনের অগ্রগতি ও পেমেন্ট নিয়ন্ত্রণ করুন</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('orders.create') }}" target="_blank" class="btn btn-outline-success">
                <i class="bi bi-plus-circle me-1"></i> নতুন অর্ডার ফরম
            </a>
            <a href="{{ route('orders.track') }}" target="_blank" class="btn btn-outline-primary">
                <i class="bi bi-search me-1"></i> পাবলিক ট্র্যাকিং
            </a>
        </div>
    </div>

    {{-- Stats Cards Row --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-2">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <span class="text-muted small fw-semibold d-block mb-1">মোট অর্ডার</span>
                <h3 class="fw-bold text-dark mb-0">{{ $stats['total'] }}</h3>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-warning">
                <span class="text-muted small fw-semibold d-block mb-1">অপেক্ষমাণ (Pending)</span>
                <h3 class="fw-bold text-warning mb-0">{{ $stats['pending'] }}</h3>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-primary">
                <span class="text-muted small fw-semibold d-block mb-1">প্রক্রিয়াধীন (Processing)</span>
                <h3 class="fw-bold text-primary mb-0">{{ $stats['processing'] }}</h3>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-success">
                <span class="text-muted small fw-semibold d-block mb-1">সম্পন্ন (Completed)</span>
                <h3 class="fw-bold text-success mb-0">{{ $stats['completed'] }}</h3>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-info">
                <span class="text-muted small fw-semibold d-block mb-1">আজকের নতুন</span>
                <h3 class="fw-bold text-info mb-0">{{ $stats['today'] }}</h3>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-danger">
                <span class="text-muted small fw-semibold d-block mb-1">যাচাইহীন পেমেন্ট</span>
                <h3 class="fw-bold text-danger mb-0">{{ $stats['unverified_payments'] }}</h3>
            </div>
        </div>
    </div>

    {{-- Filter Tabs & Search Bar --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="row g-2 align-items-center justify-content-between">
                <div class="col-lg-8">
                    <ul class="nav nav-pills gap-1 flex-wrap">
                        <li class="nav-item">
                            <a class="nav-link {{ $status === 'all' ? 'active bg-dark' : 'text-secondary' }} py-1 px-3 rounded-pill"
                                href="{{ route('admin.orders.index', ['status' => 'all', 'search' => $search]) }}">
                                সকল অর্ডার
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $status === 'pending' ? 'active bg-warning text-dark' : 'text-secondary' }} py-1 px-3 rounded-pill"
                                href="{{ route('admin.orders.index', ['status' => 'pending', 'search' => $search]) }}">
                                অপেক্ষমাণ ({{ $stats['pending'] }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $status === 'processing' ? 'active bg-primary' : 'text-secondary' }} py-1 px-3 rounded-pill"
                                href="{{ route('admin.orders.index', ['status' => 'processing', 'search' => $search]) }}">
                                প্রক্রিয়াধীন ({{ $stats['processing'] }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $status === 'completed' ? 'active bg-success' : 'text-secondary' }} py-1 px-3 rounded-pill"
                                href="{{ route('admin.orders.index', ['status' => 'completed', 'search' => $search]) }}">
                                সম্পন্ন ({{ $stats['completed'] }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $status === 'delivered' ? 'active bg-info text-dark' : 'text-secondary' }} py-1 px-3 rounded-pill"
                                href="{{ route('admin.orders.index', ['status' => 'delivered', 'search' => $search]) }}">
                                ডেলিভারি
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <form action="{{ route('admin.orders.index') }}" method="GET">
                        @if($status !== 'all')
                            <input type="hidden" name="status" value="{{ $status }}">
                        @endif
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-sm"
                                placeholder="ট্র্যাকিং আইডি, মোবাইল, TrxID..." value="{{ $search }}">
                            <button type="submit" class="btn btn-sm btn-dark">
                                <i class="bi bi-search"></i>
                            </button>
                            @if($search)
                                <a href="{{ route('admin.orders.index', ['status' => $status]) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-x"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">ট্র্যাকিং আইডি</th>
                        <th>গ্রাহকের তথ্য</th>
                        <th>সেবার নাম</th>
                        <th>ফি ও পেমেন্ট</th>
                        <th>অগ্রগতি স্ট্যাটাস</th>
                        <th>ডকুমেন্ট</th>
                        <th>তারিখ</th>
                        <th class="text-end pe-4">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $ord)
                        <tr>
                            <td class="ps-4">
                                <strong class="font-monospace text-primary fs-6">{{ $ord->tracking_id }}</strong>
                            </td>
                            <td>
                                <strong class="text-dark d-block">{{ $ord->customer_name }}</strong>
                                <span class="text-muted small"><i class="bi bi-telephone me-1"></i>{{ $ord->customer_phone }}</span>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $ord->service_title }}</span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">৳{{ number_format($ord->total_fee, 2) }}</div>
                                <div class="small">
                                    <span class="badge {{ $ord->payment_status_badge }}">{{ $ord->payment_status_label }}</span>
                                    <span class="text-muted ms-1 font-monospace">{{ $ord->payment_method }}</span>
                                </div>
                                @if($ord->transaction_id)
                                    <div class="small text-muted font-monospace">TrxID: {{ $ord->transaction_id }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $ord->status_badge }} px-2 py-1">{{ $ord->status_label }}</span>
                                @if($ord->completed_file_path)
                                    <div class="small text-success mt-1"><i class="bi bi-file-earmark-check-fill"></i> ফাইল আপলোড করা আছে</div>
                                @endif
                            </td>
                            <td>
                                @if($ord->attachments->count() > 0)
                                    <span class="badge bg-light text-secondary border">
                                        <i class="bi bi-paperclip me-1"></i> {{ $ord->attachments->count() }}টি ফাইল
                                    </span>
                                @else
                                    <span class="text-muted small">নেই</span>
                                @endif
                            </td>
                            <td class="small text-muted">
                                {{ $ord->created_at->format('d M, Y') }}<br>
                                <span class="text-muted" style="font-size: 0.75rem;">{{ $ord->created_at->format('h:i A') }}</span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.orders.show', $ord->id) }}" class="btn btn-primary" title="বিস্তারিত দেখুন">
                                        <i class="bi bi-eye"></i> দেখুন
                                    </a>
                                    <a href="{{ route('admin.orders.print', $ord->id) }}" target="_blank" class="btn btn-outline-secondary" title="রসিদ প্রিন্ট">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                    <form action="{{ route('admin.orders.destroy', $ord->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('আপনি কি নিশ্চিত এই অর্ডারটি মুছে ফেলতে চান?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="ডিলিট">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox text-muted fs-1 mb-2 d-block"></i>
                                <span class="text-muted">কোনো অর্ডার পাওয়া যায়নি।</span>
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
@endsection

