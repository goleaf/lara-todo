<x-app-layout>
    <x-header title="{{ __('Profile') }}" separator progress-indicator />

    <div class="space-y-6 max-w-5xl mx-auto">
        <x-card title="{{ __('Update Profile Information') }}"
            subtitle="{{ __('Update your account profile information and email address.') }}" shadow
            class="bg-base-100">
            @include('profile.partials.update-profile-information-form')
        </x-card>

        <x-card title="{{ __('Update Password') }}"
            subtitle="{{ __('Ensure your account is using a long, random password to stay secure.') }}" shadow
            class="bg-base-100">
            @include('profile.partials.update-password-form')
        </x-card>

        <x-card title="{{ __('Delete Account') }}"
            subtitle="{{ __('Once your account is deleted, all of its resources and data will be permanently deleted.') }}"
            shadow class="bg-base-100 border border-error/20">
            @include('profile.partials.delete-user-form')
        </x-card>
    </div>
</x-app-layout>