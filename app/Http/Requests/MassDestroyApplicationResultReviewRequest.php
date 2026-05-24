<?php

namespace App\Http\Requests;

use App\Models\ApplicationResultReview;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyApplicationResultReviewRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('application_result_review_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:application_result_reviews,id',
        ];
    }
}









