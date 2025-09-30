<?php

namespace App\Http\Requests;

use App\Models\ApplicationReport;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateApplicationReportRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('application_report_edit');
    }

    public function rules()
    {
        return [
            'report_document' => [
                'array',
            ],
            'period' => [
                'string',
                'nullable',
            ],
        ];
    }
}
