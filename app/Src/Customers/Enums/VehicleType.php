<?php

namespace App\Src\Customers\Enums;

enum VehicleType: string
{
    case Motorcycle = 'motorcycle';
    case Car = 'car';
    case Van = 'van';
    case PickupTruck = 'pickup_truck';

    public function label(): string
    {
        return match($this) {
            self::Motorcycle => 'Moto',
            self::Car => 'Auto',
            self::Van => 'Furgoneta',
            self::PickupTruck => 'Camioneta',
        };
    }
}
