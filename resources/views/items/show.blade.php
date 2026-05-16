@extends('layouts.app')

@section('title', $item->name)

@section('content')
    <x-page-header title="{{ $item->name }}" subtitle="Detail barang rental">
        <x-slot:actions>
            <x-button href="{{ route('items.edit', $item) }}" variant="secondary">Edit</x-button>
            <x-button href="{{ route('items.index') }}" variant="secondary">Kembali</x-button>
        </x-slot:actions>
    </x-page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <x-card :padding="false">
                <div class="aspect-square overflow-hidden rounded-2xl" style="background-color: var(--color-ash);">
                    @if($item->photo_url)
                        <img src="{{ $item->photo_url }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-dove);"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                        </div>
                    @endif
                </div>
            </x-card>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <x-card>
                <div class="flex items-center gap-3 mb-4">
                    <x-badge variant="{{ $item->is_active ? 'success' : 'warning' }}">{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</x-badge>
                    <x-badge variant="neutral">{{ $item->sku }}</x-badge>
                    <x-badge variant="neutral">{{ $item->category->name ?? '-' }}</x-badge>
                </div>
                <h2 class="text-xl font-semibold mb-2">{{ $item->name }}</h2>
                <p class="text-sm mb-4" style="color: var(--color-stone);">{{ $item->description ?? 'Tidak ada deskripsi.' }}</p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 py-4 border-t" style="border-color: var(--color-mist);">
                    <div>
                        <p class="text-xs uppercase tracking-wider mb-1" style="color: var(--color-stone);">Tarif/Hari</p>
                        <p class="text-lg font-bold" style="color: var(--color-forest);">{{ $item->formatted_daily_rate }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wider mb-1" style="color: var(--color-stone);">Deposit</p>
                        <p class="text-lg font-bold" style="color: var(--color-forest);">{{ $item->formatted_deposit_amount }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wider mb-1" style="color: var(--color-stone);">Stok</p>
                        <p class="text-lg font-bold"><span style="color: {{ $item->stock_available > 0 ? 'var(--color-lime-electric)' : 'var(--color-danger)' }};">{{ $item->stock_available }}</span><span class="text-sm font-normal" style="color: var(--color-stone);">/ {{ $item->stock_total }}</span></p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wider mb-1" style="color: var(--color-stone);">Kondisi</p>
                        <x-badge variant="{{ $item->condition === 'baik' ? 'success' : ($item->condition === 'perlu_perbaikan' ? 'warning' : 'danger') }}">{{ str_replace('_', ' ', ucfirst($item->condition)) }}</x-badge>
                    </div>
                </div>
            </x-card>
            <div class="flex justify-end">
                <form method="POST" action="{{ route('items.destroy', $item) }}" x-data x-on:submit.prevent="if(confirm('Hapus barang ini?')) $el.submit()">
                    @csrf
                    @method('DELETE')
                    <x-button type="submit" variant="danger">Hapus Barang</x-button>
                </form>
            </div>
        </div>
    </div>
@endsection
