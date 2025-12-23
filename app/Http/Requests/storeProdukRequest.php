<?php

namespace App\Http\Requests;

use App\Models\KategoriProduk;
use Illuminate\Foundation\Http\FormRequest;

class storeProdukRequest extends FormRequest
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
            'nama_produk' => 'required|unique:produks,nama_produk',
            'deskripsi_produk' => 'required|min:10',
            'kategori_produk_id' => 'required|exists:kategori_produks,id'
        ];
    }

    public function messages(): array{
        return  [
            'nama_produk.unique' => 'Nama produk sudah digunakan',
            'nama_produk.required' => 'Nama produk harus di isi',
            'deskripsi_produk.required' => 'Deskripsi harus di isi',
            'deskripsi_produk.min' => 'Deskripsi produk minimal 10 karakter',
            'kategori_produk_id.required' => 'Kategori produk harus di isi',
            'kategori_produk_id.exists' => 'Kategori produk tidak ditemukan!',
        ];
    }
}
