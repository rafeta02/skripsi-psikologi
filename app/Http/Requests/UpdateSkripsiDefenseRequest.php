<?php

namespace App\Http\Requests;

use App\Models\SkripsiDefense;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

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
            'eap_grade' => [
                'required',
                Rule::in(SkripsiDefense::allowedEapGrades()),
            ],
            'eap_score' => [
                'required',
                'integer',
                'min:1',
                'max:100',
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
            'eap_grade.required' => 'Nilai EAP harus dipilih',
            'eap_grade.in' => 'Nilai EAP tidak valid',
            'eap_score.required' => 'Skor EAP harus diisi',
            'eap_score.integer' => 'Skor EAP harus berupa angka',
            'eap_score.min' => 'Skor EAP minimal 1',
            'eap_score.max' => 'Skor EAP maksimal 100',
        ];
    }
}
