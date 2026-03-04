<?php

namespace App\Src\WorkOrders\Enums;

enum WorkOrderDestination: string
{
    case Workshop = 'workshop';
    case CarWash = 'car_wash';

    public function label(): string
    {
        return match($this) {
            self::Workshop => 'Taller / Lubricentro',
            self::CarWash => 'Lavadero',
        };
    }
}
