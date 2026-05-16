@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
    <x-page-header title="Edit Kategori" subtitle="Perbarui informasi kategori {{ $category->name }}">
        <x-slot:actions>
            <x-button href="{{ route('categories.index') }}" variant="secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali
            </x-button>
        </x-slot:actions>
    </x-page-header>

    <x-card class="max-w-2xl">
        <form method="POST" action="{{ route('categories.update', $category) }}">
            @csrf
            @method('PUT')

            <x-input name="name" label="Nama Kategori" :value="$category->name" :required="true" />

            <x-input name="icon" label="Icon (emoji)" :value="$category->icon" placeholder="Contoh: ⛺" hint="Gunakan emoji sebagai ikon kategori" />

            <x-input name="description" type="textarea" label="Deskripsi" :value="$category->description" placeholder="Deskripsi singkat kategori..." />

            <div class="flex items-center gap-3 mt-6">
                <x-button type="submit">Perbarui Kategori</x-button>
                <x-button href="{{ route('categories.index') }}" variant="secondary">Batal</x-button>
            </div>
        </form>
    </x-card>
@endsection
