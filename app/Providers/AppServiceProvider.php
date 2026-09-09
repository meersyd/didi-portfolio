<?php

namespace App\Providers;

use App\Models\SiteCopy;
use App\Support\Presence;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view): void {
            $site = config('portfolio');

            if (Schema::hasTable('site_copies')) {
                $copy = SiteCopy::current();
                $site = array_replace_recursive($site, $copy->toSiteArray());
                $site['resume'] = $copy->hasResume() ? route('resume') : null;
            }

            $view->with('site', $site);
        });
        View::share('presence', [
            'online' => Presence::online(),
            'label' => Presence::label(),
        ]);

        RateLimiter::for('contact', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip().'|'.strtolower((string) $request->input('email')));
        });
    }
}
