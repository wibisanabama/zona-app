<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Zona Adventure Cianjur - Sistem POS Rental Alat Camping">

    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>

    {{-- Favicon --}}
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><text y='28' font-size='28'>🏕️</text></svg>" type="image/svg+xml">

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white" x-data="{ sidebarOpen: true, mobileSidebar: false }">

    {{-- ═══ Top Navbar ═══ --}}
    <nav class="navbar fixed top-0 left-0 right-0 z-50 bg-forest text-white h-14 flex items-center px-6 shadow-sm" style="background-color: var(--color-forest);">
        <div class="flex items-center justify-between w-full">
            {{-- Left: Hamburger + Brand --}}
            <div class="flex items-center gap-4">
                {{-- Mobile hamburger --}}
                <button @click="mobileSidebar = !mobileSidebar" class="lg:hidden btn-icon text-white" aria-label="Toggle menu">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
                {{-- Desktop sidebar toggle --}}
                <button @click="sidebarOpen = !sidebarOpen" class="hidden lg:flex btn-icon text-white" aria-label="Toggle sidebar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
                {{-- Brand --}}
                <a href="/dashboard" class="flex items-center gap-2 text-white no-underline">
                    <span class="text-xl">🏕️</span>
                    <span class="font-semibold text-sm tracking-wide hidden sm:inline">Zona Adventure</span>
                </a>
            </div>

            {{-- Right: User menu --}}
            <div class="flex items-center gap-3" x-data="{ userMenu: false }">
                <div class="relative">
                    <button @click="userMenu = !userMenu" @click.outside="userMenu = false" class="flex items-center gap-2 text-white text-sm hover:opacity-90 transition-opacity cursor-pointer bg-transparent border-none">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold" style="background-color: var(--color-olive);">
                            {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                        </div>
                        <span class="hidden sm:inline">{{ Auth::user()->name ?? 'User' }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    {{-- Dropdown --}}
                    <div x-show="userMenu" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border py-1 z-50" style="border-color: var(--color-dove);" x-cloak>
                        <div class="px-4 py-2 border-b" style="border-color: var(--color-mist);">
                            <p class="text-sm font-medium text-black">{{ Auth::user()->name ?? 'User' }}</p>
                            <p class="text-xs" style="color: var(--color-stone);">{{ Auth::user()->role ?? 'staff' }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-black hover:bg-ash transition-colors cursor-pointer bg-transparent border-none" style="color: var(--color-danger);">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- ═══ Mobile Sidebar Overlay ═══ --}}
    <div x-show="mobileSidebar" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-black/50 lg:hidden" @click="mobileSidebar = false" x-cloak></div>

    <div x-show="mobileSidebar" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed top-14 left-0 bottom-0 z-50 w-64 bg-white border-r overflow-y-auto lg:hidden" style="border-color: var(--color-mist);" x-cloak>
        @include('layouts.partials.sidebar-nav')
    </div>

    {{-- ═══ Main Layout (Sidebar + Content) ═══ --}}
    <div class="flex pt-14 min-h-screen">
        {{-- Desktop Sidebar --}}
        <aside x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="-translate-x-full opacity-0" class="sidebar hidden lg:block w-60 bg-white border-r fixed top-14 bottom-0 overflow-y-auto" style="border-color: var(--color-mist);">
            @include('layouts.partials.sidebar-nav')
        </aside>

        {{-- Content Area --}}
        <main class="flex-1 transition-all duration-200" :class="sidebarOpen ? 'lg:ml-60' : 'lg:ml-0'">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">


                @yield('content')
            </div>

            {{-- Footer --}}
            <footer class="border-t py-4 px-6 text-center" style="border-color: var(--color-mist);">
                <p class="text-xs" style="color: var(--color-stone);">
                    &copy; {{ date('Y') }} Zona Adventure Cianjur - Sistem POS Rental
                </p>
            </footer>
        </main>
    </div>

    {{-- Modals and Toasts --}}
    <x-toast />
    <x-confirm-modal />

    @stack('scripts')
</body>
</html>
