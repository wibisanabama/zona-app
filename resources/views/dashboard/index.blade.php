@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <x-page-header title="Dashboard" subtitle="Selamat datang, {{ Auth::user()->name }}!" />

    {{-- KPI Cards Placeholder --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-card variant="kpi">
            <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color: var(--color-stone);">Pendapatan Hari Ini</p>
            <p class="text-2xl font-bold" style="color: var(--color-forest);">Rp {{ number_format($todayIncome, 0, ',', '.') }}</p>
        </x-card>

        <x-card variant="kpi">
            <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color: var(--color-stone);">Sewa Aktif</p>
            <p class="text-2xl font-bold" style="color: var(--color-forest);">{{ number_format($activeRentalsCount) }}</p>
        </x-card>

        <x-card variant="kpi">
            <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color: var(--color-stone);">Barang Tersedia</p>
            <p class="text-2xl font-bold" style="color: var(--color-forest);">{{ number_format($totalItems) }}</p>
        </x-card>

        <x-card variant="kpi">
            <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color: var(--color-stone);">Total Pelanggan</p>
            <p class="text-2xl font-bold" style="color: var(--color-forest);">{{ number_format($totalCustomers) }}</p>
        </x-card>
    </div>

    {{-- Bottom panels --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-card>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold" style="color: var(--color-warning);">Sewa Terlambat</h3>
                <span class="badge-warning !text-xs">{{ $overdueRentals->count() }}</span>
            </div>
            @if($overdueRentals->count() > 0)
                <div class="space-y-3">
                    @foreach($overdueRentals as $rental)
                        <div class="flex justify-between items-center p-3 rounded-lg border border-warning">
                            <div>
                                <a href="{{ route('rentals.show', $rental) }}" class="font-medium text-sm hover:underline" style="color: var(--color-black);">{{ $rental->code }}</a>
                                <p class="text-xs" style="color: var(--color-stone);">{{ $rental->customer->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-semibold text-warning">Jatuh Tempo: {{ $rental->due_date->format('d/m/Y') }}</p>
                                <x-button href="{{ route('rentals.return', $rental) }}" class="mt-1 !h-6 !text-[10px] !px-2">Proses</x-button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm py-4 text-center" style="color: var(--color-stone);">Tidak ada sewa yang terlambat saat ini.</p>
            @endif
        </x-card>

        <x-card>
            <h3 class="text-base font-semibold mb-4" style="color: var(--color-forest);">Sewa Aktif Terbaru</h3>
            @if($recentRentals->count() > 0)
                <div class="space-y-3">
                    @foreach($recentRentals as $rental)
                        <div class="flex justify-between items-center p-3 rounded-lg border" style="border-color: var(--color-mist); background-color: var(--color-ash);">
                            <div>
                                <a href="{{ route('rentals.show', $rental) }}" class="font-medium text-sm hover:underline" style="color: var(--color-black);">{{ $rental->code }}</a>
                                <p class="text-xs" style="color: var(--color-stone);">{{ $rental->customer->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-medium">{{ $rental->formatted_total }}</p>
                                <p class="text-xs" style="color: var(--color-stone);">Tgl: {{ $rental->rental_date->format('d/m') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('rentals.index') }}" class="text-xs font-medium hover:underline" style="color: var(--color-olive);">Lihat Semua Transaksi →</a>
                </div>
            @else
                <p class="text-sm py-4 text-center" style="color: var(--color-stone);">Belum ada data sewa aktif.</p>
            @endif
        </x-card>
    </div>
@endsection
