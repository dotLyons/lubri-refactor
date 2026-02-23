<?php

namespace App\Src\POS\Enums;

enum PaymentMethod: string
{
    case Cash = 'cash';
    case Transfer = 'transfer';
    case DebitCard = 'debit_card';
    case CreditCard = 'credit_card';

    public function label(): string
    {
        return match ($this) {
            self::Cash => 'Efectivo',
            self::Transfer => 'Transferencia',
            self::DebitCard => 'Tarjeta de Débito',
            self::CreditCard => 'Tarjeta de Crédito',
        };
    }
}
