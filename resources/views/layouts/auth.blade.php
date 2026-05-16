<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Login - Zona Adventure Cianjur POS Rental">

    <title>@yield('title', 'Login') - {{ config('app.name') }}</title>

    {{-- Favicon --}}
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><text y='28' font-size='28'>🏕️</text></svg>" type="image/svg+xml">

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center" style="background-color: var(--color-mist);">

    <div class="w-full max-w-md mx-auto px-4">
        {{-- Brand Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4" style="background-color: var(--color-forest);">
                <span class="text-3xl">🏕️</span>
            </div>
            <h1 class="text-2xl font-semibold" style="color: var(--color-forest);">Zona Adventure</h1>
            <p class="text-sm mt-1" style="color: var(--color-stone);">Sistem POS Rental Alat Camping - Cianjur</p>
        </div>

        {{-- Card Container --}}
        <div class="bg-white rounded-2xl p-8" style="box-shadow: var(--shadow-elevated);">
            @yield('content')
        </div>

        {{-- Footer --}}
        <p class="text-center mt-6 text-xs" style="color: var(--color-stone);">
            &copy; {{ date('Y') }} Zona Adventure Cianjur
        </p>
    </div>

    <x-toast />
    @stack('scripts')
</body>
</html>
