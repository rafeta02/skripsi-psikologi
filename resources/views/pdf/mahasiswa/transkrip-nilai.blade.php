@extends('pdf.layouts.base')

@section('title', 'Nilai Skripsi')

@section('content')
<div class="document-title">
    NILAI SKRIPSI
</div>

<div class="document-subtitle">
    {{ $documentNumber }}
</div>

<div class="content">
    <table class="table" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th colspan="2" style="text-align: center; padding: 8px; border: 1px solid #000;">NILAI SKRIPSI</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width: 35%; padding: 8px; border: 1px solid #000;">NAMA</td>
                <td style="padding: 8px; border: 1px solid #000;"><strong>{{ $mahasiswa->nama }}</strong></td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #000;">NIM</td>
                <td style="padding: 8px; border: 1px solid #000;">{{ $mahasiswa->nim }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #000;">JUDUL SKRIPSI</td>
                <td style="padding: 8px; border: 1px solid #000;"><strong>{{ $finalTitle ?? $defense?->title ?? ($application->skripsiRegistration->proposal_title_1 ?? '-') }}</strong></td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #000;">PEMBIMBING</td>
                <td style="padding: 8px; border: 1px solid #000;">{{ $pembimbing?->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #000;">PENGUJI</td>
                <td style="padding: 8px; border: 1px solid #000;">
                    @if($examiners->isNotEmpty())
                        {{ $examiners->pluck('nama')->filter()->implode(' / ') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #000;">TANGGAL SIDANG SKRIPSI</td>
                <td style="padding: 8px; border: 1px solid #000;">{{ $defenseDate }}</td>
            </tr>
                
            <tr>
                <td style="padding: 8px; border: 1px solid #000;">NILAI SKRIPSI</td>
                <td style="padding: 8px; border: 1px solid #000;"><strong>{{ number_format($averageScore, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <p class="mt-3">Dokumen ini diterbitkan sebagai bukti resmi nilai akhir skripsi mahasiswa.</p>
</div>

<div class="signature-section mt-4">
    <div class="signature-block right">
        <div class="signature-title">Surakarta, {{ $date }}</div>
        <div class="signature-title mt-2">Ketua Ajir Skripsi</div>
        <div class="signature-name mt-4">
            [Nama Koordinator Skripsi]
        </div>
        <div class="signature-nip">NIK. [NIK Koordinator]</div>
    </div>
</div>

<div style="clear: both;"></div>

@endsection
