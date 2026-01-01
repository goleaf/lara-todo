<div>
    {{-- HEADER --}}
    <x-header title="{{ __('app.todos.manage') }}" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="{{ __('app.common.search') }}..." wire:model.live.debounce="search"
                icon="o-magnifying-glass" clearable />
        </x-slot:middle>
        <x-slot:actions>
            {{-- Mobile filter button --}}
            <x-button icon="o-funnel" class="btn-ghost lg:hidden" wire:click="$toggle('showFilters')"
                badge="{{ ($category_id || $status || $tag_id) ? '!' : '' }}" />
            <x-button label="{{ __('app.todos.create') }}" icon="o-plus" link="{{ route('todos.create') }}"
                class="btn-primary" responsive />
        </x-slot:actions>
    </x-header>

    {{-- STATS BAR --}}
    <div class="flex flex-wrap gap-4 mb-6">
        <x-stat title="Total" :value="$stats['total']" icon="o-list-bullet" class="bg-base-100 shadow-sm" />
        <x-stat title="Pending" :value="$stats['pending']" icon="o-clock" class="bg-warning/10 text-warning shadow-sm" />
        <x-stat title="Completed" :value="$stats['completed']" icon="o-check-circle" class="bg-success/10 text-success shadow-sm" />
        @if($stats['filtered'] < $stats['total'])
            <x-stat title="Filtered" :value="$stats['filtered']" icon="o-funnel" class="bg-info/10 text-info shadow-sm" />
        @endif
    </div>

    {{-- FILTER DRAWER for mobile --}}
    <x-drawer wire:model="showFilters" title="{{ __('app.common.filter') }}" subtitle="Filter your todos"
        separator with-close-button close-on-escape class="w-11/12 lg:w-1/3">
        <div class="space-y-4">
            <x-select label="{{ __('app.common.category') }}" wire:model.live="category_id" :options="$categories"
                placeholder="All categories" icon="o-folder" />
            <x-select label="{{ __('app.common.status') }}" wire:model.live="status"
                :options="[['id' => 'pending', 'name' => __('app.todos.pending')], ['id' => 'completed', 'name' => __('app.todos.completed')]]"
                placeholder="All statuses" icon="o-check-circle" />
            <x-select label="{{ __('app.common.tags') }}" wire:model.live="tag_id" :options="$tags"
                placeholder="All tags" icon="o-tag" />
        </div>
        <x-slot:actions>
            <x-button label="{{ __('app.common.reset') }}" wire:click="clearFilters" icon="o-x-mark" />
            <x-button label="Apply" wire:click="$set('showFilters', false)" class="btn-primary" icon="o-check" />
        </x-slot:actions>
    </x-drawer>

    {{-- DELETE CONFIRMATION MODAL --}}
    <x-modal wire:model="deleteModal" title="Confirm Delete" class="backdrop-blur">
        <x-icon name="o-exclamation-triangle" class="w-16 h-16 mx-auto text-error mb-4" />
        <p class="text-center">{{ __('app.common.confirm_delete') }}</p>
        <p class="text-center text-sm opacity-60 mt-2">This action cannot be undone.</p>
        <x-slot:actions>
            <x-button label="{{ __('app.common.cancel') }}" @click="$wire.deleteModal = false" />
            <x-button label="{{ __('app.common.delete') }}" wire:click="delete" class="btn-error" spinner="delete"
                icon="o-trash" />
        </x-slot:actions>
    </x-modal>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {{-- DESKTOP FILTERS --}}
        <div class="hidden lg:block lg:col-span-1">
            <x-card title="{{ __('app.common.filter') }}" shadow class="bg-base-100 sticky top-4">
                <div class="grid gap-4">
                    <x-select label="{{ __('app.common.category') }}" wire:model.live="category_id" :options="$categories"
                        placeholder="All" icon="o-folder" />
                    <x-select label="{{ __('app.common.status') }}" wire:model.live="status"
                        :options="[['id' => 'pending', 'name' => __('app.todos.pending')], ['id' => 'completed', 'name' => __('app.todos.completed')]]"
                        placeholder="All" icon="o-check-circle" />
                    <x-select label="{{ __('app.common.tags') }}" wire:model.live="tag_id" :options="$tags"
                        placeholder="All" icon="o-tag" />

                    <x-button label="{{ __('app.common.reset') }}" wire:click="clearFilters"
                        class="btn-outline btn-sm" icon="o-x-mark" />
                </div>
            </x-card>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="lg:col-span-3">
            {{-- BULK ACTIONS --}}
            @if(count($selected) > 0)
                <x-alert title="{{ count($selected) }} items selected" icon="o-check" class="mb-4 alert-info">
                    <x-slot:actions>
                        <x-button label="Delete Selected" wire:click="bulkDelete"
                            wire:confirm="Are you sure you want to delete {{ count($selected) }} items?"
                            icon="o-trash" class="btn-error btn-sm" />
                        <x-button label="Clear" wire:click="$set('selected', [])" icon="o-x-mark"
                            class="btn-ghost btn-sm" />
                    </x-slot:actions>
                </x-alert>
            @endif

            @if($todos->count())
                <x-card shadow class="bg-base-100">
                    <x-table :headers="$this->headers()" :rows="$todos" :sort-by="$sortBy" with-pagination
                        selectable wire:model="selected" link="/todos/{id}">

                        {{-- Category cell with color indicator --}}
                        @scope('cell_category.name', $todo)
                            <div class="flex items-center gap-2">
                                @if($todo->category && $todo->category->color)
                                    <div class="w-3 h-3 rounded-full" style="background-color: {{ $todo->category->color }}">
                                    </div>
                                @endif
                                <span>{{ $todo->category->name ?? 'Uncategorized' }}</span>
                            </div>
                        @endscope

                        {{-- Status with toggle capability --}}
                        @scope('cell_status', $todo)
                            <x-button wire:click="toggleComplete({{ $todo->id }})"
                                class="btn-ghost btn-xs gap-1" spinner="toggleComplete({{ $todo->id }})">
                                <x-badge :value="ucfirst($todo->status)"
                                    :class="$todo->status == 'completed' ? 'badge-success' : 'badge-warning'" />
                            </x-button>
                        @endscope

                        {{-- Due date with relative time --}}
                        @scope('cell_due_date', $todo)
                            @if($todo->due_date)
                                <div class="flex flex-col">
                                    <span>{{ $todo->due_date->format('M d, Y') }}</span>
                                    <span class="text-xs opacity-60">{{ $todo->due_date->diffForHumans() }}</span>
                                </div>
                            @else
                                <span class="opacity-40">-</span>
                            @endif
                        @endscope

                        {{-- Progress bar --}}
                        @scope('cell_progress', $todo)
                            <div class="flex items-center gap-2">
                                <progress class="progress w-16 {{ $todo->progress >= 100 ? 'progress-success' : ($todo->progress >= 50 ? 'progress-warning' : 'progress-info') }}"
                                    value="{{ $todo->progress ?? 0 }}" max="100"></progress>
                                <span class="text-xs opacity-60">{{ $todo->progress ?? 0 }}%</span>
                            </div>
                        @endscope

                        {{-- Actions column --}}
                        @scope('actions', $todo)
                            <div class="flex gap-1">
                                <x-button icon="o-eye" link="{{ route('todos.show', $todo) }}"
                                    class="btn-ghost btn-xs text-info" tooltip="View" />
                                <x-button icon="o-pencil" link="{{ route('todos.edit', $todo) }}"
                                    class="btn-ghost btn-xs text-success" tooltip="Edit" />
                                <x-button icon="o-trash" wire:click="confirmDelete({{ $todo->id }})"
                                    class="btn-ghost btn-xs text-error" tooltip="Delete" />
                            </div>
                        @endscope

                    </x-table>
                </x-card>
            @else
                <x-card shadow class="bg-base-100">
                    <div class="text-center py-16">
                        <x-icon name="o-clipboard-document-list" class="w-20 h-20 mx-auto text-base-300 mb-4" />
                        <h3 class="text-xl font-bold">{{ __('app.todos.no_todos') }}</h3>
                        <p class="opacity-60 mt-2">
                            @if($search || $category_id || $status || $tag_id)
                                Try adjusting your filters or search query.
                            @else
                                Get started by creating your first todo.
                            @endif
                        </p>
                        <div class="flex gap-2 justify-center mt-6">
                            @if($search || $category_id || $status || $tag_id)
                                <x-button label="{{ __('app.common.reset') }} Filters" wire:click="clearFilters"
                                    icon="o-x-mark" class="btn-outline" />
                            @endif
                            <x-button label="{{ __('app.todos.create') }}" link="{{ route('todos.create') }}"
                                class="btn-primary" icon="o-plus" />
                        </div>
                    </div>
                </x-card>
            @endif
        </div>
    </div>
</div>