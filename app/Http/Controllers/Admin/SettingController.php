<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', [
            'setting' => Setting::firstOrCreate([], [
                'site_name' => 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার',
                'primary_color' => '#059669',
                'secondary_color' => '#334155',
                'accent_color' => '#dc2626',
                'text_color' => '#0f172a',
                'bg_color' => '#ffffff',
            ]),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'primary_color' => ['nullable', 'regex:/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/'],
            'secondary_color' => ['nullable', 'regex:/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/'],
            'accent_color' => ['nullable', 'regex:/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/'],
            'text_color' => ['nullable', 'regex:/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/'],
            'bg_color' => ['nullable', 'regex:/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'seo_keywords' => ['nullable', 'string', 'max:255'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:500'],
            'cta_title' => ['nullable', 'string', 'max:255'],
            'cta_text' => ['nullable', 'string'],
            'cta_button_text' => ['nullable', 'string', 'max:120'],
            'cta_button_link' => ['nullable', 'string', 'max:255'],
            'bkash_number' => ['nullable', 'string', 'max:255'],
            'bkash_payment_number' => ['nullable', 'string', 'max:255'],
            'nagad_number' => ['nullable', 'string', 'max:255'],
            'rocket_number' => ['nullable', 'string', 'max:255'],
            'payment_instructions' => ['nullable', 'string'],
            'emergency_notice' => ['nullable', 'string'],
            'notice_color' => ['nullable', 'string', 'max:50'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'twitter_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'favicon' => ['nullable', 'image', 'max:1024'],
            'bkash_qr_image' => ['nullable', 'image', 'max:2048'],
        ]);

        $setting = Setting::firstOrCreate([]);

        if ($request->hasFile('bkash_qr_image')) {
            if ($setting->bkash_qr_image) {
                Storage::disk('public')->delete($setting->bkash_qr_image);
            }
            $data['bkash_qr_image'] = $request->file('bkash_qr_image')->store('settings', 'public');
        }

        if ($request->hasFile('logo')) {
            if ($setting->logo_path) {
                Storage::disk('public')->delete($setting->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('settings', 'public');
        }

        if ($request->hasFile('favicon')) {
            if ($setting->favicon_path) {
                Storage::disk('public')->delete($setting->favicon_path);
            }
            $data['favicon_path'] = $request->file('favicon')->store('settings', 'public');
        }

        $data['primary_color'] = $this->normalizeColor($request->input('primary_color'), '#059669');
        $data['secondary_color'] = $this->normalizeColor($request->input('secondary_color'), '#334155');
        $data['accent_color'] = $this->normalizeColor($request->input('accent_color'), '#dc2626');
        $data['text_color'] = $this->normalizeColor($request->input('text_color'), '#0f172a');
        $data['bg_color'] = $this->normalizeColor($request->input('bg_color'), '#ffffff');

        if ($request->hasAny(['facebook_url', 'twitter_url', 'instagram_url', 'linkedin_url', 'youtube_url'])) {
            $existingSocial = $setting->social_links ?? [];
            $data['social_links'] = [
                'facebook' => $request->input('facebook_url', $existingSocial['facebook'] ?? null),
                'twitter' => $request->input('twitter_url', $existingSocial['twitter'] ?? ($existingSocial['x'] ?? null)),
                'x' => $request->input('twitter_url', $existingSocial['x'] ?? ($existingSocial['twitter'] ?? null)),
                'instagram' => $request->input('instagram_url', $existingSocial['instagram'] ?? null),
                'linkedin' => $request->input('linkedin_url', $existingSocial['linkedin'] ?? null),
                'youtube' => $request->input('youtube_url', $existingSocial['youtube'] ?? null),
            ];
        }

        unset(
            $data['logo'],
            $data['favicon'],
            $data['facebook_url'],
            $data['twitter_url'],
            $data['instagram_url'],
            $data['linkedin_url'],
            $data['youtube_url']
        );

        $setting->update($data);

        return back()->with('success', 'সেটিংস সফলভাবে আপডেট করা হয়েছে।');
    }

    private function normalizeColor(?string $value, string $default): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return $default;
        }

        return preg_match('/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/', $value) ? strtolower($value) : $default;
    }
}
