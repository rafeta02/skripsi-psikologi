@extends('layouts.dosen')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-tasks"></i> Task Assignments
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h5>Dosen: {{ $dosen->nama }}</h5>
                            <p class="text-muted">NIDN: {{ $dosen->nidn }} | NIP: {{ $dosen->nip }}</p>
                        </div>
                    </div>

                    @if($assignments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Mahasiswa</th>
                                        <th>NIM</th>
                                        <th>Program Studi</th>
                                        <th>Type</th>
                                        <th>Stage</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Assigned At</th>
                                        <th>Responded At</th>
                                        <th>Note</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assignments as $index => $assignment)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $assignment->application->mahasiswa->nama ?? 'N/A' }}</td>
                                            <td>{{ $assignment->application->mahasiswa->nim ?? 'N/A' }}</td>
                                            <td>{{ $assignment->application->mahasiswa->prodi->name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge badge-primary">
                                                    {{ strtoupper($assignment->application->type ?? 'N/A') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ ucfirst($assignment->application->stage ?? 'N/A') }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($assignment->role == 'supervisor')
                                                    <span class="badge badge-success">Pembimbing</span>
                                                @elseif($assignment->role == 'reviewer')
                                                    <span class="badge badge-info">Penguji</span>
                                                @elseif($assignment->role == 'examiner')
                                                    <span class="badge badge-warning">Examiner</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($assignment->status == 'assigned')
                                                    <span class="badge badge-warning">Assigned</span>
                                                @elseif($assignment->status == 'accepted')
                                                    <span class="badge badge-success">Accepted</span>
                                                @else
                                                    <span class="badge badge-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>{{ $assignment->assigned_at ?? 'N/A' }}</td>
                                            <td>{{ $assignment->responded_at ?? '-' }}</td>
                                            <td>{{ $assignment->note ?? '-' }}</td>
                                            <td>
                                                @if($assignment->status == 'assigned')
                                                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#respondModal{{ $assignment->id }}">
                                                        <i class="fas fa-reply"></i> Respond
                                                    </button>
                                                    
                                                    <!-- Response Modal -->
                                                    <div class="modal fade" id="respondModal{{ $assignment->id }}" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <form action="{{ route('dosen.assignments.respond', $assignment->id) }}" method="POST">
                                                                    @csrf
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Respond to Assignment</h5>
                                                                        <button type="button" class="close" data-dismiss="modal">
                                                                            <span>&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="form-group">
                                                                            <label>Mahasiswa</label>
                                                                            <input type="text" class="form-control" value="{{ $assignment->application->mahasiswa->nama }}" readonly>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Role</label>
                                                                            <input type="text" class="form-control" value="{{ ucfirst($assignment->role) }}" readonly>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Response <span class="text-danger">*</span></label>
                                                                            <select name="status" class="form-control" required>
                                                                                <option value="">-- Select Response --</option>
                                                                                <option value="accepted">Accept</option>
                                                                                <option value="rejected">Reject</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Note</label>
                                                                            <textarea name="note" class="form-control" rows="3" placeholder="Optional note..."></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                        <button type="submit" class="btn btn-primary">Submit Response</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <a href="{{ route('admin.applications.show', $assignment->application->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Tidak ada task assignment saat ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
