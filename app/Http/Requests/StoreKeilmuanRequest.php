<?php

namespace App\Http\Requests;

use App\Models\Keilmuan;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreKeilmuanRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('keilmuan_create');
    }

    public function rules()
    {
        return [
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
