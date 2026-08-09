@extends('layouts.dosen')

@section('content')
@php $pendingAssignmentCount = $assignments->where('status', 'assigned')->count(); @endphp
@include('partials.dosen.page-header', [
    'title' => 'Penugasan',
    'subtitle' => $dosen->nama . ' · ' . $assignments->count() . ' total'
        . ($pendingAssignmentCount > 0 ? ' · ' . $pendingAssignmentCount . ' belum ditanggapi' : ''),
])

@if($pendingAssignmentCount > 0)
<div class="alert alert-warning alert-dismissible fade show">
    <strong>{{ $pendingAssignmentCount }} penugasan</strong> belum disetujui atau ditolak. Klik <strong>Tinjau</strong> untuk memberikan respons.
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<div class="row">
    <div class="col-12">
        <div class="mhs-card">
            <div class="mhs-card-body p-0">
                @if($assignments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0 text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Mahasiswa</th>
                                    <th class="text-center">Program Studi</th>
                                    <th class="text-center">Jenis</th>
                                    <th class="text-center">Role</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignments as $index => $assignment)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @include('partials.dosen.mbkm-assignment-mahasiswa', [
                                                'application' => $assignment->application,
                                            ])
                                        </td>
                                        <td>
                                            {{ $assignment->application->mahasiswa->prodi->name ?? 'N/A' }}
                                            <br><small class="text-muted">{{ $assignment->application->mahasiswa->jenjang->name ?? '' }}</small>
                                        </td>
                                        <td><span class="badge badge-primary">{{ strtoupper($assignment->application->type ?? 'N/A') }}</span></td>
                                        <td>
                                            @if($assignment->role == 'supervisor')
                                                <span class="badge badge-success">Pembimbing</span>
                                            @elseif($assignment->role == 'reviewer')
                                                <span class="badge badge-info">Penguji</span>
                                            @else
                                                <span class="badge badge-warning">{{ ucfirst($assignment->role) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($assignment->status == 'assigned')
                                                <span class="badge badge-warning">Menunggu</span>
                                            @elseif($assignment->status == 'accepted')
                                                <span class="badge badge-success">Diterima</span>
                                            @else
                                                <span class="badge badge-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>{{ $assignment->assigned_at ? \Carbon\Carbon::parse($assignment->assigned_at)->format('d M Y') : '-' }}</td>
                                        <td class="text-nowrap">
                                            @if($assignment->status == 'assigned')
                                                <a href="{{ route('dosen.review-proposal', $assignment->id) }}" class="btn btn-sm btn-outline-primary">
                                                    Tinjau
                                                </a>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#detailModal{{ $assignment->id }}">
                                                    Detail
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-tasks fa-2x mb-2 d-block"></i>
                        Belum ada penugasan.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 mt-3">
        <div class="row">
            <div class="col-lg-6">
                @include('partials.dosen.quick-actions')
            </div>
            <div class="col-lg-6">
                @include('partials.dosen.activity-timeline')
            </div>
        </div>
    </div>
</div>

@foreach($assignments as $assignment)
    @if($assignment->status !== 'assigned')
        <div class="modal fade" id="detailModal{{ $assignment->id }}" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold">
                            Detail Penugasan — {{ $assignment->application->mahasiswa->nama ?? 'N/A' }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th width="140">Mahasiswa</th>
                                <td>: {{ $assignment->application->mahasiswa->nama ?? 'N/A' }} ({{ $assignment->application->mahasiswa->nim ?? '-' }})</td>
                            </tr>
                            <tr>
                                <th>Role</th>
                                <td class="text-capitalize">: {{ $assignment->role === 'supervisor' ? 'Pembimbing' : ($assignment->role === 'reviewer' ? 'Penguji' : ucfirst($assignment->role)) }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>:
                                    @if($assignment->status == 'accepted')
                                        <span class="badge badge-success">Diterima</span>
                                    @else
                                        <span class="badge badge-danger">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Tanggal Ditugaskan</th>
                                <td>: {{ $assignment->assigned_at ? \Carbon\Carbon::parse($assignment->assigned_at)->format('d M Y H:i') : '-' }}</td>
                            </tr>
                            @if($assignment->responded_at)
                                <tr>
                                    <th>Tanggal Respons</th>
                                    <td>: {{ \Carbon\Carbon::parse($assignment->responded_at)->format('d M Y H:i') }}</td>
                                </tr>
                            @endif
                            @if($assignment->note)
                                <tr>
                                    <th>Catatan</th>
                                    <td>: {{ $assignment->note }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection
