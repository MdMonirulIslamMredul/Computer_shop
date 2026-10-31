@extends('admin.layouts.app')

@section('title', 'কম্পিউটার ও অনলাইন সেবাসমূহ')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1 text-dark">কম্পিউটার ও অনলাইন সেবাসমূহ</h3>
            <p class="text-muted mb-0">দোকানের সকল সেবার তালিকা, ফি ও শর্তাবলী পরিচালনা করুন</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.services.create') }}" class="btn btn-success">
                <i class="bi bi-plus-lg me-1"></i> নতুন সেবা যুক্ত করুন
            </a>
            <a href="{{ route('services.index') }}" target="_blank" class="btn btn-outline-primary">
                <i class="bi bi-globe me-1"></i> পাবলিক পেজ দেখুন
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">সেবার নাম ও আইকন</th>
                        <th>নির্ধারিত ফি</th>
                        <th>ডেলিভারি সময়</th>
                        <th class="text-center">অনলাইন অর্ডার</th>
                        <th class="text-center">ফিচার্ড</th>
                        <th class="text-center">স্ট্যাটাস</th>
                        <th class="text-end pe-4">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="rounded-3 p-2 bg-success-subtle text-success fs-5">
                                        <i class="bi {{ $service->icon ?? 'bi-file-earmark-text' }}"></i>
                                    </span>
                                    <div>
                                        <strong class="text-dark d-block fs-6">{{ $service->title }}</strong>
                                        <small class="text-muted">{{ Str::limit($service->short_description, 60) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong class="text-success fs-6">৳{{ number_format($service->service_fee + $service->govt_fee, 2) }}</strong>
                                @if($service->govt_fee > 0)
                                    <div class="small text-muted">(সেবা: {{ $service->service_fee }} + সরকারি: {{ $service->govt_fee }})</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-clock me-1 text-primary"></i> {{ $service->delivery_time ?? '১-২ কর্মদিবস' }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($service->is_online_orderable)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">হ্যাঁ</span>
                                @else
                                    <span class="badge bg-light text-muted border">না</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($service->is_featured)
                                    <span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i> হ্যাঁ</span>
                                @else
                                    <span class="badge bg-light text-muted">না</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($service->status)
                                    <span class="badge bg-success">সক্রিয়</span>
                                @else
                                    <span class="badge bg-secondary">নিষ্ক্রিয়</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-outline-primary" title="সম্পাদনা">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.services.destroy', $service) }}" class="d-inline"
                                        onsubmit="return confirm('আপনি কি নিশ্চিত এই সেবাটি মুছে ফেলতে চান?');">
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
                            <td colspan="7" class="text-center py-5">
                                <span class="text-muted">কোনো সেবা পাওয়া যায়নি।</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($services->hasPages())
            <div class="card-footer bg-white border-top py-3 d-flex justify-content-center">
                {{ $services->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
