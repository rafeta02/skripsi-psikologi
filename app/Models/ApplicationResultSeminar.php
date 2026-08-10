<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\FileNamingTrait;
use App\Traits\MbkmDocumentNamingTrait;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ApplicationResultSeminar extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable, HasFactory, FileNamingTrait, MbkmDocumentNamingTrait {
        MbkmDocumentNamingTrait::generateFileName insteadof FileNamingTrait;
    }

    public $table = 'application_result_seminars';

    protected $dates = [
        'revision_deadline',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const RESULT_SELECT = [
        'minor' => 'Layak Dilaksanakan dengan perbaikan minor',
        'mayor' => 'Layak Dilaksanakan dengan perbaikan mayor',
    ];

    /** Legacy values kept for data lama / tampilan historis */
    public const RESULT_SELECT_LEGACY = [
        'passed'   => 'Lulus',
        'revision' => 'Revisi',
        'failed'   => 'Tidak Lulus',
    ];

    protected $appends = [
        'report_document',
        'attendance_document',
        'form_document',
        'krs_latest',
        'latest_script',
        'documentation',
    ];

    protected $fillable = [
        'application_id',
        'result',
        'note',
        'meeting_recording_link',
        'revision_deadline',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public static function allResultLabels(): array
    {
        return self::RESULT_SELECT + self::RESULT_SELECT_LEGACY;
    }

    public function resultLabel(): string
    {
        return self::allResultLabels()[$this->result] ?? ($this->result ?? '-');
    }

    /**
     * Hasil yang layak dilanjutkan ke validasi admin / sidang.
     */
    public function isEligibleOutcome(): bool
    {
        return in_array($this->result, ['passed', 'minor', 'mayor'], true);
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

    public function isValidatedByAdmin(): bool
    {
        return ApplicationAction::where('application_id', $this->application_id)
            ->where('action_type', 'result_seminar_approved')
            ->exists();
    }

    public function isRejectedByAdmin(): bool
    {
        return ApplicationAction::where('application_id', $this->application_id)
            ->where('action_type', 'result_seminar_rejected')
            ->exists();
    }

    /**
     * Keep applications.status aligned with laporan hasil (not seminar registration approval).
     */
    public function syncApplicationStatus(): void
    {
        if (!$this->application) {
            return;
        }

        if ($this->isRejectedByAdmin()) {
            $status = 'rejected';
        } else {
            $status = match ($this->result) {
                'passed', 'minor', 'mayor' => $this->isValidatedByAdmin() ? 'approved' : 'submitted',
                'revision' => 'revision',
                'failed' => 'rejected',
                default => $this->application->status,
            };
        }

        if ($this->application->status !== $status) {
            $this->application->update(['status' => $status]);
            $this->application->refresh();
        }
    }

    public function adminValidationStatusHtml(): string
    {
        if ($this->isRejectedByAdmin()) {
            return '<span class="badge badge-danger badge-lg">Ditolak Admin</span>';
        }

        if (!$this->isEligibleOutcome()) {
            return match ($this->result) {
                'revision' => '<span class="badge badge-warning badge-lg">Revisi</span>',
                'failed' => '<span class="badge badge-danger badge-lg">Tidak Lulus</span>',
                default => '<span class="badge badge-secondary badge-lg">' . e($this->resultLabel()) . '</span>',
            };
        }

        if ($this->isValidatedByAdmin()) {
            return '<span class="badge badge-success badge-lg">Divalidasi Admin</span>';
        }

        return '<span class="badge badge-warning badge-lg">Menunggu Validasi Admin</span>';
    }

    public function getRevisionDeadlineAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setRevisionDeadlineAttribute($value)
    {
        if (!$value) {
            $this->attributes['revision_deadline'] = null;
            return;
        }

        try {
            $this->attributes['revision_deadline'] = Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d');
        } catch (\Exception $e) {
            $this->attributes['revision_deadline'] = Carbon::parse($value)->format('Y-m-d');
        }
    }

    public function getReportDocumentAttribute()
    {
        return $this->getMedia('report_document');
    }

    public function getAttendanceDocumentAttribute()
    {
        return $this->getMedia('attendance_document')->last();
    }

    public function getFormDocumentAttribute()
    {
        return $this->getMedia('form_document');
    }

    public function getKrsLatestAttribute()
    {
        return $this->getMedia('krs_latest')->last();
    }

    public function getLatestScriptAttribute()
    {
        return $this->getMedia('latest_script')->last();
    }

    public function getDocumentationAttribute()
    {
        return $this->getMedia('documentation');
    }
}
