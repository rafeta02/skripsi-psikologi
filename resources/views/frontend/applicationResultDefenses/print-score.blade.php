<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Nilai Sidang — {{ $applicationResultDefense->application?->mahasiswa?->nama ?? 'Mahasiswa' }}</title>
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }

        .print-toolbar {
            max-width: 960px;
            margin: 0 auto 16px;
            display: flex;
            gap: 8px;
        }

        .print-toolbar button,
        .print-toolbar a {
            padding: 8px 16px;
            border: 1px solid #ccc;
            background: #fff;
            cursor: pointer;
            text-decoration: none;
            color: #333;
            border-radius: 4px;
            font-size: 14px;
        }

        .print-toolbar .btn-print {
            background: #28a745;
            color: #fff;
            border-color: #28a745;
        }

        .page {
            max-width: 960px;
            margin: 0 auto;
            background: #fff;
            padding: 24px;
            border: 1px solid #ddd;
        }

        h1 {
            font-size: 16pt;
            text-align: center;
            margin: 0 0 16px;
            text-transform: uppercase;
        }

        .info-table,
        .score-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .info-table th,
        .info-table td,
        .score-table th,
        .score-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
        }

        .info-table th {
            width: 28%;
            font-weight: normal;
            text-align: left;
        }

        .score-table th {
            font-size: 9pt;
            text-align: center;
            background: #f0f0f0;
        }

        .score-table td {
            text-align: center;
            font-size: 10pt;
        }

        .score-table td.text-left {
            text-align: left;
        }

        .score-table tfoot th,
        .score-table tfoot td {
            font-weight: bold;
            background: #f8f8f8;
        }

        .final-box {
            border: 2px solid #000;
            padding: 12px 16px;
            margin: 16px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .final-score {
            font-size: 22pt;
            font-weight: bold;
        }

        .final-grade {
            font-size: 20pt;
            font-weight: bold;
            padding: 4px 14px;
            border: 2px solid #000;
        }

        .section-title {
            font-size: 12pt;
            font-weight: bold;
            margin: 18px 0 8px;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .print-toolbar {
                display: none !important;
            }

            .page {
                border: none;
                padding: 0;
                max-width: none;
            }
        }
    </style>
</head>
<body>
@php
    $mahasiswa = $applicationResultDefense->application?->mahasiswa;
    $componentLabels = \App\Models\ApplicationScore::scoreComponentLabels();
    $componentKeys = \App\Models\ApplicationScore::scoreComponentKeys();
    $scores = $applicationResultDefense->scores->filter(fn ($s) => $s->score !== null);
    $componentAverages = [];

    foreach ($componentKeys as $key) {
        $values = $scores->pluck($key)->filter(fn ($v) => $v !== null);
        $componentAverages[$key] = $values->count() > 0 ? round($values->avg(), 2) : null;
    }

    $resultLabels = \App\Models\ApplicationResultDefense::RESULT_SELECT;
@endphp

<div class="print-toolbar">
    <button type="button" class="btn-print" onclick="window.print()">🖨️ Print</button>
    <a href="javascript:history.back()">← Kembali</a>
</div>

<div class="page">
    <h1>Rekap Nilai Sidang Skripsi</h1>

    <table class="info-table">
        <tr>
            <th>Nama Mahasiswa</th>
            <td><strong>{{ $mahasiswa->nama ?? '-' }}</strong></td>
        </tr>
        <tr>
            <th>NIM</th>
            <td>{{ $mahasiswa->nim ?? '-' }}</td>
        </tr>
        <tr>
            <th>Program Studi</th>
            <td>{{ $mahasiswa->prodi->name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Hasil Sidang</th>
            <td>{{ $resultLabels[$applicationResultDefense->result] ?? $applicationResultDefense->result ?? '-' }}</td>
        </tr>
        <tr>
            <th>Judul Skripsi</th>
            <td>{{ $applicationResultDefense->final_title ?: '-' }}</td>
        </tr>
        @if($applicationResultDefense->final_title_en)
        <tr>
            <th>Judul Skripsi (EN)</th>
            <td>{{ $applicationResultDefense->final_title_en }}</td>
        </tr>
        @endif
    </table>

    <div class="section-title">Nilai per Dosen Penilai</div>

    @if($scores->isEmpty())
        <p>Belum ada data penilaian.</p>
    @else
        <table class="score-table">
            <thead>
                <tr>
                    <th style="width: 18%;">Dosen</th>
                    @foreach($componentLabels as $label)
                        <th>{{ $label }}</th>
                    @endforeach
                    <th style="width: 7%;">Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($scores as $score)
                    <tr>
                        <td class="text-left">
                            <strong>{{ $score->examiner->nama ?? '-' }}</strong>
                            @if($score->examiner?->nidn)
                                <br><small>NIDN: {{ $score->examiner->nidn }}</small>
                            @endif
                        </td>
                        @foreach($componentKeys as $key)
                            <td>{{ $score->{$key} ?? '-' }}</td>
                        @endforeach
                        <td><strong>{{ number_format($score->score, 2) }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-left">Rata-rata</th>
                    @foreach($componentKeys as $key)
                        <td>
                            @if(($componentAverages[$key] ?? null) !== null)
                                {{ number_format($componentAverages[$key], 2) }}
                            @else
                                -
                            @endif
                        </td>
                    @endforeach
                    <td><strong>{{ number_format($applicationResultDefense->final_score, 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>
    @endif

    <div class="final-box">
        <div>
            <div><strong>Nilai Akhir Sidang</strong></div>
            <div class="final-score">{{ number_format($applicationResultDefense->final_score, 2) }}</div>
        </div>
        <div style="text-align: right;">
            <div><strong>Nilai Huruf</strong></div>
            <div class="final-grade">{{ $applicationResultDefense->final_grade_letter }}</div>
            <div style="font-size: 10pt; margin-top: 4px;">
                {{ \App\Models\ApplicationResultDefense::getGradeDescription($applicationResultDefense->final_grade_letter) }}
            </div>
        </div>
    </div>

    <p style="font-size: 10pt; color: #555; margin-top: 24px;">
        Dicetak pada {{ now()->format('d/m/Y H:i') }} — Dokumen ini dihasilkan dari sistem SIM Skripsi.
    </p>
</div>
</body>
</html>
