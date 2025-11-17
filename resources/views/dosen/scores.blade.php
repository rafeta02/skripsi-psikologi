@extends('layouts.dosen')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-star"></i> Application Scores
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h5>Dosen: {{ $dosen->nama }}</h5>
                            <p class="text-muted">NIDN: {{ $dosen->nidn }} | NIP: {{ $dosen->nip }}</p>
                        </div>
                    </div>

                    @if($scores->count() > 0)
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
                                        <th>Score</th>
                                        <th>Note</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($scores as $index => $score)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $score->application_result_defence->application->mahasiswa->nama ?? 'N/A' }}</td>
                                            <td>{{ $score->application_result_defence->application->mahasiswa->nim ?? 'N/A' }}</td>
                                            <td>{{ $score->application_result_defence->application->mahasiswa->prodi->name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge badge-primary">
                                                    {{ strtoupper($score->application_result_defence->application->type ?? 'N/A') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ ucfirst($score->application_result_defence->application->stage ?? 'N/A') }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong class="text-primary" style="font-size: 1.2em;">{{ $score->score }}</strong>
                                            </td>
                                            <td>{{ $score->note ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($score->created_at)->format('d M Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('admin.application-scores.show', $score->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Belum ada score yang diberikan.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
