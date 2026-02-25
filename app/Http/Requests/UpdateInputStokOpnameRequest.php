<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInputStokOpnameRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'produk' => 'required',
            'nomor_sku' => 'required',
            'jumlah_dilaporkan' => 'required|numeric|min:0'
        ];
    }

    public function messages(): array
    {
        return [
            'produk.required' => 'Produk wajib diisi',
            'nomor_sku.required' => 'Nomor SKU wajib diisi',
            'jumlah_dilaporkan.required' => 'Jumlah dilaporkan wajib diisi',
            'jumlah_dilaporkan.numeric' => 'Jumlah dilaporkan harus berupa angka',
            'jumlah_dilaporkan.min' => 'Jumlah dilaporkan minimal 0',
        ];
    }
}
