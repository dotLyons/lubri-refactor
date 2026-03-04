<?php

namespace App\Src\WorkOrders\Enums;

enum WorkOrderStatus: string
{
    case Open = 'open';
    case Closed = 'closed';

    public function label(): string
    {
        return match($this) {
            self::Open => 'Abierta',
            self::Closed => 'Cerrada',
        };
    }
}
