<?php

namespace App\Src\POS\Actions;

use App\Src\POS\Enums\CashRegisterStatus;
use App\Src\POS\Enums\MovementType;
use App\Src\POS\Models\CashRegister;
use Exception;

class CloseCashRegister
{
    public function execute(CashRegister $cashRegister, ?float $closingActualAmount = null, bool $automatically = false): CashRegister
    {
        if ($cashRegister->status === CashRegisterStatus::Closed) {
            throw new Exception('La caja ya se encuentra cerrada.');
        }

        // Calculate expected amount
        // Expected = Opening Amount + Total Incomes - Total Expenses
        $incomes = $cashRegister->movements()->where('type', MovementType::Income)->sum('amount');
        $expenses = $cashRegister->movements()->where('type', MovementType::Expense)->sum('amount');

        $expectedAmount = $cashRegister->opening_amount + $incomes - $expenses;
        
        // Difference is calculated only if an actual money amount was provided (not automatic)
        $difference = null;
        if ($closingActualAmount !== null) {
            $difference = $closingActualAmount - $expectedAmount;
        }

        $cashRegister->update([
            'closed_at' => now(),
            'closing_expected_amount' => $expectedAmount,
            'closing_actual_amount' => $closingActualAmount,
            'difference' => $difference,
            'status' => CashRegisterStatus::Closed,
            'closed_automatically' => $automatically,
        ]);

        return $cashRegister;
    }
}
