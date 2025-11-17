<?php

namespace App\Http\Requests;

use App\Models\SkripsiSeminar;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateSkripsiSeminarRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('skripsi_seminar_edit');
    }

    public function rules()
    {
        return [
            'application_id' => [
                'required',
                'exists:applications,id',
            ],
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'proposal_document' => [
                'required',
                'string',
            ],
            'approval_document' => [
                'required',
                'string',
            ],
            'plagiarism_document' => [
                'required',
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [
            'application_id.required' => 'Aplikasi skripsi wajib dipilih.',
            'application_id.exists' => 'Aplikasi skripsi tidak valid.',
            'title.required' => 'Judul proposal wajib diisi.',
            'title.max' => 'Judul proposal maksimal 255 karakter.',
            'proposal_document.required' => 'Dokumen proposal wajib diupload.',
            'approval_document.required' => 'Form persetujuan pembimbing wajib diupload.',
            'plagiarism_document.required' => 'Hasil cek plagiarisme wajib diupload.',
        ];
    }
}
