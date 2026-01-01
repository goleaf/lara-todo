<div>
    {{-- HEADER --}}
    <x-header :title="$todo->title" size="text-3xl" separator progress-indicator>
        <x-slot:subtitle>
            <div class="flex items-center gap-3 mt-2">
                <x-badge :value="ucfirst($todo->status)" :class="$todo->status == 'completed' ? 'badge-success badge-lg' : 'badge-warning badge-lg'" />
                @if($todo->category)
                    <x-badge :value="$todo->category->name" class="badge-outline badge-primary" />
                @endif
                @if($todo->due_date)
                    <span class="text-sm opacity-60">
                        <x-icon name="o-calendar" class="w-4 h-4 inline" />
                        Due {{ $todo->due_date->diffForHumans() }}
                    </span>
                @endif
            </div>
        </x-slot:subtitle>
        <x-slot:actions>
            <x-dropdown label="Actions" class="btn-ghost" right>
                <x-menu-item title="{{ __('app.common.edit') }}" icon="o-pencil"
                    link="{{ route('todos.edit', $todo) }}" />
                <x-menu-item title="{{ $todo->status === 'completed' ? 'Reopen' : 'Complete' }}"
                    icon="{{ $todo->status === 'completed' ? 'o-arrow-path' : 'o-check-circle' }}"
                    wire:click="toggleComplete" />
                <x-menu-separator />
                <x-menu-item title="{{ __('app.common.delete') }}" icon="o-trash" wire:click="$set('deleteModal', true)"
                    class="text-error" />
            </x-dropdown>
        </x-slot:actions>
    </x-header>

    {{-- DELETE CONFIRMATION MODAL --}}
    <x-modal wire:model="deleteModal" title="Delete Todo?" class="backdrop-blur">
        <div class="text-center">
            <x-icon name="o-exclamation-triangle" class="w-20 h-20 mx-auto text-error mb-4" />
            <p class="text-lg">Are you sure you want to delete this todo?</p>
            <p class="text-sm opacity-60 mt-2 mb-4">"{{ $todo->title }}"</p>
            <x-alert icon="o-information-circle" class="alert-warning text-left">
                This action cannot be undone. All associated data will be permanently removed.
            </x-alert>
        </div>
        <x-slot:actions>
            <x-button label="{{ __('app.common.cancel') }}" @click="$wire.deleteModal = false" />
            <x-button label="{{ __('app.common.delete') }}" wire:click="delete" class="btn-error" icon="o-trash"
                spinner="delete" />
        </x-slot:actions>
    </x-modal>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- MAIN CONTENT --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- PROGRESS CARD --}}
            <x-card title="Progress" shadow class="bg-base-100">
                <div class="flex items-center gap-6">
                    {{-- Radial Progress --}}
                    <div class="radial-progress {{ $todo->progress >= 100 ? 'text-success' : ($todo->progress >= 50 ? 'text-warning' : 'text-info') }}"
                        style="--value:{{ $todo->progress ?? 0 }}; --size:6rem; --thickness: 8px;">
                        <span class="text-xl font-bold">{{ $todo->progress ?? 0 }}%</span>
                    </div>

                    {{-- Progress Slider --}}
                    <div class="flex-1">
                        <div class="flex justify-between mb-2">
                            <span class="text-sm font-medium">Update Progress</span>
                            <span class="text-sm opacity-60">{{ $todo->progress ?? 0 }}%</span>
                        </div>
                        <input type="range" min="0" max="100" step="10" value="{{ $todo->progress ?? 0 }}"
                            wire:change="updateProgress($event.target.value)"
                            class="range {{ $todo->progress >= 100 ? 'range-success' : ($todo->progress >= 50 ? 'range-warning' : 'range-info') }}" />
                        <div class="flex justify-between text-xs opacity-50 mt-1">
                            <span>0%</span>
                            <span>50%</span>
                            <span>100%</span>
                        </div>
                    </div>
                </div>

                @if($todo->status === 'pending' && $todo->progress < 100)
                    <div class="mt-4">
                        <x-button label="Mark as Complete" wire:click="toggleComplete" class="btn-success w-full"
                            icon="o-check-circle" spinner="toggleComplete" />
                    </div>
                @elseif($todo->status === 'completed')
                    <div class="mt-4">
                        <x-button label="Reopen Task" wire:click="toggleComplete" class="btn-warning w-full"
                            icon="o-arrow-path" spinner="toggleComplete" />
                    </div>
                @endif
            </x-card>

            {{-- DESCRIPTION --}}
            <x-card title="{{ __('app.todos.description') }}" shadow class="bg-base-100">
                @if($todo->description)
                    <div class="prose max-w-none">
                        {!! nl2br(e($todo->description)) !!}
                    </div>
                @else
                    <div class="text-center py-8 opacity-50">
                        <x-icon name="o-document-text" class="w-12 h-12 mx-auto mb-2" />
                        <p>No description provided</p>
                        <x-button label="Add Description" link="{{ route('todos.edit', $todo) }}"
                            class="btn-ghost btn-sm mt-2" icon="o-plus" />
                    </div>
                @endif
            </x-card>

            {{-- TIMELINE / ACTIVITY --}}
            <x-card title="Timeline" shadow class="bg-base-100">
                <x-timeline>
                    <x-timeline-item title="Created" subtitle="{{ $todo->created_at->format('M d, Y \a\t H:i') }}"
                        description="{{ $todo->created_at->diffForHumans() }}" icon="o-plus-circle" first />
                    @if($todo->updated_at->gt($todo->created_at))
                        <x-timeline-item title="Last Updated" subtitle="{{ $todo->updated_at->format('M d, Y \a\t H:i') }}"
                            description="{{ $todo->updated_at->diffForHumans() }}" icon="o-pencil" />
                    @endif
                    @if($todo->due_date)
                        <x-timeline-item title="Due Date" subtitle="{{ $todo->due_date->format('M d, Y') }}"
                            :description="$todo->due_date->isPast() ? 'Overdue!' : $todo->due_date->diffForHumans()"
                            icon="o-calendar" :class="$todo->due_date->isPast() && $todo->status !== 'completed' ? 'text-error' : ''" />
                    @endif
                    @if($todo->status === 'completed')
                        <x-timeline-item title="Completed" subtitle="Task finished" description="Great job!"
                            icon="o-check-circle" last />
                    @endif
                </x-timeline>
            </x-card>
        </div>

        {{-- SIDEBAR --}}
        <div class="space-y-6">
            {{-- DETAILS CARD --}}
            <x-card title="Details" shadow class="bg-base-100">
                <div class="space-y-4">
                    {{-- Category --}}
                    <x-list-item :item="$todo" no-separator no-hover>
                        <x-slot:avatar>
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                                <x-icon name="o-folder" class="w-5 h-5 text-primary" />
                            </div>
                        </x-slot:avatar>
                        <x-slot:value>
                            {{ __('app.common.category') }}
                        </x-slot:value>
                        <x-slot:sub-value>
                            @if($todo->category)
                                <div class="flex items-center gap-2">
                                    @if($todo->category->color)
                                        <div class="w-3 h-3 rounded-full"
                                            style="background-color: {{ $todo->category->color }}"></div>
                                    @endif
                                    <span>{{ $todo->category->name }}</span>
                                </div>
                            @else
                                <span class="opacity-50">Uncategorized</span>
                            @endif
                        </x-slot:sub-value>
                    </x-list-item>

                    {{-- Status --}}
                    <x-list-item :item="$todo" no-separator no-hover>
                        <x-slot:avatar>
                            <div
                                class="w-10 h-10 rounded-full {{ $todo->status === 'completed' ? 'bg-success/10' : 'bg-warning/10' }} flex items-center justify-center">
                                <x-icon name="{{ $todo->status === 'completed' ? 'o-check-circle' : 'o-clock' }}"
                                    class="w-5 h-5 {{ $todo->status === 'completed' ? 'text-success' : 'text-warning' }}" />
                            </div>
                        </x-slot:avatar>
                        <x-slot:value>
                            {{ __('app.common.status') }}
                        </x-slot:value>
                        <x-slot:sub-value>
                            <x-badge :value="ucfirst($todo->status)" :class="$todo->status == 'completed' ? 'badge-success' : 'badge-warning'" />
                        </x-slot:sub-value>
                    </x-list-item>

                    {{-- Due Date --}}
                    @if($todo->due_date)
                        <x-list-item :item="$todo" no-separator no-hover>
                            <x-slot:avatar>
                                <div
                                    class="w-10 h-10 rounded-full {{ $todo->due_date->isPast() && $todo->status !== 'completed' ? 'bg-error/10' : 'bg-info/10' }} flex items-center justify-center">
                                    <x-icon name="o-calendar"
                                        class="w-5 h-5 {{ $todo->due_date->isPast() && $todo->status !== 'completed' ? 'text-error' : 'text-info' }}" />
                                </div>
                            </x-slot:avatar>
                            <x-slot:value>
                                {{ __('app.common.due_date') }}
                            </x-slot:value>
                            <x-slot:sub-value>
                                <div>
                                    <div class="font-bold">{{ $todo->due_date->format('M d, Y') }}</div>
                                    <div
                                        class="text-xs {{ $todo->due_date->isPast() && $todo->status !== 'completed' ? 'text-error' : 'text-info' }}">
                                        {{ $todo->due_date->diffForHumans() }}
                                    </div>
                                </div>
                            </x-slot:sub-value>
                        </x-list-item>
                    @endif
                </div>
            </x-card>

            {{-- TAGS CARD --}}
            @if($todo->tags->count())
                <x-card title="{{ __('app.common.tags') }}" shadow class="bg-base-100">
                    <div class="flex flex-wrap gap-2">
                        @foreach($todo->tags as $tag)
                            <x-badge :value="$tag->name" style="background-color: {{ $tag->color }}; color: white;"
                                class="badge-lg" />
                        @endforeach
                    </div>
                </x-card>
            @endif

            {{-- QUICK ACTIONS --}}
            <x-card title="Quick Actions" shadow class="bg-base-100">
                <div class="space-y-2">
                    <x-button label="{{ __('app.common.edit') }}" link="{{ route('todos.edit', $todo) }}"
                        class="btn-outline w-full" icon="o-pencil" />
                    <x-button label="Duplicate" link="{{ route('todos.create') }}?from={{ $todo->id }}"
                        class="btn-outline w-full" icon="o-document-duplicate" />
                    <x-button label="{{ __('app.common.delete') }}" wire:click="$set('deleteModal', true)"
                        class="btn-outline btn-error w-full" icon="o-trash" />
                </div>
            </x-card>
        </div>
    </div>
</div>