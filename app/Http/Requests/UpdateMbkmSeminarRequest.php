<?php

namespace App\Http\Requests;

use App\Models\MbkmSeminar;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateMbkmSeminarRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('mbkm_seminar_edit');
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
