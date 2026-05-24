@extends('pdf.layouts.base')

@section('title', 'Lembar Penilaian')

@section('content')
<div class="document-title">
    LEMBAR PENILAIAN
</div>

<div class="document-subtitle">
    {{ $assessmentType }}
</div>

<div class="content">
    <table class="data-table mb-3">
        <tr>
            <td>Nama Mahasiswa</td>
            <td>:</td>
            <td><strong>{{ $mahasiswa->nama }}</strong></td>
        </tr>
        <tr>
            <td>NIM</td>
            <td>:</td>
            <td>{{ $mahasiswa->nim }}</td>
        </tr>
        <tr>
            <td>Program Studi</td>
            <td>:</td>
            <td>{{ $prodi->name }}</td>
        </tr>
        <tr>
            <td>Judul</td>
            <td>:</td>
            <td>
                @if($application->type == 'skripsi')
                    {{ $application->skripsiRegistration->proposal_title_1 ?? $application->skripsiDefense->title ?? '-' }}
                @else
                    {{ $application->mbkmRegistration->proposal_title_1 ?? $application->skripsiDefense->title ?? '-' }}
                @endif
            </td>
        </tr>
    </table>

    <table class="data-table mb-3">
        <tr>
            <td>Penilai</td>
            <td>:</td>
            <td><strong>{{ $examiner->nama }}</strong></td>
        </tr>
        <tr>
            <td>NIP/NIDN</td>
            <td>:</td>
            <td>{{ $examiner->nip ?? $examiner->nidn }}</td>
        </tr>
        <tr>
            <td>Tanggal Penilaian</td>
            <td>:</td>
            <td>{{ $date }}</td>
        </tr>
    </table>

    <p><strong>KOMPONEN PENILAIAN:</strong></p>

    <table class="table">
        <thead>
            <tr>
                <th style="width: 10%;">No</th>
                <th style="width: 50%;">Komponen</th>
                <th style="width: 15%;">Bobot</th>
                <th style="width: 25%;">Nilai</th>
            </tr>
        </thead>
        <tbody>
            @if($score->content_score)
            <tr>
                <td class="text-center">1</td>
                <td>Isi/Substansi Penelitian<br>
                    <small style="font-size: 9pt;">
                        - Kesesuaian topik dengan bidang studi<br>
                        - Kejelasan rumusan masalah<br>
                        - Kelengkapan tinjauan pustaka<br>
                        - Kedalaman analisis
                    </small>
                </td>
                <td class="text-center">30%</td>
                <td class="text-center text-bold">{{ $score->content_score }}</td>
            </tr>
            @endif

            @if($score->methodology_score)
            <tr>
                <td class="text-center">2</td>
                <td>Metodologi Penelitian<br>
                    <small style="font-size: 9pt;">
                        - Ketepatan metode penelitian<br>
                        - Kesesuaian teknik pengumpulan data<br>
                        - Validitas dan reliabilitas instrumen<br>
                        - Ketepatan analisis data
                    </small>
                </td>
                <td class="text-center">25%</td>
                <td class="text-center text-bold">{{ $score->methodology_score }}</td>
            </tr>
            @endif

            @if($score->presentation_score)
            <tr>
                <td class="text-center">3</td>
                <td>Presentasi<br>
                    <small style="font-size: 9pt;">
                        - Kemampuan komunikasi<br>
                        - Penggunaan media presentasi<br>
                        - Penguasaan materi<br>
                        - Penampilan dan sikap
                    </small>
                </td>
                <td class="text-center">25%</td>
                <td class="text-center text-bold">{{ $score->presentation_score }}</td>
            </tr>
            @endif

            @if($score->qa_score)
            <tr>
                <td class="text-center">4</td>
                <td>Tanya Jawab<br>
                    <small style="font-size: 9pt;">
                        - Kemampuan menjawab pertanyaan<br>
                        - Argumentasi yang logis<br>
                        - Pemahaman terhadap penelitian<br>
                        - Sikap dan etika
                    </small>
                </td>
                <td class="text-center">20%</td>
                <td class="text-center text-bold">{{ $score->qa_score }}</td>
            </tr>
            @endif

            <tr style="background-color: #f0f0f0; font-weight: bold;">
                <td colspan="3" class="text-center">NILAI AKHIR</td>
                <td class="text-center" style="font-size: 14pt;">
                    {{ $score->overall_score }}
                </td>
            </tr>
        </tbody>
    </table>

    @if($score->grade_letter)
    <table class="data-table mt-3">
        <tr>
            <td>Nilai Huruf</td>
            <td>:</td>
            <td><strong style="font-size: 14pt;">{{ $score->grade_letter }}</strong></td>
        </tr>
    </table>
    @endif

    @if($score->comments)
    <div class="mt-3">
        <p><strong>KOMENTAR DAN MASUKAN:</strong></p>
        <div style="border: 1px solid #000; padding: 15px; min-height: 3cm; text-align: justify;">
            {{ $score->comments }}
        </div>
    </div>
    @endif

    @if($score->recommendation)
    <div class="mt-3">
        <p><strong>REKOMENDASI:</strong></p>
        <p style="font-size: 11pt;">
            @if($score->recommendation == 'passed')
                ✓ <strong>LULUS</strong> - Mahasiswa dapat melanjutkan ke tahap berikutnya
            @elseif($score->recommendation == 'passed_with_revision')
                ⚠ <strong>LULUS DENGAN REVISI</strong> - Mahasiswa perlu melakukan perbaikan sesuai masukan
            @else
                ✗ <strong>TIDAK LULUS</strong> - Mahasiswa perlu mengulang dengan perbaikan menyeluruh
            @endif
        </p>
    </div>
    @endif
</div>

<div class="signature-section mt-4">
    <div class="signature-block full">
        <div class="signature-title">Penilai,</div>
        <div class="signature-name mt-4">
            {{ $examiner->nama }}
        </div>
        <div class="signature-nip">NIP. {{ $examiner->nip ?? $examiner->nidn }}</div>
    </div>
</div>

<div class="mt-4" style="font-size: 10pt; color: #666; border-top: 1px solid #ccc; padding-top: 10px;">
    <p><strong>Catatan:</strong></p>
    <ul style="margin-left: 20px;">
        <li>Nilai dinyatakan dalam skala 0-100</li>
        <li>Nilai minimal kelulusan adalah 60 (C)</li>
        <li>Lembar penilaian ini merupakan dokumen resmi dan wajib disimpan</li>
    </ul>
</div>

<div class="mt-2" style="text-align: center; font-size: 10pt; font-style: italic;">
    Dokumen ini dibuat secara elektronik dan sah tanpa tanda tangan basah
</div>
@endsection
