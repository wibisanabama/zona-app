@extends('layouts.auth')

@section('title', '403 - Akses Ditolak')

@section('content')
<div class="text-center">
    <h2 class="text-5xl font-bold mb-2" style="color: var(--color-danger);">403</h2>
    <h3 class="text-xl font-semibold mb-4" style="color: var(--color-forest);">Akses Ditolak</h3>
    <p class="text-sm mb-6" style="color: var(--color-stone);">
        Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Jika Anda merasa ini adalah kesalahan, silakan hubungi administrator.
    </p>
    <x-button href="{{ route('dashboard') }}" class="w-full justify-center flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="m15 18-6-6 6-6"/></svg>
        Kembali ke Dashboard
    </x-button>
</div>
@endsection
