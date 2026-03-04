<?php

namespace App\Livewire\WorkOrders;

use App\Src\Inventory\Models\Product;
use App\Src\Inventory\Models\StockMovement;
use App\Src\Invoices\Models\Invoice;
use App\Src\WorkOrders\Enums\WorkOrderStatus;
use App\Src\WorkOrders\Models\WorkOrder;
use Exception;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Edit extends Component
{
    use WithPagination;

    public ?WorkOrder $workOrder = null;

    public $scheduled_at;

    public $items = [];

    public $productSearch = '';

    public function mount($id)
    {
        $this->workOrder = WorkOrder::with(['customer', 'vehicle', 'items.product.stock'])->findOrFail($id);

        $this->scheduled_at = $this->workOrder->scheduled_at->format('Y-m-d\TH:i');

        $this->refreshItems();
    }

    public function refreshItems()
    {
        $this->items = $this->workOrder->items->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->product->product_name,
                'category' => $item->product->category ? $item->product->category->category_name : 'Sin categoría',
                'quantity' => (int) $item->quantity,
                'unit_price' => $item->unit_price,
                'subtotal' => $item->subtotal,
                'max_quantity' => (int) $item->quantity + ($item->product->stock ? (int) $item->product->stock->quantity : 0),
            ];
        })->toArray();
    }

    public function updateSchedule()
    {
        $this->validate([
            'scheduled_at' => 'required|date',
        ]);

        $this->workOrder->update([
            'scheduled_at' => $this->scheduled_at,
        ]);

        $this->dispatch('work-order-updated');
        session()->flash('success_schedule', 'Horario actualizado correctamente.');
    }

    public function addItem($productId)
    {
        if ($this->workOrder->status->value === 'closed') {
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

        $existingItem = $this->workOrder->items()->where('product_id', $product->id)->first();

        DB::transaction(function () use ($existingItem, $product, $quantityToAdd) {
            if ($existingItem) {
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $quantityToAdd,
                ]);
            } else {
                $this->workOrder->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantityToAdd,
                    'unit_price' => $product->sale_price,
                ]);
            }
            if ($product->stock) {
                $product->stock->update([
                    'quantity' => $product->stock->quantity - $quantityToAdd,
                ]);

                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'out',
                    'quantity' => $quantityToAdd,
                    'reason' => 'Uso en Orden de Trabajo #'.$this->workOrder->id,
                ]);
            }
        });

        $this->workOrder->refresh();
        $this->refreshItems();

        $this->dispatch('work-order-updated');
        Flux::toast('Producto agregado correctamente.');
    }

    public function removeItem($itemId)
    {
        if ($this->workOrder->status->value === 'closed') {
            return;
        }

        $item = $this->workOrder->items()->find($itemId);

        if ($item) {
            DB::transaction(function () use ($item) {
                $product = $item->product;
                if ($product->stock) {
                    $product->stock->update([
                        'quantity' => $product->stock->quantity + $item->quantity,
                    ]);

                    StockMovement::create([
                        'product_id' => $product->id,
                        'user_id' => Auth::id(),
                        'type' => 'in',
                        'quantity' => $item->quantity,
                        'reason' => 'Devolución de Orden de Trabajo #'.$this->workOrder->id,
                    ]);
                }
                $item->delete();
            });

            $this->workOrder->refresh();
            $this->refreshItems();
            $this->dispatch('work-order-updated');
        }
    }

    public function updateItemQuantity($itemId, $newQuantity)
    {
        if ($this->workOrder->status->value === 'closed') {
            return;
        }

        $newQuantity = (int) $newQuantity;

        if ($newQuantity <= 0) {
            $this->removeItem($itemId);

            return;
        }

        $item = $this->workOrder->items()->with('product.stock')->find($itemId);
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

            DB::transaction(function () use ($item, $newQuantity, $difference) {
                $product = $item->product;
                if ($product->stock) {
                    $product->stock->update([
                        'quantity' => $product->stock->quantity - $difference,
                    ]);

                    StockMovement::create([
                        'product_id' => $product->id,
                        'user_id' => Auth::id(),
                        'type' => $difference > 0 ? 'out' : 'in',
                        'quantity' => abs($difference),
                        'reason' => ($difference > 0 ? 'Adición extra a Uso' : 'Devolución parcial').' en Orden de Trabajo #'.$this->workOrder->id,
                    ]);
                }

                $item->update([
                    'quantity' => $newQuantity,
                ]);
            });

            $this->workOrder->refresh();
            $this->refreshItems();
            $this->dispatch('work-order-updated');
        }
    }

    public function generateInvoice()
    {
        if ($this->workOrder->status->value === 'closed') {
            return;
        }

        $totalAmount = $this->workOrder->total_amount;

        if ($totalAmount <= 0) {
            session()->flash('error_payment', 'No se puede facturar una orden con total 0.');

            return;
        }

        try {
            $invoice = DB::transaction(function () use ($totalAmount) {
                $invoice = Invoice::create([
                    'customer_id' => $this->workOrder->customer_id,
                    'work_order_id' => $this->workOrder->id,
                    'total_amount' => $totalAmount,
                    'paid_amount' => 0,
                    'balance_due' => $totalAmount,
                    'status' => 'pending',
                ]);

                $this->workOrder->update([
                    'status' => WorkOrderStatus::Closed,
                ]);

                return $invoice;
            });

            return redirect()->route('invoices.pay', $invoice->id);

        } catch (Exception $e) {
            session()->flash('error_payment', 'Error al generar la factura: '.$e->getMessage());
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

        return view('livewire.work-orders.edit', [
            'baseTotalAmount' => $this->workOrder?->total_amount ?? 0,
            'totalAmount' => $this->workOrder?->total_amount ?? 0,
            'modalProducts' => $modalProducts,
        ]);
    }
}
