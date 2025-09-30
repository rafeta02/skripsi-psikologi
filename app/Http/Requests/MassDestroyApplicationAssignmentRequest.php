<?php

namespace App\Http\Requests;

use App\Models\ApplicationAssignment;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyApplicationAssignmentRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('application_assignment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:application_assignments,id',
        ];
    }
}
