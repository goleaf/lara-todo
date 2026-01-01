<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;

class Create extends Component
{
    use Toast;
    public string $name = '';
    public string $color = '#000000';

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where(fn($query) => $query->where('user_id', auth()->id()))
            ],
            'color' => 'nullable|string|hex_color|max:7',
        ];
    }

    protected function messages()
    {
        return (new \App\Http\Requests\StoreCategoryRequest)->messages();
    }

    public function save()
    {
        $this->validate();

        auth()->user()->categories()->create([
            'name' => $this->name,
            'color' => $this->color,
        ]);

        $this->success(
            __('app.categories.created_success'),
            position: 'toast-top toast-end',
            redirectTo: route('categories.index')
        );
    }

    public function render()
    {
        return view('categories.create');
    }
}
