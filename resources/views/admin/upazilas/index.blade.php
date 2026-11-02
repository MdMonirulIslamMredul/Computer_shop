@extends('admin.layouts.app')

@section('title', 'উপজেলা সম্পর্কিত পেইজ ম্যানেজমেন্ট')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1 text-dark">উপজেলা সম্পর্কিত পেইজ সমূহ</h3>
            <p class="text-muted mb-0">উপজেলা সম্পর্কিত যাবতীয় তথ্য, যোগাযোগ ও দর্শনীয় স্থান সমূহের পেইজ পরিচালনা করুন</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.upazilas.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-circle"></i> নতুন পেইজ যোগ করুন
            </a>
        </div>
    </div>

    {{-- Search Card --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.upazilas.index') }}" method="GET" class="row g-2 align-items-center justify-content-between">
                <div class="col-md-6 col-lg-5">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="পেইজ টাইটেল বা স্ল্যাগ দিয়ে খুঁজুন..." value="{{ $search }}">
                        <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-search"></i></button>
                        @if($search)
                            <a href="{{ route('admin.upazilas.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                        @endif
                    </div>
                </div>
                <div class="col-auto text-muted small">
                    মোট পেইজ: <strong class="text-dark">{{ $upazilas->total() }}</strong> টি
                </div>
            </form>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 70px;">ক্রম</th>
                        <th style="width: 120px;">ব্যানার</th>
                        <th>পেইজ শিরোনাম ও স্ল্যাগ</th>
                        <th class="text-center" style="width: 140px;">সংযুক্ত ছবি</th>
                        <th class="text-center" style="width: 100px;">স্ট্যাটাস</th>
                        <th class="text-end pe-4" style="width: 160px;">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($upazilas as $item)
                        <tr>
                            <td class="ps-4 text-muted fw-bold">#{{ $item->sort_order }}</td>
                            <td>
                                @if($item->banner_image)
                                    <img src="{{ asset('storage/' . $item->banner_image) }}" alt="{{ $item->title }}"
                                        class="rounded-3 shadow-sm object-fit-cover" style="width: 90px; height: 50px;">
                                @else
                                    <div class="rounded-3 bg-light text-muted d-flex align-items-center justify-content-center"
                                        style="width: 90px; height: 50px; font-size: 0.75rem;">
                                        <i class="bi bi-image me-1"></i> নেই
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-dark fs-6">{{ $item->title }}</div>
                                <div class="text-muted small">
                                    <code>/upazila/{{ $item->slug }}</code>
                                    <a href="{{ route('upazila.show', $item->slug) }}" target="_blank" class="ms-1 text-primary text-decoration-none" title="ভিউ পেইজ">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-inline-flex gap-1">
                                    @for($i = 1; $i <= 3; $i++)
                                        @php $imgField = 'image_' . $i; @endphp
                                        @if($item->{$imgField})
                                            <span class="badge bg-success-subtle text-success border border-success-subtle" title="ছবি {{ $i }} আপলোড করা আছে">
                                                <i class="bi bi-image-fill"></i> {{ $i }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-muted border border-secondary-subtle" title="ছবি {{ $i }} নেই">
                                                -
                                            </span>
                                        @endif
                                    @endfor
                                </div>
                            </td>
                            <td class="text-center">
                                @if($item->is_active)
                                    <span class="badge bg-success px-2 py-1"><i class="bi bi-check-circle me-1"></i> সক্রিয়</span>
                                @else
                                    <span class="badge bg-secondary px-2 py-1"><i class="bi bi-x-circle me-1"></i> নিষ্ক্রিয়</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('admin.upazilas.edit', $item->id) }}" class="btn btn-sm btn-outline-primary" title="সম্পাদনা">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="মুছে ফেলুন"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                {{-- Delete Confirmation Modal --}}
                                <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content text-start">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold text-danger">
                                                    <i class="bi bi-exclamation-triangle-fill me-2"></i> নিশ্চিত করুন
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body py-3">
                                                আপনি কি নিশ্চিত যে '<strong>{{ $item->title }}</strong>' পেইজটি মুছে ফেলতে চান?
                                                <div class="text-muted small mt-2">সংযুক্ত সকল ছবি ও টেক্সট ডাটা সম্পূর্ণ মুছে যাবে।</div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">বাতিল</button>
                                                <form action="{{ route('admin.upazilas.destroy', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">হ্যাঁ, মুছে ফেলুন</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-folder-x fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                কোনো উপজেলা পেইজ পাওয়া যায়নি।
                                <div class="mt-2">
                                    <a href="{{ route('admin.upazilas.create') }}" class="btn btn-sm btn-primary">
                                        প্রথম পেইজ তৈরি করুন
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($upazilas->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $upazilas->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

