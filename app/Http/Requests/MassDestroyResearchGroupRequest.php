<?php

namespace App\Http\Requests;

use App\Models\ResearchGroup;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyResearchGroupRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('research_group_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:research_groups,id',
        ];
    }
}
