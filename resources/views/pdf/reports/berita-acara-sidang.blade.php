@extends('pdf.layouts.base')

@section('title', 'Berita Acara Sidang Skripsi')

@section('content')
<div class="document-title">
    BERITA ACARA SIDANG SKRIPSI
</div>

<div class="document-subtitle">
    {{ $documentNumber }}
</div>

<div class="content">
    <p>Pada hari ini, <strong>{{ $schedule ? \Carbon\Carbon::parse($schedule->scheduled_date)->isoFormat('dddd, D MMMM YYYY') : '[Tanggal Sidang]' }}</strong>, 
    pukul <strong>{{ $schedule ? \Carbon\Carbon::parse($schedule->scheduled_time)->format('H:i') : '[Waktu]' }} WIB</strong>, 
    bertempat di <strong>{{ $schedule ? ($schedule->location == 'online' ? $schedule->meeting_link : $schedule->ruang->name) : '[Lokasi]' }}</strong>, 
    telah dilaksanakan sidang skripsi dengan data sebagai berikut:</p>

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
            <td>Judul Skripsi</td>
            <td>:</td>
            <td><strong>{{ $defense->title }}</strong></td>
        </tr>
    </table>

    <p><strong>Tim Penguji:</strong></p>
    
    <table class="data-table mb-3">
        <tr>
            <td>Pembimbing</td>
            <td>:</td>
            <td>{{ $pembimbing ? $pembimbing->nama : '-' }} (NIP: {{ $pembimbing ? ($pembimbing->nip ?? $pembimbing->nidn) : '-' }})</td>
        </tr>
        @foreach($examiners as $index => $examiner)
        <tr>
            <td>Penguji {{ $index + 1 }}</td>
            <td>:</td>
            <td>{{ $examiner->nama }} (NIP: {{ $examiner->nip ?? $examiner->nidn }})</td>
        </tr>
        @endforeach
    </table>

    @if($finalScore || $result)
    <p><strong>Hasil Sidang:</strong></p>
    
    <table class="data-table mb-3">
        @if($finalScore)
        <tr>
            <td>Nilai Akhir</td>
            <td>:</td>
            <td><strong>{{ $finalScore->overall_score }} ({{ $finalScore->grade_letter }})</strong></td>
        </tr>
        @endif
        @if($result)
        <tr>
            <td>Keputusan</td>
            <td>:</td>
            <td>
                <strong>
                @if($result->result == 'passed')
                    ✓ LULUS
                @elseif($result->result == 'passed_with_revision')
                    ⚠ LULUS DENGAN REVISI
                @else
                    ✗ TIDAK LULUS
                @endif
                </strong>
            </td>
        </tr>
        @if($result->result == 'passed_with_revision' && $result->revision_deadline)
        <tr>
            <td>Batas Waktu Revisi</td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($result->revision_deadline)->isoFormat('D MMMM YYYY') }}</td>
        </tr>
        @endif
        @endif
    </table>
    @endif

    @if($finalScore && $finalScore->presentation_score)
    <p><strong>Rincian Penilaian:</strong></p>
    <table class="table">
        <thead>
            <tr>
                <th>Komponen</th>
                <th>Bobot</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            @if($finalScore->content_score)
            <tr>
                <td>Isi/Substansi</td>
                <td class="text-center">30%</td>
                <td class="text-center">{{ $finalScore->content_score }}</td>
            </tr>
            @endif
            @if($finalScore->methodology_score)
            <tr>
                <td>Metodologi</td>
                <td class="text-center">25%</td>
                <td class="text-center">{{ $finalScore->methodology_score }}</td>
            </tr>
            @endif
            @if($finalScore->presentation_score)
            <tr>
                <td>Presentasi</td>
                <td class="text-center">25%</td>
                <td class="text-center">{{ $finalScore->presentation_score }}</td>
            </tr>
            @endif
            @if($finalScore->qa_score)
            <tr>
                <td>Tanya Jawab</td>
                <td class="text-center">20%</td>
                <td class="text-center">{{ $finalScore->qa_score }}</td>
            </tr>
            @endif
            <tr style="font-weight: bold;">
                <td colspan="2" class="text-right">Nilai Akhir:</td>
                <td class="text-center">{{ $finalScore->overall_score }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <p><strong>Ringkasan Sidang:</strong></p>
    <p style="text-align: justify;">
        {{ $defense->notes ?? 'Mahasiswa mempresentasikan hasil penelitiannya dengan baik. Tim penguji memberikan apresiasi atas kerja keras mahasiswa dan hasil penelitian yang telah dicapai.' }}
    </p>

    @if($finalScore && $finalScore->comments)
    <p><strong>Komentar Tim Penguji:</strong></p>
    <p style="text-align: justify;">
        {{ $finalScore->comments }}
    </p>
    @endif

    @if($result && $result->result == 'passed_with_revision')
    <p><strong>Catatan Revisi:</strong></p>
    <ul>
        <li>Mahasiswa diwajibkan melakukan revisi sesuai masukan tim penguji</li>
        <li>Revisi harus diselesaikan sebelum batas waktu yang ditentukan</li>
        <li>Setelah revisi selesai, mahasiswa dapat mengambil ijazah</li>
    </ul>
    @endif

    <p class="mt-2">Demikian berita acara ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
</div>

<div class="signature-section mt-4">
    <div class="signature-block" style="width: 48%; float: left;">
        <div class="signature-title">Mahasiswa</div>
        <div class="signature-name mt-4">
            {{ $mahasiswa->nama }}
        </div>
        <div class="signature-nip">NIM. {{ $mahasiswa->nim }}</div>
    </div>

    <div class="signature-block" style="width: 48%; float: right;">
        <div class="signature-title">Pembimbing</div>
        <div class="signature-name mt-4">
            {{ $pembimbing ? $pembimbing->nama : '[Nama Pembimbing]' }}
        </div>
        <div class="signature-nip">NIP. {{ $pembimbing ? ($pembimbing->nip ?? $pembimbing->nidn) : '[NIP]' }}</div>
    </div>
</div>

<div style="clear: both;"></div>

<div class="signature-section mt-3">
    @foreach($examiners as $index => $examiner)
    <div class="signature-block" style="width: 48%; float: {{ $index % 2 == 0 ? 'left' : 'right' }};">
        <div class="signature-title">Penguji {{ $index + 1 }}</div>
        <div class="signature-name mt-4">
            {{ $examiner->nama }}
        </div>
        <div class="signature-nip">NIP. {{ $examiner->nip ?? $examiner->nidn }}</div>
    </div>
    @if($index % 2 == 1)
    <div style="clear: both;"></div>
    @endif
    @endforeach
</div>

<div style="clear: both;"></div>

<div class="mt-4" style="page-break-before: avoid;">
    <p style="font-size: 11pt;"><strong>Mengetahui,</strong></p>
    <div class="signature-block full mt-2">
        <div class="signature-title">Ketua Program Studi Psikologi</div>
        <div class="signature-name mt-4">
            [Nama Kaprodi]
        </div>
        <div class="signature-nip">NIP. [NIP Kaprodi]</div>
    </div>
</div>

<div class="mt-4" style="text-align: center; font-size: 10pt; font-style: italic;">
    Dokumen ini dibuat secara elektronik dan sah tanpa tanda tangan basah
</div>
@endsection
