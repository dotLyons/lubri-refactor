<?php

namespace App\Src\Customers\Enums;

enum PickupCabinType: string
{
    case Single = 'single';
    case Double = 'double';

    public function label(): string
    {
        return match($this) {
            self::Single => 'Cabina Simple',
            self::Double => 'Cabina Doble',
        };
    }
}
