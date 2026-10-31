@extends('frontend.layouts.app')

@section('title', 'আমাদের সকল সেবাসমূহ | ' . ($settings->site_name ?? 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার'))
@section('meta_description', 'চাকরির আবেদন, জন্মনিবন্ধন, পাসপোর্ট, এনআইডি ও ভর্তি আবেদনসহ সকল অনলাইন ও কম্পিউটার সেবা।')

@push('styles')
<style>
    .services-hero {
        background: linear-gradient(135deg, #064e3b 0%, #059669 55%, #047857 100%);
        color: #ffffff;
        position: relative;
    }
    .service-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 32px rgba(5, 150, 105, 0.12);
        border-color: #059669;
    }
    .service-icon-box {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        background: #ecfdf5;
        color: #059669;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
    }
    html[data-theme='dark'] .service-card {
        background: var(--surface-color);
        border-color: var(--border-color);
    }
    html[data-theme='dark'] .service-card:is(:hover, :focus-within) {
        border-color: #6ee7b7;
    }
    html[data-theme='dark'] .service-icon-box {
        background: #16332f;
    }
    html[data-theme='dark'] .service-icon-box i,
    html[data-theme='dark'] .service-card .bg-success-subtle {
        color: #6ee7b7 !important;
    }
</style>
@endpush

@section('content')
    {{-- HERO SECTION --}}
    <section class="services-hero py-5">
        <div class="container py-4 text-center">
            <span class="badge bg-white text-success rounded-pill px-3 py-1 mb-2 fw-semibold">
                <i class="bi bi-pc-display me-1"></i> সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার
            </span>
            <h1 class="display-5 fw-bold mb-3 text-white">আমাদের কম্পিউটার ও অনলাইন সেবাসমূহ</h1>
            <p class="lead text-white-50 mx-auto mb-4" style="max-width: 680px;">
                চাকরির আবেদন, জন্মনিবন্ধন, পাসপোর্ট, এনআইডি কার্ড সংশোধন ও ভর্তি আবেদনসহ সকল জরুরি সেবা ঘরে বসেই অর্ডার করুন।
            </p>
            <div class="d-flex flex-wrap gap-2 justify-content-center">
                <a href="{{ route('orders.create') }}" class="btn btn-warning btn-lg px-4 rounded-pill fw-bold text-dark shadow">
                    <i class="bi bi-send-fill me-1"></i> এখনই সেবা অর্ডার করুন
                </a>
                <a href="{{ route('orders.track') }}" class="btn btn-outline-light btn-lg px-4 rounded-pill fw-semibold">
                    <i class="bi bi-search me-1"></i> কাজের অগ্রগতি ট্র্যাক করুন
                </a>
            </div>
        </div>
    </section>

    {{-- SERVICES GRID SECTION --}}
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                @forelse($services as $service)
                    <div class="col-md-6 col-lg-4">
                        <div class="service-card shadow-sm p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="service-icon-box">
                                    <i class="bi {{ $service->icon ?? 'bi-file-earmark-text' }}"></i>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success-subtle text-success fs-6 fw-bold px-3 py-1 rounded-pill">
                                        ৳{{ number_format($service->service_fee + $service->govt_fee, 0) }}
                                    </span>
                                </div>
                            </div>

                            <h4 class="fw-bold fs-5 text-dark mb-2">
                                <a href="{{ route('services.show', $service->slug) }}" class="text-decoration-none text-dark">
                                    {{ $service->title }}
                                </a>
                            </h4>

                            <p class="text-muted small mb-3 flex-grow-1" style="min-height: 54px;">
                                {{ $service->short_description ?? Str::limit(strip_tags($service->description), 100) }}
                            </p>

                            <div class="mb-3 small text-muted">
                                <i class="bi bi-clock me-1 text-success"></i> সময়: <strong>{{ $service->delivery_time ?? '১-২ কর্মদিবস' }}</strong>
                            </div>

                            <div class="mt-auto pt-3 border-top d-flex gap-2">
                                <a href="{{ route('services.show', $service->slug) }}" class="btn btn-outline-secondary btn-sm w-50 rounded-pill py-2">
                                    বিস্তারিত
                                </a>
                                @if($service->is_online_orderable)
                                    <a href="{{ route('orders.create', ['serviceSlug' => $service->slug]) }}" class="btn btn-success btn-sm w-50 rounded-pill py-2 fw-semibold">
                                        <i class="bi bi-send me-1"></i> অর্ডার করুন
                                    </a>
                                @else
                                    <a href="{{ route('contact.index') }}" class="btn btn-outline-primary btn-sm w-50 rounded-pill py-2">
                                        যোগাযোগ
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <h4 class="text-muted">কোনো সেবা পাওয়া যায়নি।</h4>
                    </div>
                @endforelse
            </div>

            @if(method_exists($services, 'hasPages') && $services->hasPages())
                <div class="mt-5 d-flex justify-content-center">
                    {{ $services->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
