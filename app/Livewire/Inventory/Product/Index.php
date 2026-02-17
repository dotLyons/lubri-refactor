<?php

namespace App\Livewire\Inventory\Product;

use Livewire\Component;
use Livewire\WithPagination;
use App\Src\Inventory\Models\Product;
use Flux\Flux;

class Index extends Component
{
    use WithPagination;

    public $productToDeleteId;
    public $search = '';
    public $searchField = 'product_name';

    protected $queryString = ['search', 'searchField'];
    protected $listeners = ['product-created' => '$refresh', 'product-updated' => '$refresh'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->productToDeleteId = $id;
        Flux::modal('delete-product')->show();
    }

    public function delete()
    {
        if ($this->productToDeleteId) {
            $product = Product::find($this->productToDeleteId);
            if ($product) {
                $product->delete();
            }
            $this->productToDeleteId = null;
            Flux::modal('delete-product')->close();
        }
    }

    public function render()
    {
        $query = Product::with(['category', 'subcategory']);

        if ($this->search) {
            switch ($this->searchField) {
                case 'product_code':
                    $query->where('product_code', 'like', '%' . $this->search . '%');
                    break;
                case 'category':
                    $query->whereHas('category', function ($q) {
                        $q->where('category_name', 'like', '%' . $this->search . '%');
                    });
                    break;
                case 'subcategory':
                    $query->whereHas('subcategory', function ($q) {
                        $q->where('subcategory_name', 'like', '%' . $this->search . '%');
                    });
                    break;
                default: // product_name
                    $query->where('product_name', 'like', '%' . $this->search . '%');
                    break;
            }
        }

        $products = $query->paginate(10);
        return view('livewire.inventory.product.index', [
            'products' => $products
        ]);
    }
}
