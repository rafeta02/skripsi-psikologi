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

class SkripsiRegistration extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, InteractsWithMedia, Auditable, HasFactory, FileNamingTrait;

    public $table = 'skripsi_registrations';

    protected $appends = [
        'khs_all',
        'krs_latest',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'application_id',
        'theme_id',
        'title',
        'abstract',
        'tps_lecturer_id',
        'preference_supervision_id',
        'assigned_supervisor_id',
        'approval_date',
        'rejection_reason',
        'revision_notes',
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

    public function theme()
    {
        return $this->belongsTo(Keilmuan::class, 'theme_id');
    }

    public function tps_lecturer()
    {
        return $this->belongsTo(Dosen::class, 'tps_lecturer_id');
    }

    public function preference_supervision()
    {
        return $this->belongsTo(Dosen::class, 'preference_supervision_id');
    }

    public function getKhsAllAttribute()
    {
        return $this->getMedia('khs_all');
    }

    public function getKrsLatestAttribute()
    {
        return $this->getMedia('krs_latest')->last();
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function assigned_supervisor()
    {
        return $this->belongsTo(Dosen::class, 'assigned_supervisor_id');
    }
}
