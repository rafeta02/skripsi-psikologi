<?php

namespace App\Http\Requests;

use App\Models\Ruang;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateRuangRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('ruang_edit');
    }

    public function rules()
    {
        return [
            'code' => [
                'string',
                'nullable',
            ],
            'name' => [
                'string',
                'nullable',
            ],
            'slug' => [
                'string',
                'nullable',
            ],
            'location' => [
                'string',
                'nullable',
            ],
            'capacity' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
