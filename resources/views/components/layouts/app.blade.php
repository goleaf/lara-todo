<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased bg-base-200/50 dark:bg-base-200">
    {{-- NAVBAR --}}
    <x-nav sticky full-width>
        <x-slot:brand>
            <div class="flex items-center gap-2">
                <x-icon name="o-sparkles" class="w-6 h-6 text-primary" />
                <span class="font-bold text-xl">{{ config('app.name') }}</span>
            </div>
        </x-slot:brand>

        <x-slot:actions>
            <x-theme-toggle />
            <x-dropdown>
                <x-slot:trigger>
                    <x-button label="{{ Auth::user()->name }}" icon="o-user" class="btn-ghost" />
                </x-slot:trigger>
                <x-menu-item title="{{ __('Profile') }}" link="{{ route('profile.edit') }}" icon="o-user-circle" />
                <x-menu-separator />
                <x-form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-menu-item title="{{ __('Log Out') }}"
                        onclick="event.preventDefault(); this.closest('form').submit();" icon="o-power" />
                </x-form>
            </x-dropdown>
        </x-slot:actions>
    </x-nav>

    {{-- MAIN --}}
    <x-main full-width>
        {{-- SIDEBAR --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100">
            <x-menu activate-by-route>
                <x-menu-item title="Dashboard" icon="o-home" link="{{ route('dashboard') }}" />
                <x-menu-separator />
                <x-menu-item title="{{ __('app.todos.title') }}" icon="o-check-circle"
                    link="{{ route('todos.index') }}" />
                <x-menu-item title="{{ __('app.categories.title') }}" icon="o-folder"
                    link="{{ route('categories.index') }}" />
                <x-menu-item title="{{ __('app.tags.title') }}" icon="o-tag" link="{{ route('tags.index') }}" />
            </x-menu>
        </x-slot:sidebar>

        {{-- CONTENT --}}
        <x-slot:content>
            @if(isset($header))
                <div class="sr-only">{{ $header }}</div>
            @endif
            {{ $slot }}
        </x-slot:content>
    </x-main>

    {{-- TOAST --}}
    <x-toast />
    @livewireScripts
</body>

</html>