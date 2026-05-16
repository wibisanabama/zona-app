@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <x-page-header title="Dashboard" subtitle="Selamat datang, {{ Auth::user()->name }}!" />

    {{-- KPI Cards Placeholder --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-card variant="kpi">
            <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color: var(--color-stone);">Pendapatan Hari Ini</p>
            <p class="text-2xl font-bold" style="color: var(--color-forest);">Rp 0</p>
        </x-card>

        <x-card variant="kpi">
            <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color: var(--color-stone);">Sewa Aktif</p>
            <p class="text-2xl font-bold" style="color: var(--color-forest);">0</p>
        </x-card>

        <x-card variant="kpi">
            <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color: var(--color-stone);">Sewa Terlambat</p>
            <p class="text-2xl font-bold" style="color: var(--color-warning);">0</p>
        </x-card>

        <x-card variant="kpi">
            <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color: var(--color-stone);">Total Pelanggan</p>
            <p class="text-2xl font-bold" style="color: var(--color-forest);">0</p>
        </x-card>
    </div>

    {{-- Bottom panels --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-card>
            <h3 class="text-base font-semibold mb-4" style="color: var(--color-forest);">Barang Terlaris Bulan Ini</h3>
            <p class="text-sm" style="color: var(--color-stone);">Belum ada data sewa.</p>
        </x-card>

        <x-card>
            <h3 class="text-base font-semibold mb-4" style="color: var(--color-forest);">Sewa Terbaru</h3>
            <p class="text-sm" style="color: var(--color-stone);">Belum ada data sewa.</p>
        </x-card>
    </div>
@endsection
