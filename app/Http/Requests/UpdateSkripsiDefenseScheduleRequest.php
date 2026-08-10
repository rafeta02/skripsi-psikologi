<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSkripsiDefenseScheduleRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('skripsi_defense_edit');
    }

    public function rules()
    {
        return [
            'waktu' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'ruang_id' => [
                'nullable',
                'exists:ruangs,id',
            ],
            'custom_place' => [
                'nullable',
                'string',
                'max:255',
            ],
            'online_meeting' => [
                'nullable',
                'string',
                'max:500',
            ],
            'note' => [
                'nullable',
                'string',
            ],
            'schedule_change_note' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages()
    {
        return [
            'waktu.required' => 'Waktu sidang wajib diisi.',
            'waktu.date_format' => 'Format waktu tidak valid.',
            'ruang_id.exists' => 'Ruangan tidak valid.',
        ];
    }
}
