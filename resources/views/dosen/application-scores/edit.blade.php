@extends('layouts.dosen')

@section('content')
@include('partials.dosen.page-header', [
    'title' => 'Isi Penilaian Sidang',
    'subtitle' => 'Komponen penilaian skala 0–100 per aspek',
])

@php
    $app = $applicationScore->application
        ?? $applicationScore->application_result_defence?->application;
    $mahasiswa = $app->mahasiswa ?? null;
    $defense = $skripsiDefense ?? $app->skripsiDefense ?? null;
@endphp

<div class="mb-3">
    <a href="{{ route('dosen.scores') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Penilaian
    </a>
</div>

<div class="mhs-card mb-4">
    <div class="mhs-card-body">
        <h6 class="font-weight-bold mb-3">Informasi Mahasiswa</h6>
        <div class="row">
            <div class="col-md-4 mb-2">
                <small class="text-muted d-block">Nama</small>
                <strong>{{ $mahasiswa->nama ?? '-' }}</strong>
            </div>
            <div class="col-md-4 mb-2">
                <small class="text-muted d-block">NIM</small>
                <strong>{{ $mahasiswa->nim ?? '-' }}</strong>
            </div>
            <div class="col-md-4 mb-2">
                <small class="text-muted d-block">Program Studi</small>
                <strong>{{ $mahasiswa->prodi->name ?? '-' }}</strong>
            </div>
        </div>
        @if($defense?->title)
            <div class="mt-2">
                <small class="text-muted d-block">Judul</small>
                <strong>{{ $defense->title }}</strong>
            </div>
        @endif
    </div>
</div>

<div class="mhs-card">
    <div class="mhs-card-body">
                    <form action="{{ route('dosen.application-scores.update', $applicationScore) }}" method="POST" id="scoreForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            @include('partials.application-score-component-fields', [
                                'scoreRecord' => $applicationScore,
                                'colClass' => 'col-12',
                                'inputClass' => 'form-control-modern score-input',
                            ])
                        </div>

                        <div class="alert alert-light border mt-3">
                            <strong>Nilai rata-rata (otomatis):</strong>
                            <span id="avgPreview" class="text-primary font-weight-bold">-</span>
                        </div>

                        <div class="form-group mt-3">
                            <label class="form-label-modern" for="note">Catatan</label>
                            <textarea name="note" id="note" class="form-control-modern @error('note') is-invalid @enderror" rows="4">{{ old('note', $applicationScore->note) }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('dosen.scores') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Penilaian
                            </button>
                        </div>
                    </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function () {
    function updateAvg() {
        var inputs = $('.score-input');
        var sum = 0;
        var count = 0;
        inputs.each(function () {
            var v = parseFloat($(this).val());
            if (!isNaN(v)) {
                sum += v;
                count++;
            }
        });
        if (count === inputs.length) {
            $('#avgPreview').text((sum / count).toFixed(2));
        } else {
            $('#avgPreview').text('-');
        }
    }
    $('.score-input').on('input', updateAvg);
    updateAvg();
});
</script>
@endsection
