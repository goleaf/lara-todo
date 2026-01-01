<div>
    <x-header title="{{ __('app.categories.title') }}" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="{{ __('app.common.search') }}..." wire:model.live.debounce="search"
                icon="o-magnifying-glass" clearable />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="{{ __('app.categories.create') }}" icon="o-plus" link="{{ route('categories.create') }}"
                class="btn-primary" responsive />
        </x-slot:actions>
    </x-header>

    {{-- DELETE CONFIRMATION MODAL --}}
    <x-modal wire:model="deleteModal" title="Delete Category?" class="backdrop-blur">
        <div class="text-center">
            <x-icon name="o-exclamation-triangle" class="w-16 h-16 mx-auto text-error mb-4" />
            <p>Are you sure you want to delete <strong>{{ $categoryName }}</strong>?</p>
            <p class="text-sm opacity-60 mt-2">This action cannot be undone.</p>
        </div>
        <x-slot:actions>
            <x-button label="{{ __('app.common.cancel') }}" @click="$wire.deleteModal = false" />
            <x-button label="{{ __('app.common.delete') }}" wire:click="delete" class="btn-error" icon="o-trash"
                spinner="delete" />
        </x-slot:actions>
    </x-modal>

    @if($categories->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($categories as $category)
                <x-card shadow class="bg-base-100 hover:shadow-lg transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                                style="background-color: {{ $category->color ?? '#6366f1' }}20;">
                                <x-icon name="o-folder" class="w-6 h-6" style="color: {{ $category->color ?? '#6366f1' }};" />
                            </div>
                            <div>
                                <a href="{{ route('categories.show', $category) }}"
                                    class="font-bold text-lg hover:text-primary transition-colors">
                                    {{ $category->name }}
                                </a>
                                <p class="text-sm opacity-60">{{ $category->todos_count }} todos</p>
                            </div>
                        </div>
                        <x-dropdown right>
                            <x-slot:trigger>
                                <x-button icon="o-ellipsis-vertical" class="btn-ghost btn-sm btn-circle" />
                            </x-slot:trigger>
                            <x-menu-item title="View" icon="o-eye" link="{{ route('categories.show', $category) }}" />
                            <x-menu-item title="Edit" icon="o-pencil" link="{{ route('categories.edit', $category) }}" />
                            <x-menu-separator />
                            <x-menu-item title="Delete" icon="o-trash"
                                wire:click="confirmDelete({{ $category->id }}, '{{ addslashes($category->name) }}')"
                                class="text-error" />
                        </x-dropdown>
                    </div>

                    {{-- Color Preview --}}
                    @if($category->color)
                        <div class="mt-4 flex items-center gap-2">
                            <div class="w-4 h-4 rounded-full" style="background-color: {{ $category->color }}"></div>
                            <span class="text-sm opacity-50">{{ $category->color }}</span>
                        </div>
                    @endif

                    {{-- Quick Actions --}}
                    <div class="mt-4 flex gap-2">
                        <x-button label="View Todos" link="{{ route('todos.index') }}?category_id={{ $category->id }}"
                            class="btn-ghost btn-sm flex-1" icon="o-list-bullet" />
                    </div>
                </x-card>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    @else
        <x-card shadow class="bg-base-100">
            <div class="text-center py-16">
                <x-icon name="o-folder-open" class="w-20 h-20 mx-auto text-base-300 mb-4" />
                <h3 class="text-xl font-bold">{{ __('No categories found') }}</h3>
                <p class="opacity-60 mt-2">
                    @if($search)
                        No categories match your search. Try a different query.
                    @else
                        Organize your todos by creating categories.
                    @endif
                </p>
                <div class="flex gap-2 justify-center mt-6">
                    @if($search)
                        <x-button label="Clear Search" wire:click="$set('search', '')" icon="o-x-mark" class="btn-outline" />
                    @endif
                    <x-button label="{{ __('app.categories.create') }}" link="{{ route('categories.create') }}"
                        class="btn-primary" icon="o-plus" />
                </div>
            </div>
        </x-card>
    @endif
</div>