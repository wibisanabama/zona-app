@extends('layouts.app')

@section('title', 'POS Rental')

@section('content')
<div x-data="posApp()" x-init="fetchItems()">
    <x-page-header title="POS Rental" subtitle="Buat transaksi sewa baru" />

    <form method="POST" action="{{ route('pos.store') }}" @submit.prevent="submitForm" id="posForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            {{-- LEFT: Item search & grid --}}
            <div class="lg:col-span-3">
                <x-card>
                    <div class="mb-4">
                        <input type="text" x-model="searchQuery" @input.debounce.300ms="fetchItems()" placeholder="Cari barang (nama atau SKU)..." class="input-base">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-[500px] overflow-y-auto pr-1">
                        <template x-for="item in filteredItems" :key="item.id">
                            <div @click="addToCart(item)" class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-all hover:shadow-sm" :class="item.stock_available <= 0 ? 'opacity-50 cursor-not-allowed' : ''" style="border-color: var(--color-dove);">
                                <div class="w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: var(--color-ash);">
                                    <template x-if="item.photo_url">
                                        <img :src="item.photo_url" class="w-full h-full object-cover rounded-lg">
                                    </template>
                                    <template x-if="!item.photo_url">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--color-dove);"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                                    </template>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs" style="color: var(--color-stone);" x-text="item.sku"></p>
                                    <p class="text-sm font-medium truncate" x-text="item.name"></p>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-xs font-semibold" style="color: var(--color-forest);" x-text="item.formatted_rate + '/hari'"></span>
                                        <span class="badge-neutral !text-xs" x-text="'Stok: ' + item.stock_available"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <template x-if="filteredItems.length === 0">
                        <p class="text-sm text-center py-8" style="color: var(--color-stone);">Tidak ada barang ditemukan.</p>
                    </template>
                </x-card>
            </div>

            {{-- RIGHT: Cart & Summary --}}
            <div class="lg:col-span-2 space-y-4">
                {{-- Customer & Date --}}
                <x-card>
                    <h3 class="text-sm font-semibold mb-3" style="color: var(--color-forest);">Informasi Sewa</h3>

                    <div class="mb-3">
                        <label class="block text-xs font-medium mb-1" style="color: var(--color-stone);">Pelanggan *</label>
                        <select name="customer_id" x-model="customerId" class="select-base" required>
                            <option value="">Pilih Pelanggan</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium mb-1" style="color: var(--color-stone);">Tanggal Sewa *</label>
                            <input type="date" name="rental_date" x-model="rentalDate" class="input-base" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium mb-1" style="color: var(--color-stone);">Durasi (hari) *</label>
                            <input type="number" name="days" x-model.number="days" min="1" max="90" class="input-base" required>
                        </div>
                    </div>
                </x-card>

                {{-- Cart Items --}}
                <x-card>
                    <h3 class="text-sm font-semibold mb-3" style="color: var(--color-forest);">
                        Keranjang (<span x-text="cart.length"></span>)
                    </h3>

                    <template x-if="cart.length === 0">
                        <p class="text-sm text-center py-6" style="color: var(--color-stone);">Klik barang di sebelah kiri untuk menambahkan.</p>
                    </template>

                    <div class="space-y-2 max-h-[300px] overflow-y-auto">
                        <template x-for="(ci, idx) in cart" :key="ci.item_id">
                            <div class="flex items-center gap-2 p-2 rounded-lg" style="background-color: var(--color-ash);">
                                <input type="hidden" :name="'items[' + idx + '][item_id]'" :value="ci.item_id">
                                <input type="hidden" :name="'items[' + idx + '][quantity]'" :value="ci.quantity">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium truncate" x-text="ci.name"></p>
                                    <p class="text-xs" style="color: var(--color-stone);" x-text="formatRupiah(ci.daily_rate) + '/hari'"></p>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button type="button" @click="decrementQty(idx)" class="w-6 h-6 rounded flex items-center justify-center text-xs border" style="border-color: var(--color-dove);">−</button>
                                    <span class="w-8 text-center text-sm font-medium" x-text="ci.quantity"></span>
                                    <button type="button" @click="incrementQty(idx)" class="w-6 h-6 rounded flex items-center justify-center text-xs border" style="border-color: var(--color-dove);">+</button>
                                </div>
                                <button type="button" @click="removeFromCart(idx)" class="text-xs ml-1" style="color: var(--color-danger);">✕</button>
                            </div>
                        </template>
                    </div>
                </x-card>

                {{-- Summary --}}
                <x-card>
                    <h3 class="text-sm font-semibold mb-3" style="color: var(--color-forest);">Ringkasan</h3>

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span style="color: var(--color-stone);">Subtotal (<span x-text="days"></span> hari)</span>
                            <span class="font-medium" x-text="formatRupiah(computedSubtotal)"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span style="color: var(--color-stone);">Diskon</span>
                            <input type="number" name="discount" x-model.number="discount" min="0" class="input-base !w-28 !h-7 !text-xs text-right" placeholder="0">
                        </div>
                        <div class="border-t pt-2" style="border-color: var(--color-mist);">
                            <div class="flex justify-between font-semibold">
                                <span>Total Sewa</span>
                                <span style="color: var(--color-forest);" x-text="formatRupiah(computedTotal)"></span>
                            </div>
                        </div>
                        <div class="flex justify-between text-xs" style="color: var(--color-stone);">
                            <span>Total Deposit</span>
                            <span x-text="formatRupiah(computedDeposit)"></span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <textarea name="notes" placeholder="Catatan (opsional)" class="input-base !text-xs" rows="2"></textarea>
                    </div>

                    <button type="submit" class="btn-primary w-full mt-4 justify-center" style="min-height: 44px; font-size: 15px;" :disabled="cart.length === 0 || !customerId || submitting">
                        <span x-show="!submitting">Simpan & Buat Struk</span>
                        <span x-show="submitting">Memproses...</span>
                    </button>
                </x-card>
            </div>
        </div>
    </form>

    {{-- Validation errors --}}
    @if($errors->any())
        <div class="mt-4 p-4 rounded-lg" style="background-color: rgba(220, 53, 69, 0.1);">
            <ul class="text-sm list-disc pl-4" style="color: var(--color-danger);">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

