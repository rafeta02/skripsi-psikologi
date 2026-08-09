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

/**
 * Laporan hasil Review Kelayakan Proposal — jalur Skripsi Reguler.
 * Terpisah dari ApplicationResultSeminar (MBKM).
 */
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
        'approved_no_revision'    => 'Disetujui tanpa perbaikan',
        'approved_minor_revision' => 'Disetujui dengan perbaikan minor',
        'approved_major_revision' => 'Disetujui dengan perbaikan mayor',
    ];

    /** Nilai lama sebelum alur Reguler diperbarui */
    public const RESULT_SELECT_LEGACY = [
        'passed'   => 'Lulus',
        'revision' => 'Revisi',
        'failed'   => 'Tidak Lulus',
    ];

    protected $appends = [
        'reviewer_feedback_forms',
        'application_letter',
        'minutes_document',
        'proposal_manuscript',
        'research_ethics_form',
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

    public static function allResultLabels(): array
    {
        return self::RESULT_SELECT + self::RESULT_SELECT_LEGACY;
    }

    public function resultLabel(): string
    {
        return self::allResultLabels()[$this->result] ?? ($this->result ?? '-');
    }

    public function isEligibleOutcome(): bool
    {
        return in_array($this->result, [
            'approved_no_revision',
            'approved_minor_revision',
            'approved_major_revision',
            'passed',
        ], true);
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
        if (! $this->application) {
            return;
        }

        if ($this->isRejectedByAdmin()) {
            $status = 'rejected';
        } else {
            $status = match ($this->result) {
                'approved_no_revision', 'approved_minor_revision', 'approved_major_revision', 'passed'
                    => $this->isValidatedByAdmin() ? 'approved' : 'submitted',
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

        if (! $this->isEligibleOutcome()) {
            return match ($this->result) {
                'revision' => '<span class="badge badge-warning badge-lg">Revisi</span>',
                'failed' => '<span class="badge badge-danger badge-lg">Tidak Lulus</span>',
                default => '<span class="badge badge-secondary badge-lg">'.e($this->resultLabel()).'</span>',
            };
        }

        if ($this->isValidatedByAdmin()) {
            return '<span class="badge badge-success badge-lg">Divalidasi Admin</span>';
        }

        return '<span class="badge badge-warning badge-lg">Menunggu Validasi Admin</span>';
    }

    public function getRevisionDeadlineAttribute($value)
    {
        if (! $value) {
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
        if (! $value) {
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

    public function getReviewerFeedbackFormsAttribute()
    {
        $current = $this->getMedia('reviewer_feedback_forms');
        if ($current->isNotEmpty()) {
            return $current;
        }

        return $this->getMedia('form_document');
    }

    public function getApplicationLetterAttribute()
    {
        return $this->getMedia('application_letter')->last();
    }

    public function getMinutesDocumentAttribute()
    {
        return $this->getMedia('minutes_document')->last();
    }

    public function getProposalManuscriptAttribute()
    {
        return $this->getMedia('proposal_manuscript')->last()
            ?: $this->getMedia('latest_script')->last();
    }

    public function getResearchEthicsFormAttribute()
    {
        return $this->getMedia('research_ethics_form')->last();
    }

    /** @deprecated Legacy accessor */
    public function getFormDocumentAttribute()
    {
        return $this->reviewer_feedback_forms;
    }

    /** @deprecated Legacy accessor */
    public function getLatestScriptAttribute()
    {
        return $this->proposal_manuscript;
    }
}
