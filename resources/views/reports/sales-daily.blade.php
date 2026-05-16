@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
    <x-page-header title="Laporan Penjualan Harian" subtitle="Bulan: {{ Carbon\Carbon::parse($month . '-01')->translatedFormat('F Y') }}">
        <x-slot:actions>
            <form method="GET" action="{{ route('reports.sales-daily') }}" class="flex items-center gap-2">
                <input type="month" name="month" value="{{ $month }}" class="input-base !h-10 !py-1" onchange="this.form.submit()">
            </form>
        </x-slot:actions>
    </x-page-header>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
        <x-card variant="kpi">
            <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color: var(--color-stone);">Total Penerimaan</p>
            <p class="text-2xl font-bold" style="color: var(--color-forest);">Rp {{ number_format($totalIncomeMonth, 0, ',', '.') }}</p>
        </x-card>
        <x-card variant="kpi">
            <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color: var(--color-stone);">Nilai Transaksi Sewa</p>
            <p class="text-2xl font-bold" style="color: var(--color-olive);">Rp {{ number_format($totalValueMonth, 0, ',', '.') }}</p>
        </x-card>
        <x-card variant="kpi">
            <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color: var(--color-stone);">Jumlah Transaksi</p>
            <p class="text-2xl font-bold" style="color: var(--color-black);">{{ number_format($totalTransactionsMonth) }}</p>
        </x-card>
    </div>

    <x-card>
        <x-data-table :headers="['Tanggal', 'Penerimaan Uang (Rp)', 'Nilai Transaksi Sewa (Rp)', 'Jml Transaksi']">
            @foreach($reportData as $row)
                <tr class="{{ $row['date']->isToday() ? 'bg-ash font-medium' : '' }}">
                    <td>{{ $row['date']->format('d/m/Y') }} <span class="text-xs text-stone ml-1">({{ $row['date']->translatedFormat('l') }})</span></td>
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
                    <td class="text-right py-3">Total Bulan Ini:</td>
                    <td class="text-right py-3" style="color: var(--color-forest);">Rp {{ number_format($totalIncomeMonth, 0, ',', '.') }}</td>
                    <td class="text-right py-3" style="color: var(--color-olive);">Rp {{ number_format($totalValueMonth, 0, ',', '.') }}</td>
                    <td class="text-center py-3">{{ number_format($totalTransactionsMonth) }}</td>
                </tr>
            </x-slot:footer>
        </x-data-table>
    </x-card>
@endsection
