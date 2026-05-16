@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
    <x-page-header title="Tambah Barang" subtitle="Daftarkan barang baru ke inventory">
        <x-slot:actions>
            <x-button href="{{ route('items.index') }}" variant="secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <x-card class="max-w-3xl">
        <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                <x-select name="category_id" label="Kategori" :options="$categories->pluck('name', 'id')->toArray()" :required="true" />
                <x-input name="sku" label="Kode SKU" placeholder="Contoh: TND-001" :required="true" />
            </div>

            <x-input name="name" label="Nama Barang" placeholder="Contoh: Tenda Great Outdoor 4P" :required="true" />

            <x-input name="description" type="textarea" label="Deskripsi" placeholder="Deskripsi barang..." />

            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6">
                <x-input name="daily_rate" type="number" label="Tarif Sewa/Hari (Rp)" placeholder="50000" :required="true" />
                <x-input name="deposit_amount" type="number" label="Deposit (Rp)" placeholder="100000" :required="true" />
                <x-input name="stock_total" type="number" label="Stok Total" placeholder="5" :required="true" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                <x-select name="condition" label="Kondisi" :options="['baik' => 'Baik', 'perlu_perbaikan' => 'Perlu Perbaikan', 'rusak' => 'Rusak']" value="baik" :required="true" />

                <div class="mb-4">
                    <label for="photo" class="block text-sm font-medium mb-1.5" style="color: var(--color-forest);">Foto Barang</label>
                    <input type="file" id="photo" name="photo" accept="image/*" class="input-base !py-1.5" style="height: auto;">
                    <p class="mt-1 text-xs" style="color: var(--color-stone);">Format: JPG, PNG. Maks 2MB.</p>
                    @error('photo')
                        <p class="mt-1 text-xs" style="color: var(--color-danger);">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center gap-3 mt-6">
                <x-button type="submit">Simpan Barang</x-button>
                <x-button href="{{ route('items.index') }}" variant="secondary">Batal</x-button>
            </div>
        </form>
    </x-card>
@endsection
