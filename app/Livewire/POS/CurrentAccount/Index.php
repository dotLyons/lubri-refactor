<?php

namespace App\Livewire\POS\CurrentAccount;

use App\Src\Invoices\Models\InvoicePayment;
use App\Src\POS\Enums\PaymentMethod;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    protected $listeners = [
        'payment-registered' => '$refresh',
    ];

    public function render()
    {
        $payments = InvoicePayment::with(['invoice.customer', 'invoice.workOrder.vehicle', 'user'])
            ->where('payment_method', PaymentMethod::CurrentAccount)
            ->when($this->search, function ($query) {
                $query->whereHas('invoice.customer', function ($q) {
                    $q->where('first_name', 'like', '%'.$this->search.'%')
                        ->orWhere('last_name', 'like', '%'.$this->search.'%')
                        ->orWhere('dni', 'like', '%'.$this->search.'%');
                })->orWhereHas('invoice.workOrder.vehicle', function ($q) {
                    $q->where('license_plate', 'like', '%'.$this->search.'%');
                });
            })
            ->latest()
            ->paginate(15);

        $totalPending = InvoicePayment::where('payment_method', PaymentMethod::CurrentAccount)
            ->whereHas('invoice', function ($q) {
                $q->where('status', 'pending');
            })
            ->sum('amount');

        return view('livewire.pos.current-account.index', [
            'payments' => $payments,
            'totalPending' => $totalPending,
        ]);
    }
}
