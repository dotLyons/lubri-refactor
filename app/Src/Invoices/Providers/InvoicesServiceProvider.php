<?php

namespace App\Src\Invoices\Providers;

use Illuminate\Support\ServiceProvider;

class InvoicesServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Migrations');
    }
}
