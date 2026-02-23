<?php

namespace App\Livewire\POS\Register;

use App\Src\POS\Actions\OpenCashRegister;
use Flux\Flux;
use Livewire\Component;

class Open extends Component
{
    public string $opening_amount = '';

    public function save(OpenCashRegister $openCashRegisterAction)
    {
        $this->validate([
            'opening_amount' => ['required', 'numeric', 'min:0'],
        ]);

        try {
            $openCashRegisterAction->execute(auth()->user(), (float) $this->opening_amount);
            
            $this->dispatch('register-opened');
            
            $this->reset('opening_amount');
            Flux::modal('open-register')->close();
            
            Flux::toast('Caja abierta exitosamente.');
        } catch (\Exception $e) {
            Flux::toast($e->getMessage(), variant: 'danger');
        }
    }

    public function render()
    {
        return view('livewire.pos.register.open');
    }
}
