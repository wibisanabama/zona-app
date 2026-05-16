@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
    <x-page-header title="Edit Barang" subtitle="Perbarui informasi {{ $item->name }}">
        <x-slot:actions>
            <x-button href="{{ route('items.index') }}" variant="secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <x-card class="max-w-3xl">
        <form method="POST" action="{{ route('items.update', $item) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                <x-select name="category_id" label="Kategori" :options="$categories->pluck('name', 'id')->toArray()" :value="$item->category_id" :required="true" />
                <x-input name="sku" label="Kode SKU" :value="$item->sku" :required="true" />
            </div>

            <x-input name="name" label="Nama Barang" :value="$item->name" :required="true" />

            <x-input name="description" type="textarea" label="Deskripsi" :value="$item->description" />

            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6">
                <x-input name="daily_rate" type="number" label="Tarif Sewa/Hari (Rp)" :value="$item->daily_rate" :required="true" />
                <x-input name="deposit_amount" type="number" label="Deposit (Rp)" :value="$item->deposit_amount" :required="true" />
                <x-input name="stock_total" type="number" label="Stok Total" :value="$item->stock_total" :required="true" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                <x-select name="condition" label="Kondisi" :options="['baik' => 'Baik', 'perlu_perbaikan' => 'Perlu Perbaikan', 'rusak' => 'Rusak']" :value="$item->condition" :required="true" />

                <div class="mb-4">
                    <label for="photo" class="block text-sm font-medium mb-1.5" style="color: var(--color-forest);">Foto Barang</label>
                    @if($item->photo_url)
                        <div class="mb-2">
                            <img src="{{ $item->photo_url }}" alt="{{ $item->name }}" class="w-24 h-24 object-cover rounded-lg border" style="border-color: var(--color-dove);">
                        </div>
                    @endif
                    <input type="file" id="photo" name="photo" accept="image/*" class="input-base !py-1.5" style="height: auto;">
                    <p class="mt-1 text-xs" style="color: var(--color-stone);">Kosongkan jika tidak ingin mengganti foto.</p>
                    @error('photo')
                        <p class="mt-1 text-xs" style="color: var(--color-danger);">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked($item->is_active) class="w-5 h-5 rounded" style="accent-color: var(--color-forest);">
                    <span class="text-sm">Barang aktif (tersedia untuk disewa)</span>
                </label>
            </div>

            <div class="flex items-center gap-3 mt-6">
                <x-button type="submit">Perbarui Barang</x-button>
                <x-button href="{{ route('items.index') }}" variant="secondary">Batal</x-button>
            </div>
        </form>
    </x-card>
@endsection
