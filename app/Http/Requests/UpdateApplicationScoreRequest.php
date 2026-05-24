<?php

namespace App\Http\Requests;

use App\Models\ApplicationScore;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateApplicationScoreRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('application_score_edit');
    }

    public function rules()
    {
        return [
            'penulisan' => [
                'integer',
                'nullable',
            ],
            'isi' => [
                'integer',
                'nullable',
            ],
            'analisis' => [
                'integer',
                'nullable',
            ],
            'teoritis' => [
                'integer',
                'nullable',
            ],
            'faktual' => [
                'integer',
                'nullable',
            ],
            'pemecahan_masalah' => [
                'integer',
                'nullable',
            ],
            'penyampaian' => [
                'integer',
                'nullable',
            ],
            'sum' => [
                'numeric',
                'nullable',
            ],
            'score' => [
                'numeric',
                'nullable',
            ],
        ];
    }
}
