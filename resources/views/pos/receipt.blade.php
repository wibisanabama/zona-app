@extends('layouts.app')

@section('title', 'Struk Sewa ' . $rental->code)

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-4 no-print">
            <x-button href="{{ route('pos.create') }}" variant="secondary">Transaksi Baru</x-button>
            <x-button onclick="window.print()" variant="primary">Cetak Struk</x-button>
        </div>

        <div class="card-base" id="receipt">
            {{-- Header --}}
            <div class="text-center border-b pb-4 mb-4" style="border-color: var(--color-mist);">
                <h2 class="text-lg font-bold" style="color: var(--color-forest);">🏕️ Zona Adventure Cianjur</h2>
                <p class="text-xs" style="color: var(--color-stone);">Rental Alat Camping & Outdoor</p>
                <p class="text-xs mt-1" style="color: var(--color-stone);">Jl. Raya Cianjur - Telp: 0263-XXXXXXX</p>
            </div>

            {{-- Info --}}
            <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                <div>
                    <p class="text-xs" style="color: var(--color-stone);">No. Transaksi</p>
                    <p class="font-semibold">{{ $rental->code }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs" style="color: var(--color-stone);">Tanggal</p>
                    <p class="font-semibold">{{ $rental->rental_date->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-xs" style="color: var(--color-stone);">Pelanggan</p>
                    <p class="font-medium">{{ $rental->customer->name }}</p>
                    <p class="text-xs" style="color: var(--color-stone);">{{ $rental->customer->phone }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs" style="color: var(--color-stone);">Durasi / Kembali</p>
                    <p class="font-medium">{{ $rental->days }} hari</p>
                    <p class="text-xs" style="color: var(--color-stone);">Maks: {{ $rental->due_date->format('d/m/Y') }}</p>
                </div>
            </div>

            {{-- Items --}}
            <table class="w-full text-sm mb-4">
                <thead>
                    <tr class="border-b" style="border-color: var(--color-dove);">
                        <th class="text-left py-2 text-xs font-medium" style="color: var(--color-stone);">Barang</th>
                        <th class="text-center py-2 text-xs font-medium" style="color: var(--color-stone);">Qty</th>
                        <th class="text-right py-2 text-xs font-medium" style="color: var(--color-stone);">Tarif/Hari</th>
                        <th class="text-right py-2 text-xs font-medium" style="color: var(--color-stone);">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rental->rentalItems as $ri)
                        <tr class="border-b" style="border-color: var(--color-mist);">
                            <td class="py-2">
                                <p class="font-medium">{{ $ri->item->name }}</p>
                                <p class="text-xs" style="color: var(--color-stone);">{{ $ri->item->sku }}</p>
                            </td>
                            <td class="text-center py-2">{{ $ri->quantity }}</td>
                            <td class="text-right py-2">Rp {{ number_format($ri->daily_rate, 0, ',', '.') }}</td>
                            <td class="text-right py-2 font-medium">Rp {{ number_format($ri->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Totals --}}
            <div class="space-y-1 text-sm border-t pt-3" style="border-color: var(--color-dove);">
                <div class="flex justify-between">
                    <span style="color: var(--color-stone);">Subtotal ({{ $rental->days }} hari)</span>
                    <span>Rp {{ number_format($rental->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($rental->discount > 0)
                    <div class="flex justify-between">
                        <span style="color: var(--color-stone);">Diskon</span>
                        <span>- Rp {{ number_format($rental->discount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex justify-between font-bold text-base pt-1 border-t" style="border-color: var(--color-mist);">
                    <span>Total Sewa</span>
                    <span style="color: var(--color-forest);">Rp {{ number_format($rental->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-xs" style="color: var(--color-stone);">
                    <span>Total Deposit</span>
                    <span>Rp {{ number_format($rental->total_deposit, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Footer --}}
            <div class="text-center mt-6 pt-4 border-t" style="border-color: var(--color-mist);">
                <p class="text-xs" style="color: var(--color-stone);">Kasir: {{ $rental->cashier->name }}</p>
                <p class="text-xs mt-1" style="color: var(--color-stone);">Terima kasih telah mempercayakan peralatan camping Anda kepada Zona Adventure!</p>
                <p class="text-xs mt-1" style="color: var(--color-stone);">Harap kembalikan barang tepat waktu untuk menghindari denda keterlambatan.</p>
            </div>
        </div>
    </div>
@endsection
