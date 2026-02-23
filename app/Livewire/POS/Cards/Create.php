<?php

namespace App\Livewire\POS\Cards;

use App\Src\POS\Enums\CardType;
use App\Src\POS\Models\Card;
use Flux\Flux;
use Livewire\Component;

class Create extends Component
{
    public string $name = '';
    public string $type = 'credit';
    
    // Arrays for dynamic plans
    public array $plans = [];

    public function mount()
    {
        // Initial empty plan for credit
        $this->addPlan();
    }

    public function updatedType()
    {
        if ($this->type === 'debit') {
            // Debit cards usually have just 1 payment (1 installment)
            $this->plans = [[
                'name' => 'Pago Débito',
                'installments' => 1,
                'surcharge_percentage' => 0,
                'is_promotion' => false,
            ]];
        } else {
            $this->plans = [];
            $this->addPlan();
        }
    }

    public function addPlan()
    {
        $this->plans[] = [
            'name' => '',
            'installments' => 1,
            'surcharge_percentage' => 0,
            'is_promotion' => false,
        ];
    }

    public function removePlan($index)
    {
        unset($this->plans[$index]);
        $this->plans = array_values($this->plans);
        
        if (count($this->plans) === 0) {
            $this->addPlan();
        }
    }

    public function save()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:debit,credit'],
            'plans.*.name' => ['required', 'string', 'max:255'],
            'plans.*.installments' => ['required', 'integer', 'min:1', 'max:120'],
            'plans.*.surcharge_percentage' => ['required', 'numeric', 'min:0'],
            'plans.*.is_promotion' => ['boolean']
        ], [
            'plans.*.name.required' => 'El nombre del plan es obligatorio.',
            'plans.*.installments.required' => 'La cantidad de cuotas es obligatoria.',
            'plans.*.surcharge_percentage.required' => 'El recargo es obligatorio.',
        ]);

        $card = Card::create([
            'name' => $this->name,
            'type' => $this->type,
            'is_active' => true,
        ]);

        foreach ($this->plans as $plan) {
            $card->plans()->create([
                'name' => $plan['name'],
                'installments' => $plan['installments'],
                'surcharge_percentage' => $plan['surcharge_percentage'],
                'is_promotion' => $plan['is_promotion'] ?? false,
                'is_active' => true,
            ]);
        }

        $this->reset(['name', 'type', 'plans']);
        $this->addPlan();

        $this->dispatch('card-created');
        Flux::modal('create-card')->close();
    }

    public function render()
    {
        return view('livewire.pos.cards.create');
    }
}
