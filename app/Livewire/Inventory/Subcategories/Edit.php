<?php

namespace App\Livewire\Inventory\Subcategories;

use Livewire\Component;
use App\Src\Inventory\Models\SubcategoryProduct;
use Livewire\Attributes\On;
use Flux\Flux;

class Edit extends Component
{
    public ?SubcategoryProduct $subcategory = null;

    public string $subcategory_name = '';
    public string $description = '';
    public string $status = 'active';

    #[On('edit-subcategory')]
    public function loadSubcategory($id)
    {
        $this->subcategory = SubcategoryProduct::find($id);

        if ($this->subcategory) {
            $this->subcategory_name = $this->subcategory->subcategory_name;
            $this->description = $this->subcategory->description ?? '';
            $this->status = $this->subcategory->status;

            Flux::modal('edit-subcategory')->show();
        }
    }

    public function update()
    {
        $this->validate([
            'subcategory_name' => ['required', 'string', 'max:255', 'unique:products_subcategories,subcategory_name,' . $this->subcategory->id],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:active,inactive'],
        ]);

        $this->subcategory->update([
            'subcategory_name' => $this->subcategory_name,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        $this->dispatch('subcategory-updated');
        Flux::modal('edit-subcategory')->close();
    }

    public function render()
    {
        return view('livewire.inventory.subcategories.edit');
    }
}
