<?php

namespace App\Livewire\Inventory\Stock;

use App\Livewire\Inventory\Stock\Index;
use App\Src\Inventory\Models\Product;
use App\Src\Inventory\Models\StockMovement;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class Adjustment extends Component
{
    public $productId = null;
    public $productName = '';
    public $currentStock = 0;

    public $type = 'in';
    public $quantity = '';
    public $reason = '';

    #[On('open-adjustment')]
    public function loadProduct($productId)
    {
        $this->reset(['type', 'quantity', 'reason']);
        $this->type = 'in'; // Default

        $product = Product::with('stock')->find($productId);

        if ($product) {
            $this->productId = $product->id;
            $this->productName = $product->product_name;
            $this->currentStock = $product->stock->quantity ?? 0;
        }
    }

    public function save()
    {
        $this->validate([
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|min:5|max:255',
        ]);

        $product = Product::with('stock')->find($this->productId);

        if (!$product) {
            return;
        }

        // Check stock for outgoing adjustments
        if ($this->type === 'out' && ($product->stock->quantity ?? 0) < $this->quantity) {
            $this->addError('quantity', 'No hay suficiente stock para realizar esta salida.');
            return;
        }

        // Register Movement
        StockMovement::create([
            'product_id' => $this->productId,
            'user_id' => Auth::id(),
            'type' => $this->type,
            'quantity' => $this->quantity,
            'reason' => $this->reason,
        ]);

        // Update Stock
        $stock = $product->stock; // Assuming stock record exists (created by Observer/Create logic)

        // Fallback if stock record doesn't exist for some reason
        if (!$stock) {
            $stock = $product->stock()->create(['quantity' => 0]);
        }

        if ($this->type === 'in') {
            $stock->increment('quantity', $this->quantity);
        } else {
            $stock->decrement('quantity', $this->quantity);
        }

        Flux::modal('stock-adjustment')->close();

        // Refresh the parent table
        $this->dispatch('stock-updated')->to(Index::class);
        $this->dispatch('toast', message: 'Stock actualizado correctamente.', type: 'success');
    }

    public function render()
    {
        return view('livewire.inventory.stock.adjustment');
    }
}