@push('scripts')
<script>
function posApp() {
    return {
        searchQuery: '',
        allItems: [],
        cart: [],
        customerId: '',
        rentalDate: new Date().toISOString().split('T')[0],
        days: 1,
        discount: 0,
        submitting: false,

        get filteredItems() {
            return this.allItems;
        },

        get computedSubtotal() {
            return this.cart.reduce((sum, ci) => sum + (ci.daily_rate * ci.quantity * this.days), 0);
        },

        get computedTotal() {
            return Math.max(0, this.computedSubtotal - this.discount);
        },

        get computedDeposit() {
            return this.cart.reduce((sum, ci) => sum + (ci.deposit_amount * ci.quantity), 0);
        },

        fetchItems() {
            let url = '{{ route("pos.search-items") }}?q=' + encodeURIComponent(this.searchQuery);
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json())
                .then(data => { this.allItems = data; });
        },

        addToCart(item) {
            if (item.stock_available <= 0) return;
            let existing = this.cart.find(c => c.item_id === item.id);
            if (existing) {
                if (existing.quantity < item.stock_available) {
                    existing.quantity++;
                }
            } else {
                this.cart.push({
                    item_id: item.id,
                    name: item.name,
                    daily_rate: item.daily_rate,
                    deposit_amount: item.deposit_amount,
                    quantity: 1,
                    max_stock: item.stock_available,
                });
            }
        },

        incrementQty(idx) {
            if (this.cart[idx].quantity < this.cart[idx].max_stock) {
                this.cart[idx].quantity++;
            }
        },

        decrementQty(idx) {
            if (this.cart[idx].quantity > 1) {
                this.cart[idx].quantity--;
            } else {
                this.removeFromCart(idx);
            }
        },

        removeFromCart(idx) {
            this.cart.splice(idx, 1);
        },

        formatRupiah(val) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(val));
        },

        submitForm() {
            if (this.cart.length === 0 || !this.customerId) return;
            this.submitting = true;
            document.getElementById('posForm').submit();
        }
    }
}
</script>
@endpush
@endsection
