<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    @if ($settings?->favicon_path)
        <link rel="icon" href="{{ asset('storage/' . $settings->favicon_path) }}">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f1f5f9;
        }

        .sidebar {
            background-color: #0f172a !important;
            z-index: 1045;
        }

        @media (min-width: 992px) {
            .sidebar {
                min-height: 100vh;
                height: auto;
                position: static;
                flex: 0 0 16.66666667% !important;
                max-width: 16.66666667% !important;
                width: 16.66666667% !important;
            }
        }

        @media (max-width: 991.98px) {
            .sidebar.offcanvas-lg {
                width: 280px !important;
                max-width: 85vw !important;
                height: 100vh !important;
                max-height: 100vh !important;
                overflow-y: auto !important;
                box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.5);
            }
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 3px;
        }

        .sidebar a {
            color: #cbd5e1;
            text-decoration: none;
            display: block;
            padding: 10px 12px;
            border-radius: 10px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #1e293b;
            color: #fff;
        }

        .sidebar [data-bs-toggle="collapse"] .bi-chevron-down {
            transition: transform 0.25s ease-in-out;
        }

        .sidebar [data-bs-toggle="collapse"][aria-expanded="true"] .bi-chevron-down,
        .sidebar [data-bs-toggle="collapse"]:not(.collapsed) .bi-chevron-down {
            transform: rotate(180deg);
        }

        .sidebar .collapse a {
            padding: 8px 12px;
            font-size: 0.92rem;
            margin-bottom: 3px;
        }

        .sidebar .collapse a.active {
            background: #2563eb !important;
            color: #ffffff !important;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.35);
        }

        .sidebar a.parent-active {
            color: #ffffff;
            font-weight: 600;
        }

        .content {
            min-height: 100vh;
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')
            <div class="col-12 col-lg-10 content p-0">
                @include('admin.partials.topbar')
                <div class="p-3 p-md-4">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
