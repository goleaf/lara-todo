<div>
    <x-header title="{{ __('Profile') }}" separator progress-indicator />

    <div class="space-y-6 max-w-5xl mx-auto">
        <livewire:profile.update-profile-information />
        <livewire:profile.update-password />
        <livewire:profile.delete-user />
    </div>
</div>