@extends('admin.layouts.app')
@section('title', 'Settings')
@section('content')
    @php
        $themeDefaults = [
            'primary_color' => '#0d6efd',
            'secondary_color' => '#6c757d',
            'accent_color' => '#f39c12',
            'text_color' => '#333333',
            'bg_color' => '#ffffff',
        ];

        $themeValues = [
            'primary_color' => old('primary_color', $setting->primary_color ?? $themeDefaults['primary_color']),
            'secondary_color' => old('secondary_color', $setting->secondary_color ?? $themeDefaults['secondary_color']),
            'accent_color' => old('accent_color', $setting->accent_color ?? $themeDefaults['accent_color']),
            'text_color' => old('text_color', $setting->text_color ?? $themeDefaults['text_color']),
            'bg_color' => old('bg_color', $setting->bg_color ?? $themeDefaults['bg_color']),
        ];
    @endphp

    <div class="card p-4">
        <h4 class="mb-3">Website Settings</h4>
        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
            @csrf

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                        <div>
                            <h5 class="card-title mb-1">Theme Settings</h5>
                            <p class="text-secondary mb-0">Customize the site colors without touching code.</p>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                data-theme-preset="light">Light</button>
                            <button type="button" class="btn btn-sm btn-outline-dark"
                                data-theme-preset="dark">Dark</button>
                            <button type="button" class="btn btn-sm btn-outline-success"
                                data-theme-preset="solar">Solar</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="resetThemeColors">Reset
                                Defaults</button>
                        </div>
                    </div>

                    <div class="row g-4 align-items-stretch">
                        <div class="col-lg-7">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Primary Color</label>
                                    <input type="color" class="form-control form-control-color w-100" name="primary_color"
                                        id="primaryColor" value="{{ $themeValues['primary_color'] }}" title="Primary color">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Secondary Color</label>
                                    <input type="color" class="form-control form-control-color w-100"
                                        name="secondary_color" id="secondaryColor"
                                        value="{{ $themeValues['secondary_color'] }}" title="Secondary color">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Accent Color</label>
                                    <input type="color" class="form-control form-control-color w-100" name="accent_color"
                                        id="accentColor" value="{{ $themeValues['accent_color'] }}" title="Accent color">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Background Color</label>
                                    <input type="color" class="form-control form-control-color w-100" name="bg_color"
                                        id="bgColor" value="{{ $themeValues['bg_color'] }}" title="Background color">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Text Color</label>
                                    <input type="color" class="form-control form-control-color w-100" name="text_color"
                                        id="textColor" value="{{ $themeValues['text_color'] }}" title="Text color">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="border rounded-3 p-3 h-100" id="themePreviewPanel"
                                style="background: {{ $themeValues['bg_color'] }}; color: {{ $themeValues['text_color'] }}; transition: background-color 0.25s ease, color 0.25s ease;">
                                <h6 class="mb-2">Live Preview</h6>
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <span class="rounded-3 border" id="primarySwatch"
                                        style="width: 44px; height: 44px; background: {{ $themeValues['primary_color'] }};"></span>
                                    <span class="rounded-3 border" id="secondarySwatch"
                                        style="width: 44px; height: 44px; background: {{ $themeValues['secondary_color'] }};"></span>
                                    <span class="rounded-3 border" id="accentSwatch"
                                        style="width: 44px; height: 44px; background: {{ $themeValues['accent_color'] }};"></span>
                                    <span class="rounded-3 border" id="textSwatch"
                                        style="width: 44px; height: 44px; background: {{ $themeValues['text_color'] }};"></span>
                                    <span class="rounded-3 border" id="bgSwatch"
                                        style="width: 44px; height: 44px; background: {{ $themeValues['bg_color'] }};"></span>
                                </div>
                                <p class="small mb-3">Buttons, links, navbar, footer, and sections will inherit these colors
                                    across the frontend.</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-sm" id="previewPrimaryBtn">Primary</button>
                                    <button type="button" class="btn btn-sm" id="previewSecondaryBtn">Secondary</button>
                                    <button type="button" class="btn btn-sm" id="previewAccentBtn">Accent</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Site Name</label>
                    <input class="form-control" name="site_name" value="{{ old('site_name', $setting->site_name) }}"
                        required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Meta Title</label>
                    <input class="form-control" name="meta_title" value="{{ old('meta_title', $setting->meta_title) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Meta Description</label>
                    <textarea class="form-control" name="meta_description" rows="2">{{ old('meta_description', $setting->meta_description) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">SEO Keywords</label>
                    <input class="form-control" name="seo_keywords"
                        value="{{ old('seo_keywords', $setting->seo_keywords) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Logo</label>
                    <div class="border rounded-3 bg-light d-flex align-items-center justify-content-center mb-2"
                        style="min-height: 140px;">
                        <img id="logoPreview"
                            src="{{ $setting->logo_path ? asset('storage/' . $setting->logo_path) : '' }}"
                            alt="Logo preview"
                            style="max-width: 100%; max-height: 110px; {{ $setting->logo_path ? '' : 'display:none;' }}">
                        <span id="logoPlaceholder" class="text-secondary {{ $setting->logo_path ? 'd-none' : '' }}">No
                            logo uploaded</span>
                    </div>
                    <input type="file" class="form-control" name="logo" id="logoInput" accept="image/*">
                    <div class="form-text">Allowed: PNG, JPG, JPEG, SVG. Recommended size: 300x100 px or similar wide logo.
                        Max file size: 2 MB.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Favicon</label>
                    <div class="border rounded-3 bg-light d-flex align-items-center justify-content-center mb-2"
                        style="min-height: 100px;">
                        <img id="faviconPreview"
                            src="{{ $setting->favicon_path ? asset('storage/' . $setting->favicon_path) : '' }}"
                            alt="Favicon preview"
                            style="max-width: 56px; max-height: 56px; {{ $setting->favicon_path ? '' : 'display:none;' }}">
                        <span id="faviconPlaceholder"
                            class="text-secondary {{ $setting->favicon_path ? 'd-none' : '' }}">No favicon uploaded</span>
                    </div>
                    <input type="file" class="form-control" name="favicon" id="faviconInput" accept="image/*,.ico">
                    <div class="form-text">Allowed: ICO, PNG, JPG, JPEG, SVG. Recommended size: 32x32 px or 48x48 px. Max file size: 1 MB.</div>
                </div>

                {{-- Cyber Shop Payment & Hotline Settings --}}
                <div class="col-12 mt-4 pt-3 border-top">
                    <h5 class="text-dark fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-wallet2 text-success"></i> সাইবার ক্যাফে ও বিকাশ/নগদ পেমেন্ট সেটিংস
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">বিকাশ পার্সোনাল নম্বর (Send Money)</label>
                            <input class="form-control" name="bkash_number" value="{{ old('bkash_number', $setting->bkash_number) }}" placeholder="০১৭১১-৬৬৫০৯৮ (Send Money)">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">নগদ পার্সোনাল নম্বর (Send Money)</label>
                            <input class="form-control" name="nagad_number" value="{{ old('nagad_number', $setting->nagad_number) }}" placeholder="০১৭১১-৬৬৫০৯৮ (Send Money)">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">বিকাশ পেমেন্ট নম্বর (Merchant / Payment)</label>
                            <input class="form-control" name="bkash_payment_number" value="{{ old('bkash_payment_number', $setting->bkash_payment_number) }}" placeholder="০১৫৩১-১৬০৪৩৪ (Payment)">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">রকেট নম্বর (ঐচ্ছিক)</label>
                            <input class="form-control" name="rocket_number" value="{{ old('rocket_number', $setting->rocket_number) }}" placeholder="০১৭১১-৬৬৫০৯৮-৯">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">বিকাশ পেমেন্ট কিউআর কোড (QR Code Image)</label>
                            <input type="file" class="form-control" name="bkash_qr_image" accept="image/*">
                            @if($setting->bkash_qr_image)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $setting->bkash_qr_image) }}" alt="QR Code" class="img-thumbnail" style="height: 60px;">
                                    <span class="small text-muted ms-2">বর্তমান কিউআর কোড সংরক্ষিত আছে</span>
                                </div>
                            @endif
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark">পেমেন্ট নির্দেশিকা (গ্রাহক অর্ডার ফর্মে দেখতে পাবেন)</label>
                            <textarea class="form-control" name="payment_instructions" rows="3" placeholder="টাকা পাঠানোর নিয়মাবলী লিখুন...">{{ old('payment_instructions', $setting->payment_instructions) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Emergency Notice Section --}}
                <div class="col-12 mt-4 pt-3 border-top">
                    <h5 class="text-dark fw-bold mb-2 d-flex align-items-center gap-2">
                        <i class="bi bi-bell-fill text-danger"></i> জরুরি ঘোষণা / নোটিশ ব্যানার (Emergency Notice Bar)
                    </h5>
                    <p class="text-muted small mb-3">
                        ওয়েবসাইটের মূল হেডারের নিচে লাল ব্যানারে গ্রাহকদের জন্য জরুরি তথ্য বা ডেডলাইন ঘোষণা দিতে পারেন। যদি কোনো নোটিশ প্রদর্শন করতে না চান, তবে বক্সটি <strong>সম্পূর্ণ ফাঁকা</strong> রেখে সংরক্ষণ করুন।
                    </p>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold text-dark">নোটিশ মেসেজ</label>
                            <input class="form-control" id="emergencyNoticeInput" name="emergency_notice" 
                                   value="{{ old('emergency_notice', $setting->emergency_notice) }}" 
                                   placeholder="উদাঃ অনলাইনে যেকোনো চাকরির আবেদন বা ভর্তি আবেদনের জন্য শেষ সময়ের অপেক্ষা না করে আজই অর্ডার করুন!">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-dark">নোটিশ বারের কালার থিম</label>
                            <select class="form-select" id="noticeColorSelect" name="notice_color">
                                <option value="navy" {{ old('notice_color', $setting->notice_color ?? 'navy') === 'navy' ? 'selected' : '' }}>
                                    🌌 Midnight Navy (স্লীক নেভি ব্লু - রিকমেন্ডেড)
                                </option>
                                <option value="amber" {{ old('notice_color', $setting->notice_color) === 'amber' ? 'selected' : '' }}>
                                    🌇 Sunset Amber (উষ্ণ গোল্ডেন অ্যাম্বার)
                                </option>
                                <option value="emerald" {{ old('notice_color', $setting->notice_color) === 'emerald' ? 'selected' : '' }}>
                                    🌿 Forest Green (দোকানের ব্র্যান্ড গ্রিন)
                                </option>
                                <option value="blue" {{ old('notice_color', $setting->notice_color) === 'blue' ? 'selected' : '' }}>
                                    🔷 Royal Blue (রয়্যাল ব্লু)
                                </option>
                                <option value="dark" {{ old('notice_color', $setting->notice_color) === 'dark' ? 'selected' : '' }}>
                                    🖤 Dark Charcoal (মিনিমাল ডার্ক)
                                </option>
                                <option value="red" {{ old('notice_color', $setting->notice_color) === 'red' ? 'selected' : '' }}>
                                    🚨 Crimson Red (লাল)
                                </option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-muted small mb-1">ওয়েবসাইট প্রিভিউ (Live Preview):</label>
                            <div id="noticePreviewBox" class="p-2 rounded-2 shadow-sm text-center d-flex align-items-center justify-content-center flex-wrap gap-2"
                                 style="background: linear-gradient(90deg, #0f172a 0%, #1e293b 100%); min-height: 44px; transition: all 0.3s ease;">
                                <span id="noticeBadgePreview" class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size: 0.78rem;">
                                    <i class="bi bi-bell-fill text-dark"></i> জরুরি নোটিশ
                                </span>
                                <span id="emergencyNoticePreview" class="fw-semibold text-white small" style="text-shadow: 0 1px 2px rgba(0,0,0,0.35);">
                                    {{ old('emergency_notice', $setting->emergency_notice) ?: 'কোনো নোটিশ সেট করা নেই (ব্যানার লুকায়িত থাকবে)' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-4 pt-3 border-top">
                    <h5 class="text-dark fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-megaphone-fill text-primary"></i> Homepage Call To Action (CTA)
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">CTA Title</label>
                            <input class="form-control" name="cta_title" value="{{ old('cta_title', $setting->cta_title) }}" placeholder="e.g. Ready to switch to solar?">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-dark">CTA Button Text</label>
                            <input class="form-control" name="cta_button_text" value="{{ old('cta_button_text', $setting->cta_button_text) }}" placeholder="e.g. Contact Us">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-dark">CTA Button Link</label>
                            <input class="form-control" name="cta_button_link" value="{{ old('cta_button_link', $setting->cta_button_link) }}" placeholder="e.g. /contact">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark">CTA Description</label>
                            <textarea class="form-control" name="cta_text" rows="2" placeholder="e.g. Talk to our experts and get a customized clean-energy plan.">{{ old('cta_text', $setting->cta_text) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <button class="btn btn-primary mt-4 px-4"><i class="bi bi-check-lg me-1"></i> Save Settings</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            const bindPreview = (inputId, previewId, placeholderId) => {
                const input = document.getElementById(inputId);
                const preview = document.getElementById(previewId);
                const placeholder = document.getElementById(placeholderId);

                if (!input || !preview || !placeholder) {
                    return;
                }

                input.addEventListener('change', () => {
                    const file = input.files && input.files[0];

                    if (!file) {
                        return;
                    }

                    preview.src = URL.createObjectURL(file);
                    preview.style.display = 'block';
                    placeholder.classList.add('d-none');
                });
            };

            bindPreview('logoInput', 'logoPreview', 'logoPlaceholder');
            bindPreview('faviconInput', 'faviconPreview', 'faviconPlaceholder');

            const themeDefaults = {
                primaryColor: '#0d6efd',
                secondaryColor: '#6c757d',
                accentColor: '#f39c12',
                textColor: '#333333',
                bgColor: '#ffffff',
            };

            const inputs = {
                primary: document.getElementById('primaryColor'),
                secondary: document.getElementById('secondaryColor'),
                accent: document.getElementById('accentColor'),
                text: document.getElementById('textColor'),
                bg: document.getElementById('bgColor'),
            };

            const swatches = {
                primary: document.getElementById('primarySwatch'),
                secondary: document.getElementById('secondarySwatch'),
                accent: document.getElementById('accentSwatch'),
                text: document.getElementById('textSwatch'),
                bg: document.getElementById('bgSwatch'),
            };

            const previewPanel = document.getElementById('themePreviewPanel');
            const previewButtons = {
                primary: document.getElementById('previewPrimaryBtn'),
                secondary: document.getElementById('previewSecondaryBtn'),
                accent: document.getElementById('previewAccentBtn'),
            };

            const applyColors = () => {
                if (!previewPanel) {
                    return;
                }

                const primary = inputs.primary?.value || themeDefaults.primaryColor;
                const secondary = inputs.secondary?.value || themeDefaults.secondaryColor;
                const accent = inputs.accent?.value || themeDefaults.accentColor;
                const text = inputs.text?.value || themeDefaults.textColor;
                const bg = inputs.bg?.value || themeDefaults.bgColor;

                if (swatches.primary) swatches.primary.style.backgroundColor = primary;
                if (swatches.secondary) swatches.secondary.style.backgroundColor = secondary;
                if (swatches.accent) swatches.accent.style.backgroundColor = accent;
                if (swatches.text) swatches.text.style.backgroundColor = text;
                if (swatches.bg) swatches.bg.style.backgroundColor = bg;

                previewPanel.style.backgroundColor = bg;
                previewPanel.style.color = text;

                if (previewButtons.primary) {
                    previewButtons.primary.style.backgroundColor = primary;
                    previewButtons.primary.style.borderColor = primary;
                    previewButtons.primary.style.color = '#ffffff';
                }

                if (previewButtons.secondary) {
                    previewButtons.secondary.style.backgroundColor = secondary;
                    previewButtons.secondary.style.borderColor = secondary;
                    previewButtons.secondary.style.color = '#ffffff';
                }

                if (previewButtons.accent) {
                    previewButtons.accent.style.backgroundColor = accent;
                    previewButtons.accent.style.borderColor = accent;
                    previewButtons.accent.style.color = '#111827';
                }

                document.documentElement.style.setProperty('--primary-color', primary);
                document.documentElement.style.setProperty('--secondary-color', secondary);
                document.documentElement.style.setProperty('--accent-color', accent);
                document.documentElement.style.setProperty('--text-color', text);
                document.documentElement.style.setProperty('--bg-color', bg);
            };

            Object.values(inputs).forEach((input) => {
                if (!input) {
                    return;
                }

                input.addEventListener('input', applyColors);
            });

            document.querySelectorAll('[data-theme-preset]').forEach((button) => {
                button.addEventListener('click', () => {
                    const preset = button.dataset.themePreset;
                    const themes = {
                        light: {
                            primaryColor: '#0d6efd',
                            secondaryColor: '#6c757d',
                            accentColor: '#f39c12',
                            textColor: '#333333',
                            bgColor: '#ffffff',
                        },
                        dark: {
                            primaryColor: '#38bdf8',
                            secondaryColor: '#94a3b8',
                            accentColor: '#f59e0b',
                            textColor: '#f8fafc',
                            bgColor: '#0f172a',
                        },
                        solar: {
                            primaryColor: '#0f766e',
                            secondaryColor: '#64748b',
                            accentColor: '#f59e0b',
                            textColor: '#1e293b',
                            bgColor: '#ffffff',
                        },
                    };

                    const theme = themes[preset];

                    if (!theme) {
                        return;
                    }

                    inputs.primary.value = theme.primaryColor;
                    inputs.secondary.value = theme.secondaryColor;
                    inputs.accent.value = theme.accentColor;
                    inputs.text.value = theme.textColor;
                    inputs.bg.value = theme.bgColor;
                    applyColors();
                });
            });

            const resetButton = document.getElementById('resetThemeColors');
            if (resetButton) {
                resetButton.addEventListener('click', () => {
                    inputs.primary.value = themeDefaults.primaryColor;
                    inputs.secondary.value = themeDefaults.secondaryColor;
                    inputs.accent.value = themeDefaults.accentColor;
                    inputs.text.value = themeDefaults.textColor;
                    inputs.bg.value = themeDefaults.bgColor;
                    applyColors();
                });
            }

            applyColors();

            // Emergency Notice Live Preview
            const noticeInput = document.getElementById('emergencyNoticeInput');
            const noticePreview = document.getElementById('emergencyNoticePreview');
            const noticeColorSelect = document.getElementById('noticeColorSelect');
            const noticePreviewBox = document.getElementById('noticePreviewBox');
            const noticeBadgePreview = document.getElementById('noticeBadgePreview');

            const noticeThemes = {
                navy: {
                    bg: 'linear-gradient(90deg, #0f172a 0%, #1e293b 100%)',
                    badgeClass: 'badge bg-warning text-dark fw-bold px-2 py-1',
                    iconClass: 'bi bi-bell-fill text-dark'
                },
                amber: {
                    bg: 'linear-gradient(90deg, #d97706 0%, #b45309 100%)',
                    badgeClass: 'badge bg-white text-dark fw-bold px-2 py-1',
                    iconClass: 'bi bi-bell-fill text-warning'
                },
                emerald: {
                    bg: 'linear-gradient(90deg, #065f46 0%, #047857 100%)',
                    badgeClass: 'badge bg-white text-success fw-bold px-2 py-1',
                    iconClass: 'bi bi-bell-fill text-success'
                },
                blue: {
                    bg: 'linear-gradient(90deg, #1e40af 0%, #1d4ed8 100%)',
                    badgeClass: 'badge bg-white text-primary fw-bold px-2 py-1',
                    iconClass: 'bi bi-bell-fill text-primary'
                },
                dark: {
                    bg: 'linear-gradient(90deg, #18181b 0%, #27272a 100%)',
                    badgeClass: 'badge bg-warning text-dark fw-bold px-2 py-1',
                    iconClass: 'bi bi-bell-fill text-dark'
                },
                red: {
                    bg: 'linear-gradient(90deg, #dc2626 0%, #b91c1c 100%)',
                    badgeClass: 'badge bg-white text-danger fw-bold px-2 py-1',
                    iconClass: 'bi bi-bell-fill text-danger'
                }
            };

            function updateNoticeColorPreview() {
                if (!noticeColorSelect || !noticePreviewBox || !noticeBadgePreview) return;
                const selected = noticeColorSelect.value || 'navy';
                const theme = noticeThemes[selected] || noticeThemes.navy;
                noticePreviewBox.style.background = theme.bg;
                noticeBadgePreview.className = theme.badgeClass;
                noticeBadgePreview.innerHTML = `<i class="${theme.iconClass}"></i> জরুরি নোটিশ`;
            }

            if (noticeColorSelect) {
                noticeColorSelect.addEventListener('change', updateNoticeColorPreview);
                updateNoticeColorPreview();
            }

            if (noticeInput && noticePreview) {
                noticeInput.addEventListener('input', function() {
                    noticePreview.textContent = this.value.trim() || 'কোনো নোটিশ সেট করা নেই (ব্যানার লুকায়িত থাকবে)';
                });
            }
        })();
    </script>
@endpush
