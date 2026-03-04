<?php

namespace App\Src\Invoices\Enums;

enum InvoiceStatus: string
{
    case Pending = 'pending';
    case Partial = 'partial';
    case Paid = 'paid';
    case Canceled = 'canceled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendiente',
            self::Partial => 'Pago Parcial',
            self::Paid => 'Pagada',
            self::Canceled => 'Anulada',
        };
    }
}
