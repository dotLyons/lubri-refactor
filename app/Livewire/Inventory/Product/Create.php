<?php

namespace App\Livewire\Inventory\Product;

use Livewire\Component;
use App\Src\Inventory\Models\Product;
use App\Src\Inventory\Models\ProductCategory;
use App\Src\Inventory\Models\SubcategoryProduct;
use Flux\Flux;

class Create extends Component
{
    public string $product_name = '';
    public string $product_code = '';
    public string $bar_code = '';
    public string $category_id = '';
    public string $subcategory_id = '';
    public string $cost_price = '';
    public string $sale_price = '';
    public string $initial_stock = '0';
    public string $description = '';
    public string $status = 'active';

    public $categories = [];
    public $subcategories = [];

    public function mount()
    {
        $this->categories = ProductCategory::all();
        $this->subcategories = SubcategoryProduct::all();
    }

    public function updatedCostPrice()
    {
        if (is_numeric($this->cost_price)) {
            $this->sale_price = number_format($this->cost_price * 1.60, 2, '.', '');
        }
    }

    public function save()
    {
        $this->validate([
            'product_name' => ['required', 'string', 'max:255'],
            'product_code' => ['required', 'string', 'max:50', 'unique:products,product_code'],
            'bar_code' => ['nullable', 'string', 'max:50'],
            'category_id' => ['required', 'exists:product_categories,id'],
            'subcategory_id' => ['nullable', 'exists:products_subcategories,id'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:active,inactive'],
        ]);

        $product = Product::create([
            'product_name' => $this->product_name,
            'product_code' => $this->product_code,
            'bar_code' => $this->bar_code ?: null,
            'category_id' => $this->category_id,
            'subcategory_id' => $this->subcategory_id ?: null,
            'cost_price' => $this->cost_price,
            'sale_price' => $this->sale_price,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        $product->stock()->create([
            'quantity' => $this->initial_stock,
        ]);

        $this->reset(['product_name', 'product_code', 'bar_code', 'category_id', 'subcategory_id', 'cost_price', 'sale_price', 'initial_stock', 'description', 'status']);

        $this->dispatch('product-created');
        Flux::modal('create-product')->close();
    }

    public function render()
    {
        return view('livewire.inventory.product.create');
    }
}
