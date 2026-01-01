<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-2xl font-bold italic">{{ __('Join us!') }}</h2>
        <p class="text-sm opacity-60">{{ __('Create an account to start organizing your life.') }}</p>
    </div>

    <x-form method="POST" action="{{ route('register') }}">
        @csrf

        <x-input label="{{ __('Name') }}" name="name" :value="old('name')" required autofocus icon="o-user" />
        <x-input label="{{ __('Email') }}" name="email" type="email" :value="old('email')" required
            icon="o-at-symbol" />
        <x-input label="{{ __('Password') }}" name="password" type="password" required autocomplete="new-password"
            icon="o-key" />
        <x-input label="{{ __('Confirm Password') }}" name="password_confirmation" type="password" required
            autocomplete="new-password" icon="o-shield-check" />

        <x-slot:actions>
            <x-button label="{{ __('Register') }}" type="submit" class="btn-primary w-full" icon="o-user-plus" />
        </x-slot:actions>
    </x-form>

    <div class="divider my-8">OR</div>

    <div class="text-center">
        <p class="text-sm">
            {{ __("Already have an account?") }}
            <a href="{{ route('login') }}" class="link link-primary font-bold">{{ __('Log in here') }}</a>
        </p>
    </div>
</x-guest-layout>