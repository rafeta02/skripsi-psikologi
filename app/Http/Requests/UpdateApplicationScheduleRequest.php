<?php

namespace App\Http\Requests;

use App\Models\ApplicationSchedule;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateApplicationScheduleRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('application_schedule_edit');
    }

    public function rules()
    {
        return [
            'waktu' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'custom_place' => [
                'string',
                'nullable',
            ],
            'online_meeting' => [
                'string',
                'nullable',
            ],
            'approval_form' => [
                'array',
            ],
        ];
    }
}
