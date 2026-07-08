@if($items->isEmpty())
    <p class="text-muted mb-0">Tidak ada data penugasan penguji.</p>
@else
    <div class="table-responsive">
        <table class="table table-sm table-bordered mb-0">
            <thead class="thead-light">
                <tr>
                    <th>Jenis</th>
                    <th>Peran</th>
                    <th>Mahasiswa</th>
                    <th>NIM</th>
                    <th>Prodi</th>
                    <th>Jalur</th>
                    <th>Status Aplikasi</th>
                    <th>Judul</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>
                            @php
                                $jenisBadge = match($item['jenis']) {
                                    'Seminar Reguler' => 'info',
                                    'Seminar MBKM' => 'primary',
                                    'Sidang Skripsi' => 'success',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge badge-{{ $jenisBadge }}">{{ $item['jenis'] }}</span>
                        </td>
                        <td>{{ $item['peran'] }}</td>
                        <td>{{ $item['mahasiswa'] }}</td>
                        <td>{{ $item['nim'] }}</td>
                        <td>{{ $item['prodi'] }}</td>
                        <td>{{ $item['type'] }}</td>
                        <td>{{ ucfirst($item['status_aplikasi']) }}</td>
                        <td><small>{{ \Illuminate\Support\Str::limit($item['judul'], 60) }}</small></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
