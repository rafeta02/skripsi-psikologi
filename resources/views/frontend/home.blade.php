@extends('layouts.frontend')

@section('content')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #22004C 0%, #4A0080 100%);
        color: white;
        padding: 2.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }
    
    .dashboard-header h1 {
        margin: 0 0 0.5rem;
        font-size: 2rem;
        font-weight: 600;
    }
    
    .dashboard-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 1rem;
    }
    
    .dashboard-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        padding: 2rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s;
    }
    
    .dashboard-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    }
    
    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }
    
    .card-title i {
        margin-right: 0.75rem;
        color: #22004C;
        font-size: 1.5rem;
    }
    
    .status-badge {
        display: inline-block;
        padding: 0.5rem 1.25rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .status-submitted {
        background: #fef3c7;
        color: #92400e;
    }
    
    .status-approved {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .status-scheduled {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .status-done {
        background: #e0e7ff;
        color: #3730a3;
    }
    
    .progress-section {
        margin: 2rem 0;
    }
    
    .progress-bar-custom {
        height: 30px;
        background: #e2e8f0;
        border-radius: 15px;
        overflow: hidden;
        position: relative;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #22004C 0%, #4A0080 100%);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding-right: 1rem;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        transition: width 1s ease;
    }
    
    .phase-name {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2d3748;
        margin: 1rem 0 0.5rem;
    }
    
    .phase-description {
        color: #718096;
        font-size: 1rem;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }
    
    .info-item {
        background: #f7fafc;
        padding: 1.25rem;
        border-radius: 10px;
        border-left: 4px solid #22004C;
    }
    
    .info-label {
        font-size: 0.85rem;
        color: #718096;
        font-weight: 500;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .info-value {
        font-size: 1.1rem;
        color: #2d3748;
        font-weight: 600;
    }
    
    .next-steps-list {
        list-style: none;
        padding: 0;
        margin: 1rem 0 0;
    }
    
    .next-steps-list li {
        padding: 1rem 1.25rem;
        background: #f7fafc;
        border-left: 4px solid #48bb78;
        margin-bottom: 0.75rem;
        border-radius: 6px;
        display: flex;
        align-items: center;
    }
    
    .next-steps-list li::before {
        content: '✓';
        display: inline-block;
        width: 24px;
        height: 24px;
        background: #48bb78;
        color: white;
        border-radius: 50%;
        text-align: center;
        line-height: 24px;
        margin-right: 1rem;
        font-weight: bold;
        flex-shrink: 0;
    }
    
    .assignment-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .assignment-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: #f7fafc;
        border-radius: 8px;
    }
    
    .assignment-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #22004C 0%, #4A0080 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        margin-right: 1rem;
        flex-shrink: 0;
    }
    
    .assignment-info {
        flex: 1;
    }
    
    .assignment-role {
        font-size: 0.85rem;
        color: #718096;
        font-weight: 500;
        text-transform: uppercase;
    }
    
    .assignment-name {
        font-size: 1.1rem;
        color: #2d3748;
        font-weight: 600;
        margin-top: 0.25rem;
    }
    
    .assignment-status {
        padding: 0.35rem 0.85rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .status-assigned {
        background: #fef3c7;
        color: #92400e;
    }
    
    .status-accepted {
        background: #d1fae5;
        color: #065f46;
    }
    
    .schedule-item {
        padding: 1.25rem;
        background: #ebf8ff;
        border-left: 4px solid #4299e1;
        border-radius: 8px;
        margin-bottom: 1rem;
    }
    
    .schedule-type {
        font-size: 0.85rem;
        color: #2c5282;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }
    
    .schedule-time {
        font-size: 1.1rem;
        color: #2d3748;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .schedule-place {
        color: #4a5568;
        font-size: 0.95rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #a0aec0;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .empty-state h3 {
        font-size: 1.5rem;
        color: #718096;
        margin-bottom: 0.5rem;
    }
    
    .empty-state p {
        font-size: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .btn-primary-custom {
        padding: 0.75rem 2rem;
        background: linear-gradient(135deg, #22004C 0%, #4A0080 100%);
        color: white;
        border-radius: 8px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(34, 0, 76, 0.4);
        color: white;
        text-decoration: none;
    }
    
    .btn-primary-custom i {
        margin-right: 0.5rem;
    }
    
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }
    
    .quick-action-btn {
        padding: 1.25rem;
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        color: #2d3748;
    }
    
    .quick-action-btn:hover {
        border-color: #22004C;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(34, 0, 76, 0.15);
        text-decoration: none;
        color: #22004C;
    }
    
    .quick-action-btn i {
        font-size: 2rem;
        color: #22004C;
        margin-bottom: 0.75rem;
        display: block;
    }
    
    .quick-action-btn span {
        font-weight: 500;
        font-size: 0.95rem;
    }
    
    .profile-incomplete-warning {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        padding: 2.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(220, 53, 69, 0.3);
    }
    
    .profile-incomplete-warning h2 {
        margin: 0 0 1rem;
        font-size: 1.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
    }
    
    .profile-incomplete-warning h2 i {
        margin-right: 1rem;
        font-size: 2rem;
    }
    
    .profile-incomplete-warning p {
        margin: 0.75rem 0;
        opacity: 0.95;
        font-size: 1.05rem;
    }
    
    .missing-fields-list {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 8px;
        padding: 1.25rem;
        margin: 1.25rem 0;
    }
    
    .missing-fields-list h4 {
        margin: 0 0 0.75rem;
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    .missing-fields-list ul {
        margin: 0;
        padding-left: 1.5rem;
        list-style: none;
    }
    
    .missing-fields-list ul li {
        padding: 0.35rem 0;
        font-size: 1rem;
    }
    
    .missing-fields-list ul li::before {
        content: '✗';
        display: inline-block;
        width: 20px;
        margin-right: 0.5rem;
        font-weight: bold;
    }
    
    .btn-update-profile {
        padding: 0.85rem 2.5rem;
        background: white;
        color: #dc3545;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 1.05rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        margin-top: 1rem;
    }
    
    .btn-update-profile:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 255, 255, 0.4);
        color: #dc3545;
        text-decoration: none;
    }
    
    .btn-update-profile i {
        margin-right: 0.5rem;
    }
    
    .content-blocked {
        filter: blur(3px);
        pointer-events: none;
        user-select: none;
    }
</style>

<div class="container py-4">
    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    
    <!-- Error Message -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    
    <!-- Profile Incomplete Warning -->
    @if(!$profileComplete)
        <div class="profile-incomplete-warning">
            <h2>
                <i class="fas fa-exclamation-triangle"></i>
                Profil Anda Belum Lengkap!
            </h2>
            <p><strong>Perhatian:</strong> Anda harus melengkapi profil terlebih dahulu sebelum dapat mengakses fitur-fitur sistem.</p>
            <p>Silakan {{ !Auth::user()->mahasiswa_id && !Auth::user()->dosen_id ? 'buat' : 'lengkapi' }} profil Anda untuk melanjutkan.</p>
            
            @if(count($missingFields) > 0)
                <div class="missing-fields-list">
                    <h4>{{ !Auth::user()->mahasiswa_id && !Auth::user()->dosen_id ? 'Informasi:' : 'Data yang Belum Dilengkapi:' }}</h4>
                    <ul>
                        @foreach($missingFields as $field)
                            <li>{{ $field }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            @if($profileEditRoute)
                <a href="{{ $profileEditRoute }}" class="btn-update-profile">
                    <i class="fas {{ !Auth::user()->mahasiswa_id && !Auth::user()->dosen_id ? 'fa-plus-circle' : 'fa-edit' }}"></i> 
                    {{ !Auth::user()->mahasiswa_id && !Auth::user()->dosen_id ? 'Buat Profil Sekarang' : 'Lengkapi Profil Sekarang' }}
                </a>
            @endif
        </div>
    @endif

    <!-- Header -->
    <div class="dashboard-header {{ !$profileComplete ? 'content-blocked' : '' }}">
        <h1>Dashboard {{ Auth::user()->level === 'DOSEN' ? 'Dosen' : 'Mahasiswa' }}</h1>
        <p>Selamat datang, {{ $mahasiswa->nama ?? $dosen->nama ?? Auth::user()->name }}</p>
    </div>

    <div class="{{ !$profileComplete ? 'content-blocked' : '' }}">
    @if($activeApplication)
        <!-- Current Phase -->
        <div class="dashboard-card">
            <div class="card-title">
                <i class="fas fa-chart-line"></i>
                Status Saat Ini
            </div>
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <span class="status-badge status-{{ $activeApplication->status }}">
                        {{ ucfirst($activeApplication->status) }}
                    </span>
                    <span class="ml-2 text-muted">
                        {{ ucfirst($activeApplication->type) }} - {{ ucfirst($activeApplication->stage) }}
                    </span>
                </div>
            </div>
            
            @if($currentPhase)
                <div class="progress-section">
                    <div class="progress-bar-custom">
                        <div class="progress-fill" style="width: {{ $currentPhase['progress'] }}%;">
                            {{ $currentPhase['progress'] }}%
                        </div>
                    </div>
                    <div class="phase-name">{{ $currentPhase['name'] }}</div>
                    <div class="phase-description">{{ $currentPhase['description'] }}</div>
                        </div>
                    @endif

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Jenis Skripsi</div>
                    <div class="info-value">{{ ucfirst($activeApplication->type) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tahap</div>
                    <div class="info-value">{{ ucfirst($activeApplication->stage) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tanggal Daftar</div>
                    <div class="info-value">{{ $activeApplication->submitted_at ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        @if(count($nextSteps) > 0)
            <div class="dashboard-card">
                <div class="card-title">
                    <i class="fas fa-tasks"></i>
                    Langkah Selanjutnya
                </div>
                <ul class="next-steps-list">
                    @foreach($nextSteps as $step)
                        <li>{{ $step }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Dosen Assignments -->
        @if(count($assignments) > 0)
            <div class="dashboard-card">
                <div class="card-title">
                    <i class="fas fa-user-tie"></i>
                    Dosen Pembimbing & Penguji
                </div>
                <div class="assignment-list">
                    @foreach($assignments as $assignment)
                        <div class="assignment-item">
                            <div class="assignment-icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="assignment-info">
                                <div class="assignment-role">{{ ucfirst($assignment->role) }}</div>
                                <div class="assignment-name">{{ $assignment->lecturer->nama ?? 'N/A' }}</div>
                            </div>
                            <span class="assignment-status status-{{ $assignment->status }}">
                                {{ ucfirst($assignment->status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Schedules -->
        @if(count($schedules) > 0)
            <div class="dashboard-card">
                <div class="card-title">
                    <i class="fas fa-calendar-alt"></i>
                    Jadwal
                </div>
                @foreach($schedules as $schedule)
                    <div class="schedule-item">
                        <div class="schedule-type">{{ $schedule->schedule_type }}</div>
                        <div class="schedule-time">
                            <i class="far fa-clock"></i> {{ $schedule->waktu }}
                        </div>
                        <div class="schedule-place">
                            <i class="fas fa-map-marker-alt"></i> 
                            {{ $schedule->ruang->name ?? $schedule->custom_place ?? 'Online' }}
                            @if($schedule->online_meeting)
                                <br><a href="{{ $schedule->online_meeting }}" target="_blank" class="text-primary">
                                    <i class="fas fa-video"></i> Link Meeting
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="dashboard-card">
            <div class="card-title">
                <i class="fas fa-bolt"></i>
                Aksi Cepat
            </div>
            <div class="quick-actions">
                @if($activeApplication->type === 'skripsi')
                    @if($activeApplication->stage === 'registration')
                        <a href="{{ route('frontend.skripsi-registrations.index') }}" class="quick-action-btn">
                            <i class="fas fa-file-alt"></i>
                            <span>Lihat Pendaftaran</span>
                        </a>
                    @elseif($activeApplication->stage === 'seminar')
                        <a href="{{ route('frontend.skripsi-seminars.index') }}" class="quick-action-btn">
                            <i class="fas fa-presentation"></i>
                            <span>Seminar Proposal</span>
                        </a>
                    @elseif($activeApplication->stage === 'defense')
                        <a href="{{ route('frontend.skripsi-defenses.index') }}" class="quick-action-btn">
                            <i class="fas fa-graduation-cap"></i>
                            <span>Sidang Skripsi</span>
                        </a>
                    @endif
                @elseif($activeApplication->type === 'mbkm')
                    @if($activeApplication->stage === 'registration')
                        <a href="{{ route('frontend.mbkm-registrations.index') }}" class="quick-action-btn">
                            <i class="fas fa-file-alt"></i>
                            <span>Lihat Pendaftaran MBKM</span>
                        </a>
                    @elseif($activeApplication->stage === 'seminar')
                        <a href="{{ route('frontend.mbkm-seminars.index') }}" class="quick-action-btn">
                            <i class="fas fa-presentation"></i>
                            <span>Seminar MBKM</span>
                        </a>
                    @elseif($activeApplication->stage === 'defense')
                        <a href="{{ route('frontend.skripsi-defenses.index') }}" class="quick-action-btn">
                            <i class="fas fa-graduation-cap"></i>
                            <span>Sidang Skripsi</span>
                        </a>
                    @endif
                @endif
                
                <a href="{{ route('frontend.application-reports.index') }}" class="quick-action-btn">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Lapor Kendala</span>
                </a>
                
                <a href="{{ route('frontend.applications.index') }}" class="quick-action-btn">
                    <i class="fas fa-list"></i>
                    <span>Semua Aplikasi</span>
                </a>
            </div>
        </div>

    @else
        <!-- Empty State -->
        <div class="dashboard-card">
            <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <h3>Belum Ada Aplikasi Aktif</h3>
                <p>Anda belum memiliki aplikasi skripsi yang aktif. Pilih jalur skripsi yang sesuai untuk memulai.</p>
                <a href="{{ route('frontend.choose-path') }}" class="btn-primary-custom">
                    <i class="fas fa-route"></i> Pilih Jalur Skripsi
                </a>
            </div>
        </div>

        <!-- Quick Start Guide -->
        <div class="dashboard-card">
            <div class="card-title">
                <i class="fas fa-info-circle"></i>
                Panduan Memulai
            </div>
            <ul class="next-steps-list">
                <br>
                <li>Pilih jalur skripsi (Skripsi Reguler atau MBKM)</li>
                <li>Lengkapi formulir pendaftaran topik skripsi</li>
                <li>Upload dokumen persyaratan (KHS & KRS)</li>
                <li>Tunggu verifikasi dari admin</li>
                <li>Dapatkan dosen pembimbing dan mulai bimbingan</li>
            </ul>
            <div style="margin-top: 1.5rem;">
                <a href="{{ route('frontend.choose-path') }}" class="btn-primary-custom">
                    <i class="fas fa-route"></i> Mulai Sekarang
                </a>
            </div>
        </div>
    @endif

    <!-- All Applications History -->
    @if(count($applications) > 0)
        <div class="dashboard-card">
            <div class="card-title">
                <i class="fas fa-history"></i>
                Riwayat Aplikasi
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Jenis</th>
                            <th>Tahap</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $app)
                            <tr>
                                <td><strong>{{ ucfirst($app->type) }}</strong></td>
                                <td>{{ ucfirst($app->stage) }}</td>
                                <td>
                                    <span class="status-badge status-{{ $app->status }}">
                                        {{ ucfirst($app->status) }}
                                    </span>
                                </td>
                                <td>{{ $app->submitted_at ?? $app->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('frontend.applications.show', $app->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
    </div>
    @endif
    </div><!-- End content-blocked wrapper -->
</div>
@endsection