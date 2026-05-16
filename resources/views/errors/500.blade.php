@extends('layouts.auth')

@section('title', '500 - Kesalahan Server')

@section('content')
<div class="text-center py-8">
    <h1 class="text-6xl font-bold mb-4" style="color: var(--color-danger);">500</h1>
    <h2 class="text-xl font-semibold mb-2" style="color: var(--color-black);">Kesalahan Sistem</h2>
    <p class="text-sm mb-6" style="color: var(--color-stone);">Maaf, terjadi kesalahan pada server kami. Silakan coba beberapa saat lagi.</p>
    
    <x-button href="{{ route('dashboard') }}" class="w-full justify-center">
        Kembali ke Dashboard
    </x-button>
</div>
@endsection
