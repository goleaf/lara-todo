<?php

namespace App\Livewire\Todos;

use App\Models\Todo;
use Livewire\Component;
use Mary\Traits\Toast;

class Show extends Component
{
    use Toast;

    public Todo $todo;

    // Modal states
    public bool $deleteModal = false;
    public bool $completeModal = false;

    public function mount(Todo $todo)
    {
        $this->authorize('view', $todo);
        $this->todo = $todo->load(['category', 'tags']);
    }

    public function toggleComplete()
    {
        $this->authorize('update', $this->todo);

        $this->todo->status = $this->todo->status === 'completed' ? 'pending' : 'completed';
        $this->todo->progress = $this->todo->status === 'completed' ? 100 : 0;
        $this->todo->save();

        $message = $this->todo->status === 'completed' ? 'Task marked as complete!' : 'Task reopened';
        $this->success($message, position: 'toast-top toast-end');
    }

    public function updateProgress(int $value)
    {
        $this->authorize('update', $this->todo);

        $this->todo->progress = $value;
        if ($value >= 100) {
            $this->todo->status = 'completed';
        } elseif ($value < 100 && $this->todo->status === 'completed') {
            $this->todo->status = 'pending';
        }
        $this->todo->save();

        $this->success('Progress updated!', position: 'toast-top toast-end');
    }

    public function delete()
    {
        $this->authorize('delete', $this->todo);
        $this->todo->delete();

        $this->success(
            __('app.todos.deleted_success'),
            position: 'toast-top toast-end',
            redirectTo: route('todos.index')
        );
    }

    public function render()
    {
        return view('todos.show');
    }
}
