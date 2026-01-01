<section>
    <x-card title="{{ __('Update Password') }}"
        subtitle="{{ __('Ensure your account is using a long, random password to stay secure.') }}" shadow
        class="bg-base-100">
        <x-form wire:submit="updatePassword" class="mt-6">
            <x-input label="{{ __('Current Password') }}" wire:model="current_password" type="password"
                autocomplete="current-password" icon="o-key" />
            <x-input label="{{ __('New Password') }}" wire:model="password" type="password" autocomplete="new-password"
                icon="o-lock-closed" />
            <x-input label="{{ __('Confirm Password') }}" wire:model="password_confirmation" type="password"
                autocomplete="new-password" icon="o-shield-check" />

            <x-slot:actions>
                <div class="flex items-center gap-4">
                    <x-button label="{{ __('Save') }}" type="submit" class="btn-primary" icon="o-check"
                        spinner="updatePassword" />

                    <x-action-message class="me-3" on="password-updated">
                        {{ __('Saved.') }}
                    </x-action-message>
                </div>
            </x-slot:actions>
        </x-form>
    </x-card>
</section>