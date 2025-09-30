<?php

namespace App\Http\Requests;

use App\Models\SkripsiRegistration;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSkripsiRegistrationRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('skripsi_registration_edit');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'nullable',
            ],
            'khs_all' => [
                'array',
            ],
        ];
    }
}
