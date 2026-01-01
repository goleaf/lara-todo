<?php

namespace App\Livewire\Tags;

use Livewire\Component;
use Illuminate\Validation\Rule;

class Create extends Component
{
    public string $name = '';
    public string $color = '#000000';

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tags')->where(fn($query) => $query->where('user_id', auth()->id()))
            ],
            'color' => 'required|string|max:50',
        ];
    }

    protected function messages()
    {
        return (new \App\Http\Requests\StoreTagRequest)->messages();
    }

    public function save()
    {
        $this->validate();

        auth()->user()->tags()->create([
            'name' => $this->name,
            'color' => $this->color,
        ]);

        return redirect()->route('tags.index')
            ->with('success', __('app.tags.created_success'));
    }

    public function render()
    {
        return view('tags.create');
    }
}
