<?php

namespace App\Http\Requests;

use App\Models\ApplicationResultDefense;
use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationResultDefenseRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('application_result_defense_create');
    }

    public function rules()
    {
        if ($this->hasFile('minutes_document')) {
            return $this->directUploadRules();
        }

        return $this->dropzoneRules();
    }

    protected function directUploadRules(): array
    {
        $rules = [
            'application_id' => ['required', 'exists:applications,id'],
            'final_title' => ['required', 'string', 'max:500'],
            'final_title_en' => ['nullable', 'string', 'max:500'],
            'result' => ['required', 'in:' . implode(',', array_keys(ApplicationResultDefense::RESULT_SELECT))],
            'note' => ['nullable', 'string', 'max:5000'],
            'revision_deadline' => ['nullable', 'date'],
            'title_change_form' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'minutes_document' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'latest_script' => ['required', 'file', 'mimes:pdf', 'max:20480'],
            'documentation' => ['required', 'array', 'min:1'],
            'documentation.*' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'approval_page' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'invitation_document' => ['required', 'array', 'min:1'],
            'invitation_document.*' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'feedback_document' => ['required', 'array', 'min:1'],
            'feedback_document.*' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'revision_approval_sheet' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ];

        if ($this->input('result') === 'revision') {
            $rules['revision_deadline'] = ['required', 'date'];
            $rules['revision_approval_sheet'] = ['required', 'file', 'mimes:pdf', 'max:10240'];
        }

        return $rules;
    }

    protected function dropzoneRules(): array
    {
        $rules = [
            'application_id' => ['required', 'exists:applications,id'],
            'final_title' => ['required', 'string', 'max:500'],
            'final_title_en' => ['nullable', 'string', 'max:500'],
            'result' => ['required', 'in:' . implode(',', array_keys(ApplicationResultDefense::RESULT_SELECT))],
            'note' => ['nullable', 'string', 'max:5000'],
            'revision_deadline' => ['nullable', 'date_format:' . config('panel.date_format')],
            'title_change_form' => ['nullable', 'string'],
            'minutes_document' => ['required', 'string'],
            'latest_script' => ['required', 'string'],
            'documentation' => ['required', 'array', 'min:1'],
            'approval_page' => ['required', 'string'],
            'invitation_document' => ['required', 'array', 'min:1'],
            'feedback_document' => ['required', 'array', 'min:1'],
            'revision_approval_sheet' => ['nullable', 'string'],
        ];

        if ($this->input('result') === 'revision') {
            $rules['revision_deadline'] = ['required', 'date_format:' . config('panel.date_format')];
            $rules['revision_approval_sheet'] = ['required', 'string'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'application_id.required' => 'Application ID wajib diisi',
            'application_id.exists' => 'Application tidak ditemukan',
            'final_title.required' => 'Judul akhir skripsi wajib diisi',
            'result.required' => 'Hasil sidang wajib dipilih',
            'result.in' => 'Hasil sidang tidak valid',
            'minutes_document.required' => 'Berita acara dan lampirannya wajib diupload',
            'latest_script.required' => 'Naskah skripsi yang telah direvisi dan disahkan wajib diupload',
            'documentation.required' => 'Dokumentasi sidang wajib diupload',
            'documentation.min' => 'Minimal upload 1 dokumentasi sidang',
            'approval_page.required' => 'Lembar pengesahan wajib diupload',
            'invitation_document.required' => 'Berkas undangan sidang wajib diupload',
            'invitation_document.min' => 'Minimal upload 1 berkas undangan sidang',
            'feedback_document.required' => 'Umpan balik sidang wajib diupload',
            'feedback_document.min' => 'Minimal upload 1 umpan balik sidang',
            'revision_approval_sheet.required' => 'Lembar persetujuan hasil revisi wajib diupload jika lulus dengan revisi',
            'revision_deadline.required' => 'Batas waktu revisi wajib diisi jika lulus dengan revisi',
            'note.max' => 'Catatan maksimal 5000 karakter',
        ];
    }
}
