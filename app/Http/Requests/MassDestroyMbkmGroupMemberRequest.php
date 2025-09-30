<?php

namespace App\Http\Requests;

use App\Models\MbkmGroupMember;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyMbkmGroupMemberRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('mbkm_group_member_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:mbkm_group_members,id',
        ];
    }
}
