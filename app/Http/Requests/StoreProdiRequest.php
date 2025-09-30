<?php

namespace App\Http\Requests;

use App\Models\Prodi;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreProdiRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('prodi_create');
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
        ];
    }
}
