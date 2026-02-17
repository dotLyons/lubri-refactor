<?php

namespace App\Livewire\Inventory;

use Livewire\Component;
use Livewire\WithPagination;
use App\Src\Inventory\Models\ProductCategory;
use Flux\Flux;

class Index extends Component
{
    use WithPagination;

    public $categoryToDeleteId;
    public $search = '';

    protected $queryString = ['search'];
    protected $listeners = ['category-created' => '$refresh', 'category-updated' => '$refresh'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->categoryToDeleteId = $id;
        Flux::modal('delete-category')->show();
    }

    public function delete()
    {
        if ($this->categoryToDeleteId) {
            $category = ProductCategory::find($this->categoryToDeleteId);
            if ($category) {
                $category->delete();
            }
            $this->categoryToDeleteId = null;
            Flux::modal('delete-category')->close();
        }
    }

    public function render()
    {
        $query = ProductCategory::query();

        if ($this->search) {
            $query->where('category_name', 'like', '%' . $this->search . '%');
        }

        $categories = $query->paginate(10);
        return view('livewire.inventory.index', [
            'categories' => $categories
        ]);
    }
}
