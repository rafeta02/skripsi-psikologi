<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>@yield('title', 'Document')</title>
    <style>
        @page {
            margin: 2.5cm 3cm;
            @if(isset($watermark))
            background-image: url("{{ $watermark }}");
            background-repeat: no-repeat;
            background-position: center center;
            @endif
        }

        body {
            font-family: 'Times New Roman', 'DejaVu Serif', serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 1.5cm;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
        }

        .header-logo {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }

        .header-title {
            font-size: 16pt;
            font-weight: bold;
            margin: 5px 0;
        }

        .header-subtitle {
            font-size: 12pt;
            margin: 3px 0;
        }

        .header-address {
            font-size: 10pt;
            margin: 3px 0;
        }

        .document-number {
            text-align: right;
            margin-bottom: 1cm;
            font-size: 11pt;
        }

        .document-title {
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
            margin: 1cm 0;
            text-transform: uppercase;
        }

        .document-subtitle {
            font-size: 12pt;
            text-align: center;
            margin-bottom: 1cm;
        }

        .content {
            text-align: justify;
            margin-bottom: 2cm;
        }

        .content p {
            margin: 10px 0;
        }

        .data-table {
            width: 100%;
            margin: 15px 0;
        }

        .data-table td {
            padding: 5px 10px;
            vertical-align: top;
        }

        .data-table td:first-child {
            width: 35%;
            font-weight: normal;
        }

        .data-table td:nth-child(2) {
            width: 5%;
            text-align: center;
        }

        .data-table td:last-child {
            width: 60%;
        }

        .signature-section {
            margin-top: 3cm;
            width: 100%;
        }

        .signature-block {
            display: inline-block;
            width: 48%;
            text-align: center;
            vertical-align: top;
            margin-bottom: 1cm;
        }

        .signature-block.left {
            float: left;
        }

        .signature-block.right {
            float: right;
        }

        .signature-block.full {
            width: 100%;
            float: none;
        }

        .signature-title {
            font-weight: normal;
            margin-bottom: 3cm;
        }

        .signature-name {
            font-weight: bold;
            margin-top: 0.5cm;
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 200px;
            padding-bottom: 2px;
        }

        .signature-nip {
            margin-top: 5px;
            font-size: 11pt;
        }

        .stamp-area {
            position: absolute;
            right: 3cm;
            bottom: 8cm;
            width: 150px;
            height: 150px;
            border: 2px dashed #ccc;
            text-align: center;
            padding-top: 60px;
            font-size: 10pt;
            color: #999;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10pt;
            border-top: 1px solid #000;
            padding-top: 10px;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100pt;
            color: rgba(200, 200, 200, 0.2);
            font-weight: bold;
            z-index: -1;
        }

        /* Table styling */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-bold {
            font-weight: bold;
        }

        .mt-1 { margin-top: 0.5cm; }
        .mt-2 { margin-top: 1cm; }
        .mt-3 { margin-top: 1.5cm; }
        .mt-4 { margin-top: 2cm; }

        .mb-1 { margin-bottom: 0.5cm; }
        .mb-2 { margin-bottom: 1cm; }
        .mb-3 { margin-bottom: 1.5cm; }
        .mb-4 { margin-bottom: 2cm; }

        /* Page break */
        .page-break {
            page-break-after: always;
        }

        .no-page-break {
            page-break-inside: avoid;
        }
    </style>
    @stack('styles')
</head>
<body>
    @if(isset($watermark) && $watermark)
    <div class="watermark">{{ $watermark }}</div>
    @endif

    <div class="header">
        @if(isset($logo) && $logo)
        <img src="{{ $logo }}" alt="Logo" class="header-logo">
        @endif
        <div class="header-title">UNIVERSITAS SEBELAS MARET</div>
        <div class="header-subtitle">FAKULTAS PSIKOLOGI</div>
        <div class="header-subtitle">PROGRAM STUDI PSIKOLOGI</div>
        <div class="header-address">
            Jl. Ir. Sutami No 36A, Kentingan, Kecamatan Jebres, Kota Surakarta, Jawa Tengah 57126 | Telp: (0271) 646994 | Email: sebelasmaret@mail.uns.ac.id
        </div>
    </div>

    @if(isset($documentNumber))
    <div class="document-number">
        Nomor: {{ $documentNumber }}
    </div>
    @endif

    @yield('content')

    @if(isset($pageNumber) && $pageNumber)
    <div class="footer">
        Halaman @pageNumber dari @pageCount
    </div>
    @endif

    @stack('scripts')
</body>
</html>
