<?php

namespace App\Http\Requests;

use App\Models\ApplicationResultDefense;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreApplicationResultDefenseRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('application_result_defense_create');
    }

    public function rules()
    {
        return [
            // Required Fields
            'application_id' => [
                'required',
                'exists:applications,id',
            ],
            'result' => [
                'required',
                'in:' . implode(',', array_keys(ApplicationResultDefense::RESULT_SELECT)),
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
            
            // Optional Fields
            'note' => [
                'nullable',
                'string',
                'max:5000',
            ],
            'revision_deadline' => [
                'nullable',
                'date_format:' . config('panel.date_format'),
            ],
            'final_grade' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],
            
            // Optional Documents
            'form_document.*' => [
                'nullable',
                'string',
            ],
            'latest_script' => [
                'nullable',
                'string',
            ],
            'documentation' => [
                'nullable',
                'array',
            ],
            'documentation.*' => [
                'nullable',
                'string',
            ],
            'certificate_document' => [
                'nullable',
                'string',
            ],
            'publication_document' => [
                'nullable',
                'string',
            ],
        ];
    }
    
    public function messages()
    {
        return [
            'application_id.required' => 'Application ID wajib diisi',
            'application_id.exists' => 'Application tidak ditemukan',
            'result.required' => 'Hasil sidang wajib dipilih',
            'result.in' => 'Hasil sidang tidak valid',
            'report_document.required' => 'Berita acara sidang wajib diupload',
            'report_document.min' => 'Minimal upload 1 berita acara sidang',
            'attendance_document.required' => 'Daftar hadir wajib diupload',
            'note.max' => 'Catatan maksimal 5000 karakter',
            'final_grade.numeric' => 'Nilai harus berupa angka',
            'final_grade.min' => 'Nilai minimal 0',
            'final_grade.max' => 'Nilai maksimal 100',
        ];
    }
}
