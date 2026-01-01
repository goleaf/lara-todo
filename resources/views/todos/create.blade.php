<div>
    <x-header title="{{ __('app.todos.create') }}" subtitle="Create a new task step by step" separator
        progress-indicator>
        <x-slot:actions>
            <x-button label="{{ __('app.common.cancel') }}" link="{{ route('todos.index') }}" icon="o-x-mark" />
        </x-slot:actions>
    </x-header>

    {{-- STEPS INDICATOR --}}
    <x-steps wire:model="step" class="border-y border-base-content/10 my-6 py-5">
        <x-step step="1" text="Basic Info">
            <x-icon name="o-pencil" class="w-5 h-5" />
        </x-step>
        <x-step step="2" text="Category & Tags">
            <x-icon name="o-folder" class="w-5 h-5" />
        </x-step>
        <x-step step="3" text="Schedule">
            <x-icon name="o-calendar" class="w-5 h-5" />
        </x-step>
    </x-steps>

    <x-card shadow class="bg-base-100 max-w-2xl mx-auto" progress-indicator>
        <x-form wire:submit="save">
            {{-- STEP 1: Basic Information --}}
            <div class="{{ $step !== 1 ? 'hidden' : '' }}">
                <div class="space-y-6">
                    <x-header title="What needs to be done?" subtitle="Describe your task" size="text-xl"
                        class="!mb-0" />

                    <x-input label="{{ __('app.common.title') }}" wire:model="title" icon="o-pencil"
                        placeholder="Enter a descriptive title..." hint="Be specific about what you need to accomplish"
                        clearable />

                    <x-textarea label="{{ __('app.todos.description') }}" wire:model="description" rows="4"
                        placeholder="Add more details about the task..." hint="Optional: Add context or steps needed" />
                </div>
            </div>

            {{-- STEP 2: Category & Tags --}}
            <div class="{{ $step !== 2 ? 'hidden' : '' }}">
                <div class="space-y-6">
                    <x-header title="Organize your task" subtitle="Select category and tags" size="text-xl"
                        class="!mb-0" />

                    <x-select label="{{ __('app.common.category') }}" wire:model="category_id" :options="$categories"
                        icon="o-folder" placeholder="Select a category..." hint="Group your tasks by project or area" />

                    <x-select label="{{ __('app.common.tags') }}" wire:model="tags" :options="$tags" icon="o-tag"
                        multiple placeholder="Add tags..." hint="Use tags for better filtering" />

                    @if($categories->isEmpty())
                        <x-alert title="No categories yet" description="Create a category first to organize your tasks."
                            icon="o-information-circle" class="alert-warning">
                            <x-slot:actions>
                                <x-button label="Create Category" link="{{ route('categories.create') }}"
                                    class="btn-warning btn-sm" icon="o-plus" />
                            </x-slot:actions>
                        </x-alert>
                    @endif
                </div>
            </div>

            {{-- STEP 3: Schedule --}}
            <div class="{{ $step !== 3 ? 'hidden' : '' }}">
                <div class="space-y-6">
                    <x-header title="When is it due?" subtitle="Set a deadline (optional)" size="text-xl"
                        class="!mb-0" />

                    <x-datetime label="{{ __('app.common.due_date') }}" wire:model="due_date" icon="o-calendar"
                        hint="Leave empty if there's no deadline" />

                    {{-- Summary Card --}}
                    <x-card title="Summary" class="bg-base-200/50 border border-base-300">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm opacity-60">Title</span>
                                <span class="font-medium">{{ $title ?: '(not set)' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm opacity-60">Category</span>
                                <span class="font-medium">
                                    @if($category_id)
                                        {{ $categories->find($category_id)?->name ?? 'Unknown' }}
                                    @else
                                        (not set)
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm opacity-60">Tags</span>
                                <div class="flex gap-1">
                                    @forelse($tags as $tagId)
                                        @php $tag = $tags->find($tagId); @endphp
                                        @if($tag)
                                            <x-badge :value="$tag->name" class="badge-sm badge-primary" />
                                        @endif
                                    @empty
                                        <span class="text-sm opacity-40">None</span>
                                    @endforelse
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm opacity-60">Due Date</span>
                                <span class="font-medium">{{ $due_date ?: 'No deadline' }}</span>
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>

            <x-slot:actions>
                {{-- Navigation buttons --}}
                <div class="flex justify-between w-full">
                    <div>
                        @if($step > 1)
                            <x-button label="Previous" wire:click="prevStep" icon="o-arrow-left" class="btn-ghost" />
                        @else
                            <x-button label="{{ __('app.common.cancel') }}" link="{{ route('todos.index') }}"
                                class="btn-ghost" />
                        @endif
                    </div>
                    <div class="flex gap-2">
                        @if($step < $totalSteps)
                            <x-button label="Next" wire:click="nextStep" icon-right="o-arrow-right" class="btn-primary"
                                spinner="nextStep" />
                        @else
                            <x-button label="{{ __('app.common.create') }}" type="submit" class="btn-primary" icon="o-check"
                                spinner="save" />
                        @endif
                    </div>
                </div>
            </x-slot:actions>
        </x-form>
    </x-card>
</div>