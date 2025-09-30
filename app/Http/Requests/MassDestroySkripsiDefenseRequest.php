<?php

namespace App\Http\Requests;

use App\Models\SkripsiDefense;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroySkripsiDefenseRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('skripsi_defense_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:skripsi_defenses,id',
        ];
    }
}
