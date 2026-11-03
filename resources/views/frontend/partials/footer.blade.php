@php
    $footerSetting = $settings ?? null;
    $social = $footerSetting?->social_links ?? [];

    $mapEmbed = $footerSetting?->google_map_embed;
    $mapSrc = null;
    if (!empty($mapEmbed)) {
        if (preg_match('/src=["\']([^"\']+)["\']/', $mapEmbed, $matches)) {
            $mapSrc = $matches[1];
        } else {
            $mapSrc = $mapEmbed;
        }
    }
    if (!$mapSrc && !empty($footerSetting?->contact_address)) {
        $mapSrc = 'https://maps.google.com/maps?q=' . urlencode($footerSetting->contact_address) . '&t=&z=13&ie=UTF8&iwloc=&output=embed';
    }
@endphp

<footer class="pt-5 pb-3 mt-5 position-relative" style="background-color: #0b1324;">
    <div class="container">
        <div class="row g-4 align-items-start">
            {{-- Col 1: Brand & Contact Info (Width: 3/12) --}}
            <div class="col-lg-3 col-md-6 col-12">
                <div class="d-flex align-items-center gap-2 mb-3">
                    @if ($footerSetting?->logo_path)
                        <img src="{{ asset('storage/' . $footerSetting->logo_path) }}"
                            alt="{{ $footerSetting?->site_name ?? 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার' }} logo"
                            style="max-height: 44px; width: auto; background: #fff; padding: 4px 8px; border-radius: 8px;">
                    @else
                        <span class="badge rounded-circle p-2 bg-success text-white">
                            <i class="bi bi-pc-display-horizontal fs-5"></i>
                        </span>
                    @endif
                    <div>
                        <h6 class="text-white mb-0 fw-bold fs-6">{{ $footerSetting?->site_name ?? 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার' }}</h6>
                    </div>
                </div>
                <p class="small text-secondary mb-3 leading-relaxed" style="font-size: 0.85rem;">
                    {{ $footerSetting?->about_content ?: ($footerSetting?->company_intro ?? 'ডিজিটাল সেবা ও অনলাইন অ্যাপ্লিকেশনের নির্ভরযোগ্য কেন্দ্র।') }}
                </p>
                <div class="d-flex flex-column gap-2 small text-secondary" style="font-size: 0.82rem;">
                    @if ($footerSetting?->contact_address)
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-geo-alt-fill text-danger mt-1"></i>
                            <span>{{ $footerSetting->contact_address }}</span>
                        </div>
                    @endif
                    @if ($footerSetting?->contact_email)
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-envelope-fill text-primary"></i>
                            <a href="mailto:{{ $footerSetting->contact_email }}" class="text-secondary text-decoration-none hover-white">
                                {{ $footerSetting->contact_email }}
                            </a>
                        </div>
                    @endif
                    @if ($footerSetting?->contact_phone)
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-telephone-fill text-success"></i>
                            <a href="tel:{{ $footerSetting->contact_phone }}" class="text-secondary text-decoration-none hover-white">
                                {{ $footerSetting->contact_phone }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Col 2: Quick Links (Width: 2/12) --}}
            <div class="col-lg-2 col-md-6 col-6">
                <h6 class="text-white fw-bold mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-link-45deg text-success"></i> গুরুত্বপূর্ণ লিংক
                </h6>
                <div class="d-flex flex-column gap-2" style="font-size: 0.88rem;">
                    <a href="{{ route('orders.create') }}" class="footer-nav-link text-decoration-none">
                        <i class="bi bi-chevron-right small text-success opacity-50"></i> সেবা অর্ডার করুন
                    </a>
                    <a href="{{ route('orders.track') }}" class="footer-nav-link text-decoration-none">
                        <i class="bi bi-chevron-right small text-success opacity-50"></i> কাজের ট্র্যাকিং
                    </a>
                    <a href="{{ route('useful-links.index') }}" class="footer-nav-link text-decoration-none">
                        <i class="bi bi-chevron-right small text-success opacity-50"></i> প্রয়োজনীয় লিংক
                    </a>
                    <a href="{{ route('services.index') }}" class="footer-nav-link text-decoration-none">
                        <i class="bi bi-chevron-right small text-success opacity-50"></i> আমাদের সেবাসমূহ
                    </a>
                    <a href="{{ route('about') }}" class="footer-nav-link text-decoration-none">
                        <i class="bi bi-chevron-right small text-success opacity-50"></i> আমাদের সম্পর্কে
                    </a>
                    <a href="{{ route('contact.index') }}" class="footer-nav-link text-decoration-none">
                        <i class="bi bi-chevron-right small text-success opacity-50"></i> যোগাযোগ
                    </a>
                </div>
            </div>

            {{-- Col 3: Upazila Information Links (Width: 3/12) --}}
            <div class="col-lg-3 col-md-6 col-6">
                <h6 class="text-white fw-bold mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-geo-alt-fill text-success"></i> উপজেলা তথ্য
                </h6>
                <div class="d-flex flex-column gap-2" style="font-size: 0.88rem;">
                    @if(isset($upazilaNavPages) && $upazilaNavPages->count() > 0)
                        @foreach($upazilaNavPages as $navPage)
                            <a href="{{ route('upazila.show', $navPage->slug) }}" class="footer-nav-link text-decoration-none" title="{{ $navPage->title }}">
                                <i class="bi bi-chevron-right small text-success opacity-50"></i> {{ $navPage->title }}
                            </a>
                        @endforeach
                    @else
                        <a href="{{ route('upazila.index') }}" class="footer-nav-link text-decoration-none">
                            <i class="bi bi-chevron-right small text-success opacity-50"></i> উপজেলা সম্পর্কিত তথ্য
                        </a>
                    @endif
                </div>
            </div>

            {{-- Col 4: Location & Social Media (Width: 4/12) --}}
            <div class="col-lg-4 col-md-6 col-12">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="text-white fw-bold mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-pin-map-fill text-danger"></i> আমাদের অবস্থান
                    </h6>
                    @if ($mapSrc)
                        <a href="{{ $mapSrc }}" target="_blank" rel="noopener" class="small text-success text-decoration-none d-inline-flex align-items-center gap-1" style="font-size: 0.8rem;">
                            বড় ম্যাপে দেখুন <i class="bi bi-box-arrow-up-right small"></i>
                        </a>
                    @endif
                </div>
                <div class="rounded-3 overflow-hidden shadow-sm border border-secondary border-opacity-25 mb-2" style="height: 125px; width: 100%; background: #0f172a;">
                    <iframe src="{{ $mapSrc ?: 'https://maps.google.com/maps?q=Alexander,Ramgati,Lakshmipur&t=&z=14&ie=UTF8&iwloc=&output=embed' }}"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                @php
                    $hasFacebook = !empty($social['facebook']);
                    $hasTwitter = !empty($social['twitter']) || !empty($social['x']);
                    $hasInstagram = !empty($social['instagram']);
                    $hasLinkedin = !empty($social['linkedin']);
                    $hasYoutube = !empty($social['youtube']);
                    $hasAnySocial = $hasFacebook || $hasTwitter || $hasInstagram || $hasLinkedin || $hasYoutube;
                @endphp
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pt-1">
                    <div class="d-flex flex-wrap gap-1">
                        @if ($hasFacebook || !$hasAnySocial)
                            <a class="footer-social-btn facebook" href="{{ $social['facebook'] ?? '#' }}" target="_blank" rel="noopener" title="Facebook">
                                <i class="bi bi-facebook"></i>
                            </a>
                        @endif
                        @if ($hasTwitter || !$hasAnySocial)
                            <a class="footer-social-btn twitter" href="{{ $social['twitter'] ?? ($social['x'] ?? '#') }}" target="_blank" rel="noopener" title="X (Twitter)">
                                <i class="bi bi-twitter-x"></i>
                            </a>
                        @endif
                        @if ($hasInstagram || !$hasAnySocial)
                            <a class="footer-social-btn instagram" href="{{ $social['instagram'] ?? '#' }}" target="_blank" rel="noopener" title="Instagram">
                                <i class="bi bi-instagram"></i>
                            </a>
                        @endif
                        @if ($hasLinkedin || !$hasAnySocial)
                            <a class="footer-social-btn linkedin" href="{{ $social['linkedin'] ?? '#' }}" target="_blank" rel="noopener" title="LinkedIn">
                                <i class="bi bi-linkedin"></i>
                            </a>
                        @endif
                        @if ($hasYoutube || !$hasAnySocial)
                            <a class="footer-social-btn youtube" href="{{ $social['youtube'] ?? '#' }}" target="_blank" rel="noopener" title="YouTube">
                                <i class="bi bi-youtube"></i>
                            </a>
                        @endif
                    </div>
                    <span class="small text-secondary" style="font-size: 0.76rem;">সোশ্যাল মিডিয়ায় যুক্ত থাকুন</span>
                </div>
            </div>
        </div>

        <hr class="border-secondary border-opacity-25 my-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <p class="small text-secondary mb-0">
                &copy; {{ date('Y') }} <strong class="text-light">{{ $footerSetting?->site_name ?? 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার' }}</strong>. প্রোঃ মোঃ আবদুর রহমান। সর্বস্বত্ব সংরক্ষিত।
            </p>
            <div class="small text-secondary">
                জনতা বাজার, মেইন রোড, আলেকজান্ডার, রামগতি, লক্ষ্মীপুর। মোবাইল: 01711-665098, 01717-074428
            </div>
        </div>
        <div class="text-center pt-3 mt-2 border-top border-secondary border-opacity-10">
            <p class="small text-secondary mb-0" style="font-size: 0.82rem;">
                Website Developed and Designed by <a href="https://www.techwebdit.com/" target="_blank" rel="noopener" class="text-success fw-semibold text-decoration-none">Techweb BD IT</a> (<a href="https://www.techwebdit.com/" target="_blank" rel="noopener" class="text-secondary text-decoration-none hover-white">https://www.techwebdit.com/</a>)
            </p>
        </div>
    </div>
</footer>

<style>
    .footer-nav-link {
        color: #94a3b8;
        transition: all 0.2s ease-in-out;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
    .footer-nav-link:hover {
        color: #10b981;
        transform: translateX(4px);
    }
    .footer-social-btn {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
        color: #e2e8f0;
        transition: all 0.25s ease-in-out;
        text-decoration: none;
        font-size: 0.95rem;
        border: 1px solid rgba(255, 255, 255, 0.12);
    }
    .footer-social-btn:hover {
        transform: translateY(-3px);
        color: #ffffff;
    }
    .footer-social-btn.facebook:hover {
        background: #1877f2;
        border-color: #1877f2;
    }
    .footer-social-btn.twitter:hover {
        background: #000000;
        border-color: #333333;
    }
    .footer-social-btn.instagram:hover {
        background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
        border-color: transparent;
    }
    .footer-social-btn.linkedin:hover {
        background: #0a66c2;
        border-color: #0a66c2;
    }
    .footer-social-btn.youtube:hover {
        background: #ff0000;
        border-color: #ff0000;
    }
    .hover-white:hover {
        color: #ffffff !important;
    }
</style>
