<?php

namespace App\Http\Requests;

use App\Models\MbkmRegistration;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyMbkmRegistrationRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('mbkm_registration_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:mbkm_registrations,id',
        ];
    }
}
