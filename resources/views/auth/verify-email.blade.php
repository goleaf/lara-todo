<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-2xl font-bold italic">{{ __('Verify Email') }}</h2>
        <p class="text-sm opacity-60">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success shadow-sm mb-4">
            <x-icon name="o-check-circle" class="w-6 h-6" />
            <span class="text-sm">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </span>
        </div>
    @endif

    <div class="mt-8 space-y-4">
        <x-form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-button label="{{ __('Resend Verification Email') }}" type="submit" class="btn-primary w-full"
                icon="o-paper-airplane" />
        </x-form>

        <x-form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-button label="{{ __('Log Out') }}" type="submit" class="btn-ghost w-full btn-sm"
                icon="o-arrow-left-on-rectangle" />
        </x-form>
    </div>
</x-guest-layout>