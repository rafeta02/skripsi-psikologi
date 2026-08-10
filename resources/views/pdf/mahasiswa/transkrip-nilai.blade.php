<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Nilai Skripsi</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.5cm 2cm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', 'DejaVu Serif', serif;
            font-size: 11pt;
            line-height: 1.35;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .page {
            page-break-inside: avoid;
        }

        .document-title {
            font-size: 13pt;
            font-weight: bold;
            text-align: center;
            margin: 0 0 0.6cm;
            text-transform: uppercase;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 0 0.5cm;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 5px 7px;
            vertical-align: top;
            text-align: left;
        }

        .table th {
            text-align: center;
            font-weight: bold;
        }

        .table td:first-child {
            width: 34%;
        }

        .examiner-list {
            margin: 0;
            padding-left: 1.2em;
            list-style-type: disc;
        }

        .examiner-list li {
            margin: 0 0 2px;
        }

        .note {
            font-size: 10pt;
            margin: 0.4cm 0 0.8cm;
            text-align: justify;
        }

        .signature-section {
            margin-top: 0.8cm;
            width: 100%;
            page-break-inside: avoid;
        }

        .signature-block {
            width: 45%;
            float: right;
            text-align: center;
        }

        .signature-title {
            margin-bottom: 2.2cm;
        }

        .signature-name {
            font-weight: bold;
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 180px;
            padding-bottom: 2px;
        }

        .signature-nip {
            margin-top: 4px;
            font-size: 10pt;
        }

        .clearfix {
            clear: both;
        }
    </style>
</head>
<body>
<div class="page">
    <div class="document-title">Nilai Skripsi</div>

    <table class="table">
        <thead>
            <tr>
                <th colspan="2">NILAI SKRIPSI</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>NAMA</td>
                <td><strong>{{ $mahasiswa->nama }}</strong></td>
            </tr>
            <tr>
                <td>NIM</td>
                <td>{{ $mahasiswa->nim }}</td>
            </tr>
            <tr>
                <td>JUDUL SKRIPSI</td>
                <td><strong>{{ $finalTitle ?? $defense?->title ?? ($application->skripsiRegistration->title ?? '-') }}</strong></td>
            </tr>
            <tr>
                <td>JUDUL SKRIPSI (EN)</td>
                <td><strong>{{ $finalTitleEn ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td>PEMBIMBING</td>
                <td>{{ $pembimbing?->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td>PENGUJI</td>
                <td>
                    @if($examiners->isNotEmpty())
                        <ul class="examiner-list">
                            @foreach($examiners as $examiner)
                                @if($examiner?->nama)
                                    <li>{{ $examiner->nama }}</li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td>TANGGAL SIDANG SKRIPSI</td>
                <td>{{ $defenseDate }}</td>
            </tr>
            <tr>
                <td>NILAI SKRIPSI</td>
                <td><strong>{{ number_format($averageScore, 2) }}</strong></td>
            </tr>
            <tr>
                <td>NILAI EAP</td>
                <td><strong>{{ $eapGrade ?? '-' }}</strong></td>
            </tr>
            <tr>
                <td>SKOR EAP</td>
                <td><strong>{{ isset($eapScore) ? $eapScore : '-' }}</strong></td>
            </tr>
        </tbody>
    </table>

    <p class="note">Dokumen ini diterbitkan sebagai bukti resmi nilai akhir skripsi mahasiswa.</p>

    <div class="signature-section">
        <div class="signature-block">
            <div class="signature-title">Surakarta, {{ $date }}</div>
            <div>Ketua Divisi Tugas Akhir</div>
            <div class="signature-name">[Nama Ketua Divisi Tugas Akhir]</div>
            <div class="signature-nip">NIK. [NIK Ketua Divisi Tugas Akhir]</div>
        </div>
    </div>

    <div class="clearfix"></div>
</div>
</body>
</html>
