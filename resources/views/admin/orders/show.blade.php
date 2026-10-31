@extends('admin.layouts.app')

@section('title', 'অর্ডার বিস্তারিত: ' . $order->tracking_id)

@push('styles')
<style>
    .doc-preview-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .doc-preview-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08) !important;
    }
    .doc-thumb-box:hover .doc-hover-overlay {
        opacity: 1 !important;
    }
    .transition {
        transition: all 0.2s ease;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">ড্যাশবোর্ড</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">কাজের অর্ডার</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $order->tracking_id }}</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center gap-2">
                <h3 class="fw-bold text-dark mb-0 font-monospace">{{ $order->tracking_id }}</h3>
                <span class="badge {{ $order->status_badge }} fs-6 px-3 py-1">{{ $order->status_label }}</span>
                <span class="badge {{ $order->payment_status_badge }} fs-6 px-3 py-1">{{ $order->payment_status_label }}</span>
            </div>
        </div>

        <div class="d-flex gap-2 mt-3 mt-md-0">
            <a href="{{ route('orders.track', ['search' => $order->tracking_id]) }}" target="_blank" class="btn btn-outline-info">
                <i class="bi bi-eye me-1"></i> পাবলিক ভিউ
            </a>
            <a href="{{ route('admin.orders.print', $order->id) }}" target="_blank" class="btn btn-outline-secondary">
                <i class="bi bi-printer me-1"></i> রসিদ প্রিন্ট
            </a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark">
                <i class="bi bi-arrow-left me-1"></i> তালিকায় ফিরে যান
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left Column: Customer & Application Data --}}
        <div class="col-lg-8">
            {{-- Customer Information Card --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-person-circle text-primary me-2"></i> গ্রাহকের তথ্য
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="tel:{{ $order->customer_phone }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                            <i class="bi bi-telephone-fill me-1"></i> কল করুন
                        </a>
                        @php
                            $waMsg = urlencode("আসসালামু আলাইকুম {$order->customer_name}, সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার থেকে আপনার অর্ডার #{$order->tracking_id} এর বিষয়ে যোগাযোগ করা হচ্ছে।");
                        @endphp
                        <a href="https://wa.me/88{{ preg_replace('/[^0-9]/', '', $order->customer_phone) }}?text={{ $waMsg }}" target="_blank" class="btn btn-sm btn-success rounded-pill px-3">
                            <i class="bi bi-whatsapp me-1"></i> হোয়াটসঅ্যাপ
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">নাম:</small>
                            <strong class="text-dark fs-6">{{ $order->customer_name }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">মোবাইল নম্বর:</small>
                            <strong class="text-dark fs-6">{{ $order->customer_phone }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">ইমেইল:</small>
                            <span class="text-dark">{{ $order->customer_email ?? 'প্রদান করা হয়নি' }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">ঠিকানা:</small>
                            <span class="text-dark">{{ $order->customer_address ?? 'প্রদান করা হয়নি' }}</span>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">অর্ডার সাবমিটের তারিখ ও সময়:</small>
                            <span class="text-dark">{{ $order->created_at->format('d M, Y - h:i A') }} ({{ $order->created_at->diffForHumans() }})</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Application Data Entry Details --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-card-text text-success me-2"></i> আবেদনের তথ্য ও ডাটা এন্ট্রি
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <small class="text-muted d-block">নির্বাচিত সেবা:</small>
                        <h5 class="fw-bold text-success">{{ $order->service_title }}</h5>
                    </div>

                    @if(!empty($order->application_data))
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle mb-0">
                                <tbody>
                                    @foreach($order->application_data as $key => $val)
                                        <tr>
                                            <th class="bg-light text-dark" style="width: 35%;">
                                                {{ \App\Models\ServiceOrder::getFieldLabel($key) }}
                                            </th>
                                            <td class="text-dark fw-semibold">
                                                @if($key === 'applicant_photo')
                                                    <div class="d-flex align-items-center gap-3">
                                                        <img src="{{ asset('storage/' . $val) }}" alt="Applicant Photo" class="img-thumbnail rounded-3 shadow-sm" style="max-height: 85px; cursor: pointer;" onclick="openDocPreview('{{ asset('storage/' . $val) }}', 'আবেদনকারীর ছবি (User Photo)', 'photo')">
                                                        <div class="d-flex flex-column gap-2">
                                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="openDocPreview('{{ asset('storage/' . $val) }}', 'আবেদনকারীর ছবি (User Photo)', 'photo')">
                                                                <i class="bi bi-window-fullscreen me-1"></i> উইন্ডোতে প্রিভিউ
                                                            </button>
                                                            <a href="{{ asset('storage/' . $val) }}" download class="btn btn-sm btn-light border rounded-pill px-3">
                                                                <i class="bi bi-download me-1"></i> ডাউনলোড
                                                            </a>
                                                        </div>
                                                    </div>
                                                @else
                                                    {{ $val }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-light border mb-0 text-muted">
                            আবেদনের জন্য কোনো অতিরিক্ত ডাটা এন্ট্রি ফিল্ড যুক্ত করা হয়নি।
                        </div>
                    @endif

                    @if($order->customer_notes)
                        <div class="mt-4 p-3 bg-light rounded-3 border">
                            <strong class="text-dark d-block mb-1"><i class="bi bi-chat-left-quote text-secondary me-1"></i> গ্রাহকের অতিরিক্ত নোট:</strong>
                            <p class="text-secondary mb-0" style="white-space: pre-line;">{{ $order->customer_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Customer Uploaded Documents / Attachments --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-paperclip text-info me-2"></i> গ্রাহকের সংযুক্ত ফাইলসমূহ ({{ $order->attachments->count() }}টি)
                    </h5>
                    <span class="badge bg-light text-secondary border px-3 py-1">মোট {{ $order->attachments->count() }}টি ফাইল</span>
                </div>
                <div class="card-body p-4">
                    @if($order->attachments->count() > 0)
                        <div class="row g-3">
                            @foreach($order->attachments as $att)
                                @php
                                    $ext = strtolower(pathinfo($att->file_name, PATHINFO_EXTENSION));
                                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp', 'svg']) || $att->file_type === 'photo';
                                    $isPdf = $ext === 'pdf' || $att->file_type === 'pdf';
                                    $fileUrl = asset('storage/' . $att->file_path);
                                @endphp
                                <div class="col-md-6">
                                    <div class="card border rounded-3 overflow-hidden shadow-sm h-100 doc-preview-card">
                                        {{-- Visual Preview Thumbnail Box --}}
                                        <div class="position-relative bg-light d-flex align-items-center justify-content-center doc-thumb-box border-bottom" style="height: 155px; cursor: pointer; background: #f8fafc;" onclick="openDocPreview('{{ $fileUrl }}', '{{ addslashes($att->file_name) }}', '{{ $isImage ? 'photo' : ($isPdf ? 'pdf' : 'document') }}')">
                                            @if($isImage)
                                                <img src="{{ $fileUrl }}" alt="{{ $att->file_name }}" class="w-100 h-100 object-fit-contain p-2" loading="lazy">
                                                <div class="doc-hover-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 text-white opacity-0 transition">
                                                    <span class="badge bg-white text-dark shadow-sm px-3 py-2 rounded-pill"><i class="bi bi-window-fullscreen text-primary me-1"></i> উইন্ডো প্রিভিউ</span>
                                                </div>
                                            @elseif($isPdf)
                                                <div class="text-center p-3">
                                                    <i class="bi bi-file-earmark-pdf-fill text-danger" style="font-size: 3.5rem;"></i>
                                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle d-block mt-2">PDF ডকুমেন্ট</span>
                                                </div>
                                                <div class="doc-hover-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 text-white opacity-0 transition">
                                                    <span class="badge bg-white text-dark shadow-sm px-3 py-2 rounded-pill"><i class="bi bi-window-fullscreen text-primary me-1"></i> উইন্ডো প্রিভিউ</span>
                                                </div>
                                            @else
                                                <div class="text-center p-3">
                                                    <i class="bi bi-file-earmark-word-fill text-info" style="font-size: 3.5rem;"></i>
                                                    <span class="badge bg-info bg-opacity-10 text-info border border-info-subtle d-block mt-2">ডকুমেন্ট ফাইল</span>
                                                </div>
                                                <div class="doc-hover-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 text-white opacity-0 transition">
                                                    <span class="badge bg-white text-dark shadow-sm px-3 py-2 rounded-pill"><i class="bi bi-window-fullscreen text-primary me-1"></i> উইন্ডো প্রিভিউ</span>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Card Content & Buttons --}}
                                        <div class="card-body p-3 d-flex flex-column justify-content-between">
                                            <div class="mb-2">
                                                <strong class="d-block text-dark text-truncate small" title="{{ $att->file_name }}">
                                                    {{ $att->file_name }}
                                                </strong>
                                                <div class="d-flex align-items-center gap-2 mt-1">
                                                    <span class="badge bg-light text-secondary border">{{ $att->formatted_size }}</span>
                                                    <span class="badge {{ $isImage ? 'bg-success-subtle text-success border border-success-subtle' : ($isPdf ? 'bg-danger-subtle text-danger border border-danger-subtle' : 'bg-info-subtle text-info border border-info-subtle') }}">
                                                        {{ strtoupper($ext ?: 'FILE') }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="d-flex gap-2 mt-2 pt-2 border-top">
                                                <button type="button" class="btn btn-sm btn-primary rounded-pill flex-fill d-flex align-items-center justify-content-center gap-1 py-1" onclick="openDocPreview('{{ $fileUrl }}', '{{ addslashes($att->file_name) }}', '{{ $isImage ? 'photo' : ($isPdf ? 'pdf' : 'document') }}')">
                                                    <i class="bi bi-window-fullscreen"></i> উইন্ডো প্রিভিউ
                                                </button>
                                                <a href="{{ $fileUrl }}" class="btn btn-sm btn-outline-secondary rounded-pill flex-fill d-flex align-items-center justify-content-center gap-1 py-1" download title="ডাউনলোড করুন">
                                                    <i class="bi bi-download"></i> ডাউনলোড
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">গ্রাহক কোনো ফাইল সংযুক্ত করেননি।</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column: Payment, Status Controls, and Upload Completed PDF --}}
        <div class="col-lg-4">
            {{-- 1. Payment Verification Card --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-wallet2 text-success me-2"></i> পেমেন্ট তথ্য
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">সেবা ফি:</span>
                        <strong>৳{{ number_format($order->service_fee, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">সরকারি ফি:</span>
                        <strong>৳{{ number_format($order->govt_fee, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3 border-top pt-2">
                        <span class="fs-6 fw-bold">সর্বমোট ফি:</span>
                        <span class="fs-5 fw-bolder text-success">৳{{ number_format($order->total_fee, 2) }}</span>
                    </div>

                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <div class="mb-2">
                            <span class="text-muted small d-block">পেমেন্ট মেথড:</span>
                            <strong class="text-dark">{{ $order->payment_method_name }}</strong>
                        </div>
                        @if($order->payment_number)
                            <div class="mb-2">
                                <span class="text-muted small d-block">প্রেরক নম্বর:</span>
                                <strong class="text-dark font-monospace">{{ $order->payment_number }}</strong>
                            </div>
                        @endif
                        @if($order->transaction_id)
                            <div class="mb-2">
                                <span class="text-muted small d-block">Transaction ID (TrxID):</span>
                                <strong class="text-primary font-monospace fs-6">{{ $order->transaction_id }}</strong>
                            </div>
                        @endif
                        <div>
                            <span class="text-muted small d-block">পেমেন্ট স্ট্যাটাস:</span>
                            <span class="badge {{ $order->payment_status_badge }}">{{ $order->payment_status_label }}</span>
                        </div>
                    </div>

                    @if($order->payment_status !== 'verified')
                        <form action="{{ route('admin.orders.verify-payment', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 py-2 fw-semibold rounded-pill">
                                <i class="bi bi-check2-circle me-1"></i> পেমেন্ট নিশ্চিত করুন (Verify)
                            </button>
                        </form>
                    @else
                        <div class="text-center text-success small fw-semibold">
                            <i class="bi bi-shield-check-fill me-1"></i> পেমেন্ট যাচাইকৃত
                        </div>
                    @endif
                </div>
            </div>

            {{-- 2. Status & Progress Management Form --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-sliders text-primary me-2"></i> কাজের অগ্রগতি নিয়ন্ত্রণ
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">অর্ডারের অগ্রগতি স্ট্যাটাস <span class="text-danger">*</span></label>
                            <select name="order_status" class="form-select" required>
                                <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>১. অপেক্ষমাণ (Pending)</option>
                                <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>২. আবেদন প্রক্রিয়াধীন (Processing)</option>
                                <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>৩. কাজ সম্পন্ন / ফাইল প্রস্তুত (Completed)</option>
                                <option value="delivered" {{ $order->order_status === 'delivered' ? 'selected' : '' }}>৪. ডেলিভারি সম্পন্ন (Delivered)</option>
                                <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>৫. বাতিল (Cancelled)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">পেমেন্ট অবস্থা <span class="text-danger">*</span></label>
                            <select name="payment_status" class="form-select" required>
                                <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>অপেক্ষমাণ (Pending)</option>
                                <option value="verified" {{ $order->payment_status === 'verified' ? 'selected' : '' }}>যাচাইকৃত (Verified)</option>
                                <option value="rejected" {{ $order->payment_status === 'rejected' ? 'selected' : '' }}>বাতিল (Rejected)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">অপারেটর মন্তব্য / গ্রাহকের জন্য আপডেট নোট</label>
                            <textarea name="admin_notes" class="form-control" rows="3" placeholder="এই নোটটি গ্রাহক তার ট্র্যাকিং পেজে দেখতে পাবেন...">{{ old('admin_notes', $order->admin_notes) }}</textarea>
                            <small class="text-muted">উদাঃ 'আপনার আবেদন সম্পন্ন হয়েছে, কপি ডাউনলোড করুন' বা 'ছবি ঝাপসা এসেছে, নতুন ছবি পাঠান'</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold rounded-pill">
                            <i class="bi bi-save me-1"></i> স্ট্যাটাস আপডেট করুন
                        </button>
                    </form>
                </div>
            </div>

            {{-- 3. Completed Application File Upload (Core Requirement) --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-cloud-arrow-up text-success me-2"></i> সম্পন্ন আবেদন কপি / স্লিপ আপলোড
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($order->completed_file_path)
                        <div class="alert alert-success border-0 rounded-3 mb-3">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <div>
                                    <strong class="d-block text-success"><i class="bi bi-check-circle-fill me-1"></i> ফাইল আপলোড করা আছে</strong>
                                    <small class="text-muted">গ্রাহক এটি ট্র্যাকিং পেজ থেকে ডাউনলোড করতে পারছেন।</small>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3" onclick="openDocPreview('{{ asset('storage/' . $order->completed_file_path) }}', 'সম্পূর্ণ আবেদন ফাইল / স্লিপ', '{{ str_ends_with(strtolower($order->completed_file_path), '.pdf') ? 'pdf' : 'photo' }}')">
                                        <i class="bi bi-window-fullscreen me-1"></i> উইন্ডো প্রিভিউ
                                    </button>
                                    <a href="{{ route('orders.download', $order->tracking_id) }}" class="btn btn-sm btn-success rounded-pill px-3" target="_blank">
                                        <i class="bi bi-download me-1"></i> ডাউনলোড
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('admin.orders.upload-file', $order->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">ফাইল নির্বাচন করুন (PDF বা ছবি)</label>
                            <input type="file" name="completed_file" class="form-control" accept=".pdf,image/*,.doc,.docx" required>
                            <small class="text-muted">আবেদন কপি, প্রবেশপত্র, বা কনফার্মেশন স্লিপ আপলোড করুন।</small>
                        </div>
                        <button type="submit" class="btn btn-success w-100 py-2 fw-semibold rounded-pill">
                            <i class="bi bi-upload me-1"></i> সম্পূর্ণ ফাইল আপলোড করুন
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Document In-Window Preview Modal --}}
<div class="modal fade" id="docPreviewModal" tabindex="-1" aria-labelledby="docPreviewModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header py-3 px-4 bg-light border-bottom">
                <div class="d-flex align-items-center gap-2 text-truncate" style="max-width: 65%;">
                    <i class="bi bi-file-earmark-text text-primary fs-4 flex-shrink-0"></i>
                    <h5 class="modal-title fw-bold text-dark text-truncate mb-0" id="docPreviewModalTitle">ডকুমেন্ট প্রিভিউ</h5>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" id="docPreviewNewWindowBtn" class="btn btn-sm btn-outline-primary rounded-pill px-3" title="নতুন উইন্ডোতে খুলুন">
                        <i class="bi bi-box-arrow-up-right me-1"></i> নতুন উইন্ডো
                    </button>
                    <a id="docPreviewDownloadBtn" href="#" download class="btn btn-sm btn-success rounded-pill px-3" title="ডাউনলোড করুন">
                        <i class="bi bi-download me-1"></i> ডাউনলোড
                    </a>
                    <button type="button" class="btn-close ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0 bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center" style="min-height: 480px;">
                {{-- 1. Image Preview Container --}}
                <div id="docPreviewImgContainer" class="w-100 p-3 text-center" style="display: none;">
                    <img id="docPreviewImg" src="" alt="Document Preview" class="img-fluid rounded-3 shadow" style="max-height: 76vh; max-width: 100%; object-fit: contain;">
                </div>

                {{-- 2. PDF Viewer Container --}}
                <div id="docPreviewPdfContainer" class="w-100 h-100" style="display: none; height: 78vh;">
                    <iframe id="docPreviewPdf" src="" style="width: 100%; height: 78vh; border: none;"></iframe>
                </div>

                {{-- 3. Fallback / Other Document Container --}}
                <div id="docPreviewOtherContainer" class="text-center p-5 bg-white rounded-4 shadow-sm m-3" style="display: none; max-width: 480px;">
                    <i class="bi bi-file-earmark-arrow-down text-info mb-3 d-block" style="font-size: 4rem;"></i>
                    <h5 class="fw-bold text-dark mb-1" id="docPreviewOtherName">ডকুমেন্ট ফাইল</h5>
                    <p class="text-muted small mb-3">এই ফরম্যাটের ফাইল ব্রাউজারে সরাসরি প্রিভিউ করা যায় না। ফাইলটি দেখতে নিচের বাটনে ক্লিক করে ডাউনলোড অথবা নতুন উইন্ডোতে ওপেন করুন।</p>
                    <div class="d-flex justify-content-center gap-2">
                        <a id="docPreviewOtherDownload" href="#" download class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-download me-1"></i> ফাইলটি ডাউনলোড করুন
                        </a>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2 px-4 bg-light border-top d-flex justify-content-between">
                <small class="text-muted">
                    <i class="bi bi-info-circle me-1"></i> প্রিভিউ জুম করতে আপনার মাউসের স্ক্রল বা ব্রাউজার জুম ব্যবহার করতে পারেন।
                </small>
                <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">
                    বন্ধ করুন
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openDocPreview(fileUrl, fileName, fileType) {
        const modalEl = document.getElementById('docPreviewModal');
        if (!modalEl) return;

        const modalTitle = document.getElementById('docPreviewModalTitle');
        const imgContainer = document.getElementById('docPreviewImgContainer');
        const pdfContainer = document.getElementById('docPreviewPdfContainer');
        const otherContainer = document.getElementById('docPreviewOtherContainer');
        const previewImg = document.getElementById('docPreviewImg');
        const previewPdf = document.getElementById('docPreviewPdf');
        const downloadBtn = document.getElementById('docPreviewDownloadBtn');
        const newWindowBtn = document.getElementById('docPreviewNewWindowBtn');

        if (modalTitle) modalTitle.textContent = fileName;
        if (downloadBtn) {
            downloadBtn.href = fileUrl;
            downloadBtn.setAttribute('download', fileName);
        }
        if (newWindowBtn) {
            newWindowBtn.onclick = function () {
                window.open(fileUrl, '_blank', 'width=1000,height=800,menubar=no,toolbar=no');
            };
        }

        const ext = (fileName || '').split('.').pop().toLowerCase();
        const isImage = fileType === 'photo' || ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp', 'svg'].includes(ext);
        const isPdf = fileType === 'pdf' || ext === 'pdf';

        if (isImage) {
            imgContainer.style.display = 'block';
            pdfContainer.style.display = 'none';
            otherContainer.style.display = 'none';
            previewImg.src = fileUrl;
            previewPdf.src = '';
        } else if (isPdf) {
            imgContainer.style.display = 'none';
            pdfContainer.style.display = 'block';
            otherContainer.style.display = 'none';
            previewImg.src = '';
            previewPdf.src = fileUrl;
        } else {
            imgContainer.style.display = 'none';
            pdfContainer.style.display = 'none';
            otherContainer.style.display = 'block';
            previewImg.src = '';
            previewPdf.src = '';
            const otherNameEl = document.getElementById('docPreviewOtherName');
            const otherDownEl = document.getElementById('docPreviewOtherDownload');
            if (otherNameEl) otherNameEl.textContent = fileName;
            if (otherDownEl) otherDownEl.href = fileUrl;
        }

        const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
        bsModal.show();
    }

    document.getElementById('docPreviewModal')?.addEventListener('hidden.bs.modal', function () {
        const previewPdf = document.getElementById('docPreviewPdf');
        const previewImg = document.getElementById('docPreviewImg');
        if (previewPdf) previewPdf.src = '';
        if (previewImg) previewImg.src = '';
    });
</script>
@endpush

