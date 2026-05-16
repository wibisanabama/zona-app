@extends('layouts.app')

@section('title', 'Tambah Pelanggan')

@section('content')
    <x-page-header title="Tambah Pelanggan" subtitle="Daftarkan pelanggan baru">
        <x-slot:actions>
            <x-button href="{{ route('customers.index') }}" variant="secondary">Kembali</x-button>
        </x-slot:actions>
    </x-page-header>

    <x-card class="max-w-2xl">
        <form method="POST" action="{{ route('customers.store') }}">
            @csrf
            <x-input name="name" label="Nama Lengkap" placeholder="Nama lengkap pelanggan" :required="true" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                <x-input name="phone" label="No. Telepon" placeholder="08xxxxxxxxxx" :required="true" />
                <x-input name="email" type="email" label="Email" placeholder="email@contoh.com" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                <x-select name="identity_type" label="Jenis Identitas" :options="['ktp' => 'KTP', 'sim' => 'SIM', 'passport' => 'Passport']" :required="true" />
                <x-input name="identity_number" label="No. Identitas" placeholder="Nomor KTP/SIM/Passport" :required="true" />
            </div>

            <x-input name="address" type="textarea" label="Alamat" placeholder="Alamat lengkap" :required="true" />
            <x-input name="notes" type="textarea" label="Catatan" placeholder="Catatan tambahan (opsional)" />

            <div class="flex items-center gap-3 mt-6">
                <x-button type="submit">Simpan Pelanggan</x-button>
                <x-button href="{{ route('customers.index') }}" variant="secondary">Batal</x-button>
            </div>
        </form>
    </x-card>
@endsection
