<?php

use App\Livewire\TestComponent;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::get('test-component', App\Livewire\TestComponent::class)
    ->middleware(['auth', 'verified'])
    ->name('test-component');

Route::get('inventory/categories', App\Livewire\Inventory\Index::class)
    ->middleware(['auth', 'verified'])
    ->name('inventory.categories.index');

Route::get('inventory/subcategories', App\Livewire\Inventory\Subcategories\Index::class)
    ->middleware(['auth', 'verified'])
    ->name('inventory.subcategories.index');

Route::get('inventory/products', App\Livewire\Inventory\Product\Index::class)
    ->middleware(['auth', 'verified'])
    ->name('inventory.products.index');

Route::get('inventory/stocks', App\Livewire\Inventory\Stock\Index::class)
    ->middleware(['auth', 'verified'])
    ->name('inventory.stocks.index');

Route::get('inventory/reports/stock/download', [App\Http\Controllers\Inventory\StockReportController::class, 'download'])
    ->middleware(['auth', 'verified'])
    ->name('inventory.reports.stock.download');

Route::get('pos/cards', App\Livewire\POS\Cards\Index::class)
    ->middleware(['auth', 'verified'])
    ->name('pos.cards.index');

Route::get('pos/register', App\Livewire\POS\Register\Index::class)
    ->middleware(['auth', 'verified'])
    ->name('pos.register.index');

require __DIR__ . '/settings.php';
