<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-2xl font-bold italic">{{ __('Reset Password') }}</h2>
        <p class="text-sm opacity-60">{{ __('Please enter your new password below.') }}</p>
    </div>

    <x-form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <x-input label="{{ __('Email') }}" name="email" type="email" :value="old('email', $request->email)" required
            autofocus icon="o-at-symbol" />
        <x-input label="{{ __('Password') }}" name="password" type="password" required autocomplete="new-password"
            icon="o-key" />
        <x-input label="{{ __('Confirm Password') }}" name="password_confirmation" type="password" required
            autocomplete="new-password" icon="o-shield-check" />

        <x-slot:actions>
            <x-button label="{{ __('Reset Password') }}" type="submit" class="btn-primary w-full" icon="o-check" />
        </x-slot:actions>
    </x-form>
</x-guest-layout>