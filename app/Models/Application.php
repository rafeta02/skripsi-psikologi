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

class Application extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable, HasFactory;

    public $table = 'applications';

    public const TYPE_SELECT = [
        'skripsi' => 'skripsi',
        'mbkm'    => 'mbkm',
    ];

    protected $dates = [
        'submitted_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const STAGE_SELECT = [
        'registration' => 'registration',
        'seminar'      => 'seminar',
        'defense'      => 'defense',
    ];

    protected $fillable = [
        'mahasiswa_id',
        'type',
        'stage',
        'status',
        'submitted_at',
        'notes',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const STATUS_SELECT = [
        'submitted' => 'submitted',
        'approved'  => 'approved',
        'rejected'  => 'rejected',
        'scheduled' => 'scheduled',
        'done'      => 'done',
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

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function getSubmittedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setSubmittedAtAttribute($value)
    {
        $this->attributes['submitted_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }
}
