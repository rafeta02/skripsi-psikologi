<?php

namespace App\Http\Requests;

use App\Models\Mahasiswa;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreMahasiswaRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('mahasiswa_create');
    }

    public function rules()
    {
        return [
            'nim' => [
                'string',
                'nullable',
            ],
            'nama' => [
                'string',
                'nullable',
            ],
            'tahun_masuk' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'tanggal_lahir' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'tempat_lahir' => [
                'string',
                'nullable',
            ],
        ];
    }
}
