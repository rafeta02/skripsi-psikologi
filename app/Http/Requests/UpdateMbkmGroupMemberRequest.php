<?php

namespace App\Http\Requests;

use App\Models\MbkmGroupMember;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateMbkmGroupMemberRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('mbkm_group_member_edit');
    }

    public function rules()
    {
        return [];
    }
}
