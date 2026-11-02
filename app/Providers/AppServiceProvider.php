<?php

namespace App\Providers;

use App\Models\Contact;
use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        if (
            env('FORCE_HTTPS', false) ||
            request()->server('HTTP_X_FORWARDED_PROTO') === 'https' ||
            request()->header('X-Forwarded-Proto') === 'https' ||
            (app()->environment('production') && !app()->isLocal() && !str_contains((string) config('app.url'), 'localhost'))
        ) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        View::composer('*', function ($view) {
            static $cachedSettings = null;
            if ($cachedSettings === null) {
                try {
                    if (Schema::hasTable('settings')) {
                        $cachedSettings = Setting::first() ?? false;
                    }
                } catch (\Throwable $e) {
                    $cachedSettings = false;
                }
            }
            $view->with('settings', $cachedSettings ?: null);
        });

        View::composer(['admin.partials.sidebar', 'admin.partials.topbar'], function ($view) {
            $unreadContactsCount = 0;
            try {
                if (Schema::hasTable('contacts')) {
                    $unreadContactsCount = Contact::unread()->count();
                }
            } catch (\Throwable $e) {
                $unreadContactsCount = 0;
            }
            $view->with('unreadContactsCount', $unreadContactsCount);
        });

        View::composer(['frontend.partials.navbar', 'frontend.partials.footer', 'frontend.layouts.app', 'frontend.layouts.*'], function ($view) {
            static $cachedUpazilas = null;
            if ($cachedUpazilas === null) {
                try {
                    if (Schema::hasTable('upazilas')) {
                        $cachedUpazilas = \App\Models\Upazila::where('is_active', true)
                            ->orderBy('sort_order')
                            ->orderBy('id')
                            ->get(['id', 'title', 'slug']);
                    } else {
                        $cachedUpazilas = collect();
                    }
                } catch (\Throwable $e) {
                    $cachedUpazilas = collect();
                }
            }
            $view->with('upazilaNavPages', $cachedUpazilas);
        });
    }
}
