<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - organize your life</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="antialiased bg-base-200 dark:bg-base-300 text-base-content font-sans">
    {{-- NAVBAR --}}
    <x-nav sticky full-width>
        <x-slot:brand>
            <div class="flex items-center gap-2">
                <x-icon name="o-sparkles" class="w-8 h-8 text-primary" />
                <span class="font-black text-2xl tracking-tight">{{ config('app.name') }}</span>
            </div>
        </x-slot:brand>
        <x-slot:actions>
            @if (Route::has('login'))
                <div class="flex items-center gap-2">
                    @auth
                        <x-button label="Go to Dashboard" link="{{ url('/dashboard') }}" class="btn-primary" icon="o-home" />
                    @else
                        <x-button label="Log in" link="{{ route('login') }}" class="btn-ghost" />
                        @if (Route::has('register'))
                            <x-button label="Register" link="{{ route('register') }}" class="btn-primary" />
                        @endif
                    @endauth
                    <x-theme-toggle />
                </div>
            @endif
        </x-slot:actions>
    </x-nav>

    {{-- HERO --}}
    <div class="hero min-h-[80vh] bg-base-100">
        <div class="hero-content text-center">
            <div class="max-w-2xl">
                <h1 class="text-6xl font-black mb-8 leading-tight">
                    Manage your tasks <br>
                    <span class="text-primary italic">with elegance</span>
                </h1>
                <p class="text-xl mb-12 opacity-80 leading-relaxed px-12">
                    The simplest yet most powerful way to organize your daily work.
                    Built with Laravel 12 and MaryUI for a premium experience.
                </p>
                <div class="flex justify-center gap-4">
                    <x-button label="Get Started for Free" link="{{ route('register') }}"
                        class="btn-primary btn-lg px-8" />
                    <x-button label="Learn More" link="#features" class="btn-outline btn-lg px-8" />
                </div>
            </div>
        </div>
    </div>

    {{-- FEATURES --}}
    <div id="features" class="py-24 px-8 max-w-7xl mx-auto">
        <div class="text-center mb-20">
            <h2 class="text-4xl font-black mb-4">Everything you need</h2>
            <div class="w-24 h-1.5 bg-primary mx-auto rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <x-card title="Smart Categories" shadow class="bg-base-100 p-4 hover:shadow-xl transition-all duration-300">
                <x-slot:figure>
                    <div class="p-8 bg-primary/5">
                        <x-icon name="o-folder" class="w-16 h-16 text-primary mx-auto" />
                    </div>
                </x-slot:figure>
                Organize your todos into custom categories to keep your work structured and focused.
            </x-card>

            <x-card title="Custom Tags" shadow class="bg-base-100 p-4 hover:shadow-xl transition-all duration-300">
                <x-slot:figure>
                    <div class="p-8 bg-secondary/5">
                        <x-icon name="o-tag" class="w-16 h-16 text-secondary mx-auto" />
                    </div>
                </x-slot:figure>
                Add multiple tags to your tasks with custom colors for instant visual identification.
            </x-card>

            <x-card title="Advanced Filtering" shadow
                class="bg-base-100 p-4 hover:shadow-xl transition-all duration-300">
                <x-slot:figure>
                    <div class="p-8 bg-accent/5">
                        <x-icon name="o-funnel" class="w-16 h-16 text-accent mx-auto" />
                    </div>
                </x-slot:figure>
                Find exactly what you are looking for with our powerful filtering and search system.
            </x-card>
        </div>
    </div>

    {{-- FOOTER --}}
    <footer class="footer footer-center p-10 bg-base-300 text-base-content rounded mt-20">
        <div class="flex items-center gap-2">
            <x-icon name="o-sparkles" class="w-8 h-8 text-primary" />
            <span class="font-black text-xl">{{ config('app.name') }}</span>
        </div>
        <p class="font-bold">
            Providing reliable productivity tools since 2026. <br />Made with ❤️ by Deepmind team.
        </p>
        <div>
            <div class="grid grid-flow-col gap-4">
                <a class="link link-hover">Terms of Service</a>
                <a class="link link-hover">Privacy Policy</a>
                <a class="link link-hover">Contact</a>
            </div>
        </div>
        <div>
            <p>Copyright © 2026 - All right reserved</p>
        </div>
    </footer>
    @livewireScripts
</body>

</html>