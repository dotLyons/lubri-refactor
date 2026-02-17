<?php

namespace App\Livewire\Inventory\Stock;

use App\Src\Inventory\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $searchField = 'product_name';
    public $selectedProductId = null;

    #[On('stock-updated')]
    public function refresh()
    {
        // Just refreshing the component
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    #[On('view-movements')]
    public function viewMovements($productId)
    {
        $this->selectedProductId = $productId;
        $this->dispatch('load-movements', productId: $productId)->to('inventory.stock.movements');
    }

    public function render()
    {
        $query = Product::with('stock');

        if ($this->search) {
            $query->where($this->searchField, 'like', '%' . $this->search . '%');
        }

        $products = $query->paginate(20);

        return view('livewire.inventory.stock.index', [
            'products' => $products
        ]);
    }
}
