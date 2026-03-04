<?php

namespace App\Livewire\Budget;

use App\Src\Budget\Models\Budget;
use App\Src\Customers\Models\Customer;
use App\Src\Customers\Models\Vehicle;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{
    public $customer_id = '';

    public $vehicle_id = '';

    public $notes = '';

    public $customers = [];

    public $vehicles = [];

    public function mount()
    {
        $this->customers = Customer::orderBy('last_name')->get();
    }

    public function updatedCustomerId($value)
    {
        if ($value) {
            $this->vehicles = Vehicle::where('customer_id', $value)->get();
            $this->vehicle_id = '';
        } else {
            $this->vehicles = [];
        }
    }

    public function save()
    {
        $this->validate([
            'customer_id' => 'required|exists:customers,id',
            'vehicle_id' => 'required|exists:vehicles,id',
        ]);

        Budget::create([
            'customer_id' => $this->customer_id,
            'vehicle_id' => $this->vehicle_id,
            'user_id' => Auth::id(),
            'status' => 'open',
            'notes' => $this->notes,
        ]);

        $this->dispatch('budget-created');

        $this->reset(['customer_id', 'vehicle_id', 'notes']);

        Flux::modal('create-budget')->close();
    }

    public function render()
    {
        return view('livewire.budget.create');
    }
}
