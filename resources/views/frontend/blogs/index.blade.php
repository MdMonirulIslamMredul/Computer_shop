@extends('frontend.layouts.app')

@section('title', 'ব্লগ ও তথ্য বাতায়ন | ' . ($settings->site_name ?? 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার'))
@section('meta_description', 'সরকারি-বেসরকারি চাকরির বিজ্ঞপ্তি, অনলাইন আবেদন নির্দেশিকা, প্রযুক্তি টিপস ও সৃজনশীল লাইব্রেরী এন্ড কম্পিউটারের সর্বশেষ তথ্য ও আপডেট।')

@php
    $bnDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    $enDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    $bnMonths = [
        1 => 'জানুয়ারি', 2 => 'ফেব্রুয়ারি', 3 => 'মার্চ', 4 => 'এপ্রিল',
        5 => 'মে', 6 => 'জুন', 7 => 'জুলাই', 8 => 'আগস্ট',
        9 => 'সেপ্টেম্বর', 10 => 'অক্টোবর', 11 => 'নভেম্বর', 12 => 'ডিসেম্বর'
    ];
    $bnShortMonths = [
        1 => 'জানু', 2 => 'ফেব্রু', 3 => 'মার্চ', 4 => 'এপ্রিল',
        5 => 'মে', 6 => 'জুন', 7 => 'জুলাই', 8 => 'আগস্ট',
        9 => 'সেপ্টে', 10 => 'অক্টো', 11 => 'নভে', 12 => 'ডিসে'
    ];
    $toBn = function ($number) use ($enDigits, $bnDigits) {
        return str_replace($enDigits, $bnDigits, (string) $number);
    };
    $formatBnDate = function ($date) use ($bnMonths, $toBn) {
        if (!$date) return 'সম্প্রতি';
        $d = $toBn($date->format('d'));
        $m = $bnMonths[(int) $date->format('n')] ?? $date->format('M');
        $y = $toBn($date->format('Y'));
        return "{$d} {$m}, {$y}";
    };
@endphp

@push('styles')
<style>
    /* Hero Banner */
    .blog-hero {
        background: linear-gradient(135deg, #064e3b 0%, #059669 55%, #047857 100%);
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }
    .blog-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(circle at 15% 25%, rgba(16, 185, 129, 0.25) 0%, transparent 45%),
            radial-gradient(circle at 85% 75%, rgba(5, 150, 105, 0.2) 0%, transparent 45%);
        pointer-events: none;
    }
    .hero-search-wrapper {
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 50px;
        padding: 6px;
        backdrop-filter: blur(10px);
    }
    .hero-search-wrapper .form-control {
        color: #ffffff !important;
    }
    .hero-search-wrapper .form-control::placeholder {
        color: rgba(255, 255, 255, 0.75) !important;
    }

    /* Filter Pills Bar */
    .archive-pills-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 6px;
        scrollbar-width: thin;
    }
    .archive-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        color: #475569;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }
    .archive-pill:hover {
        background: #ecfdf5;
        color: #059669;
        border-color: #a7f3d0;
        transform: translateY(-1px);
    }
    .archive-pill.active {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: #ffffff !important;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.35);
    }
    .archive-pill .pill-badge {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 50px;
        background: #f1f5f9;
        color: #64748b;
    }
    .archive-pill.active .pill-badge {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }

    /* Applied Filters Chips */
    .applied-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
        border-radius: 50px;
        font-size: 0.82rem;
        font-weight: 600;
        margin-right: 6px;
        margin-bottom: 6px;
    }
    .applied-chip a {
        color: #059669;
        text-decoration: none;
        font-weight: 800;
        font-size: 1rem;
        line-height: 1;
    }
    .applied-chip a:hover {
        color: #dc2626;
    }

    /* Blog Card */
    .blog-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
        position: relative;
    }
    .blog-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 36px rgba(5, 150, 105, 0.12);
        border-color: #059669;
    }

    .blog-media {
        position: relative;
        height: 220px;
        background: linear-gradient(180deg, #f0fdf4 0%, #ecfdf5 100%);
        overflow: hidden;
    }
    .blog-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.45s ease;
    }
    .blog-card:hover .blog-media img {
        transform: scale(1.06);
    }
    .blog-media-placeholder {
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #059669;
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    }

    /* Prominent Floating Calendar Date Badge */
    .blog-calendar-badge {
        position: absolute;
        top: 14px;
        left: 14px;
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.15);
        text-align: center;
        min-width: 54px;
        z-index: 3;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    .blog-cal-month {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: #ffffff;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 3px 6px;
        line-height: 1.1;
    }
    .blog-cal-day {
        color: #0f172a;
        font-size: 1.3rem;
        font-weight: 900;
        line-height: 1.1;
        padding: 4px 6px 1px;
    }
    .blog-cal-year {
        color: #64748b;
        font-size: 0.68rem;
        font-weight: 700;
        padding-bottom: 3px;
        line-height: 1;
    }

    /* Read time badge */
    .blog-read-time {
        position: absolute;
        top: 14px;
        right: 14px;
        background: rgba(15, 23, 42, 0.78);
        backdrop-filter: blur(8px);
        color: #ffffff;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 50px;
        z-index: 3;
    }

    .blog-card-body {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .blog-meta-row {
        font-size: 0.82rem;
        color: #64748b;
        margin-bottom: 0.65rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .blog-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        text-decoration: none;
        margin-bottom: 0.6rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.15s ease;
    }
    .blog-title:hover {
        color: #059669;
    }
    .blog-excerpt {
        color: #64748b;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 1.25rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex-grow: 1;
    }

    /* Sidebar Widget */
    .sidebar-widget {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
    }
    .sidebar-widget-title {
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 1.1rem;
        padding-bottom: 0.65rem;
        border-bottom: 2px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .recent-post-item {
        display: flex;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #f8fafc;
        text-decoration: none;
    }
    .recent-post-item:last-child {
        border-bottom: none;
    }
    .recent-post-thumb {
        width: 64px;
        height: 64px;
        border-radius: 12px;
        overflow: hidden;
        background: #f0fdf4;
        flex-shrink: 0;
    }
    .recent-post-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .recent-post-title {
        font-size: 0.88rem;
        font-weight: 600;
        color: #1e293b;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.15s ease;
    }
    .recent-post-item:hover .recent-post-title {
        color: #059669;
    }
    .recent-post-date {
        font-size: 0.74rem;
        color: #64748b;
        margin-top: 4px;
    }

    /* Archive Link */
    .archive-list-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 9px 12px;
        border-radius: 10px;
        color: #475569;
        text-decoration: none;
        font-size: 0.88rem;
        font-weight: 500;
        transition: all 0.15s ease;
        margin-bottom: 3px;
    }
    .archive-list-link:hover {
        background: #ecfdf5;
        color: #059669;
        padding-left: 16px;
    }
    .archive-list-link.active {
        background: rgba(5, 150, 105, 0.12);
        color: #059669;
        font-weight: 700;
    }

    /* Dark theme overrides */
    html[data-theme='dark'] .blog-index-main {
        background: var(--surface-alt-color) !important;
    }
    html[data-theme='dark'] .blog-index-main :is(.blog-card, .sidebar-widget, .blog-calendar-badge) {
        background-color: var(--surface-color);
        border-color: var(--border-color);
    }
    html[data-theme='dark'] .blog-index-main :is(.blog-media, .blog-media-placeholder) {
        background: linear-gradient(135deg, #16332f, #0f172a);
    }
    html[data-theme='dark'] .blog-index-main :is(.recent-post-thumb, .archive-list-link:hover, .archive-list-link:focus-visible) {
        background: #16332f;
    }
    html[data-theme='dark'] .blog-index-main :is(.sidebar-widget-title, .recent-post-item, .border-top) {
        border-color: var(--border-color) !important;
    }
    html[data-theme='dark'] .blog-index-main .blog-card:is(:hover, :focus-within) {
        border-color: #6ee7b7;
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.35);
    }
    html[data-theme='dark'] .blog-index-main :is(.blog-excerpt, .blog-cal-year, .recent-post-date),
    html[data-theme='dark'] .blog-index-main .blog-meta-row :is(span, strong, i) {
        color: #cbd5e1 !important;
    }
    html[data-theme='dark'] .blog-index-main .blog-title:is(:hover, :focus-visible),
    html[data-theme='dark'] .blog-index-main .recent-post-item:is(:hover, :focus-visible) .recent-post-title,
    html[data-theme='dark'] .blog-index-main .archive-list-link:is(:hover, :focus-visible, .active) :is(span, i) {
        color: #6ee7b7 !important;
    }
    html[data-theme='dark'] .blog-index-main .archive-list-link.active {
        background: rgba(16, 185, 129, 0.18);
    }
    html[data-theme='dark'] .archive-pills-bar .archive-pill {
        background: var(--surface-color);
        border-color: var(--border-color);
    }
    html[data-theme='dark'] .archive-pills-bar .archive-pill:is(:hover, :focus-visible) {
        background: #16332f;
        border-color: #6ee7b7;
    }
    html[data-theme='dark'] .archive-pills-bar .archive-pill:is(:hover, :focus-visible) :is(span, i) {
        color: #6ee7b7 !important;
    }
    html[data-theme='dark'] .archive-pills-bar .archive-pill.active {
        background: linear-gradient(135deg, #065f46, #047857);
        border-color: #6ee7b7;
    }
    html[data-theme='dark'] .archive-pills-bar .archive-pill .pill-badge {
        background: #1e293b;
        color: #cbd5e1 !important;
    }
    html[data-theme='dark'] .archive-pills-bar .archive-pill.active .pill-badge {
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff !important;
    }
    html[data-theme='dark'] .blog-index-main .applied-chip {
        background: #16332f;
        border-color: #059669;
        color: #a7f3d0 !important;
    }
    html[data-theme='dark'] .blog-index-main .applied-chip a {
        color: #6ee7b7 !important;
    }
</style>
@endpush

@section('content')

    {{-- HERO HEADER --}}
    <section class="blog-hero py-5">
        <div class="container py-3">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3 small">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-white text-opacity-75 text-decoration-none">
                            <i class="bi bi-house-door me-1"></i> হোম
                        </a>
                    </li>
                    <li class="breadcrumb-item {{ !$selectedYear && !$selectedMonth ? 'active text-white' : '' }}">
                        @if($selectedYear || $selectedMonth)
                            <a href="{{ route('blogs.index') }}" class="text-white text-opacity-75 text-decoration-none">
                                ব্লগ ও তথ্য বাতায়ন
                            </a>
                        @else
                            ব্লগ ও তথ্য বাতায়ন
                        @endif
                    </li>
                    @if($selectedYear && $selectedMonth)
                        <li class="breadcrumb-item active text-white" aria-current="page">
                            {{ $bnMonths[(int) $selectedMonth] ?? '' }} {{ $toBn($selectedYear) }}
                        </li>
                    @elseif($selectedYear)
                        <li class="breadcrumb-item active text-white" aria-current="page">
                            সাল {{ $toBn($selectedYear) }}
                        </li>
                    @endif
                </ol>
            </nav>

            <div class="row align-items-center g-4">
                <div class="col-lg-7" data-aos="fade-right">
                    <span class="badge bg-white text-success rounded-pill px-3 py-2 fw-semibold mb-3 shadow-sm">
                        <i class="bi bi-journal-richtext me-1"></i> সৃজনশীল ব্লগ ও তথ্য বাতায়ন
                    </span>
                    <h1 class="display-6 fw-bold mb-3 text-white">ডিজিটাল সেবা, প্রযুক্তি টিপস ও সমসাময়িক আপডেট</h1>
                    <p class="lead text-white-50 mb-0" style="max-width: 650px; line-height: 1.7; font-size: 1.05rem;">
                        সরকারি-বেসরকারি চাকরির বিজ্ঞপ্তি, অনলাইন আবেদন নির্দেশিকা, পাসপোর্ট ও জাতীয় পরিচয়পত্র সেবা এবং সৃজনশীল লাইব্রেরী ও কম্পিউটারের সর্বশেষ আপডেট তথ্য এক নজরে পড়ুন।
                    </p>
                </div>
                <div class="col-lg-5" data-aos="fade-left">
                    {{-- Quick Search Form --}}
                    <form action="{{ route('blogs.index') }}" method="GET" class="hero-search-wrapper shadow-sm">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-0 text-white ps-3">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="q" class="form-control bg-transparent border-0 shadow-none text-white"
                                   placeholder="আর্টিকেল খুঁজুন (যেমন: চাকরির আবেদন)..."
                                   value="{{ $search }}">
                            @if($selectedYear)
                                <input type="hidden" name="year" value="{{ $selectedYear }}">
                            @endif
                            @if($selectedMonth)
                                <input type="hidden" name="month" value="{{ $selectedMonth }}">
                            @endif
                            <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold text-dark shadow-sm">
                                অনুসন্ধান
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- INTERACTIVE DATE ARCHIVES PILL FILTER BAR --}}
    <section class="py-3 bg-white border-bottom shadow-sm">
        <div class="container">
            <div class="archive-pills-bar">
                {{-- All Posts --}}
                <a href="{{ route('blogs.index', request()->except(['year', 'month', 'page'])) }}"
                   class="archive-pill {{ !$selectedYear && !$selectedMonth ? 'active' : '' }}">
                    <i class="bi bi-grid-fill"></i>
                    <span>সকল আর্টিকেল</span>
                    <span class="pill-badge">{{ $toBn($totalCount) }}</span>
                </a>

                {{-- Each Monthly Archive Pill --}}
                @foreach($archives as $arc)
                    @php
                        $isArcActive = ($selectedYear == $arc->year && $selectedMonth == $arc->month);
                        $mName = $bnMonths[(int) $arc->month] ?? $arc->month;
                    @endphp
                    <a href="{{ route('blogs.index', array_merge(request()->except(['page']), ['year' => $arc->year, 'month' => $arc->month])) }}"
                       class="archive-pill {{ $isArcActive ? 'active' : '' }}">
                        <i class="bi bi-calendar3"></i>
                        <span>{{ $mName }} {{ $toBn($arc->year) }}</span>
                        <span class="pill-badge">{{ $toBn($arc->count) }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- MAIN BLOG CONTENT SECTION --}}
    <section class="blog-index-main py-5" style="background: #f8fafc;">
        <div class="container">
            <div class="row g-4">

                {{-- MAIN COLUMN: ARTICLES GRID (COL-LG-8) --}}
                <div class="col-lg-8">

                    {{-- Top Controls Bar --}}
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 bg-white p-3 rounded-4 border">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="fw-bold text-dark fs-6">
                                মোট <span class="text-success">{{ $toBn($blogs->total()) }}</span> টি আর্টিকেল পাওয়া গেছে
                            </span>
                            @if($selectedYear && $selectedMonth)
                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1">
                                    {{ $bnMonths[(int) $selectedMonth] ?? '' }} {{ $toBn($selectedYear) }}
                                </span>
                            @endif
                        </div>

                        {{-- Sort Dropdown --}}
                        <div class="d-flex align-items-center gap-2 ms-auto">
                            <form action="{{ route('blogs.index') }}" method="GET" class="d-flex align-items-center gap-2 m-0">
                                @foreach(request()->except(['sort', 'page']) as $k => $v)
                                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                @endforeach

                                <label for="sortSelect" class="small fw-semibold text-muted text-nowrap d-none d-sm-inline">
                                    সাজান:
                                </label>
                                <select name="sort" id="sortSelect" class="form-select form-select-sm rounded-pill fw-semibold border-secondary-subtle px-3"
                                        onchange="this.form.submit()">
                                    <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>সর্বশেষ প্রকাশিত</option>
                                    <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>প্রাচীনতম আগে</option>
                                    <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>শিরোনাম (ক - হ)</option>
                                    <option value="name_desc" {{ $sort === 'name_desc' ? 'selected' : '' }}>শিরোনাম (হ - ক)</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    {{-- Applied Filters Chips --}}
                    @if($activeFiltersCount > 0)
                        <div class="mb-4 d-flex flex-wrap align-items-center">
                            <span class="small text-muted fw-bold me-2">প্রযুক্ত ফিল্টার:</span>
                            @if($search !== '')
                                <span class="applied-chip">
                                    অনুসন্ধান: "{{ Str::limit($search, 16) }}"
                                    <a href="{{ request()->fullUrlWithQuery(['q' => null, 'search' => null]) }}" title="ফিল্টার মুছুন">&times;</a>
                                </span>
                            @endif
                            @if($selectedYear && $selectedMonth)
                                <span class="applied-chip">
                                    আর্কাইভ: {{ $bnMonths[(int) $selectedMonth] ?? '' }} {{ $toBn($selectedYear) }}
                                    <a href="{{ request()->fullUrlWithQuery(['year' => null, 'month' => null]) }}" title="ফিল্টার মুছুন">&times;</a>
                                </span>
                            @elseif($selectedYear)
                                <span class="applied-chip">
                                    সাল: {{ $toBn($selectedYear) }}
                                    <a href="{{ request()->fullUrlWithQuery(['year' => null]) }}" title="ফিল্টার মুছুন">&times;</a>
                                </span>
                            @endif
                            <a href="{{ route('blogs.index') }}" class="small text-danger fw-semibold ms-2 text-decoration-none">
                                <i class="bi bi-x-circle me-1"></i> সকল ফিল্টার রিসেট
                            </a>
                        </div>
                    @endif

                    {{-- Articles Grid (2-column layout in col-lg-8) --}}
                    @if($blogs->count() > 0)
                        <div class="row g-4">
                            @foreach($blogs as $blog)
                                @php
                                    $postDate = $blog->published_at ?? $blog->created_at;
                                    $readMinutes = max(1, (int) round(str_word_count(strip_tags($blog->body ?? $blog->excerpt ?? '')) / 180));
                                    $shortMonthName = $postDate ? ($bnShortMonths[(int) $postDate->format('n')] ?? $postDate->format('M')) : '';
                                @endphp
                                <div class="col-md-6" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 2) * 100 }}">
                                    <article class="blog-card">
                                        {{-- Media Banner --}}
                                        <div class="blog-media">
                                            {{-- Prominent Floating Calendar Date Badge in Bangla --}}
                                            @if($postDate)
                                                <div class="blog-calendar-badge" title="প্রকাশনার তারিখ: {{ $formatBnDate($postDate) }}">
                                                    <div class="blog-cal-month">{{ $shortMonthName }}</div>
                                                    <div class="blog-cal-day">{{ $toBn($postDate->format('d')) }}</div>
                                                    <div class="blog-cal-year">{{ $toBn($postDate->format('Y')) }}</div>
                                                </div>
                                            @endif

                                            {{-- Read Time Pill --}}
                                            <span class="blog-read-time">
                                                <i class="bi bi-clock me-1 text-warning"></i> {{ $toBn($readMinutes) }} মিনিট পাঠ
                                            </span>

                                            @if($blog->image)
                                                <a href="{{ route('blogs.show', $blog->slug) }}">
                                                    <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" loading="lazy">
                                                </a>
                                            @else
                                                <a href="{{ route('blogs.show', $blog->slug) }}" class="text-decoration-none">
                                                    <div class="blog-media-placeholder">
                                                        <i class="bi bi-journal-richtext" style="font-size: 3.2rem;"></i>
                                                        <span class="small mt-2 fw-semibold">সৃজনশীল ব্লগ বাতায়ন</span>
                                                    </div>
                                                </a>
                                            @endif
                                        </div>

                                        {{-- Body --}}
                                        <div class="blog-card-body">
                                            {{-- Inline Post Date & Author Meta --}}
                                            <div class="blog-meta-row">
                                                <span>
                                                    <i class="bi bi-calendar3 text-success me-1"></i>
                                                    <strong>{{ $formatBnDate($postDate) }}</strong>
                                                </span>
                                                <span>&bull;</span>
                                                <span>
                                                    <i class="bi bi-person text-secondary me-1"></i> সৃজনশীল টিম
                                                </span>
                                            </div>

                                            <h2 class="fs-5 mb-2">
                                                <a href="{{ route('blogs.show', $blog->slug) }}" class="blog-title" title="{{ $blog->title }}">
                                                    {{ $blog->title }}
                                                </a>
                                            </h2>

                                            <p class="blog-excerpt">
                                                {{ $blog->excerpt ?? Str::limit(strip_tags($blog->body), 110) }}
                                            </p>

                                            <div class="d-flex align-items-center justify-content-between pt-3 border-top mt-auto">
                                                <a href="{{ route('blogs.show', $blog->slug) }}" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-semibold">
                                                    বিস্তারিত পড়ুন <i class="bi bi-arrow-right ms-1"></i>
                                                </a>
                                                <span class="text-muted small">
                                                    <i class="bi bi-book me-1"></i> পূর্ণাঙ্গ গাইড
                                                </span>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-5 d-flex justify-content-center">
                            {{ $blogs->links() }}
                        </div>

                    @else
                        {{-- Empty State --}}
                        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white my-4">
                            <div class="py-4">
                                <div class="d-inline-flex p-4 rounded-circle bg-success-subtle text-success mb-3">
                                    <i class="bi bi-journal-x fs-1"></i>
                                </div>
                                <h4 class="fw-bold text-dark">কোনো আর্টিকেল পাওয়া যায়নি</h4>
                                <p class="text-muted mx-auto" style="max-width: 480px;">
                                    আপনার অনুসন্ধানের কি-ওয়ার্ড বা নির্বাচিত তারিখের সাথে মেলে এমন কোনো ব্লগ পোস্ট খুঁজে পাওয়া যায়নি।
                                </p>
                                <div class="mt-3">
                                    <a href="{{ route('blogs.index') }}" class="btn btn-success rounded-pill px-4 fw-semibold">
                                        <i class="bi bi-arrow-clockwise me-1"></i> সকল আর্টিকেল দেখুন
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

                {{-- SIDEBAR COLUMN (COL-LG-4) --}}
                <aside class="col-lg-4">

                    {{-- 1. Search Box Widget --}}
                    <div class="sidebar-widget">
                        <div class="sidebar-widget-title">
                            <i class="bi bi-search text-success"></i> আর্টিকেল অনুসন্ধান
                        </div>
                        <form action="{{ route('blogs.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control rounded-start-pill ps-3"
                                       placeholder="কী-ওয়ার্ড লিখুন..." value="{{ $search }}">
                                <button type="submit" class="btn btn-success rounded-end-pill px-3">
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- 2. Recent Posts Widget with Post Dates --}}
                    @if(isset($recentBlogs) && $recentBlogs->count() > 0)
                        <div class="sidebar-widget">
                            <div class="sidebar-widget-title">
                                <i class="bi bi-clock-history text-success"></i> সাম্প্রতিক পোস্টসমূহ
                            </div>
                            <div class="recent-posts-list">
                                @foreach($recentBlogs as $recent)
                                    @php
                                        $rDate = $recent->published_at ?? $recent->created_at;
                                    @endphp
                                    <a href="{{ route('blogs.show', $recent->slug) }}" class="recent-post-item">
                                        <div class="recent-post-thumb">
                                            @if($recent->image)
                                                <img src="{{ asset('storage/' . $recent->image) }}" alt="{{ $recent->title }}">
                                            @else
                                                <div class="w-100 h-100 d-flex align-items-center justify-content-center text-success bg-success-subtle">
                                                    <i class="bi bi-file-earmark-text fs-4"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="recent-post-title">{{ $recent->title }}</div>
                                            <div class="recent-post-date">
                                                <i class="bi bi-calendar3 me-1 text-success"></i>
                                                {{ $formatBnDate($rDate) }}
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 3. Monthly Archives Widget --}}
                    @if(isset($archives) && $archives->count() > 0)
                        <div class="sidebar-widget">
                            <div class="sidebar-widget-title">
                                <i class="bi bi-archive text-success"></i> মাসিক আর্কাইভ
                            </div>
                            <div class="archives-list">
                                @foreach($archives as $arc)
                                    @php
                                        $isArcActive = ($selectedYear == $arc->year && $selectedMonth == $arc->month);
                                        $mName = $bnMonths[(int) $arc->month] ?? $arc->month;
                                    @endphp
                                    <a href="{{ route('blogs.index', ['year' => $arc->year, 'month' => $arc->month]) }}"
                                       class="archive-list-link {{ $isArcActive ? 'active' : '' }}">
                                        <span>
                                            <i class="bi bi-folder2-open me-2 text-success"></i>
                                            {{ $mName }} {{ $toBn($arc->year) }}
                                        </span>
                                        <span class="badge bg-success-subtle text-success rounded-pill">
                                            {{ $toBn($arc->count) }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 4. Consultation / Direct Contact Box (Tailored to Srijonshil Library & Computer) --}}
                    <div class="sidebar-widget p-4 text-center text-white" style="background: linear-gradient(135deg, #064e3b 0%, #047857 100%);">
                        <div class="d-inline-flex p-3 rounded-circle mb-3 fs-3" style="background: rgba(255, 255, 255, 0.15); color: #fbbf24;">
                            <i class="bi bi-laptop"></i>
                        </div>
                        <h4 class="fw-bold text-white fs-5 mb-2">অনলাইন সেবা বা আবেদন প্রয়োজন?</h4>
                        <p class="small text-white-50 mb-3" style="line-height: 1.6;">
                            চাকরির আবেদন, জন্মনিবন্ধন, পাসপোর্ট, ড্রাইভিং লাইসেন্স বা ভর্তি সহায়তার জন্য সরাসরি আমাদের সাথে যোগাযোগ করুন বা অনলাইনে আবেদন করুন।
                        </p>
                        <a href="{{ route('orders.create') }}" class="btn btn-warning rounded-pill w-100 py-2 fw-bold text-dark shadow-sm mb-2">
                            <i class="bi bi-send-fill me-1"></i> এখনই আবেদন করুন
                        </a>
                        <a href="{{ route('contact.index') }}" class="btn btn-outline-light rounded-pill w-100 py-2 fw-semibold btn-sm">
                            <i class="bi bi-geo-alt-fill me-1"></i> আমাদের অবস্থান ও যোগাযোগ
                        </a>
                    </div>

                </aside>

            </div>
        </div>
    </section>

@endsection
