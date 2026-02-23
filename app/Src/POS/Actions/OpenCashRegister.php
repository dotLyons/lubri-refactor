<?php

namespace App\Src\POS\Actions;

use App\Models\User;
use App\Src\POS\Enums\CashRegisterStatus;
use App\Src\POS\Models\CashRegister;
use Exception;

class OpenCashRegister
{
    public function execute(User $user, float $openingAmount): CashRegister
    {
        $openRegister = CashRegister::where('status', CashRegisterStatus::Open)->first();

        if ($openRegister) {
            throw new Exception('Ya existe una caja abierta en el sistema.');
        }

        return CashRegister::create([
            'user_id' => $user->id,
            'opened_at' => now(),
            'opening_amount' => $openingAmount,
            'status' => CashRegisterStatus::Open,
        ]);
    }
}
