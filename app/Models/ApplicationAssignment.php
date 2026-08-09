<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\FileNamingTrait;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ApplicationAssignment extends Model implements HasMedia
{
    use SoftDeletes, Auditable, HasFactory, InteractsWithMedia, FileNamingTrait;

    public $table = 'application_assignments';

    protected $dates = [
        'assigned_at',
        'responded_at',
        'response_deadline',
        'feedback_deadline',
        'feedback_submitted_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const STATUS_SELECT = [
        'assigned'           => 'Assigned',
        'accepted'           => 'Accepted',
        'rejected'           => 'Rejected',
        'expired'            => 'Expired',
        'feedback_submitted' => 'Feedback Submitted',
        'replaced'           => 'Replaced',
        'informed'           => 'Informed',
    ];

    public const FEEDBACK_RESULT_SELECT = [
        'passed'   => 'Lulus (Passed)',
        'revision' => 'Revisi (Revision)',
        'failed'   => 'Tidak Lulus (Failed)',
    ];

    public const ROLE_SELECT = [
        'supervisor'           => 'Supervisor',
        'supervisor_informant' => 'Supervisor Informant',
        'reviewer'             => 'Reviewer',
        'examiner'             => 'Examiner',
    ];

    protected $appends = [
        'feedback_document',
    ];

    protected $fillable = [
        'application_id',
        'skripsi_seminar_id',
        'lecturer_id',
        'role',
        'reviewer_slot',
        'status',
        'assigned_at',
        'responded_at',
        'response_deadline',
        'feedback_deadline',
        'note',
        'rejection_reason',
        'feedback_result',
        'feedback_note',
        'feedback_submitted_at',
        'replaced_by_assignment_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $assignment) {
            $application = Application::find($assignment->application_id);
            if ($application && $application->is_group_mirror) {
                throw new \RuntimeException(
                    'Tidak dapat membuat penugasan pada Application mirror MBKM. Gunakan aplikasi ketua kelompok.'
                );
            }
        });
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function skripsiSeminar(): BelongsTo
    {
        return $this->belongsTo(SkripsiSeminar::class, 'skripsi_seminar_id');
    }

    public function replacedBy(): BelongsTo
    {
        return $this->belongsTo(self::class, 'replaced_by_assignment_id');
    }

    public function scopeWithoutGroupMirrors($query)
    {
        return $query->whereHas('application', function ($q) {
            $q->where(function ($inner) {
                $inner->where('is_group_mirror', false)
                    ->orWhereNull('is_group_mirror');
            });
        });
    }

    public function scopeActiveReviewers($query)
    {
        return $query->where('role', 'reviewer')
            ->whereNotIn('status', ['replaced', 'expired']);
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'lecturer_id');
    }

    public function isProposalReviewer(): bool
    {
        return $this->role === 'reviewer'
            && $this->skripsi_seminar_id
            && $this->application?->type === 'skripsi'
            && $this->application?->stage === 'seminar';
    }

    public function isSupervisorAssignment(): bool
    {
        return $this->role === 'supervisor';
    }

    public function isSupervisorInformant(): bool
    {
        return $this->role === 'supervisor_informant'
            && $this->skripsi_seminar_id
            && $this->application?->type === 'skripsi'
            && $this->application?->stage === 'seminar';
    }

    public function showsProposalReviewDocuments(): bool
    {
        return $this->isProposalReviewer() || $this->isSupervisorInformant();
    }

    public function requiresFeedback(): bool
    {
        return $this->isProposalReviewer();
    }

    public function isPendingAction(): bool
    {
        if ($this->isSupervisorInformant()) {
            return false;
        }

        if ($this->status === 'assigned') {
            return true;
        }

        return $this->status === 'accepted' && $this->requiresFeedback();
    }

    public function displayStatusLabel(): array
    {
        return match (true) {
            $this->isSupervisorInformant() && $this->status === 'informed' => ['Informasi', 'secondary'],
            $this->status === 'assigned' => ['Menunggu Respons', 'warning'],
            $this->status === 'accepted' && $this->requiresFeedback() => ['Menunggu Feedback', 'info'],
            $this->status === 'accepted' => ['Diterima', 'success'],
            $this->status === 'feedback_submitted' => ['Feedback Terkirim', 'success'],
            $this->status === 'rejected' => ['Ditolak', 'danger'],
            $this->status === 'expired' => ['Kedaluwarsa', 'dark'],
            $this->status === 'replaced' => ['Diganti', 'secondary'],
            $this->status === 'informed' => ['Informasi', 'secondary'],
            default => [ucfirst($this->status ?? '-'), 'secondary'],
        };
    }

    public function scopePendingAction($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'assigned')
                ->orWhere(function ($inner) {
                    $inner->where('status', 'accepted')
                        ->where('role', 'reviewer')
                        ->whereNotNull('skripsi_seminar_id')
                        ->whereHas('application', function ($app) {
                            $app->where('type', 'skripsi')->where('stage', 'seminar');
                        });
                });
        });
    }

    public function canRespondToAssignment(): bool
    {
        return $this->status === 'assigned'
            && (!$this->getRawOriginal('response_deadline') || now()->lte(Carbon::parse($this->getRawOriginal('response_deadline'))));
    }

    public function canSubmitFeedback(): bool
    {
        return $this->status === 'accepted'
            && !$this->getRawOriginal('feedback_submitted_at')
            && (!$this->getRawOriginal('feedback_deadline') || now()->lte(Carbon::parse($this->getRawOriginal('feedback_deadline'))));
    }

    public function isFeedbackOverdue(): bool
    {
        if ($this->status !== 'accepted' || $this->getRawOriginal('feedback_submitted_at')) {
            return false;
        }

        $deadline = $this->getRawOriginal('feedback_deadline');

        return $deadline && now()->gt(Carbon::parse($deadline));
    }

    public function statusBadgeHtml(): string
    {
        [$label, $badge] = $this->displayStatusLabel();

        return sprintf('<span class="badge badge-%s">%s</span>', $badge, e($label));
    }

    public function getFeedbackDocumentAttribute()
    {
        return $this->getMedia('feedback_document')->last();
    }

    public function getAssignedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setAssignedAtAttribute($value)
    {
        if (!$value) {
            $this->attributes['assigned_at'] = null;
            return;
        }

        if ($value instanceof Carbon || $value instanceof \DateTimeInterface) {
            $this->attributes['assigned_at'] = $value->format('Y-m-d H:i:s');
            return;
        }

        try {
            $this->attributes['assigned_at'] = Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            $this->attributes['assigned_at'] = Carbon::parse($value)->format('Y-m-d H:i:s');
        }
    }

    public function getRespondedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setRespondedAtAttribute($value)
    {
        if (!$value) {
            $this->attributes['responded_at'] = null;
            return;
        }

        if ($value instanceof Carbon || $value instanceof \DateTimeInterface) {
            $this->attributes['responded_at'] = $value->format('Y-m-d H:i:s');
            return;
        }

        try {
            $this->attributes['responded_at'] = Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            $this->attributes['responded_at'] = Carbon::parse($value)->format('Y-m-d H:i:s');
        }
    }
}
