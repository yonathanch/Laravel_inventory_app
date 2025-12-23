<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateKategoriProdukRequest extends FormRequest
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
        // kalo ada data yg di edit uniquenya tidak berlaku,dari mana kategori_produk? dari php artisan route:list yaitu:
        // master-data/kategori-produk/{kategori_produk}/edit master-data.kategori-produk.edit ›
        //  $this->kategori_produk->id,
        return [
            'nama_kategori' => 'unique:kategori_produks,nama_kategori,' . $this->kategori_produk->id,
        ];
    }

    public function message(): array
    {
        return [
            'required' => 'Nama kategori waib di isi',
            'unique' => 'Nama kategori sudah digunakan',
        ];
    }
}
