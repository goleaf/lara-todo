<div>
    <x-header title="{{ $category->name }}" separator progress-indicator>
        <x-slot:middle>
            @if($category->color)
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full border border-base-300 shadow-sm"
                        style="background-color: {{ $category->color }}"></div>
                    <span class="text-sm opacity-50">{{ $category->color }}</span>
                </div>
            @endif
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="{{ __('app.common.edit') }}" icon="o-pencil"
                link="{{ route('categories.edit', $category) }}" class="btn-primary" />
            <x-button label="{{ __('app.common.delete') }}" icon="o-trash" wire:click="delete"
                wire:confirm="{{ __('app.common.confirm_delete') }}" class="btn-error" />
        </x-slot:actions>
    </x-header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            <x-card title="Latest Todos in {{ $category->name }}" shadow class="bg-base-100">
                @if($category->todos->count())
                    <x-table :headers="[['key' => 'title', 'label' => 'Title'], ['key' => 'status', 'label' => 'Status']]"
                        :rows="$category->todos()->latest()->take(10)->get()" striped>
                        @scope('cell_status', $todo)
                        <x-badge :value="ucfirst($todo->status)" :class="$todo->status == 'completed' ? 'badge-success' : 'badge-warning'" />
                        @endscope
                    </x-table>
                    <div class="mt-4">
                        <x-button label="View All Todos" link="{{ route('todos.index', ['category_id' => $category->id]) }}"
                            class="btn-ghost" icon="o-list-bullet" />
                    </div>
                @else
                    <x-alert title="No todos in this category" icon="o-exclamation-triangle" class="alert-warning" />
                @endif
            </x-card>
        </div>

        <div class="space-y-6">
            <x-card title="Stats" shadow class="bg-base-100 text-center">
                <x-stat title="Total Todos" :value="$category->todos_count" icon="o-check-circle" />
            </x-card>
        </div>
    </div>
</div>