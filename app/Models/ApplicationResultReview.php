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

class ApplicationResultReview extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable, HasFactory, FileNamingTrait;

    public $table = 'application_result_reviews';

    protected $dates = [
        'revision_deadline',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const RESULT_SELECT = [
        'passed'   => 'Passed',
        'revision' => 'Revision',
        'failed'   => 'Failed',
    ];

    protected $appends = [
        'form_document',
        'latest_script',
    ];

    protected $fillable = [
        'application_id',
        'reviewer_1_assignment_id',
        'reviewer_2_assignment_id',
        'result',
        'note',
        'revision_deadline',
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

    public function reviewer1Assignment()
    {
        return $this->belongsTo(ApplicationAssignment::class, 'reviewer_1_assignment_id');
    }

    public function reviewer2Assignment()
    {
        return $this->belongsTo(ApplicationAssignment::class, 'reviewer_2_assignment_id');
    }

    public function isValidatedByAdmin(): bool
    {
        return ApplicationAction::where('application_id', $this->application_id)
            ->where('action_type', 'result_review_approved')
            ->exists();
    }

    public function isRejectedByAdmin(): bool
    {
        return ApplicationAction::where('application_id', $this->application_id)
            ->where('action_type', 'result_review_rejected')
            ->exists();
    }

    public function syncApplicationStatus(): void
    {
        if (!$this->application) {
            return;
        }

        if ($this->isRejectedByAdmin()) {
            $status = 'rejected';
        } else {
            $status = match ($this->result) {
                'passed' => $this->isValidatedByAdmin() ? 'approved' : 'submitted',
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

    public function getRevisionDeadlineAttribute($value)
    {
        if (!$value) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance($value)->format(config('panel.date_format'));
        }

        try {
            return Carbon::createFromFormat('Y-m-d', substr((string) $value, 0, 10))
                ->format(config('panel.date_format'));
        } catch (\Throwable $e) {
            return Carbon::parse($value)->format(config('panel.date_format'));
        }
    }

    public function setRevisionDeadlineAttribute($value)
    {
        if (!$value) {
            $this->attributes['revision_deadline'] = null;
            return;
        }

        if ($value instanceof \DateTimeInterface) {
            $this->attributes['revision_deadline'] = Carbon::instance($value)->format('Y-m-d');
            return;
        }

        try {
            $this->attributes['revision_deadline'] = Carbon::createFromFormat(
                config('panel.date_format'),
                $value
            )->format('Y-m-d');
        } catch (\Throwable $e) {
            $this->attributes['revision_deadline'] = Carbon::parse($value)->format('Y-m-d');
        }
    }

    public function getFormDocumentAttribute()
    {
        return $this->getMedia('form_document');
    }

    public function getLatestScriptAttribute()
    {
        return $this->getMedia('latest_script')->last();
    }
}

