@extends('pdf.layouts.base')

@section('title', 'Surat Keterangan Lulus')

@section('content')
<div class="document-title">
    SURAT KETERANGAN LULUS
</div>

<div class="document-subtitle">
    {{ $documentNumber }}
</div>

<div class="content">
    <p>Yang bertanda tangan di bawah ini:</p>

    <table class="data-table mb-3">
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
        <tr>
            <td>Fakultas</td>
            <td>:</td>
            <td>{{ $prodi->faculty->name ?? 'Fakultas Psikologi' }}</td>
        </tr>
    </table>

    <p>Dengan ini menerangkan bahwa:</p>

    <table class="data-table mb-3">
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td><strong>{{ $mahasiswa->nama }}</strong></td>
        </tr>
        <tr>
            <td>NIM</td>
            <td>:</td>
            <td>{{ $mahasiswa->nim }}</td>
        </tr>
        <tr>
            <td>Tempat/Tanggal Lahir</td>
            <td>:</td>
            <td>{{ $mahasiswa->tempat_lahir ?? '[Tempat]' }}, {{ $mahasiswa->tanggal_lahir ? \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->isoFormat('D MMMM YYYY') : '[Tanggal Lahir]' }}</td>
        </tr>
        <tr>
            <td>Program Studi</td>
            <td>:</td>
            <td>{{ $prodi->name }}</td>
        </tr>
        <tr>
            <td>Fakultas</td>
            <td>:</td>
            <td>{{ $prodi->faculty->name ?? 'Fakultas Psikologi' }}</td>
        </tr>
        <tr>
            <td>Angkatan</td>
            <td>:</td>
            <td>{{ $mahasiswa->angkatan }}</td>
        </tr>
    </table>

    <p>Telah <strong>LULUS</strong> Ujian Sidang Skripsi pada:</p>

    <table class="data-table mb-3">
        <tr>
            <td>Tanggal Sidang</td>
            <td>:</td>
            <td>{{ $graduationDate }}</td>
        </tr>
        <tr>
            <td>Judul Skripsi</td>
            <td>:</td>
            <td><strong>{{ $defense ? $defense->title : '-' }}</strong></td>
        </tr>
        <tr>
            <td>Jenis Skripsi</td>
            <td>:</td>
            <td>{{ $application->type == 'mbkm' ? 'MBKM' : 'Reguler' }}</td>
        </tr>
    </table>

    @if($finalScore)
    <table class="data-table mb-3">
        <tr>
            <td>Nilai Akhir</td>
            <td>:</td>
            <td><strong style="font-size: 14pt;">{{ number_format($finalScore->overall_score, 2) }} ({{ $finalScore->grade_letter }})</strong></td>
        </tr>
        @if($finalScore->overall_score >= 85)
        <tr>
            <td>Predikat</td>
            <td>:</td>
            <td><strong style="color: #d4af37;">⭐ CUM LAUDE ⭐</strong></td>
        </tr>
        @elseif($finalScore->overall_score >= 75)
        <tr>
            <td>Predikat</td>
            <td>:</td>
            <td><strong style="color: #4CAF50;">SANGAT MEMUASKAN</strong></td>
        </tr>
        @elseif($finalScore->overall_score >= 60)
        <tr>
            <td>Predikat</td>
            <td>:</td>
            <td><strong style="color: #2196F3;">MEMUASKAN</strong></td>
        </tr>
        @endif
    </table>
    @endif

    @if($defenseResult)
    <table class="data-table mb-3">
        <tr>
            <td>Status Kelulusan</td>
            <td>:</td>
            <td>
                <strong>
                @if($defenseResult->result == 'passed')
                    ✓ LULUS TANPA REVISI
                @elseif($defenseResult->result == 'passed_with_revision')
                    ✓ LULUS DENGAN REVISI
                    @if($defenseResult->revision_deadline)
                        <br><small>(Revisi selesai: {{ \Carbon\Carbon::parse($defenseResult->revision_deadline)->isoFormat('D MMMM YYYY') }})</small>
                    @endif
                @else
                    {{ strtoupper($defenseResult->result) }}
                @endif
                </strong>
            </td>
        </tr>
    </table>
    @endif

    <p class="mt-3">Surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>

    <div class="mt-3" style="background-color: #f9f9f9; padding: 15px; border-left: 4px solid var(--primary-500, #7F00FF);">
        <p style="margin: 0; font-size: 11pt;"><strong>Catatan:</strong></p>
        <ul style="margin: 5px 0 0 20px; font-size: 10pt;">
            <li>Surat keterangan ini berlaku untuk keperluan administrasi akademik</li>
            <li>Mahasiswa berhak mengambil ijazah setelah menyelesaikan semua persyaratan</li>
            <li>Untuk informasi lebih lanjut, hubungi sekretariat Program Studi</li>
        </ul>
    </div>
</div>

<div class="signature-section mt-4">
    <div class="signature-block right">
        <div class="signature-title">Ditetapkan di : [Kota]</div>
        <div class="signature-title">Pada tanggal : {{ $date }}</div>
        <div class="signature-title mt-2">Ketua Program Studi Psikologi</div>
        <div class="signature-name mt-4">
            [Nama Kaprodi]
        </div>
        <div class="signature-nip">NIP. [NIP Kaprodi]</div>
    </div>
</div>

<div style="clear: both;"></div>

@if(isset($stamp))
<div class="stamp-area">
    (Cap/Stempel Fakultas)
</div>
@endif

<div class="mt-4" style="font-size: 10pt; color: #666; border-top: 2px solid #000; padding-top: 10px;">
    <p style="text-align: center; font-style: italic;">
        "Dengan rahmat Tuhan Yang Maha Esa"
    </p>
    <p style="text-align: center; margin-top: 10px;">
        <strong>SELAMAT!</strong><br>
        Anda telah menyelesaikan studi dengan baik.<br>
        Semoga ilmu yang diperoleh bermanfaat untuk masyarakat dan bangsa.
    </p>
</div>

<div class="mt-3" style="text-align: center; font-size: 10pt; font-style: italic;">
    Dokumen ini dibuat secara elektronik dan sah tanpa tanda tangan basah
</div>
@endsection
