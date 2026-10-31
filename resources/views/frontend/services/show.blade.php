@extends('frontend.layouts.app')

@section('title', $service->title . ' - ' . ($settings->site_name ?? 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার'))
@section('meta_description', Str::limit(strip_tags($service->short_description ?? $service->description), 155))

@push('styles')
<style>
    .service-detail-hero {
        background: linear-gradient(135deg, #064e3b 0%, #059669 55%, #047857 100%);
        color: #ffffff;
        position: relative;
    }
    .service-detail-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        overflow: hidden;
    }
    .service-main-img-box {
        position: relative;
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 16px;
        overflow: hidden;
        min-height: 280px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
    }
    .service-main-img-box img {
        width: 100%;
        max-height: 420px;
        object-fit: cover;
    }
    .sidebar-widget {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }
    html[data-theme='dark'] .service-detail-card,
    html[data-theme='dark'] .sidebar-widget {
        background-color: var(--surface-color);
        border-color: var(--border-color);
    }
    html[data-theme='dark'] .service-main-img-box {
        background: var(--surface-alt-color);
        border-color: var(--border-color);
    }
    html[data-theme='dark'] .service-rich-text a,
    html[data-theme='dark'] .service-rich-text a * {
        color: #7dd3fc !important;
    }
    html[data-theme='dark'] .service-rich-text a {
        text-decoration: underline;
    }
    html[data-theme='dark'] .service-rich-text :is(table, th, td, blockquote) {
        background-color: var(--surface-alt-color) !important;
        border-color: var(--border-color) !important;
    }
</style>
@endpush

@section('content')
    {{-- HERO SECTION --}}
    <section class="service-detail-hero py-5">
        <div class="container py-3">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">হোম</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('services.index') }}" class="text-white-50 text-decoration-none">সেবাসমূহ</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">{{ $service->title }}</li>
                </ol>
            </nav>

            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-white text-success rounded-pill px-3 py-1 mb-2 fw-semibold">
                        <i class="bi {{ $service->icon ?? 'bi-pc-display' }} me-1"></i> অনলাইন সেবা
                    </span>
                    <h1 class="display-6 fw-bold mb-3 text-white">{{ $service->title }}</h1>
                    <p class="lead text-white-50 mb-0">
                        {{ $service->short_description ?? 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার-এর মাধ্যমে সহজে ও নির্ভুলভাবে সেবা গ্রহণ করুন।' }}
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <div class="p-3 bg-white bg-opacity-10 rounded-4 border border-white border-opacity-25 d-inline-block text-start">
                        <div class="text-white-50 small">মোট নির্ধারিত ফি:</div>
                        <div class="fs-2 fw-bold text-white">৳{{ number_format($service->service_fee + $service->govt_fee, 2) }}</div>
                        <div class="text-white-50 small mt-1">
                            <i class="bi bi-clock me-1"></i> সময়: {{ $service->delivery_time ?? '১-২ কর্মদিবস' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN CONTENT AREA --}}
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                {{-- Left Column: Service Details --}}
                <div class="col-lg-8">
                    <div class="service-detail-card p-4 p-md-5 mb-4">
                        {{-- Image if exists --}}
                        @if($service->image)
                            <div class="service-main-img-box mb-4">
                                <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}">
                            </div>
                        @endif

                        {{-- Service Full Description --}}
                        <div class="service-rich-text text-secondary mb-4" style="line-height: 1.8;">
                            {!! $service->description ?? '<p>' . e($service->short_description) . '</p>' !!}
                        </div>

                        {{-- Required Documents Section --}}
                        @if($service->required_documents)
                            <div class="card border-0 bg-light rounded-4 p-4 mb-4 border">
                                <h5 class="fw-bold text-dark mb-3">
                                    <i class="bi bi-card-checklist text-success me-2"></i> আবেদনের জন্য যা যা প্রয়োজন (কাগজপত্র):
                                </h5>
                                <div class="text-secondary" style="white-space: pre-line; line-height: 1.7;">
                                    {{ $service->required_documents }}
                                </div>
                            </div>
                        @endif

                        {{-- Why Choose Us / Service Perks --}}
                        <h5 class="fw-bold text-dark mb-3">আমাদের মাধ্যমে আবেদনের সুবিধাসমূহ</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-white">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <i class="bi bi-shield-check text-success fs-5"></i>
                                        <strong class="text-dark">শতভাগ নির্ভুল ডাটা এন্ট্রি</strong>
                                    </div>
                                    <p class="small text-muted mb-0">অভিজ্ঞ অপারেটর দ্বারা সতর্কতার সাথে তথ্য পূরণ করা হয়।</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-white">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <i class="bi bi-phone text-success fs-5"></i>
                                        <strong class="text-dark">ঘরে বসেই অনলাইন ট্র্যাকিং</strong>
                                    </div>
                                    <p class="small text-muted mb-0">ট্র্যাকিং আইডি দিয়ে যেকোনো সময় কাজের অগ্রগতি দেখতে পারবেন।</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-white">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <i class="bi bi-cloud-arrow-down text-success fs-5"></i>
                                        <strong class="text-dark">অনলাইন কপি ডাউনলোড</strong>
                                    </div>
                                    <p class="small text-muted mb-0">আবেদন কপি বা স্লিপ সাইট থেকে সরাসরি ডাউনলোড করতে পারবেন।</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border rounded-3 bg-white">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <i class="bi bi-wallet2 text-success fs-5"></i>
                                        <strong class="text-dark">বিকাশ/নগদে নিরাপদ পেমেন্ট</strong>
                                    </div>
                                    <p class="small text-muted mb-0">দোকানের পার্সোনাল বিকাশ বা নগদে ফি পরিশোধের সুবিধা।</p>
                                </div>
                            </div>
                        </div>

                        {{-- CTA Banner inside details --}}
                        <div class="card border-0 rounded-4 p-4 text-white text-center" style="background: linear-gradient(135deg, #059669 0%, #047857 100%);">
                            <h4 class="fw-bold mb-2">এই সেবার জন্য এখনই আবেদন করুন</h4>
                            <p class="mb-3 text-white-50">প্রয়োজনীয় তথ্য ও ছবি দিয়ে মাত্র ২ মিনিটে আপনার অর্ডার সাবমিট করুন।</p>
                            <div>
                                <a href="{{ route('orders.create', ['serviceSlug' => $service->slug]) }}" class="btn btn-warning btn-lg px-4 rounded-pill fw-bold text-dark shadow">
                                    <i class="bi bi-send-fill me-1"></i> অনলাইনে আবেদন অর্ডার করুন
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Sidebar --}}
                <div class="col-lg-4">
                    {{-- 1. Order Service Card --}}
                    <div class="sidebar-widget p-4 text-center text-white" style="background: linear-gradient(135deg, #059669 0%, #047857 100%);">
                        <div class="d-inline-flex p-3 rounded-circle mb-3 fs-3" style="background: rgba(255, 255, 255, 0.2); color: #ffffff;">
                            <i class="bi bi-send-check-fill"></i>
                        </div>
                        <h4 class="fw-bold text-white fs-5 mb-1">অনলাইনে অর্ডার করুন</h4>
                        <div class="fs-3 fw-bolder mb-2 text-warning">
                            ৳{{ number_format($service->service_fee + $service->govt_fee, 2) }}
                        </div>
                        <p class="small text-white-50 mb-3">
                            সেবা ফি: ৳{{ number_format($service->service_fee, 2) }} | সরকারি ফি: ৳{{ number_format($service->govt_fee, 2) }}
                        </p>
                        <a href="{{ route('orders.create', ['serviceSlug' => $service->slug]) }}"
                           class="btn btn-warning rounded-pill w-100 py-3 fw-bold shadow text-dark fs-6">
                            <i class="bi bi-check-circle-fill me-1"></i> এখনই অর্ডার দিন
                        </a>
                        <div class="mt-3 small text-white-50">
                            <i class="bi bi-clock me-1"></i> ডেলিভারি সময়: <strong>{{ $service->delivery_time ?? '১-২ কর্মদিবস' }}</strong>
                        </div>
                    </div>

                    {{-- 2. Hotline Support Widget --}}
                    <div class="sidebar-widget p-4">
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-headset text-success me-2"></i>জরুরি হেল্পলাইন</h5>
                        <p class="small text-muted mb-3">আবেদন সংক্রান্ত যেকোনো তথ্যের জন্য সরাসরি দোকানে যোগাযোগ করুন।</p>
                        <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 mb-2">
                            <i class="bi bi-telephone-fill text-success fs-4"></i>
                            <div>
                                <small class="text-muted d-block">মোবাইল নম্বর:</small>
                                <strong class="text-dark">{{ $settings->contact_phone ?? '01711-665098' }}</strong>
                            </div>
                        </div>
                        <a href="https://wa.me/8801711665098" target="_blank" class="btn btn-outline-success w-100 rounded-pill py-2">
                            <i class="bi bi-whatsapp me-1"></i> হোয়াটসঅ্যাপে কথা বলুন
                        </a>
                    </div>

                    {{-- 3. Shop Location Widget --}}
                    <div class="sidebar-widget p-4">
                        <h5 class="fw-bold text-dark mb-2"><i class="bi bi-geo-alt-fill text-danger me-2"></i>দোকানের ঠিকানা</h5>
                        <p class="small text-muted mb-0">
                            <strong>সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার</strong><br>
                            {{ $settings->contact_address ?? 'জনতা বাজার, মেইন রোড, আলেকজান্ডার, রামগতি, লক্ষ্মীপুর।' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
