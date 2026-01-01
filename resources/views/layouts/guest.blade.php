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

<body class="font-sans text-base-content antialiased bg-base-200/50 dark:bg-base-200">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="mb-8">
            <a href="/" class="flex flex-col items-center gap-2">
                <x-icon name="o-sparkles" class="w-16 h-16 text-primary" />
                <span class="text-3xl font-black">{{ config('app.name') }}</span>
            </a>
        </div>

        <x-card class="w-full sm:max-w-md bg-base-100 shadow-xl">
            {{ $slot }}
        </x-card>

        <div class="mt-8">
            <x-theme-toggle />
        </div>
    </div>
    @livewireScripts
</body>

</html>