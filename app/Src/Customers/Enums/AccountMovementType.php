<?php

namespace App\Src\Customers\Enums;

enum AccountMovementType: string
{
    case Debit = 'debit';
    case Credit = 'credit';

    public function label(): string
    {
        return match ($this) {
            self::Debit => 'Debe',
            self::Credit => 'Haber',
        };
    }
}
