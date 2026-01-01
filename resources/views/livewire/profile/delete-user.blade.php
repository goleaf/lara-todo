<section class="space-y-6">
    <x-card title="{{ __('Delete Account') }}"
        subtitle="{{ __('Once your account is deleted, all of its resources and data will be permanently deleted.') }}"
        shadow class="bg-base-100 border border-error/20">
        <x-button label="{{ __('Delete Account') }}"
            onclick="document.getElementById('confirm-user-deletion').showModal()" class="btn-error" icon="o-trash" />

        <x-modal id="confirm-user-deletion" class="backdrop-blur">
            <x-card title="{{ __('Are you sure you want to delete your account?') }}" shadow class="bg-base-100">
                <p class="text-sm opacity-70 mb-6">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>

                <x-form wire:submit="deleteUser">
                    <x-input label="{{ __('Password') }}" wire:model="password" type="password"
                        placeholder="{{ __('Password') }}" required icon="o-key" />

                    <x-slot:actions>
                        <x-button label="{{ __('Cancel') }}" onclick="document.getElementById('confirm-user-deletion').close()"
                            class="btn-ghost" />
                        <x-button label="{{ __('Delete Account') }}" type="submit" class="btn-error" icon="o-trash"
                            spinner="deleteUser" />
                    </x-slot:actions>
                </x-form>
            </x-card>
        </x-modal>
    </x-card>
</section>