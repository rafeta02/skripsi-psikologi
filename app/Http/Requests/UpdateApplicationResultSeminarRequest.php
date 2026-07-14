<?php

namespace App\Http\Requests;

use App\Models\ApplicationResultSeminar;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateApplicationResultSeminarRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('application_result_seminar_edit');
    }

    public function rules()
    {
        $allowedResults = array_keys(ApplicationResultSeminar::allResultLabels());

        return [
            'application_id' => [
                'required',
                'exists:applications,id',
            ],
            'result' => [
                'required',
                'in:' . implode(',', $allowedResults),
            ],
            'note' => [
                'nullable',
                'string',
            ],
            'meeting_recording_link' => [
                'nullable',
                'url',
                'max:500',
            ],
            'revision_deadline' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'form_document' => [
                'required',
                'array',
                'min:1',
            ],
            'form_document.*' => [
                'required',
                'string',
            ],
            'attendance_document' => [
                'required',
                'string',
            ],
            'latest_script' => [
                'required',
                'string',
            ],
            'documentation' => [
                'required',
                'array',
                'min:1',
            ],
            'documentation.*' => [
                'required',
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [
            'application_id.required' => 'Aplikasi skripsi wajib diisi',
            'application_id.exists' => 'Aplikasi skripsi tidak valid',
            'result.required' => 'Hasil seminar wajib diisi',
            'form_document.required' => 'Form Review Kelayakan Proposal MBKM Riset wajib diupload',
            'form_document.min' => 'Form Review Kelayakan Proposal MBKM Riset minimal 1 file',
            'attendance_document.required' => 'Presensi Peserta wajib diupload',
            'documentation.required' => 'Dokumentasi Seminar wajib diupload',
            'documentation.min' => 'Dokumentasi Seminar minimal 1 file',
            'latest_script.required' => 'Naskah Proposal MBKM hasil revisi wajib diupload',
        ];
    }
}
