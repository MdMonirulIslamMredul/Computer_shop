<?php
    $navSetting = $settings ?? null;
    $isHome = request()->routeIs('home');
?>
{{-- Top Info Bar --}}
<div class="bg-success text-white py-1 px-2 px-md-3 d-none d-md-block" style="background: linear-gradient(90deg, #059669 0%, #047857 100%); font-size: 0.85rem;">
    <div class="container-fluid px-1 px-md-2 d-flex justify-content-between align-items-center" style="padding-left: clamp(6px, 0.8vw, 14px) !important; padding-right: clamp(6px, 0.8vw, 14px) !important;">
        <div class="d-flex align-items-center gap-3">
            <span><i class="bi bi-geo-alt-fill text-warning me-1"></i> জনতা বাজার, মেইন রোড, আলেকজান্ডার, রামগতি, লক্ষ্মীপুর</span>
            <span class="opacity-50">|</span>
            <span><i class="bi bi-clock-fill text-warning me-1"></i> সকাল ৮:০০ - রাত ১০:০০</span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="tel:01711665098" class="text-white text-decoration-none fw-semibold">
                <i class="bi bi-telephone-fill text-warning me-1"></i> ০১৭১১-৬৬৫০৯৮
            </a>
            <a href="https://wa.me/8801711665098" target="_blank" class="text-white text-decoration-none fw-semibold">
                <i class="bi bi-whatsapp text-warning me-1"></i> হোয়াটসঅ্যাপ
            </a>
        </div>
    </div>
</div>

