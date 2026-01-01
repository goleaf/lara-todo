<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-2xl font-bold italic">{{ __('Forgot Password?') }}</h2>
        <p class="text-sm opacity-60">
            {{ __('No problem. Just let us know your email address and we will email you a password reset link.') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <x-form method="POST" action="{{ route('password.email') }}">
        @csrf

        <x-input label="{{ __('Email') }}" name="email" type="email" :value="old('email')" required autofocus
            icon="o-at-symbol" />

        <x-slot:actions>
            <x-button label="{{ __('Email Password Reset Link') }}" type="submit" class="btn-primary w-full"
                icon="o-paper-airplane" />
        </x-slot:actions>
    </x-form>

    <div class="mt-8 text-center">
        <a href="{{ route('login') }}" class="link link-primary text-sm font-bold">{{ __('Back to login') }}</a>
    </div>
</x-guest-layout>