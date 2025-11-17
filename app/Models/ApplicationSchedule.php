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

    protected $appends = [
        'approval_form',
    ];

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
        $this->attributes['waktu'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'ruang_id');
    }

    public function getApprovalFormAttribute()
    {
        return $this->getMedia('approval_form');
    }
}
