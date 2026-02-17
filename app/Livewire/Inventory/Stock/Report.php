<?php

namespace App\Livewire\Inventory\Stock;

use App\Src\Inventory\Models\ProductCategory;
use App\Src\Inventory\Models\SubcategoryProduct;
use Flux\Flux;
use Livewire\Component;

class Report extends Component
{
    public $categories = [];
    public $subcategories = [];

    public $selectedCategory = '';
    public $selectedSubcategory = '';
    public $stockStatus = 'all'; // all, with_stock, no_stock

    public function mount()
    {
        $this->categories = ProductCategory::all();
        $this->subcategories = SubcategoryProduct::all();
    }

    public function generate()
    {
        // Close modal
        Flux::modal('stock-report-modal')->close();

        // Redirect to download route with parameters
        return redirect()->route('inventory.reports.stock.download', [
            'category_id' => $this->selectedCategory,
            'subcategory_id' => $this->selectedSubcategory,
            'stock_status' => $this->stockStatus,
        ]);
    }

    public function render()
    {
        return view('livewire.inventory.stock.report');
    }
}
