@extends('frontend.layouts.app')

@section('title', ($blog->meta_title ?: $blog->title) . ' | ' . ($settings->site_name ?? 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার'))
@section('meta_description', $blog->meta_description ?: ($blog->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($blog->body), 155)))
@section('meta_keywords', $blog->seo_keywords ?: 'কম্পিউটার সেবা, অনলাইন আবেদন, চাকরির বিজ্ঞপ্তি, প্রযুক্তি টিপস, সৃজনশীল লাইব্রেরী')

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

    $postDate = $blog->published_at ?? $blog->created_at;
    $readingMinutes = max(1, (int) ceil(str_word_count(strip_tags($blog->body)) / 180));
    $archiveMonthName = $blog->published_at ? ($bnMonths[(int) $blog->published_at->format('n')] ?? '') : '';
@endphp

@push('styles')
<style>
    /* Hero Header */
    .blog-detail-hero {
        background: linear-gradient(135deg, #064e3b 0%, #059669 55%, #047857 100%);
        color: #ffffff;
        position: relative;
        overflow: hidden;
    }
    .blog-detail-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(circle at 15% 25%, rgba(16, 185, 129, 0.25) 0%, transparent 45%),
            radial-gradient(circle at 85% 75%, rgba(5, 150, 105, 0.2) 0%, transparent 45%);
        pointer-events: none;
    }

    .hero-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #ffffff !important;
        border-radius: 50px;
        font-size: 0.82rem;
        font-weight: 600;
        text-decoration: none;
        backdrop-filter: blur(8px);
        transition: all 0.2s ease;
    }
    .hero-badge-pill:hover {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff !important;
        transform: translateY(-1px);
    }

    .date-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        color: #0f172a;
        padding: 6px 16px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.85rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }
    .date-badge-pill i {
        color: #059669;
        font-size: 1rem;
    }

    /* Featured Media */
    .blog-featured-media-wrapper {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.08);
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        margin-bottom: 2rem;
        position: relative;
    }
    .blog-featured-media-wrapper img {
        width: 100%;
        max-height: 480px;
        object-fit: cover;
        display: block;
    }

    /* Floating Calendar Card on Featured Image */
    .featured-calendar-badge {
        position: absolute;
        bottom: 20px;
        left: 20px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.2);
        display: flex;
        align-items: center;
        border: 1px solid rgba(255, 255, 255, 0.6);
        z-index: 2;
    }
    .featured-cal-cube {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: #ffffff;
        padding: 10px 14px;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        line-height: 1.1;
    }
    .featured-cal-cube .fc-month {
        font-size: 0.75rem;
        font-weight: 700;
    }
    .featured-cal-cube .fc-day {
        font-size: 1.5rem;
        font-weight: 900;
        margin-top: 2px;
    }
    .featured-cal-info {
        padding: 8px 16px;
        font-size: 0.85rem;
        color: #334155;
    }
    .featured-cal-info strong {
        color: #0f172a;
        display: block;
    }

    /* Article Lead / Excerpt Callout */
    .article-lead-callout {
        background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
        border-left: 5px solid #059669;
        border-radius: 0 18px 18px 0;
        padding: 1.5rem 1.8rem;
        font-size: 1.1rem;
        line-height: 1.8;
        color: #064e3b;
        font-weight: 500;
        margin-bottom: 2rem;
        position: relative;
    }
    .article-lead-callout i.quote-icon {
        font-size: 2rem;
        color: #059669;
        opacity: 0.45;
        margin-bottom: 0.5rem;
        display: inline-block;
    }

    /* Article Card & Content Typography */
    .article-body-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 2.5rem;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
    }
    .article-content {
        font-size: 1.05rem;
        line-height: 1.9;
        color: #334155;
    }
    .article-content p {
        margin-bottom: 1.5rem;
    }
    .article-content h1,
    .article-content h2,
    .article-content h3,
    .article-content h4 {
        color: #0f172a;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
        line-height: 1.4;
    }
    .article-content h2 {
        font-size: 1.55rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #ecfdf5;
        color: #065f46;
    }
    .article-content h3 {
        font-size: 1.3rem;
    }
    .article-content ul,
    .article-content ol {
        margin-bottom: 1.5rem;
        padding-left: 1.6rem;
    }
    .article-content li {
        margin-bottom: 0.5rem;
    }
    .article-content blockquote {
        margin: 1.8rem 0;
        padding: 1.2rem 1.6rem;
        background: #f0fdf4;
        border-left: 4px solid #059669;
        border-radius: 0 12px 12px 0;
        font-style: italic;
        color: #065f46;
    }
    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 14px;
        margin: 1.5rem 0;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
    }
    .article-content table {
        width: 100%;
        margin: 1.5rem 0;
        border-collapse: collapse;
    }
    .article-content table th,
    .article-content table td {
        border: 1px solid #e2e8f0;
        padding: 10px 14px;
        text-align: left;
    }
    .article-content table th {
        background: #f8fafc;
        font-weight: 700;
        color: #0f172a;
    }

    /* Tags / Keywords */
    .article-tag-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: #f1f5f9;
        color: #475569;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        margin-right: 6px;
        margin-bottom: 6px;
    }
    .article-tag-pill:hover {
        background: #ecfdf5;
        color: #059669;
        transform: translateY(-1px);
    }

    /* Social Share Buttons */
    .share-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        color: #ffffff;
        font-size: 0.95rem;
        text-decoration: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .share-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        color: #ffffff;
    }
    .share-facebook { background: #1877f2; }
    .share-whatsapp { background: #25d366; }
    .share-twitter { background: #000000; }
    .share-linkedin { background: #0077b5; }
    .share-copy { background: #059669; }

    /* Next / Previous Article Navigation */
    .article-nav-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.25rem;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        transition: all 0.25s ease;
        height: 100%;
    }
    .article-nav-card:hover {
        border-color: #059669;
        box-shadow: 0 8px 24px rgba(5, 150, 105, 0.12);
        transform: translateY(-2px);
    }
    .article-nav-direction {
        font-size: 0.78rem;
        font-weight: 700;
        color: #059669;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .article-nav-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .article-nav-date {
        font-size: 0.76rem;
        color: #64748b;
        margin-top: 6px;
    }

    /* Sidebar Widgets */
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

    /* Recent Post Items */
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
        transition: transform 0.3s ease;
    }
    .recent-post-item:hover .recent-post-thumb img {
        transform: scale(1.08);
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
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Archive Date Filter Links */
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
        margin-bottom: 4px;
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
    .archive-count-badge {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 50px;
        background: #f1f5f9;
        color: #64748b;
    }
    .archive-list-link.active .archive-count-badge {
        background: #059669;
        color: #ffffff;
    }

    @media (max-width: 767.98px) {
        .article-body-card {
            padding: 1.5rem;
        }
        .featured-calendar-badge {
            bottom: 12px;
            left: 12px;
        }
    }

    /* Dark theme overrides */
    html[data-theme='dark'] .blog-detail-main {
        background-color: var(--surface-alt-color) !important;
    }
    html[data-theme='dark'] .blog-detail-main :is(.article-body-card, .sidebar-widget, .article-nav-card, .blog-featured-media-wrapper, .featured-calendar-badge),
    html[data-theme='dark'] .blog-detail-hero .date-badge-pill {
        background: var(--surface-color);
        border-color: var(--border-color);
    }
    html[data-theme='dark'] .blog-detail-main :is(.sidebar-widget-title, .recent-post-item, .border-top),
    html[data-theme='dark'] .blog-detail-main .article-content h2 {
        border-color: var(--border-color) !important;
    }
    html[data-theme='dark'] .blog-detail-main :is(.recent-post-thumb, .article-tag-pill, .archive-count-badge) {
        background: #16332f;
    }
    html[data-theme='dark'] .blog-detail-main :is(.article-nav-date, .recent-post-date) {
        color: #cbd5e1 !important;
    }
    html[data-theme='dark'] .blog-detail-main .article-lead-callout {
        background: #16332f;
        border-left-color: #6ee7b7;
        color: #e5e7eb;
    }
    html[data-theme='dark'] .blog-detail-main .article-lead-callout i.quote-icon {
        color: #6ee7b7 !important;
        opacity: 1;
    }
    html[data-theme='dark'] .blog-detail-main .article-content blockquote {
        background: var(--surface-alt-color) !important;
        border-left-color: #6ee7b7;
        color: #cbd5e1;
    }
    html[data-theme='dark'] .blog-detail-main .article-content table {
        background: var(--surface-color) !important;
    }
    html[data-theme='dark'] .blog-detail-main .article-content table :is(th, td) {
        background: var(--surface-color) !important;
        border-color: var(--border-color) !important;
        color: #e5e7eb !important;
    }
    html[data-theme='dark'] .blog-detail-main .article-content table th {
        background: #1e293b !important;
    }
    html[data-theme='dark'] .blog-detail-main .article-content a,
    html[data-theme='dark'] .blog-detail-main .article-nav-direction:not(.text-muted),
    html[data-theme='dark'] .blog-detail-hero .date-badge-pill i {
        color: #6ee7b7 !important;
    }
    html[data-theme='dark'] .blog-detail-main :is(.article-tag-pill, .archive-list-link):is(:hover, :focus-visible) {
        background: #16332f;
        color: #6ee7b7 !important;
    }
    html[data-theme='dark'] .blog-detail-main .archive-list-link.active {
        background: rgba(16, 185, 129, 0.18);
    }
    html[data-theme='dark'] .blog-detail-main .archive-list-link.active .archive-count-badge {
        background: #065f46;
        color: #ffffff !important;
    }
    html[data-theme='dark'] .blog-detail-main a.article-nav-card:is(:hover, :focus-visible) {
        background: #16332f;
        border-color: #6ee7b7;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

    {{-- ARTICLE HERO HEADER --}}
    <section class="blog-detail-hero py-5">
        <div class="container py-3">
            {{-- Breadcrumbs --}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-3 small">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-white text-opacity-75 text-decoration-none">
                            <i class="bi bi-house-door me-1"></i> হোম
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('blogs.index') }}" class="text-white text-opacity-75 text-decoration-none">
                            ব্লগ ও তথ্য বাতায়ন
                        </a>
                    </li>
                    @if($blog->published_at)
                        <li class="breadcrumb-item">
                            <a href="{{ route('blogs.index', ['year' => $blog->published_at->year, 'month' => $blog->published_at->month]) }}" class="text-white text-opacity-75 text-decoration-none">
                                {{ $archiveMonthName }} {{ $toBn($blog->published_at->year) }}
                            </a>
                        </li>
                    @endif
                    <li class="breadcrumb-item active text-white" aria-current="page">
                        {{ \Illuminate\Support\Str::limit($blog->title, 35) }}
                    </li>
                </ol>
            </nav>

            <div class="row">
                <div class="col-lg-10" data-aos="fade-up">
                    {{-- Badges row --}}
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <span class="hero-badge-pill">
                            <i class="bi bi-journal-text text-warning"></i> প্রযুক্তি ও ডিজিটাল সেবা
                        </span>
                        @if($blog->published_at)
                            <a href="{{ route('blogs.index', ['year' => $blog->published_at->year, 'month' => $blog->published_at->month]) }}" class="hero-badge-pill" title="{{ $archiveMonthName }} {{ $toBn($blog->published_at->year) }} আর্কাইভ">
                                <i class="bi bi-archive text-warning"></i> {{ $archiveMonthName }} {{ $toBn($blog->published_at->year) }} আর্কাইভ
                            </a>
                        @endif
                    </div>

                    {{-- Title --}}
                    <h1 class="display-6 fw-bold text-white mb-4 lh-sm">
                        {{ $blog->title }}
                    </h1>

                    {{-- Prominent Meta Bar with Post Date --}}
                    <div class="d-flex flex-wrap align-items-center gap-3 pt-2">
                        @if ($blog->published_at)
                            <div class="date-badge-pill" title="প্রকাশনার তারিখ">
                                <i class="bi bi-calendar-check-fill"></i>
                                <span>প্রকাশিত: {{ $formatBnDate($blog->published_at) }}</span>
                            </div>
                        @else
                            <div class="date-badge-pill">
                                <i class="bi bi-calendar-event"></i>
                                <span>সম্প্রতি প্রকাশিত</span>
                            </div>
                        @endif

                        <span class="d-inline-flex align-items-center text-white text-opacity-90 small fw-semibold">
                            <i class="bi bi-clock me-1 text-warning"></i> {{ $toBn($readingMinutes) }} মিনিট পাঠ
                        </span>

                        <span class="d-inline-flex align-items-center text-white text-opacity-90 small fw-semibold">
                            <i class="bi bi-person-circle me-1 text-warning"></i> সৃজনশীল টিম
                        </span>

                        <a href="{{ route('blogs.index') }}" class="ms-auto btn btn-sm btn-outline-light rounded-pill px-3 d-none d-md-inline-flex align-items-center gap-2">
                            <i class="bi bi-arrow-left"></i> সকল আর্টিকেল
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN ARTICLE & SIDEBAR SECTION --}}
    <section class="blog-detail-main py-5" style="background-color: #f8fafc;">
        <div class="container">
            <div class="row g-4 g-lg-5">

                {{-- Left: Article Content --}}
                <div class="col-lg-8">

                    {{-- Featured Image with Date Badge Overlay --}}
                    @if ($blog->image)
                        <div class="blog-featured-media-wrapper" data-aos="fade-up">
                            <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" class="img-fluid">

                            @if($blog->published_at)
                                <div class="featured-calendar-badge d-none d-sm-flex">
                                    <div class="featured-cal-cube">
                                        <span class="fc-month">{{ $bnShortMonths[(int) $blog->published_at->format('n')] ?? $blog->published_at->format('M') }}</span>
                                        <span class="fc-day">{{ $toBn($blog->published_at->format('d')) }}</span>
                                    </div>
                                    <div class="featured-cal-info">
                                        <strong>প্রকাশের তারিখ: {{ $formatBnDate($blog->published_at) }}</strong>
                                        <small class="text-muted">{{ $toBn($readingMinutes) }} মিনিট পাঠ &bull; সৃজনশীল টিম</small>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Article Excerpt Callout --}}
                    @if ($blog->excerpt)
                        <div class="article-lead-callout" data-aos="fade-up">
                            <i class="bi bi-quote quote-icon"></i>
                            <div>{{ $blog->excerpt }}</div>
                        </div>
                    @endif

                    {{-- Article Body Card --}}
                    <article class="article-body-card" data-aos="fade-up">
                        <div class="article-content">
                            {!! $blog->body !!}
                        </div>

                        {{-- Keywords / Tags Bar --}}
                        @if ($blog->seo_keywords)
                            @php
                                $keywords = array_filter(array_map('trim', explode(',', $blog->seo_keywords)));
                            @endphp
                            @if(count($keywords) > 0)
                                <div class="mt-4 pt-4 border-top">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="small fw-bold text-muted me-1">
                                            <i class="bi bi-tags-fill me-1 text-success"></i> বিষয়সমূহ:
                                        </span>
                                        @foreach($keywords as $keyword)
                                            <a href="{{ route('blogs.index', ['search' => $keyword]) }}" class="article-tag-pill">
                                                #{{ $keyword }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif

                        {{-- Share & Meta Footer --}}
                        <div class="mt-4 pt-4 border-top d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="small fw-bold text-dark">শেয়ার করুন:</span>
                                @php
                                    $currentUrl = urlencode(request()->fullUrl());
                                    $encodedTitle = urlencode($blog->title);
                                @endphp
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $currentUrl }}" target="_blank" rel="noopener noreferrer" class="share-btn share-facebook" title="ফেসবুকে শেয়ার করুন">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                <a href="https://api.whatsapp.com/send?text={{ $encodedTitle }}%20{{ $currentUrl }}" target="_blank" rel="noopener noreferrer" class="share-btn share-whatsapp" title="হোয়াটসঅ্যাপে শেয়ার করুন">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?text={{ $encodedTitle }}&url={{ $currentUrl }}" target="_blank" rel="noopener noreferrer" class="share-btn share-twitter" title="এক্স (টুইটার)-এ শেয়ার করুন">
                                    <i class="bi bi-twitter-x"></i>
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $currentUrl }}" target="_blank" rel="noopener noreferrer" class="share-btn share-linkedin" title="লিঙ্কডইন-এ শেয়ার করুন">
                                    <i class="bi bi-linkedin"></i>
                                </a>
                                <button type="button" class="share-btn share-copy border-0" id="copyShareLinkBtn" title="লিংক কপি করুন" data-url="{{ request()->fullUrl() }}">
                                    <i class="bi bi-link-45deg"></i>
                                </button>
                                <span id="copyLinkSuccess" class="text-success small fw-bold d-none">
                                    <i class="bi bi-check-circle-fill"></i> লিংক কপি হয়েছে!
                                </span>
                            </div>

                            <a href="{{ route('blogs.index') }}" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-semibold">
                                <i class="bi bi-grid me-1"></i> সকল আর্টিকেল দেখুন
                            </a>
                        </div>
                    </article>

                    {{-- Next / Previous Article Navigation --}}
                    @if(isset($prevBlog) || isset($nextBlog))
                        <div class="row g-3 mt-4">
                            <div class="col-sm-6">
                                @if(isset($prevBlog))
                                    <a href="{{ route('blogs.show', $prevBlog->slug) }}" class="article-nav-card">
                                        <div class="article-nav-direction">
                                            <i class="bi bi-arrow-left"></i> পূর্ববর্তী আর্টিকেল
                                        </div>
                                        <div class="article-nav-title">{{ $prevBlog->title }}</div>
                                        <div class="article-nav-date">
                                            <i class="bi bi-calendar3 me-1 text-success"></i> {{ $formatBnDate($prevBlog->published_at) }}
                                        </div>
                                    </a>
                                @else
                                    <div class="article-nav-card opacity-50">
                                        <div class="article-nav-direction text-muted">
                                            <i class="bi bi-dash"></i> প্রথম আর্টিকেল
                                        </div>
                                        <div class="article-nav-title text-muted">পূর্বে আর কোনো আর্টিকেল নেই</div>
                                    </div>
                                @endif
                            </div>

                            <div class="col-sm-6">
                                @if(isset($nextBlog))
                                    <a href="{{ route('blogs.show', $nextBlog->slug) }}" class="article-nav-card text-sm-end">
                                        <div class="article-nav-direction justify-content-sm-end">
                                            পরবর্তী আর্টিকেল <i class="bi bi-arrow-right"></i>
                                        </div>
                                        <div class="article-nav-title">{{ $nextBlog->title }}</div>
                                        <div class="article-nav-date justify-content-sm-end">
                                            <i class="bi bi-calendar3 me-1 text-success"></i> {{ $formatBnDate($nextBlog->published_at) }}
                                        </div>
                                    </a>
                                @else
                                    <div class="article-nav-card opacity-50 text-sm-end">
                                        <div class="article-nav-direction text-muted justify-content-sm-end">
                                            সর্বশেষ আর্টিকেল <i class="bi bi-dash"></i>
                                        </div>
                                        <div class="article-nav-title text-muted">এটিই সর্বশেষ প্রকাশিত পোস্ট</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>

                {{-- Right: Sidebar with Filters, Archives & Recent Posts --}}
                <div class="col-lg-4">

                    {{-- Search Widget --}}
                    <div class="sidebar-widget">
                        <div class="sidebar-widget-title">
                            <i class="bi bi-search text-success"></i> আর্টিকেল অনুসন্ধান
                        </div>
                        <form action="{{ route('blogs.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control rounded-start-pill border-end-0 ps-3" placeholder="কী-ওয়ার্ড লিখুন..." aria-label="Search">
                                <button class="btn btn-success rounded-end-pill px-3" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Date Archives Widget --}}
                    @if(isset($archives) && $archives->count() > 0)
                        <div class="sidebar-widget">
                            <div class="sidebar-widget-title">
                                <i class="bi bi-calendar3 text-success"></i> মাসিক আর্কাইভ
                            </div>
                            <div class="d-flex flex-column">
                                @foreach($archives as $archive)
                                    @php
                                        $mName = $bnMonths[(int) $archive->month] ?? $archive->month;
                                        $isCurrentArticleMonth = $blog->published_at && ($blog->published_at->year == $archive->year && $blog->published_at->month == $archive->month);
                                    @endphp
                                    <a href="{{ route('blogs.index', ['year' => $archive->year, 'month' => $archive->month]) }}" class="archive-list-link {{ $isCurrentArticleMonth ? 'active' : '' }}">
                                        <span>
                                            <i class="bi bi-folder2-open me-2 text-success"></i> {{ $mName }} {{ $toBn($archive->year) }}
                                        </span>
                                        <span class="archive-count-badge">{{ $toBn($archive->count) }}</span>
                                    </a>
                                @endforeach
                            </div>
                            <div class="mt-3 pt-2 text-center">
                                <a href="{{ route('blogs.index') }}" class="small text-success text-decoration-none fw-semibold">
                                    সকল আর্কাইভ দেখুন &rarr;
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Recent Articles Widget --}}
                    @if(isset($recentBlogs) && $recentBlogs->count() > 0)
                        <div class="sidebar-widget">
                            <div class="sidebar-widget-title">
                                <i class="bi bi-clock-history text-success"></i> সাম্প্রতিক পোস্টসমূহ
                            </div>
                            <div class="d-flex flex-column">
                                @foreach($recentBlogs as $recent)
                                    <a href="{{ route('blogs.show', $recent->slug) }}" class="recent-post-item">
                                        <div class="recent-post-thumb">
                                            @if($recent->image)
                                                <img src="{{ asset('storage/' . $recent->image) }}" alt="{{ $recent->title }}">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center h-100 text-success bg-success-subtle">
                                                    <i class="bi bi-file-earmark-text fs-4 opacity-75"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <div class="recent-post-title">{{ $recent->title }}</div>
                                            <div class="recent-post-date">
                                                <i class="bi bi-calendar3 text-success"></i>
                                                {{ $formatBnDate($recent->published_at) }}
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Consultation / Service Order CTA (Tailored to Srijonshil Library & Computer) --}}
                    <div class="sidebar-widget p-4 text-center text-white" style="background: linear-gradient(135deg, #064e3b 0%, #047857 100%);">
                        <div class="mb-3">
                            <span class="d-inline-flex align-items-center justify-content-center bg-white text-success rounded-circle" style="width: 52px; height: 52px; font-size: 1.5rem; box-shadow: 0 4px 14px rgba(0,0,0,0.15);">
                                <i class="bi bi-laptop"></i>
                            </span>
                        </div>
                        <h4 class="fw-bold mb-2 text-white fs-5">অনলাইন সেবা বা আবেদন প্রয়োজন?</h4>
                        <p class="small text-white-50 mb-3" style="line-height: 1.6;">
                            চাকরির আবেদন, পাসপোর্ট, জাতীয় পরিচয়পত্র বা যেকোনো অনলাইন কাজ নির্ভুলভাবে সম্পন্ন করতে আমাদের অভিজ্ঞ টিমের সেবা নিন।
                        </p>
                        <a href="{{ route('orders.create') }}" class="btn btn-warning rounded-pill w-100 py-2 fw-bold text-dark shadow-sm mb-2">
                            <i class="bi bi-send-fill me-1"></i> সরাসরি আবেদন অর্ডার করুন
                        </a>
                        <a href="{{ route('contact.index') }}" class="btn btn-outline-light rounded-pill w-100 py-2 fw-semibold btn-sm">
                            <i class="bi bi-telephone-fill me-1"></i> আমাদের সাথে যোগাযোগ করুন
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const copyBtn = document.getElementById('copyShareLinkBtn');
        const copySuccess = document.getElementById('copyLinkSuccess');

        if (copyBtn) {
            copyBtn.addEventListener('click', function () {
                const url = this.getAttribute('data-url') || window.location.href;
                navigator.clipboard.writeText(url).then(function () {
                    if (copySuccess) {
                        copySuccess.classList.remove('d-none');
                        setTimeout(function () {
                            copySuccess.classList.add('d-none');
                        }, 2500);
                    }
                }).catch(function (err) {
                    console.error('Could not copy text: ', err);
                });
            });
        }
    });
</script>
@endpush
