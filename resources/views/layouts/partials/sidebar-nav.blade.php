<nav class="p-4 space-y-1">
    {{-- Dashboard --}}
    <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        <span>Dashboard</span>
    </a>

    {{-- POS --}}
    <a href="{{ route('pos.create') }}" class="sidebar-link {{ request()->routeIs('pos.*') ? 'active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        <span>POS Rental</span>
    </a>

    {{-- Sewa Aktif --}}
    @if(Route::has('rentals.index'))
    <a href="{{ route('rentals.index') }}" class="sidebar-link {{ request()->routeIs('rentals.*') ? 'active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        <span>Sewa Aktif</span>
    </a>
    @endif

    @if(Auth::user() && Auth::user()->isAdmin())
    <div class="py-2">
        <div class="border-t" style="border-color: var(--color-mist);"></div>
        <p class="px-4 pt-3 pb-1 text-xs font-medium uppercase tracking-wider" style="color: var(--color-stone);">Master Data</p>
    </div>

    {{-- Barang --}}
    <a href="{{ route('items.index') }}" class="sidebar-link {{ request()->routeIs('items.*') ? 'active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
        <span>Barang</span>
    </a>

    {{-- Kategori --}}
    <a href="{{ route('categories.index') }}" class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
        <span>Kategori</span>
    </a>
    @endif

    {{-- Pelanggan --}}
    <a href="{{ route('customers.index') }}" class="sidebar-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        <span>Pelanggan</span>
    </a>

    @if(Auth::user() && Auth::user()->isAdmin())
    <div class="py-2">
        <div class="border-t" style="border-color: var(--color-mist);"></div>
        <p class="px-4 pt-3 pb-1 text-xs font-medium uppercase tracking-wider" style="color: var(--color-stone);">Laporan</p>
    </div>

    {{-- Laporan --}}
    @if(Route::has('reports.sales-daily'))
    <a href="{{ route('reports.sales-daily') }}" class="sidebar-link {{ request()->routeIs('reports.sales-daily') ? 'active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
        <span>Penjualan Harian</span>
    </a>

    <a href="{{ route('reports.sales-monthly') }}" class="sidebar-link {{ request()->routeIs('reports.sales-monthly') ? 'active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        <span>Penjualan Bulanan</span>
    </a>

    <a href="{{ route('reports.item-utilization') }}" class="sidebar-link {{ request()->routeIs('reports.item-utilization') ? 'active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
        <span>Utilisasi Barang</span>
    </a>

    <a href="{{ route('reports.overdue') }}" class="sidebar-link {{ request()->routeIs('reports.overdue') ? 'active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <span>Sewa Terlambat</span>
    </a>
    @endif
    @endif
</nav>
