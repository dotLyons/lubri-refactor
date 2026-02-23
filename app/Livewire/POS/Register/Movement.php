<?php

namespace App\Livewire\POS\Register;

use App\Src\POS\Actions\RegisterMovement;
use App\Src\POS\Enums\MovementType;
use App\Src\POS\Enums\PaymentMethod;
use Flux\Flux;
use Exception;
use Livewire\Component;

class Movement extends Component
{
    public string $type = 'income';
    public string $amount = '';
    public string $description = '';
    public string $passcode = '';

    public function save(RegisterMovement $registerMovementAction)
    {
        $this->validate([
            'type' => ['required', 'string', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'description' => ['required', 'string', 'max:255'],
            'passcode' => ['required', 'string']
        ]);

        try {
            $registerMovementAction->execute(
                user: auth()->user(),
                type: MovementType::from($this->type),
                amount: (float) $this->amount,
                paymentMethod: PaymentMethod::Cash,
                description: $this->description,
                isManual: true,
                passcode: $this->passcode
            );

            $this->dispatch('movement-registered');
            
            $this->reset(['type', 'amount', 'description', 'passcode']);
            Flux::modal('manual-movement')->close();
            
            Flux::toast('Movimiento registrado exitosamente.', variant: 'success');
        } catch (Exception $e) {
            Flux::toast($e->getMessage(), variant: 'danger');
        }
    }

    public function render()
    {
        return view('livewire.pos.register.movement');
    }
}
