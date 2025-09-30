<?php

namespace App\Http\Requests;

use App\Models\SkripsiRegistration;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSkripsiRegistrationRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('skripsi_registration_create');
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
