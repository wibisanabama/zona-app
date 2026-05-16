@extends('layouts.app')

@section('title', 'Detail Sewa ' . $rental->code)

@section('content')
    @php $effStatus = $rental->effective_status; @endphp

    <x-page-header title="Sewa {{ $rental->code }}" subtitle="Detail transaksi sewa">
        <x-slot:actions>
            @if($rental->status === 'aktif')
                <x-button href="{{ route('rentals.return', $rental) }}">Proses Pengembalian</x-button>
                <form method="POST" action="{{ route('rentals.cancel', $rental) }}" x-data x-on:submit.prevent="if(confirm('Batalkan sewa ini? Stok akan dikembalikan.')) $el.submit()" class="inline">
                    @csrf
                    <x-button type="submit" variant="danger">Batalkan</x-button>
                </form>
            @endif
            <x-button href="{{ route('rentals.index') }}" variant="secondary">Kembali</x-button>
        </x-slot:actions>
    </x-page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            {{-- Status & Info --}}
            <x-card>
                <div class="flex items-center gap-3 mb-4">
                    @php
                        $variant = match($effStatus) {
                            'aktif' => 'success', 'terlambat' => 'warning',
                            'selesai' => 'neutral', 'batal' => 'danger', default => 'neutral',
                        };
                    @endphp
                    <x-badge :variant="$variant">{{ ucfirst($effStatus) }}</x-badge>
                    <span class="font-mono text-sm" style="color: var(--color-stone);">{{ $rental->code }}</span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                    <div>
                        <p class="text-xs" style="color: var(--color-stone);">Tanggal Sewa</p>
                        <p class="font-medium">{{ $rental->rental_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs" style="color: var(--color-stone);">Jatuh Tempo</p>
                        <p class="font-medium">{{ $rental->due_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs" style="color: var(--color-stone);">Durasi</p>
                        <p class="font-medium">{{ $rental->days }} hari</p>
                    </div>
                    <div>
                        <p class="text-xs" style="color: var(--color-stone);">Dikembalikan</p>
                        <p class="font-medium">{{ $rental->actual_return_date ? $rental->actual_return_date->format('d/m/Y') : '—' }}</p>
                    </div>
                </div>
            </x-card>

            {{-- Items --}}
            <x-card>
                <h3 class="text-sm font-semibold mb-3" style="color: var(--color-forest);">Barang Disewa</h3>
                <x-data-table :headers="['Barang', 'Qty', 'Tarif/Hari', 'Subtotal', 'Dikembalikan', 'Kondisi']">
                    @foreach($rental->rentalItems as $ri)
                        <tr>
                            <td>
                                <p class="font-medium">{{ $ri->item->name ?? '—' }}</p>
                                <p class="text-xs" style="color: var(--color-stone);">{{ $ri->item->sku ?? '' }}</p>
                            </td>
                            <td>{{ $ri->quantity }}</td>
                            <td>Rp {{ number_format($ri->daily_rate, 0, ',', '.') }}</td>
                            <td class="font-medium">Rp {{ number_format($ri->subtotal, 0, ',', '.') }}</td>
                            <td>{{ $ri->returned_quantity }}/{{ $ri->quantity }}</td>
                            <td>
                                @if($ri->condition_on_return)
                                    <x-badge variant="{{ $ri->condition_on_return === 'baik' ? 'success' : ($ri->condition_on_return === 'perlu_perbaikan' ? 'warning' : 'danger') }}">
                                        {{ str_replace('_', ' ', ucfirst($ri->condition_on_return)) }}
                                    </x-badge>
                                @else
                                    <span style="color: var(--color-stone);">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </x-data-table>
            </x-card>
        </div>

        {{-- Right sidebar --}}
        <div class="space-y-6">
            {{-- Customer --}}
            <x-card>
                <h3 class="text-sm font-semibold mb-3" style="color: var(--color-forest);">Pelanggan</h3>
                <p class="font-medium">{{ $rental->customer->name ?? '—' }}</p>
                <p class="text-sm" style="color: var(--color-stone);">{{ $rental->customer->phone ?? '' }}</p>
                <p class="text-xs mt-1" style="color: var(--color-stone);">Kasir: {{ $rental->cashier->name ?? '—' }}</p>
            </x-card>

            {{-- Financial --}}
            <x-card>
                <h3 class="text-sm font-semibold mb-3" style="color: var(--color-forest);">Ringkasan Biaya</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span style="color: var(--color-stone);">Subtotal</span><span>Rp {{ number_format($rental->subtotal, 0, ',', '.') }}</span></div>
                    @if($rental->discount > 0)
                        <div class="flex justify-between"><span style="color: var(--color-stone);">Diskon</span><span>- Rp {{ number_format($rental->discount, 0, ',', '.') }}</span></div>
                    @endif
                    <div class="flex justify-between font-semibold border-t pt-2" style="border-color: var(--color-mist);"><span>Total Sewa</span><span style="color: var(--color-forest);">Rp {{ number_format($rental->total_amount, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between text-xs"><span style="color: var(--color-stone);">Deposit</span><span>Rp {{ number_format($rental->total_deposit, 0, ',', '.') }}</span></div>
                    @if($rental->late_fee > 0)
                        <div class="flex justify-between text-xs"><span style="color: var(--color-danger);">Denda</span><span style="color: var(--color-danger);">Rp {{ number_format($rental->late_fee, 0, ',', '.') }}</span></div>
                    @endif
                    <div class="flex justify-between text-xs"><span style="color: var(--color-stone);">Dibayar</span><span>Rp {{ number_format($rental->paid_amount, 0, ',', '.') }}</span></div>
                </div>
            </x-card>

            @if($rental->notes)
                <x-card>
                    <h3 class="text-sm font-semibold mb-2" style="color: var(--color-forest);">Catatan</h3>
                    <p class="text-sm" style="color: var(--color-stone);">{{ $rental->notes }}</p>
                </x-card>
            @endif

            {{-- Payment Form --}}
            @if($rental->status !== 'batal')
                <x-card x-data="{ showForm: false }">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold" style="color: var(--color-forest);">Pembayaran</h3>
                        <button @click="showForm = !showForm" class="text-xs underline cursor-pointer bg-transparent border-none" style="color: var(--color-olive);" x-text="showForm ? 'Tutup' : 'Tambah'"></button>
                    </div>

                    {{-- Payment list --}}
                    @if($rental->payments && $rental->payments->count() > 0)
                        <div class="space-y-2 mb-3">
                            @foreach($rental->payments as $payment)
                                <div class="flex items-center justify-between p-2 rounded text-sm" style="background-color: var(--color-ash);">
                                    <div>
                                        <span class="font-medium">{{ $payment->formatted_amount }}</span>
                                        <span class="text-xs ml-1" style="color: var(--color-stone);">{{ ucfirst($payment->method) }} • {{ ucfirst($payment->type) }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs" style="color: var(--color-stone);">{{ $payment->created_at->format('d/m H:i') }}</span>
                                        <form method="POST" action="{{ route('payments.destroy', $payment) }}" x-data x-on:submit.prevent="if(confirm('Hapus pembayaran ini?')) $el.submit()" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs cursor-pointer bg-transparent border-none" style="color: var(--color-danger);">✕</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs mb-3" style="color: var(--color-stone);">Belum ada pembayaran.</p>
                    @endif

                    {{-- Add payment form --}}
                    <div x-show="showForm" x-transition class="border-t pt-3" style="border-color: var(--color-mist);">
                        <form method="POST" action="{{ route('payments.store', $rental) }}">
                            @csrf
                            <div class="grid grid-cols-2 gap-2 mb-2">
                                <input type="number" name="amount" placeholder="Jumlah (Rp)" class="input-base !h-8 !text-xs" required min="1">
                                <select name="method" class="select-base !h-8 !text-xs">
                                    <option value="tunai">Tunai</option>
                                    <option value="transfer">Transfer</option>
                                    <option value="qris">QRIS</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-2 mb-2">
                                <select name="type" class="select-base !h-8 !text-xs">
                                    <option value="sewa">Sewa</option>
                                    <option value="deposit">Deposit</option>
                                    <option value="denda">Denda</option>
                                    <option value="refund">Refund</option>
                                </select>
                                <input type="text" name="notes" placeholder="Catatan" class="input-base !h-8 !text-xs">
                            </div>
                            <x-button type="submit" class="w-full justify-center !min-h-[32px] !text-xs">Catat Pembayaran</x-button>
                        </form>
                    </div>
                </x-card>
            @endif
        </div>
    </div>
@endsection
