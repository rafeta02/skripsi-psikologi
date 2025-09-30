<?php

namespace App\Http\Requests;

use App\Models\SkripsiDefense;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSkripsiDefenseRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('skripsi_defense_create');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'nullable',
            ],
            'abstract' => [
                'string',
                'nullable',
            ],
            'ethics_statement' => [
                'array',
            ],
            'research_instruments' => [
                'array',
            ],
            'data_collection_letter' => [
                'array',
            ],
            'research_module' => [
                'array',
            ],
            'defense_approval_page' => [
                'array',
            ],
            'mbkm_report' => [
                'array',
            ],
            'research_poster' => [
                'array',
            ],
            'supervision_logbook' => [
                'array',
            ],
        ];
    }
}
