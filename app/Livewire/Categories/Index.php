<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use WithPagination, Toast;

    public string $search = '';

    // Delete modal
    public bool $deleteModal = false;
    public ?int $categoryToDelete = null;
    public ?string $categoryName = null;

    public function confirmDelete(int $categoryId, string $categoryName)
    {
        $this->categoryToDelete = $categoryId;
        $this->categoryName = $categoryName;
        $this->deleteModal = true;
    }

    public function delete()
    {
        if ($this->categoryToDelete) {
            $category = Category::find($this->categoryToDelete);
            if ($category) {
                $this->authorize('delete', $category);

                // Check if category has todos
                if ($category->todos()->count() > 0) {
                    $this->error(
                        'Cannot delete category',
                        description: 'This category has associated todos. Please reassign or delete them first.',
                        position: 'toast-top toast-end'
                    );
                    $this->deleteModal = false;
                    return;
                }

                $category->delete();
                $this->success(__('app.categories.deleted_success'), position: 'toast-top toast-end');
            }
        }
        $this->deleteModal = false;
        $this->categoryToDelete = null;
        $this->categoryName = null;
    }

    public function render()
    {
        $query = auth()->user()->categories()->withCount('todos');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $categories = $query->latest()->paginate(10);

        return view('categories.index', compact('categories'));
    }
}
