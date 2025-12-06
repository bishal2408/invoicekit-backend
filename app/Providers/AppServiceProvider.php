<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
    }
}
