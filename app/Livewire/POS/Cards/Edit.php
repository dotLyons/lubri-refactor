<?php

namespace App\Livewire\POS\Cards;

use App\Src\POS\Enums\CardType;
use App\Src\POS\Models\Card;
use Flux\Flux;
use Livewire\Component;

class Edit extends Component
{
    public ?Card $card = null;
    public string $name = '';
    public string $type = 'credit';
    
    // Arrays for dynamic plans
    public array $plans = [];

    protected $listeners = ['edit-card' => 'loadCard'];

    public function loadCard($cardId)
    {
        $this->card = Card::with('plans')->findOrFail($cardId);
        $this->name = $this->card->name;
        $this->type = $this->card->type instanceof CardType ? $this->card->type->value : $this->card->type;

        $this->plans = $this->card->plans->map(function ($plan) {
            return [
                'id' => $plan->id,
                'name' => $plan->name,
                'installments' => $plan->installments,
                'surcharge_percentage' => $plan->surcharge_percentage,
                'is_promotion' => $plan->is_promotion,
            ];
        })->toArray();

        if (count($this->plans) === 0) {
            $this->addPlan();
        }

        Flux::modal('edit-card')->show();
    }

    public function updatedType()
    {
        // When switching types, we might want to warn the user or just clear plans.
        // For safety in edit, if they switch to debit, we force a single payment.
        if ($this->type === 'debit') {
            $this->plans = [[
                'id' => null, // new plan
                'name' => 'Pago Débito',
                'installments' => 1,
                'surcharge_percentage' => 0,
                'is_promotion' => false,
            ]];
        }
    }

    public function addPlan()
    {
        $this->plans[] = [
            'id' => null, // indicates it's a new plan
            'name' => '',
            'installments' => 1,
            'surcharge_percentage' => 0,
            'is_promotion' => false,
        ];
    }

    public function removePlan($index)
    {
        // We just remove it from the array. 
        // Actual deletion from DB happens on save() via sync/delete.
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

        $this->card->update([
            'name' => $this->name,
            'type' => $this->type,
        ]);

        // Keep track of plan IDs that are still in the form
        $keptPlanIds = [];

        foreach ($this->plans as $planData) {
            if (!empty($planData['id'])) {
                // Update existing plan
                $plan = $this->card->plans()->find($planData['id']);
                if ($plan) {
                    $plan->update([
                        'name' => $planData['name'],
                        'installments' => $planData['installments'],
                        'surcharge_percentage' => $planData['surcharge_percentage'],
                        'is_promotion' => $planData['is_promotion'] ?? false,
                    ]);
                    $keptPlanIds[] = $plan->id;
                }
            } else {
                // Create new plan
                $newPlan = $this->card->plans()->create([
                    'name' => $planData['name'],
                    'installments' => $planData['installments'],
                    'surcharge_percentage' => $planData['surcharge_percentage'],
                    'is_promotion' => $planData['is_promotion'] ?? false,
                    'is_active' => true,
                ]);
                $keptPlanIds[] = $newPlan->id;
            }
        }

        // Delete plans that were removed from the form
        $this->card->plans()->whereNotIn('id', $keptPlanIds)->delete();

        $this->dispatch('card-updated');
        Flux::modal('edit-card')->close();
    }

    public function render()
    {
        return view('livewire.pos.cards.edit');
    }
}
