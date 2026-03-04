<?php

namespace App\Livewire\WorkOrders;

use App\Src\Customers\Models\Customer;
use App\Src\Customers\Models\Vehicle;
use App\Src\WorkOrders\Enums\WorkOrderDestination;
use App\Src\WorkOrders\Models\WorkOrder;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{
    public $destination = '';
    public $customer_id = '';
    public $vehicle_id = '';
    public $scheduled_at = '';

    public $customers = [];
    public $vehicles = [];

    public function mount()
    {
        $this->customers = Customer::orderBy('last_name')->get();
        $this->scheduled_at = now()->addDay()->format('Y-m-d\TH:i');
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
            'destination' => 'required|string',
            'customer_id' => 'required|exists:customers,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'scheduled_at' => 'required|date',
        ]);

        WorkOrder::create([
            'customer_id' => $this->customer_id,
            'vehicle_id' => $this->vehicle_id,
            'user_id' => Auth::id(),
            'destination' => $this->destination,
            'scheduled_at' => $this->scheduled_at,
            'status' => 'open', 
        ]);

        $this->dispatch('work-order-created');
        
        $this->reset(['destination', 'customer_id', 'vehicle_id', 'scheduled_at']);
        $this->scheduled_at = now()->addDay()->format('Y-m-d\TH:i');
        
        Flux::modal('create-work-order')->close();
    }

    public function render()
    {
        $destinations = WorkOrderDestination::cases();

        return view('livewire.work-orders.create', [
            'destinations' => $destinations,
        ]);
    }
}
