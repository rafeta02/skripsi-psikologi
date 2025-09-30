<?php

namespace App\Http\Requests;

use App\Models\ApplicationResultSeminar;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreApplicationResultSeminarRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('application_result_seminar_create');
    }

    public function rules()
    {
        return [
            'revision_deadline' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'report_document' => [
                'array',
            ],
            'form_document' => [
                'array',
            ],
            'documentation' => [
                'array',
            ],
        ];
    }
}
