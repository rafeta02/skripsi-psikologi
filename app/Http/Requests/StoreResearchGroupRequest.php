<?php

namespace App\Http\Requests;

use App\Models\ResearchGroup;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreResearchGroupRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('research_group_create');
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
