@extends('frontend.layouts.app')

@section('title', 'যোগাযোগ ও অবস্থান | ' . ($setting->site_name ?? 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার'))
@section('meta_description', 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার - জনতা বাজার, আলেকজান্ডার, রামগতি, লক্ষ্মীপুর। সরাসরি যোগাযোগ করুন বা অনলাইনে আবেদন অর্ডার করুন।')

@php
    $mapEmbed = $setting?->google_map_embed;
    $mapSrc = null;
    if (!empty($mapEmbed)) {
        if (preg_match('/src=["\']([^"\']+)["\']/', $mapEmbed, $matches)) {
            $mapSrc = $matches[1];
        } else {
            $mapSrc = $mapEmbed;
        }
    }
    if (!$mapSrc && !empty($setting?->contact_address)) {
        $mapSrc = 'https://maps.google.com/maps?q=' . urlencode($setting->contact_address) . '&t=&z=14&ie=UTF8&iwloc=&output=embed';
    }
@endphp

@push('styles')
<style>
    .contact-hero {
        background: linear-gradient(135deg, #064e3b 0%, #059669 55%, #047857 100%);
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }
    .contact-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(circle at 15% 25%, rgba(16, 185, 129, 0.25) 0%, transparent 45%),
            radial-gradient(circle at 85% 75%, rgba(5, 150, 105, 0.2) 0%, transparent 45%);
        pointer-events: none;
    }
    .contact-icon-box {
        width: 48px;
        height: 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
    {{-- Hero Section with Bengali Banner Texts --}}
    <section class="contact-hero py-5">
        <div class="container py-lg-4 position-relative" data-aos="fade-up">
            <div class="row align-items-center justify-content-between g-4">
                <div class="col-lg-8">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-15 border border-white border-opacity-25 text-white small fw-semibold mb-3">
                        <i class="bi bi-clock-fill text-warning"></i> সকাল ৮:০০ - রাত ১০:০০ খোলা
                    </div>
                    <h1 class="display-5 fw-bold text-white mb-3">আমাদের সাথে যোগাযোগ করুন ও দোকানে আসুন</h1>
                    <p class="lead text-white-50 mb-0" style="max-width: 740px; line-height: 1.7;">
                        চাকরির আবেদন, জন্মনিবন্ধন, পাসপোর্ট, এনআইডি ও ভর্তি আবেদন সংক্রান্ত যেকোনো প্রয়োজনে সরাসরি কল করুন অথবা জনতা বাজারে আমাদের দোকানে আসুন।
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('orders.create') }}" class="btn btn-warning btn-lg rounded-pill px-4 py-3 fw-bold text-dark shadow">
                        <i class="bi bi-send-fill me-1"></i> সরাসরি অনলাইন অর্ডার করুন
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Contact Section --}}
    <section class="py-5 bg-light">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-4 mb-4 p-4 border-0 d-flex align-items-center gap-3" role="alert" data-aos="fade-down">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success text-white p-2">
                        <i class="bi bi-check-lg fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1 text-success">মেসেজ সফলভাবে পাঠানো হয়েছে!</h5>
                        <div class="small mb-0">{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-4 mb-4 p-3 border-0" role="alert">
                    <div class="d-flex align-items-center gap-2 fw-semibold">
                        <i class="bi bi-exclamation-triangle-fill"></i> অনুগ্রহ করে নিচের ভুলগুলো সংশোধন করুন:
                    </div>
                    <ul class="mb-0 mt-2 small ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-4 align-items-stretch">
                {{-- Form Column --}}
                <div class="col-lg-7" data-aos="fade-right">
                    <div class="card p-4 p-lg-5 border-0 shadow-sm rounded-4 h-100 bg-white">
                        <div class="mb-4">
                            <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill fw-semibold mb-2">বার্তা পাঠান</span>
                            <h3 class="fw-bold text-dark mb-1">আমাদের মেসেজ বা অনুসন্ধান পাঠান</h3>
                            <p class="text-secondary small mb-0">আপনার কোনো কাজ বা জিজ্ঞাসা থাকলে নিচের ফরমটি পূরণ করুন, আমরা দ্রুত আপনার সাথে যোগাযোগ করব।</p>
                        </div>

                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">আপনার নাম <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-secondary">
                                            <i class="bi bi-person-fill"></i>
                                        </span>
                                        <input type="text" name="name" class="form-control border-start-0 @error('name') is-invalid @enderror"
                                            value="{{ old('name') }}" placeholder="মোঃ মনিরুল ইসলাম" required>
                                    </div>
                                    @error('name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">মোবাইল নম্বর <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-secondary">
                                            <i class="bi bi-telephone-fill"></i>
                                        </span>
                                        <input type="tel" name="phone" class="form-control border-start-0 @error('phone') is-invalid @enderror"
                                            value="{{ old('phone') }}" placeholder="০১৭xxxxxxxx" required>
                                    </div>
                                    @error('phone')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">ইমেইল ঠিকানা <small class="text-secondary">(ঐচ্ছিক)</small></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-secondary">
                                            <i class="bi bi-envelope-fill"></i>
                                        </span>
                                        <input type="email" name="email" class="form-control border-start-0 @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}" placeholder="example@mail.com">
                                    </div>
                                    @error('email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">বিষয় / সেবার নাম <small class="text-secondary">(ঐচ্ছিক)</small></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-secondary">
                                            <i class="bi bi-chat-text-fill"></i>
                                        </span>
                                        <input type="text" name="subject" class="form-control border-start-0 @error('subject') is-invalid @enderror"
                                            value="{{ old('subject') }}" placeholder="উদাঃ চাকরির আবেদন সংক্রান্ত">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark">আপনার বার্তা / প্রয়োজনীয় কাজের বিবরণ <span class="text-danger">*</span></label>
                                    <textarea name="message" class="form-control @error('message') is-invalid @enderror" rows="5"
                                        placeholder="আপনার কী কাজ বা কী তথ্য প্রয়োজন তা বিস্তারিত লিখুন..." required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-success px-5 py-3 rounded-pill fw-semibold shadow-sm d-inline-flex align-items-center gap-2">
                                        <i class="bi bi-send-fill"></i> মেসেজ পাঠান
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Contact Info Column --}}
                <div class="col-lg-5" data-aos="fade-left">
                    <div class="d-flex flex-column gap-4 h-100">
                        {{-- Office Information Card --}}
                        <div class="card p-4 border-0 shadow-sm rounded-4 bg-white">
                            <h4 class="fw-bold mb-3 text-dark">যোগাযোগের তথ্য</h4>
                            <div class="d-flex flex-column gap-3">
                                @if ($setting?->contact_address)
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="contact-icon-box bg-danger-subtle text-danger">
                                            <i class="bi bi-geo-alt-fill"></i>
                                        </div>
                                        <div>
                                            <div class="small fw-semibold text-dark">দোকানের অবস্থান ও ঠিকানা:</div>
                                            <div class="text-secondary small">{{ $setting->contact_address }}</div>
                                        </div>
                                    </div>
                                @endif

                                @if ($setting?->contact_phone)
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="contact-icon-box bg-success-subtle text-success">
                                            <i class="bi bi-telephone-fill"></i>
                                        </div>
                                        <div>
                                            <div class="small fw-semibold text-dark">হটলাইন ও মোবাইল নম্বর:</div>
                                            <a href="tel:01711665098" class="text-secondary small text-decoration-none fw-bold d-block">
                                                {{ $setting->contact_phone }}
                                            </a>
                                            <a href="https://wa.me/8801711665098" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 mt-2">
                                                <i class="bi bi-whatsapp me-1"></i> হোয়াটসঅ্যাপে মেসেজ দিন
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                @if ($setting?->contact_email)
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="contact-icon-box bg-primary-subtle text-primary">
                                            <i class="bi bi-envelope-fill"></i>
                                        </div>
                                        <div>
                                            <div class="small fw-semibold text-dark">ইমেইল:</div>
                                            <a href="mailto:{{ $setting->contact_email }}" class="text-secondary small text-decoration-none">
                                                {{ $setting->contact_email }}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Map Card --}}
                        <div class="card p-3 border-0 shadow-sm rounded-4 flex-grow-1 overflow-hidden d-flex flex-column bg-white">
                            <div class="d-flex align-items-center justify-content-between mb-2 px-2 pt-1">
                                <span class="fw-bold text-dark small d-flex align-items-center gap-2">
                                    <i class="bi bi-map-fill text-danger"></i> গুগল ম্যাপে অবস্থান
                                </span>
                                @if ($mapSrc)
                                    <a href="{{ $mapSrc }}" target="_blank" rel="noopener" class="small text-success text-decoration-none d-inline-flex align-items-center gap-1">
                                        বড় ম্যাপে দেখুন <i class="bi bi-box-arrow-up-right small"></i>
                                    </a>
                                @endif
                            </div>
                            <div class="rounded-3 overflow-hidden flex-grow-1" style="min-height: 220px; background: #0f172a;">
                                <iframe src="{{ $mapSrc ?: 'https://maps.google.com/maps?q=Alexander,Ramgati,Lakshmipur&t=&z=14&ie=UTF8&iwloc=&output=embed' }}"
                                    width="100%"
                                    height="100%"
                                    style="border:0; min-height: 220px;"
                                    allowfullscreen=""
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
