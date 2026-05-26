@extends('layouts.mahasiswa')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, #8e44ad 0%, #3498db 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <h2 class="mb-1 text-white font-weight-bold">
                        <i class="fas fa-calendar-plus mr-2"></i> Buat Jadwal
                    </h2>
                    <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                        Buat jadwal seminar MBKM atau sidang skripsi
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-lg-12">
            @if(!$activeApplication)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> <strong>Perhatian!</strong>
                    <p class="mb-0 mt-2">
                        @if($defenseScheduleAccess['message'] ?? null)
                            {{ $defenseScheduleAccess['message'] }}
                        @else
                            Anda belum memiliki aplikasi yang disetujui untuk dijadwalkan. Pastikan pendaftaran sidang sudah <strong>diterima admin</strong> terlebih dahulu.
                        @endif
                    </p>
                </div>
                <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            @else
                <div class="card-modern">
                    <div class="card-modern-body">
                        <form action="{{ route('frontend.application-schedules.store') }}" method="POST">
                            @csrf
                            
                            <input type="hidden" name="application_id" value="{{ $activeApplication->id }}">

                            <!-- Application Info -->
                            <div class="alert alert-info mb-4">
                                <h6 class="font-weight-bold mb-2">Informasi Aplikasi:</h6>
                                <p class="mb-1"><strong>Tipe:</strong> {{ strtoupper($activeApplication->type) }}</p>
                                <p class="mb-0"><strong>Tahap:</strong> {{ ucfirst($activeApplication->stage) }}</p>
                            </div>

                            <!-- Schedule Type -->
                            <div class="form-group">
                                <label class="form-label-modern required">Jenis Acara</label>
                                <select name="schedule_type" class="form-control-modern @error('schedule_type') is-invalid @enderror" required>
                                    <option value="">-- Pilih Jenis Acara --</option>
                                    @if($activeApplication->type === 'mbkm' && $activeApplication->stage === 'seminar')
                                        <option value="mbkm_seminar" selected>Seminar MBKM</option>
                                    @endif
                                    @if($activeApplication->type === 'skripsi' && $activeApplication->stage === 'seminar')
                                        <option value="skripsi_seminar" selected>Seminar Proposal Skripsi</option>
                                    @endif
                                    @if($activeApplication->stage === 'defense')
                                        <option value="skripsi_defense" selected>Sidang Skripsi</option>
                                    @endif
                                </select>
                                @error('schedule_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date & Time -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label-modern required">Tanggal</label>
                                        <input type="date" name="date" class="form-control-modern @error('waktu') is-invalid @enderror" value="{{ old('date') }}" required min="{{ date('Y-m-d') }}">
                                        @error('waktu')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label-modern required">Waktu</label>
                                        <input type="time" name="time" class="form-control-modern @error('waktu') is-invalid @enderror" value="{{ old('time') }}" required>
                                        <small class="form-text text-muted">Format 24 jam (contoh: 14:00)</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Location Type -->
                            <div class="form-group">
                                <label class="form-label-modern required">Lokasi Pelaksanaan</label>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="locationOffline" name="location_type" class="custom-control-input" value="offline" checked>
                                    <label class="custom-control-label" for="locationOffline">
                                        <i class="fas fa-building"></i> Offline (Ruangan)
                                    </label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="locationOnline" name="location_type" class="custom-control-input" value="online">
                                    <label class="custom-control-label" for="locationOnline">
                                        <i class="fas fa-video"></i> Online (Virtual Meeting)
                                    </label>
                                </div>
                            </div>

                            <!-- Room (for offline) -->
                            <div class="form-group" id="roomField">
                                <label class="form-label-modern required">Ruangan</label>
                                <select name="ruang_id" class="form-control-modern @error('ruang_id') is-invalid @enderror">
                                    <option value="">-- Pilih Ruangan --</option>
                                    @foreach($ruangs as $id => $name)
                                        @if($id)
                                            <option value="{{ $id }}" {{ old('ruang_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('ruang_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Online Link (for online) -->
                            <div class="form-group" id="onlineField" style="display: none;">
                                <label class="form-label-modern required">Link Meeting Online</label>
                                <input type="url" name="online_link" class="form-control-modern @error('online_link') is-invalid @enderror" value="{{ old('online_link') }}" placeholder="https://zoom.us/j/...">
                                <small class="form-text text-muted">Masukkan link Zoom, Google Meet, atau platform meeting lainnya</small>
                                @error('online_link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="form-group">
                                <label class="form-label-modern">Catatan Tambahan</label>
                                <textarea name="notes" class="form-control-modern @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                                <small class="form-text text-muted">Informasi tambahan yang perlu disampaikan (opsional)</small>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('frontend.application-schedules.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-calendar-check"></i> Buat Jadwal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle room/online link fields
    $('input[name="location_type"]').on('change', function() {
        if ($(this).val() === 'online') {
            $('#roomField').hide().find('select').prop('required', false);
            $('#onlineField').show().find('input').prop('required', true);
        } else {
            $('#roomField').show().find('select').prop('required', true);
            $('#onlineField').hide().find('input').prop('required', false);
        }
    });
    
    // Combine date and time into waktu field before submit
    $('form').on('submit', function(e) {
        const date = $('input[name="date"]').val();
        const time = $('input[name="time"]').val();
        
        if (date && time) {
            // Create hidden field for waktu (datetime)
            $('<input>').attr({
                type: 'hidden',
                name: 'waktu',
                value: date + ' ' + time + ':00'
            }).appendTo($(this));
        }
    });
});
</script>
@endpush
@endsection
