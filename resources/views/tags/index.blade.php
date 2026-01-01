<div>
    <x-header title="{{ __('app.tags.title') }}" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="{{ __('app.common.search') }}..." wire:model.live.debounce="search"
                icon="o-magnifying-glass" clearable />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="{{ __('app.tags.create') }}" icon="o-plus" link="{{ route('tags.create') }}"
                class="btn-primary" responsive />
        </x-slot:actions>
    </x-header>

    {{-- DELETE CONFIRMATION MODAL --}}
    <x-modal wire:model="deleteModal" title="Delete Tag?" class="backdrop-blur">
        <div class="text-center">
            <x-icon name="o-exclamation-triangle" class="w-16 h-16 mx-auto text-error mb-4" />
            <p>Are you sure you want to delete <strong>{{ $tagName }}</strong>?</p>
            <p class="text-sm opacity-60 mt-2">This will remove the tag from all associated todos.</p>
        </div>
        <x-slot:actions>
            <x-button label="{{ __('app.common.cancel') }}" @click="$wire.deleteModal = false" />
            <x-button label="{{ __('app.common.delete') }}" wire:click="delete" class="btn-error" icon="o-trash"
                spinner="delete" />
        </x-slot:actions>
    </x-modal>

    @if($tags->count())
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($tags as $tag)
                <x-card shadow class="bg-base-100 hover:shadow-lg transition-all hover:-translate-y-1">
                    <div class="text-center">
                        {{-- Color Circle --}}
                        <div class="w-12 h-12 rounded-full mx-auto mb-3 flex items-center justify-center"
                            style="background-color: {{ $tag->color ?? '#6366f1' }};">
                            <x-icon name="o-tag" class="w-6 h-6 text-white" />
                        </div>

                        {{-- Tag Name --}}
                        <a href="{{ route('tags.show', $tag) }}" class="font-bold hover:text-primary transition-colors">
                            {{ $tag->name }}
                        </a>

                        {{-- Todo Count Badge --}}
                        <div class="mt-2">
                            <x-badge :value="$tag->todos_count . ' todos'" class="badge-sm badge-ghost" />
                        </div>

                        {{-- Actions --}}
                        <div class="flex justify-center gap-1 mt-3">
                            <x-button icon="o-eye" link="{{ route('tags.show', $tag) }}" class="btn-ghost btn-xs"
                                tooltip="View" />
                            <x-button icon="o-pencil" link="{{ route('tags.edit', $tag) }}" class="btn-ghost btn-xs"
                                tooltip="Edit" />
                            <x-button icon="o-trash" wire:click="confirmDelete({{ $tag->id }}, '{{ addslashes($tag->name) }}')"
                                class="btn-ghost btn-xs text-error" tooltip="Delete" />
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $tags->links() }}
        </div>
    @else
        <x-card shadow class="bg-base-100">
            <div class="text-center py-16">
                <x-icon name="o-tag" class="w-20 h-20 mx-auto text-base-300 mb-4" />
                <h3 class="text-xl font-bold">{{ __('No tags found') }}</h3>
                <p class="opacity-60 mt-2">
                    @if($search)
                        No tags match your search. Try a different query.
                    @else
                        Create tags to organize and filter your todos.
                    @endif
                </p>
                <div class="flex gap-2 justify-center mt-6">
                    @if($search)
                        <x-button label="Clear Search" wire:click="$set('search', '')" icon="o-x-mark" class="btn-outline" />
                    @endif
                    <x-button label="{{ __('app.tags.create') }}" link="{{ route('tags.create') }}" class="btn-primary"
                        icon="o-plus" />
                </div>
            </div>
        </x-card>
    @endif
</div>