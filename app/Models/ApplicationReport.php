<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ApplicationReport extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasFactory;

    public $table = 'application_reports';

    protected $appends = [
        'report_document',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const STATUS_SELECT = [
        'submitted' => 'Submitted',
        'reviewed'  => 'Reviewed',
    ];

    protected $fillable = [
        'application_id',
        'report_text',
        'period',
        'status',
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

    public function getReportDocumentAttribute()
    {
        return $this->getMedia('report_document');
    }
}
