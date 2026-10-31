@extends('frontend.layouts.app')

@section('title', 'আমাদের সম্পর্কে | ' . ($setting->site_name ?? 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার'))
@section('meta_description', 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার - রামগতি, লক্ষ্মীপুরের শীর্ষস্থানীয় ডিজিটাল ও অনলাইন সেবা কেন্দ্র।')

@section('content')
    @php
        $bannerImage = !empty($about?->banner_image)
            ? asset('storage/' . $about->banner_image)
            : 'https://images.unsplash.com/photo-1450133064473-71024230f91b?auto=format&fit=crop&w=1800&q=80';
        $image1 = !empty($about?->image1)
            ? asset('storage/' . $about->image1)
            : 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1200&q=80';
        $image2 = !empty($about?->image2)
            ? asset('storage/' . $about->image2)
            : 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1200&q=80';
        $keyValues = collect($about?->key_values ?? ['নির্ভুল ডাটা এন্ট্রি', 'অনলাইন ট্র্যাকিং', 'দ্রুত ডেলিভারি', 'সততা ও বিশ্বস্ততা'])
            ->filter()
            ->values();
    @endphp

    {{-- Hero Section --}}
    <section class="py-5 text-white"
        style="position: relative; background: url('{{ $bannerImage }}') center/cover no-repeat;">
        <div style="position: absolute; inset: 0; background: linear-gradient(120deg, rgba(6, 78, 59, 0.90), rgba(15, 23, 42, 0.88));">
        </div>
        <div class="container position-relative py-4" data-aos="fade-up">
            <span class="badge bg-white text-success px-3 py-1 rounded-pill fw-bold mb-3 shadow-sm">
                <i class="bi bi-info-circle me-1"></i> আমাদের পরিচিতি
            </span>
            <h1 class="display-5 fw-bold mb-3">{{ $about?->title ?? 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার সম্পর্কে' }}</h1>
            <p class="lead mb-0 text-white-50" style="max-width: 780px; line-height: 1.7;">
                {!! $about?->page_details ??
                    'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার হলো ডিজিটাল সেবা ও অনলাইন অ্যাপ্লিকেশনের এক নির্ভরযোগ্য কেন্দ্র। আমাদের অভিজ্ঞ টিম অত্যন্ত সতর্কতা ও নির্ভুলতার সাথে সরকারি চাকরি, পাসপোর্ট, এনআইডি, জন্ম নিবন্ধন এবং কলেজ-বিশ্ববিদ্যালয় ভর্তির আবেদন করে থাকে।' !!}
            </p>
        </div>
    </section>

    {{-- Main Content Section --}}
    <section class="py-5 bg-light">
        <div class="container">
            {{-- Mission & Vision Section --}}
            @php
                $missionText = $about?->mission ?? ($setting->mission ?? 'রামগতি ও পার্শ্ববর্তী অঞ্চলের সকল নাগরিক, চাকরিপ্রার্থী ও শিক্ষার্থীদের কাছে দ্রুততম, সাশ্রয়ী ও শতভাগ নির্ভুল ডিজিটাল সেবা পৌঁছে দেওয়া।');
                $visionText = $about?->vision ?? ($setting->vision ?? 'আধুনিক তথ্যপ্রযুক্তির সমন্বয়ে অত্র অঞ্চলের শীর্ষস্থানীয় ওয়ান-স্টপ কম্পিউটার ও অনলাইন সেবা কেন্দ্র হিসেবে প্রতিষ্ঠা লাভ করা।');
                $historyText = $about?->history ?? ($setting->history ?? 'রামগতির প্রাণকেন্দ্র আলেকজান্ডার জনতা বাজারে দীর্ঘদিনের অভিজ্ঞতা নিয়ে আমরা সততা ও সুনামের সাথে স্থানীয় জনগণের আস্থা অর্জন করে আসছি।');
            @endphp

            <div class="row g-4 mb-5" data-aos="fade-up">
                <div class="col-md-6">
                    <div class="card h-100 p-4 border-0 shadow-sm rounded-4"
                        style="border-top: 4px solid #059669 !important;">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-success-subtle text-success"
                                style="width: 48px; height: 48px; font-size: 1.5rem;">
                                <i class="bi bi-bullseye"></i>
                            </div>
                            <h3 class="h4 fw-bold mb-0 text-dark">আমাদের লক্ষ্য (Mission)</h3>
                        </div>
                        <div class="text-secondary fs-6" style="line-height: 1.8;">
                            {!! nl2br(e($missionText)) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 p-4 border-0 shadow-sm rounded-4"
                        style="border-top: 4px solid #dc2626 !important;">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-danger-subtle text-danger"
                                style="width: 48px; height: 48px; font-size: 1.5rem;">
                                <i class="bi bi-eye-fill"></i>
                            </div>
                            <h3 class="h4 fw-bold mb-0 text-dark">আমাদের দৃষ্টিভঙ্গি (Vision)</h3>
                        </div>
                        <div class="text-secondary fs-6" style="line-height: 1.8;">
                            {!! nl2br(e($visionText)) !!}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Feature Row 1 --}}
            <div class="row g-4 align-items-center mb-5">
                <div class="col-lg-6" data-aos="fade-right">
                    <img src="{{ $image1 }}" alt="সৃজনশীল লাইব্রেরী ও কম্পিউটার" class="img-fluid rounded-4 shadow-sm w-100" style="max-height: 400px; object-fit: cover;">
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="card p-4 p-lg-5 h-100 border-0 shadow-sm rounded-4 bg-white">
                        <div class="d-inline-flex align-items-center gap-2 mb-2 text-success fw-semibold small">
                            <i class="bi bi-laptop"></i> ডিজিটাল আবেদন ও প্রযুক্তি সেবা
                        </div>
                        <h3 class="h4 fw-bold mb-3 text-dark">অভিজ্ঞ অপারেটর ও শতভাগ নির্ভুল কাজ</h3>
                        <div class="text-secondary leading-relaxed" style="line-height: 1.8;">
                            {!! $about?->details1 ?? '<p>সরকারি চাকরি, বিসিএস, প্রাইমারি শিক্ষক, ব্যাংক ও বিভিন্ন মন্ত্রণালয়-অধিদপ্তরের চাকরির অনলাইন আবেদন অত্যন্ত সতর্কতা ও নির্ভুলতার সাথে সম্পন্ন করা হয়। কোটা নির্বাচন, ছবি ও স্বাক্ষরের সঠিক সাইজ এবং চালানের বিষয়ে পূর্ণ সহায়তা দেওয়া হয়।</p>' !!}
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('orders.create') }}" class="btn btn-success rounded-pill px-4 py-2 fw-semibold">
                                <i class="bi bi-send me-1"></i> এখনই সেবা অর্ডার করুন
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Feature Row 2 --}}
            <div class="row g-4 align-items-center mb-5">
                <div class="col-lg-6 order-lg-2" data-aos="fade-left">
                    <img src="{{ $image2 }}" alt="পাসপোর্ট ও এনআইডি সেবা" class="img-fluid rounded-4 shadow-sm w-100" style="max-height: 400px; object-fit: cover;">
                </div>
                <div class="col-lg-6 order-lg-1" data-aos="fade-right">
                    <div class="card p-4 p-lg-5 h-100 border-0 shadow-sm rounded-4 bg-white">
                        <div class="d-inline-flex align-items-center gap-2 mb-2 text-danger fw-semibold small">
                            <i class="bi bi-passport-fill"></i> পাসপোর্ট, জন্মনিবন্ধন ও NID সেবা
                        </div>
                        <h3 class="h4 fw-bold mb-3 text-dark">নাগরিক সেবা ও তথ্য সংশোধন</h3>
                        <div class="text-secondary leading-relaxed" style="line-height: 1.8;">
                            {!! $about?->details2 ?? '<p>ই-পাসপোর্ট নতুন আবেদন, মেয়াদোত্তীর্ণ পাসপোর্ট নবায়ন, পুলিশ ক্লিয়ারেন্স সার্টিফিকেট এবং জাতীয় পরিচয়পত্রের (NID) যেকোনো তথ্য সংশোধন ও হারানো আইডি কার্ডের অনলাইন কপি উত্তোলনে আমরা নির্ভরযোগ্য সহযোগী।</p>' !!}
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('orders.track') }}" class="btn btn-outline-success rounded-pill px-4 py-2 fw-semibold">
                                <i class="bi bi-search me-1"></i> কাজের অগ্রগতি ট্র্যাক করুন
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Journey / History Section --}}
            @if ($historyText)
                <div class="row g-4 mb-5" data-aos="fade-up">
                    <div class="col-12">
                        <div class="card p-4 p-lg-5 border-0 shadow-sm rounded-4 position-relative overflow-hidden bg-white"
                            style="border-left: 5px solid #059669 !important;">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-success-subtle text-success"
                                    style="width: 48px; height: 48px; font-size: 1.5rem;">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <h3 class="h4 fw-bold mb-0 text-dark">আমাদের পথচলা ও ইতিহাস</h3>
                            </div>
                            <div class="text-secondary fs-6" style="line-height: 1.8;">
                                {!! nl2br(e($historyText)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Stats & Key Values --}}
            <div class="row g-4">
                <div class="col-md-4" data-aos="zoom-in">
                    <div class="card p-4 text-center h-100 border-0 shadow-sm rounded-4 bg-white">
                        <div class="d-inline-flex p-3 rounded-circle bg-success-subtle text-success mx-auto mb-2 fs-2">
                            <i class="bi bi-award-fill"></i>
                        </div>
                        <h3 class="mb-1 text-success fw-bold">{{ $about?->years_experience ?? 10 }}+ বছর</h3>
                        <p class="mb-0 text-muted">সুনামের সাথে অবিচল সেবা</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="80">
                    <div class="card p-4 text-center h-100 border-0 shadow-sm rounded-4 bg-white">
                        <div class="d-inline-flex p-3 rounded-circle bg-primary-subtle text-primary mx-auto mb-2 fs-2">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <h3 class="mb-1 text-primary fw-bold">রামগতি, লক্ষ্মীপুর</h3>
                        <p class="mb-0 text-muted">জনতা বাজার, মেইন রোড, আলেকজান্ডার</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="zoom-in" data-aos-delay="160">
                    <div class="card p-4 h-100 border-0 shadow-sm rounded-4 bg-white">
                        <h5 class="mb-3 fw-bold text-dark"><i class="bi bi-check2-all text-success me-1"></i> মূল বৈশিষ্ট্যসমূহ:</h5>
                        <ul class="mb-0 ps-3 text-secondary" style="line-height: 1.8;">
                            @foreach ($keyValues as $value)
                                <li>{{ $value }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
