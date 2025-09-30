<?php

namespace App\Http\Requests;

use App\Models\ApplicationResultDefense;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreApplicationResultDefenseRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('application_result_defense_create');
    }

    public function rules()
    {
        return [
            'revision_deadline' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'final_grade' => [
                'numeric',
                'min:0',
                'max:10',
            ],
            'documentation' => [
                'array',
            ],
            'invitation_document' => [
                'array',
            ],
            'feedback_document' => [
                'array',
            ],
            'report_document' => [
                'array',
            ],
            'revision_approval_sheet' => [
                'array',
            ],
        ];
    }
}
