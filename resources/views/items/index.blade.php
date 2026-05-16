@extends('layouts.app')

@section('title', 'Barang')

@section('content')
    <x-page-header title="Daftar Barang" subtitle="Kelola inventory barang rental">
        <x-slot:actions>
            <x-button href="{{ route('items.create') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Barang
            </x-button>
        </x-slot:actions>
    </x-page-header>

    {{-- Filters --}}
    <div class="mb-6" x-data="{ showFilter: {{ request()->hasAny(['search','category','availability']) ? 'true' : 'false' }} }">
        <form method="GET" action="{{ route('items.index') }}">
            <div class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[200px] max-w-sm">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau SKU..." class="input-base">
                </div>
                <div class="min-w-[180px]">
                    <select name="category" class="select-base">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="min-w-[160px]">
                    <select name="availability" class="select-base">
                        <option value="">Semua Status</option>
                        <option value="available" @selected(request('availability') === 'available')>Tersedia</option>
                        <option value="unavailable" @selected(request('availability') === 'unavailable')>Tidak Tersedia</option>
                    </select>
                </div>
                <x-button type="submit" variant="secondary">Cari</x-button>
                @if(request()->hasAny(['search','category','availability']))
                    <a href="{{ route('items.index') }}" class="text-sm underline" style="color: var(--color-stone);">Reset</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Items Grid --}}
    @if($items->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($items as $item)
                <div class="card-base !p-0 overflow-hidden group">
                    {{-- Photo --}}
                    <div class="aspect-[4/3] overflow-hidden" style="background-color: var(--color-ash);">
                        @if($item->photo_url)
                            <img src="{{ $item->photo_url }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-dove);"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div>
                                <p class="text-xs" style="color: var(--color-stone);">{{ $item->sku }}</p>
                                <h3 class="font-medium text-sm leading-tight">{{ $item->name }}</h3>
                            </div>
                            @if($item->stock_available > 0 && $item->is_active)
                                <x-badge variant="success">{{ $item->stock_available }}</x-badge>
                            @else
                                <x-badge variant="warning">0</x-badge>
                            @endif
                        </div>

                        <p class="text-xs mb-1" style="color: var(--color-stone);">{{ $item->category->name ?? '-' }}</p>
                        <p class="font-semibold text-sm" style="color: var(--color-forest);">{{ $item->formatted_daily_rate }}<span class="font-normal text-xs" style="color: var(--color-stone);">/hari</span></p>

                        <div class="flex items-center gap-2 mt-3 pt-3 border-t" style="border-color: var(--color-mist);">
                            <a href="{{ route('items.show', $item) }}" class="btn-secondary !min-h-[30px] !text-xs !py-1 !px-3 flex-1 text-center">Detail</a>
                            <a href="{{ route('items.edit', $item) }}" class="btn-icon !w-[30px] !h-[30px]" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $items->links() }}
        </div>
    @else
        <x-empty-state 
            title="Belum Ada Barang" 
            message="Silakan tambah barang baru untuk disewakan."
            action-label="+ Tambah Barang"
            :action-url="route('items.create')" />
    @endif
@endsection
