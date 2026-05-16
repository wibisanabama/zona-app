@extends('layouts.app')

@section('title', 'Laporan Utilisasi Barang')

@section('content')
    <x-page-header title="Laporan Utilisasi Barang" subtitle="Bulan: {{ Carbon\Carbon::parse($month . '-01')->translatedFormat('F Y') }}">
        <x-slot:actions>
            <div class="flex items-center gap-2">
                <form method="GET" action="{{ route('reports.item-utilization') }}" class="flex items-center">
                    <input type="month" name="month" value="{{ $month }}" class="input-base !h-10 !py-1" onchange="this.form.submit()">
                </form>
                <x-button onclick="window.print()" variant="secondary">Cetak</x-button>
                <a href="{{ route('reports.item-utilization.csv', ['month' => $month]) }}">
                    <x-button>Export CSV</x-button>
                </a>
            </div>
        </x-slot:actions>
    </x-page-header>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
        <x-card variant="kpi">
            <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color: var(--color-stone);">Total Barang Disewa</p>
            <p class="text-2xl font-bold" style="color: var(--color-black);">{{ number_format($totalQuantity) }}</p>
        </x-card>
        <x-card variant="kpi">
            <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color: var(--color-stone);">Total Pendapatan Sewa</p>
            <p class="text-2xl font-bold" style="color: var(--color-forest);">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </x-card>
    </div>

    <x-card>
        <x-data-table :headers="['SKU', 'Nama Barang', 'Total Disewa', 'Total Pendapatan (Rp)']">
            @foreach($reportData as $row)
                <tr>
                    <td class="font-medium" style="color: var(--color-night);">{{ $row->sku }}</td>
                    <td>{{ $row->name }}</td>
                    <td class="text-center">{{ number_format($row->total_quantity) }}</td>
                    <td class="text-right text-forest font-medium">
                        {{ number_format($row->total_revenue, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
            @if($reportData->isEmpty())
                <tr>
                    <td colspan="4" class="text-center py-8 text-stone">
                        Tidak ada data utilisasi barang untuk bulan ini.
                    </td>
                </tr>
            @endif
            <x-slot:footer>
                <tr class="font-bold border-t-2" style="border-color: var(--color-dove);">
                    <td colspan="2" class="text-right py-3">Total:</td>
                    <td class="text-center py-3">{{ number_format($totalQuantity) }}</td>
                    <td class="text-right py-3" style="color: var(--color-forest);">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                </tr>
            </x-slot:footer>
        </x-data-table>
    </x-card>
@endsection
