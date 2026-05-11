<?php

namespace App\Http\Requests\Admin\AttendanceLocation;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $locationId = $this->route('attendance_location')->id;

        return [
            'name' => ['required', 'string', 'max:255', 'unique:attendance_locations,name,'.$locationId],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'radius_meters' => ['required', 'integer', 'min:10', 'max:300'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
