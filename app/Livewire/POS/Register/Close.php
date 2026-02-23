<?php

namespace App\Livewire\POS\Register;

use App\Src\POS\Actions\CloseCashRegister;
use App\Src\POS\Enums\CashRegisterStatus;
use App\Src\POS\Models\CashRegister;
use Flux\Flux;
use Livewire\Component;

class Close extends Component
{
    public string $closing_actual_amount = '';
    public ?CashRegister $register = null;

    protected $listeners = ['register-close-request' => 'loadRegister'];

    public function loadRegister()
    {
        $this->register = CashRegister::where('status', CashRegisterStatus::Open)->first();
        if ($this->register) {
            $this->reset('closing_actual_amount');
            Flux::modal('close-register')->show();
        }
    }

    public function save(CloseCashRegister $closeCashRegisterAction)
    {
        $this->validate([
            'closing_actual_amount' => ['required', 'numeric', 'min:0'],
        ]);

        if (! $this->register) {
            return;
        }

        try {
            $closeCashRegisterAction->execute($this->register, (float) $this->closing_actual_amount);
            
            $this->dispatch('register-closed');
            Flux::modal('close-register')->close();
            
            Flux::toast('Caja cerrada con éxito.', variant: 'success');
        } catch (\Exception $e) {
            Flux::toast($e->getMessage(), variant: 'danger');
        }
    }

    public function render()
    {
        return view('livewire.pos.register.close');
    }
}
