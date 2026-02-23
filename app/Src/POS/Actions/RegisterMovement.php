<?php

namespace App\Src\POS\Actions;

use App\Models\User;
use App\Src\POS\Enums\CashRegisterStatus;
use App\Src\POS\Enums\MovementType;
use App\Src\POS\Enums\PaymentMethod;
use App\Src\POS\Models\CashRegister;
use App\Src\POS\Models\CashRegisterMovement;
use Exception;

class RegisterMovement
{
    public function execute(
        User $user,
        MovementType $type,
        float $amount,
        PaymentMethod $paymentMethod,
        ?int $cardPlanId = null,
        ?string $description = null,
        bool $isManual = false,
        ?string $passcode = null
    ): CashRegisterMovement {
        // Validation for manual adjustments passcode
        if ($isManual) {
            if ($passcode !== '12265000') {
                throw new Exception('El código de autorización es incorrecto.');
            }
        }

        $openRegister = CashRegister::where('status', CashRegisterStatus::Open)->first();

        if (! $openRegister) {
            throw new Exception('No se encontró una caja abierta para registrar el movimiento.');
        }

        return $openRegister->movements()->create([
            'user_id' => $user->id,
            'type' => $type,
            'amount' => $amount,
            'payment_method' => $paymentMethod,
            'card_plan_id' => $cardPlanId,
            'description' => $description,
            'is_manual' => $isManual,
        ]);
    }
}
