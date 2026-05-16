@extends('layouts.app')

@section('title', 'Laporan Penjualan Bulanan')

@section('content')
    <x-page-header title="Laporan Penjualan Bulanan" subtitle="Tahun: {{ $year }}">
        <x-slot:actions>
            <div class="flex items-center gap-2">
                <form method="GET" action="{{ route('reports.sales-monthly') }}" class="flex items-center">
                    <input type="number" name="year" value="{{ $year }}" class="input-base !h-10 !py-1 w-24" onchange="this.form.submit()">
                </form>
                <x-button onclick="window.print()" variant="secondary">Cetak</x-button>
                <a href="{{ route('reports.sales-monthly.csv', ['year' => $year]) }}">
                    <x-button>Export CSV</x-button>
                </a>
            </div>
        </x-slot:actions>
    </x-page-header>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
        <x-card variant="kpi">
            <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color: var(--color-stone);">Total Penerimaan</p>
            <p class="text-2xl font-bold" style="color: var(--color-forest);">Rp {{ number_format($totalIncomeYear, 0, ',', '.') }}</p>
        </x-card>
        <x-card variant="kpi">
            <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color: var(--color-stone);">Nilai Transaksi Sewa</p>
            <p class="text-2xl font-bold" style="color: var(--color-olive);">Rp {{ number_format($totalValueYear, 0, ',', '.') }}</p>
        </x-card>
        <x-card variant="kpi">
            <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color: var(--color-stone);">Jumlah Transaksi</p>
            <p class="text-2xl font-bold" style="color: var(--color-black);">{{ number_format($totalTransactionsYear) }}</p>
        </x-card>
    </div>

    <x-card>
        <x-data-table :headers="['Bulan', 'Penerimaan Uang (Rp)', 'Nilai Transaksi Sewa (Rp)', 'Jml Transaksi']">
            @foreach($reportData as $row)
                <tr>
                    <td>{{ $row['month_name'] }}</td>
                    <td class="text-right {{ $row['income'] > 0 ? 'text-forest font-medium' : '' }}">
                        {{ number_format($row['income'], 0, ',', '.') }}
                    </td>
                    <td class="text-right">
                        {{ number_format($row['value'], 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        {{ $row['transactions'] }}
                    </td>
                </tr>
            @endforeach
            <x-slot:footer>
                <tr class="font-bold border-t-2" style="border-color: var(--color-dove);">
                    <td class="text-right py-3">Total Tahun Ini:</td>
                    <td class="text-right py-3" style="color: var(--color-forest);">Rp {{ number_format($totalIncomeYear, 0, ',', '.') }}</td>
                    <td class="text-right py-3" style="color: var(--color-olive);">Rp {{ number_format($totalValueYear, 0, ',', '.') }}</td>
                    <td class="text-center py-3">{{ number_format($totalTransactionsYear) }}</td>
                </tr>
            </x-slot:footer>
        </x-data-table>
    </x-card>
@endsection
