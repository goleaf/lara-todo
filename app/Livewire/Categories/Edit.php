<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;

class Edit extends Component
{
    use Toast;
    public Category $category;
    public string $name = '';
    public string $color = '';

    public function mount(Category $category)
    {
        $this->authorize('update', $category);
        $this->category = $category;
        $this->name = $category->name;
        $this->color = $category->color ?? '#000000';
    }

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where(fn($query) => $query->where('user_id', auth()->id()))->ignore($this->category->id)
            ],
            'color' => 'nullable|string|hex_color|max:7',
        ];
    }

    protected function messages()
    {
        return (new \App\Http\Requests\UpdateCategoryRequest)->messages();
    }

    public function save()
    {
        $validated = $this->validate();

        $this->category->update($validated);

        $this->success(
            __('app.categories.updated_success'),
            position: 'toast-top toast-end',
            redirectTo: route('categories.index')
        );
    }

    public function render()
    {
        return view('categories.edit');
    }
}
