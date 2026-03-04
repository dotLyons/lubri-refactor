<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\FortifyServiceProvider::class,
    App\Src\Foundation\Providers\FoundationServiceProvider::class,
    App\Src\Inventory\Providers\InventoryServiceProvider::class,
    App\Src\POS\Providers\PosServiceProvider::class,
    App\Src\Customers\Providers\CustomersServiceProvider::class,
    App\Src\WorkOrders\Providers\WorkOrdersServiceProvider::class,
    App\Src\Invoices\Providers\InvoicesServiceProvider::class,
    App\Src\Budget\Providers\BudgetServiceProvider::class,
];
