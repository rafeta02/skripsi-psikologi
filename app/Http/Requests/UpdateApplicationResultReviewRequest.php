<?php

namespace App\Http\Requests;

use App\Models\ApplicationResultReview;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateApplicationResultReviewRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('application_result_review_edit');
    }

    public function rules()
    {
        return [
            'application_id' => [
                'required',
                'exists:applications,id',
            ],
            'result' => [
                'required',
                'string',
            ],
            'revision_deadline' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'form_document' => [
                'array',
                'nullable',
            ],
            'form_document.*' => [
                'string',
            ],
            'latest_script' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [
            'application_id.required' => 'Aplikasi skripsi wajib diisi',
            'application_id.exists' => 'Aplikasi skripsi tidak valid',
            'result.required' => 'Hasil review wajib diisi',
        ];
    }
}

