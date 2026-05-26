<?php

namespace App\Http\Requests;

use App\Models\ApplicationReport;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreApplicationReportRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('application_report_create');
    }

    public function rules()
    {
        return [
            'application_id' => [
                'required',
                'exists:applications,id',
            ],
            'report_text' => [
                'required',
                'string',
            ],
            'report_document' => [
                'nullable',
                'array',
            ],
            'report_document.*' => [
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:10240',
            ],
            'period' => [
                'nullable',
                'string',
                'in:proposal,penelitian,sidang',
            ],
        ];
    }

    public function messages()
    {
        return [
            'report_text.required' => 'Uraian kendala wajib diisi.',
            'report_text.string' => 'Uraian kendala tidak valid.',
            'application_id.required' => 'Aplikasi wajib dipilih.',
            'application_id.exists' => 'Aplikasi tidak ditemukan.',
            'period.in' => 'Periode laporan tidak valid.',
            'report_document.*.mimes' => 'Bukti pendukung harus berformat PDF atau gambar.',
            'report_document.*.max' => 'Ukuran file maksimal 10MB.',
        ];
    }
}
