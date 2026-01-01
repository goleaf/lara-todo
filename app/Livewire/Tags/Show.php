<?php

namespace App\Livewire\Tags;

use App\Models\Tag;
use Livewire\Component;

class Show extends Component
{
    public Tag $tag;

    public function mount(Tag $tag)
    {
        $this->authorize('view', $tag);
        $this->tag = $tag->load('todos');
    }

    public function delete()
    {
        $this->authorize('delete', $this->tag);
        $this->tag->delete();

        return redirect()->route('tags.index')
            ->with('success', __('app.tags.deleted_success'));
    }

    public function render()
    {
        return view('tags.show');
    }
}
