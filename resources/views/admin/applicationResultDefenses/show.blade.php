@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.applicationResultDefense.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.application-result-defenses.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
                <a class="btn btn-success" href="{{ route('admin.application-result-defenses.print-score', $applicationResultDefense->id) }}" target="_blank">
                    <i class="fas fa-print"></i> {{ trans('global.print') }} Nilai
                </a>
            </div>

            @php
                $adminValidated = $applicationResultDefense->isValidatedByAdmin();
                $adminRejected = $applicationResultDefense->isRejectedByAdmin();
            @endphp

            <div class="row mb-4">
                <div class="col-md-8">
                    <h5 class="font-weight-bold mb-2">Status Validasi Admin</h5>
                    {!! $applicationResultDefense->adminValidationStatusHtml() !!}
                    @if($applicationResultDefense->application)
                        <p class="text-muted mt-2 mb-0">
                            Status aplikasi: <strong>{{ $applicationResultDefense->application->status }}</strong>
                        </p>
                    @endif
                </div>
                <div class="col-md-4">
                    @if(!$adminValidated && !$adminRejected)
                        <button type="button" class="btn btn-success btn-block mb-2" data-toggle="modal" data-target="#approveDefenseModal">
                            <i class="fas fa-check mr-1"></i> Validasi Laporan Hasil Sidang
                        </button>
                        <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#rejectDefenseModal">
                            <i class="fas fa-times mr-1"></i> Tolak Laporan
                        </button>
                    @elseif($adminValidated && in_array($applicationResultDefense->result, ['passed', 'revision']))
                        <div class="alert alert-success mb-0">
                            <i class="fas fa-users mr-1"></i>
                            Penilaian dikirim ke {{ $applicationResultDefense->scores->count() }} dosen.
                        </div>
                    @elseif($adminValidated && $applicationResultDefense->result === 'failed')
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-redo mr-1"></i>
                            Tidak ada penilaian dosen. Mahasiswa dapat mendaftar ulang <code>SkripsiDefense</code>.
                        </div>
                    @endif
                </div>
            </div>

            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationResultDefense.fields.application') }}
                        </th>
                        <td>
                            {{ $applicationResultDefense->application->status ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationResultDefense.fields.result') }}
                        </th>
                        <td>
                            {{ App\Models\ApplicationResultDefense::RESULT_SELECT[$applicationResultDefense->result] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationResultDefense.fields.note') }}
                        </th>
                        <td>
                            {{ $applicationResultDefense->note }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationResultDefense.fields.revision_deadline') }}
                        </th>
                        <td>
                            {{ $applicationResultDefense->revision_deadline }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.applicationResultDefense.fields.final_grade') }}
                        </th>
                        <td>
                            {{ $applicationResultDefense->final_grade }}
                        </td>
                    </tr>
                    @if($applicationResultDefense->scores->isNotEmpty())
                    <tr>
                        <th>
                            Nilai Akhir Sidang
                        </th>
                        <td>
                            <strong style="font-size: 18px;">{{ number_format($applicationResultDefense->final_score, 2) }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Nilai Huruf
                        </th>
                        <td>
                            <span style="display: inline-block; background: #667eea; color: white; padding: 5px 15px; border-radius: 5px; font-weight: bold; font-size: 16px;">
                                {{ $applicationResultDefense->final_grade_letter }}
                            </span>
                            <span style="color: #666; margin-left: 10px;">
                                {{ \App\Models\ApplicationResultDefense::getGradeDescription($applicationResultDefense->final_grade_letter) }}
                            </span>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th>Dokumen Hasil Sidang</th>
                        <td>
                            @include('partials.defense-result-documents', ['record' => $applicationResultDefense])
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.application-result-defenses.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
                <a class="btn btn-success" href="{{ route('admin.application-result-defenses.print-score', $applicationResultDefense->id) }}" target="_blank">
                    <i class="fas fa-print"></i> {{ trans('global.print') }} Nilai
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveDefenseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-check-circle mr-2"></i>Validasi Hasil Sidang</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="approveDefenseForm">
                <div class="modal-body">
                    <div class="alert alert-success">
                        Setelah divalidasi, form <strong>ApplicationScore</strong> akan tersedia untuk dosen pembimbing dan penguji sidang.
                    </div>
                    <div class="form-group">
                        <label for="approve_notes">Catatan (opsional)</label>
                        <textarea class="form-control" id="approve_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-check mr-1"></i> Setujui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectDefenseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-times-circle mr-2"></i>Tolak Laporan Hasil Sidang</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="rejectDefenseForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="reject_reason">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="reject_reason" name="reason" rows="4" required minlength="10"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-times mr-1"></i> Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@parent
<script>
$(document).ready(function() {
    $('#approveDefenseForm').on('submit', function(e) {
        e.preventDefault();
        const btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true);
        $.ajax({
            url: '{{ route("admin.application-result-defenses.approve", $applicationResultDefense->id) }}',
            method: 'POST',
            data: $(this).serialize(),
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res) {
                $('#approveDefenseModal').modal('hide');
                alert(res.message || 'Berhasil');
                window.location.reload();
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.message || 'Gagal memvalidasi');
            },
            complete: function() { btn.prop('disabled', false); }
        });
    });

    $('#rejectDefenseForm').on('submit', function(e) {
        e.preventDefault();
        const btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true);
        $.ajax({
            url: '{{ route("admin.application-result-defenses.reject", $applicationResultDefense->id) }}',
            method: 'POST',
            data: $(this).serialize(),
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res) {
                $('#rejectDefenseModal').modal('hide');
                alert(res.message || 'Ditolak');
                window.location.reload();
            },
            error: function(xhr) {
                let msg = xhr.responseJSON?.message || 'Gagal';
                if (xhr.responseJSON?.errors?.reason) msg = xhr.responseJSON.errors.reason[0];
                alert(msg);
            },
            complete: function() { btn.prop('disabled', false); }
        });
    });

});
</script>
@endsection