<?php

namespace App\Http\Requests;

use App\Models\SkripsiRegistration;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSkripsiRegistrationRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('skripsi_registration_create');
    }

    public function rules()
    {
        return [
            'theme_id' => [
                'required',
                'integer',
                'exists:keilmuans,id',
            ],
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'abstract' => [
                'nullable',
                'string',
            ],
            'tps_lecturer_id' => [
                'nullable',
                'integer',
                'exists:dosens,id',
            ],
            'preference_supervision_id' => [
                'required',
                'integer',
                'exists:dosens,id',
            ],
            'khs_all' => [
                'required',
                'array',
                'min:1',
            ],
            'khs_all.*' => [
                'required',
                'string',
            ],
            'krs_latest' => [
                'required',
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [
            'theme_id.required' => 'Tema Keilmuan wajib dipilih.',
            'theme_id.exists' => 'Tema Keilmuan yang dipilih tidak valid.',
            'title.required' => 'Judul Skripsi wajib diisi.',
            'title.max' => 'Judul Skripsi maksimal 255 karakter.',
            'preference_supervision_id.required' => 'Preferensi Dosen Pembimbing wajib dipilih.',
            'preference_supervision_id.exists' => 'Dosen Pembimbing yang dipilih tidak valid.',
            'tps_lecturer_id.exists' => 'Dosen TPS yang dipilih tidak valid.',
            'khs_all.required' => 'KHS (Kartu Hasil Studi) wajib diupload.',
            'khs_all.min' => 'Minimal 1 file KHS harus diupload.',
            'krs_latest.required' => 'KRS (Kartu Rencana Studi) semester terakhir wajib diupload.',
        ];
    }
}
