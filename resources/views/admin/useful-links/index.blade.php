@extends('admin.layouts.app')

@section('title', 'প্রয়োজনীয় লিংক ম্যানেজমেন্ট')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1 text-dark">প্রয়োজনীয় লিংক ও আবেদন পোর্টাল সমূহ</h3>
            <p class="text-muted mb-0">গ্রাহকদের জন্য গুরুত্বপূর্ণ ওয়েবসাইট ও আবেদন নির্দেশিকার তালিকা পরিচালনা করুন</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.useful-links.create') }}" class="btn btn-success">
                <i class="bi bi-plus-lg me-1"></i> নতুন লিংক যুক্ত করুন
            </a>
            <a href="{{ route('useful-links.index') }}" target="_blank" class="btn btn-outline-primary">
                <i class="bi bi-globe me-1"></i> পাবলিক পেজ দেখুন
            </a>
        </div>
    </div>

    {{-- Filter & Search Card --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.useful-links.index') }}" method="GET" class="row g-2 align-items-center justify-content-between">
                <div class="col-md-4">
                    <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="all">-- সকল ক্যাটাগরি --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="শিরোনাম বা লিংক দিয়ে খুঁজুন..." value="{{ $search }}">
                        <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-search"></i></button>
                        @if($search)
                            <a href="{{ route('admin.useful-links.index', ['category' => $category]) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x"></i></a>
                        @endif
                    </div>
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
                        <th class="ps-4" style="width: 60px;">ক্রম</th>
                        <th>শিরোনাম ও ক্যাটাগরি</th>
                        <th>অফিসিয়াল ওয়েব লিংক</th>
                        <th>সংযুক্ত সেবা</th>
                        <th class="text-center">ফিচার্ড</th>
                        <th class="text-center">স্ট্যাটাস</th>
                        <th class="text-end pe-4">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($links as $link)
                        <tr>
                            <td class="ps-4 fw-bold text-muted">{{ $link->sort_order }}</td>
                            <td>
                                <strong class="text-dark d-block fs-6">{{ $link->title }}</strong>
                                <span class="badge bg-light text-success border border-success-subtle rounded-pill">
                                    {{ $link->category }}
                                </span>
                                <small class="text-muted d-block mt-1">{{ Str::limit($link->short_description, 80) }}</small>
                            </td>
                            <td>
                                <a href="{{ $link->external_url }}" target="_blank" class="small font-monospace text-decoration-none">
                                    {{ Str::limit($link->external_url, 40) }} <i class="bi bi-box-arrow-up-right small"></i>
                                </a>
                            </td>
                            <td>
                                @if($link->service)
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                        {{ $link->service->title }}
                                    </span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($link->is_featured)
                                    <span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i> হ্যাঁ</span>
                                @else
                                    <span class="badge bg-light text-muted">না</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($link->status)
                                    <span class="badge bg-success">সক্রিয়</span>
                                @else
                                    <span class="badge bg-secondary">নিষ্ক্রিয়</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.useful-links.edit', $link->id) }}" class="btn btn-outline-primary" title="সম্পাদনা">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.useful-links.destroy', $link->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('আপনি কি নিশ্চিত এই লিংকটি মুছে ফেলতে চান?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="ডিলিট">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-link-45deg text-muted fs-1 mb-2 d-block"></i>
                                <span class="text-muted">কোনো লিংক পাওয়া যায়নি।</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($links->hasPages())
            <div class="card-footer bg-white border-top py-3 d-flex justify-content-center">
                {{ $links->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

