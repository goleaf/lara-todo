<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;

class Show extends Component
{
    public Category $category;

    public function mount(Category $category)
    {
        $this->authorize('view', $category);
        $this->category = $category->loadCount('todos')->load('todos');
    }

    public function delete()
    {
        $this->authorize('delete', $this->category);
        $this->category->delete();

        return redirect()->route('categories.index')
            ->with('success', __('app.categories.deleted_success'));
    }

    public function render()
    {
        return view('categories.show');
    }
}
