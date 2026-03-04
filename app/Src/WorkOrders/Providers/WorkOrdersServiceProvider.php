<?php

namespace App\Src\WorkOrders\Providers;

use Illuminate\Support\ServiceProvider;

class WorkOrdersServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Migrations');
    }
}
