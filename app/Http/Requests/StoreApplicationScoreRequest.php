<?php

namespace App\Http\Requests;

use App\Models\ApplicationScore;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreApplicationScoreRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('application_score_create');
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
