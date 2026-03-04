<?php

namespace App\Src\Budget\Providers;

use Illuminate\Support\ServiceProvider;

class BudgetServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Migrations');
    }
}
