<?php

namespace App\Http\Requests;

use App\Models\ApplicationAssignment;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateApplicationAssignmentRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('application_assignment_edit');
    }

    public function rules()
    {
        return [
            'assigned_at' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'responded_at' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
        ];
    }
}
