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
            'score' => [
                'numeric',
                'min:0',
                'max:10',
            ],
        ];
    }
}
