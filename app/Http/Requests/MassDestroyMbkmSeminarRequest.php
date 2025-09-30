<?php

namespace App\Http\Requests;

use App\Models\MbkmSeminar;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyMbkmSeminarRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('mbkm_seminar_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:mbkm_seminars,id',
        ];
    }
}
