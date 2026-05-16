@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <h2 class="text-xl font-semibold text-center mb-6" style="color: var(--color-forest);">Masuk ke Akun Anda</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <x-input
            name="email"
            type="email"
            label="Email"
            placeholder="nama@zonaadventure.id"
            :required="true"
            autocomplete="email"
            autofocus
        />

        <x-input
            name="password"
            type="password"
            label="Password"
            placeholder="Masukkan password"
            :required="true"
            autocomplete="current-password"
        />

        <div class="mt-6">
            <x-button type="submit" variant="primary" class="w-full justify-center" style="min-height: 44px; font-size: 15px;">
                Masuk
            </x-button>
        </div>
    </form>

    @if($errors->any() && !$errors->has('email') && !$errors->has('password'))
        <div class="mt-4 p-3 rounded-lg text-sm text-center" style="background-color: rgba(237, 179, 38, 0.1); color: var(--color-warning);">
            {{ $errors->first() }}
        </div>
    @endif
@endsection
