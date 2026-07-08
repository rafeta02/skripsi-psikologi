<div class="mb-3">
    <div class="row">
        <div class="col-md-6">
            <table class="table table-sm table-borderless mb-0">
                <tr><th class="text-muted" style="width: 140px;">Mahasiswa</th><td>{{ $detail['mahasiswa'] }} ({{ $detail['nim'] }})</td></tr>
                <tr><th class="text-muted">Prodi</th><td>{{ $detail['prodi'] }}</td></tr>
                <tr><th class="text-muted">Jalur</th><td>{{ $detail['type'] }}</td></tr>
                <tr><th class="text-muted">Hasil Sidang</th><td>{{ $detail['result_label'] }}</td></tr>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-sm table-borderless mb-0">
                <tr>
                    <th class="text-muted" style="width: 140px;">Nilai Akhir</th>
                    <td><strong class="text-success" style="font-size: 1.25rem;">{{ number_format($detail['final_score'], 2) }}</strong></td>
                </tr>
                <tr>
                    <th class="text-muted">Nilai Huruf</th>
                    <td>
                        <span class="badge badge-dark badge-lg">{{ $detail['grade'] }}</span>
                        <small class="text-muted ml-1">{{ $detail['grade_description'] }}</small>
                    </td>
                </tr>
                <tr>
                    <th class="text-muted">Status</th>
                    <td>
                        @if($detail['finalized'])
                            <span class="badge badge-success">Sudah difinalisasi</span>
                        @else
                            <span class="badge badge-warning">Belum difinalisasi</span>
                        @endif
                    </td>
                </tr>
                <tr><th class="text-muted">Judul</th><td><small>{{ $detail['judul'] }}</small></td></tr>
            </table>
        </div>
    </div>
</div>

<h6 class="font-weight-bold">Rata-rata per Komponen (semua penilai)</h6>
<div class="table-responsive mb-4">
    <table class="table table-sm table-bordered">
        <thead class="thead-light">
            <tr>
                @foreach($componentLabels as $key => $label)
                    <th class="text-center" style="font-size: 0.75rem;">{{ $label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach($componentLabels as $key => $label)
                    <td class="text-center font-weight-bold">
                        @if(($detail['component_averages'][$key] ?? null) !== null)
                            {{ number_format($detail['component_averages'][$key], 2) }}
                        @else
                            -
                        @endif
                    </td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>

<h6 class="font-weight-bold">Nilai per Dosen Penilai</h6>
@if(empty($detail['scorers']))
    <p class="text-muted">Belum ada data penilaian.</p>
@else
    <div class="table-responsive">
        <table class="table table-sm table-bordered mb-0">
            <thead class="thead-light">
                <tr>
                    <th>Dosen</th>
                    <th>NIDN</th>
                    @foreach($componentLabels as $key => $label)
                        <th class="text-center" style="font-size: 0.7rem;" title="{{ $label }}">
                            {{ \Illuminate\Support\Str::limit($label, 14) }}
                        </th>
                    @endforeach
                    <th class="text-center bg-success text-white">Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detail['scorers'] as $scorer)
                    <tr>
                        <td>{{ $scorer['examiner'] }}</td>
                        <td>{{ $scorer['nidn'] }}</td>
                        @foreach($componentLabels as $key => $label)
                            <td class="text-center">
                                {{ $scorer['components'][$key] ?? '-' }}
                            </td>
                        @endforeach
                        <td class="text-center font-weight-bold">{{ number_format($scorer['score'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="thead-light">
                <tr>
                    <th colspan="2" class="text-right">Rata-rata komponen</th>
                    @foreach($componentLabels as $key => $label)
                        <th class="text-center">
                            @if(($detail['component_averages'][$key] ?? null) !== null)
                                {{ number_format($detail['component_averages'][$key], 2) }}
                            @else
                                -
                            @endif
                        </th>
                    @endforeach
                    <th class="text-center text-success">{{ number_format($detail['final_score'], 2) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
@endif
