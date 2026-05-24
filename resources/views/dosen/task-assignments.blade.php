@extends('layouts.dosen')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card-modern" style="background: linear-gradient(135deg, var(--dosen-primary) 0%, var(--dosen-secondary) 100%); border: none;">
                <div class="card-modern-body" style="padding: 2rem;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-1 text-white font-weight-bold">
                                <i class="fas fa-tasks mr-2"></i> Task Assignments
                            </h2>
                            <p class="mb-0" style="color: rgba(255,255,255,0.9);">
                                <i class="fas fa-user mr-2"></i> {{ $dosen->nama }}
                                <span class="mx-2">|</span>
                                <i class="fas fa-id-card mr-2"></i> NIDN: {{ $dosen->nidn }}
                            </p>
                        </div>
                        <div class="col-md-4 text-right">
                            <div style="color: rgba(255,255,255,0.9); font-size: var(--font-size-lg);">
                                <div><i class="fas fa-clipboard-list mr-2"></i> <strong>{{ $assignments->count() }}</strong> Total Assignments</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assignments List -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-modern">
                <div class="card-modern-body p-0">
                    @if($assignments->count() > 0)
                        <div class="table-responsive">
                            <table class="table-modern table-modern-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Mahasiswa</th>
                                        <th>Program Studi</th>
                                        <th>Jenis</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assignments as $index => $assignment)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div style="width: 32px; height: 32px; background: var(--dosen-accent); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin-right: var(--spacing-2);">
                                                        <i class="fas fa-user" style="font-size: 12px; color: white;"></i>
                                                    </div>
                                                    <div>
                                                        <div class="font-weight-semibold">{{ $assignment->application->mahasiswa->nama ?? 'N/A' }}</div>
                                                        <div class="text-xs text-gray-600">{{ $assignment->application->mahasiswa->nim ?? 'N/A' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-sm">{{ $assignment->application->mahasiswa->prodi->name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge-modern badge-modern-primary">
                                                    {{ strtoupper($assignment->application->type ?? 'N/A') }}
                                                </span>
                                            </td>
                                            <td class="text-capitalize">
                                                @if($assignment->role == 'supervisor')
                                                    <span class="badge-modern badge-modern-success">Pembimbing</span>
                                                @elseif($assignment->role == 'reviewer')
                                                    <span class="badge-modern badge-modern-info">Penguji</span>
                                                @elseif($assignment->role == 'examiner')
                                                    <span class="badge-modern badge-modern-warning">Examiner</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($assignment->status == 'assigned')
                                                    <span class="badge-modern badge-modern-warning">Belum Direspon</span>
                                                @elseif($assignment->status == 'accepted')
                                                    <span class="badge-modern badge-modern-success">Diterima</span>
                                                @else
                                                    <span class="badge-modern badge-modern-danger">Ditolak</span>
                                                @endif
                                            </td>
                                            <td>
                                                <i class="far fa-calendar mr-1"></i>
                                                {{ $assignment->assigned_at ? \Carbon\Carbon::parse($assignment->assigned_at)->format('d M Y') : 'N/A' }}
                                            </td>
                                            <td>
                                                @if($assignment->status == 'assigned')
                                                    <a href="{{ route('dosen.review-proposal', $assignment->id) }}" class="btn-modern btn-modern-sm btn-modern-success">
                                                        <i class="fas fa-clipboard-check"></i> Review
                                                    </a>
                                                @else
                                                    <button class="btn-modern btn-modern-sm btn-modern-outline" onclick="showNote({{ $assignment->id }})">
                                                        <i class="fas fa-eye"></i> Lihat
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        
                    @else
                        <div class="empty-state text-center py-5">
                            <div style="width: 100px; height: 100px; background: var(--gray-100); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-4);">
                                <i class="fas fa-tasks fa-3x text-muted"></i>
                            </div>
                            <h4 class="text-muted">Tidak Ada Task Assignment</h4>
                            <p class="text-muted">Anda belum memiliki task assignment saat ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($assignments->count() > 0)
    <!-- ALL MODALS OUTSIDE TABLE -->
    @foreach($assignments as $assignment)
        <!-- Response Modal -->
        <div class="modal" id="respondModal{{ $assignment->id }}" tabindex="-1" role="dialog" aria-labelledby="respondModalLabel{{ $assignment->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
                    <form action="{{ route('dosen.task-assignments.respond', $assignment->id) }}" method="POST">
                        @csrf
                        <div class="modal-header" style="background: linear-gradient(135deg, var(--dosen-primary), var(--dosen-secondary)); border-radius: 12px 12px 0 0; border: none;">
                            <h5 class="modal-title text-white font-weight-bold" id="respondModalLabel{{ $assignment->id }}">
                                <i class="fas fa-reply mr-2"></i> Respond to Assignment
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" style="padding: 2rem;">
                            <div class="form-group">
                                <label class="font-weight-semibold">Mahasiswa</label>
                                <input type="text" class="form-control" value="{{ $assignment->application->mahasiswa->nama }} ({{ $assignment->application->mahasiswa->nim }})" readonly style="background-color: #f8f9fa;">
                            </div>
                            <div class="form-group">
                                <label class="font-weight-semibold">Role Anda</label>
                                <input type="text" class="form-control" value="{{ ucfirst($assignment->role) }}" readonly style="background-color: #f8f9fa;">
                            </div>
                            <div class="form-group">
                                <label class="font-weight-semibold">Response <span class="text-danger">*</span></label>
                                <select name="status" class="form-control" required style="border-radius: 8px;">
                                    <option value="">-- Pilih Response --</option>
                                    <option value="accepted">✅ Terima (Accept)</option>
                                    <option value="rejected">❌ Tolak (Reject)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-semibold">Catatan (Opsional)</label>
                                <textarea name="note" class="form-control" rows="3" placeholder="Tambahkan catatan jika diperlukan..." style="border-radius: 8px;"></textarea>
                                <small class="form-text text-muted">Catatan akan dikirim ke mahasiswa</small>
                            </div>
                        </div>
                        <div class="modal-footer" style="border-top: 1px solid #e9ecef; padding: 1rem 2rem;">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 8px;">
                                <i class="fas fa-times"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-primary" style="background: var(--dosen-primary); border-color: var(--dosen-primary); border-radius: 8px;">
                                <i class="fas fa-paper-plane"></i> Kirim Response
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endif

@push('styles')
<style>
    /* Ultra-simple modal fix - no animations */
    .modal {
        z-index: 99999 !important;
        display: none;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100% !important;
    }
    
    .modal.show {
        display: block !important;
    }
    
    .modal-backdrop {
        z-index: 99998 !important;
        background-color: rgba(0, 0, 0, 0.5);
        position: fixed !important;
    }
    
    .modal-dialog {
        margin: 30px auto;
        position: relative;
        z-index: 1;
    }
    
    body.modal-open {
        overflow: hidden !important;
        padding-right: 0 !important;
    }
    
    /* Disable fade animation to prevent glitch */
    .modal.fade .modal-dialog {
        transition: none !important;
        transform: none !important;
    }
    
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.5);
        position: relative;
        z-index: 1;
    }
    
    .modal-body .form-control:focus {
        border-color: var(--dosen-primary);
        box-shadow: 0 0 0 0.2rem rgba(34, 0, 76, 0.25);
    }
    
    /* CRITICAL: Prevent table from creating stacking context */
    .table-responsive {
        position: relative !important;
        z-index: auto !important;
        overflow-x: auto !important;
        overflow-y: visible !important;
    }
    
    .card-modern {
        position: relative !important;
        z-index: auto !important;
        overflow: visible !important;
    }
    
    .card-modern-body {
        position: relative !important;
        z-index: auto !important;
        overflow: visible !important;
    }
    
    table, thead, tbody, tr, td {
        position: static !important;
        z-index: auto !important;
    }
