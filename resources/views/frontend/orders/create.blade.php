@extends('frontend.layouts.app')

@section('title', 'অনলাইন সেবা অর্ডার ও ডাটা এন্ট্রি | ' . ($settings->site_name ?? 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার'))

@push('styles')
<style>
    .order-step-badge {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #059669;
        color: #fff;
        font-weight: 700;
        margin-right: 10px;
        flex-shrink: 0;
    }
    .payment-option-card {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 18px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .payment-option-card:hover {
        border-color: #059669;
        background: #f0fdf4;
    }
    .payment-option-card input[type="radio"]:checked + label {
        color: #059669;
        font-weight: 700;
    }
    .payment-option-card.active {
        border-color: #059669;
        background: #ecfdf5;
    }
    .service-summary-box {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: white;
        border-radius: 16px;
    }
    .qr-preview-card {
        background: #ffffff;
        border: 2px solid #fecdd3;
        border-radius: 14px;
        transition: all 0.2s ease;
    }
    .qr-preview-card:hover {
        border-color: #f43f5e;
        box-shadow: 0 8px 24px rgba(244, 63, 94, 0.12);
    }
    .copy-btn {
        transition: all 0.15s ease;
    }
    .upload-dropzone {
        transition: all 0.25s ease;
    }
    .upload-dropzone:hover, .upload-dropzone.dragover {
        border-color: #059669 !important;
        background-color: #ecfdf5 !important;
        transform: translateY(-2px);
    }
    .doc-item {
        transition: all 0.2s ease;
    }
    .doc-item:hover {
        background-color: #f8fafc !important;
        border-color: #cbd5e1 !important;
    }
    .remove-doc-btn {
        transition: all 0.2s ease;
    }
    .remove-doc-btn:hover {
        background-color: #dc2626 !important;
        color: #ffffff !important;
        transform: scale(1.1);
    }
</style>
@endpush

@section('content')
<div class="py-5 bg-light">
    <div class="container">
        {{-- Breadcrumb & Title --}}
        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">হোম</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('services.index') }}" class="text-decoration-none">সেবাসমূহ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">অনলাইনে আবেদন অর্ডার</li>
                    </ol>
                </nav>
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        <h2 class="fw-bold mb-1 text-dark">অনলাইন আবেদন ও ডাটা এন্ট্রি অর্ডার ফরম</h2>
                        <p class="text-muted mb-0">আপনার প্রয়োজনীয় তথ্য ও ফাইল সাবমিট করুন, আমরা শতভাগ নির্ভুলভাবে আপনার আবেদন সম্পন্ন করব।</p>
                    </div>
                    <a href="{{ route('orders.track') }}" class="btn btn-outline-success rounded-pill px-3 py-2">
                        <i class="bi bi-search me-1"></i> অর্ডার ট্র্যাকিং করুন
                    </a>
                </div>
            </div>
        </div>

        <form action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data" id="serviceOrderForm">
            @csrf

            <div class="row justify-content-center g-4">
                {{-- Left Form Column --}}
                <div class="col-lg-7">
                    {{-- Step 1: Service Selection --}}
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <span class="order-step-badge">১</span>
                                <h5 class="fw-bold mb-0 text-dark">সেবা নির্বাচন করুন</h5>
                            </div>

                            <div class="mb-3">
                                <label for="service_id" class="form-label fw-semibold">কোন কাজের জন্য আবেদন করতে চান? <span class="text-danger">*</span></label>
                                <select name="service_id" id="service_id" class="form-select form-select-lg @error('service_id') is-invalid @enderror" required>
                                    <option value="" disabled {{ (!isset($selectedService) && old('service_id') !== 'other') ? 'selected' : '' }}>-- তালিকা থেকে সেবা বেছে নিন --</option>
                                    @foreach($services as $srv)
                                        <option value="{{ $srv->id }}"
                                            data-fee="{{ $srv->service_fee }}"
                                            data-govt-fee="{{ $srv->govt_fee }}"
                                            data-time="{{ $srv->delivery_time ?? '১-২ কর্মদিবস' }}"
                                            data-docs="{{ $srv->required_documents }}"
                                            {{ (old('service_id', $selectedService?->id) == $srv->id) ? 'selected' : '' }}>
                                            {{ $srv->title }} (ফি: ৳{{ number_format($srv->service_fee + $srv->govt_fee, 0) }})
                                        </option>
                                    @endforeach
                                    <option value="other" data-fee="0" data-govt-fee="0" data-time="আলোচনা সাপেক্ষে" data-docs="আপনার কাজের ধরন অনুযায়ী প্রয়োজনীয় কাগজপত্র আপলোড করুন।" {{ old('service_id') === 'other' ? 'selected' : '' }}>
                                        ✍️ তালিকায় নেই? সেবার নাম নিজে লিখুন (অন্যান্য সেবা)
                                    </option>
                                </select>
                                @error('service_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Manual / Custom Service Title Input Box --}}
                            <div id="customServiceBox" class="p-3 bg-light rounded-4 border border-2 border-warning mb-3" style="display: {{ old('service_id') === 'other' ? 'block' : 'none' }};">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-pencil-square text-warning fs-5"></i>
                                    <label for="custom_service_title" class="form-label fw-bold text-dark mb-0">
                                        আপনার কাঙ্ক্ষিত সেবার নাম বাংলায় লিখুন <span class="text-danger">*</span>
                                    </label>
                                </div>
                                <input type="text" name="custom_service_title" id="custom_service_title" 
                                       class="form-control form-control-lg bg-white @error('custom_service_title') is-invalid @enderror"
                                       placeholder="উদাঃ জমির খতিয়ান/পর্চা উত্তোলন, বিদ্যুৎ বিলের নাম সংশোধন, ইত্যাদি..."
                                       value="{{ old('custom_service_title') }}">
                                <div class="small text-muted mt-2 d-flex align-items-center gap-1.5">
                                    <i class="bi bi-shield-check text-success"></i>
                                    <span>তালিকায় না থাকলে এখানে নাম লিখুন। <strong>কোনো অগ্রিম ফি লাগবে না</strong>, দোকানে এসে সরাসরি নগদ (Shop Cash) পরিশোধ করতে পারবেন।</span>
                                </div>
                                @error('custom_service_title')
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Selected Service Details Accordion/Notice --}}
                            <div id="serviceDetailsNotice" class="alert alert-info border-0 rounded-3 mb-0" style="display: {{ isset($selectedService) ? 'block' : 'none' }};">
                                <div class="d-flex gap-2">
                                    <i class="bi bi-info-circle-fill fs-5 text-info"></i>
                                    <div>
                                        <strong id="noticeTitle">{{ $selectedService?->title }}</strong>
                                        <p class="mb-1 small" id="noticeTime">প্রত্যাশিত সময়: <span class="fw-semibold text-dark">{{ $selectedService?->delivery_time ?? '১-২ কর্মদিবস' }}</span></p>
                                        <div id="noticeDocs" class="small text-muted" style="white-space: pre-line;">{{ $selectedService?->required_documents }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 2: Customer Contact Information --}}
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <span class="order-step-badge">২</span>
                                <h5 class="fw-bold mb-0 text-dark">যোগাযোগের তথ্য</h5>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">আপনার নাম <span class="text-danger">*</span></label>
                                    <input type="text" name="customer_name" id="customer_name" class="form-control @error('customer_name') is-invalid @enderror"
                                        placeholder="উদাঃ মোঃ মনিরুল ইসলাম" value="{{ old('customer_name') }}" required>
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">মোবাইল নম্বর <span class="text-danger">*</span></label>
                                    <input type="tel" name="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror"
                                        placeholder="০১৭xxxxxxxx" value="{{ old('customer_phone') }}" required>
                                    <small class="text-muted d-block mt-1" style="font-size: 0.74rem; line-height: 1.2;">
                                        <i class="bi bi-chat-dots text-success me-1"></i> এসএমএস বা কলে কাজের আপডেট দেওয়া হবে।
                                    </small>
                                    @error('customer_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">ইমেইল (ঐচ্ছিক)</label>
                                    <input type="email" name="customer_email" class="form-control @error('customer_email') is-invalid @enderror"
                                        placeholder="example@mail.com" value="{{ old('customer_email') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">ঠিকানা / এলাকা (ঐচ্ছিক)</label>
                                    <input type="text" name="customer_address" class="form-control"
                                        placeholder="গ্রাম, ইউনিয়ন, উপজেলা" value="{{ old('customer_address') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 3: Application Data & Details (Bangla & English Names + Photo) --}}
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <span class="order-step-badge">৩</span>
                                <div>
                                    <h5 class="fw-bold mb-0 text-dark">আবেদন সংক্রান্ত তথ্যাবলী (বাংলা ও ইংরেজি)</h5>
                                    <small class="text-muted">আবেদনকারী, পিতা-মাতা ও স্বামী/স্ত্রীর নাম নির্ভুলভাবে বাংলা ও ইংরেজিতে পূরণ করুন।</small>
                                </div>
                            </div>

                            <div class="row g-3">
                                {{-- 1. Applicant Name in Bangla & English --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">আবেদনকারীর পূর্ণ নাম (বাংলায়) <span class="text-danger">*</span></label>
                                    <input type="text" name="app_applicant_name_bn" id="app_applicant_name_bn" class="form-control @error('app_applicant_name_bn') is-invalid @enderror"
                                        placeholder="উদাঃ মোঃ মনিরুল ইসলাম" value="{{ old('app_applicant_name_bn') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">আবেদনকারীর পূর্ণ নাম (ইংরেজিতে) <span class="text-danger">*</span></label>
                                    <input type="text" name="app_applicant_name_en" id="app_applicant_name_en" class="form-control @error('app_applicant_name_en') is-invalid @enderror"
                                        placeholder="উদাঃ Md. Monirul Islam" value="{{ old('app_applicant_name_en') }}" required>
                                </div>

                                {{-- 2. Father's Name in Bangla & English --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">পিতার নাম (বাংলায়) <span class="text-danger">*</span></label>
                                    <input type="text" name="app_father_name_bn" class="form-control @error('app_father_name_bn') is-invalid @enderror"
                                        placeholder="পিতার নাম বাংলায় লিখুন" value="{{ old('app_father_name_bn') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">পিতার নাম (ইংরেজিতে) <span class="text-danger">*</span></label>
                                    <input type="text" name="app_father_name_en" class="form-control @error('app_father_name_en') is-invalid @enderror"
                                        placeholder="Father's name in English" value="{{ old('app_father_name_en') }}" required>
                                </div>

                                {{-- 3. Mother's Name in Bangla & English --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">মাতার নাম (বাংলায়) <span class="text-danger">*</span></label>
                                    <input type="text" name="app_mother_name_bn" class="form-control @error('app_mother_name_bn') is-invalid @enderror"
                                        placeholder="মাতার নাম বাংলায় লিখুন" value="{{ old('app_mother_name_bn') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">মাতার নাম (ইংরেজিতে) <span class="text-danger">*</span></label>
                                    <input type="text" name="app_mother_name_en" class="form-control @error('app_mother_name_en') is-invalid @enderror"
                                        placeholder="Mother's name in English" value="{{ old('app_mother_name_en') }}" required>
                                </div>

                                {{-- 4. Spouse Name in Bangla & English (Optional) --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">স্বামী / স্ত্রীর নাম (বাংলায়) <small class="text-muted fw-normal">(প্রযোজ্য ক্ষেত্রে)</small></label>
                                    <input type="text" name="app_spouse_name_bn" class="form-control"
                                        placeholder="স্বামী বা স্ত্রীর নাম বাংলায় লিখুন" value="{{ old('app_spouse_name_bn') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">স্বামী / স্ত্রীর নাম (ইংরেজিতে) <small class="text-muted fw-normal">(প্রযোজ্য ক্ষেত্রে)</small></label>
                                    <input type="text" name="app_spouse_name_en" class="form-control"
                                        placeholder="Spouse name in English" value="{{ old('app_spouse_name_en') }}">
                                </div>

                                {{-- 5. User Photo Upload Field (Right after Name Fields) --}}
                                <div class="col-12 mt-2">
                                    <div class="p-3 rounded-4 border" style="background-color: #f0fdf4; border-color: #a7f3d0 !important;">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-person-bounding-box text-success fs-5"></i>
                                                <label for="applicant_photo" class="form-label fw-bold text-dark mb-0">আবেদনকারীর ছবি আপলোড (User Photo)</label>
                                            </div>
                                            <span class="badge rounded-pill px-3 py-1.5 fw-semibold shadow-sm" style="background-color: #059669 !important; color: #ffffff !important; font-size: 0.82rem;">
                                                <i class="bi bi-camera me-1 text-white"></i> পাসপোর্ট সাইজ
                                            </span>
                                        </div>
                                        <p class="small text-muted mb-2">আবেদনকারীর সাম্প্রতিক তোলা রঙিন পাসপোর্ট সাইজের স্পষ্ট ছবি নির্বাচন করুন (JPG, PNG, WebP - সর্বোচ্চ ৫ মেগাবাইট)।</p>
                                        <div class="row align-items-center g-3">
                                            <div class="col-md-8">
                                                <input type="file" name="applicant_photo" id="applicant_photo" class="form-control bg-white @error('applicant_photo') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg,image/webp">
                                                @error('applicant_photo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 text-center text-md-end">
                                                <div id="applicantPhotoPreviewContainer" style="display: none;">
                                                    <img id="applicantPhotoPreview" src="#" alt="Photo Preview" class="img-thumbnail rounded-3 shadow-sm" style="max-height: 80px; width: auto; object-fit: cover;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- 6. Additional Standard Application Fields --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">জন্ম তারিখ (DOB)</label>
                                    <input type="date" name="app_dob" class="form-control" value="{{ old('app_dob') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">জাতীয় পরিচয়পত্র / জন্মনিবন্ধন নম্বর</label>
                                    <input type="text" name="app_nid_birth_reg_no" class="form-control" placeholder="NID বা ১৭ ডিজিট জন্মনিবন্ধন নম্বর" value="{{ old('app_nid_birth_reg_no') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">পদের নাম / সার্কুলার / প্রতিষ্ঠানের নাম</label>
                                    <input type="text" name="app_job_post_or_circular" class="form-control" placeholder="যেমনঃ অফিস সহকারী / অনার্স ১ম বর্ষ / সিসিটিভি সেবা" value="{{ old('app_job_post_or_circular') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">শিক্ষাগত যোগ্যতা / রোল ও রেজিস্ট্রেশন</label>
                                    <input type="text" name="app_exam_roll_reg" class="form-control" placeholder="যেমনঃ SSC Roll: 123456, Reg: 1234567890..." value="{{ old('app_exam_roll_reg') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">কলেজ/বিষয়ের পছন্দক্রম বা কাজের বিশেষ নির্দেশনা</label>
                                    <textarea name="app_choice_list" class="form-control" rows="2" placeholder="ভর্তি আবেদনের ক্ষেত্রে পছন্দের তালিকা, চাকরির বিশেষ নির্দেশনা বা সিসিটিভি সেবার বিবরণ লিখুন...">{{ old('app_choice_list') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 4: File & Document Upload (Minimum 2 Files Required, More than 2 Allowed) --}}
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <span class="order-step-badge">৪</span>
                                <div>
                                    <h5 class="fw-bold mb-0 text-dark">প্রয়োজনীয় কাগজপত্র ও ফাইল আপলোড <span class="text-danger">*</span></h5>
                                    <small class="text-muted">অনলাইনে আবেদনের জন্য <strong>নূন্যতম ২টি ফাইল</strong> আপলোড করতে হবে (২টির অধিক যেকোনো সংখ্যক ফাইল একসাথে বা আলাদা আলাদা সিলেক্ট করতে পারবেন)।</small>
                                </div>
                            </div>

                            {{-- Hidden File Inputs (Native Form Input & Helper File Picker) --}}
                            <input type="file" name="attachments[]" id="attachments" class="d-none" multiple accept="image/*,.pdf,.doc,.docx">
                            <input type="file" id="tempDocPicker" class="d-none" multiple accept="image/*,.pdf,.doc,.docx">

                            {{-- Drag and Drop Upload Zone --}}
                            <div id="uploadDropzone" class="upload-dropzone border border-2 border-dashed rounded-4 p-4 text-center bg-white" style="border-color: #10b981 !important; cursor: pointer;">
                                <div class="mb-2">
                                    <span class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-circle" style="width: 54px; height: 54px;">
                                        <i class="bi bi-cloud-arrow-up-fill fs-2"></i>
                                    </span>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">ফাইল সিলেক্ট করুন অথবা ড্র্যাগ করে ড্রপ করুন</h6>
                                <p class="text-muted small mb-3">যেমন: NID / জন্মসনদ, স্বাক্ষর, শিক্ষাগত সনদ বা অন্যান্য প্রয়োজনীয় ডকুমেন্টস (কমপক্ষে ২টি ফাইল আবশ্যক)</p>
                                <div>
                                    <button type="button" class="btn btn-success fw-semibold px-4 py-2 rounded-pill shadow-sm" id="btnPickDoc">
                                        <i class="bi bi-plus-circle-fill me-1"></i> + ডকুমেন্ট যুক্ত করুন
                                    </button>
                                </div>
                                <div class="mt-2 text-muted" style="font-size: 0.78rem;">
                                    সমর্থিত ফরম্যাট: JPG, PNG, PDF, DOC, DOCX (প্রতিটি ফাইল সর্বোচ্চ ১২ মেগাবাইট)
                                </div>
                            </div>

                            {{-- Selected Documents List Section --}}
                            <div id="documentsListContainer" class="mt-3" style="display: none;">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold text-dark fs-6">সংযুক্ত ডকুমেন্টস তালিকা</span>
                                        <span id="docCountBadge" class="badge bg-success rounded-pill px-2">০টি</span>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-success rounded-pill fw-semibold" id="btnAddMoreDocs">
                                        <i class="bi bi-plus-lg me-1"></i> + আরও ডকুমেন্ট যোগ করুন
                                    </button>
                                </div>

                                {{-- Validation / Status Alert --}}
                                <div id="docStatusAlert" class="alert py-2 px-3 small d-flex align-items-center gap-2 mb-2">
                                    <i id="docStatusIcon" class="bi fs-5"></i>
                                    <span id="docStatusText" class="fw-semibold"></span>
                                </div>

                                {{-- Dynamic Cards List --}}
                                <div id="documentsCardsList" class="d-flex flex-column gap-2" style="max-height: 280px; overflow-y: auto;">
                                    {{-- Rendered via JavaScript --}}
                                </div>
                            </div>

                            @error('attachments')
                                <div class="alert alert-danger py-2 px-3 small mt-2 fw-semibold">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $message }}
                                </div>
                            @enderror
                            @error('attachments.*')
                                <div class="alert alert-danger py-2 px-3 small mt-1">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $message }}
                                </div>
                            @enderror

                            <div class="mt-3">
                                <label class="form-label fw-semibold">অতিরিক্ত কোনো নোট বা নির্দেশনা (ঐচ্ছিক)</label>
                                <textarea name="customer_notes" class="form-control" rows="2" placeholder="আমাদের অপারেটরের জন্য কোনো অতিরিক্ত নির্দেশনা থাকলে এখানে লিখুন...">{{ old('customer_notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Summary & Payment Column --}}
                <div class="col-lg-5">
                    <div class="sticky-top" style="top: 90px; z-index: 10;">
                        {{-- Fee Summary Card --}}
                        <div class="service-summary-box p-4 shadow-sm mb-4">
                            <h5 class="fw-bold mb-3 border-bottom border-white border-opacity-25 pb-2">
                                <i class="bi bi-receipt-cutoff me-2"></i> ফি বিবরণী
                            </h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span>নির্বাচিত সেবা:</span>
                                <strong id="summaryServiceTitle">{{ $selectedService?->title ?? 'সেবা নির্বাচন করুন' }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>সেবা ফি (সার্ভিস চার্জ):</span>
                                <span>৳<span id="summaryServiceFee">{{ number_format($selectedService?->service_fee ?? 0, 2) }}</span></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>সরকারি/অফিসিয়াল ফি (যদি থাকে):</span>
                                <span>৳<span id="summaryGovtFee">{{ number_format($selectedService?->govt_fee ?? 0, 2) }}</span></span>
                            </div>
                            <hr class="border-white border-opacity-25 my-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fs-5 fw-bold">সর্বমোট প্রদেয় টাকা:</span>
                                <span class="fs-3 fw-bolder">৳<span id="summaryTotalFee">{{ number_format(($selectedService?->service_fee ?? 0) + ($selectedService?->govt_fee ?? 0), 2) }}</span></span>
                            </div>
                        </div>

                        {{-- Step 5: Payment Card --}}
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="order-step-badge bg-danger">৫</span>
                                    <h5 class="fw-bold mb-0 text-dark">পেমেন্ট মেথড ও তথ্য</h5>
                                </div>

                                {{-- Manual Service Zero-Payment Notice --}}
                                <div id="manualPayNotice" class="alert alert-success border-0 rounded-4 p-3 mb-3 shadow-sm" style="display: {{ old('service_id') === 'other' ? 'block' : 'none' }};">
                                    <div class="d-flex align-items-start gap-2">
                                        <i class="bi bi-check-circle-fill fs-4 text-success flex-shrink-0"></i>
                                        <div>
                                            <strong class="d-block text-success mb-1">কোনো অগ্রিম ফি বা পেমেন্ট লাগবে না!</strong>
                                            <p class="small text-dark mb-0" style="line-height: 1.5;">
                                                যেহেতু আপনি তালিকার বাইরে নতুন সেবা নিজে লিখেছেন, তাই অনলাইনে কোনো টাকা পাঠাতে হবে না। পেমেন্ট মাধ্যম হিসেবে <strong>'দোকানে এসে সরাসরি নগদ প্রদান' (Shop Cash)</strong> নির্বাচিত রাখা হয়েছে।
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Payment Method Radio Selector --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">পেমেন্ট মাধ্যম বেছে নিন <span class="text-danger">*</span></label>
                                    <div class="row g-2">
                                        <div class="col-6" id="payBkashCol">
                                            <div class="form-check payment-option-card d-flex align-items-center gap-2">
                                                <input class="form-check-input" type="radio" name="payment_method" id="pay_bkash" value="bkash" {{ old('payment_method', 'bkash') == 'bkash' ? 'checked' : '' }} required>
                                                <label class="form-check-label w-100 cursor-pointer" for="pay_bkash">
                                                    <span class="badge bg-danger text-white me-1">bKash</span> বিকাশ
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6" id="payNagadCol">
                                            <div class="form-check payment-option-card d-flex align-items-center gap-2">
                                                <input class="form-check-input" type="radio" name="payment_method" id="pay_nagad" value="nagad" {{ old('payment_method') == 'nagad' ? 'checked' : '' }}>
                                                <label class="form-check-label w-100 cursor-pointer" for="pay_nagad">
                                                    <span class="badge bg-warning text-dark me-1">Nagad</span> নগদ
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12" id="payCashCol">
                                            <div class="form-check payment-option-card d-flex align-items-center gap-2" id="payCashCard">
                                                <input class="form-check-input" type="radio" name="payment_method" id="pay_cash" value="shop_cash" {{ (old('payment_method') == 'shop_cash' || old('service_id') == 'other') ? 'checked' : '' }}>
                                                <label class="form-check-label w-100 cursor-pointer fw-semibold text-dark" for="pay_cash">
                                                    <i class="bi bi-cash-stack text-success fs-5 me-1"></i> দোকানে এসে সরাসরি নগদ প্রদান (Shop Cash)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @error('payment_method')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Payment Instructions Box (bKash Payment & Personal, Nagad Personal) --}}
                                <div id="onlinePaymentDetails">
                                    <div class="p-3 bg-light rounded-4 mb-3 border">
                                        <h6 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2">
                                            <i class="bi bi-wallet2 text-success"></i> টাকা পাঠানোর পেমেন্ট নম্বরসমূহ:
                                        </h6>

                                        {{-- 1. bKash Payment / Merchant Number & QR Code --}}
                                        <div id="bkashPaymentCardBox" class="p-3 mb-3 qr-preview-card">
                                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                                <div>
                                                    <span class="badge bg-danger text-white px-2 py-1">বিকাশ পেমেন্ট (Payment):</span>
                                                    <strong class="text-dark fs-5 font-monospace ms-1 user-select-all">01531160434</strong>
                                                    <small class="d-block text-muted" style="font-size: 0.8rem;">বিকাশ অ্যাপের 'Make Payment' অপশন অথবা নিচের কিউআর কোড স্ক্যান করুন</small>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-danger rounded-pill px-3 py-1 copy-btn" data-copy="01531160434">
                                                    <i class="bi bi-copy me-1"></i> কপি
                                                </button>
                                            </div>

                                            {{-- Bangla QR Code Image --}}
                                            <div class="text-center pt-2 border-top">
                                                <div class="d-flex align-items-center justify-content-center gap-2 mb-1">
                                                    <span class="small fw-bold text-danger"><i class="bi bi-qr-code-scan"></i> বিকাশ বাংলা কিউআর (Bangla QR)</span>
                                                </div>
                                                <a href="{{ asset('storage/settings/bkash_payment_qr.jpg') }}" target="_blank" title="বড় আকারে দেখতে ক্লিক করুন">
                                                    <img src="{{ asset('storage/settings/bkash_payment_qr.jpg') }}" alt="bKash Bangla QR - 01531160434" class="img-fluid rounded-3 shadow-sm border bg-white" style="max-height: 180px; width: auto; object-fit: contain;">
                                                </a>
                                                <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">বিকাশ অ্যাপে কিউআর স্ক্যান করে দ্রুত পেমেন্ট সম্পন্ন করুন</small>
                                            </div>
                                        </div>

                                        {{-- 2. bKash Personal Number (Send Money) --}}
                                        <div class="p-2 mb-2 bg-white rounded-3 border d-flex flex-wrap align-items-center justify-content-between gap-2">
                                            <div>
                                                <span class="badge bg-danger text-white me-1">বিকাশ পার্সোনাল:</span>
                                                <strong class="text-dark fs-6 font-monospace user-select-all">01711665098</strong>
                                                <small class="d-block text-muted" style="font-size: 0.78rem;">বিকাশ অ্যাপ বা *247# থেকে Send Money করুন</small>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 copy-btn" data-copy="01711665098">
                                                <i class="bi bi-copy me-1"></i> কপি
                                            </button>
                                        </div>

                                        {{-- 3. Nagad Personal Number (Send Money) --}}
                                        <div class="p-2 mb-3 bg-white rounded-3 border d-flex flex-wrap align-items-center justify-content-between gap-2">
                                            <div>
                                                <span class="badge bg-warning text-dark me-1">নগদ পার্সোনাল:</span>
                                                <strong class="text-dark fs-6 font-monospace user-select-all">01711665098</strong>
                                                <small class="d-block text-muted" style="font-size: 0.78rem;">নগদ অ্যাপ বা *167# থেকে Send Money করুন</small>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 copy-btn" data-copy="01711665098">
                                                <i class="bi bi-copy me-1"></i> কপি
                                            </button>
                                        </div>

                                        <p class="text-muted small mb-0 mt-1" style="font-size: 0.8rem; line-height: 1.6;">
                                            ১. বিকাশ পেমেন্ট (01531160434) বা বিকাশ/নগদ পার্সোনাল (01711665098)-এ আবেদনের মোট ফি পরিশোধ করুন।<br>
                                            ২. টাকা পাঠানোর পর প্রাপ্ত Transaction ID (TrxID) এবং যে নম্বর থেকে পাঠিয়েছেন তা নিচের বক্সে লিখে অর্ডার কনফার্ম করুন।
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">যে নম্বর থেকে টাকা পাঠিয়েছেন <span class="text-danger">*</span></label>
                                        <input type="text" name="payment_number" id="payment_number" class="form-control @error('payment_number') is-invalid @enderror"
                                            placeholder="০১৭xxxxxxxx" value="{{ old('payment_number') }}">
                                        @error('payment_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Transaction ID (TrxID) <span class="text-danger">*</span></label>
                                        <input type="text" name="transaction_id" id="transaction_id" class="form-control text-uppercase @error('transaction_id') is-invalid @enderror"
                                            placeholder="উদাঃ 9H8K7L2M" value="{{ old('transaction_id') }}">
                                        @error('transaction_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div id="cashNotice" class="alert alert-warning border-0 small mb-3" style="display: none;">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> আপনি দোকানে সরাসরি এসে নগদ টাকা পরিশোধ করবেন। আপনার আবেদন জমা রাখা হবে এবং দোকানে পেমেন্টের পর কাজ শুরু হবে।
                                </div>

                                {{-- Submit Button --}}
                                <button type="submit" id="submitOrderBtn" class="btn btn-success btn-lg w-100 py-3 fw-bold rounded-pill shadow-sm">
                                    <i class="bi bi-check-circle-fill me-2"></i> আবেদন অর্ডার কনফার্ম করুন
                                </button>
                                <p class="text-center text-muted small mt-2 mb-0">অর্ডার সাবমিটের সাথে সাথে আপনি একটি ট্র্যাকিং নম্বর পাবেন।</p>
                            </div>
                        </div>

                        {{-- Shop Help Support --}}
                        <div class="card border-0 bg-white shadow-sm rounded-4 p-3 text-center">
                            <span class="small text-muted mb-1">কোনো সমস্যা বা জরুরি প্রয়োজনে যোগাযোগ করুন:</span>
                            <div class="fw-bold text-success fs-5">
                                <i class="bi bi-telephone-fill me-1"></i> ০১৭১১-৬৬৫০৯৮ / ০১৫৩১-১৬০৪৩৪
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const serviceSelect = document.getElementById('service_id');
        const summaryServiceTitle = document.getElementById('summaryServiceTitle');
        const summaryServiceFee = document.getElementById('summaryServiceFee');
        const summaryGovtFee = document.getElementById('summaryGovtFee');
        const summaryTotalFee = document.getElementById('summaryTotalFee');
        const serviceDetailsNotice = document.getElementById('serviceDetailsNotice');
        const noticeTitle = document.getElementById('noticeTitle');
        const noticeTime = document.getElementById('noticeTime');
        const noticeDocs = document.getElementById('noticeDocs');

        const payMethodRadios = document.querySelectorAll('input[name="payment_method"]');
        const onlinePaymentDetails = document.getElementById('onlinePaymentDetails');
        const cashNotice = document.getElementById('cashNotice');
        const paymentNumberInput = document.getElementById('payment_number');
        const transactionIdInput = document.getElementById('transaction_id');

        // Photo Preview handler
        const applicantPhotoInput = document.getElementById('applicant_photo');
        const photoPreview = document.getElementById('applicantPhotoPreview');
        const photoPreviewContainer = document.getElementById('applicantPhotoPreviewContainer');

        if (applicantPhotoInput) {
            applicantPhotoInput.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        photoPreview.src = e.target.result;
                        photoPreviewContainer.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    photoPreviewContainer.style.display = 'none';
                }
            });
        }

        // Interactive Multi-Document Upload Manager
        const attachmentsInput = document.getElementById('attachments');
        const tempDocPicker = document.getElementById('tempDocPicker');
        const uploadDropzone = document.getElementById('uploadDropzone');
        const btnPickDoc = document.getElementById('btnPickDoc');
        const btnAddMoreDocs = document.getElementById('btnAddMoreDocs');
        const documentsListContainer = document.getElementById('documentsListContainer');
        const docCountBadge = document.getElementById('docCountBadge');
        const docStatusAlert = document.getElementById('docStatusAlert');
        const docStatusIcon = document.getElementById('docStatusIcon');
        const docStatusText = document.getElementById('docStatusText');
        const documentsCardsList = document.getElementById('documentsCardsList');
        const orderForm = document.getElementById('serviceOrderForm');

        let fileListArray = [];

        function formatBytes(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        }

        function getFileIcon(filename) {
            const ext = filename.split('.').pop().toLowerCase();
            if (['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp'].includes(ext)) {
                return '<i class="bi bi-file-earmark-image-fill text-primary fs-3"></i>';
            } else if (ext === 'pdf') {
                return '<i class="bi bi-file-earmark-pdf-fill text-danger fs-3"></i>';
            } else if (['doc', 'docx'].includes(ext)) {
                return '<i class="bi bi-file-earmark-word-fill text-info fs-3"></i>';
            } else {
                return '<i class="bi bi-file-earmark-text-fill text-secondary fs-3"></i>';
            }
        }

        function syncDataTransfer() {
            try {
                const dt = new DataTransfer();
                fileListArray.forEach(file => dt.items.add(file));
                if (attachmentsInput) {
                    attachmentsInput.files = dt.files;
                }
            } catch (err) {
                console.warn('DataTransfer sync warning:', err);
            }
        }

        function renderDocumentList() {
            if (!documentsListContainer || !documentsCardsList) return;

            if (fileListArray.length === 0) {
                documentsListContainer.style.display = 'none';
                documentsCardsList.innerHTML = '';
                syncDataTransfer();
                return;
            }

            documentsListContainer.style.display = 'block';
            if (docCountBadge) docCountBadge.textContent = `${fileListArray.length}টি`;

            // Status message and badge styling
            if (fileListArray.length < 2) {
                docStatusAlert.className = 'alert alert-warning py-2 px-3 small d-flex align-items-center gap-2 mb-2 border-warning-subtle';
                docStatusIcon.className = 'bi bi-exclamation-triangle-fill text-warning fs-5';
                docStatusText.innerHTML = `আপনি <strong>${fileListArray.length}টি</strong> ফাইল নির্বাচন করেছেন। আবেদনের জন্য আরও নূন্যতম <strong>${2 - fileListArray.length}টি</strong> ফাইল আবশ্যক (+ আরও ডকুমেন্ট যোগ করুন)।`;
            } else {
                docStatusAlert.className = 'alert alert-success py-2 px-3 small d-flex align-items-center gap-2 mb-2 border-success-subtle';
                docStatusIcon.className = 'bi bi-check-circle-fill text-success fs-5';
                docStatusText.innerHTML = `মোট <strong>${fileListArray.length}টি</strong> ফাইল সফলভাবে যুক্ত হয়েছে (নূন্যতম ২টি ফাইলের শর্ত পূরণ হয়েছে)।`;
            }

            documentsCardsList.innerHTML = '';

            fileListArray.forEach((file, index) => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'doc-item bg-white border rounded-3 p-2 px-3 d-flex align-items-center justify-content-between shadow-sm';
                itemDiv.innerHTML = `
                    <div class="d-flex align-items-center gap-2 text-truncate" style="max-width: 80%;">
                        <div class="flex-shrink-0">
                            ${getFileIcon(file.name)}
                        </div>
                        <div class="text-truncate">
                            <div class="fw-semibold text-dark text-truncate" title="${file.name}">
                                ${file.name}
                            </div>
                            <div class="text-muted small">
                                <span class="badge bg-light text-secondary border me-1">ডকুমেন্ট #${index + 1}</span>
                                <span>${formatBytes(file.size)}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="button" class="btn btn-outline-danger btn-sm rounded-circle d-flex align-items-center justify-content-center remove-doc-btn" data-index="${index}" title="এই ফাইলটি মুছে ফেলুন (-)" style="width: 32px; height: 32px;">
                            <i class="bi bi-dash-lg fw-bold"></i>
                        </button>
                    </div>
                `;
                documentsCardsList.appendChild(itemDiv);
            });

            // Bind click to each remove (-) button
            documentsCardsList.querySelectorAll('.remove-doc-btn').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const removeIdx = parseInt(this.getAttribute('data-index'), 10);
                    if (!isNaN(removeIdx)) {
                        fileListArray.splice(removeIdx, 1);
                        syncDataTransfer();
                        renderDocumentList();
                    }
                });
            });

            syncDataTransfer();
        }

        function handleNewFiles(newFiles) {
            if (!newFiles || newFiles.length === 0) return;
            for (let i = 0; i < newFiles.length; i++) {
                const newFile = newFiles[i];
                // Check if already in list (same name and size)
                const exists = fileListArray.some(f => f.name === newFile.name && f.size === newFile.size);
                if (!exists) {
                    fileListArray.push(newFile);
                }
            }
            renderDocumentList();
        }

        function triggerPicker() {
            if (tempDocPicker) {
                tempDocPicker.value = '';
                tempDocPicker.click();
            }
        }

        if (btnPickDoc) {
            btnPickDoc.addEventListener('click', function (e) {
                e.stopPropagation();
                triggerPicker();
            });
        }

        if (btnAddMoreDocs) {
            btnAddMoreDocs.addEventListener('click', function (e) {
                e.stopPropagation();
                triggerPicker();
            });
        }

        if (uploadDropzone) {
            uploadDropzone.addEventListener('click', function (e) {
                if (e.target.closest('#btnPickDoc')) return;
                triggerPicker();
            });

            uploadDropzone.addEventListener('dragover', function (e) {
                e.preventDefault();
                e.stopPropagation();
                uploadDropzone.classList.add('dragover');
            });

            uploadDropzone.addEventListener('dragleave', function (e) {
                e.preventDefault();
                e.stopPropagation();
                uploadDropzone.classList.remove('dragover');
            });

            uploadDropzone.addEventListener('drop', function (e) {
                e.preventDefault();
                e.stopPropagation();
                uploadDropzone.classList.remove('dragover');
                if (e.dataTransfer && e.dataTransfer.files) {
                    handleNewFiles(e.dataTransfer.files);
                }
            });
        }

        if (tempDocPicker) {
            tempDocPicker.addEventListener('change', function () {
                if (this.files && this.files.length > 0) {
                    handleNewFiles(this.files);
                }
            });
        }

        // Prevent submit if less than 2 files selected
        if (orderForm) {
            orderForm.addEventListener('submit', function (e) {
                if (fileListArray.length < 2) {
                    e.preventDefault();
                    renderDocumentList();
                    if (uploadDropzone) {
                        uploadDropzone.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        uploadDropzone.classList.add('border-danger');
                        setTimeout(() => uploadDropzone.classList.remove('border-danger'), 2500);
                    }
                    alert('অনলাইনে আবেদনের জন্য অনুগ্রহ করে কমপক্ষে ২টি ফাইল (যেমন: NID/জন্মনিবন্ধন, সনদ, স্বাক্ষর ইত্যাদি) নির্বাচন করুন।');
                }
            });
        }

        // Copy button handler
        document.querySelectorAll('.copy-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const textToCopy = this.getAttribute('data-copy');
                if (textToCopy) {
                    navigator.clipboard.writeText(textToCopy).then(() => {
                        const originalHtml = this.innerHTML;
                        this.innerHTML = '<i class="bi bi-check2"></i> কপি হয়েছে!';
                        this.classList.remove('btn-outline-secondary', 'btn-outline-danger', 'btn-danger');
                        this.classList.add('btn-success');
                        setTimeout(() => {
                            this.innerHTML = originalHtml;
                            this.classList.remove('btn-success');
                            if (textToCopy === '01531160434') {
                                this.classList.add('btn-danger');
                            } else {
                                this.classList.add('btn-outline-secondary');
                            }
                        }, 2000);
                    });
                }
            });
        });

        // Auto-sync customer_name if applicant name in Bangla is typed
        const appNameBn = document.getElementById('app_applicant_name_bn');
        const customerName = document.getElementById('customer_name');
        if (appNameBn && customerName) {
            appNameBn.addEventListener('input', function () {
                if (!customerName.value || customerName.dataset.autoFilled === 'true') {
                    customerName.value = this.value;
                    customerName.dataset.autoFilled = 'true';
                }
            });
            customerName.addEventListener('input', function () {
                customerName.dataset.autoFilled = 'false';
            });
        }

        // Custom Manual Service Elements
        const customServiceBox = document.getElementById('customServiceBox');
        const customServiceInput = document.getElementById('custom_service_title');
        const manualPayNotice = document.getElementById('manualPayNotice');
        const payBkashCol = document.getElementById('payBkashCol');
        const payNagadCol = document.getElementById('payNagadCol');
        const payCashRadio = document.getElementById('pay_cash');
        const payBkashRadio = document.getElementById('pay_bkash');
        const payNagadRadio = document.getElementById('pay_nagad');

        function updateServiceSummary() {
            const selectedOpt = serviceSelect.options[serviceSelect.selectedIndex];
            if (!selectedOpt || !selectedOpt.value) return;

            const isManual = selectedOpt.value === 'other';

            if (isManual) {
                if (customServiceBox) customServiceBox.style.display = 'block';
                if (customServiceInput) {
                    customServiceInput.setAttribute('required', 'required');
                    const manualTitle = customServiceInput.value.trim() || 'অন্যান্য সেবা (ম্যানুয়াল)';
                    summaryServiceTitle.textContent = manualTitle;
                } else {
                    summaryServiceTitle.textContent = 'অন্যান্য সেবা (ম্যানুয়াল)';
                }

                summaryServiceFee.textContent = '0.00 (দোকানে প্রযোজ্য)';
                summaryGovtFee.textContent = '0.00';
                summaryTotalFee.textContent = '0.00 (অগ্রিম নেই)';

                if (serviceDetailsNotice) serviceDetailsNotice.style.display = 'none';

                // Automatically select Shop Cash
                if (payCashRadio) {
                    payCashRadio.checked = true;
                }
                if (manualPayNotice) manualPayNotice.style.display = 'block';
                if (payBkashCol) payBkashCol.style.opacity = '0.4';
                if (payNagadCol) payNagadCol.style.opacity = '0.4';
                if (payBkashRadio) payBkashRadio.disabled = true;
                if (payNagadRadio) payNagadRadio.disabled = true;

                updatePaymentMode();
            } else {
                if (customServiceBox) customServiceBox.style.display = 'none';
                if (customServiceInput) customServiceInput.removeAttribute('required');
                if (manualPayNotice) manualPayNotice.style.display = 'none';
                if (payBkashCol) payBkashCol.style.opacity = '1';
                if (payNagadCol) payNagadCol.style.opacity = '1';
                if (payBkashRadio) payBkashRadio.disabled = false;
                if (payNagadRadio) payNagadRadio.disabled = false;

                const sFee = parseFloat(selectedOpt.getAttribute('data-fee') || '0');
                const gFee = parseFloat(selectedOpt.getAttribute('data-govt-fee') || '0');
                const total = sFee + gFee;
                const time = selectedOpt.getAttribute('data-time') || '১-২ কর্মদিবস';
                const docs = selectedOpt.getAttribute('data-docs') || '';

                summaryServiceTitle.textContent = selectedOpt.textContent.split('(')[0].trim();
                summaryServiceFee.textContent = sFee.toFixed(2);
                summaryGovtFee.textContent = gFee.toFixed(2);
                summaryTotalFee.textContent = total.toFixed(2);

                if (noticeTitle) noticeTitle.textContent = selectedOpt.textContent.split('(')[0].trim();
                if (noticeTime) noticeTime.innerHTML = 'প্রত্যাশিত সময়: <span class="fw-semibold text-dark">' + time + '</span>';
                if (noticeDocs) noticeDocs.textContent = docs;
                if (serviceDetailsNotice) serviceDetailsNotice.style.display = 'block';

                updatePaymentMode();
            }
        }

        if (customServiceInput) {
            customServiceInput.addEventListener('input', function () {
                if (serviceSelect && serviceSelect.value === 'other') {
                    summaryServiceTitle.textContent = this.value.trim() || 'অন্যান্য সেবা (ম্যানুয়াল)';
                }
            });
        }

        serviceSelect.addEventListener('change', updateServiceSummary);

        function updatePaymentMode() {
            let selectedMethod = 'bkash';
            payMethodRadios.forEach(r => {
                if (r.checked) selectedMethod = r.value;
            });

            if (selectedMethod === 'shop_cash' || (serviceSelect && serviceSelect.value === 'other')) {
                onlinePaymentDetails.style.display = 'none';
                cashNotice.style.display = (serviceSelect && serviceSelect.value === 'other') ? 'none' : 'block';
                paymentNumberInput.removeAttribute('required');
                transactionIdInput.removeAttribute('required');
            } else {
                onlinePaymentDetails.style.display = 'block';
                cashNotice.style.display = 'none';
                paymentNumberInput.setAttribute('required', 'required');
                transactionIdInput.setAttribute('required', 'required');
            }
        }

        payMethodRadios.forEach(r => r.addEventListener('change', updatePaymentMode));

        // Initial setup on page load
        if (serviceSelect && serviceSelect.value) {
            updateServiceSummary();
        } else {
            updatePaymentMode();
        }
    });
</script>
@endpush
