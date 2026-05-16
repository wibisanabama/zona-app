@extends('layouts.app')

@section('title', 'Sewa Aktif')

@section('content')
    <x-page-header title="Daftar Sewa" subtitle="Kelola transaksi sewa aktif dan riwayat" />

    <div class="mb-6">
        <form method="GET" action="{{ route('rentals.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px] max-w-sm">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama pelanggan..." class="input-base">
            </div>
            <div class="min-w-[160px]">
                <select name="status" class="select-base">
                    <option value="">Semua Status</option>
                    <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
                    <option value="terlambat" @selected(request('status') === 'terlambat')>Terlambat</option>
                    <option value="selesai" @selected(request('status') === 'selesai')>Selesai</option>
                    <option value="batal" @selected(request('status') === 'batal')>Batal</option>
                </select>
            </div>
            <x-button type="submit" variant="secondary">Cari</x-button>
            @if(request()->hasAny(['search','status']))
                <a href="{{ route('rentals.index') }}" class="text-sm underline" style="color: var(--color-stone);">Reset</a>
            @endif
        </form>
    </div>

    @if($rentals->count() > 0)
        <x-data-table :headers="['Kode', 'Pelanggan', 'Tanggal Sewa', 'Jatuh Tempo', 'Total', 'Status', 'Aksi']">
            @foreach($rentals as $rental)
                @php $effStatus = $rental->effective_status; @endphp
                <tr>
                    <td class="font-mono font-medium text-sm">{{ $rental->code }}</td>
                    <td>{{ $rental->customer->name ?? '-' }}</td>
                    <td>{{ $rental->rental_date->format('d/m/Y') }}</td>
                    <td>{{ $rental->due_date->format('d/m/Y') }}</td>
                    <td class="font-medium">{{ $rental->formatted_total }}</td>
                    <td>
                        @php
                            $variant = match($effStatus) {
                                'aktif' => 'success',
                                'terlambat' => 'warning',
                                'selesai' => 'neutral',
                                'batal' => 'danger',
                                default => 'neutral',
                            };
                        @endphp
                        <x-badge :variant="$variant">{{ ucfirst($effStatus) }}</x-badge>
                    </td>
                    <td>
                        <div class="flex items-center gap-1">
                            <a href="{{ route('rentals.show', $rental) }}" class="btn-icon" title="Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </a>
                            @if($rental->status === 'aktif')
                                <a href="{{ route('rentals.return', $rental) }}" class="btn-icon" title="Kembalikan" style="color: var(--color-lime-electric);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-data-table>
        <div class="mt-4">{{ $rentals->links() }}</div>
    @else
        <x-card>
            <div class="py-12 text-center">
                <p class="text-sm mb-3" style="color: var(--color-stone);">Belum ada transaksi sewa.</p>
                <x-button href="{{ route('pos.create') }}">Buat Transaksi Baru</x-button>
            </div>
        </x-card>
    @endif
@endsection
