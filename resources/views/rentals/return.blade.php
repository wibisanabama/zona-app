@extends('layouts.app')

@section('title', 'Pengembalian ' . $rental->code)

@section('content')
    <x-page-header title="Pengembalian Barang" subtitle="Proses pengembalian sewa {{ $rental->code }}">
        <x-slot:actions>
            <x-button href="{{ route('rentals.show', $rental) }}" variant="secondary">Kembali</x-button>
        </x-slot:actions>
    </x-page-header>

    @if($rental->is_overdue)
        <div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium flex items-center gap-2" style="background-color: rgba(237,179,38,0.15); color: var(--color-warning);">
            ⚠️ Sewa ini sudah melewati jatuh tempo ({{ $rental->due_date->format('d/m/Y') }}). Denda keterlambatan akan dihitung otomatis.
        </div>
    @endif

    <x-card class="max-w-3xl">
        <div class="mb-4 p-3 rounded-lg text-sm" style="background-color: var(--color-ash);">
            <span class="font-medium">{{ $rental->customer->name }}</span> - {{ $rental->customer->phone }}
        </div>

        <form method="POST" action="{{ route('rentals.process-return', $rental) }}">
            @csrf

            <table class="w-full text-sm mb-6">
                <thead>
                    <tr class="border-b" style="border-color: var(--color-dove);">
                        <th class="text-left py-2 text-xs font-medium" style="color: var(--color-stone);">Barang</th>
                        <th class="text-center py-2 text-xs font-medium" style="color: var(--color-stone);">Disewa</th>
                        <th class="text-center py-2 text-xs font-medium" style="color: var(--color-stone);">Dikembalikan</th>
                        <th class="text-center py-2 text-xs font-medium" style="color: var(--color-stone);">Kondisi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rental->rentalItems as $idx => $ri)
                        <tr class="border-b" style="border-color: var(--color-mist);">
                            <input type="hidden" name="items[{{ $idx }}][rental_item_id]" value="{{ $ri->id }}">
                            <td class="py-3">
                                <p class="font-medium">{{ $ri->item->name }}</p>
                                <p class="text-xs" style="color: var(--color-stone);">{{ $ri->item->sku }}</p>
                            </td>
                            <td class="text-center">{{ $ri->quantity }}</td>
                            <td class="text-center">
                                <input type="number" name="items[{{ $idx }}][returned_quantity]" value="{{ $ri->quantity }}" min="0" max="{{ $ri->quantity }}" class="input-base !w-20 !h-8 !text-sm text-center mx-auto">
                            </td>
                            <td class="text-center">
                                <select name="items[{{ $idx }}][condition_on_return]" class="select-base !h-8 !text-xs !min-w-[140px]">
                                    <option value="baik">Baik</option>
                                    <option value="perlu_perbaikan">Perlu Perbaikan</option>
                                    <option value="rusak">Rusak</option>
                                </select>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="flex items-center gap-3">
                <x-button type="submit">Proses Pengembalian</x-button>
                <x-button href="{{ route('rentals.show', $rental) }}" variant="secondary">Batal</x-button>
            </div>
        </form>
    </x-card>
@endsection
