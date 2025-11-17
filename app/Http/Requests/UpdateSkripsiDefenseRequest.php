<?php

namespace App\Http\Requests;

use App\Models\SkripsiDefense;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSkripsiDefenseRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('skripsi_defense_edit');
    }

    public function rules()
    {
        return [
            'application_id' => [
                'required',
                'exists:applications,id',
            ],
            'title' => [
                'required',
                'string',
            ],
            'abstract' => [
                'required',
                'string',
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
    
    public function messages()
    {
        return [
            'application_id.required' => 'Aplikasi harus dipilih',
            'title.required' => 'Judul skripsi harus diisi',
            'abstract.required' => 'Abstrak harus diisi',
        ];
    }
}
