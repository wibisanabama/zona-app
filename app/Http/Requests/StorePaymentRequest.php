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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $rental = $this->route('rental');
            if (!$rental) {
                return;
            }

            $amount = $this->input('amount');
            $type = $this->input('type');

            if (!$amount || !is_numeric($amount)) {
                return;
            }

            if ($type === 'refund_deposit') {
                if ($amount > $rental->total_deposit) {
                    $validator->errors()->add('amount', 'Jumlah refund deposit tidak boleh melebihi total deposit (Rp ' . number_format($rental->total_deposit, 0, ',', '.') . ').');
                }
            } elseif (in_array($type, ['dp', 'pelunasan', 'denda'])) {
                $outstandingBalance = $rental->total_amount + $rental->late_fee - $rental->paid_amount;
                if ($outstandingBalance < 0) {
                    $outstandingBalance = 0;
                }
                if ($amount > $outstandingBalance) {
                    $validator->errors()->add('amount', 'Jumlah pembayaran tidak boleh melebihi sisa tagihan (Rp ' . number_format($outstandingBalance, 0, ',', '.') . ').');
                }
            }
        });
    }
}
