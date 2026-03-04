<?php

namespace App\Livewire\Budget;

use App\Src\Budget\Models\Budget;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    protected $listeners = [
        'budget-created' => '$refresh',
        'budget-updated' => '$refresh',
        'budget-deleted' => '$refresh',
    ];

    public function deleteBudget($id)
    {
        $budget = Budget::findOrFail($id);

        if ($budget->status->value === 'closed') {
            return;
        }

        $budget->items()->delete();
        $budget->delete();

        $this->dispatch('budget-deleted');
    }

    public function render()
    {
        $budgets = Budget::with(['customer', 'vehicle', 'user', 'items'])
            ->when($this->search, function ($query) {
                $query->whereHas('customer', function ($q) {
                    $q->where('first_name', 'like', "%{$this->search}%")
                        ->orWhere('last_name', 'like', "%{$this->search}%")
                        ->orWhere('dni', 'like', "%{$this->search}%");
                })->orWhereHas('vehicle', function ($q) {
                    $q->where('license_plate', 'like', "%{$this->search}%");
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.budget.index', [
            'budgets' => $budgets,
        ]);
    }
}
