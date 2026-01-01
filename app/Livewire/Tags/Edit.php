<?php

namespace App\Livewire\Tags;

use App\Models\Tag;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;

class Edit extends Component
{
    use Toast;
    public Tag $tag;
    public string $name = '';
    public string $color = '';

    public function mount(Tag $tag)
    {
        $this->authorize('update', $tag);
        $this->tag = $tag;
        $this->name = $tag->name;
        $this->color = $tag->color;
    }

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tags')->where(fn($query) => $query->where('user_id', auth()->id()))->ignore($this->tag->id)
            ],
            'color' => 'required|string|max:50',
        ];
    }

    protected function messages()
    {
        return (new \App\Http\Requests\UpdateTagRequest)->messages();
    }

    public function save()
    {
        $validated = $this->validate();

        $this->tag->update($validated);

        $this->success(
            __('app.tags.updated_success'),
            position: 'toast-top toast-end',
            redirectTo: route('tags.index')
        );
    }

    public function render()
    {
        return view('tags.edit');
    }
}
