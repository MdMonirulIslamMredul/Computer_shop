@extends('frontend.layouts.app')

@section('title', $upazila->title . ' | ' . ($settings->site_name ?? 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার'))
@section('meta_description', Str::limit(strip_tags($upazila->detail_1 ?? $upazila->title), 160))

@push('styles')
<style>
    .upazila-hero {
        position: relative;
        min-height: 280px;
        background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #0f766e 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        overflow: hidden;
    }
    .upazila-hero.has-banner {
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    .upazila-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(6, 78, 59, 0.75) 0%, rgba(15, 23, 42, 0.88) 100%);
        backdrop-filter: blur(2px);
    }
    .upazila-content {
        font-size: 1.05rem;
        line-height: 1.85;
        color: #334155;
    }
    .upazila-content p {
        margin-bottom: 1.25rem;
    }
    .upazila-content h2, .upazila-content h3, .upazila-content h4 {
        color: #0f172a;
        font-weight: 700;
        margin-top: 1.75rem;
        margin-bottom: 0.9rem;
    }
    .upazila-content ul, .upazila-content ol {
        margin-bottom: 1.25rem;
        padding-left: 1.5rem;
    }
    .upazila-content li {
        margin-bottom: 0.4rem;
    }
    .upazila-content table {
        width: 100%;
        margin-bottom: 1.5rem;
        border-collapse: collapse;
    }
    .upazila-content table th, .upazila-content table td {
        border: 1px solid #e2e8f0;
        padding: 0.65rem 0.9rem;
    }
    .upazila-content table th {
        background-color: #f1f5f9;
        font-weight: 600;
    }
    .upazila-image-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        overflow: hidden;
        border-radius: 1rem;
    }
    .upazila-image-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15) !important;
    }
    .upazila-image-card img {
        width: 100%;
        max-height: 440px;
        object-fit: cover;
        display: block;
        transition: transform 0.4s ease;
    }
    .upazila-image-card:hover img {
        transform: scale(1.02);
    }
</style>
@endpush

@section('content')
{{-- Hero Banner Section --}}
<section class="upazila-hero {{ $upazila->banner_image ? 'has-banner' : '' }} py-5 mb-5"
    @if($upazila->banner_image) style="background-image: url('{{ asset('storage/' . $upazila->banner_image) }}');" @endif>
    <div class="upazila-hero-overlay"></div>
    <div class="container position-relative py-3">
        <div class="row align-items-center">
            <div class="col-lg-9">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none"><i class="bi bi-house-door me-1"></i> হোম</a></li>
                        <li class="breadcrumb-item text-white-50">উপজেলা</li>
                        <li class="breadcrumb-item active text-white fw-semibold" aria-current="page">{{ $upazila->title }}</li>
                    </ol>
                </nav>
                <span class="badge bg-success bg-opacity-75 text-white px-3 py-1 rounded-pill mb-2 border border-light border-opacity-25">
                    <i class="bi bi-geo-alt-fill me-1"></i> উপজেলা সম্পর্কিত তথ্য
                </span>
                <h1 class="display-5 fw-bold text-white mb-2">{{ $upazila->title }}</h1>
                <p class="lead text-white-50 mb-0">
                    রামগতি উপজেলা ও সংশ্লিষ্ট এলাকার বিস্তারিত তথ্য, নাগরিক সুবিধা ও প্রাসঙ্গিক বিবরণ
                </p>
            </div>
            <div class="col-lg-3 text-lg-end mt-4 mt-lg-0">
                <button type="button" onclick="window.print()" class="btn btn-outline-light btn-sm rounded-pill px-3 py-2 shadow-sm">
                    <i class="bi bi-printer me-1"></i> পেইজ প্রিন্ট করুন
                </button>
            </div>
        </div>
    </div>
</section>

