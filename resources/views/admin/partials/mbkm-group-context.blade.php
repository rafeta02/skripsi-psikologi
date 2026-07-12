{{--
  Konteks kelompok MBKM (sampai sebelum sidang).
  @param \App\Models\MbkmRegistration $mbkmGroupRegistration
  @param string $mode  'full' | 'compact'
--}}
@php
    $registration = $mbkmGroupRegistration ?? null;
    $mode = $mode ?? 'compact';
    $members = $registration?->groupMembers ?? collect();
@endphp

@if($registration && $members->count() > 0)
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

            @if($registration->allMembersRequirementsComplete())
                <span class="badge badge-success p-2 ml-1">
                    <i class="fas fa-check mr-1"></i> Semua syarat individu lengkap
                </span>
            @else
                <span class="badge badge-secondary p-2 ml-1">
                    Syarat individu: {{ $members->where('requirements_status', 'complete')->count() }}/{{ $members->count() }}
                </span>
            @endif
        </div>

        @if($mode === 'compact')
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
                                <td class="small">{{ \Illuminate\Support\Str::limit($member->title ?? '-', 60) }}</td>
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
                    @endphp
                    <div class="card mb-2 border">
                        <div class="card-header p-2" id="heading{{ $member->id }}">
                            <button class="btn btn-link btn-block text-left text-dark text-decoration-none d-flex justify-content-between align-items-center"
                                    type="button" data-toggle="collapse" data-target="#{{ $collapseId }}"
                                    aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="{{ $collapseId }}">
                                <span>
                                    <strong>{{ $member->mahasiswa->nama ?? '-' }}</strong>
                                    <small class="text-muted ml-2">{{ $member->mahasiswa->nim ?? '' }}</small>
                                    @if($member->role === 'ketua')
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
                                    <p class="mb-1 text-justify">{{ $member->title ?: '-' }}</p>
                                    @if($member->title_en)
                                        <small class="text-muted">EN: {{ $member->title_en }}</small>
                                    @endif
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="font-weight-bold">Total SKS:</label>
                                        <p>{{ $member->total_sks_taken ?? '-' }} SKS</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="font-weight-bold">SKS MKP:</label>
                                        <p>{{ $member->sks_mkp_taken ?? '-' }} SKS</p>
                                    </div>
                                </div>

                                <h6 class="font-weight-bold">Nilai Mata Kuliah</h6>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <table class="table table-sm table-borderless mb-0">
                                            <tr>
                                                <th width="65%">MK Kuantitatif</th>
                                                <td><span class="badge badge-primary">{{ $member->nilai_mk_kuantitatif ?? '-' }}</span></td>
                                            </tr>
                                            <tr>
                                                <th>MK Kualitatif</th>
                                                <td><span class="badge badge-primary">{{ $member->nilai_mk_kualitatif ?? '-' }}</span></td>
                                            </tr>
                                            <tr>
                                                <th>MK Statistika Dasar</th>
                                                <td><span class="badge badge-primary">{{ $member->nilai_mk_statistika_dasar ?? '-' }}</span></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm table-borderless mb-0">
                                            <tr>
                                                <th width="65%">MK Statistika Lanjutan</th>
                                                <td><span class="badge badge-primary">{{ $member->nilai_mk_statistika_lanjutan ?? '-' }}</span></td>
                                            </tr>
                                            <tr>
                                                <th>MK Konstruksi Tes</th>
                                                <td><span class="badge badge-primary">{{ $member->nilai_mk_konstruksi_tes ?? '-' }}</span></td>
                                            </tr>
                                            <tr>
                                                <th>MK TPS</th>
                                                <td><span class="badge badge-primary">{{ $member->nilai_mk_tps ?? '-' }}</span></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <h6 class="font-weight-bold">Dokumen Individu</h6>
                                @php
                                    $docRows = [
                                        ['label' => 'KHS Seluruh Semester', 'items' => $member->khs_all ?? collect(), 'multi' => true],
                                        ['label' => 'KRS Semester Terakhir', 'items' => $member->krs_latest ? collect([$member->krs_latest]) : collect(), 'multi' => false],
                                        ['label' => 'Bukti Pembayaran SPP', 'items' => $member->spp ? collect([$member->spp]) : collect(), 'multi' => false],
                                        ['label' => 'Form Pengakuan / Recognition', 'items' => $member->recognition_form ? collect([$member->recognition_form]) : collect(), 'multi' => false],
                                    ];
                                @endphp
                                @foreach($docRows as $doc)
                                    <div class="form-group mb-2">
                                        <label class="font-weight-bold mb-1">{{ $doc['label'] }}:</label>
                                        @if($doc['items']->count() > 0)
                                            <div class="list-group">
                                                @foreach($doc['items'] as $key => $media)
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
