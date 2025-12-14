<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
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
        JsonResource::macro('paginationInformation', function ($request, $paginated, $default) {
            unset($default['links']);
            unset($default['meta']['links']);
            unset($default['meta']['path']);

            return $default;
        });

        if (App::environment() == 'local') {
            DB::listen(function ($query) {
                Log::debug([
                    $query->sql,
                    $query->bindings,
                    $query->time,
                ]);
            });
        }

        // rate limiter for api_keys
        RateLimiter::for('api-key', function (Request $request) {
            $apiKey = $request->attributes->get('api_key') ?? null;

            // if api key is not set
            if (! $apiKey) {
                return Limit::none();
            }

            $limit = $apiKey->getEffectiveRateLimitPerMinute();

            // if rate limit per minute is not set
            if (! $limit) {
                return Limit::none();
            }

            return Limit::perMinute($limit)->by($apiKey->id);
        });
    }
}
