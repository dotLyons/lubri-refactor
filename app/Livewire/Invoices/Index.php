<?php

namespace App\Livewire\Invoices;

use App\Src\Invoices\Models\Invoice;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public string $dateFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Invoice::with(['customer', 'workOrder.vehicle', 'payments.cardPlan.card'])
            ->latest();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('id', 'like', '%'.$this->search.'%')
                    ->orWhereHas('customer', function ($q2) {
                        $q2->where('first_name', 'like', '%'.$this->search.'%')
                            ->orWhere('last_name', 'like', '%'.$this->search.'%')
                            ->orWhere('dni', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('workOrder', function ($q2) {
                        $q2->where('id', 'like', '%'.$this->search.'%');
                    });
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->dateFilter) {
            $query->whereDate('created_at', $this->dateFilter);
        }

        $invoices = $query->paginate(15);

        return view('livewire.invoices.index', [
            'invoices' => $invoices,
        ]);
    }
}
