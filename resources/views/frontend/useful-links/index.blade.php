@extends('frontend.layouts.app')

@section('title', 'প্রয়োজনীয় লিংক ও আবেদন পোর্টাল সমূহ | ' . ($settings->site_name ?? 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার'))

@push('styles')
<style>
    .link-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        background: #ffffff;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .link-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -8px rgba(5, 150, 105, 0.15);
        border-color: #059669;
    }
    .link-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #ecfdf5;
        color: #059669;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .category-pill {
        border-radius: 30px;
        padding: 8px 18px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .category-pill.active {
        background-color: #059669 !important;
        color: #ffffff !important;
    }
    html[data-theme='dark'] .link-card {
        background: var(--surface-color);
        border-color: var(--border-color);
    }
    html[data-theme='dark'] .link-card:is(:hover, :focus-within) {
        border-color: #6ee7b7;
    }
    html[data-theme='dark'] .link-icon-box {
        background: #16332f;
    }
    html[data-theme='dark'] .link-icon-box i,
    html[data-theme='dark'] .badge.bg-success-subtle {
        color: #6ee7b7 !important;
    }
    html[data-theme='dark'] .category-pill:is(:hover, :focus-visible) {
        outline: 2px solid #6ee7b7;
        outline-offset: 2px;
    }
</style>
@endpush

@section('content')
<div class="py-5 bg-light">
    <div class="container">
        {{-- Page Header --}}
        <div class="row justify-content-center text-center mb-4">
            <div class="col-lg-8">
                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-semibold mb-2">
                    <i class="bi bi-link-45deg me-1"></i> ওয়ান-স্টপ লিংক ডিরেক্টরি
                </span>
                <h1 class="fw-bold text-dark mb-2">প্রয়োজনীয় লিংক ও আবেদন পোর্টাল সমূহ</h1>
                <p class="text-muted">চাকরির আবেদন, জন্মনিবন্ধন, পাসপোর্ট, এনআইডি ও ভর্তি সংক্রান্ত সকল প্রয়োজনীয় ওয়েবসাইটের তালিকা ও আবেদন নির্দেশিকা।</p>

                {{-- Search Box --}}
                <div class="card border-0 shadow-sm rounded-pill p-2 mt-3 bg-white">
                    <form action="{{ route('useful-links.index') }}" method="GET" class="d-flex align-items-center">
                        @if($currentCategory !== 'সকল')
                            <input type="hidden" name="category" value="{{ $currentCategory }}">
                        @endif
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-0 ps-3">
                                <i class="bi bi-search text-success fs-5"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-0 shadow-none ps-2"
                                placeholder="লিংক বা সেবার নাম দিয়ে খুঁজুন (যেমনঃ জন্মনিবন্ধন, পাসপোর্ট, টেলিটক)..." value="{{ $search }}">
                            @if($search)
                                <a href="{{ route('useful-links.index', ['category' => $currentCategory]) }}" class="btn btn-link text-muted me-2 text-decoration-none">ক্লিয়ার</a>
                            @endif
                            <button type="submit" class="btn btn-success px-4 rounded-pill fw-semibold">
                                অনুসন্ধান
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Category Pills Filter --}}
        <div class="d-flex flex-wrap gap-2 justify-content-center mb-5">
            @foreach($categories as $cat)
                <a href="{{ route('useful-links.index', ['category' => $cat, 'search' => $search]) }}"
                    class="category-pill {{ $currentCategory === $cat ? 'active' : 'bg-white text-secondary border' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>

        {{-- Cards Grid --}}
        <div class="row g-4">
            @forelse($links as $link)
                <div class="col-md-6 col-lg-4">
                    <div class="link-card shadow-sm p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="link-icon-box">
                                <i class="bi {{ $link->icon ?? 'bi-link-45deg' }}"></i>
                            </div>
                            <span class="badge bg-light text-success border border-success-subtle rounded-pill px-3 py-1">
                                {{ $link->category }}
                            </span>
                        </div>

                        {{-- Title --}}
                        <h5 class="fw-bold text-dark mb-2" style="min-height: 48px; line-height: 1.3;">
                            {{ $link->title }}
                        </h5>

                        {{-- Short Details (Shown directly on the card face as requested) --}}
                        <p class="text-muted small mb-4 flex-grow-1" style="min-height: 60px;">
                            {{ $link->short_description ?? Str::limit(strip_tags($link->long_description), 110) }}
                        </p>

                        {{-- Action Buttons --}}
                        <div class="mt-auto pt-3 border-top d-flex flex-column gap-2">
                            {{-- View Long Details in Modal --}}
                            <button type="button" class="btn btn-outline-success w-100 rounded-pill py-2 fw-semibold"
                                data-bs-toggle="modal" data-bs-target="#linkModal{{ $link->id }}">
                                <i class="bi bi-info-circle me-1"></i> বিস্তারিত ও নির্দেশিকা দেখুন
                            </button>

                            <div class="d-flex gap-2">
                                {{-- Direct Official Link --}}
                                <a href="{{ $link->external_url }}" target="_blank" rel="noopener noreferrer"
                                    class="btn btn-light border text-dark w-100 rounded-pill py-2 small fw-semibold" title="অফিসিয়াল সাইটে যান">
                                    <i class="bi bi-box-arrow-up-right me-1 text-primary"></i> মূল লিংক
                                </a>

                                {{-- Order via us --}}
                                @if($link->service_id)
                                    <a href="{{ route('orders.create', ['serviceSlug' => $link->service?->slug ?? null]) }}"
                                        class="btn btn-success w-100 rounded-pill py-2 small fw-semibold" title="আমাদের মাধ্যমে আবেদন করুন">
                                        <i class="bi bi-send me-1"></i> আবেদন করান
                                    </a>
                                @else
                                    <a href="{{ route('orders.create') }}"
                                        class="btn btn-success w-100 rounded-pill py-2 small fw-semibold">
                                        <i class="bi bi-send me-1"></i> আবেদন করান
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Interactive Modal for Long Details --}}
                <div class="modal fade" id="linkModal{{ $link->id }}" tabindex="-1" aria-labelledby="linkModalLabel{{ $link->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content rounded-4 border-0 shadow">
                            <div class="modal-header bg-success text-white py-3 rounded-top-4 border-0" style="background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi {{ $link->icon ?? 'bi-link-45deg' }} fs-4"></i>
                                    <div>
                                        <h5 class="modal-title fw-bold text-white mb-0" id="linkModalLabel{{ $link->id }}">{{ $link->title }}</h5>
                                        <span class="badge bg-white text-success rounded-pill mt-1">{{ $link->category }}</span>
                                    </div>
                                </div>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body p-4 p-md-5">
                                {{-- Long Details Section --}}
                                <h6 class="fw-bold text-dark mb-2">
                                    <i class="bi bi-file-text text-success me-1"></i> আবেদনের বিস্তারিত ও নির্দেশনা:
                                </h6>
                                <div class="text-secondary mb-4" style="line-height: 1.7; white-space: pre-line;">
                                    {{ $link->long_description ?? $link->short_description }}
                                </div>

                                {{-- Required Documents Checklist --}}
                                @if($link->required_documents)
                                    <div class="p-3 bg-light rounded-3 border mb-4">
                                        <h6 class="fw-bold text-dark mb-2">
                                            <i class="bi bi-card-checklist text-primary me-1"></i> আবেদনের জন্য যা যা প্রয়োজন (কাগজপত্র):
                                        </h6>
                                        <div class="small text-muted" style="white-space: pre-line; line-height: 1.6;">
                                            {{ $link->required_documents }}
                                        </div>
                                    </div>
                                @endif

                                {{-- External URL preview --}}
                                <div class="p-3 bg-body-tertiary rounded-3 mb-4 d-flex align-items-center justify-content-between">
                                    <div class="text-truncate me-2">
                                        <small class="text-muted d-block">অফিসিয়াল ওয়েব পোর্টাল:</small>
                                        <span class="text-primary font-monospace fw-semibold">{{ $link->external_url }}</span>
                                    </div>
                                    <a href="{{ $link->external_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary rounded-pill px-3 flex-shrink-0">
                                        ভিজিট করুন <i class="bi bi-box-arrow-up-right ms-1"></i>
                                    </a>
                                </div>

                                {{-- Direct Action Card for Shop Order --}}
                                <div class="card border-0 bg-success-subtle rounded-4 p-3 d-flex flex-row align-items-center justify-content-between">
                                    <div>
                                        <strong class="text-success d-block">নিজে আবেদন করতে ঝামেলা মনে হচ্ছে?</strong>
                                        <small class="text-muted">আমাদের অভিজ্ঞ টিম দিয়ে মাত্র কয়েক মিনিটে নির্ভুলভাবে আবেদন সম্পন্ন করান।</small>
                                    </div>
                                    @if($link->service_id)
                                        <a href="{{ route('orders.create', ['serviceSlug' => $link->service?->slug ?? null]) }}" class="btn btn-success rounded-pill px-3 py-2 fw-semibold flex-shrink-0">
                                            আমাদের দিয়ে আবেদন করান
                                        </a>
                                    @else
                                        <a href="{{ route('orders.create') }}" class="btn btn-success rounded-pill px-3 py-2 fw-semibold flex-shrink-0">
                                            অর্ডার করুন
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="modal-footer bg-light py-3 border-top rounded-bottom-4 justify-content-between">
                                <span class="small text-muted">হেল্পলাইন: ০১৭১১-৬৬৫০৯৮</span>
                                <div class="d-flex gap-2">
                                    <a href="{{ $link->external_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary rounded-pill px-3">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> ওয়েবসাইট খুলুন
                                    </a>
                                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">বন্ধ করুন</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 text-center p-5">
                        <i class="bi bi-link-45deg text-muted fs-1 mb-3"></i>
                        <h4 class="fw-bold text-dark">কোনো লিংক পাওয়া যায়নি</h4>
                        <p class="text-muted">নির্বাচিত ক্যাটাগরি বা শব্দ দিয়ে কোনো লিংক খুঁজে পাওয়া যায়নি।</p>
                        <div>
                            <a href="{{ route('useful-links.index') }}" class="btn btn-outline-success rounded-pill px-4">
                                সকল লিংক দেখুন
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($links->hasPages())
            <div class="mt-5 d-flex justify-content-center">
                {{ $links->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

