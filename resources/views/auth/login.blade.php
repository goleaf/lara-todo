<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-2xl font-bold italic">{{ __('Welcome back!') }}</h2>
        <p class="text-sm opacity-60">{{ __('Sign in to manage your tasks.') }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <x-form method="POST" action="{{ route('login') }}">
        @csrf

        <x-input label="{{ __('Email') }}" name="email" type="email" :value="old('email')" required autofocus
            icon="o-at-symbol" />

        <x-input label="{{ __('Password') }}" name="password" type="password" required icon="o-key">
            <x-slot:append>
                @if (Route::has('password.request'))
                    <a class="text-xs link link-hover text-primary pt-2 pr-2" href="{{ route('password.request') }}">
                        {{ __('Forgot?') }}
                    </a>
                @endif
            </x-slot:append>
        </x-input>

        <x-checkbox label="{{ __('Remember me') }}" name="remember" />

        <x-slot:actions>
            <x-button label="{{ __('Log in') }}" type="submit" class="btn-primary w-full"
                icon="o-arrow-right-start-on-rectangle" />
        </x-slot:actions>
    </x-form>

    <div class="divider my-8">OR</div>

    <div class="text-center">
        <p class="text-sm">
            {{ __("Don't have an account?") }}
            <a href="{{ route('register') }}" class="link link-primary font-bold">{{ __('Register here') }}</a>
        </p>
    </div>

    {{-- TEST USERS --}}
    <div class="mt-12">
        <div class="collapse collapse-arrow bg-base-200 border border-base-300">
            <input type="checkbox" />
            <div class="collapse-title text-sm font-bold flex items-center gap-2">
                <x-icon name="o-beaker" class="w-4 h-4 text-primary" />
                {{ __('Test Users (Password: password)') }}
            </div>
            <div class="collapse-content space-y-2">
                @foreach (\App\Models\User::withCount(['todos'])->limit(5)->get() as $user)
                    <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover
                        class="bg-base-100 mb-2 rounded-xl shadow-sm border border-base-300">
                        <x-slot:actions>
                            <x-badge :value="$user->todos_count" class="badge-neutral group-hover:badge-primary" />
                        </x-slot:actions>
                    </x-list-item>
                @endforeach
            </div>
        </div>
    </div>
</x-guest-layout>