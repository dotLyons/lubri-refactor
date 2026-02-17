<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use App\Src\Inventory\Models\ProductCategory;
use Illuminate\Validation\Rule;
use Flux\Flux;

class Create extends Component
{
    public string $category_name = '';
    public string $description = '';
    public string $status = 'active';

    public function save()
    {
        $this->validate([
            'category_name' => ['required', 'string', 'max:255', 'unique:product_categories,category_name'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:active,inactive'],
        ]);

        ProductCategory::create([
            'category_name' => $this->category_name,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        $this->reset(['category_name', 'description', 'status']);

        $this->dispatch('category-created');
        Flux::modal('create-category')->close();
    }

    public function render()
    {
        return view('livewire.inventory.create');
    }
}
