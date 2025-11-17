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

class ApplicationResultDefense extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable, HasFactory, FileNamingTrait;

    public $table = 'application_result_defenses';

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

    protected $fillable = [
        'application_id',
        'result',
        'note',
        'revision_deadline',
        'final_grade',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'documentation',
        'invitation_document',
        'feedback_document',
        'minutes_document',
        'latest_script',
        'approval_page',
        'report_document',
        'revision_approval_sheet',
        'attendance_document',
        'form_document',
        'certificate_document',
        'publication_document',
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

    public function getRevisionDeadlineAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setRevisionDeadlineAttribute($value)
    {
        $this->attributes['revision_deadline'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getDocumentationAttribute()
    {
        return $this->getMedia('documentation');
    }

    public function getInvitationDocumentAttribute()
    {
        return $this->getMedia('invitation_document');
    }

    public function getFeedbackDocumentAttribute()
    {
        return $this->getMedia('feedback_document');
    }

    public function getMinutesDocumentAttribute()
    {
        return $this->getMedia('minutes_document')->last();
    }

    public function getLatestScriptAttribute()
    {
        return $this->getMedia('latest_script')->last();
    }

    public function getApprovalPageAttribute()
    {
        return $this->getMedia('approval_page')->last();
    }

    public function getReportDocumentAttribute()
    {
        return $this->getMedia('report_document');
    }

    public function getRevisionApprovalSheetAttribute()
    {
        return $this->getMedia('revision_approval_sheet');
    }

    public function getAttendanceDocumentAttribute()
    {
        return $this->getMedia('attendance_document')->last();
    }

    public function getFormDocumentAttribute()
    {
        return $this->getMedia('form_document')->last();
    }

    public function getCertificateDocumentAttribute()
    {
        return $this->getMedia('certificate_document')->last();
    }

    public function getPublicationDocumentAttribute()
    {
        return $this->getMedia('publication_document')->last();
    }
}
