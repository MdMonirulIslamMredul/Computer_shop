@extends('admin.layouts.app')

@section('title', 'নতুন উপজেলা পেইজ তৈরি')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1 text-dark">নতুন উপজেলা পেইজ যোগ করুন</h3>
            <p class="text-muted mb-0">নতুন পেইজ তৈরি করে শিরোনাম, ব্যানার, ৩টি ছবি এবং ৩টি টেক্সট ডিটেইলস ইনপুট দিন</p>
        </div>
        <div>
            <a href="{{ route('admin.upazilas.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> তালিকায় ফিরে যান
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> অনুগ্রহ করে নিচের ত্রুটিগুলি সংশোধন করুন:
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.upazilas.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.upazilas._form')
    </form>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        // Initialize CKEditor 5 for each detail editor
        ['#detail1Editor', '#detail2Editor', '#detail3Editor'].forEach(function(selector) {
            const el = document.querySelector(selector);
            if (el) {
                ClassicEditor.create(el, {
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'underline', 'link', '|',
                        'bulletedList', 'numberedList', 'blockQuote', '|',
                        'insertTable', 'undo', 'redo'
                    ]
                }).catch(error => console.error(error));
            }
        });

        // Auto-slug generator from title
        const titleInput = document.getElementById('pageTitle');
        const slugInput = document.getElementById('pageSlug');
        if (titleInput && slugInput) {
            titleInput.addEventListener('input', function() {
                if (!slugInput.dataset.touched) {
                    slugInput.value = titleInput.value
                        .toLowerCase()
                        .trim()
                        .replace(/[^\w\s-]/g, '')
                        .replace(/[\s_-]+/g, '-')
                        .replace(/^-+|-+$/g, '');
                }
            });
            slugInput.addEventListener('input', function() {
                slugInput.dataset.touched = "true";
            });
        }
    </script>
@endpush

