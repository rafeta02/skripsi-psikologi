@if($items->isEmpty())
    <p class="text-muted mb-0">Tidak ada data bimbingan.</p>
@else
    <div class="table-responsive">
        <table class="table table-sm table-bordered mb-0">
            <thead class="thead-light">
                <tr>
                    <th>Mahasiswa</th>
                    <th>NIM</th>
                    <th>Prodi</th>
                    <th>Jalur</th>
                    <th>Peran</th>
                    <th>Tahap</th>
                    <th>Status Penugasan</th>
                    <th>Status Aplikasi</th>
                    <th>Judul</th>
                    <th>Ditugaskan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item['mahasiswa'] }}</td>
                        <td>{{ $item['nim'] }}</td>
                        <td>{{ $item['prodi'] }}</td>
                        <td><span class="badge badge-primary">{{ $item['type'] }}</span></td>
                        <td>
                            @if(!empty($item['peran_kelompok']))
                                <span class="badge badge-{{ $item['peran_kelompok'] === 'Ketua' ? 'info' : 'secondary' }}">{{ $item['peran_kelompok'] }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $item['stage'] }}</td>
                        <td>
                            @php
                                $badge = match($item['status_penugasan']) {
                                    'accepted' => 'success',
                                    'assigned' => 'warning',
                                    'rejected' => 'danger',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge badge-{{ $badge }}">{{ ucfirst($item['status_penugasan']) }}</span>
                        </td>
                        <td>{{ ucfirst($item['status_aplikasi']) }}</td>
                        <td><small>{{ \Illuminate\Support\Str::limit($item['judul'], 60) }}</small></td>
                        <td>{{ $item['assigned_at'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
