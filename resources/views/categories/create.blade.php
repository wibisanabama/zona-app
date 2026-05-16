@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
    <x-page-header title="Tambah Kategori" subtitle="Buat kategori baru untuk mengelompokkan barang rental">
        <x-slot:actions>
            <x-button href="{{ route('categories.index') }}" variant="secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <x-card class="max-w-2xl">
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf

            <x-input name="name" label="Nama Kategori" placeholder="Contoh: Tenda" :required="true" />

            <x-input name="icon" label="Icon (emoji)" placeholder="Contoh: ⛺" hint="Gunakan emoji sebagai ikon kategori" />

            <x-input name="description" type="textarea" label="Deskripsi" placeholder="Deskripsi singkat kategori..." />

            <div class="flex items-center gap-3 mt-6">
                <x-button type="submit">Simpan Kategori</x-button>
                <x-button href="{{ route('categories.index') }}" variant="secondary">Batal</x-button>
            </div>
        </form>
    </x-card>
@endsection
