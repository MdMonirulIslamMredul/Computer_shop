@extends('admin.layouts.app')

@section('title', 'প্রয়োজনীয় লিংক সম্পাদনা')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1 text-dark">প্রয়োজনীয় লিংক সম্পাদনা</h3>
            <p class="text-muted mb-0">{{ $link->title }}</p>
        </div>
        <a href="{{ route('admin.useful-links.index') }}" class="btn btn-outline-dark">
            <i class="bi bi-arrow-left me-1"></i> তালিকায় ফিরুন
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('admin.useful-links.update', $link->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    {{-- Title --}}
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">শিরোনাম / পোর্টালের নাম <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title', $link->title) }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Category --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">ক্যাটাগরি <span class="text-danger">*</span></label>
                        <input type="text" name="category" list="categoryOptions" class="form-control @error('category') is-invalid @enderror"
                            value="{{ old('category', $link->category) }}" required>
                        <datalist id="categoryOptions">
                            <option value="চাকরির আবেদন">
                            <option value="এনআইডি ও জন্মনিবন্ধন">
                            <option value="ভর্তি ও শিক্ষা">
                            <option value="পাসপোর্ট ও ভিসা">
                            <option value="সরকারি ও অন্যান্য">
                        </datalist>
                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- External URL --}}
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">অফিসিয়াল ওয়েব পোর্টাল লিংক (URL) <span class="text-danger">*</span></label>
                        <input type="url" name="external_url" class="form-control @error('external_url') is-invalid @enderror"
                            value="{{ old('external_url', $link->external_url) }}" required>
                        @error('external_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Linked Service --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">সংযুক্ত সেবা (যদি থাকে)</label>
                        <select name="service_id" class="form-select">
                            <option value="">-- কোনো সেবা সংযুক্ত নেই --</option>
                            @foreach($services as $srv)
                                <option value="{{ $srv->id }}" {{ old('service_id', $link->service_id) == $srv->id ? 'selected' : '' }}>
                                    {{ $srv->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Short Description --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">সংক্ষিপ্ত বিবরণ (কার্ডের উপরে দেখাবে) <span class="text-danger">*</span></label>
                        <textarea name="short_description" class="form-control" rows="2" required>{{ old('short_description', $link->short_description) }}</textarea>
                    </div>

                    {{-- Long Description --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">বিস্তারিত বিবরণ ও নির্দেশিকা (কার্ডে ক্লিক করলে পপআপে দেখাবে)</label>
                        <textarea name="long_description" class="form-control" rows="5">{{ old('long_description', $link->long_description) }}</textarea>
                    </div>

                    {{-- Required Documents --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">আবেদনের জন্য যা যা প্রয়োজন (কাগজপত্র তালিকা)</label>
                        <textarea name="required_documents" class="form-control" rows="3">{{ old('required_documents', $link->required_documents) }}</textarea>
                    </div>

                    {{-- Icon & Sort Order --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">বুটস্ট্র্যাপ আইকন ক্লাস (ঐচ্ছিক)</label>
                        <input type="text" name="icon" class="form-control" value="{{ old('icon', $link->icon) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">ক্রম নম্বর (Sort Order)</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $link->sort_order) }}">
                    </div>

                    <div class="col-md-4 d-flex align-items-center gap-4 pt-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $link->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_featured">হোমপেজে ফিচার্ড লিংক হিসেবে দেখান</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', $link->status) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="status">সক্রিয় (Active)</label>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="col-12 text-end pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-semibold rounded-pill">
                            <i class="bi bi-save me-1"></i> আপডেট করুন
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

