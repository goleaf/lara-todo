<div>
    <x-header title="{{ __('app.todos.edit') }}" subtitle="{{ $todo->title }}" separator progress-indicator>
        <x-slot:actions>
            <x-button label="{{ __('app.common.cancel') }}" link="{{ route('todos.index') }}" />
            <x-button label="View" link="{{ route('todos.show', $todo) }}" icon="o-eye" class="btn-ghost" />
        </x-slot:actions>
    </x-header>

    {{-- STATUS CHANGE CONFIRMATION MODAL --}}
    <x-modal wire:model="showStatusModal" title="Change Status?" class="backdrop-blur">
        <div class="text-center">
            <x-icon name="{{ $pendingStatus === 'completed' ? 'o-check-circle' : 'o-clock' }}"
                class="w-16 h-16 mx-auto {{ $pendingStatus === 'completed' ? 'text-success' : 'text-warning' }} mb-4" />
            <p>Change status to <strong>{{ ucfirst($pendingStatus) }}</strong>?</p>
            @if($pendingStatus === 'completed')
                <p class="text-sm opacity-60 mt-2">Progress will be set to 100%</p>
            @endif
        </div>
        <x-slot:actions>
            <x-button label="{{ __('app.common.cancel') }}" @click="$wire.showStatusModal = false" />
            <x-button label="Confirm" wire:click="applyStatusChange"
                class="{{ $pendingStatus === 'completed' ? 'btn-success' : 'btn-warning' }}"
                icon="o-check" spinner="applyStatusChange" />
        </x-slot:actions>
    </x-modal>

    <x-card shadow class="bg-base-100 max-w-4xl mx-auto">
        <x-form wire:submit="save">
            {{-- TABS FOR FORM SECTIONS --}}
            <x-tabs wire:model="activeTab">
                {{-- DETAILS TAB --}}
                <x-tab name="details-tab" label="Details" icon="o-pencil">
                    <div class="space-y-4 pt-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-input label="{{ __('app.common.title') }}" wire:model="title" icon="o-pencil"
                                placeholder="Task title" clearable />
                            <x-select label="{{ __('app.common.category') }}" wire:model="category_id" :options="$categories"
                                icon="o-folder" placeholder="Select category" />
                        </div>

                        <x-textarea label="{{ __('app.todos.description') }}" wire:model="description" rows="4"
                            placeholder="Add details about this task..." />
                    </div>
                </x-tab>

                {{-- ORGANIZATION TAB --}}
                <x-tab name="organize-tab" label="Tags & Date" icon="o-tag">
                    <div class="space-y-4 pt-4">
                        <x-datetime label="{{ __('app.common.due_date') }}" wire:model="due_date" icon="o-calendar"
                            hint="Leave empty for no deadline" />

                        <x-select label="{{ __('app.common.tags') }}" wire:model="tags" :options="$tags" icon="o-tag"
                            multiple placeholder="Select tags..." />

                        @if($tags->isNotEmpty())
                            <div class="flex flex-wrap gap-2">
                                @foreach($tags as $tagId)
                                    @php $tag = $tags->find($tagId); @endphp
                                    @if($tag)
                                        <x-badge :value="$tag->name"
                                            style="background-color: {{ $tag->color }}; color: white;" />
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </x-tab>

                {{-- STATUS TAB --}}
                <x-tab name="status-tab" label="Status" icon="o-chart-bar">
                    <div class="space-y-6 pt-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Status Selection --}}
                            <div>
                                <label class="label font-semibold">{{ __('app.common.status') }}</label>
                                <div class="flex gap-2 mt-2">
                                    <x-button label="Pending"
                                        wire:click="confirmStatusChange('pending')"
                                        class="{{ $status === 'pending' ? 'btn-warning' : 'btn-outline' }}"
                                        icon="o-clock" />
                                    <x-button label="Completed"
                                        wire:click="confirmStatusChange('completed')"
                                        class="{{ $status === 'completed' ? 'btn-success' : 'btn-outline' }}"
                                        icon="o-check-circle" />
                                </div>
                            </div>

                            {{-- Current Status Badge --}}
                            <div>
                                <label class="label font-semibold">Current Status</label>
                                <div class="mt-2">
                                    <x-badge :value="ucfirst($status)"
                                        :class="$status === 'completed' ? 'badge-success badge-lg' : 'badge-warning badge-lg'" />
                                </div>
                            </div>
                        </div>

                        {{-- Progress Slider --}}
                        <div>
                            <label class="label font-semibold flex justify-between">
                                <span>Progress</span>
                                <span class="badge badge-primary">{{ $progress }}%</span>
                            </label>
                            <input type="range" min="0" max="100" step="10"
                                wire:model.live="progress" wire:change="updateProgress($event.target.value)"
                                class="range {{ $progress >= 100 ? 'range-success' : ($progress >= 50 ? 'range-warning' : 'range-info') }}" />
                            <div class="flex justify-between text-xs opacity-50 mt-1">
                                <span>0%</span>
                                <span>25%</span>
                                <span>50%</span>
                                <span>75%</span>
                                <span>100%</span>
                            </div>
                        </div>

                        {{-- Visual Progress Card --}}
                        <x-card class="bg-base-200/50 border border-base-300">
                            <div class="flex items-center gap-4">
                                <div class="radial-progress {{ $progress >= 100 ? 'text-success' : ($progress >= 50 ? 'text-warning' : 'text-info') }}"
                                    style="--value:{{ $progress }}; --size:5rem;">
                                    {{ $progress }}%
                                </div>
                                <div>
                                    <h4 class="font-bold">{{ $status === 'completed' ? 'Task Complete!' : 'In Progress' }}</h4>
                                    <p class="text-sm opacity-60">
                                        @if($progress >= 100)
                                            Great job! You've completed this task.
                                        @elseif($progress >= 75)
                                            Almost there! Just a little more to go.
                                        @elseif($progress >= 50)
                                            You're halfway through! Keep going.
                                        @elseif($progress >= 25)
                                            Good start! Keep up the momentum.
                                        @else
                                            Just getting started. You've got this!
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </x-card>
                    </div>
                </x-tab>
            </x-tabs>

            <x-slot:actions>
                <x-button label="{{ __('app.common.cancel') }}" link="{{ route('todos.index') }}" />
                <x-button label="{{ __('app.common.update') }}" type="submit" class="btn-primary" icon="o-check"
                    spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>