@extends('layouts.app')

@section('title', 'Kategori Barang')

@section('content')
    <x-page-header title="Kategori Barang" subtitle="Kelola kategori untuk mengelompokkan barang rental">
        <x-slot:actions>
            <x-button href="{{ route('categories.create') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Kategori
            </x-button>
        </x-slot:actions>
    </x-page-header>

    {{-- Search --}}
    <div class="mb-6">
        <form method="GET" action="{{ route('categories.index') }}" class="flex gap-3 items-end">
            <div class="flex-1 max-w-sm">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori..." class="input-base">
            </div>
            <x-button type="submit" variant="secondary">Cari</x-button>
            @if(request('search'))
                <a href="{{ route('categories.index') }}" class="text-sm underline" style="color: var(--color-stone);">Reset</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    @if($categories->count() > 0)
        <x-data-table :headers="['Nama', 'Deskripsi', 'Jumlah Barang', 'Aksi']">
            @foreach($categories as $category)
                <tr>
                    <td>
                        <div class="flex items-center gap-2">
                            @if($category->icon)
                                <span class="text-lg">{{ $category->icon }}</span>
                            @endif
                            <span class="font-medium">{{ $category->name }}</span>
                        </div>
                    </td>
                    <td>
                        <span style="color: var(--color-stone);">{{ $category->description ?? '—' }}</span>
                    </td>
                    <td>
                        <x-badge variant="{{ $category->items_count > 0 ? 'success' : 'neutral' }}">
                            {{ $category->items_count }} barang
                        </x-badge>
                    </td>
                    <td>
                        <div class="flex items-center gap-1">
                            <a href="{{ route('categories.edit', $category) }}" class="btn-icon" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('categories.destroy', $category) }}" x-data x-on:submit.prevent="if(confirm('Hapus kategori {{ $category->name }}?')) $el.submit()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon" style="color: var(--color-danger);" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-data-table>

        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    @else
        <x-card>
            <div class="py-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-3" style="color: var(--color-dove);"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                <p class="text-sm mb-3" style="color: var(--color-stone);">Belum ada kategori.</p>
                <x-button href="{{ route('categories.create') }}">Tambah Kategori Pertama</x-button>
            </div>
        </x-card>
    @endif
@endsection
