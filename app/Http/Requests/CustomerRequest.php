<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customerId = $this->route('customer')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', Rule::unique('customers', 'phone')->ignore($customerId)],
            'identity_type' => ['required', Rule::in(['ktp', 'sim', 'passport'])],
            'identity_number' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:1000'],
            'email' => ['nullable', 'email', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'blacklisted' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama pelanggan wajib diisi.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.unique' => 'Nomor telepon sudah terdaftar.',
            'identity_type.required' => 'Jenis identitas wajib dipilih.',
            'identity_number.required' => 'Nomor identitas wajib diisi.',
            'address.required' => 'Alamat wajib diisi.',
        ];
    }
}
