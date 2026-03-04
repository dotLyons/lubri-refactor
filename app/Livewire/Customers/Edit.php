<?php

namespace App\Livewire\Customers;

use App\Src\Customers\Enums\PickupCabinType;
use App\Src\Customers\Enums\VehicleType;
use App\Src\Customers\Models\Customer;
use Flux\Flux;
use Livewire\Component;

class Edit extends Component
{
    public ?Customer $customer = null;

    public $dni;
    public $first_name;
    public $last_name;
    public $primary_phone;
    public $secondary_phone;
    public $birth_date;

    public $vehicles = [];

    protected $listeners = [
        'edit-customer' => 'loadCustomer',
    ];

    public function loadCustomer($id)
    {
        $this->customer = Customer::with('vehicles')->findOrFail($id);

        $this->dni = $this->customer->dni;
        $this->first_name = $this->customer->first_name;
        $this->last_name = $this->customer->last_name;
        $this->primary_phone = $this->customer->primary_phone;
        $this->secondary_phone = $this->customer->secondary_phone;
        $this->birth_date = $this->customer->birth_date ? $this->customer->birth_date->format('Y-m-d') : null;

        $this->vehicles = $this->customer->vehicles->map(function ($vehicle) {
            return [
                'id' => $vehicle->id,
                'type' => $vehicle->type->value,
                'brand' => $vehicle->brand,
                'model' => $vehicle->model,
                'year' => $vehicle->year,
                'license_plate' => $vehicle->license_plate,
                'version' => $vehicle->version,
                'color' => $vehicle->color,
                'pickup_cabin_type' => $vehicle->pickup_cabin_type?->value,
                'engine_displacement' => $vehicle->engine_displacement,
            ];
        })->toArray();

        if (empty($this->vehicles)) {
            $this->addVehicle();
        }

        Flux::modal('edit-customer')->show();
    }

    public function addVehicle()
    {
        $this->vehicles[] = [
            'id' => null,
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
        if (isset($this->vehicles[$index]['id']) && $this->vehicles[$index]['id']) {
            $this->customer->vehicles()->where('id', $this->vehicles[$index]['id'])->delete();
        }

        unset($this->vehicles[$index]);
        $this->vehicles = array_values($this->vehicles);
    }

    public function save()
    {
        $this->validate([
            'dni' => 'required|string|unique:customers,dni,' . $this->customer->id,
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

        $this->customer->update([
            'dni' => $this->dni,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'primary_phone' => $this->primary_phone,
            'secondary_phone' => $this->secondary_phone,
            'birth_date' => $this->birth_date,
        ]);

        $vehicleIdsToKeep = [];

        foreach ($this->vehicles as $vehicleData) {
            if (!empty($vehicleData['brand']) && !empty($vehicleData['model']) && !empty($vehicleData['license_plate'])) {
                
                $data = [
                    'type' => $vehicleData['type'],
                    'brand' => $vehicleData['brand'],
                    'model' => $vehicleData['model'],
                    'year' => $vehicleData['year'],
                    'license_plate' => $vehicleData['license_plate'],
                    'version' => $vehicleData['version'],
                    'color' => $vehicleData['color'],
                    'pickup_cabin_type' => $vehicleData['type'] === VehicleType::PickupTruck->value ? $vehicleData['pickup_cabin_type'] : null,
                    'engine_displacement' => $vehicleData['type'] === VehicleType::Motorcycle->value ? $vehicleData['engine_displacement'] : null,
                ];

                if (isset($vehicleData['id']) && $vehicleData['id']) {
                    $vehicle = $this->customer->vehicles()->find($vehicleData['id']);
                    if ($vehicle) {
                        $vehicle->update($data);
                        $vehicleIdsToKeep[] = $vehicle->id;
                    }
                } else {
                    $newVehicle = $this->customer->vehicles()->create($data);
                    $vehicleIdsToKeep[] = $newVehicle->id;
                }
            }
        }

        if ($this->customer) {
            $this->customer->vehicles()->whereNotIn('id', $vehicleIdsToKeep)->delete();
        }

        $this->dispatch('customer-updated');
        
        Flux::modal('edit-customer')->close();
    }

    public function render()
    {
        $vehicleTypes = VehicleType::cases();
        $pickupCabinTypes = PickupCabinType::cases();

        return view('livewire.customers.edit', [
            'vehicleTypes' => $vehicleTypes,
            'pickupCabinTypes' => $pickupCabinTypes,
        ]);
    }
}
