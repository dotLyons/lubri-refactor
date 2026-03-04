<?php

namespace App\Livewire\Budget;

use App\Src\Budget\Enums\BudgetStatus;
use App\Src\Budget\Models\Budget;
use App\Src\Inventory\Models\Product;
use App\Src\Inventory\Models\StockMovement;
use App\Src\WorkOrders\Enums\WorkOrderDestination;
use App\Src\WorkOrders\Enums\WorkOrderStatus;
use App\Src\WorkOrders\Models\WorkOrder;
use App\Src\WorkOrders\Models\WorkOrderItem;
use Exception;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Edit extends Component
{
    use WithPagination;

    public ?Budget $budget = null;

    public $notes = '';

    public $items = [];

    public $productSearch = '';

    public $showCloseModal = false;

    public $destination = '';

    public $scheduled_at = '';

    public function mount($id)
    {
        $this->budget = Budget::with(['customer', 'vehicle', 'items.product.stock'])->findOrFail($id);

        $this->notes = $this->budget->notes ?? '';
        $this->scheduled_at = now()->addDay()->format('Y-m-d\TH:i');

        $this->refreshItems();
    }

    public function refreshItems()
    {
        $this->items = $this->budget->items->map(function ($item) {
            $stockAvailable = $item->product->stock ? (int) $item->product->stock->quantity : 0;

            return [
                'id' => $item->id,
                'name' => $item->product->product_name,
                'category' => $item->product->category ? $item->product->category->category_name : 'Sin categoría',
                'quantity' => (int) $item->quantity,
                'unit_price' => $item->unit_price,
                'subtotal' => $item->subtotal,
                'max_quantity' => (int) $item->quantity + $stockAvailable,
            ];
        })->toArray();
    }

    public function saveNotes()
    {
        $this->budget->update([
            'notes' => $this->notes,
        ]);

        $this->dispatch('budget-updated');
        Flux::toast('Notas guardadas correctamente.');
    }

    public function addItem($productId)
    {
        if ($this->budget->status->value === 'closed') {
            return;
        }

        $quantityToAdd = 1;

        $product = Product::with('stock')->find($productId);
        if (! $product) {
            return;
        }

        $stockAvailable = $product->stock ? $product->stock->quantity : 0;
        if ($quantityToAdd > $stockAvailable) {
            Flux::toast('Stock insuficiente. Solo quedan '.$stockAvailable.' unidades de '.$product->product_name.'.', variant: 'danger');

            return;
        }

        $existingItem = $this->budget->items()->where('product_id', $product->id)->first();

        if ($existingItem) {
            $existingItem->update([
                'quantity' => $existingItem->quantity + $quantityToAdd,
            ]);
        } else {
            $this->budget->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantityToAdd,
                'unit_price' => $product->sale_price,
            ]);
        }

        $this->budget->refresh();
        $this->refreshItems();

        $this->dispatch('budget-updated');
        Flux::toast('Producto agregado correctamente.');
    }

    public function removeItem($itemId)
    {
        if ($this->budget->status->value === 'closed') {
            return;
        }

        $item = $this->budget->items()->find($itemId);

        if ($item) {
            $item->delete();

            $this->budget->refresh();
            $this->refreshItems();
            $this->dispatch('budget-updated');
        }
    }

    public function updateItemQuantity($itemId, $newQuantity)
    {
        if ($this->budget->status->value === 'closed') {
            return;
        }

        $newQuantity = (int) $newQuantity;

        if ($newQuantity <= 0) {
            $this->removeItem($itemId);

            return;
        }

        $item = $this->budget->items()->with('product.stock')->find($itemId);
        if ($item) {
            $difference = $newQuantity - (int) $item->quantity;

            if ($difference > 0) {
                $stockAvailable = $item->product->stock ? $item->product->stock->quantity : 0;
                if ($difference > $stockAvailable) {
                    Flux::toast('Stock insuficiente. Solo te quedan '.$stockAvailable.' unidades extra disponibles en el inventario para poder sumar.', variant: 'danger');
                    $this->refreshItems();

                    return;
                }
            }

            $item->update([
                'quantity' => $newQuantity,
            ]);

            $this->budget->refresh();
            $this->refreshItems();
            $this->dispatch('budget-updated');
        }
    }

    public function openCloseModal()
    {
        Flux::toast('Abriendo modal...');
        $this->showCloseModal = true;
    }

    public function closeBudgetAndCreateWorkOrder()
    {
        $this->validate([
            'destination' => 'required|string',
            'scheduled_at' => 'required|date',
        ]);

        $this->budget->load('items.product.stock');

        $insufficientStock = [];
        foreach ($this->budget->items as $item) {
            $stockAvailable = $item->product && $item->product->stock ? (int) $item->product->stock->quantity : 0;
            if ((float) $item->quantity > $stockAvailable) {
                $insufficientStock[] = $item->product->product_name.' (disponible: '.$stockAvailable.', solicitado: '.$item->quantity.')';
            }
        }

        if (! empty($insufficientStock)) {
            Flux::toast('Los siguientes productos no tienen stock suficiente: '.implode(', ', $insufficientStock), variant: 'danger');

            return;
        }

        try {
            $workOrder = DB::transaction(function () {
                $workOrder = WorkOrder::create([
                    'customer_id' => $this->budget->customer_id,
                    'vehicle_id' => $this->budget->vehicle_id,
                    'user_id' => Auth::id(),
                    'destination' => $this->destination,
                    'scheduled_at' => $this->scheduled_at,
                    'status' => WorkOrderStatus::Open,
                ]);

                foreach ($this->budget->items as $budgetItem) {
                    $product = $budgetItem->product;
                    $stockAvailable = $product->stock ? $product->stock->quantity : 0;

                    if ($budgetItem->quantity > $stockAvailable) {
                        throw new Exception('Stock insuficiente para '.$product->product_name);
                    }

                    WorkOrderItem::create([
                        'work_order_id' => $workOrder->id,
                        'product_id' => $budgetItem->product_id,
                        'quantity' => $budgetItem->quantity,
                        'unit_price' => $budgetItem->unit_price,
                    ]);

                    if ($product->stock) {
                        $product->stock->update([
                            'quantity' => $product->stock->quantity - $budgetItem->quantity,
                        ]);

                        StockMovement::create([
                            'product_id' => $product->id,
                            'user_id' => Auth::id(),
                            'type' => 'out',
                            'quantity' => $budgetItem->quantity,
                            'reason' => 'Uso en Orden de Trabajo #'.$workOrder->id.' (desde Presupuesto #'.$this->budget->id.')',
                        ]);
                    }
                }

                $this->budget->update([
                    'status' => BudgetStatus::Closed,
                    'work_order_id' => $workOrder->id,
                ]);

                return $workOrder;
            });

            $this->showCloseModal = false;
            $this->dispatch('budget-updated');
            Flux::toast('Presupuesto cerrado y orden de trabajo creada correctamente.');

            return redirect()->route('work-orders.edit', $workOrder->id);

        } catch (Exception $e) {
            Flux::toast('Error al cerrar presupuesto: '.$e->getMessage(), variant: 'danger');
        }
    }

    public function render()
    {
        $productsQuery = Product::with('stock');

        if ($this->productSearch !== '') {
            $productsQuery->where(function ($q) {
                $q->where('product_name', 'like', "%{$this->productSearch}%")
                    ->orWhere('product_code', 'like', "%{$this->productSearch}%")
                    ->orWhere('bar_code', 'like', "%{$this->productSearch}%");
            });
        }

        $modalProducts = $productsQuery->paginate(10, ['*'], 'productPage');
        $destinations = WorkOrderDestination::cases();

        return view('livewire.budget.edit', [
            'totalAmount' => $this->budget?->total_amount ?? 0,
            'modalProducts' => $modalProducts,
            'destinations' => $destinations,
        ]);
    }
}
