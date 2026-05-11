<?php

namespace App\Http\Requests\Admin\Intern;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInternAttendanceLocationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'location_ids' => ['nullable', 'array'],
            'location_ids.*' => [
                'integer',
                Rule::exists('attendance_locations', 'id')->where(fn ($query) => $query->whereNull('deleted_at')),
            ],
            'primary_location_id' => ['nullable', 'integer'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $locationIds = array_map('intval', $this->input('location_ids', []));
            $primaryLocationId = $this->input('primary_location_id');

            if ($primaryLocationId && ! in_array((int) $primaryLocationId, $locationIds, true)) {
                $validator->errors()->add('primary_location_id', 'Lokasi utama harus termasuk dalam daftar lokasi aktif intern.');
            }
        });
    }
}
