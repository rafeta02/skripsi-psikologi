<?php

namespace App\Http\Requests;

use App\Models\SkripsiDefense;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSkripsiDefenseRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('skripsi_defense_create');
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
            ],
            'abstract' => [
                'required',
                'string',
            ],
            // Required single file documents
            'defence_document' => [
                'required',
            ],
            'plagiarism_report' => [
                'required',
            ],
            'publication_statement' => [
                'required',
            ],
            'spp_receipt' => [
                'required',
            ],
            'krs_latest' => [
                'required',
            ],
            'eap_certificate' => [
                'required',
            ],
            'transcript' => [
                'required',
            ],
            'siakad_supervisor_screenshot' => [
                'required',
            ],
            // Optional single file documents
            'mbkm_recommendation_letter' => [
                'nullable',
            ],
            // Required multiple file documents
            'ethics_statement' => [
                'required',
                'array',
                'min:1',
            ],
            'research_instruments' => [
                'required',
                'array',
                'min:1',
            ],
            'data_collection_letter' => [
                'required',
                'array',
                'min:1',
            ],
            'research_module' => [
                'required',
                'array',
                'min:1',
            ],
            'defense_approval_page' => [
                'required',
                'array',
                'min:1',
            ],
            'research_poster' => [
                'required',
                'array',
                'min:1',
            ],
            'supervision_logbook' => [
                'required',
                'array',
                'min:1',
            ],
            // Optional multiple file documents
            'mbkm_report' => [
                'nullable',
                'array',
            ],
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
        ];
    }
}