</style>
@endpush

@push('scripts')
<script>
(function() {
    'use strict';
    
    $(document).ready(function() {
        console.log('[Modal] Initializing modal handlers');
        
        // Remove ALL fade classes from modals to prevent animation glitches
        $('.modal').removeClass('fade');
        
        // Simple modal show/hide without complex animations
        $('.modal').on('show.bs.modal', function (e) {
            console.log('[Modal] Opening modal');
            const $modal = $(this);
            
            // Force z-index
            $modal.css('z-index', '99999');
            
            // Ensure backdrop is behind modal
            setTimeout(function() {
                $('.modal-backdrop').css('z-index', '99998');
            }, 10);
        });
        
        $('.modal').on('shown.bs.modal', function (e) {
            console.log('[Modal] Modal fully shown');
        });
        
        $('.modal').on('hide.bs.modal', function (e) {
            console.log('[Modal] Closing modal');
        });
        
        $('.modal').on('hidden.bs.modal', function (e) {
            console.log('[Modal] Modal closed');
            
            // Clean up any leftover backdrops
            $('.modal-backdrop').remove();
            
            // Restore body
            if ($('.modal.show').length === 0) {
                $('body').removeClass('modal-open');
                $('body').css('padding-right', '');
                $('body').css('overflow', '');
            }
        });
        
        // Form submission validation
        $('form[action*="task-assignments"][action*="respond"]').on('submit', function(e) {
            const status = $(this).find('select[name="status"]').val();
            
            if (!status) {
                e.preventDefault();
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Silakan pilih response (Accept/Reject)',
                        confirmButtonColor: '#22004C'
                    });
                } else {
                    alert('Silakan pilih response!');
                }
                return false;
            }
            
            // Show loading
            if ($('#loadingSpinner').length) {
                $('#loadingSpinner').show();
            }
        });
        
        console.log('[Modal] Found ' + $('.modal').length + ' modals');
    });
    
    // Function to show note detail
    window.showNote = function(assignmentId) {
        const row = $('button[onclick="showNote(' + assignmentId + ')"]').closest('tr');
        const status = row.find('td').eq(5).text().trim();
        const date = row.find('td').eq(6).text().trim();
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Detail Response',
                html: '<div class="text-left"><p><strong>Status:</strong> ' + status + '</p><p><strong>Tanggal:</strong> ' + date + '</p></div>',
                icon: 'info',
                confirmButtonColor: '#22004C'
            });
        }
    };
})();
</script>
@endpush
@endsection