{{-- Main Body --}}
<div class="container pb-5">
    <div class="row g-4">
        {{-- Primary Alternating Content Column --}}
        <div class="col-lg-8">
            <div class="d-flex flex-column gap-4">

                {{-- SECTION 1: Detail 1 --}}
                @if(!empty($upazila->detail_1))
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                        <div class="d-flex align-items-center gap-2 mb-3 border-bottom pb-2">
                            <span class="badge bg-success text-white rounded-circle p-2">
                                <i class="bi bi-file-earmark-text"></i>
                            </span>
                            <h4 class="fw-bold mb-0 text-dark">১ম অংশ: বিস্তারিত তথ্য</h4>
                        </div>
                        <div class="upazila-content">
                            {!! $upazila->detail_1 !!}
                        </div>
                    </div>
                @endif

                {{-- MEDIA 1: Image 1 --}}
                @if(!empty($upazila->image_1))
                    <div class="card border-0 shadow-sm upazila-image-card">
                        <img src="{{ asset('storage/' . $upazila->image_1) }}" alt="{{ $upazila->title }} - Image 1"
                            class="img-fluid" loading="lazy">
                    </div>
                @endif

                {{-- SECTION 2: Detail 2 --}}
                @if(!empty($upazila->detail_2))
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                        <div class="d-flex align-items-center gap-2 mb-3 border-bottom pb-2">
                            <span class="badge bg-success text-white rounded-circle p-2">
                                <i class="bi bi-journal-check"></i>
                            </span>
                            <h4 class="fw-bold mb-0 text-dark">২য় অংশ: প্রাসঙ্গিক বিবরণ</h4>
                        </div>
                        <div class="upazila-content">
                            {!! $upazila->detail_2 !!}
                        </div>
                    </div>
                @endif

                {{-- MEDIA 2: Image 2 --}}
                @if(!empty($upazila->image_2))
                    <div class="card border-0 shadow-sm upazila-image-card">
                        <img src="{{ asset('storage/' . $upazila->image_2) }}" alt="{{ $upazila->title }} - Image 2"
                            class="img-fluid" loading="lazy">
                    </div>
                @endif

                {{-- SECTION 3: Detail 3 --}}
                @if(!empty($upazila->detail_3))
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                        <div class="d-flex align-items-center gap-2 mb-3 border-bottom pb-2">
                            <span class="badge bg-success text-white rounded-circle p-2">
                                <i class="bi bi-pin-map-fill"></i>
                            </span>
                            <h4 class="fw-bold mb-0 text-dark">৩য় অংশ: অতিরিক্ত বিবরণ ও নির্দেশিকা</h4>
                        </div>
                        <div class="upazila-content">
                            {!! $upazila->detail_3 !!}
                        </div>
                    </div>
                @endif

                {{-- MEDIA 3: Image 3 --}}
                @if(!empty($upazila->image_3))
                    <div class="card border-0 shadow-sm upazila-image-card">
                        <img src="{{ asset('storage/' . $upazila->image_3) }}" alt="{{ $upazila->title }} - Image 3"
                            class="img-fluid" loading="lazy">
                    </div>
                @endif

                {{-- If all details are empty fallback --}}
                @if(empty($upazila->detail_1) && empty($upazila->detail_2) && empty($upazila->detail_3))
                    <div class="card border-0 shadow-sm rounded-4 p-5 text-center text-muted bg-white">
                        <i class="bi bi-info-circle fs-1 text-secondary opacity-50 mb-2"></i>
                        <h5 class="fw-semibold">এই পেইজে এখনো কোনো বিস্তারিত বিবরণ যুক্ত করা হয়নি।</h5>
                        <p class="mb-0">শীঘ্রই প্রয়োজনীয় তথ্য আপডেট করা হবে।</p>
                    </div>
                @endif

            </div>
        </div>

        {{-- Sidebar Column --}}
        <div class="col-lg-4">
            <div class="position-sticky" style="top: 100px;">
                
                {{-- Other Upazila Pages Widget --}}
                @if(isset($otherPages) && $otherPages->count() > 0)
                    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                        <div class="card-header bg-success text-white py-3 px-4 border-0"
                            style="background: linear-gradient(135deg, #059669 0%, #047857 100%);">
                            <h5 class="fw-bold mb-0 d-flex align-items-center">
                                <i class="bi bi-collection me-2"></i> অন্যান্য উপজেলা তথ্য
                            </h5>
                        </div>
                        <div class="list-group list-group-flush p-2">
                            @foreach($otherPages as $other)
                                <a href="{{ route('upazila.show', $other->slug) }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center justify-content-between rounded-3 border-0 py-3 px-3 mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-geo-alt text-success"></i>
                                        <span class="fw-semibold text-dark">{{ $other->title }}</span>
                                    </div>
                                    <i class="bi bi-chevron-right text-muted small"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Online Service Order CTA Card --}}
                <div class="card border-0 shadow-sm rounded-4 text-white p-4 mb-4"
                    style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
                    <span class="badge bg-warning text-dark align-self-start px-3 py-1 rounded-pill mb-3 fw-bold">
                        <i class="bi bi-lightning-charge-fill"></i> দ্রুত সেবা
                    </span>
                    <h5 class="fw-bold text-white mb-2">অনলাইন আবেদন ও কম্পিউটার সেবা</h5>
                    <p class="text-white-50 small mb-4">
                        যেকোনো সরকারি বা বেসরকারি আবেদন, ফরম পূরণ, ভোটার আইডি কার্ড সংশোধন ও প্রিন্ট সেবার জন্য ঘরে বসেই আবেদন করুন।
                    </p>
                    <a href="{{ route('orders.create') }}" class="btn btn-success fw-bold py-2 rounded-pill shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-send-fill"></i> অনলাইনে কাজের আবেদন করুন
                    </a>
                </div>

                {{-- Contact Hotline Widget --}}
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h6 class="fw-bold text-dark mb-3">
                        <i class="bi bi-headset text-success me-2"></i> সরাসরি সহায়তা প্রয়োজন?
                    </h6>
                    <p class="text-muted small mb-3">
                        উপজেলা সংক্রান্ত বা কম্পিউটার সেবার যেকোনো তথ্যের জন্য আমাদের কল করুন বা হোয়াটসঅ্যাপে মেসেজ দিন:
                    </p>
                    <div class="d-flex flex-column gap-2">
                        <a href="tel:01711665098" class="btn btn-outline-dark btn-sm rounded-pill py-2 text-start px-3">
                            <i class="bi bi-telephone-fill text-success me-2"></i> ০১৭১১-৬৬৫০৯৮
                        </a>
                        <a href="https://wa.me/8801711665098" target="_blank" class="btn btn-outline-success btn-sm rounded-pill py-2 text-start px-3">
                            <i class="bi bi-whatsapp text-success me-2"></i> হোয়াটসঅ্যাপ মেসেজ
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

