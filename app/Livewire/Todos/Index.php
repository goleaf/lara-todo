<?php

namespace App\Livewire\Todos;

use App\Models\Todo;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use WithPagination, Toast;

    #[Url]
    public string $search = '';

    #[Url]
    public $category_id = '';

    #[Url]
    public $status = '';

    #[Url]
    public $tag_id = '';

    // Sorting
    public array $sortBy = ['column' => 'created_at', 'direction' => 'desc'];

    // Filter drawer for mobile
    public bool $showFilters = false;

    // Delete modal
    public bool $deleteModal = false;
    public ?int $todoToDelete = null;

    // Row selection
    public array $selected = [];

    // Expanded rows
    public array $expanded = [];

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-16'],
            ['key' => 'title', 'label' => __('app.common.title'), 'class' => 'w-72'],
            ['key' => 'category.name', 'label' => __('app.common.category'), 'sortBy' => 'category_name'],
            ['key' => 'status', 'label' => __('app.common.status')],
            ['key' => 'due_date', 'label' => __('app.common.due_date')],
            ['key' => 'progress', 'label' => __('app.common.progress'), 'class' => 'w-32'],
        ];
    }

    public function confirmDelete(int $todoId)
    {
        $this->todoToDelete = $todoId;
        $this->deleteModal = true;
    }

    public function delete()
    {
        if ($this->todoToDelete) {
            $todo = Todo::find($this->todoToDelete);
            if ($todo) {
                $this->authorize('delete', $todo);
                $todo->delete();
                $this->success(__('app.todos.deleted_success'), position: 'toast-top toast-end');
            }
        }
        $this->deleteModal = false;
        $this->todoToDelete = null;
    }

    public function bulkDelete()
    {
        if (count($this->selected) > 0) {
            $todos = Todo::whereIn('id', $this->selected)
                ->where('user_id', auth()->id())
                ->get();

            foreach ($todos as $todo) {
                $todo->delete();
            }

            $this->success(count($this->selected) . ' todos deleted', position: 'toast-top toast-end');
            $this->selected = [];
        }
    }

    public function toggleComplete(int $todoId)
    {
        $todo = Todo::find($todoId);
        if ($todo && $todo->user_id === auth()->id()) {
            $todo->status = $todo->status === 'completed' ? 'pending' : 'completed';
            $todo->progress = $todo->status === 'completed' ? 100 : 0;
            $todo->save();
            $this->success('Todo status updated!', position: 'toast-top toast-end');
        }
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->category_id = '';
        $this->status = '';
        $this->tag_id = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = auth()->user()->todos()
            ->with(['category', 'tags'])
            ->withAggregate('category', 'name')
            ->filter([
                'search' => $this->search,
                'category_id' => $this->category_id,
                'status' => $this->status,
                'tag_id' => $this->tag_id
            ]);

        // Apply sorting
        if ($this->sortBy['column']) {
            $query->orderBy($this->sortBy['column'], $this->sortBy['direction']);
        }

        $todos = $query->paginate(12);

        $categories = auth()->user()->categories;
        $tags = auth()->user()->tags;

        // Stats for filter summary
        $stats = [
            'total' => auth()->user()->todos()->count(),
            'filtered' => $todos->total(),
            'pending' => auth()->user()->todos()->where('status', 'pending')->count(),
            'completed' => auth()->user()->todos()->where('status', 'completed')->count(),
        ];

        return view('todos.index', compact('todos', 'categories', 'tags', 'stats'));
    }
}
