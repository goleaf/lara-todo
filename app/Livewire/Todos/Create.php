<?php

namespace App\Livewire\Todos;

use Livewire\Component;
use Mary\Traits\Toast;

class Create extends Component
{
    use Toast;

    // Form fields
    public string $title = '';
    public string $description = '';
    public $category_id = '';
    public string $due_date = '';
    public array $tags = [];
    public int $priority = 2; // 1=High, 2=Medium, 3=Low

    // Multi-step form
    public int $step = 1;
    public int $totalSteps = 3;

    protected function rules()
    {
        $rules = (new \App\Http\Requests\StoreTodoRequest)->rules();
        unset($rules['status'], $rules['progress']);
        return $rules;
    }

    protected function messages()
    {
        return (new \App\Http\Requests\StoreTodoRequest)->messages();
    }

    public function nextStep()
    {
        // Validate current step before proceeding
        if ($this->step === 1) {
            $this->validate([
                'title' => 'required|string|max:255',
            ]);
        } elseif ($this->step === 2) {
            $this->validate([
                'category_id' => 'required|exists:categories,id',
            ]);
        }

        if ($this->step < $this->totalSteps) {
            $this->step++;
        }
    }

    public function prevStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function save()
    {
        $this->validate();

        $todo = auth()->user()->todos()->create([
            'title' => $this->title,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'due_date' => $this->due_date ?: null,
            'status' => 'pending',
            'progress' => 0,
        ]);

        $todo->tags()->sync($this->tags);

        $this->success(
            __('app.todos.created_success'),
            description: 'Your new todo has been created successfully.',
            position: 'toast-top toast-end',
            redirectTo: route('todos.index')
        );
    }

    public function render()
    {
        return view('todos.create', [
            'categories' => auth()->user()->categories,
            'tags' => auth()->user()->tags,
        ]);
    }
}
