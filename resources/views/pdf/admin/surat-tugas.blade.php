@extends('pdf.layouts.base')

@section('title', 'Surat Tugas ' . $roleText)

@section('content')
<div class="document-title">
    SURAT TUGAS
</div>

<div class="document-subtitle">
    {{ $documentNumber }}
</div>

<div class="content">
    <p>Yang bertanda tangan di bawah ini:</p>

    <table class="data-table">
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td>[Nama Ketua Program Studi]</td>
        </tr>
        <tr>
            <td>NIP/NIDN</td>
            <td>:</td>
            <td>[NIP Kaprodi]</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>Ketua Program Studi Psikologi</td>
        </tr>
    </table>

    <p>Dengan ini menugaskan:</p>

    <table class="data-table">
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td><strong>{{ $dosen->nama }}</strong></td>
        </tr>
        <tr>
            <td>NIP/NIDN</td>
            <td>:</td>
            <td>{{ $dosen->nip ?? $dosen->nidn }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>{{ $dosen->jabatan ?? 'Dosen' }}</td>
        </tr>
        <tr>
            <td>Bidang Keilmuan</td>
            <td>:</td>
            <td>{{ $dosen->keilmuan->name ?? '-' }}</td>
        </tr>
    </table>

    <p>Untuk menjadi <strong>{{ $roleText }}</strong> dalam penyusunan skripsi mahasiswa:</p>

    <table class="data-table">
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td>{{ $mahasiswa->nama }}</td>
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
            <td>Jenis Skripsi</td>
            <td>:</td>
            <td>{{ $application->type == 'mbkm' ? 'MBKM' : 'Reguler' }}</td>
        </tr>
        <tr>
            <td>Judul</td>
            <td>:</td>
            <td>
                @if($application->type == 'skripsi')
                    {{ $application->skripsiRegistration->proposal_title_1 ?? '-' }}
                @else
                    {{ $application->mbkmRegistration->proposal_title_1 ?? '-' }}
                @endif
            </td>
        </tr>
    </table>

    <p>Surat tugas ini berlaku sejak tanggal ditetapkan sampai dengan selesainya proses bimbingan/ujian skripsi mahasiswa yang bersangkutan.</p>

    <p>Demikian surat tugas ini dibuat untuk dapat dilaksanakan dengan penuh tanggung jawab.</p>
</div>

<div class="signature-section">
    <div class="signature-block right">
        <div class="signature-title">Ditetapkan di : [Kota]</div>
        <div class="signature-title">Pada tanggal : {{ $date }}</div>
        <div class="signature-title mt-1">Ketua Program Studi</div>
        <div class="signature-name mt-4">
            [Nama Kaprodi]
        </div>
        <div class="signature-nip">NIP. [NIP Kaprodi]</div>
    </div>
</div>

@if(isset($stamp))
<div class="stamp-area">
    (Cap/Stempel)
</div>
@endif

<div style="clear: both;"></div>

<div class="mt-4" style="font-size: 10pt; color: #666;">
    <p><em>Catatan:</em></p>
    <ul>
        <li>Surat tugas ini diterbitkan secara resmi oleh Program Studi Psikologi</li>
        <li>Mohon untuk melaksanakan tugas dengan sebaik-baiknya</li>
        <li>Untuk informasi lebih lanjut, hubungi sekretariat Program Studi</li>
    </ul>
</div>
@endsection
