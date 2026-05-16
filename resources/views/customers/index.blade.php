@extends('layouts.app')

@section('title', 'Pelanggan')

@section('content')
    <x-page-header title="Daftar Pelanggan" subtitle="Kelola data pelanggan penyewa">
        <x-slot:actions>
            <x-button href="{{ route('customers.create') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Pelanggan
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <div class="mb-6">
        <form method="GET" action="{{ route('customers.index') }}" class="flex gap-3 items-end">
            <div class="flex-1 max-w-sm">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau telepon..." class="input-base">
            </div>
            <x-button type="submit" variant="secondary">Cari</x-button>
            @if(request('search'))
                <a href="{{ route('customers.index') }}" class="text-sm underline" style="color: var(--color-stone);">Reset</a>
            @endif
        </form>
    </div>

    @if($customers->count() > 0)
        <x-data-table :headers="['Nama', 'Telepon', 'Identitas', 'Alamat', 'Status', 'Aksi']">
            @foreach($customers as $customer)
                <tr>
                    <td class="font-medium">{{ $customer->name }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td><span class="uppercase text-xs">{{ $customer->identity_type }}</span> - {{ $customer->identity_number }}</td>
                    <td><span class="truncate block max-w-[200px]" style="color: var(--color-stone);">{{ $customer->address }}</span></td>
                    <td>
                        @if($customer->blacklisted)
                            <x-badge variant="danger">Blacklist</x-badge>
                        @else
                            <x-badge variant="success">Aktif</x-badge>
                        @endif
                    </td>
                    <td>
                        <div class="flex items-center gap-1">
                            <a href="{{ route('customers.show', $customer) }}" class="btn-icon" title="Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </a>
                            <a href="{{ route('customers.edit', $customer) }}" class="btn-icon" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-data-table>
        <div class="mt-4">{{ $customers->links() }}</div>
    @else
        <x-empty-state 
            title="Belum Ada Pelanggan" 
            message="Data pelanggan akan muncul di sini setelah Anda menambahkan pelanggan baru."
            action-label="+ Pelanggan Baru"
            :action-url="route('customers.create')" />
    @endif
@endsection
