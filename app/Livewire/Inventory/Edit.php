<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use App\Src\Inventory\Models\ProductCategory;
use Livewire\Attributes\On;
use Flux\Flux;

class Edit extends Component
{
    public ?ProductCategory $category = null;

    public string $category_name = '';
    public string $description = '';
    public string $status = 'active';

    #[On('edit-category')]
    public function loadCategory($id)
    {
        $this->category = ProductCategory::find($id);

        if ($this->category) {
            $this->category_name = $this->category->category_name;
            $this->description = $this->category->description ?? '';
            $this->status = $this->category->status;

            Flux::modal('edit-category')->show();
        }
    }

    public function update()
    {
        $this->validate([
            'category_name' => ['required', 'string', 'max:255', 'unique:product_categories,category_name,' . $this->category->id],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:active,inactive'],
        ]);

        $this->category->update([
            'category_name' => $this->category_name,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        $this->dispatch('category-updated');
        Flux::modal('edit-category')->close();
    }

    public function render()
    {
        return view('livewire.inventory.edit');
    }
}
