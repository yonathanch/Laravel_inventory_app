<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePeriodeStokOpnameRequest extends FormRequest
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
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_mulai.required'      => 'Tanggal mulai wajib di isi',
            'tanggal_selesai.required'    => 'Tanggal selesai wajib di  isi',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah tanggal mulai',
            'is_active' => 'Status wajib di isi',
        ];
    }

}
