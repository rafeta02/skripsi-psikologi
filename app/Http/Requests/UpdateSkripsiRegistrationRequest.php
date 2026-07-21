<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSkripsiRegistrationRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('skripsi_registration_edit');
    }

    public function rules()
    {
        return [
            'application_id' => [
                'nullable',
                'integer',
                'exists:applications,id',
            ],
            'theme_ids' => [
                'nullable',
                'array',
                'min:1',
            ],
            'theme_ids.*' => [
                'required',
                'integer',
                'exists:keilmuans,id',
            ],
            'title' => [
                'string',
                'nullable',
            ],
            'khs_all' => [
                'array',
            ],
        ];
    }

    public function messages()
    {
        return [
            'theme_ids.min' => 'Pilih minimal satu tema riset.',
            'theme_ids.*.exists' => 'Tema riset yang dipilih tidak valid.',
        ];
    }
}
