<?php

namespace App\Http\Requests;

use App\Models\MbkmRegistration;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreMbkmRegistrationRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('mbkm_registration_create');
    }

    public function rules()
    {
        return [
            'title_mbkm' => [
                'string',
                'nullable',
            ],
            'title' => [
                'string',
                'nullable',
            ],
            'khs_all' => [
                'array',
            ],
            'total_sks_taken' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'nilai_mk_kuantitatif' => [
                'string',
                'nullable',
            ],
            'nilai_mk_kualitatif' => [
                'string',
                'nullable',
            ],
            'nilai_mk_statistika_dasar' => [
                'string',
                'nullable',
            ],
            'nilai_mk_statistika_lanjutan' => [
                'string',
                'nullable',
            ],
            'nilai_mk_konstruksi_tes' => [
                'string',
                'nullable',
            ],
            'nilai_mk_tps' => [
                'string',
                'nullable',
            ],
            'sks_mkp_taken' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
        ];
    }
}
