<?php

namespace App\Http\Requests\Intern\Attendance;

use App\Models\Attendance;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttendanceSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => [
                'required',
                'string',
                Rule::in(Attendance::submissionStatuses()),
            ],
            'date' => ['required', 'date', 'before_or_equal:today'],
            'reason' => ['required', 'string', 'min:10', 'max:1000'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.in' => 'Jenis pengajuan absensi tidak valid.',
            'date.before_or_equal' => 'Tanggal pengajuan tidak boleh melebihi hari ini.',
            'reason.min' => 'Alasan minimal harus terdiri dari 10 karakter.',
            'attachment.mimes' => 'Lampiran hanya mendukung format JPG, PNG, atau PDF.',
            'attachment.max' => 'Ukuran lampiran maksimal 2 MB.',
        ];
    }
}