<nav class="navbar navbar-expand-lg sticky-top bg-white shadow-sm border-bottom py-2.5" style="z-index: 1040; min-height: 64px;">
    <div class="container-fluid px-1 px-md-2" style="padding-left: clamp(6px, 0.8vw, 14px) !important; padding-right: clamp(6px, 0.8vw, 14px) !important;">
        <a class="navbar-brand fw-bold d-flex align-items-center me-1 me-lg-2 flex-shrink-0" href="{{ route('home') }}">
            @if ($navSetting?->logo_path)
                <img src="{{ asset('storage/' . $navSetting->logo_path) }}" alt="Logo" height="35" class="me-1 me-md-2" style="max-height: 35px; width: auto; object-fit: contain;">
            @else
                <span class="badge rounded-circle p-1.5 me-1 me-md-2" style="background: linear-gradient(135deg, #059669, #dc2626); color: white;">
                    <i class="bi bi-pc-display-horizontal fs-6"></i>
                </span>
            @endif
            <div>
                <span class="d-block text-dark fw-bold text-nowrap" style="font-size: 0.79rem; line-height: 1.15; letter-spacing: -0.2px;">
                    {{ $navSetting->site_name ?? 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার' }}
                </span>
                <small class="text-muted d-none d-sm-block text-nowrap" style="font-size: 0.59rem; line-height: 1;">অনলাইন সেবা ও সাইবার ক্যাফে সেন্টার</small>
            </div>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <li class="nav-item">
                    <a class="nav-link px-2 {{ request()->routeIs('home') ? 'active fw-bold text-success' : 'text-dark' }}"
                       style="font-weight: 600; font-size: 0.88rem;" href="{{ route('home') }}">
                        <i class="bi bi-house-door me-1"></i> হোম
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2 {{ request()->routeIs('services.*') ? 'active fw-bold text-success' : 'text-dark' }}"
                       style="font-weight: 600; font-size: 0.88rem;" href="{{ route('services.index') }}">
                        <i class="bi bi-grid me-1"></i> সেবাসমূহ
                    </a>
                </li>
                {{-- Upazila Dynamic Dropdown --}}
                @if(isset($upazilaNavPages) && $upazilaNavPages->count() > 0)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-2 {{ request()->routeIs('upazila.show') ? 'active fw-bold text-success' : 'text-dark' }}"
                           href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                           style="font-weight: 600; font-size: 0.88rem;">
                            <i class="bi bi-geo-alt me-1"></i> উপজেলা
                        </a>
                        <ul class="dropdown-menu shadow border-0 py-2 rounded-3">
                            @foreach($upazilaNavPages as $navUpazila)
                                <li>
                                    <a class="dropdown-item py-2 px-3 {{ request()->is('upazila/' . $navUpazila->slug) ? 'active bg-success text-white fw-semibold' : '' }}"
                                       href="{{ route('upazila.show', $navUpazila->slug) }}"
                                       style="font-size: 0.88rem;">
                                        <i class="bi bi-chevron-right me-1 small opacity-75"></i> {{ $navUpazila->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link px-2 {{ request()->routeIs('useful-links.*') ? 'active fw-bold text-success' : 'text-dark' }}"
                       style="font-weight: 600; font-size: 0.88rem;" href="{{ route('useful-links.index') }}">
                        <i class="bi bi-link-45deg me-1"></i> প্রয়োজনীয় লিংক
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2 {{ request()->routeIs('orders.track') ? 'active fw-bold text-success' : 'text-dark' }}"
                       style="font-weight: 600; font-size: 0.88rem;" href="{{ route('orders.track') }}">
                        <i class="bi bi-search me-1"></i> ট্র্যাকিং
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2 {{ request()->routeIs('blogs.*') ? 'active fw-bold text-success' : 'text-dark' }}"
                       style="font-weight: 600; font-size: 0.88rem;" href="{{ route('blogs.index') }}">
                        <i class="bi bi-journal-text me-1"></i> ব্লগ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2 {{ request()->routeIs('about') ? 'active fw-bold text-success' : 'text-dark' }}"
                       style="font-weight: 600; font-size: 0.88rem;" href="{{ route('about') }}">
                        আমাদের সম্পর্কে
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2 {{ request()->routeIs('contact.index') ? 'active fw-bold text-success' : 'text-dark' }}"
                       style="font-weight: 600; font-size: 0.88rem;" href="{{ route('contact.index') }}">
                        যোগাযোগ
                    </a>
                </li>

                {{-- Order Service Primary CTA Button --}}
                <li class="nav-item ms-lg-1 ms-xl-2 my-2 my-lg-0">
                    <a href="{{ route('orders.create') }}" class="btn btn-success btn-sm px-2.5 py-2 rounded-pill fw-bold shadow-sm d-inline-flex align-items-center gap-1 text-white text-nowrap" style="font-size: 0.8rem;">
                        <i class="bi bi-send-fill"></i> আবেদন করুন
                    </a>
                </li>

                {{-- @guest
                    <li class="nav-item ms-lg-1">
                        <a class="btn btn-outline-success btn-sm px-3 py-2 rounded-pill fw-semibold" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i> লগইন
                        </a>
                    </li>
                @endguest --}}

                @auth
                    <li class="nav-item dropdown ms-lg-1 ms-xl-2">
                        <a class="nav-link dropdown-toggle btn btn-light btn-sm px-2.5 py-2 rounded-pill text-dark fw-semibold text-nowrap" style="font-size: 0.8rem;" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1 text-success"></i> {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            @if (auth()->user()->is_admin)
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> অ্যাডমিন ড্যাশবোর্ড</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.orders.index') }}"><i class="bi bi-receipt me-2"></i> অর্ডার ম্যানেজমেন্ট</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> লগআউট</button>
                                    </form>
                                </li>
                            @else
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="bi bi-person me-2"></i> আমার প্রোফাইল</a></li>
                                <li><a class="dropdown-item" href="{{ route('orders.track') }}"><i class="bi bi-clock-history me-2"></i> আমার অর্ডার ট্র্যাকিং</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> লগআউট</button>
                                    </form>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

{{-- Dynamic Emergency Notice Ticker Bar --}}
@if (!empty($navSetting?->emergency_notice))
    @php
        $noticeTheme = $navSetting?->notice_color ?? 'navy';
        $themeStyles = [
            'navy' => [
                'bg' => 'linear-gradient(90deg, #0f172a 0%, #1e293b 100%)',
                'border' => 'rgba(255, 255, 255, 0.12)',
                'badge_bg' => 'bg-warning text-dark',
                'icon_class' => 'bi-bell-fill text-dark',
                'text_color' => '#ffffff',
                'badge_style' => 'color: #0f172a !important;',
            ],
            'amber' => [
                'bg' => 'linear-gradient(90deg, #d97706 0%, #b45309 100%)',
                'border' => 'rgba(0, 0, 0, 0.15)',
                'badge_bg' => 'bg-white text-dark',
                'icon_class' => 'bi-bell-fill text-warning',
                'text_color' => '#ffffff',
                'badge_style' => 'color: #b45309 !important;',
            ],
            'emerald' => [
                'bg' => 'linear-gradient(90deg, #065f46 0%, #047857 100%)',
                'border' => 'rgba(255, 255, 255, 0.12)',
                'badge_bg' => 'bg-white text-success',
                'icon_class' => 'bi-bell-fill text-success',
                'text_color' => '#ffffff',
                'badge_style' => 'color: #047857 !important;',
            ],
            'blue' => [
                'bg' => 'linear-gradient(90deg, #1e40af 0%, #1d4ed8 100%)',
                'border' => 'rgba(255, 255, 255, 0.12)',
                'badge_bg' => 'bg-white text-primary',
                'icon_class' => 'bi-bell-fill text-primary',
                'text_color' => '#ffffff',
                'badge_style' => 'color: #1e40af !important;',
            ],
            'dark' => [
                'bg' => 'linear-gradient(90deg, #18181b 0%, #27272a 100%)',
                'border' => 'rgba(255, 255, 255, 0.12)',
                'badge_bg' => 'bg-warning text-dark',
                'icon_class' => 'bi-bell-fill text-dark',
                'text_color' => '#ffffff',
                'badge_style' => 'color: #0f172a !important;',
            ],
            'red' => [
                'bg' => 'linear-gradient(90deg, #dc2626 0%, #b91c1c 100%)',
                'border' => 'rgba(0, 0, 0, 0.15)',
                'badge_bg' => 'bg-white text-danger',
                'icon_class' => 'bi-bell-fill text-danger',
                'text_color' => '#ffffff',
                'badge_style' => 'color: #dc2626 !important;',
            ],
        ];
        $currentNotice = $themeStyles[$noticeTheme] ?? $themeStyles['navy'];
    @endphp
    <div class="emergency-notice-bar py-1.5 px-2 px-md-3 shadow-sm position-relative overflow-hidden"
        style="background: {{ $currentNotice['bg'] }}; border-bottom: 1px solid {{ $currentNotice['border'] }}; z-index: 1030;">
        <div class="container-fluid px-1 px-md-2 d-flex align-items-center gap-2 gap-md-3" style="padding-left: clamp(6px, 0.8vw, 14px) !important; padding-right: clamp(6px, 0.8vw, 14px) !important;">
            {{-- Pinned Badge on the left --}}
            <div class="notice-badge-wrapper flex-shrink-0">
                <span class="badge {{ $currentNotice['badge_bg'] }} fw-bold shadow-sm px-2.5 py-1 text-nowrap d-inline-flex align-items-center gap-1"
                    style="font-size: 0.78rem; letter-spacing: 0.2px; {{ $currentNotice['badge_style'] }}">
                    <i class="bi {{ $currentNotice['icon_class'] }} animate-bell"></i>
                    <span>জরুরি নোটিশ</span>
                </span>
            </div>

            {{-- Single-line Ticker Track --}}
            <div class="notice-ticker-track flex-grow-1 overflow-hidden position-relative">
                <div class="notice-ticker-content fw-semibold"
                    style="color: {{ $currentNotice['text_color'] }} !important; font-size: 0.88rem; line-height: 1.5; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.4);">
                    {{ $navSetting->emergency_notice }}
                </div>
            </div>
        </div>
    </div>
@endif

<style>
    .navbar {
        min-height: 64px;
    }
    .navbar .nav-link {
        color: #1e293b !important;
        font-weight: 600;
        transition: color 0.2s ease;
        white-space: nowrap !important;
        padding-top: 0.55rem !important;
        padding-bottom: 0.55rem !important;
    }
    .navbar .nav-link.px-2 {
        padding-left: 0.36rem !important;
        padding-right: 0.36rem !important;
        font-size: 0.83rem !important;
    }
    @media (min-width: 1200px) {
        .navbar .nav-link.px-2 {
            padding-left: 0.46rem !important;
            padding-right: 0.46rem !important;
            font-size: 0.86rem !important;
        }
    }
    @media (min-width: 1400px) {
        .navbar .nav-link.px-2 {
            padding-left: 0.55rem !important;
            padding-right: 0.55rem !important;
            font-size: 0.89rem !important;
        }
    }
    .navbar .nav-link:hover,
    .navbar .nav-link.active {
        color: #059669 !important;
    }
    html[data-theme='dark'] .navbar .nav-link {
        color: #e5e7eb !important;
    }
    html[data-theme='dark'] .navbar .nav-link:hover,
    html[data-theme='dark'] .navbar .nav-link:focus-visible,
    html[data-theme='dark'] .navbar .nav-link.active {
        color: #6ee7b7 !important;
    }
    html[data-theme='dark'] .navbar .nav-link i {
        color: inherit !important;
    }
    html[data-theme='dark'] .navbar .navbar-collapse {
        background: var(--surface-color);
    }
    html[data-theme='dark'] .navbar .navbar-toggler-icon {
        filter: invert(1) brightness(200%);
    }
    html[data-theme='dark'] .emergency-notice-bar .notice-badge-wrapper .badge > * {
        color: inherit !important;
    }
    @keyframes bellSwing {
        0%, 100% { transform: rotate(0deg); }
        10%, 30% { transform: rotate(-14deg); }
        20%, 40% { transform: rotate(14deg); }
        50% { transform: rotate(0deg); }
    }
    .animate-bell {
        display: inline-block;
        animation: bellSwing 2.5s infinite ease-in-out;
    }
    .emergency-notice-bar .notice-ticker-track {
        display: flex;
        align-items: center;
        overflow: hidden;
        white-space: nowrap;
        position: relative;
    }
    .emergency-notice-bar .notice-ticker-content {
        white-space: nowrap;
        display: inline-block;
        color: #ffffff !important;
    }
    @media (min-width: 992px) {
        .emergency-notice-bar .notice-ticker-track {
            justify-content: center;
        }
    }
    @media (max-width: 991.98px) {
        .emergency-notice-bar {
            padding-top: 5px !important;
            padding-bottom: 5px !important;
        }
        .emergency-notice-bar .container {
            padding-left: 8px;
            padding-right: 8px;
        }
        .emergency-notice-bar .notice-ticker-track {
            mask-image: linear-gradient(to right, transparent 0%, black 12px, black 94%, transparent 100%);
            -webkit-mask-image: linear-gradient(to right, transparent 0%, black 12px, black 94%, transparent 100%);
        }
        .emergency-notice-bar .notice-ticker-content {
            display: inline-block;
            white-space: nowrap !important;
            padding-left: 100%;
            animation: noticeTickerScroll 18s linear infinite;
            will-change: transform;
        }
        .emergency-notice-bar:hover .notice-ticker-content,
        .emergency-notice-bar:active .notice-ticker-content {
            animation-play-state: paused;
        }
    }
    @keyframes noticeTickerScroll {
        0% {
            transform: translateX(0%);
        }
        100% {
            transform: translateX(-100%);
        }
    }
    @media (max-width: 991.98px) {
        .navbar .navbar-collapse {
            background: #ffffff;
            padding: 1rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            margin-top: 0.5rem;
        }
    }
</style>
