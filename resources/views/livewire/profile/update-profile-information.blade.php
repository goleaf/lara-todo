<section>
    <x-card title="{{ __('Update Profile Information') }}"
        subtitle="{{ __('Update your account profile information and email address.') }}" shadow class="bg-base-100">
        <x-form wire:submit="updateProfileInformation" class="mt-6">
            <x-file label="{{ __('Avatar') }}" wire:model="avatar" accept="image/*" />

            @if (auth()->user()->avatar)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-20 h-20 rounded-full object-cover">
                </div>
            @endif

            <x-input label="{{ __('Name') }}" wire:model="name" required autofocus icon="o-user" />
            <x-input label="{{ __('Email') }}" wire:model="email" type="email" required icon="o-at-symbol" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                <div class="alert alert-warning shadow-sm">
                    <x-icon name="o-exclamation-triangle" class="w-6 h-6" />
                    <div>
                        <p class="text-sm">{{ __('Your email address is unverified.') }}</p>
                        <x-button label="{{ __('Click here to re-send the verification email.') }}" form="send-verification"
                            class="btn-link btn-xs p-0 h-auto" />
                    </div>
                </div>
            @endif

            <x-slot:actions>
                <div class="flex items-center gap-4">
                    <x-button label="{{ __('Save') }}" type="submit" class="btn-primary" icon="o-check"
                        spinner="updateProfileInformation" />

                    <x-action-message class="me-3" on="profile-updated">
                        {{ __('Saved.') }}
                    </x-action-message>
                </div>
            </x-slot:actions>
        </x-form>
    </x-card>
</section>