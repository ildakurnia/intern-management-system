<?php

namespace App\Http\Requests\Intern\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceCheckpointRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'accuracy' => ['nullable', 'numeric', 'min:0', 'max:10000'],
        ];
    }

    public function messages(): array
    {
        return [
            'latitude.required' => 'Lokasi browser belum dikirim. Izinkan akses lokasi lalu coba lagi.',
            'longitude.required' => 'Lokasi browser belum dikirim. Izinkan akses lokasi lalu coba lagi.',
            'latitude.between' => 'Latitude yang dikirim browser tidak valid.',
            'longitude.between' => 'Longitude yang dikirim browser tidak valid.',
            'accuracy.numeric' => 'Akurasi lokasi yang dikirim browser tidak valid.',
        ];
    }
}
