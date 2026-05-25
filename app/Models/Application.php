<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Application extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable, HasFactory;

    public $table = 'applications';

    public const TYPE_SELECT = [
        'skripsi' => 'skripsi',
        'mbkm'    => 'mbkm',
    ];

    protected $dates = [
        'submitted_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const STAGE_SELECT = [
        'registration' => 'registration',
        'seminar'      => 'seminar',
        'defense'      => 'defense',
    ];

    protected $fillable = [
        'mahasiswa_id',
        'type',
        'stage',
        'status',
        'submitted_at',
        'notes',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const STATUS_SELECT = [ 
        'submitted' => 'submitted',
        'approved'  => 'approved',
        'rejected'  => 'rejected',
        'scheduled' => 'scheduled',
        'result'    => 'result',
        'revision'  => 'revision',
        'done'      => 'done',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function getSubmittedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setSubmittedAtAttribute($value)
    {
        if (!$value) {
            $this->attributes['submitted_at'] = null;
            return;
        }
        
        // If value is already a Carbon instance or DateTime
        if ($value instanceof \DateTimeInterface) {
            $this->attributes['submitted_at'] = $value->format('Y-m-d H:i:s');
            return;
        }
        
        // If value is a string, try to parse it
        try {
            $this->attributes['submitted_at'] = Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            // If parsing fails, try standard parse
            $this->attributes['submitted_at'] = Carbon::parse($value)->format('Y-m-d H:i:s');
        }
    }

    public function actions()
    {
        return $this->hasMany(ApplicationAction::class);
    }

    public function skripsiRegistration()
    {
        return $this->hasOne(SkripsiRegistration::class);
    }

    public function mbkmRegistration()
    {
        return $this->hasOne(MbkmRegistration::class);
    }

    public function assignments()
    {
        return $this->hasMany(ApplicationAssignment::class, 'application_id');
    }

    /**
     * Registration is fully accepted for mahasiswa only after supervisor accepts assignment.
     */
    public function isRegistrationAcceptedBySupervisor(): bool
    {
        if ($this->stage !== 'registration') {
            return $this->status === 'approved';
        }

        return $this->assignments()
            ->where('role', 'supervisor')
            ->where('status', 'accepted')
            ->exists();
    }

    /**
     * Human-readable registration status for mahasiswa UI.
     */
    public function getRegistrationStatusForMahasiswa(): array
    {
        if ($this->stage !== 'registration') {
            return $this->mapApplicationStatusForMahasiswa();
        }

        if ($this->status === 'revision') {
            return [
                'badge' => 'warning',
                'icon' => 'edit',
                'label' => 'Perlu Revisi',
                'detail' => 'Perbaiki pendaftaran sesuai catatan yang diberikan',
            ];
        }

        if ($this->status === 'rejected') {
            return [
                'badge' => 'danger',
                'icon' => 'times-circle',
                'label' => 'Ditolak',
                'detail' => 'Pendaftaran ditolak',
            ];
        }

        $supervisorAssignment = $this->assignments
            ->where('role', 'supervisor')
            ->first();

        if ($supervisorAssignment?->status === 'rejected') {
            return [
                'badge' => 'danger',
                'icon' => 'times-circle',
                'label' => 'Ditolak',
                'detail' => 'Dosen pembimbing menolak penugasan',
            ];
        }

        if ($this->isRegistrationAcceptedBySupervisor()) {
            return [
                'badge' => 'success',
                'icon' => 'check-circle',
                'label' => 'Diterima',
                'detail' => 'Pendaftaran disetujui oleh dosen pembimbing',
            ];
        }

        $adminAssigned = false;
        if ($this->type === 'skripsi' && $this->skripsiRegistration) {
            $adminAssigned = (bool) $this->skripsiRegistration->assigned_supervisor_id;
        } elseif ($this->type === 'mbkm' && $this->mbkmRegistration) {
            $adminAssigned = (bool) $this->mbkmRegistration->approval_date;
        }

        if ($adminAssigned || ($supervisorAssignment && $supervisorAssignment->status === 'assigned')) {
            return [
                'badge' => 'warning',
                'icon' => 'clock',
                'label' => 'Menunggu Dosen',
                'detail' => 'Admin telah menugaskan dosen pembimbing. Menunggu persetujuan dosen.',
            ];
        }

        return [
            'badge' => 'warning',
            'icon' => 'clock',
            'label' => 'Menunggu Review',
            'detail' => 'Menunggu verifikasi admin',
        ];
    }

    protected function mapApplicationStatusForMahasiswa(): array
    {
        return match ($this->status) {
            'approved' => ['badge' => 'success', 'icon' => 'check-circle', 'label' => 'Disetujui', 'detail' => 'Telah disetujui'],
            'rejected' => ['badge' => 'danger', 'icon' => 'times-circle', 'label' => 'Ditolak', 'detail' => 'Ditolak'],
            'revision' => ['badge' => 'warning', 'icon' => 'edit', 'label' => 'Perlu Revisi', 'detail' => 'Memerlukan perbaikan'],
            'scheduled' => ['badge' => 'info', 'icon' => 'calendar-check', 'label' => 'Terjadwal', 'detail' => 'Sudah dijadwalkan'],
            'done' => ['badge' => 'secondary', 'icon' => 'flag-checkered', 'label' => 'Selesai', 'detail' => 'Proses selesai'],
            default => ['badge' => 'warning', 'icon' => 'clock', 'label' => 'Menunggu Review', 'detail' => 'Menunggu verifikasi'],
        };
    }

    /**
     * Check if mahasiswa already has an active application
     */
    public static function hasActiveApplication($mahasiswaId)
    {
        return self::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', ['submitted', 'approved', 'scheduled'])
            ->exists();
    }

    /**
     * Get active application for mahasiswa
     */
    public static function getActiveApplication($mahasiswaId)
    {
        return self::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('status', ['submitted', 'approved', 'scheduled'])
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Scope to get active applications
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['submitted', 'approved', 'scheduled']);
    }
}

