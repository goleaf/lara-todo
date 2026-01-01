<x-app-layout>
    <x-header title="{{ __('app.dashboard.title') }}" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <div class="flex items-center gap-2">
                <div class="text-sm font-medium opacity-70">
                    {{ __('app.dashboard.welcome', ['name' => Auth::user()->name]) }}
                </div>
                <x-avatar :image="Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name)" class="!w-10 !h-10" />
            </div>
        </x-slot:middle>
    </x-header>

    {{-- STATS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <x-stat title="{{ __('app.dashboard.stats.total_todos') }}" value="{{ $stats['total_todos'] }}"
            icon="o-list-bullet" class="bg-base-100 shadow" />
        <x-stat title="{{ __('app.dashboard.stats.pending_todos') }}" value="{{ $stats['pending_todos'] }}"
            icon="o-clock" class="bg-warning/10 text-warning shadow" />
        <x-stat title="{{ __('app.dashboard.stats.completed_todos') }}" value="{{ $stats['completed_todos'] }}"
            icon="o-check-circle" class="bg-success/10 text-success shadow" />
        <x-stat title="{{ __('app.dashboard.stats.total_categories') }}" value="{{ $stats['total_categories'] }}"
            icon="o-folder" class="bg-base-100 shadow" />
        <x-stat title="{{ __('app.dashboard.stats.total_tags') }}" value="{{ $stats['total_tags'] }}" icon="o-tag"
            class="bg-base-100 shadow" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- RECENT TODOS --}}
        <div class="lg:col-span-2 space-y-4">
            <x-card title="{{ __('app.dashboard.recent_todos') }}" shadow class="bg-base-100">
                @if($recent_todos->count())
                    @foreach($recent_todos as $todo)
                        <x-list-item :item="$todo" value="title" sub-value="description" link="{{ route('todos.show', $todo) }}"
                            class="hover:bg-base-200/50 rounded-lg transition-colors">
                            <x-slot:actions>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs opacity-50">{{ $todo->due_date?->format('M d') }}</span>
                                    <x-badge :value="ucfirst($todo->status)" :class="$todo->status == 'completed' ? 'badge-success' : 'badge-warning'" />
                                </div>
                            </x-slot:actions>
                        </x-list-item>
                    @endforeach
                    <div class="mt-4">
                        <x-button label="View All" link="{{ route('todos.index') }}" class="btn-ghost btn-sm"
                            icon="o-arrow-right" />
                    </div>
                @else
                    <div class="text-center py-10">
                        <x-icon name="o-clipboard" class="w-16 h-16 mx-auto text-base-300" />
                        <h3 class="text-lg font-bold mt-4">All caught up!</h3>
                        <p class="opacity-60">You have no pending tasks. Enjoy your day!</p>
                        <x-button label="Create Task" link="{{ route('todos.create') }}" class="btn-primary mt-4"
                            icon="o-plus" />
                    </div>
                @endif
            </x-card>
        </div>

        {{-- QUICK CREATE --}}
        <div class="space-y-4">
            <x-card title="{{ __('app.dashboard.quick_create') }}" shadow class="bg-primary/5 border border-primary/10">
                <x-form method="POST" action="{{ route('todos.store') }}">
                    @csrf
                    <x-input label="{{ __('app.common.title') }}" name="title" required
                        placeholder="What needs to be done?" />
                    <x-select label="{{ __('app.common.category') }}" name="category_id" :options="$categories"
                        placeholder="Select category" required />

                    <x-slot:actions>
                        <x-button label="{{ __('app.common.create') }}" type="submit" class="btn-primary w-full"
                            icon="o-plus" />
                    </x-slot:actions>
                </x-form>
            </x-card>

            <x-card title="Shortcuts" shadow class="bg-base-100">
                <div class="grid grid-cols-2 gap-2">
                    <x-button label="New Category" link="{{ route('categories.create') }}" icon="o-folder-plus"
                        class="btn-outline btn-sm" />
                    <x-button label="New Tag" link="{{ route('tags.create') }}" icon="o-tag"
                        class="btn-outline btn-sm" />
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>