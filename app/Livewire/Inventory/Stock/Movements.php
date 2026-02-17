<?php

namespace App\Livewire\Inventory\Stock;

use App\Src\Inventory\Models\StockMovement;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Movements extends Component
{
    use WithPagination;

    public $productId = null;

    #[On('load-movements')]
    public function loadMovements($productId)
    {
        $this->productId = $productId;
        $this->resetPage(); // Reset pagination when loading new product
    }

    public function render()
    {
        $movements = collect([]);

        if ($this->productId) {
            $movements = StockMovement::where('product_id', $this->productId)
                ->with('user')
                ->latest()
                ->paginate(10);
        }

        return view('livewire.inventory.stock.movements', [
            'movements' => $movements
        ]);
    }
}
