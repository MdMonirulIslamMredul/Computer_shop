@extends('admin.layouts.app')

@section('title', 'গ্রাহক প্রোফাইল - ' . ($customer->name_bn ?: $customer->customer_name))

@section('content')
<div class="container-fluid px-0">
    {{-- Breadcrumb & Navigation Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">ড্যাশবোর্ড</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.customers.index') }}" class="text-decoration-none">গ্রাহক ডিরেক্টরি</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $customer->phone }}</li>
                </ol>
            </nav>
            <h3 class="fw-bold mb-1 text-dark">
                <i class="bi bi-person-bounding-box text-success me-2"></i> গ্রাহক প্রোফাইল ও বিস্তারিত তথ্য
            </h3>
            <p class="text-muted mb-0">মোবাইল নম্বর: <span class="fw-bold text-dark font-monospace">{{ $customer->phone }}</span> এর সকল জমা দেওয়া তথ্য ও আবেদনের ইতিহাস</p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-outline-secondary d-none d-sm-inline-flex align-items-center">
                <i class="bi bi-printer me-1"></i> প্রিন্ট করুন
            </button>
            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-success">
                <i class="bi bi-arrow-left me-1"></i> গ্রাহক তালিকা
            </a>
            <a href="{{ route('admin.orders.index', ['search' => $customer->phone]) }}" class="btn btn-success">
                <i class="bi bi-receipt me-1"></i> অর্ডারে অনুসন্ধান
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left Column: Identity & Contact Card --}}
        <div class="col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-4 mb-4 bg-white">
                <div class="position-relative d-inline-block mx-auto mb-3">
                    @if($customer->photo_url)
                        <a href="{{ $customer->photo_url }}" target="_blank" title="ছবিটি বড় করে দেখুন">
                            <img src="{{ $customer->photo_url }}" alt="{{ $customer->name_bn }}"
                                 class="rounded-4 shadow-sm border border-3 border-success-subtle img-fluid"
                                 style="width: 140px; height: 140px; object-fit: cover;">
                        </a>
                    @else
                        <div class="rounded-4 bg-success-subtle text-success d-inline-flex align-items-center justify-content-center fw-bold shadow-sm mx-auto"
                             style="width: 140px; height: 140px; font-size: 3rem;">
                            {{ mb_substr($customer->name_bn, 0, 1) }}
                        </div>
                    @endif
                    <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-success p-2 border border-2 border-white" title="সক্রিয় গ্রাহক">
                        <i class="bi bi-check-lg text-white"></i>
                    </span>
                </div>

                <h4 class="fw-bold text-dark mb-1">{{ $customer->name_bn }}</h4>
                @if($customer->name_en)
                    <p class="text-muted small mb-2">{{ $customer->name_en }}</p>
                @endif

                <div class="d-inline-flex align-items-center gap-1 bg-light px-3 py-1 rounded-pill mb-3">
                    <i class="bi bi-telephone-fill text-success small"></i>
                    <span class="font-monospace fw-bold text-dark">{{ $customer->phone }}</span>
                </div>

                <div class="d-grid gap-2 mb-3">
                    <a href="tel:{{ $customer->phone }}" class="btn btn-outline-primary btn-sm rounded-pill">
                        <i class="bi bi-telephone-outbound me-1"></i> সরাসরি কল করুন
                    </a>
                    @php
                        $cleanPh = preg_replace('/[^0-9]/', '', $customer->phone);
                        if (str_starts_with($cleanPh, '01')) {
                            $cleanPh = '88' . $cleanPh;
                        }
                    @endphp
                    <a href="https://wa.me/{{ $cleanPh }}" target="_blank" class="btn btn-outline-success btn-sm rounded-pill">
                        <i class="bi bi-whatsapp me-1"></i> হোয়াটসঅ্যাপে মেসেজ
                    </a>
                </div>

                <hr class="text-muted my-3 opacity-25">

                <div class="text-start small">
                    @if($customer->email)
                        <div class="mb-2">
                            <span class="text-muted d-block">ইমেইল এড্রেস:</span>
                            <a href="mailto:{{ $customer->email }}" class="text-decoration-none text-dark fw-semibold">
                                <i class="bi bi-envelope me-1 text-primary"></i> {{ $customer->email }}
                            </a>
                        </div>
                    @endif

                    @if($customer->address)
                        <div class="mb-2">
                            <span class="text-muted d-block">ঠিকানা:</span>
                            <span class="text-dark fw-semibold">
                                <i class="bi bi-geo-alt me-1 text-danger"></i> {{ $customer->address }}
                            </span>
                        </div>
                    @endif

                    <div class="mb-2">
                        <span class="text-muted d-block">প্রথম আবেদন:</span>
                        <span class="text-dark fw-semibold">
                            <i class="bi bi-calendar3 me-1 text-info"></i> {{ $customer->orders->last()?->created_at?->format('d M, Y (h:i A)') }}
                        </span>
                    </div>

                    <div>
                        <span class="text-muted d-block">সর্বশেষ আবেদন:</span>
                        <span class="text-dark fw-semibold">
                            <i class="bi bi-clock-history me-1 text-warning"></i> {{ $customer->latest_date ? $customer->latest_date->format('d M, Y (h:i A)') : 'N/A' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Summary Stats Mini-cards --}}
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-bar-chart-fill text-success me-2"></i>আবেদন সারসংক্ষেপ</h6>
                <div class="row g-2 text-center">
                    <div class="col-6">
                        <div class="p-2 rounded-3 bg-light">
                            <small class="text-muted d-block" style="font-size: 0.75rem;">মোট অর্ডার</small>
                            <span class="fw-bold text-primary fs-5">{{ $customer->orders_count }} টি</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 rounded-3 bg-light">
                            <small class="text-muted d-block" style="font-size: 0.75rem;">মোট খরচ</small>
                            <span class="fw-bold text-success fs-5">৳{{ number_format($customer->total_spent, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Detailed Tabs & Information --}}
        <div class="col-lg-8 col-xl-9">
            {{-- Personal & Family Information Card --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-card-heading text-success me-2"></i> ব্যক্তিগত ও পারিবারিক তথ্যাবলী
                    </h5>
                    <span class="badge bg-light text-dark border">সর্বশেষ আবেদন অনুযায়ী</span>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light h-100">
                                <span class="text-muted small d-block mb-1">আবেদনকারীর পূর্ণ নাম (বাংলা)</span>
                                <strong class="text-dark fs-6">{{ $customer->name_bn }}</strong>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light h-100">
                                <span class="text-muted small d-block mb-1">আবেদনকারীর পূর্ণ নাম (ইংরেজি)</span>
                                <strong class="text-dark fs-6">{{ $customer->name_en ?: 'প্রদান করা হয়নি' }}</strong>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light h-100">
                                <span class="text-muted small d-block mb-1">পিতার নাম (বাংলা / ইংরেজি)</span>
                                <strong class="text-dark d-block">{{ $customer->father_name_bn ?: 'প্রদান করা হয়নি' }}</strong>
                                @if($customer->father_name_en)
                                    <small class="text-muted">{{ $customer->father_name_en }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light h-100">
                                <span class="text-muted small d-block mb-1">মাতার নাম (বাংলা / ইংরেজি)</span>
                                <strong class="text-dark d-block">{{ $customer->mother_name_bn ?: 'প্রদান করা হয়নি' }}</strong>
                                @if($customer->mother_name_en)
                                    <small class="text-muted">{{ $customer->mother_name_en }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light h-100">
                                <span class="text-muted small d-block mb-1">স্বামী / স্ত্রীর নাম (যদি থাকে)</span>
                                <strong class="text-dark d-block">{{ $customer->spouse_name_bn ?: ($customer->spouse_name_en ?: 'প্রযোজ্য নয় / দেওয়া হয়নি') }}</strong>
                                @if($customer->spouse_name_en && $customer->spouse_name_bn)
                                    <small class="text-muted">{{ $customer->spouse_name_en }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light h-100">
                                <span class="text-muted small d-block mb-1">জন্ম তারিখ (DOB)</span>
                                <strong class="text-dark fs-6">
                                    <i class="bi bi-calendar-event text-primary me-1"></i>
                                    {{ $customer->dob ?: 'প্রদান করা হয়নি' }}
                                </strong>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light h-100">
                                <span class="text-muted small d-block mb-1">এনআইডি / জন্ম নিবন্ধন নম্বর</span>
                                <strong class="text-dark font-monospace fs-6">
                                    <i class="bi bi-upc-scan text-success me-1"></i>
                                    {{ $customer->nid_birth_reg_no ?: 'প্রদান করা হয়নি' }}
                                </strong>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-3 bg-light h-100">
                                <span class="text-muted small d-block mb-1">বর্তমান ঠিকানা</span>
                                <span class="text-dark">{{ $customer->address ?: 'প্রদান করা হয়নি' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Additional fields from latest application data if any --}}
                    @php
                        $standardKeys = [
                            'applicant_name_bn', 'applicant_name_en', 'applicant_photo',
                            'father_name_bn', 'father_name_en', 'father_name',
                            'mother_name_bn', 'mother_name_en', 'mother_name',
                            'spouse_name_bn', 'spouse_name_en',
                            'nid_birth_reg_no', 'dob', 'gender'
                        ];
                        $latestAppData = $customer->latest_order?->application_data ?? [];
                        $extraFields = array_filter($latestAppData, function ($k) use ($standardKeys) {
                            return !in_array($k, $standardKeys);
                        }, ARRAY_FILTER_USE_KEY);
                    @endphp

                    @if(!empty($extraFields))
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle text-primary me-2"></i>অন্যান্য বিশেষ আবেদন ক্ষেত্রসমূহ</h6>
                            <div class="row g-2">
                                @foreach($extraFields as $k => $val)
                                    @continue(is_array($val) || empty($val))
                                    <div class="col-sm-6 col-md-4">
                                        <div class="border rounded-3 p-2 bg-white">
                                            <small class="text-muted d-block">{{ $customer->latest_order->getFieldLabel($k) }}</small>
                                            <span class="fw-semibold text-dark">{{ $val }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- All Customer Documents & Attachments --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-paperclip text-success me-2"></i> সংযুক্ত সকল ফাইল ও ডকুমেন্টস (Attachments)
                    </h5>
                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1">
                        {{ $customer->attachments->count() }} টি ফাইল
                    </span>
                </div>
                <div class="card-body p-4">
                    @if($customer->attachments->isNotEmpty())
                        <div class="row g-3">
                            @foreach($customer->attachments as $att)
                                @php
                                    $isImage = in_array(strtolower(pathinfo($att->file_name, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    $isPdf = strtolower(pathinfo($att->file_name, PATHINFO_EXTENSION)) === 'pdf';
                                    $fileUrl = asset('storage/' . $att->file_path);
                                @endphp
                                <div class="col-sm-6 col-md-4 col-xl-3">
                                    <div class="card h-100 border rounded-3 p-2 bg-light shadow-sm text-center">
                                        @if($isImage)
                                            <a href="{{ $fileUrl }}" target="_blank" class="d-block mb-2">
                                                <img src="{{ $fileUrl }}" alt="{{ $att->file_name }}"
                                                     class="rounded-2 border img-fluid"
                                                     style="height: 100px; width: 100%; object-fit: cover;">
                                            </a>
                                        @elseif($isPdf)
                                            <div class="d-flex align-items-center justify-content-center text-danger mb-2" style="height: 100px; font-size: 3rem;">
                                                <i class="bi bi-file-earmark-pdf-fill"></i>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center justify-content-center text-secondary mb-2" style="height: 100px; font-size: 3rem;">
                                                <i class="bi bi-file-earmark-text-fill"></i>
                                            </div>
                                        @endif

                                        <small class="fw-bold text-dark text-truncate d-block mb-1" title="{{ $att->file_name }}">
                                            {{ $att->file_name }}
                                        </small>

                                        <div class="text-muted small mb-2" style="font-size: 0.72rem;">
                                            <span>{{ number_format(($att->file_size ?? 0) / 1024, 1) }} KB</span> • 
                                            <span>{{ $att->created_at?->format('d M, Y') }}</span>
                                        </div>

                                        <div class="mt-auto d-flex justify-content-center gap-1">
                                            <a href="{{ $fileUrl }}" target="_blank" class="btn btn-outline-success btn-sm rounded-pill px-2" title="দেখুন">
                                                <i class="bi bi-eye"></i> দেখুন
                                            </a>
                                            <a href="{{ $fileUrl }}" download class="btn btn-outline-primary btn-sm rounded-pill px-2" title="ডাউনলোড করুন">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-folder-x fs-1 d-block mb-2 text-secondary opacity-50"></i>
                            <p class="mb-0">এই গ্রাহকের কোনো অতিরিক্ত ফাইল বা ডকুমেন্টস পাওয়া যায়নি।</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- All Customer Orders / Applications History Table --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-clock-history text-success me-2"></i> আবেদনের সম্পূর্ণ ইতিহাস (Order History)
                    </h5>
                    <span class="badge bg-primary rounded-pill px-3 py-1">{{ $orders->count() }} টি আবেদন</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light border-bottom text-muted small text-uppercase" style="font-size: 0.78rem;">
                                <tr>
                                    <th class="ps-4 py-3">সেবার নাম</th>
                                    <th class="py-3">আবেদনের তারিখ</th>
                                    <th class="py-3">নির্ধারিত ফি</th>
                                    <th class="py-3 text-center">পেমেন্ট অবস্থা</th>
                                    <th class="py-3 text-center">কাজের স্ট্যাটাস</th>
                                    <th class="text-end pe-4 py-3">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $ord)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <strong class="text-dark d-block fs-6">{{ $ord->service_title }}</strong>
                                            @if($ord->service)
                                                <small class="text-muted">{{ $ord->service->category?->name ?? '' }}</small>
                                            @endif
                                        </td>
                                        <td class="py-3">
                                            <span class="text-dark small d-block">{{ $ord->created_at->format('d M, Y') }}</span>
                                            <small class="text-muted">{{ $ord->created_at->format('h:i A') }}</small>
                                        </td>
                                        <td class="py-3">
                                            <strong class="text-success fs-6">৳{{ number_format($ord->total_fee, 2) }}</strong>
                                        </td>
                                        <td class="py-3 text-center">
                                            @if($ord->payment_status === 'verified' || $ord->payment_status === 'paid')
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-semibold" style="font-size: 0.78rem;">
                                                    <i class="bi bi-check-circle-fill me-1"></i>পরিশোধিত
                                                </span>
                                            @elseif($ord->payment_status === 'rejected' || $ord->payment_status === 'failed')
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1.5 fw-semibold" style="font-size: 0.78rem;">
                                                    <i class="bi bi-x-circle-fill me-1"></i>ব্যর্থ
                                                </span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-3 py-1.5 fw-semibold" style="font-size: 0.78rem;">
                                                    <i class="bi bi-hourglass-split me-1"></i>অপেক্ষমাণ
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 text-center">
                                            @if($ord->status === 'completed')
                                                <span class="badge bg-success rounded-pill px-3 py-1.5 fw-semibold" style="font-size: 0.78rem;">সম্পন্ন</span>
                                            @elseif($ord->status === 'processing')
                                                <span class="badge bg-primary rounded-pill px-3 py-1.5 fw-semibold" style="font-size: 0.78rem;">প্রক্রিয়াধীন</span>
                                            @elseif($ord->status === 'cancelled')
                                                <span class="badge bg-danger rounded-pill px-3 py-1.5 fw-semibold" style="font-size: 0.78rem;">বাতিল</span>
                                            @else
                                                <span class="badge bg-secondary rounded-pill px-3 py-1.5 fw-semibold" style="font-size: 0.78rem;">নতুন জমা</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4 py-3">
                                            <a href="{{ route('admin.orders.show', $ord) }}" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1">
                                                <i class="bi bi-eye-fill me-1"></i> বিস্তারিত
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

