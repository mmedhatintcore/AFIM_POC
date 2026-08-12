<?php

namespace App\Providers;

use App\Support\Logging\CustomLogger;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('custom.logger', fn () => new CustomLogger);
    }

    public function boot(): void
    {
        RateLimiter::for('contact', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));
        RateLimiter::for('survey', fn (Request $request) => Limit::perMinute(10)->by($request->ip()));
    }
}
