<?php

namespace App\Http\Requests;

use App\Models\ApplicationReport;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyApplicationReportRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('application_report_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:application_reports,id',
        ];
    }
}
