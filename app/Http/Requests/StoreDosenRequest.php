<?php

namespace App\Http\Requests;

use App\Models\Dosen;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreDosenRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('dosen_create');
    }

    public function rules()
    {
        return [
            'nip' => [
                'string',
                'nullable',
            ],
            'nidn' => [
                'string',
                'nullable',
            ],
            'nama' => [
                'string',
                'nullable',
            ],
            'tempat_lahir' => [
                'string',
                'nullable',
            ],
            'tanggal_lahir' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'keilmuans.*' => [
                'integer',
            ],
            'keilmuans' => [
                'array',
            ],
        ];
    }
}
