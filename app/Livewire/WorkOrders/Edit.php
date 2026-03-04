<?php

namespace App\Livewire\WorkOrders;

use App\Src\Inventory\Models\Product;
use App\Src\POS\Actions\RegisterMovement;
use App\Src\POS\Enums\MovementType;
use App\Src\POS\Enums\PaymentMethod;
use App\Src\POS\Models\CashRegister;
use App\Src\WorkOrders\Enums\WorkOrderStatus;
use App\Src\WorkOrders\Models\WorkOrder;
use Exception;
use Flux\Flux;
use App\Src\POS\Models\Card;
use App\Src\POS\Models\CardPlan;
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
    
    public $paymentMethod = 'cash';
    public $selectedCardId = '';
    public $selectedPlanId = '';

    public function updatedPaymentMethod()
    {
        $this->selectedCardId = '';
        $this->selectedPlanId = '';
    }

    public function updatedSelectedCardId()
    {
        $this->selectedPlanId = '';
    }

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
        if ($this->workOrder->status->value === 'closed') return;

        $quantityToAdd = 1;

        $product = Product::with('stock')->find($productId);
        if (!$product) return;

        $stockAvailable = $product->stock ? $product->stock->quantity : 0;
        if ($quantityToAdd > $stockAvailable) {
            Flux::toast('Stock insuficiente. Solo quedan ' . $stockAvailable . ' unidades de ' . $product->product_name . '.', variant: 'danger');
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
            }
        });

        $this->workOrder->refresh();
        $this->refreshItems();
        
        $this->dispatch('work-order-updated');
        Flux::toast('Producto agregado correctamente.');
    }

    public function removeItem($itemId)
    {
        if ($this->workOrder->status->value === 'closed') return;

        $item = $this->workOrder->items()->find($itemId);
        
        if ($item) {
            DB::transaction(function () use ($item) {
                $product = $item->product;
                if ($product->stock) {
                    $product->stock->update([
                        'quantity' => $product->stock->quantity + $item->quantity,
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
        if ($this->workOrder->status->value === 'closed') return;
        
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
                    Flux::toast('Stock insuficiente. Solo te quedan ' . $stockAvailable . ' unidades extra disponibles en el inventario para poder sumar.', variant: 'danger');
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

    public function chargeAndClose()
    {
        if ($this->workOrder->status->value === 'closed') return;
        
        $baseTotalAmount = $this->workOrder->total_amount;
        $totalAmount = $baseTotalAmount;

        if (in_array($this->paymentMethod, ['credit_card', 'debit_card']) && $this->selectedPlanId) {
            $plan = CardPlan::find($this->selectedPlanId);
            if ($plan) {
                $surcharge = ($baseTotalAmount * $plan->surcharge_percentage) / 100;
                $totalAmount = $baseTotalAmount + $surcharge;
            }
        }
        
        if ($totalAmount <= 0) {
            session()->flash('error_payment', 'No se puede cobrar una orden con total 0.');
            return;
        }

        if (in_array($this->paymentMethod, ['credit_card', 'debit_card']) && !$this->selectedPlanId) {
            session()->flash('error_payment', 'Debe seleccionar un plan de cuotas para el pago con tarjeta.');
            return;
        }

        try {
            DB::transaction(function () use ($totalAmount) {
                $registerMovement = new RegisterMovement();
                $registerMovement->execute(
                    user: Auth::user(),
                    type: MovementType::Income,
                    amount: $totalAmount,
                    paymentMethod: PaymentMethod::from($this->paymentMethod),
                    cardPlanId: $this->selectedPlanId ?: null, 
                    description: "Cobro Orden de Trabajo #" . $this->workOrder->id . " - " . $this->workOrder->vehicle->license_plate,
                    isManual: false 
                );

                $this->workOrder->update([
                    'status' => WorkOrderStatus::Closed,
                ]);
            });

            session()->flash('success_payment', 'Orden cerrada y cobrada con éxito.');
            return redirect()->route('work-orders.index');

        } catch (Exception $e) {
            session()->flash('error_payment', $e->getMessage());
        }
    }

    public function render()
    {
        $productsQuery = Product::with('stock');
        
        if ($this->productSearch !== '') {
            $productsQuery->where(function($q) {
                $q->where('product_name', 'like', "%{$this->productSearch}%")
                  ->orWhere('product_code', 'like', "%{$this->productSearch}%")
                  ->orWhere('bar_code', 'like', "%{$this->productSearch}%");
            });
        }
        
        $modalProducts = $productsQuery->paginate(10, ['*'], 'productPage');

        $cards = collect();
        $plans = collect();
        
        if ($this->paymentMethod === 'credit_card' || $this->paymentMethod === 'debit_card') {
            $type = $this->paymentMethod === 'credit_card' ? 'credit' : 'debit';
            $cards = Card::where('type', $type)->where('is_active', true)->get();
        }

        if ($this->selectedCardId) {
            $plans = CardPlan::where('card_id', $this->selectedCardId)->where('is_active', true)->get();
        }

        $baseTotalAmount = $this->workOrder?->total_amount ?? 0;
        $totalAmount = $baseTotalAmount;
        $surchargeAmount = 0;

        if (in_array($this->paymentMethod, ['credit_card', 'debit_card']) && $this->selectedPlanId) {
            $plan = CardPlan::find($this->selectedPlanId);
            if ($plan) {
                $surchargeAmount = ($baseTotalAmount * $plan->surcharge_percentage) / 100;
                $totalAmount = $baseTotalAmount + $surchargeAmount;
            }
        }

        return view('livewire.work-orders.edit', [
            'baseTotalAmount' => $baseTotalAmount,
            'totalAmount' => $totalAmount,
            'surchargeAmount' => $surchargeAmount,
            'cards' => $cards,
            'plans' => $plans,
            'modalProducts' => $modalProducts,
        ]);
    }
}
