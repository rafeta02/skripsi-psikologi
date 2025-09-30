<?php

namespace App\Http\Requests;

use App\Models\SkripsiSeminar;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSkripsiSeminarRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('skripsi_seminar_create');
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
