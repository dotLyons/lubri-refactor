<?php

namespace App\Livewire\Inventory\Subcategories;

use Livewire\Component;
use Livewire\WithPagination;
use App\Src\Inventory\Models\SubcategoryProduct;
use Flux\Flux;

class Index extends Component
{
    use WithPagination;

    public $subcategoryToDeleteId;
    public $search = '';

    protected $queryString = ['search'];
    protected $listeners = ['subcategory-created' => '$refresh', 'subcategory-updated' => '$refresh'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->subcategoryToDeleteId = $id;
        Flux::modal('delete-subcategory')->show();
    }

    public function delete()
    {
        if ($this->subcategoryToDeleteId) {
            $subcategory = SubcategoryProduct::find($this->subcategoryToDeleteId);
            if ($subcategory) {
                $subcategory->delete();
            }
            $this->subcategoryToDeleteId = null;
            Flux::modal('delete-subcategory')->close();
        }
    }

    public function render()
    {
        $query = SubcategoryProduct::query();

        if ($this->search) {
            $query->where('subcategory_name', 'like', '%' . $this->search . '%');
        }

        $subcategories = $query->paginate(10);
        return view('livewire.inventory.subcategories.index', [
            'subcategories' => $subcategories
        ]);
    }
}
