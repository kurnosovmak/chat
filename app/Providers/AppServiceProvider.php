<?php

namespace App\Providers;

use App\Services\Auth\AuthContract;
use App\Services\Auth\PassportAuth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            AuthContract::class,
            PassportAuth::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
