<?php

namespace App\Http\Requests;

use App\Models\Keilmuan;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateKeilmuanRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('keilmuan_edit');
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
