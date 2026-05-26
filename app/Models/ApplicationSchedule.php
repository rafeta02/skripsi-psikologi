<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\FileNamingTrait;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ApplicationSchedule extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable, HasFactory, FileNamingTrait;

    public $table = 'application_schedules';

    protected $dates = [
        'waktu',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const SCHEDULE_TYPE_SELECT = [
        'seminar'         => 'Seminar Proposal',
        'defense'         => 'Sidang Skripsi',
        'skripsi_seminar' => 'Seminar Skripsi',
        'mbkm_seminar'    => 'Seminar MBKM',
        'skripsi_defense' => 'Sidang Skripsi',
    ];

    protected $fillable = [
        'application_id',
        'schedule_type',
        'waktu',
        'ruang_id',
        'custom_place',
        'online_meeting',
        'note',
        'created_at',
        'updated_at',
        'deleted_at',
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

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function getWaktuAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setWaktuAttribute($value)
    {
        if (!$value) {
            $this->attributes['waktu'] = null;
            return;
        }
        
        // If value is already a Carbon instance or DateTime
        if ($value instanceof \DateTimeInterface) {
            $this->attributes['waktu'] = $value->format('Y-m-d H:i:s');
            return;
        }
        
        // If value is a string, try to parse it
        try {
            $this->attributes['waktu'] = Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            // If parsing fails, try standard parse
            $this->attributes['waktu'] = Carbon::parse($value)->format('Y-m-d H:i:s');
        }
    }

    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'ruang_id');
    }

    public function isApprovedByAdmin(): bool
    {
        if ($this->application?->status === 'scheduled') {
            return true;
        }

        return ApplicationAction::where('application_id', $this->application_id)
            ->where('action_type', 'schedule_approved')
            ->where('metadata->schedule_id', $this->id)
            ->exists();
    }

    public function isRejectedByAdmin(): bool
    {
        return ApplicationAction::where('application_id', $this->application_id)
            ->where('action_type', 'schedule_rejected')
            ->exists()
            && !$this->isApprovedByAdmin();
    }

    /**
     * Status validasi jadwal oleh admin (bukan status aplikasi tahap sebelumnya).
     */
    public function adminValidationStatus(): array
    {
        if ($this->isRejectedByAdmin()) {
            return [
                'label' => 'Ditolak',
                'badge' => 'danger',
                'icon' => 'times-circle',
                'detail' => 'Jadwal ditolak admin. Silakan ajukan jadwal baru.',
            ];
        }

        if ($this->isApprovedByAdmin()) {
            return [
                'label' => 'Dijadwalkan',
                'badge' => 'success',
                'icon' => 'calendar-check',
                'detail' => 'Jadwal telah diverifikasi dan dikonfirmasi admin.',
            ];
        }

        return [
            'label' => 'Menunggu Verifikasi',
            'badge' => 'warning',
            'icon' => 'clock',
            'detail' => 'Jadwal diajukan dan menunggu persetujuan admin.',
        ];
    }
}
