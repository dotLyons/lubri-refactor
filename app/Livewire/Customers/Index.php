<?php

namespace App\Livewire\Customers;

use App\Src\Customers\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    protected $listeners = [
        'customer-created' => '$refresh',
        'customer-updated' => '$refresh',
        'customer-deleted' => '$refresh',
    ];

    public function deleteCustomer($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->vehicles()->delete();
        $customer->delete();
        
        $this->dispatch('customer-deleted');
    }

    public function render()
    {
        $customers = Customer::with('vehicles')
            ->when($this->search, function ($query) {
                $query->where('first_name', 'like', "%{$this->search}%")
                      ->orWhere('last_name', 'like', "%{$this->search}%")
                      ->orWhere('dni', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(10);

        return view('livewire.customers.index', [
            'customers' => $customers,
        ]);
    }
}
