<?php

namespace App\Http\Requests\Admin\Division;

use Illuminate\Foundation\Http\FormRequest;

class StoreDivisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:divisions,name'],
            'code' => ['required', 'string', 'max:50', 'unique:divisions,code'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama divisi wajib diisi.',
            'name.unique' => 'Nama divisi sudah terdaftar.',
            'code.required' => 'Kode divisi wajib diisi.',
            'code.unique' => 'Kode divisi sudah digunakan.',
        ];
    }
}
