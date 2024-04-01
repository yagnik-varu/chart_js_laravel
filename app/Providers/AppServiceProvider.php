<?php

namespace App\Providers;

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
        // parent::boo;

        // DB::listen(function ($query) {
        //     Log::info($query->sql); // Log the query itself (optional)
        //     Log::info($query->time . ' ms'); // Log the query execution time
        // });
    }
}
