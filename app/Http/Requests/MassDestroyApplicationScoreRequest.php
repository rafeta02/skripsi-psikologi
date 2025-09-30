<?php

namespace App\Http\Requests;

use App\Models\ApplicationScore;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyApplicationScoreRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('application_score_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:application_scores,id',
        ];
    }
}
