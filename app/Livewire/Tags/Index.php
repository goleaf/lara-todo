<?php

namespace App\Livewire\Tags;

use App\Models\Tag;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use WithPagination, Toast;

    public string $search = '';

    // Delete modal
    public bool $deleteModal = false;
    public ?int $tagToDelete = null;
    public ?string $tagName = null;

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-16'],
            ['key' => 'name', 'label' => __('app.common.name')],
            ['key' => 'color', 'label' => __('app.common.color')],
            ['key' => 'todos_count', 'label' => 'Todos', 'class' => 'w-24'],
        ];
    }

    public function confirmDelete(int $tagId, string $tagName)
    {
        $this->tagToDelete = $tagId;
        $this->tagName = $tagName;
        $this->deleteModal = true;
    }

    public function delete()
    {
        if ($this->tagToDelete) {
            $tag = Tag::find($this->tagToDelete);
            if ($tag) {
                $this->authorize('delete', $tag);
                $tag->delete();
                $this->success(__('app.tags.deleted_success'), position: 'toast-top toast-end');
            }
        }
        $this->deleteModal = false;
        $this->tagToDelete = null;
        $this->tagName = null;
    }

    public function render()
    {
        $query = auth()->user()->tags()->withCount('todos');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $tags = $query->latest()->paginate(12);

        return view('tags.index', compact('tags'));
    }
}
