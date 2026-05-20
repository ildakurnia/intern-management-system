<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLogbookRequest extends FormRequest
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
        $intern = $this->user()?->intern;
        $internId = $intern->id ?? null;
        $logbookId = $this->route('logbook');
        $startDate = $intern?->start_date?->toDateString();

        $tanggalRules = [
            'required',
            'date',
            'before_or_equal:today',
        ];

        if ($startDate) {
            $tanggalRules[] = 'after_or_equal:' . $startDate;
        }

        $tanggalRules[] = Rule::unique('logbooks')->where(function ($query) use ($internId) {
            return $query->where('intern_id', $internId);
        })->ignore($logbookId);

        return [
            'tanggal' => $tanggalRules,
            'uraian_aktivitas' => 'required|string|min:30',
            'pembelajaran_diperoleh' => 'required|string',
            'kendala_dialami' => 'nullable|string',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tanggal.after_or_equal' => 'Tanggal logbook tidak boleh sebelum masa magang dimulai.',
            'tanggal.before_or_equal' => 'Tanggal logbook tidak boleh melebihi hari ini.',
            'tanggal.unique' => 'Anda sudah membuat logbook untuk tanggal ini.',
        ];
    }
}
