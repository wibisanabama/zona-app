@extends('layouts.auth')

@section('title', '403 - Akses Ditolak')

@section('content')
<div class="text-center py-8">
    <h1 class="text-6xl font-bold mb-4" style="color: var(--color-warning);">403</h1>
    <h2 class="text-xl font-semibold mb-2" style="color: var(--color-black);">Akses Ditolak</h2>
    <p class="text-sm mb-6" style="color: var(--color-stone);">Maaf, Anda tidak memiliki izin (hak akses) untuk melihat halaman ini.</p>
    
    <x-button href="{{ route('dashboard') }}" class="w-full justify-center">
        Kembali ke Dashboard
    </x-button>
</div>
@endsection
