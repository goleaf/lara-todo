<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen font-sans antialiased bg-base-200/50 dark:bg-base-200" x-data>
    {{-- NAVBAR mobile only --}}
    <x-nav sticky class="lg:hidden">
        <x-slot:brand>
            <div class="flex items-center gap-2">
                <x-icon name="o-sparkles" class="w-6 h-6 text-primary" />
                <span class="font-bold text-lg">{{ config('app.name') }}</span>
            </div>
        </x-slot:brand>
        <x-slot:actions>
            {{-- Search Button for Mobile --}}
            <x-button icon="o-magnifying-glass" class="btn-ghost btn-circle btn-sm"
                @click.stop="$dispatch('mary-search-open')" />
            <label for="main-drawer" class="lg:hidden cursor-pointer">
                <x-icon name="o-bars-3" class="w-6 h-6" />
            </label>
        </x-slot:actions>
    </x-nav>

    {{-- MAIN --}}
    <x-main full-width>
        {{-- SIDEBAR --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 lg:bg-inherit">
            {{-- BRAND --}}
            <div class="p-4 flex items-center gap-2 mb-2">
                <x-icon name="o-sparkles" class="w-8 h-8 text-primary" />
                <span class="font-bold text-xl">{{ config('app.name') }}</span>
            </div>

            {{-- SEARCH TRIGGER --}}
            <div class="px-4 mb-4">
                <x-button label="Search..." icon="o-magnifying-glass" class="btn-ghost btn-block justify-start"
                    @click.stop="$dispatch('mary-search-open')">
                    <x-slot:hint>
                        <x-kbd value="⌘" class="hidden lg:inline" />
                        <x-kbd value="K" class="hidden lg:inline" />
                    </x-slot:hint>
                </x-button>
            </div>

            {{-- MENU --}}
            <x-menu activate-by-route>
                {{-- User Info --}}
                @if($user = auth()->user())
                    <div class="px-4 mb-4">
                        <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover
                            class="bg-base-200/50 rounded-lg p-2">
                            <x-slot:avatar>
                                <x-avatar :image="$user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6366f1&color=fff'" class="!w-10 !h-10" />
                            </x-slot:avatar>
                            <x-slot:actions>
                                <x-dropdown right>
                                    <x-slot:trigger>
                                        <x-button icon="o-ellipsis-vertical" class="btn-ghost btn-xs btn-circle" />
                                    </x-slot:trigger>
                                    <x-menu-item title="{{ __('Profile') }}" icon="o-user-circle"
                                        link="{{ route('profile.edit') }}" />
                                    <x-menu-separator />
                                    <x-form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-menu-item title="{{ __('Log Out') }}" icon="o-power"
                                            onclick="event.preventDefault(); this.closest('form').submit();" />
                                    </x-form>
                                </x-dropdown>
                            </x-slot:actions>
                        </x-list-item>
                    </div>
                    <x-menu-separator />
                @endif

                {{-- Main Navigation --}}
                <x-menu-item title="Dashboard" icon="o-home" link="{{ route('dashboard') }}" />
                <x-menu-separator />

                {{-- Todo Management --}}
                <x-menu-sub title="{{ __('app.todos.title') }}" icon="o-check-circle">
                    <x-menu-item title="All Todos" icon="o-list-bullet" link="{{ route('todos.index') }}" />
                    <x-menu-item title="Create Todo" icon="o-plus" link="{{ route('todos.create') }}" />
                </x-menu-sub>

                {{-- Organization --}}
                <x-menu-sub title="Organization" icon="o-folder-open">
                    <x-menu-item title="{{ __('app.categories.title') }}" icon="o-folder"
                        link="{{ route('categories.index') }}" />
                    <x-menu-item title="{{ __('app.tags.title') }}" icon="o-tag" link="{{ route('tags.index') }}" />
                </x-menu-sub>

                <x-menu-separator />

                {{-- Quick Stats --}}
                @if($user = auth()->user())
                    @php
                        $pendingCount = $user->todos()->where('status', 'pending')->count();
                        $completedCount = $user->todos()->where('status', 'completed')->count();
                    @endphp
                    <div class="px-4 py-2">
                        <div class="text-xs font-semibold uppercase opacity-50 mb-2">Quick Stats</div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="bg-warning/10 rounded-lg p-2 text-center">
                                <div class="text-lg font-bold text-warning">{{ $pendingCount }}</div>
                                <div class="text-xs opacity-60">Pending</div>
                            </div>
                            <div class="bg-success/10 rounded-lg p-2 text-center">
                                <div class="text-lg font-bold text-success">{{ $completedCount }}</div>
                                <div class="text-xs opacity-60">Done</div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Theme Toggle --}}
                <div class="px-4 py-2 mt-auto">
                    <x-theme-toggle class="w-full" />
                </div>
            </x-menu>
        </x-slot:sidebar>

        {{-- CONTENT --}}
        <x-slot:content>
            {{-- Desktop Header Bar --}}
            <div class="hidden lg:flex items-center justify-between mb-6 py-4 px-6 bg-base-100 rounded-xl shadow-sm">
                <div class="flex items-center gap-4">
                    {{-- Breadcrumb could go here --}}
                </div>
                <div class="flex items-center gap-4">
                    {{-- Search Button --}}
                    <x-button label="Search" icon="o-magnifying-glass" class="btn-ghost"
                        @click.stop="$dispatch('mary-search-open')">
                        <x-slot:hint>
                            <x-kbd value="⌘K" />
                        </x-slot:hint>
                    </x-button>

                    <x-theme-toggle class="btn btn-ghost btn-circle" />

                    {{-- User Dropdown --}}
                    <x-dropdown right>
                        <x-slot:trigger>
                            <x-button class="btn-ghost gap-2">
                                <x-avatar :image="Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=6366f1&color=fff'" class="!w-8 !h-8" />
                                <span class="hidden md:inline">{{ Auth::user()->name }}</span>
                                <x-icon name="o-chevron-down" class="w-4 h-4" />
                            </x-button>
                        </x-slot:trigger>
                        <x-menu-item title="{{ __('Profile') }}" link="{{ route('profile.edit') }}"
                            icon="o-user-circle" />
                        <x-menu-separator />
                        <x-form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-menu-item title="{{ __('Log Out') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();" icon="o-power" />
                        </x-form>
                    </x-dropdown>
                </div>
            </div>

            @if (isset($header))
                <div class="sr-only">{{ $header }}</div>
            @endif
            {{ $slot }}
        </x-slot:content>
    </x-main>

    {{-- SPOTLIGHT SEARCH --}}
    <x-spotlight shortcut="meta.k" search-text="Search todos, categories, or actions..."
        no-results-text="No results found. Try a different search." />

    {{-- TOAST --}}
    <x-toast position="toast-top toast-end" />

    @livewireScripts
</body>

</html>