@extends('admin.layouts.app')

@section('title', 'গ্রাহক তথ্য ও মোবাইল নম্বর অনুসন্ধান')

@section('content')
<div class="container-fluid px-0">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h3 class="fw-bold mb-1 text-dark">
                <i class="bi bi-person-lines-fill text-success me-2"></i> গ্রাহক ডিরেক্টরি ও তথ্য বাতায়ন
            </h3>
            <p class="text-muted mb-0">মোবাইল নম্বর বা নাম দিয়ে গ্রাহকের সকল জমা দেওয়া তথ্য, ছবি ও আবেদনের ইতিহাস খুঁজুন</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-receipt me-1"></i> সকল অর্ডারের তালিকা
            </a>
            <a href="{{ route('orders.create') }}" target="_blank" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i> নতুন অর্ডার ফরম
            </a>
        </div>
    </div>

    {{-- Stats Cards Row --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-success">
                <span class="text-muted small fw-semibold d-block mb-1">মোট অনন্য গ্রাহক (Unique Customers)</span>
                <h3 class="fw-bold text-success mb-0">{{ $stats['total_customers'] }} জন</h3>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-primary">
                <span class="text-muted small fw-semibold d-block mb-1">মোট অনলাইন আবেদন (Total Orders)</span>
                <h3 class="fw-bold text-primary mb-0">{{ $stats['total_orders'] }} টি</h3>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-4 border-warning">
                <span class="text-muted small fw-semibold d-block mb-1">মোট সেবামূল্য (Total Revenue)</span>
                <h3 class="fw-bold text-dark mb-0">৳{{ number_format($stats['total_revenue'], 2) }}</h3>
            </div>
        </div>
    </div>

    {{-- Search Filter Card --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.customers.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-lg-9 col-md-8">
                    <label class="form-label fw-bold text-dark small">
                        <i class="bi bi-search text-success me-1"></i> মোবাইল নম্বর বা নাম দিয়ে খুঁজুন:
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-success">
                            <i class="bi bi-telephone-fill"></i>
                        </span>
                        <input type="text" name="search" class="form-control form-control-lg border-start-0 ps-0"
                               placeholder="গ্রাহকের মোবাইল নম্বর (যেমনঃ 01711665098) বা নাম লিখুন..."
                               value="{{ $search }}" autofocus>
                        @if($search !== '')
                            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary d-flex align-items-center">
                                <i class="bi bi-x-circle me-1"></i> ক্লিয়ার
                            </a>
                        @endif
                        <button type="submit" class="btn btn-success px-4 fw-bold">
                            <i class="bi bi-search me-1"></i> অনুসন্ধান করুন
                        </button>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 text-md-end pt-md-4">
                    <div class="text-muted small">
                        খুঁজে পাওয়া গেছে: <strong class="text-success">{{ $paginatedPhones->total() }}</strong> জন গ্রাহক
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Customer Table List Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0">
                <i class="bi bi-table text-success me-2"></i> গ্রাহক তালিকা (Customers Directory)
            </h5>
            @if($search !== '')
                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                    অনুসন্ধান ফলাফল: "{{ $search }}"
                </span>
            @endif
        </div>

        <div class="card-body p-0">
            @if($customers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light border-bottom text-muted small text-uppercase">
                            <tr>
                                <th style="width: 75px;" class="text-center py-3">ছবি (Photo)</th>
                                <th class="py-3">গ্রাহকের নাম (Name)</th>
                                <th class="py-3">মোবাইল নম্বর (Mobile)</th>
                                <th class="py-3">পিতা ও মাতার নাম</th>
                                <th class="py-3">এনআইডি / জন্মসনদ</th>
                                <th class="py-3">সর্বশেষ সেবা</th>
                                <th class="py-3 text-center">মোট আবেদন</th>
                                <th class="py-3 text-end pe-4">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                                @php
                                    $modalId = 'custModal_' . md5($customer->phone);
                                @endphp
                                <tr>
                                    {{-- 1. Photo Thumbnail --}}
                                    <td class="text-center py-3">
                                        @if($customer->photo_url)
                                            <a href="{{ $customer->photo_url }}" target="_blank" title="ছবি বড় করে দেখুন">
                                                <img src="{{ $customer->photo_url }}" alt="{{ $customer->name_bn }}"
                                                     class="rounded-3 shadow-sm border"
                                                     style="width: 52px; height: 52px; object-fit: cover;">
                                            </a>
                                        @else
                                            <div class="rounded-3 bg-success-subtle text-success d-inline-flex align-items-center justify-content-center fw-bold shadow-sm"
                                                 style="width: 52px; height: 52px; font-size: 1.25rem;">
                                                {{ mb_substr($customer->name_bn, 0, 1) }}
                                            </div>
                                        @endif
                                    </td>

                                    {{-- 2. Name (Bangla & English) --}}
                                    <td class="py-3">
                                        <strong class="text-dark fs-6 d-block mb-0">{{ $customer->name_bn }}</strong>
                                        @if($customer->name_en)
                                            <small class="text-muted d-block">{{ $customer->name_en }}</small>
                                        @endif
                                        @if($customer->address)
                                            <small class="text-secondary d-block mt-0.5" style="font-size: 0.78rem;">
                                                <i class="bi bi-geo-alt text-danger me-1"></i>{{ Str::limit($customer->address, 30) }}
                                            </small>
                                        @endif
                                    </td>

                                    {{-- 3. Mobile Number --}}
                                    <td class="py-3">
                                        <strong class="text-dark font-monospace fs-6 d-block">{{ $customer->phone }}</strong>
                                        <div class="d-flex align-items-center gap-1 mt-1">
                                            <a href="tel:{{ $customer->phone }}" class="badge bg-light text-primary border text-decoration-none" title="কল করুন">
                                                <i class="bi bi-telephone-fill me-1"></i> কল
                                            </a>
                                            @php
                                                $cleanPh = preg_replace('/[^0-9]/', '', $customer->phone);
                                                if (str_starts_with($cleanPh, '01')) {
                                                    $cleanPh = '88' . $cleanPh;
                                                }
                                            @endphp
                                            <a href="https://wa.me/{{ $cleanPh }}" target="_blank" class="badge bg-light text-success border text-decoration-none" title="হোয়াটসঅ্যাপ চ্যাট">
                                                <i class="bi bi-whatsapp me-1"></i> চ্যাট
                                            </a>
                                        </div>
                                    </td>

                                    {{-- 4. Father & Mother Name --}}
                                    <td class="py-3">
                                        <div class="small">
                                            <span class="text-muted">পিতা:</span>
                                            <strong class="text-dark">{{ $customer->father_name_bn ?: ($customer->father_name_en ?: 'N/A') }}</strong>
                                        </div>
                                        <div class="small mt-0.5">
                                            <span class="text-muted">মাতা:</span>
                                            <strong class="text-dark">{{ $customer->mother_name_bn ?: ($customer->mother_name_en ?: 'N/A') }}</strong>
                                        </div>
                                    </td>

                                    {{-- 5. NID / Birth Reg No --}}
                                    <td class="py-3">
                                        @if($customer->nid_birth_reg_no)
                                            <span class="font-monospace fw-semibold text-dark small d-block">{{ $customer->nid_birth_reg_no }}</span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                        @if($customer->dob)
                                            <small class="text-muted d-block mt-0.5" style="font-size: 0.75rem;">
                                                <i class="bi bi-calendar-event me-1"></i>{{ $customer->dob }}
                                            </small>
                                        @endif
                                    </td>

                                    {{-- 6. Latest Service --}}
                                    <td class="py-3">
                                        <span class="text-dark fw-semibold small d-block">
                                            {{ Str::limit($customer->latest_order?->service_title ?? 'N/A', 28) }}
                                        </span>
                                        <small class="text-muted" style="font-size: 0.75rem;">
                                            {{ $customer->latest_date ? $customer->latest_date->format('d M, Y') : '' }}
                                        </small>
                                    </td>

                                    {{-- 7. Total Orders --}}
                                    <td class="text-center py-3">
                                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fw-bold">
                                            {{ $customer->orders_count }} টি আবেদন
                                        </span>
                                    </td>

                                    {{-- 8. Actions --}}
                                    <td class="text-end pe-4 py-3">
                                        <div class="d-flex justify-content-end align-items-center gap-1">
                                            {{-- Open Full Details Modal --}}
                                            <button type="button" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}" title="সকল তথ্য দেখুন">
                                                <i class="bi bi-eye-fill me-1"></i> পূর্ণাঙ্গ তথ্য
                                            </button>

                                            {{-- View Dedicated Profile Page --}}
                                            <a href="{{ route('admin.customers.show', $customer->phone) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-2" title="গ্রাহকের সম্পূর্ণ প্রোফাইল">
                                                <i class="bi bi-arrow-right-circle"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="p-4 border-top d-flex justify-content-center">
                    {{ $paginatedPhones->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-person-x text-muted" style="font-size: 3.5rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark">কোনো গ্রাহক খুঁজে পাওয়া যায়নি</h5>
                    @if($search !== '')
                        <p class="text-muted mb-3">"{{ $search }}" এর সাথে মিল পাওয়া কোনো তথ্য নেই।</p>
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-success rounded-pill px-4">
                            <i class="bi bi-arrow-repeat me-1"></i> সকল গ্রাহক দেখুন
                        </a>
                    @else
                        <p class="text-muted mb-0">এখনো কোনো গ্রাহক সেবা নেওয়ার জন্য আবেদন করেননি।</p>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Customer Full Dossier Modals (Placed OUTSIDE the table to keep HTML strictly valid) --}}
    @if($customers->count() > 0)
        @foreach($customers as $customer)
            @php
                $modalId = 'custModal_' . md5($customer->phone);
            @endphp
            <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content rounded-4 border-0 shadow">
                        <div class="modal-header bg-success text-white py-3 border-0">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-person-badge fs-4"></i>
                                <div>
                                    <h5 class="modal-title fw-bold mb-0 text-white" id="{{ $modalId }}Label">
                                        গ্রাহকের পূর্ণাঙ্গ তথ্য ও প্রোফাইল
                                    </h5>
                                    <small class="text-white-50">মোবাইল নম্বর: {{ $customer->phone }}</small>
                                </div>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-4">
                            {{-- Top Profile Card --}}
                            <div class="card border-0 bg-light rounded-4 p-3 mb-4">
                                <div class="d-flex flex-wrap align-items-center gap-3">
                                    <div class="flex-shrink-0 text-center">
                                        @if($customer->photo_url)
                                            <a href="{{ $customer->photo_url }}" target="_blank">
                                                <img src="{{ $customer->photo_url }}" alt="{{ $customer->name_bn }}"
                                                     class="rounded-4 shadow-sm border bg-white"
                                                     style="width: 90px; height: 90px; object-fit: cover;">
                                            </a>
                                        @else
                                            <div class="rounded-4 bg-success text-white d-inline-flex align-items-center justify-content-center fw-bold shadow-sm"
                                                 style="width: 90px; height: 90px; font-size: 2rem;">
                                                {{ mb_substr($customer->name_bn, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                                            <div>
                                                <h4 class="fw-bold text-dark mb-1">{{ $customer->name_bn }}</h4>
                                                @if($customer->name_en)
                                                    <h6 class="text-muted mb-2">{{ $customer->name_en }}</h6>
                                                @endif
                                            </div>
                                            <span class="badge bg-success rounded-pill px-3 py-2">
                                                মোট {{ $customer->orders_count }}টি অর্ডার সম্পন্ন
                                            </span>
                                        </div>

                                        <div class="d-flex flex-wrap align-items-center gap-3 text-secondary small">
                                            <div>
                                                <i class="bi bi-telephone-fill text-success me-1"></i>
                                                <strong class="text-dark font-monospace">{{ $customer->phone }}</strong>
                                            </div>
                                            @if($customer->email)
                                                <div>
                                                    <i class="bi bi-envelope-fill text-primary me-1"></i>
                                                    <span>{{ $customer->email }}</span>
                                                </div>
                                            @endif
                                            @if($customer->address)
                                                <div>
                                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                                    <span>{{ $customer->address }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Family & Personal Details Table --}}
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                <i class="bi bi-info-circle text-success me-2"></i> পারিবারিক ও ব্যক্তিগত তথ্য
                            </h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="p-3 bg-white border rounded-3">
                                        <small class="text-muted d-block">পিতার নাম (বাংলা):</small>
                                        <strong class="text-dark">{{ $customer->father_name_bn ?? 'প্রদান করা হয়নি' }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-white border rounded-3">
                                        <small class="text-muted d-block">পিতার নাম (ইংরেজি):</small>
                                        <strong class="text-dark">{{ $customer->father_name_en ?? 'প্রদান করা হয়নি' }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-white border rounded-3">
                                        <small class="text-muted d-block">মাতার নাম (বাংলা):</small>
                                        <strong class="text-dark">{{ $customer->mother_name_bn ?? 'প্রদান করা হয়নি' }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-white border rounded-3">
                                        <small class="text-muted d-block">মাতার নাম (ইংরেজি):</small>
                                        <strong class="text-dark">{{ $customer->mother_name_en ?? 'প্রদান করা হয়নি' }}</strong>
                                    </div>
                                </div>
                                @if($customer->spouse_name_bn || $customer->spouse_name_en)
                                    <div class="col-md-6">
                                        <div class="p-3 bg-white border rounded-3">
                                            <small class="text-muted d-block">স্বামী / স্ত্রীর নাম (বাংলা):</small>
                                            <strong class="text-dark">{{ $customer->spouse_name_bn ?? '-' }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 bg-white border rounded-3">
                                            <small class="text-muted d-block">স্বামী / স্ত্রীর নাম (ইংরেজি):</small>
                                            <strong class="text-dark">{{ $customer->spouse_name_en ?? '-' }}</strong>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-6">
                                    <div class="p-3 bg-white border rounded-3">
                                        <small class="text-muted d-block">জাতীয় পরিচয়পত্র / জন্মনিবন্ধন নম্বর:</small>
                                        <strong class="text-dark font-monospace">{{ $customer->nid_birth_reg_no ?? 'প্রদান করা হয়নি' }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-white border rounded-3">
                                        <small class="text-muted d-block">জন্ম তারিখ (DOB):</small>
                                        <strong class="text-dark">{{ $customer->dob ?? 'প্রদান করা হয়নি' }}</strong>
                                    </div>
                                </div>
                            </div>

                            {{-- Customer's Uploaded Attachments / Documents --}}
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                <i class="bi bi-paperclip text-info me-2"></i> গ্রাহকের আপলোডকৃত ফাইলসমূহ ({{ $customer->attachments->count() }}টি)
                            </h6>
                            @if($customer->attachments->count() > 0)
                                <div class="row g-2 mb-4">
                                    @foreach($customer->attachments as $att)
                                        <div class="col-md-6">
                                            <div class="p-2 border rounded-3 bg-white d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-2 overflow-hidden me-2">
                                                    @if($att->file_type === 'photo')
                                                        <i class="bi bi-image text-success fs-4"></i>
                                                    @elseif($att->file_type === 'pdf')
                                                        <i class="bi bi-file-earmark-pdf text-danger fs-4"></i>
                                                    @else
                                                        <i class="bi bi-file-earmark-text text-primary fs-4"></i>
                                                    @endif
                                                    <div class="text-truncate">
                                                        <strong class="d-block text-truncate small text-dark" title="{{ $att->file_name }}">
                                                            {{ $att->file_name }}
                                                        </strong>
                                                        <small class="text-muted">{{ $att->formatted_size }}</small>
                                                    </div>
                                                </div>
                                                <a href="{{ asset('storage/' . $att->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 flex-shrink-0" download>
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted small mb-4">কোনো সংযুক্ত ফাইল পাওয়া যায়নি।</p>
                            @endif

                            {{-- Customer's Orders History (Clean Modern Design - NO tracking id) --}}
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                <i class="bi bi-clock-history text-primary me-2"></i> আবেদনের ইতিহাস (Order History)
                            </h6>
                            <div class="card border border-light-subtle rounded-3 overflow-hidden shadow-none mb-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr class="text-muted small text-uppercase" style="font-size: 0.78rem;">
                                                <th class="ps-3 py-3">সেবার নাম</th>
                                                <th class="py-3">আবেদনের তারিখ</th>
                                                <th class="py-3">নির্ধারিত ফি</th>
                                                <th class="py-3 text-center">পেমেন্ট অবস্থা</th>
                                                <th class="py-3 text-center">কাজের স্ট্যাটাস</th>
                                                <th class="text-end pe-3 py-3">অ্যাকশন</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($customer->orders as $ord)
                                                <tr>
                                                    <td class="ps-3 py-2.5">
                                                        <strong class="text-dark d-block" style="font-size: 0.9rem;">{{ $ord->service_title }}</strong>
                                                        <span class="text-muted" style="font-size: 0.75rem;">
                                                            <i class="bi bi-clock me-1"></i>{{ $ord->created_at->format('h:i A') }}
                                                        </span>
                                                    </td>
                                                    <td class="py-2.5 text-secondary" style="font-size: 0.85rem;">
                                                        {{ $ord->created_at->format('d M, Y') }}
                                                    </td>
                                                    <td class="py-2.5">
                                                        <span class="fw-bold text-success" style="font-size: 0.95rem;">৳{{ number_format($ord->total_fee, 2) }}</span>
                                                    </td>
                                                    <td class="py-2.5 text-center">
                                                        @if($ord->payment_status === 'verified' || $ord->payment_status === 'paid')
                                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fw-semibold" style="font-size: 0.75rem;">
                                                                <i class="bi bi-check-circle-fill me-1"></i>পরিশোধিত
                                                            </span>
                                                        @elseif($ord->payment_status === 'rejected' || $ord->payment_status === 'failed')
                                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1 fw-semibold" style="font-size: 0.75rem;">
                                                                <i class="bi bi-x-circle-fill me-1"></i>ব্যর্থ
                                                            </span>
                                                        @else
                                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2.5 py-1 fw-semibold" style="font-size: 0.75rem;">
                                                                <i class="bi bi-hourglass-split me-1"></i>অপেক্ষমাণ
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="py-2.5 text-center">
                                                        @if($ord->status === 'completed')
                                                            <span class="badge bg-success text-white rounded-pill px-2.5 py-1 fw-semibold" style="font-size: 0.75rem;">সম্পন্ন</span>
                                                        @elseif($ord->status === 'processing')
                                                            <span class="badge bg-primary text-white rounded-pill px-2.5 py-1 fw-semibold" style="font-size: 0.75rem;">প্রক্রিয়াধীন</span>
                                                        @elseif($ord->status === 'cancelled')
                                                            <span class="badge bg-danger text-white rounded-pill px-2.5 py-1 fw-semibold" style="font-size: 0.75rem;">বাতিল</span>
                                                        @else
                                                            <span class="badge bg-secondary text-white rounded-pill px-2.5 py-1 fw-semibold" style="font-size: 0.75rem;">নতুন জমা</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end pe-3 py-2.5">
                                                        <a href="{{ route('admin.orders.show', $ord->id) }}" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1" style="font-size: 0.8rem;">
                                                            <i class="bi bi-eye-fill me-1"></i> দেখুন
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer bg-light py-2 border-0">
                            <a href="{{ route('admin.customers.show', $customer->phone) }}" class="btn btn-primary rounded-pill px-4">
                                <i class="bi bi-person-vcard me-1"></i> পূর্ণাঙ্গ প্রোফাইল পেজ
                            </a>
                            <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                                বন্ধ করুন
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
