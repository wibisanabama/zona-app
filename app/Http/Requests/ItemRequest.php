<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $itemId = $this->route('item')?->id;

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'sku' => [
                'required',
                'string',
                'max:50',
                Rule::unique('items', 'sku')->ignore($itemId),
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'daily_rate' => ['required', 'numeric', 'min:0'],
            'deposit_amount' => ['required', 'numeric', 'min:0'],
            'stock_total' => ['required', 'integer', 'min:1'],
            'condition' => ['required', Rule::in(['baik', 'perlu_perbaikan', 'rusak'])],
            'photo' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori tidak valid.',
            'sku.required' => 'Kode SKU wajib diisi.',
            'sku.unique' => 'Kode SKU sudah digunakan.',
            'name.required' => 'Nama barang wajib diisi.',
            'daily_rate.required' => 'Tarif sewa harian wajib diisi.',
            'daily_rate.min' => 'Tarif sewa harian tidak boleh negatif.',
            'deposit_amount.required' => 'Deposit wajib diisi.',
            'stock_total.required' => 'Stok total wajib diisi.',
            'stock_total.min' => 'Stok total minimal 1.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.max' => 'Ukuran foto maksimal 2MB.',
        ];
    }
}
