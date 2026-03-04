<?php

namespace App\Livewire\Customers;

use App\Src\Customers\Enums\PickupCabinType;
use App\Src\Customers\Enums\VehicleType;
use App\Src\Customers\Models\Customer;
use Flux\Flux;
use Livewire\Component;

class Create extends Component
{
    public $dni;
    public $first_name;
    public $last_name;
    public $primary_phone;
    public $secondary_phone;
    public $birth_date;

    public $vehicles = [];

    public function mount()
    {
        $this->addVehicle();
    }

    public function addVehicle()
    {
        $this->vehicles[] = [
            'type' => VehicleType::Car->value,
            'brand' => '',
            'model' => '',
            'year' => date('Y'),
            'license_plate' => '',
            'version' => '',
            'color' => '',
            'pickup_cabin_type' => null,
            'engine_displacement' => '',
        ];
    }

    public function removeVehicle($index)
    {
        unset($this->vehicles[$index]);
        $this->vehicles = array_values($this->vehicles);
    }

    public function save()
    {
        $this->validate([
            'dni' => 'required|string|unique:customers,dni',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'primary_phone' => 'required|string',
            'secondary_phone' => 'nullable|string',
            'birth_date' => 'nullable|date',
            
            'vehicles' => 'array',
            'vehicles.*.type' => 'required|string',
            'vehicles.*.brand' => 'required|string',
            'vehicles.*.model' => 'required|string',
            'vehicles.*.year' => 'required|integer',
            'vehicles.*.license_plate' => 'required|string',
            'vehicles.*.version' => 'nullable|string',
            'vehicles.*.color' => 'nullable|string',
            'vehicles.*.pickup_cabin_type' => 'nullable|string',
            'vehicles.*.engine_displacement' => 'nullable|string',
        ]);

        $customer = Customer::create([
            'dni' => $this->dni,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'primary_phone' => $this->primary_phone,
            'secondary_phone' => $this->secondary_phone,
            'birth_date' => $this->birth_date,
        ]);

        foreach ($this->vehicles as $vehicleData) {
            if (!empty($vehicleData['brand']) && !empty($vehicleData['model']) && !empty($vehicleData['license_plate'])) {
                $customer->vehicles()->create([
                    'type' => $vehicleData['type'],
                    'brand' => $vehicleData['brand'],
                    'model' => $vehicleData['model'],
                    'year' => $vehicleData['year'],
                    'license_plate' => $vehicleData['license_plate'],
                    'version' => $vehicleData['version'],
                    'color' => $vehicleData['color'],
                    'pickup_cabin_type' => $vehicleData['type'] === VehicleType::PickupTruck->value ? $vehicleData['pickup_cabin_type'] : null,
                    'engine_displacement' => $vehicleData['type'] === VehicleType::Motorcycle->value ? $vehicleData['engine_displacement'] : null,
                ]);
            }
        }

        $this->dispatch('customer-created');
        
        $this->reset(['dni', 'first_name', 'last_name', 'primary_phone', 'secondary_phone', 'birth_date', 'vehicles']);
        $this->addVehicle();
        
        Flux::modal('create-customer')->close();
    }

    public function render()
    {
        $vehicleTypes = VehicleType::cases();
        $pickupCabinTypes = PickupCabinType::cases();

        return view('livewire.customers.create', [
            'vehicleTypes' => $vehicleTypes,
            'pickupCabinTypes' => $pickupCabinTypes,
        ]);
    }
}
