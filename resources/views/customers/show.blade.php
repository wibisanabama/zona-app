@extends('layouts.app')

@section('title', $customer->name)

@section('content')
    <x-page-header title="{{ $customer->name }}" subtitle="Detail pelanggan">
        <x-slot:actions>
            <x-button href="{{ route('customers.edit', $customer) }}" variant="secondary">Edit</x-button>
            <x-button href="{{ route('customers.index') }}" variant="secondary">Kembali</x-button>
        </x-slot:actions>
    </x-page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <x-card>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-lg font-bold text-white" style="background-color: var(--color-forest);">
                        {{ substr($customer->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-semibold">{{ $customer->name }}</h3>
                        @if($customer->blacklisted)
                            <x-badge variant="danger">Blacklist</x-badge>
                        @else
                            <x-badge variant="success">Aktif</x-badge>
                        @endif
                    </div>
                </div>

                <div class="space-y-3 text-sm">
                    <div><span style="color: var(--color-stone);">Telepon:</span><br>{{ $customer->phone }}</div>
                    <div><span style="color: var(--color-stone);">Email:</span><br>{{ $customer->email ?? '—' }}</div>
                    <div><span style="color: var(--color-stone);">Identitas:</span><br><span class="uppercase">{{ $customer->identity_type }}</span> - {{ $customer->identity_number }}</div>
                    <div><span style="color: var(--color-stone);">Alamat:</span><br>{{ $customer->address }}</div>
                    @if($customer->notes)
                        <div><span style="color: var(--color-stone);">Catatan:</span><br>{{ $customer->notes }}</div>
                    @endif
                </div>
            </x-card>
        </div>

        <div class="lg:col-span-2">
            <x-card>
                <h3 class="text-base font-semibold mb-4" style="color: var(--color-forest);">Riwayat Sewa</h3>
                @if($customer->rentals && $customer->rentals->count() > 0)
                    <x-data-table :headers="['Kode', 'Tanggal', 'Status', 'Total']">
                        @foreach($customer->rentals as $rental)
                            <tr>
                                <td><a href="{{ route('rentals.show', $rental) }}" class="font-medium underline" style="color: var(--color-olive);">{{ $rental->code }}</a></td>
                                <td>{{ $rental->rental_date->format('d/m/Y') }}</td>
                                <td>
                                    <x-badge variant="{{ $rental->status === 'selesai' ? 'success' : ($rental->status === 'terlambat' ? 'warning' : ($rental->status === 'batal' ? 'danger' : 'neutral')) }}">
                                        {{ ucfirst($rental->status) }}
                                    </x-badge>
                                </td>
                                <td>Rp {{ number_format($rental->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </x-data-table>
                @else
                    <p class="text-sm py-8 text-center" style="color: var(--color-stone);">Belum ada riwayat sewa.</p>
                @endif
            </x-card>
        </div>
    </div>
@endsection
