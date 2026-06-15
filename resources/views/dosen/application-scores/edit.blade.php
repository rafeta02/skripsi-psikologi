@extends('layouts.dosen')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, var(--dosen-primary) 0%, var(--dosen-secondary) 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <h2 class="mb-1 text-white font-weight-bold">
                        <i class="fas fa-clipboard-check mr-2"></i> Penilaian Sidang Skripsi
                    </h2>
                    <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                        Isi komponen penilaian (skala 0–100 per aspek)
                    </p>
                </div>
            </div>
        </div>
    </div>

    @php
        $app = $applicationScore->application
            ?? $applicationScore->application_result_defence?->application;
        $mahasiswa = $app->mahasiswa ?? null;
        $defense = $app->skripsiDefense ?? null;
    @endphp

    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern">
                <div class="card-modern-body">
                    <h5 class="font-weight-bold mb-3">Informasi Mahasiswa</h5>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label class="text-muted mb-1">Nama</label>
                            <p class="font-weight-semibold mb-0">{{ $mahasiswa->nama ?? '-' }}</p>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="text-muted mb-1">NIM</label>
                            <p class="font-weight-semibold mb-0">{{ $mahasiswa->nim ?? '-' }}</p>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="text-muted mb-1">Program Studi</label>
                            <p class="font-weight-semibold mb-0">{{ $mahasiswa->prodi->name ?? '-' }}</p>
                        </div>
                    </div>
                    @if($defense?->title)
                        <div class="mt-2">
                            <label class="text-muted mb-1">Judul</label>
                            <p class="font-weight-semibold mb-0">{{ $defense->title }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-modern">
                <div class="card-modern-body">
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
                            <a href="{{ route('dosen.scores') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Simpan Penilaian
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
