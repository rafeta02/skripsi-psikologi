<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\FileNamingTrait;
use App\Traits\MultiTenantModelTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SkripsiSeminar extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, InteractsWithMedia, Auditable, HasFactory, FileNamingTrait;

    public $table = 'skripsi_seminars';

    protected $dates = [
        'admin_validated_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'proposal_document',
        'approval_document',
        'plagiarism_document',
    ];

    protected $fillable = [
        'application_id',
        'reviewer_1_id',
        'reviewer_2_id',
        'admin_validated_at',
        'admin_validated_by',
        'admin_revision_notes',
        'title',
        'title_en',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by_id',
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

    public function getProposalDocumentAttribute()
    {
        return $this->getMedia('proposal_document')->last();
    }

    public function getApprovalDocumentAttribute()
    {
        return $this->getMedia('approval_document')->last();
    }

    public function getPlagiarismDocumentAttribute()
    {
        return $this->getMedia('plagiarism_document')->last();
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function reviewer1()
    {
        return $this->belongsTo(Dosen::class, 'reviewer_1_id');
    }

    public function reviewer2()
    {
        return $this->belongsTo(Dosen::class, 'reviewer_2_id');
    }

    public function reviewerAssignments()
    {
        return $this->hasMany(ApplicationAssignment::class, 'skripsi_seminar_id')
            ->where('role', 'reviewer');
    }

    public function adminStatusBadgeHtml(): string
    {
        $application = $this->application;
        if (! $application) {
            return '<span class="badge badge-secondary">-</span>';
        }

        if ($application->status === 'rejected') {
            return '<span class="badge badge-danger">Ditolak</span>';
        }

        if ($application->status === 'revision') {
            return '<span class="badge badge-warning">Perlu Revisi</span>';
        }

        if (! $this->admin_validated_at) {
            return '<span class="badge badge-info">Menunggu Review Admin</span>';
        }

        $assignments = ApplicationAssignment::where('skripsi_seminar_id', $this->id)
            ->where('role', 'reviewer')
            ->whereNotIn('status', ['replaced'])
            ->get();

        if ($assignments->isEmpty()) {
            return '<span class="badge badge-info">Menunggu Review</span>';
        }

        if ($assignments->contains(fn ($a) => in_array($a->status, ['expired'], true))) {
            return '<span class="badge badge-dark">Reviewer Kedaluwarsa</span>';
        }

        if ($assignments->where('status', 'feedback_submitted')->count() >= 2) {
            return '<span class="badge badge-success">Review Selesai</span>';
        }

        if ($assignments->contains(fn ($a) => $a->status === 'assigned')) {
            return '<span class="badge badge-warning">Menunggu Respons Reviewer</span>';
        }

        if ($assignments->contains(fn ($a) => $a->status === 'accepted')) {
            return '<span class="badge badge-primary">Menunggu Feedback Reviewer</span>';
        }

        return match ($application->status) {
            'submitted' => '<span class="badge badge-info">Dalam Proses Review</span>',
            'approved' => '<span class="badge badge-success">Disetujui</span>',
            default => '<span class="badge badge-secondary">' . e(ucfirst($application->status)) . '</span>',
        };
    }
}
