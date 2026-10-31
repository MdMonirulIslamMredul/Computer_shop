<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">সেবার নাম (Title) <span class="text-danger">*</span></label>
        <input name="title" class="form-control" value="{{ old('title', $service->title ?? '') }}" placeholder="যেমনঃ সরকারি চাকরির আবেদন" required>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">স্লাগ (Slug)</label>
        <input name="slug" class="form-control" value="{{ old('slug', $service->slug ?? '') }}" placeholder="খালি রাখলে স্বয়ংক্রিয় তৈরি হবে">
    </div>

    <div class="col-md-3">
        <label class="form-label fw-semibold">সেবা ফি / সার্ভিস চার্জ (৳)</label>
        <input name="service_fee" type="number" step="0.01" class="form-control" value="{{ old('service_fee', $service->service_fee ?? 0) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">সরকারি ফি (যদি থাকে ৳)</label>
        <input name="govt_fee" type="number" step="0.01" class="form-control" value="{{ old('govt_fee', $service->govt_fee ?? 0) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">ডেলিভারি সময় (Time)</label>
        <input name="delivery_time" class="form-control" value="{{ old('delivery_time', $service->delivery_time ?? '১-২ কর্মদিবস') }}" placeholder="উদাঃ ২-৬ ঘণ্টা">
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">ক্রম নম্বর (Sort Order)</label>
        <input name="sort_order" type="number" class="form-control" value="{{ old('sort_order', $service->sort_order ?? 0) }}">
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">বুটস্ট্র্যাপ আইকন ক্লাস</label>
        <input name="icon" class="form-control" value="{{ old('icon', $service->icon ?? 'bi-file-earmark-text') }}" placeholder="bi-briefcase-fill">
    </div>

    <div class="col-md-8 d-flex align-items-center gap-4 pt-4">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_online_orderable" id="is_online_orderable" value="1"
                {{ old('is_online_orderable', $service->is_online_orderable ?? true) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="is_online_orderable">অনলাইন অর্ডার নেওয়া যাবে</label>
        </div>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1"
                {{ old('is_featured', $service->is_featured ?? false) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="is_featured">ফিচার্ড সেবা</label>
        </div>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="status" id="status" value="1"
                {{ old('status', $service->status ?? true) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="status">সক্রিয় (Active)</label>
        </div>
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold">সংক্ষিপ্ত বিবরণ (Short Description)</label>
        <input name="short_description" class="form-control" value="{{ old('short_description', $service->short_description ?? '') }}" placeholder="১-২ লাইনে সেবার সারসংক্ষেপ">
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold">প্রয়োজনীয় কাগজপত্র (Required Documents Checklist)</label>
        <textarea name="required_documents" class="form-control" rows="3" placeholder="১. পাসপোর্ট সাইজ ছবি&#10;২. স্বাক্ষর&#10;৩. শিক্ষাগত সনদ">{{ old('required_documents', $service->required_documents ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold">বিস্তারিত বিবরণ (Full Description)</label>
        <textarea name="description" class="form-control" rows="5">{{ old('description', $service->description ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold">সেবার ছবি (Image)</label>
        <input type="file" name="image" class="form-control">
        @if(!empty($service->image))
            <div class="mt-2">
                <img src="{{ asset('storage/' . $service->image) }}" alt="Preview" height="80" class="rounded border">
            </div>
        @endif
    </div>
</div>
