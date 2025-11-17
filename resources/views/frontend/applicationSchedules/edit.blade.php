@extends('layouts.frontend')
@section('content')
<link rel="stylesheet" href="{{ asset('css/modern-form.css') }}">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="form-card">
                <div class="form-header">
                    <h2>
                        Edit Jadwal 
                        @if($applicationSchedule->schedule_type === 'defense')
                            Sidang Skripsi
                        @elseif($applicationSchedule->schedule_type === 'seminar')
                            Seminar Proposal
                        @else
                            Seminar/Sidang
                        @endif
                    </h2>
                    <p>Perbarui jadwal seminar proposal atau sidang skripsi Anda</p>
                </div>

                <form method="POST" action="{{ route("frontend.application-schedules.update", [$applicationSchedule->id]) }}" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    
                    <div class="form-body">
                        <div class="info-box warning">
                            <div class="info-box-title">Mode Edit</div>
                            <div class="info-box-text">
                                Pastikan perubahan jadwal telah dikonfirmasi dengan dosen pembimbing dan penguji sebelum menyimpan.
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="application_id">Aplikasi Skripsi <span class="required">*</span></label>
                            <select class="form-control select2" name="application_id" id="application_id" required>
                                <option value="">-- Pilih Aplikasi --</option>
                                @foreach($applications as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('application_id') ? old('application_id') : $applicationSchedule->application->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('application'))
                                <span class="text-danger small">{{ $errors->first('application') }}</span>
                            @endif
                            <span class="help-block">Pilih aplikasi skripsi yang akan dijadwalkan</span>
                        </div>

                        <div class="form-group">
                            <label for="schedule_type">Tipe Jadwal <span class="required">*</span></label>
                            <select class="form-control" name="schedule_type" id="schedule_type" required>
                                <option value="">-- Pilih Tipe --</option>
                                @foreach(App\Models\ApplicationSchedule::SCHEDULE_TYPE_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ (old('schedule_type') ? old('schedule_type') : $applicationSchedule->schedule_type ?? '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('schedule_type'))
                                <span class="text-danger small">{{ $errors->first('schedule_type') }}</span>
                            @endif
                            <span class="help-block">Pilih jenis acara yang akan dijadwalkan</span>
                        </div>

                        <div class="form-group">
                            <label for="waktu">Waktu Pelaksanaan <span class="required">*</span></label>
                            <input class="form-control datetime" type="text" name="waktu" id="waktu" value="{{ old('waktu', $applicationSchedule->waktu) }}" required>
                            @if($errors->has('waktu'))
                                <span class="text-danger small">{{ $errors->first('waktu') }}</span>
                            @endif
                            <span class="help-block">Tanggal dan waktu pelaksanaan</span>
                        </div>

                        <div class="form-group">
                            <label for="ruang_id">Ruangan</label>
                            <select class="form-control select2" name="ruang_id" id="ruang_id">
                                <option value="">-- Pilih Ruangan --</option>
                                @foreach($ruangs as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('ruang_id') ? old('ruang_id') : $applicationSchedule->ruang->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('ruang'))
                                <span class="text-danger small">{{ $errors->first('ruang') }}</span>
                            @endif
                            <span class="help-block">Pilih ruangan jika pelaksanaan offline</span>
                        </div>

                        <div class="form-group">
                            <label for="custom_place">Tempat Lain</label>
                            <input class="form-control" type="text" name="custom_place" id="custom_place" value="{{ old('custom_place', $applicationSchedule->custom_place) }}" placeholder="Contoh: Lab Psikologi Lt. 2">
                            @if($errors->has('custom_place'))
                                <span class="text-danger small">{{ $errors->first('custom_place') }}</span>
                            @endif
                            <span class="help-block">Isi jika tempat tidak ada di daftar ruangan</span>
                        </div>

                        <div class="form-group">
                            <label for="online_meeting">Link Meeting Online</label>
                            <input class="form-control" type="text" name="online_meeting" id="online_meeting" value="{{ old('online_meeting', $applicationSchedule->online_meeting) }}" placeholder="https://zoom.us/j/...">
                            @if($errors->has('online_meeting'))
                                <span class="text-danger small">{{ $errors->first('online_meeting') }}</span>
                            @endif
                            <span class="help-block">Link Zoom/Google Meet jika pelaksanaan online</span>
                        </div>

                        <div class="form-group">
                            <label for="note">Catatan</label>
                            <textarea class="form-control" name="note" id="note" rows="3" placeholder="Catatan tambahan...">{{ old('note', $applicationSchedule->note) }}</textarea>
                            @if($errors->has('note'))
                                <span class="text-danger small">{{ $errors->first('note') }}</span>
                            @endif
                            <span class="help-block">Informasi tambahan tentang jadwal</span>
                        </div>

                        <div class="form-group">
                            <label for="approval_form">Form Persetujuan</label>
                            <div class="needsclick dropzone" id="approval_form-dropzone">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #cbd5e0;"></i>
                                    <p>Klik atau seret file ke sini</p>
                                    <small>PDF form persetujuan dari dosen (maksimal 10 MB)</small>
                                </div>
                            </div>
                            @if($errors->has('approval_form'))
                                <span class="text-danger small">{{ $errors->first('approval_form') }}</span>
                            @endif
                            <span class="help-block">Upload form persetujuan jadwal dari dosen pembimbing/penguji</span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('frontend.application-schedules.index') }}" class="btn-back">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var uploadedApprovalFormMap = {}
Dropzone.options.approvalFormDropzone = {
    url: '{{ route('frontend.application-schedules.storeMedia') }}',
    maxFilesize: 10,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="approval_form[]" value="' + response.name + '">')
      uploadedApprovalFormMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedApprovalFormMap[file.name]
      }
      $('form').find('input[name="approval_form[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($applicationSchedule) && $applicationSchedule->approval_form)
          var files = {!! json_encode($applicationSchedule->approval_form) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="approval_form[]" value="' + file.file_name + '">')
            }
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }
         return _results
     }
}
</script>
@endsection