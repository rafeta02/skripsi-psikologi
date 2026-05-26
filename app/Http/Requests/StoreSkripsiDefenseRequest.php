<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreSkripsiDefenseRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('skripsi_defense_create');
    }

    public function rules()
    {
        if ($this->hasFile('defence_document')) {
            return $this->directUploadRules();
        }

        return $this->dropzoneRules();
    }

    protected function directUploadRules(): array
    {
        $pdf = 'file|mimes:pdf|max:20480';
        $pdf10 = 'file|mimes:pdf|max:10240';
        $screenshot = 'file|mimes:pdf,jpg,jpeg,png|max:10240';

        return [
            'application_id' => ['required', 'exists:applications,id'],
            'title' => ['required', 'string'],
            'abstract' => ['required', 'string'],
            'defence_document' => ['required', $pdf],
            'plagiarism_report' => ['required', $pdf10],
            'publication_statement' => ['required', $pdf10],
            'spp_receipt' => ['required', $pdf10],
            'krs_latest' => ['required', $pdf10],
            'eap_certificate' => ['required', $pdf10],
            'transcript' => ['required', $pdf10],
            'siakad_supervisor_screenshot' => ['required', $screenshot],
            'mbkm_recommendation_letter' => ['nullable', $pdf10],
            'ethics_statement' => ['required', 'array', 'min:1'],
            'ethics_statement.*' => ['required', $pdf10],
            'research_instruments' => ['required', 'array', 'min:1'],
            'research_instruments.*' => ['required', $pdf10],
            'data_collection_letter' => ['required', 'array', 'min:1'],
            'data_collection_letter.*' => ['required', $pdf10],
            'research_module' => ['required', 'array', 'min:1'],
            'research_module.*' => ['required', $pdf10],
            'defense_approval_page' => ['required', 'array', 'min:1'],
            'defense_approval_page.*' => ['required', $pdf10],
            'research_poster' => ['required', 'array', 'min:1'],
            'research_poster.*' => ['required', $pdf10],
            'supervision_logbook' => ['required', 'array', 'min:1'],
            'supervision_logbook.*' => ['required', $pdf10],
            'mbkm_report' => ['nullable', 'array'],
            'mbkm_report.*' => ['nullable', $pdf10],
        ];
    }

    protected function dropzoneRules(): array
    {
        return [
            'application_id' => ['required', 'exists:applications,id'],
            'title' => ['required', 'string'],
            'abstract' => ['required', 'string'],
            'defence_document' => ['required'],
            'plagiarism_report' => ['required'],
            'publication_statement' => ['required'],
            'spp_receipt' => ['required'],
            'krs_latest' => ['required'],
            'eap_certificate' => ['required'],
            'transcript' => ['required'],
            'siakad_supervisor_screenshot' => ['required'],
            'mbkm_recommendation_letter' => ['nullable'],
            'ethics_statement' => ['required', 'array', 'min:1'],
            'research_instruments' => ['required', 'array', 'min:1'],
            'data_collection_letter' => ['required', 'array', 'min:1'],
            'research_module' => ['required', 'array', 'min:1'],
            'defense_approval_page' => ['required', 'array', 'min:1'],
            'research_poster' => ['required', 'array', 'min:1'],
            'supervision_logbook' => ['required', 'array', 'min:1'],
            'mbkm_report' => ['nullable', 'array'],
        ];
    }

    public function messages()
    {
        return [
            'application_id.required' => 'Aplikasi skripsi harus dipilih',
            'title.required' => 'Judul skripsi harus diisi',
            'abstract.required' => 'Abstrak skripsi harus diisi',
            'defence_document.required' => 'Dokumen sidang harus diupload',
            'plagiarism_report.required' => 'Laporan plagiarisme harus diupload',
            'ethics_statement.required' => 'Pernyataan etika penelitian harus diupload minimal 1 file',
            'research_instruments.required' => 'Instrumen penelitian harus diupload minimal 1 file',
            'data_collection_letter.required' => 'Surat izin pengumpulan data harus diupload minimal 1 file',
            'research_module.required' => 'Modul penelitian harus diupload minimal 1 file',
            'publication_statement.required' => 'Pernyataan publikasi harus diupload',
            'defense_approval_page.required' => 'Halaman persetujuan sidang harus diupload minimal 1 file',
            'spp_receipt.required' => 'Bukti pembayaran SPP harus diupload',
            'krs_latest.required' => 'KRS terbaru harus diupload',
            'eap_certificate.required' => 'Sertifikat EAP harus diupload',
            'transcript.required' => 'Transkrip nilai harus diupload',
            'research_poster.required' => 'Poster penelitian harus diupload minimal 1 file',
            'siakad_supervisor_screenshot.required' => 'Screenshot pembimbing SIAKAD harus diupload',
            'supervision_logbook.required' => 'Logbook bimbingan harus diupload minimal 1 file',
            '*.mimes' => 'File harus berformat PDF (kecuali screenshot SIAKAD: PDF/gambar).',
            '*.max' => 'Ukuran file melebihi batas yang diizinkan.',
        ];
    }
}
