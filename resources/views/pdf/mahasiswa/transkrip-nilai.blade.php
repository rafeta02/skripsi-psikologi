@extends('pdf.layouts.base')

@section('title', 'Transkrip Nilai Skripsi')

@section('content')
<div class="document-title">
    TRANSKRIP NILAI SKRIPSI
</div>

<div class="document-subtitle">
    {{ $documentNumber }}
</div>

<div class="content">
    <p style="text-align: center; margin-bottom: 1.5cm;">
        <strong>DATA MAHASISWA</strong>
    </p>

    <table class="data-table mb-3">
        <tr>
            <td>Nama Lengkap</td>
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

    <div class="mt-4 mb-3">
        <p><strong>INFORMASI SKRIPSI</strong></p>
        
        <table class="data-table">
            <tr>
                <td>Judul Skripsi</td>
                <td>:</td>
                <td><strong>{{ $defense ? $defense->title : ($application->skripsiRegistration->proposal_title_1 ?? '-') }}</strong></td>
            </tr>
            <tr>
                <td>Jenis</td>
                <td>:</td>
                <td>{{ $application->type == 'mbkm' ? 'MBKM' : 'Reguler' }}</td>
            </tr>
            <tr>
                <td>Pembimbing</td>
                <td>:</td>
                <td>{{ $pembimbing ? $pembimbing->nama : '-' }} @if($pembimbing) (NIP: {{ $pembimbing->nip ?? $pembimbing->nidn }}) @endif</td>
            </tr>
            <tr>
                <td>Tanggal Lulus</td>
                <td>:</td>
                <td>{{ $graduationDate }}</td>
            </tr>
        </table>
    </div>

    <div class="mt-4">
        <p><strong>RINCIAN PENILAIAN</strong></p>
        
        @if($scores->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 10%;">No</th>
                    <th style="width: 40%;">Tahap Penilaian</th>
                    <th style="width: 25%;">Penilai</th>
                    <th style="width: 15%;">Nilai</th>
                    <th style="width: 10%;">Huruf</th>
                </tr>
            </thead>
            <tbody>
                @foreach($scores as $index => $score)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        @if($loop->first && $scores->count() > 1)
                            Penilaian Seminar Proposal
                        @elseif($loop->last)
                            Penilaian Sidang Skripsi
                        @else
                            Penilaian {{ $index + 1 }}
                        @endif
                    </td>
                    <td>{{ $score->examiner->nama ?? '-' }}</td>
                    <td class="text-center">{{ number_format($score->overall_score, 2) }}</td>
                    <td class="text-center"><strong>{{ $score->grade_letter }}</strong></td>
                </tr>
                @endforeach
                
                <tr style="background-color: #f0f0f0; font-weight: bold; border-top: 2px solid #000;">
                    <td colspan="3" class="text-right">NILAI AKHIR SKRIPSI:</td>
                    <td class="text-center" style="font-size: 14pt;">{{ number_format($averageScore, 2) }}</td>
                    <td class="text-center" style="font-size: 14pt;">{{ $finalGrade }}</td>
                </tr>
            </tbody>
        </table>
        @else
        <p style="text-align: center; padding: 20px; background-color: #f9f9f9;">
            <em>Belum ada penilaian yang tercatat</em>
        </p>
        @endif
    </div>

    @if($averageScore >= 85)
    <div class="mt-3" style="background-color: #fffacd; padding: 15px; border: 2px solid #ffd700; border-radius: 5px; text-align: center;">
        <p style="margin: 0; font-weight: bold; font-size: 14pt;">
            ⭐ PREDIKAT: CUM LAUDE ⭐
        </p>
        <p style="margin: 5px 0 0 0; font-size: 10pt;">
            (Nilai Akhir ≥ 85)
        </p>
    </div>
    @elseif($averageScore >= 75)
    <div class="mt-3" style="background-color: #e6f3ff; padding: 15px; border: 2px solid #4CAF50; border-radius: 5px; text-align: center;">
        <p style="margin: 0; font-weight: bold; font-size: 14pt;">
            ✓ PREDIKAT: SANGAT MEMUASKAN
        </p>
        <p style="margin: 5px 0 0 0; font-size: 10pt;">
            (Nilai Akhir 75-84)
        </p>
    </div>
    @elseif($averageScore >= 60)
    <div class="mt-3" style="background-color: #f0f0f0; padding: 15px; border: 2px solid #2196F3; border-radius: 5px; text-align: center;">
        <p style="margin: 0; font-weight: bold; font-size: 14pt;">
            ✓ PREDIKAT: MEMUASKAN
        </p>
        <p style="margin: 5px 0 0 0; font-size: 10pt;">
            (Nilai Akhir 60-74)
        </p>
    </div>
    @endif

    <div class="mt-4">
        <p><strong>KETERANGAN NILAI:</strong></p>
        <table style="width: 100%; font-size: 10pt;">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <table>
                        <tr><td>A</td><td>:</td><td>85 - 100</td><td>(Istimewa)</td></tr>
                        <tr><td>A-</td><td>:</td><td>80 - 84</td><td>(Sangat Baik)</td></tr>
                        <tr><td>B+</td><td>:</td><td>75 - 79</td><td>(Baik Sekali)</td></tr>
                        <tr><td>B</td><td>:</td><td>70 - 74</td><td>(Baik)</td></tr>
                        <tr><td>B-</td><td>:</td><td>65 - 69</td><td>(Cukup Baik)</td></tr>
                    </table>
                </td>
                <td style="width: 50%; vertical-align: top;">
                    <table>
                        <tr><td>C+</td><td>:</td><td>60 - 64</td><td>(Cukup)</td></tr>
                        <tr><td>C</td><td>:</td><td>55 - 59</td><td>(Kurang)</td></tr>
                        <tr><td>D</td><td>:</td><td>50 - 54</td><td>(Kurang Sekali)</td></tr>
                        <tr><td>E</td><td>:</td><td>0 - 49</td><td>(Gagal)</td></tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <p class="mt-3">Transkrip nilai ini diterbitkan sebagai bukti resmi penyelesaian studi skripsi dan dapat digunakan untuk keperluan akademik.</p>
</div>

<div class="signature-section mt-4">
    <div class="signature-block right">
        <div class="signature-title">Dikeluarkan di : [Kota]</div>
        <div class="signature-title">Tanggal : {{ $date }}</div>
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
    (Cap/Stempel Prodi)
</div>
@endif

<div class="mt-4" style="text-align: center; font-size: 10pt; font-style: italic; border-top: 1px solid #ccc; padding-top: 10px;">
    Dokumen ini dibuat secara elektronik dan sah tanpa tanda tangan basah
</div>
@endsection
