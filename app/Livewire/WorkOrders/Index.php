<?php

namespace App\Livewire\WorkOrders;

use App\Src\WorkOrders\Models\WorkOrder;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    protected $listeners = [
        'work-order-created' => '$refresh',
        'work-order-updated' => '$refresh',
        'work-order-deleted' => '$refresh',
    ];

    public function deleteWorkOrder($id)
    {
        $order = WorkOrder::findOrFail($id);
        
        if ($order->status->value === 'closed') {
            return;
        }

        $order->items()->delete();
        $order->delete();

        $this->dispatch('work-order-deleted');
    }

    public function render()
    {
        $workOrders = WorkOrder::with(['customer', 'vehicle', 'user', 'items'])
            ->when($this->search, function ($query) {
                $query->whereHas('customer', function ($q) {
                    $q->where('first_name', 'like', "%{$this->search}%")
                      ->orWhere('last_name', 'like', "%{$this->search}%")
                      ->orWhere('dni', 'like', "%{$this->search}%");
                })->orWhereHas('vehicle', function ($q) {
                    $q->where('license_plate', 'like', "%{$this->search}%");
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.work-orders.index', [
            'workOrders' => $workOrders,
        ]);
    }
}
