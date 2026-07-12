{{--
  Konteks kelompok MBKM (sampai sebelum sidang).
  @param \App\Models\MbkmRegistration $mbkmGroupRegistration
  @param string $mode  'full' | 'compact'
--}}
@php
    $registration = $mbkmGroupRegistration ?? null;
    $mode = $mode ?? 'compact';
    $members = $registration?->groupMembers ?? collect();
    $ketuaAppMahasiswa = $registration?->application?->mahasiswa;
@endphp

@if($registration)
<div class="card {{ $mode === 'full' ? '' : 'mb-3' }}">
    <div class="card-header text-white" style="background-color: #6f42c1;">
        <h3 class="card-title mb-0">
            <i class="fas fa-users mr-2"></i>
            Kelompok MBKM
            <small class="font-weight-normal ml-2" style="opacity:.9;">
                (proses berkelompok sampai sebelum sidang)
            </small>
        </h3>
    </div>
    <div class="card-body">
        <div class="mb-3">
            @if(($registration->group_status ?? 'draft') === 'submitted')
                <span class="badge badge-success p-2">
                    <i class="fas fa-paper-plane mr-1"></i> Kelompok sudah diajukan
                </span>
            @else
                <span class="badge badge-warning p-2">
                    <i class="fas fa-hourglass-half mr-1"></i> Draft — menunggu syarat individu
                </span>
            @endif

            @if($members->count() > 0)
                @if($registration->allMembersRequirementsComplete())
                    <span class="badge badge-success p-2 ml-1">
                        <i class="fas fa-check mr-1"></i> Semua syarat individu lengkap
                    </span>
                @else
                    <span class="badge badge-secondary p-2 ml-1">
                        Syarat individu: {{ $members->where('requirements_status', 'complete')->count() }}/{{ $members->count() }}
                    </span>
                @endif
            @else
                <span class="badge badge-secondary p-2 ml-1">Belum ada daftar anggota</span>
            @endif
        </div>

        @if($members->isEmpty())
            <div class="alert alert-warning mb-3">
                <i class="fas fa-exclamation-triangle mr-1"></i>
                Daftar anggota kelompok belum tersimpan. Menampilkan data individu dari form ketua (legacy).
            </div>

            @if($mode === 'compact')
                <table class="table table-sm table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Peran</th>
                            <th>Judul Skripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $ketuaAppMahasiswa->nim ?? '-' }}</td>
                            <td>{{ $ketuaAppMahasiswa->nama ?? '-' }}</td>
                            <td><span class="badge badge-success">Ketua</span></td>
                            <td class="small">{{ \Illuminate\Support\Str::limit($registration->title ?? '-', 60) }}</td>
                        </tr>
                    </tbody>
                </table>
            @else
                <div class="border rounded p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <strong>{{ $ketuaAppMahasiswa->nama ?? '-' }}</strong>
                            <small class="text-muted ml-2">{{ $ketuaAppMahasiswa->nim ?? '' }}</small>
                            <span class="badge badge-success ml-1">Ketua</span>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-bold mb-0">Judul Skripsi:</label>
                        <p class="mb-1 text-justify">{{ $registration->title ?: '-' }}</p>
                        @if($registration->title_en)
                            <small class="text-muted">EN: {{ $registration->title_en }}</small>
                        @endif
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="font-weight-bold">Total SKS:</label>
                            <p>{{ $registration->total_sks_taken ?? '-' }} SKS</p>
                        </div>
                        <div class="col-md-6">
                            <label class="font-weight-bold">SKS MKP:</label>
                            <p>{{ $registration->sks_mkp_taken ?? '-' }} SKS</p>
                        </div>
                    </div>
                    <h6 class="font-weight-bold">Nilai Mata Kuliah</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless mb-0">
                                <tr><th width="65%">MK Kuantitatif</th><td><span class="badge badge-primary">{{ $registration->nilai_mk_kuantitatif ?? '-' }}</span></td></tr>
                                <tr><th>MK Kualitatif</th><td><span class="badge badge-primary">{{ $registration->nilai_mk_kualitatif ?? '-' }}</span></td></tr>
                                <tr><th>MK Statistika Dasar</th><td><span class="badge badge-primary">{{ $registration->nilai_mk_statistika_dasar ?? '-' }}</span></td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless mb-0">
                                <tr><th width="65%">MK Statistika Lanjutan</th><td><span class="badge badge-primary">{{ $registration->nilai_mk_statistika_lanjutan ?? '-' }}</span></td></tr>
                                <tr><th>MK Konstruksi Tes</th><td><span class="badge badge-primary">{{ $registration->nilai_mk_konstruksi_tes ?? '-' }}</span></td></tr>
                                <tr><th>MK TPS</th><td><span class="badge badge-primary">{{ $registration->nilai_mk_tps ?? '-' }}</span></td></tr>
                            </table>
                        </div>
                    </div>
                    <h6 class="font-weight-bold">Dokumen Individu (dari form ketua)</h6>
                    @php
                        $legacyDocs = [
                            ['label' => 'KHS Seluruh Semester', 'items' => $registration->khs_all ?? collect()],
                            ['label' => 'KRS Semester Terakhir', 'items' => $registration->krs_latest ? collect([$registration->krs_latest]) : collect()],
                            ['label' => 'Bukti Pembayaran SPP', 'items' => $registration->spp ? collect([$registration->spp]) : collect()],
                            ['label' => 'Form Pengakuan / Recognition', 'items' => $registration->recognition_form ? collect([$registration->recognition_form]) : collect()],
                        ];
                    @endphp
                    @foreach($legacyDocs as $doc)
                        <div class="form-group mb-2">
                            <label class="font-weight-bold mb-1">{{ $doc['label'] }}:</label>
                            @if($doc['items']->count() > 0)
                                <div class="list-group">
                                    @foreach($doc['items'] as $media)
                                        <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                            <small class="text-muted"><i class="fas fa-file-pdf text-danger mr-2"></i>{{ $media->file_name }}</small>
                                            <div class="btn-group">
                                                <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>
                                                <button type="button" class="btn btn-sm btn-info preview-doc" data-url="{{ $media->getUrl() }}" data-type="pdf"><i class="fas fa-expand"></i></button>
                                                <a href="{{ $media->getUrl() }}" download class="btn btn-sm btn-success"><i class="fas fa-download"></i></a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted small mb-0">Tidak ada file</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        @elseif($mode === 'compact')
            <div class="table-responsive">
                <table class="table table-sm table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Peran</th>
                            <th>Judul Skripsi</th>
                            <th>Syarat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($members as $index => $member)
                            @php
                                $displayTitle = $member->title
                                    ?: (($member->role === 'ketua') ? ($registration->title ?? null) : null);
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $member->mahasiswa->nim ?? '-' }}</td>
                                <td>{{ $member->mahasiswa->nama ?? '-' }}</td>
                                <td>
                                    @if($member->role === 'ketua')
                                        <span class="badge badge-success">Ketua</span>
                                    @else
                                        <span class="badge badge-secondary">Anggota</span>
                                    @endif
                                </td>
                                <td class="small">{{ \Illuminate\Support\Str::limit($displayTitle ?? '-', 60) }}</td>
                                <td>
                                    @if(($member->requirements_status ?? '') === 'complete')
                                        <span class="badge badge-success">Lengkap</span>
                                    @else
                                        <span class="badge badge-warning">Belum</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="accordion" id="mbkmMemberAccordion">
                @foreach($members as $index => $member)
                    @php
                        $collapseId = 'memberCollapse' . $member->id;
                        $isComplete = ($member->requirements_status ?? '') === 'complete';
                        $isKetua = $member->role === 'ketua';
                        $displayTitle = $member->title ?: ($isKetua ? ($registration->title ?? null) : null);
                        $displayTitleEn = $member->title_en ?: ($isKetua ? ($registration->title_en ?? null) : null);
                        $nilai = [
                            'total_sks_taken' => $member->total_sks_taken ?: ($isKetua ? $registration->total_sks_taken : null),
                            'sks_mkp_taken' => $member->sks_mkp_taken ?: ($isKetua ? $registration->sks_mkp_taken : null),
                            'nilai_mk_kuantitatif' => $member->nilai_mk_kuantitatif ?: ($isKetua ? $registration->nilai_mk_kuantitatif : null),
                            'nilai_mk_kualitatif' => $member->nilai_mk_kualitatif ?: ($isKetua ? $registration->nilai_mk_kualitatif : null),
                            'nilai_mk_statistika_dasar' => $member->nilai_mk_statistika_dasar ?: ($isKetua ? $registration->nilai_mk_statistika_dasar : null),
                            'nilai_mk_statistika_lanjutan' => $member->nilai_mk_statistika_lanjutan ?: ($isKetua ? $registration->nilai_mk_statistika_lanjutan : null),
                            'nilai_mk_konstruksi_tes' => $member->nilai_mk_konstruksi_tes ?: ($isKetua ? $registration->nilai_mk_konstruksi_tes : null),
                            'nilai_mk_tps' => $member->nilai_mk_tps ?: ($isKetua ? $registration->nilai_mk_tps : null),
                        ];
                        $khs = ($member->khs_all && count($member->khs_all)) ? $member->khs_all : (($isKetua ? $registration->khs_all : null) ?? collect());
                        $krs = $member->krs_latest ?: ($isKetua ? $registration->krs_latest : null);
                        $spp = $member->spp ?: ($isKetua ? $registration->spp : null);
                        $recognition = $member->recognition_form ?: ($isKetua ? $registration->recognition_form : null);
                    @endphp
                    <div class="card mb-2 border">
                        <div class="card-header p-2" id="heading{{ $member->id }}">
                            <button class="btn btn-link btn-block text-left text-dark text-decoration-none d-flex justify-content-between align-items-center"
                                    type="button" data-toggle="collapse" data-target="#{{ $collapseId }}"
                                    aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="{{ $collapseId }}">
                                <span>
                                    <strong>{{ $member->mahasiswa->nama ?? '-' }}</strong>
                                    <small class="text-muted ml-2">{{ $member->mahasiswa->nim ?? '' }}</small>
                                    @if($isKetua)
                                        <span class="badge badge-success ml-1">Ketua</span>
                                    @else
                                        <span class="badge badge-secondary ml-1">Anggota</span>
                                    @endif
                                </span>
                                <span class="badge badge-{{ $isComplete ? 'success' : 'warning' }}">
                                    {{ $isComplete ? 'Syarat lengkap' : 'Belum lengkap' }}
                                </span>
                            </button>
                        </div>
                        <div id="{{ $collapseId }}" class="collapse {{ $index === 0 ? 'show' : '' }}"
                             data-parent="#mbkmMemberAccordion">
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold mb-0">Judul Skripsi:</label>
                                    <p class="mb-1 text-justify">{{ $displayTitle ?: '-' }}</p>
                                    @if($displayTitleEn)
                                        <small class="text-muted">EN: {{ $displayTitleEn }}</small>
                                    @endif
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="font-weight-bold">Total SKS:</label>
                                        <p>{{ $nilai['total_sks_taken'] ?? '-' }} SKS</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="font-weight-bold">SKS MKP:</label>
                                        <p>{{ $nilai['sks_mkp_taken'] ?? '-' }} SKS</p>
                                    </div>
                                </div>

                                <h6 class="font-weight-bold">Nilai Mata Kuliah</h6>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <table class="table table-sm table-borderless mb-0">
                                            <tr><th width="65%">MK Kuantitatif</th><td><span class="badge badge-primary">{{ $nilai['nilai_mk_kuantitatif'] ?? '-' }}</span></td></tr>
                                            <tr><th>MK Kualitatif</th><td><span class="badge badge-primary">{{ $nilai['nilai_mk_kualitatif'] ?? '-' }}</span></td></tr>
                                            <tr><th>MK Statistika Dasar</th><td><span class="badge badge-primary">{{ $nilai['nilai_mk_statistika_dasar'] ?? '-' }}</span></td></tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm table-borderless mb-0">
                                            <tr><th width="65%">MK Statistika Lanjutan</th><td><span class="badge badge-primary">{{ $nilai['nilai_mk_statistika_lanjutan'] ?? '-' }}</span></td></tr>
                                            <tr><th>MK Konstruksi Tes</th><td><span class="badge badge-primary">{{ $nilai['nilai_mk_konstruksi_tes'] ?? '-' }}</span></td></tr>
                                            <tr><th>MK TPS</th><td><span class="badge badge-primary">{{ $nilai['nilai_mk_tps'] ?? '-' }}</span></td></tr>
                                        </table>
                                    </div>
                                </div>

                                <h6 class="font-weight-bold">Dokumen Individu</h6>
                                @php
                                    $docRows = [
                                        ['label' => 'KHS Seluruh Semester', 'items' => collect($khs)],
                                        ['label' => 'KRS Semester Terakhir', 'items' => $krs ? collect([$krs]) : collect()],
                                        ['label' => 'Bukti Pembayaran SPP', 'items' => $spp ? collect([$spp]) : collect()],
                                        ['label' => 'Form Pengakuan / Recognition', 'items' => $recognition ? collect([$recognition]) : collect()],
                                    ];
                                @endphp
                                @foreach($docRows as $doc)
                                    <div class="form-group mb-2">
                                        <label class="font-weight-bold mb-1">{{ $doc['label'] }}:</label>
                                        @if($doc['items']->count() > 0)
                                            <div class="list-group">
                                                @foreach($doc['items'] as $media)
                                                    <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                        <div>
                                                            <i class="fas fa-file-pdf text-danger mr-2"></i>
                                                            <small class="text-muted">{{ $media->file_name }}</small>
                                                        </div>
                                                        <div class="btn-group">
                                                            <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-sm btn-primary">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-sm btn-info preview-doc"
                                                                    data-url="{{ $media->getUrl() }}" data-type="pdf">
                                                                <i class="fas fa-expand"></i>
                                                            </button>
                                                            <a href="{{ $media->getUrl() }}" download class="btn btn-sm btn-success">
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted small mb-0">Tidak ada file</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endif
