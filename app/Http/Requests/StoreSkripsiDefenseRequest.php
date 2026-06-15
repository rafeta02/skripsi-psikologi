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
        return [
            'application_id' => ['required', 'exists:applications,id'],
            'title' => ['required', 'string'],
            'abstract' => ['required', 'string'],
            // Single file (PDF)
            'defence_document' => ['required', 'file', 'mimes:pdf', 'max:20480'],
            'plagiarism_report' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'publication_statement' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'signed_scientific_publication_statement' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'spp_receipt' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'krs_latest' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'eap_certificate' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'transcript' => ['required', 'file', 'mimes:pdf', 'max:10240'],

            // Screenshot (PDF or image)
            'siakad_supervisor_screenshot' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],

            // Optional single PDF
            'mbkm_recommendation_letter' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],

            // Multi file: each item is a PDF
            'ethics_statement' => ['required', 'array', 'min:1'],
            'ethics_statement.*' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'research_instruments' => ['required', 'array', 'min:1'],
            'research_instruments.*' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'data_collection_letter' => ['required', 'array', 'min:1'],
            'data_collection_letter.*' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'research_module' => ['nullable', 'array'],
            'research_module.*' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'defense_approval_page' => ['required', 'array', 'min:1'],
            'defense_approval_page.*' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'research_poster' => ['required', 'array', 'min:1'],
            'research_poster.*' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'supervision_logbook' => ['required', 'array', 'min:1'],
            'supervision_logbook.*' => ['required', 'file', 'mimes:pdf', 'max:10240'],

            // Optional multi PDF
            'mbkm_report' => ['nullable', 'array'],
            'mbkm_report.*' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
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
            'signed_scientific_publication_statement' => ['required'],
            'spp_receipt' => ['required'],
            'krs_latest' => ['required'],
            'eap_certificate' => ['required'],
            'transcript' => ['required'],
            'siakad_supervisor_screenshot' => ['required'],
            'mbkm_recommendation_letter' => ['nullable'],
            'ethics_statement' => ['required', 'array', 'min:1'],
            'research_instruments' => ['required', 'array', 'min:1'],
            'data_collection_letter' => ['required', 'array', 'min:1'],
            'research_module' => ['nullable', 'array'],
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
            'data_collection_letter.required' => 'Surat Keterangan Telah Melaksanakan Penelitian / Informed consent harus diupload minimal 1 file',
            'research_module.*.mimes' => 'Modul penelitian harus berformat PDF.',
            'publication_statement.required' => 'Pernyataan publikasi harus diupload',
            'signed_scientific_publication_statement.required' => 'Surat Pernyataan Publikasi Ilmiah sudah ditanda tangani harus diupload',
            'defense_approval_page.required' => 'Halaman persetujuan sidang harus diupload minimal 1 file',
            'spp_receipt.required' => 'Bukti pembayaran SPP harus diupload',
            'krs_latest.required' => 'KRS terbaru harus diupload',
            'eap_certificate.required' => 'Sertifikat EAP yang sudah dilegalisir harus diupload',
            'transcript.required' => 'Transkrip nilai harus diupload',
            'research_poster.required' => 'Poster penelitian harus diupload minimal 1 file',
            'siakad_supervisor_screenshot.required' => 'Screenshot pembimbing SIAKAD harus diupload',
            'supervision_logbook.required' => 'Tangkapan layar panel konsultasi Siakad (minimal 12 kali) harus diupload minimal 1 file',
            '*.mimes' => 'File harus berformat PDF (kecuali screenshot SIAKAD: PDF/gambar).',
            '*.max' => 'Ukuran file melebihi batas yang diizinkan.',
        ];
    }
}
