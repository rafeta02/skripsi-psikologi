<?php

namespace App\Http\Requests;

use App\Models\MbkmSeminar;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreMbkmSeminarRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('mbkm_seminar_create');
    }

    public function rules()
    {
        return [
            'application_id' => [
                'nullable',
                'exists:applications,id',
            ],
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'notes' => [
                'nullable',
                'string',
            ],
            'proposal_document' => [
                'required',
                'file',
                'mimes:pdf',
                'max:10240', // 10MB
            ],
            'approval_document' => [
                'required',
                'file',
                'mimes:pdf',
                'max:10240',
            ],
            'plagiarism_document' => [
                'required',
                'file',
                'mimes:pdf',
                'max:10240',
            ],
        ];
    }

    public function messages()
    {
        return [
            'application_id.exists' => 'Aplikasi MBKM tidak valid.',
            'title.required' => 'Judul MBKM wajib diisi.',
            'title.max' => 'Judul MBKM maksimal 255 karakter.',
            'proposal_document.required' => 'Dokumen proposal MBKM wajib diupload.',
            'proposal_document.file' => 'Dokumen proposal harus berupa file.',
            'proposal_document.mimes' => 'Dokumen proposal harus berformat PDF.',
            'proposal_document.max' => 'Ukuran dokumen proposal maksimal 10MB.',
            'approval_document.required' => 'Dokumen persetujuan pembimbing wajib diupload.',
            'approval_document.file' => 'Dokumen persetujuan harus berupa file.',
            'approval_document.mimes' => 'Dokumen persetujuan harus berformat PDF.',
            'approval_document.max' => 'Ukuran dokumen persetujuan maksimal 10MB.',
            'plagiarism_document.required' => 'Dokumen hasil cek plagiarisme wajib diupload.',
            'plagiarism_document.file' => 'Dokumen plagiarism check harus berupa file.',
            'plagiarism_document.mimes' => 'Dokumen plagiarism check harus berformat PDF.',
            'plagiarism_document.max' => 'Ukuran dokumen plagiarism check maksimal 10MB.',
        ];
    }
}
