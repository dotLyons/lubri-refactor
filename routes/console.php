<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// POS: Cierre automático de caja a las 23:55
\Illuminate\Support\Facades\Schedule::command('pos:close-register')->dailyAt('23:55');
