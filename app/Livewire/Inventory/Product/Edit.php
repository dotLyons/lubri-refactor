<?php

namespace App\Livewire\Inventory\Product;

use Livewire\Component;
use App\Src\Inventory\Models\Product;
use App\Src\Inventory\Models\ProductCategory;
use App\Src\Inventory\Models\SubcategoryProduct;
use Livewire\Attributes\On;
use Flux\Flux;

class Edit extends Component
{
    public ?Product $product = null;

    public string $product_name = '';
    public string $product_code = '';
    public string $bar_code = '';
    public string $category_id = '';
    public string $subcategory_id = '';
    public string $cost_price = '';
    public string $sale_price = '';
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

    #[On('edit-product')]
    public function loadProduct($id)
    {
        $this->product = Product::find($id);

        if ($this->product) {
            $this->product_name = $this->product->product_name;
            $this->product_code = $this->product->product_code;
            $this->bar_code = $this->product->bar_code ?? '';
            $this->category_id = $this->product->category_id;
            $this->subcategory_id = $this->product->subcategory_id ?? '';
            $this->cost_price = $this->product->cost_price;
            $this->sale_price = $this->product->sale_price;
            $this->description = $this->product->description ?? '';
            $this->status = $this->product->status;

            Flux::modal('edit-product')->show();
        }
    }

    public function update()
    {
        $this->validate([
            'product_name' => ['required', 'string', 'max:255'],
            'product_code' => ['required', 'string', 'max:50', 'unique:products,product_code,' . $this->product->id],
            'bar_code' => ['nullable', 'string', 'max:50'],
            'category_id' => ['required', 'exists:product_categories,id'],
            'subcategory_id' => ['nullable', 'exists:products_subcategories,id'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:active,inactive'],
        ]);

        $this->product->update([
            'product_name' => $this->product_name,
            'product_code' => $this->product_code,
            'bar_code' => $this->bar_code,
            'category_id' => $this->category_id,
            'subcategory_id' => $this->subcategory_id ?: null,
            'cost_price' => $this->cost_price,
            'sale_price' => $this->sale_price,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        $this->dispatch('product-updated');
        Flux::modal('edit-product')->close();
    }

    public function render()
    {
        return view('livewire.inventory.product.edit');
    }
}
