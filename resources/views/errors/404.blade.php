@extends('layouts.auth')

@section('title', '404 - Halaman Tidak Ditemukan')

@section('content')
<div class="text-center py-8">
    <h1 class="text-6xl font-bold mb-4" style="color: var(--color-forest);">404</h1>
    <h2 class="text-xl font-semibold mb-2" style="color: var(--color-black);">Halaman Tidak Ditemukan</h2>
    <p class="text-sm mb-6" style="color: var(--color-stone);">Maaf, halaman yang Anda cari tidak ada atau telah dipindahkan.</p>
    
    <x-button href="{{ route('dashboard') }}" class="w-full justify-center">
        Kembali ke Dashboard
    </x-button>
</div>
@endsection
