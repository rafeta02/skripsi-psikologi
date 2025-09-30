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

class ApplicationResultSeminar extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable, HasFactory;

    public $table = 'application_result_seminars';

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
        'report_document',
        'attendance_document',
        'form_document',
        'latest_script',
        'documentation',
    ];

    protected $fillable = [
        'application_id',
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

    public function getRevisionDeadlineAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setRevisionDeadlineAttribute($value)
    {
        $this->attributes['revision_deadline'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
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

    public function getLatestScriptAttribute()
    {
        return $this->getMedia('latest_script')->last();
    }

    public function getDocumentationAttribute()
    {
        return $this->getMedia('documentation');
    }
}
