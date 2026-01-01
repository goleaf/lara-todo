<div>
    <x-header title="{{ __('app.tags.title') }}: {{ $tag->name }}" separator progress-indicator>
        <x-slot:actions>
            <x-button label="{{ __('app.common.edit') }}" icon="o-pencil" link="{{ route('tags.edit', $tag) }}"
                class="btn-primary" />
            <x-button label="{{ __('app.common.delete') }}" icon="o-trash" wire:click="delete"
                wire:confirm="{{ __('app.common.confirm_delete') }}" class="btn-error" />
        </x-slot:actions>
    </x-header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            <x-card title="Todos with this Tag" shadow class="bg-base-100">
                @if($tag->todos->count())
                    <x-table :rows="$tag->todos" :headers="[['key' => 'title', 'label' => 'Title'], ['key' => 'status', 'label' => 'Status']]">
                        @scope('cell_status', $todo)
                        <x-badge :value="ucfirst($todo->status)" :class="$todo->status == 'completed' ? 'badge-success' : 'badge-warning'" />
                        @endscope
                        @scope('actions', $todo)
                        <x-button icon="o-eye" link="{{ route('todos.show', $todo) }}" class="btn-ghost btn-sm" />
                        @endscope
                    </x-table>
                @else
                    <x-alert title="No todos found with this tag" icon="o-exclamation-triangle" class="alert-warning" />
                @endif
            </x-card>
        </div>

        <div class="space-y-6">
            <x-card title="Tag Details" shadow class="bg-base-100">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="font-medium">Color:</span>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full border border-base-300"
                                style="background-color: {{ $tag->color }}"></div>
                            <span class="text-sm font-mono">{{ strtoupper($tag->color) }}</span>
                        </div>
                    </div>
                </div>
            </x-card>

            <x-card title="Quick Stats" shadow class="bg-base-100 text-center">
                <x-stat title="Tagged Todos" :value="$tag->todos->count()" icon="o-tag" />
            </x-card>
        </div>
    </div>
</div>