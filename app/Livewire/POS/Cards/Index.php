<?php

namespace App\Livewire\POS\Cards;

use App\Src\POS\Models\Card;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = [
        'card-created' => '$refresh',
        'card-updated' => '$refresh'
    ];

    public function deleteCard($cardId)
    {
        $card = Card::findOrFail($cardId);
        $card->delete();
    }

    public function render()
    {
        return view('livewire.pos.cards.index', [
            'cards' => Card::with('plans')->latest()->get(),
        ]);
    }
}
