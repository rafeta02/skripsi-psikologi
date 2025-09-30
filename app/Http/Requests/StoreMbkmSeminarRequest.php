<?php

namespace App\Http\Requests;

use App\Models\MbkmSeminar;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreMbkmSeminarRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('mbkm_seminar_create');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'nullable',
            ],
        ];
    }
}
