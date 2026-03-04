<?php

namespace App\Src\Customers\Providers;

use Illuminate\Support\ServiceProvider;

class CustomersServiceProvider extends ServiceProvider
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
        // Load Custom Migrations for the Customers bounded context
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');
    }
}
