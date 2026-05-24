@extends('pdf.layouts.base')

@section('title', 'Berita Acara Seminar Proposal')

@section('content')
<div class="document-title">
    BERITA ACARA SEMINAR PROPOSAL SKRIPSI
</div>

<div class="document-subtitle">
    {{ $documentNumber }}
</div>

<div class="content">
    <p>Pada hari ini, <strong>{{ $schedule ? \Carbon\Carbon::parse($schedule->scheduled_date)->isoFormat('dddd, D MMMM YYYY') : '[Tanggal Seminar]' }}</strong>, 
    pukul <strong>{{ $schedule ? \Carbon\Carbon::parse($schedule->scheduled_time)->format('H:i') : '[Waktu]' }} WIB</strong>, 
    bertempat di <strong>{{ $schedule ? ($schedule->location == 'online' ? $schedule->meeting_link : $schedule->ruang->name) : '[Lokasi]' }}</strong>, 
    telah dilaksanakan seminar proposal skripsi dengan data sebagai berikut:</p>

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
            <td>Judul Proposal</td>
            <td>:</td>
            <td><strong>{{ $seminar->title }}</strong></td>
        </tr>
    </table>

    <p><strong>Tim Penilai:</strong></p>
    
    <table class="data-table mb-3">
        <tr>
            <td>Pembimbing</td>
            <td>:</td>
            <td>{{ $pembimbing ? $pembimbing->nama : '-' }} (NIP: {{ $pembimbing ? ($pembimbing->nip ?? $pembimbing->nidn) : '-' }})</td>
        </tr>
        @foreach($reviewers as $index => $reviewer)
        <tr>
            <td>Reviewer {{ $index + 1 }}</td>
            <td>:</td>
            <td>{{ $reviewer->nama }} (NIP: {{ $reviewer->nip ?? $reviewer->nidn }})</td>
        </tr>
        @endforeach
    </table>

    @if($result)
    <p><strong>Hasil Seminar:</strong></p>
    
    <table class="data-table mb-3">
        <tr>
            <td>Keputusan</td>
            <td>:</td>
            <td>
                <strong>
                @if($result->result == 'passed')
                    ✓ LULUS
                @elseif($result->result == 'revision')
                    ⚠ LULUS DENGAN REVISI
                @else
                    ✗ TIDAK LULUS
                @endif
                </strong>
            </td>
        </tr>
        @if($result->result == 'revision' && $result->revision_deadline)
        <tr>
            <td>Batas Waktu Revisi</td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($result->revision_deadline)->isoFormat('D MMMM YYYY') }}</td>
        </tr>
        @endif
        @if($result->reviewer_notes)
        <tr>
            <td>Catatan</td>
            <td>:</td>
            <td>{{ $result->reviewer_notes }}</td>
        </tr>
        @endif
    </table>
    @endif

    <p><strong>Ringkasan Diskusi:</strong></p>
    <p style="text-align: justify;">
        {{ $seminar->description ?? 'Mahasiswa mempresentasikan proposal penelitiannya dengan baik. Tim penilai memberikan masukan dan saran untuk penyempurnaan proposal.' }}
    </p>

    @if($result && $result->result == 'revision')
    <p><strong>Catatan Revisi:</strong></p>
    <ul>
        <li>Mahasiswa diwajibkan melakukan revisi sesuai masukan tim penilai</li>
        <li>Revisi harus diselesaikan sebelum batas waktu yang ditentukan</li>
        <li>Konsultasikan hasil revisi dengan pembimbing</li>
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
    @foreach($reviewers as $index => $reviewer)
    <div class="signature-block" style="width: 48%; float: {{ $index % 2 == 0 ? 'left' : 'right' }};">
        <div class="signature-title">Reviewer {{ $index + 1 }}</div>
        <div class="signature-name mt-4">
            {{ $reviewer->nama }}
        </div>
        <div class="signature-nip">NIP. {{ $reviewer->nip ?? $reviewer->nidn }}</div>
    </div>
    @if($index % 2 == 1)
    <div style="clear: both;"></div>
    @endif
    @endforeach
</div>

<div style="clear: both;"></div>

<div class="mt-4" style="text-align: center; font-size: 10pt; font-style: italic;">
    Dokumen ini dibuat secara elektronik dan sah tanpa tanda tangan basah
</div>
@endsection
