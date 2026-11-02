<div class="row g-4">
    {{-- Basic Information --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
                <i class="bi bi-info-circle text-primary me-2"></i> প্রাথমিক তথ্য (Basic Details)
            </h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">পেইজ শিরোনাম (Page Title) <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="pageTitle" class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title', $upazila->title ?? '') }}" placeholder="যেমন: রামগতি উপজেলা পরিচিতি" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">ইউআরএল স্ল্যাগ (Slug)</label>
                    <input type="text" name="slug" id="pageSlug" class="form-control @error('slug') is-invalid @enderror"
                        value="{{ old('slug', $upazila->slug ?? '') }}" placeholder="যেমন: ramgati-upazila-info (ফাঁকা রাখলে স্বয়ংক্রিয় তৈরি হবে)">
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">পেইজ ইউআরএল ফরম্যাট: <code>/upazila/{slug}</code></small>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">প্রদর্শন ক্রম (Sort Order)</label>
                    <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror"
                        value="{{ old('sort_order', $upazila->sort_order ?? 0) }}" min="0">
                    <small class="text-muted">হেডার ড্রপডাউনে প্রদর্শনের সিরিয়াল নির্ধারণে ব্যবহার করা হয়</small>
                    @error('sort_order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 d-flex align-items-center pt-md-4">
                    <div class="form-check form-switch fs-5">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActiveCheck" value="1"
                            {{ old('is_active', $upazila->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fs-6 fw-semibold text-dark ms-2" for="isActiveCheck">
                            ওয়েবসাইটে প্রদর্শন করুন (Active / Publish)
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Banner & Images Section --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
                <i class="bi bi-images text-success me-2"></i> ব্যানার ও গ্যালারি ছবিসমূহ (Images)
            </h5>
            
            {{-- Banner Image --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">ব্যানার ছবি (Banner Image)</label>
                <input type="file" name="banner_image" class="form-control @error('banner_image') is-invalid @enderror" accept="image/*">
                <small class="text-muted">পেইজের শীর্ষে হিরো ব্যানার হিসেবে প্রদর্শিত হবে। রিকমেন্ডেড সাইজ: ১২০০x৪০০ পিক্সেল (Max 5MB)</small>
                @error('banner_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                @if(!empty($upazila->banner_image))
                    <div class="mt-2 p-2 border rounded-3 bg-light d-inline-block">
                        <div class="small text-muted mb-1">বর্তমান ব্যানার:</div>
                        <img src="{{ asset('storage/' . $upazila->banner_image) }}" alt="Banner" class="rounded object-fit-cover" style="max-height: 120px; max-width: 100%;">
                    </div>
                @endif
            </div>

            <div class="row g-3">
                {{-- Image 1 --}}
                <div class="col-md-4">
                    <div class="border rounded-3 p-3 bg-light h-100">
                        <label class="form-label fw-semibold text-dark"><i class="bi bi-card-image me-1 text-primary"></i> ছবি ১ (Image 1)</label>
                        <input type="file" name="image_1" class="form-control form-control-sm @error('image_1') is-invalid @enderror" accept="image/*">
                        <small class="text-muted d-block mt-1">ডিটেইলস ১ এর সাথে সাজানো থাকবে</small>
                        @error('image_1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if(!empty($upazila->image_1))
                            <div class="mt-2 text-center">
                                <img src="{{ asset('storage/' . $upazila->image_1) }}" alt="Image 1" class="rounded shadow-sm object-fit-cover w-100" style="height: 120px;">
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Image 2 --}}
                <div class="col-md-4">
                    <div class="border rounded-3 p-3 bg-light h-100">
                        <label class="form-label fw-semibold text-dark"><i class="bi bi-card-image me-1 text-primary"></i> ছবি ২ (Image 2)</label>
                        <input type="file" name="image_2" class="form-control form-control-sm @error('image_2') is-invalid @enderror" accept="image/*">
                        <small class="text-muted d-block mt-1">ডিটেইলস ২ এর সাথে সাজানো থাকবে</small>
                        @error('image_2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if(!empty($upazila->image_2))
                            <div class="mt-2 text-center">
                                <img src="{{ asset('storage/' . $upazila->image_2) }}" alt="Image 2" class="rounded shadow-sm object-fit-cover w-100" style="height: 120px;">
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Image 3 --}}
                <div class="col-md-4">
                    <div class="border rounded-3 p-3 bg-light h-100">
                        <label class="form-label fw-semibold text-dark"><i class="bi bi-card-image me-1 text-primary"></i> ছবি ৩ (Image 3)</label>
                        <input type="file" name="image_3" class="form-control form-control-sm @error('image_3') is-invalid @enderror" accept="image/*">
                        <small class="text-muted d-block mt-1">ডিটেইলস ৩ এর সাথে সাজানো থাকবে</small>
                        @error('image_3')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if(!empty($upazila->image_3))
                            <div class="mt-2 text-center">
                                <img src="{{ asset('storage/' . $upazila->image_3) }}" alt="Image 3" class="rounded shadow-sm object-fit-cover w-100" style="height: 120px;">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Details Sections (3 Text Editors) --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold mb-3 text-dark border-bottom pb-2">
                <i class="bi bi-textarea-t text-warning me-2"></i> ৩টি বিস্তারিত বিবরণ সেকশন (Details 1, 2, 3)
            </h5>

            {{-- Detail 1 --}}
            <div class="mb-4">
                <label class="form-label fw-bold text-dark fs-6">
                    <span class="badge bg-primary me-2">সেকশন ১</span> বিস্তারিত বিবরণ ১ (Detail 1)
                </label>
                <textarea name="detail_1" id="detail1Editor" class="form-control upazila-editor" rows="6">{{ old('detail_1', $upazila->detail_1 ?? '') }}</textarea>
                @error('detail_1')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Detail 2 --}}
            <div class="mb-4">
                <label class="form-label fw-bold text-dark fs-6">
                    <span class="badge bg-primary me-2">সেকশন ২</span> বিস্তারিত বিবরণ ২ (Detail 2)
                </label>
                <textarea name="detail_2" id="detail2Editor" class="form-control upazila-editor" rows="6">{{ old('detail_2', $upazila->detail_2 ?? '') }}</textarea>
                @error('detail_2')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Detail 3 --}}
            <div class="mb-2">
                <label class="form-label fw-bold text-dark fs-6">
                    <span class="badge bg-primary me-2">সেকশন ৩</span> বিস্তারিত বিবরণ ৩ (Detail 3)
                </label>
                <textarea name="detail_3" id="detail3Editor" class="form-control upazila-editor" rows="6">{{ old('detail_3', $upazila->detail_3 ?? '') }}</textarea>
                @error('detail_3')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mt-4">
    <a href="{{ route('admin.upazilas.index') }}" class="btn btn-outline-secondary px-4">
        <i class="bi bi-arrow-left me-1"></i> ফিরে যান
    </a>
    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
        <i class="bi bi-check-lg me-1"></i> সংরক্ষণ করুন (Save Page)
    </button>
</div>

