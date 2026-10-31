@extends('frontend.layouts.app')

@section('title', $link->title . ' | ' . ($settings->site_name ?? 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার'))

@section('content')
<div class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">হোম</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('useful-links.index') }}">প্রয়োজনীয় লিংক</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $link->title }}</li>
                    </ol>
                </nav>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-success text-white p-4 border-0" style="background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;">
                        <span class="badge bg-white text-success rounded-pill px-3 py-1 mb-2">{{ $link->category }}</span>
                        <h2 class="fw-bold mb-1">{{ $link->title }}</h2>
                        <p class="mb-0 text-white-50">{{ $link->short_description }}</p>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="bi bi-file-text text-success me-1"></i> বিস্তারিত আবেদন পদ্ধতি ও নির্দেশিকা:
                        </h5>
                        <div class="text-secondary mb-4" style="line-height: 1.8; white-space: pre-line;">
                            {{ $link->long_description ?? $link->short_description }}
                        </div>

                        @if($link->required_documents)
                            <div class="p-4 bg-light rounded-4 border mb-4">
                                <h5 class="fw-bold text-dark mb-3">
                                    <i class="bi bi-card-checklist text-primary me-2"></i> আবেদনের জন্য যা যা প্রয়োজন (কাগজপত্র ও ডকুমেন্টস):
                                </h5>
                                <div class="text-secondary" style="white-space: pre-line; line-height: 1.7;">
                                    {{ $link->required_documents }}
                                </div>
                            </div>
                        @endif

                        <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between p-4 bg-light rounded-4 border">
                            <div>
                                <small class="text-muted d-block">অফিসিয়াল পোর্টাল লিংক:</small>
                                <span class="text-primary font-monospace fw-bold">{{ $link->external_url }}</span>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ $link->external_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary rounded-pill px-4 py-2">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> পোর্টাল ভিজিট করুন
                                </a>

                                @if($link->service_id)
                                    <a href="{{ route('orders.create', ['serviceSlug' => $link->service?->slug ?? null]) }}" class="btn btn-success rounded-pill px-4 py-2 fw-semibold">
                                        <i class="bi bi-send me-1"></i> আমাদের মাধ্যমে আবেদন করুন
                                    </a>
                                @else
                                    <a href="{{ route('orders.create') }}" class="btn btn-success rounded-pill px-4 py-2 fw-semibold">
                                        <i class="bi bi-send me-1"></i> আমাদের মাধ্যমে আবেদন করুন
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if($relatedLinks->count() > 0)
                    <div class="mt-4">
                        <h4 class="fw-bold text-dark mb-3">সম্পর্কিত অন্যান্য লিংক</h4>
                        <div class="row g-3">
                            @foreach($relatedLinks as $rel)
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                                        <h6 class="fw-bold mb-1">
                                            <a href="{{ route('useful-links.show', $rel->slug) }}" class="text-decoration-none text-dark">
                                                {{ $rel->title }}
                                            </a>
                                        </h6>
                                        <p class="text-muted small mb-2">{{ Str::limit($rel->short_description, 80) }}</p>
                                        <div class="mt-auto">
                                            <a href="{{ route('useful-links.show', $rel->slug) }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                                বিস্তারিত দেখুন
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

