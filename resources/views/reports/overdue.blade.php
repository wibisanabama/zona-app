@extends('layouts.app')

@section('title', 'Laporan Sewa Terlambat')

@section('content')
    <x-page-header title="Laporan Sewa Terlambat" subtitle="Per Tanggal: {{ $today->translatedFormat('d F Y') }}">
        <x-slot:actions>
            <div class="flex items-center gap-2">
                <x-button onclick="window.print()" variant="secondary">Cetak</x-button>
                <a href="{{ route('reports.overdue.csv') }}">
                    <x-button>Export CSV</x-button>
                </a>
            </div>
        </x-slot:actions>
    </x-page-header>

    <div class="mb-6">
        <x-card variant="kpi">
            <p class="text-xs font-medium uppercase tracking-wider mb-1" style="color: var(--color-stone);">Total Transaksi Terlambat</p>
            <p class="text-2xl font-bold text-red-600">{{ number_format($overdueRentals->count()) }}</p>
        </x-card>
    </div>

    <x-card>
        <x-data-table :headers="['No. Invoice', 'Pelanggan', 'Tanggal Sewa', 'Tenggat Waktu', 'Keterlambatan (Hari)', 'Total Tagihan (Rp)']">
            @foreach($overdueRentals as $rental)
                @php
                    $daysLate = (int) Carbon\Carbon::parse($rental->due_date)->diffInDays($today, true);
                @endphp
                <tr>
                    <td class="font-medium">
                        <a href="{{ route('rentals.show', $rental) }}" class="text-indigo-600 hover:text-indigo-900 hover:underline">
                            {{ $rental->invoice_number }}
                        </a>
                    </td>
                    <td>{{ $rental->customer->name }}</td>
                    <td>{{ Carbon\Carbon::parse($rental->rental_date)->format('d/m/Y') }}</td>
                    <td class="text-red-600 font-medium">{{ Carbon\Carbon::parse($rental->due_date)->format('d/m/Y') }}</td>
                    <td class="text-center font-bold text-red-600">{{ $daysLate }} Hari</td>
                    <td class="text-right font-medium">
                        {{ number_format($rental->total_amount, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
            @if($overdueRentals->isEmpty())
                <tr>
                    <td colspan="6" class="text-center py-8 text-stone">
                        Tidak ada penyewaan yang terlambat saat ini.
                    </td>
                </tr>
            @endif
        </x-data-table>
    </x-card>
@endsection
