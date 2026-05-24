@extends('layouts.admin')
@section('content')

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('admin.application-result-reviews.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Student Information Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-graduate mr-2"></i>Informasi Mahasiswa</h5>
                </div>
                <div class="card-body">
                    @if($applicationResultReview->application && $applicationResultReview->application->mahasiswa)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Nama Mahasiswa:</label>
                                    <p class="form-control-plaintext">{{ $applicationResultReview->application->mahasiswa->nama }}</p>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">NIM:</label>
                                    <p class="form-control-plaintext">{{ $applicationResultReview->application->mahasiswa->nim }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">Program Studi:</label>
                                    <p class="form-control-plaintext">
                                        {{ $applicationResultReview->application->mahasiswa->prodi->name ?? '-' }}
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Jenjang:</label>
                                    <p class="form-control-plaintext">
                                        {{ $applicationResultReview->application->mahasiswa->jenjang->name ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-muted">Data mahasiswa tidak tersedia</p>
                    @endif
                </div>
            </div>

            <!-- Result Review Details Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-file-check mr-2"></i>Detail Hasil Review</h5>
                </div>
    <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Hasil Review:</label>
                                <p class="form-control-plaintext">
                                    @if($applicationResultReview->result)
                                        @php
                                            $resultLabels = [
                                                'passed' => '<span class="badge badge-success badge-lg">Lulus</span>',
                                                'revision' => '<span class="badge badge-warning badge-lg">Revisi</span>',
                                                'failed' => '<span class="badge badge-danger badge-lg">Tidak Lulus</span>',
                                            ];
                                        @endphp
                                        {!! $resultLabels[$applicationResultReview->result] ?? $applicationResultReview->result !!}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
        <div class="form-group">
                                <label class="font-weight-bold">Batas Waktu Revisi:</label>
                                <p class="form-control-plaintext">
                                    {{ $applicationResultReview->revision_deadline ?? 'Tidak ada' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-file-pdf mr-2"></i>Dokumen</h5>
                </div>
                <div class="card-body">
                    <!-- Form Documents -->
                    <div class="form-group">
                        <label class="font-weight-bold">Form Penilaian:</label>
                        <div class="mt-2">
                            @if($applicationResultReview->form_document && count($applicationResultReview->form_document) > 0)
                                @foreach($applicationResultReview->form_document as $index => $media)
                                    <div class="mb-2">
                                        <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-file-pdf mr-1"></i> Form Penilaian {{ $index + 1 }}
                                        </a>
                                    </div>
                            @endforeach
                            @else
                                <p class="text-muted">Tidak ada dokumen</p>
                            @endif
                        </div>
                    </div>

                    <!-- Latest Script -->
                    <div class="form-group">
                        <label class="font-weight-bold">Naskah Proposal Terbaru:</label>
                        <div class="mt-2">
                            @if($applicationResultReview->latest_script)
                                <a href="{{ $applicationResultReview->latest_script->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-pdf mr-1"></i> Lihat Naskah
                                </a>
                            @else
                                <p class="text-muted">Tidak ada dokumen</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action History Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-history mr-2"></i>Riwayat Aksi</h5>
                </div>
                <div class="card-body">
                    @if($applicationResultReview->application && $applicationResultReview->application->actions && count($applicationResultReview->application->actions) > 0)
                        <div class="timeline">
                            @foreach($applicationResultReview->application->actions->sortByDesc('created_at') as $action)
                                <div class="timeline-item mb-3 pb-3 border-bottom">
                                    <div class="d-flex">
                                        <div class="mr-3">
                                            @php
                                                $iconMap = [
                                                    'result_review_approved' => 'check-circle text-success',
                                                    'result_review_rejected' => 'times-circle text-danger',
                                                ];
                                                $icon = $iconMap[$action->action_type] ?? 'info-circle text-info';
                                            @endphp
                                            <i class="fas fa-{{ $icon }} fa-2x"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-1">
                                                <strong>{{ ucfirst(str_replace('_', ' ', $action->action_type)) }}</strong>
                                            </p>
                                            <p class="text-muted mb-1">{{ $action->notes }}</p>
                                            <small class="text-muted">
                                                <i class="far fa-clock mr-1"></i>
                                                {{ $action->created_at->format('d M Y H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Belum ada riwayat aksi</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Status Aplikasi</h5>
                </div>
                <div class="card-body text-center">
                    @if($applicationResultReview->application)
                        @php
                            $statusBadges = [
                                'submitted' => 'badge-info',
                                'approved' => 'badge-success',
                                'rejected' => 'badge-danger',
                            ];
                            $statusClass = $statusBadges[$applicationResultReview->application->status] ?? 'badge-secondary';
                        @endphp
                        <h3 class="mb-3">
                            <span class="badge {{ $statusClass }} badge-lg px-4 py-3" style="font-size: 1.2rem;">
                                {{ ucfirst($applicationResultReview->application->status) }}
                            </span>
                        </h3>
                        <p class="text-muted">Stage: <strong>{{ ucfirst($applicationResultReview->application->stage) }}</strong></p>
                    @else
                        <p class="text-muted">Status tidak tersedia</p>
                    @endif
                </div>
            </div>

            <!-- Actions Card -->
            @if($applicationResultReview->application && $applicationResultReview->application->status !== 'approved' && $applicationResultReview->application->status !== 'rejected')
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-tasks mr-2"></i>Aksi</h5>
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn btn-success btn-block mb-2" data-toggle="modal" data-target="#approveModal">
                            <i class="fas fa-check mr-1"></i> Setujui Hasil Review
                        </button>
                        <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#rejectModal">
                            <i class="fas fa-times mr-1"></i> Tolak Hasil Review
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle mr-2"></i>
                    Setujui Hasil Review
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="approveForm">
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="fas fa-info-circle mr-2"></i>
                        Hasil review akan disetujui dan status aplikasi akan diubah menjadi "Disetujui".
                    </div>
                    <div class="form-group">
                        <label for="approve_notes">Catatan (Opsional)</label>
                        <textarea class="form-control" id="approve_notes" name="notes" rows="3" 
                                  placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check mr-1"></i> Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-times-circle mr-2"></i>
                    Tolak Hasil Review
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="rejectForm">
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Pastikan Anda memberikan alasan yang jelas untuk penolakan.
                    </div>
                    <div class="form-group">
                        <label for="reject_reason">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="reject_reason" name="reason" rows="4" 
                                  placeholder="Jelaskan alasan penolakan..." required minlength="10"></textarea>
                        <small class="form-text text-muted">Minimal 10 karakter</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times mr-1"></i> Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Approve Form Submit
    $('#approveForm').on('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');
        
        $.ajax({
            url: '{{ route("admin.application-result-reviews.approve", $applicationResultReview->id) }}',
            method: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#approveModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(function() {
                    window.location.reload();
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan'
                });
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fas fa-check mr-1"></i> Setujui');
            }
        });
    });

    // Reject Form Submit
    $('#rejectForm').on('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');
        
        $.ajax({
            url: '{{ route("admin.application-result-reviews.reject", $applicationResultReview->id) }}',
            method: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#rejectModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Hasil Review Ditolak',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(function() {
                    window.location.reload();
                });
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan';
                if (xhr.responseJSON?.errors?.reason) {
                    errorMessage = xhr.responseJSON.errors.reason[0];
                } else if (xhr.responseJSON?.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: errorMessage
                });
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fas fa-times mr-1"></i> Tolak');
            }
        });
    });

    // Clear modal on close
    $('.modal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
    });
});
</script>
@endsection

