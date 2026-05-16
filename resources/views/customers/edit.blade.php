@extends('layouts.app')

@section('title', 'Edit Pelanggan')

@section('content')
    <x-page-header title="Edit Pelanggan" subtitle="Perbarui data {{ $customer->name }}">
        <x-slot:actions>
            <x-button href="{{ route('customers.index') }}" variant="secondary">Kembali</x-button>
        </x-slot:actions>
    </x-page-header>

    <x-card class="max-w-2xl">
        <form method="POST" action="{{ route('customers.update', $customer) }}">
            @csrf
            @method('PUT')
            <x-input name="name" label="Nama Lengkap" :value="$customer->name" :required="true" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                <x-input name="phone" label="No. Telepon" :value="$customer->phone" :required="true" />
                <x-input name="email" type="email" label="Email" :value="$customer->email" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                <x-select name="identity_type" label="Jenis Identitas" :options="['ktp' => 'KTP', 'sim' => 'SIM', 'passport' => 'Passport']" :value="$customer->identity_type" :required="true" />
                <x-input name="identity_number" label="No. Identitas" :value="$customer->identity_number" :required="true" />
            </div>

            <x-input name="address" type="textarea" label="Alamat" :value="$customer->address" :required="true" />
            <x-input name="notes" type="textarea" label="Catatan" :value="$customer->notes" />

            <div class="mb-4">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="hidden" name="blacklisted" value="0">
                    <input type="checkbox" name="blacklisted" value="1" @checked($customer->blacklisted) class="w-5 h-5 rounded" style="accent-color: var(--color-danger);">
                    <span class="text-sm">Blacklist pelanggan ini</span>
                </label>
            </div>

            <div class="flex items-center gap-3 mt-6">
                <x-button type="submit">Perbarui</x-button>
                <x-button href="{{ route('customers.index') }}" variant="secondary">Batal</x-button>
            </div>
        </form>
    </x-card>
@endsection
