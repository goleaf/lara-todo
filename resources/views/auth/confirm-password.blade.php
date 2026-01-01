<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-2xl font-bold italic">{{ __('Secure Area') }}</h2>
        <p class="text-sm opacity-60">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </p>
    </div>

    <x-form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <x-input label="{{ __('Password') }}" name="password" type="password" required autocomplete="current-password"
            icon="o-key" />

        <x-slot:actions>
            <x-button label="{{ __('Confirm') }}" type="submit" class="btn-primary w-full" icon="o-check-badge" />
        </x-slot:actions>
    </x-form>
</x-guest-layout>