<div>
    <x-header title="{{ __('app.categories.edit') }}" separator progress-indicator>
        <x-slot:actions>
            <x-button label="{{ __('app.common.cancel') }}" link="{{ route('categories.index') }}" />
        </x-slot:actions>
    </x-header>

    <x-card shadow class="bg-base-100 max-w-2xl mx-auto">
        <x-form wire:submit="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="{{ __('app.common.name') }}" wire:model="name" icon="o-folder" />
                <x-colorpicker label="{{ __('app.common.color') }}" wire:model="color" icon="o-swatch" />
            </div>

            <x-slot:actions>
                <x-button label="{{ __('app.common.cancel') }}" link="{{ route('categories.index') }}" />
                <x-button label="{{ __('app.common.update') }}" type="submit" class="btn-primary" icon="o-check" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>