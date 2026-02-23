<?php

namespace App\Livewire\POS\Register;

use App\Src\POS\Enums\CashRegisterStatus;
use App\Src\POS\Enums\MovementType;
use App\Src\POS\Models\CashRegister;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $paymentMethodFilter = '';
    public string $typeFilter = '';

    protected $listeners = [
        'register-opened' => '$refresh',
        'register-closed' => '$refresh',
        'movement-registered' => '$refresh',
    ];

    public function render()
    {
        $activeRegister = CashRegister::with(['movements.user', 'movements.cardPlan'])
            ->where('status', CashRegisterStatus::Open)
            ->first();

        // Calculate current totals if open
        $cashInDrawer = 0;
        // Detailed breakdown variables
        $totalIncomes = 0;
        $totalExpenses = 0;
        $movements = null;

        if ($activeRegister) {
            $totalIncomes = $activeRegister->movements()->where('type', MovementType::Income)->sum('amount');
            $totalExpenses = $activeRegister->movements()->where('type', MovementType::Expense)->sum('amount');
            
            // Expected Amount ignores payment_method constraints as per user request
            $cashInDrawer = $activeRegister->opening_amount + $totalIncomes - $totalExpenses;
            
            $movementsQuery = $activeRegister->movements()
                ->with(['user', 'cardPlan.card'])
                ->latest();
                
            if ($this->paymentMethodFilter !== '') {
                $movementsQuery->where('payment_method', $this->paymentMethodFilter);
            }
            if ($this->typeFilter !== '') {
                $movementsQuery->where('type', $this->typeFilter);
            }
            
            $movements = $movementsQuery->paginate(10);
        }

        return view('livewire.pos.register.index', [
            'activeRegister' => $activeRegister,
            'movements' => $movements,
            'cashInDrawer' => $cashInDrawer,
            'totalIncomes' => $totalIncomes,
            'totalExpenses' => $totalExpenses,
        ]);
    }
}
