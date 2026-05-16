<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:1'],
            'method' => ['required', 'in:tunai,transfer,qris,edc'],
            'type' => ['required', 'in:dp,pelunasan,denda,refund_deposit'],
            'paid_at' => ['nullable', 'date'],
            'reference_no' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Jumlah pembayaran wajib diisi.',
            'amount.min' => 'Jumlah pembayaran minimal Rp 1.',
            'method.required' => 'Metode pembayaran wajib dipilih.',
            'type.required' => 'Jenis pembayaran wajib dipilih.',
            'paid_at.date' => 'Format tanggal pembayaran tidak valid.',
        ];
    }
}
