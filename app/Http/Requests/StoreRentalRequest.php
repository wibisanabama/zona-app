<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRentalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'rental_date' => ['required', 'date', 'after_or_equal:today'],
            'days' => ['required', 'integer', 'min:1', 'max:90'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_id' => ['required', 'exists:items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Pelanggan wajib dipilih.',
            'rental_date.required' => 'Tanggal sewa wajib diisi.',
            'rental_date.after_or_equal' => 'Tanggal sewa tidak boleh di masa lalu.',
            'days.required' => 'Durasi sewa wajib diisi.',
            'days.min' => 'Durasi sewa minimal 1 hari.',
            'items.required' => 'Tambahkan minimal 1 barang ke keranjang.',
            'items.min' => 'Tambahkan minimal 1 barang ke keranjang.',
        ];
    }
}
