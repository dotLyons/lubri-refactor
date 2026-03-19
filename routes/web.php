<?php

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

Route::get('pos/current-account', App\Livewire\POS\CurrentAccount\Index::class)
    ->middleware(['auth', 'verified'])
    ->name('pos.current-account.index');

Route::get('pos/current-account/{payment}/pdf', [App\Http\Controllers\POS\CurrentAccountPdfController::class, 'download'])
    ->middleware(['auth', 'verified'])
    ->name('pos.current-account.pdf');

Route::get('customers', App\Livewire\Customers\Index::class)
    ->middleware(['auth', 'verified'])
    ->name('customers.index');

Route::get('work-orders', App\Livewire\WorkOrders\Index::class)
    ->middleware(['auth', 'verified'])
    ->name('work-orders.index');

Route::get('work-orders/{id}/manage', App\Livewire\WorkOrders\Edit::class)
    ->middleware(['auth', 'verified'])
    ->name('work-orders.edit');

Route::get('work-orders/{workOrder}/pdf', [App\Http\Controllers\WorkOrders\WorkOrderPdfController::class, 'download'])
    ->middleware(['auth', 'verified'])
    ->name('work-orders.pdf');

Route::get('budgets', App\Livewire\Budget\Index::class)
    ->middleware(['auth', 'verified'])
    ->name('budgets.index');

Route::get('budgets/{id}/manage', App\Livewire\Budget\Edit::class)
    ->middleware(['auth', 'verified'])
    ->name('budgets.edit');

Route::get('invoices/{id}/pay', App\Livewire\Invoices\Pay::class)
    ->middleware(['auth', 'verified'])
    ->name('invoices.pay');

Route::get('pos/invoices', App\Livewire\Invoices\Index::class)
    ->middleware(['auth', 'verified'])
    ->name('pos.invoices.index');

Route::get('invoices/{invoice}/pdf', [App\Http\Controllers\Invoices\InvoicePdfController::class, 'download'])
    ->middleware(['auth', 'verified'])
    ->name('invoices.pdf');

require __DIR__.'/settings.php';
