<?php

namespace App\Livewire\Inventory\Subcategories;

use Livewire\Component;
use App\Src\Inventory\Models\SubcategoryProduct;
use Flux\Flux;

class Create extends Component
{
    public string $subcategory_name = '';
    public string $description = '';
    public string $status = 'active';

    public function save()
    {
        $this->validate([
            'subcategory_name' => ['required', 'string', 'max:255', 'unique:products_subcategories,subcategory_name'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:active,inactive'],
        ]);

        SubcategoryProduct::create([
            'subcategory_name' => $this->subcategory_name,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        $this->reset(['subcategory_name', 'description', 'status']);

        $this->dispatch('subcategory-created');
        Flux::modal('create-subcategory')->close();
    }

    public function render()
    {
        return view('livewire.inventory.subcategories.create');
    }
}
