<?php

namespace App\Http\Requests;

use App\Models\ApplicationSchedule;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreApplicationScheduleRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('application_schedule_create');
    }

    public function rules()
    {
        return [
            'application_id' => [
                'required',
                'exists:applications,id',
            ],
            'schedule_type' => [
                'required',
                'in:seminar,defense,skripsi_seminar,mbkm_seminar,skripsi_defense',
            ],
            'waktu' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'custom_place' => [
                'string',
                'nullable',
            ],
            'online_meeting' => [
                'string',
                'nullable',
            ],
            'approval_form' => [
                'required',
                'array',
                'min:1',
            ],
            'approval_form.*' => [
                'required',
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [
            'application_id.required' => 'Aplikasi skripsi wajib dipilih.',
            'application_id.exists' => 'Aplikasi skripsi tidak valid.',
            'schedule_type.required' => 'Tipe jadwal wajib dipilih.',
            'schedule_type.in' => 'Tipe jadwal tidak valid. Pilih salah satu: Seminar atau Sidang.',
            'waktu.required' => 'Waktu pelaksanaan wajib diisi.',
            'waktu.date_format' => 'Format waktu tidak valid.',
            'approval_form.required' => 'Form persetujuan wajib diupload.',
            'approval_form.min' => 'Minimal 1 form persetujuan harus diupload.',
        ];
    }
}
