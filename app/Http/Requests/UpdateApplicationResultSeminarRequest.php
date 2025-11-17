<?php

namespace App\Http\Requests;

use App\Models\ApplicationResultSeminar;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateApplicationResultSeminarRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('application_result_seminar_edit');
    }

    public function rules()
    {
        return [
            'application_id' => [
                'required',
                'exists:applications,id',
            ],
            'result' => [
                'required',
                'string',
            ],
            'note' => [
                'nullable',
                'string',
            ],
            'revision_deadline' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'report_document' => [
                'required',
                'array',
                'min:1',
            ],
            'report_document.*' => [
                'required',
                'string',
            ],
            'attendance_document' => [
                'required',
                'string',
            ],
            'form_document' => [
                'array',
                'nullable',
            ],
            'form_document.*' => [
                'string',
            ],
            'latest_script' => [
                'nullable',
                'string',
            ],
            'documentation' => [
                'array',
                'nullable',
            ],
            'documentation.*' => [
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
            'report_document.required' => 'Berita acara seminar wajib diupload',
            'report_document.min' => 'Berita acara seminar minimal 1 file',
            'attendance_document.required' => 'Daftar hadir wajib diupload',
        ];
    }
}
