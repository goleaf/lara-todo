<?php

namespace App\Livewire\Todos;

use App\Models\Todo;
use Livewire\Component;
use Mary\Traits\Toast;

class Edit extends Component
{
    use Toast;

    public Todo $todo;

    public string $title = '';
    public string $description = '';
    public $category_id = '';
    public string $status = '';
    public string $due_date = '';
    public int $progress = 0;
    public array $tags = [];

    // Tabs for edit form
    public string $activeTab = 'details-tab';

    // Confirmation for status change
    public bool $showStatusModal = false;
    public string $pendingStatus = '';

    public function mount(Todo $todo)
    {
        $this->authorize('update', $todo);
        $this->todo = $todo;
        $this->title = $todo->title;
        $this->description = $todo->description ?? '';
        $this->category_id = $todo->category_id;
        $this->status = $todo->status;
        $this->due_date = $todo->due_date ? $todo->due_date->format('Y-m-d') : '';
        $this->progress = $todo->progress ?? 0;
        $this->tags = $todo->tags->pluck('id')->toArray();
    }

    protected function rules()
    {
        return (new \App\Http\Requests\UpdateTodoRequest)->rules();
    }

    protected function messages()
    {
        return (new \App\Http\Requests\UpdateTodoRequest)->messages();
    }

    public function confirmStatusChange(string $newStatus)
    {
        if ($this->status !== $newStatus) {
            $this->pendingStatus = $newStatus;
            $this->showStatusModal = true;
        }
    }

    public function applyStatusChange()
    {
        $this->status = $this->pendingStatus;
        if ($this->status === 'completed') {
            $this->progress = 100;
        } elseif ($this->status === 'pending' && $this->progress === 100) {
            $this->progress = 0;
        }
        $this->showStatusModal = false;
        $this->success('Status updated!', position: 'toast-top toast-end');
    }

    public function updateProgress(int $value)
    {
        $this->progress = $value;
        if ($value >= 100) {
            $this->status = 'completed';
        } elseif ($value < 100 && $this->status === 'completed') {
            $this->status = 'pending';
        }
    }

    public function save()
    {
        $validated = $this->validate();

        // Convert empty due_date string to null
        if (empty($this->due_date)) {
            $validated['due_date'] = null;
        }

        $this->todo->update($validated);
        $this->todo->tags()->sync($this->tags);

        $this->success(
            __('app.todos.updated_success'),
            description: 'Your changes have been saved.',
            position: 'toast-top toast-end',
            redirectTo: route('todos.index')
        );
    }

    public function render()
    {
        return view('todos.edit', [
            'categories' => auth()->user()->categories,
            'tags' => auth()->user()->tags,
        ]);
    }
}
