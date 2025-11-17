<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationAssignment extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'application_assignments';

    protected $dates = [
        'assigned_at',
        'responded_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const STATUS_SELECT = [
        'assigned' => 'Assigned',
        'accepted' => 'Accepted',
        'rejected' => 'Rejected',
    ];

    public const ROLE_SELECT = [
        'supervisor' => 'Supervisor',
        'reviewer'   => 'Reviewer',
        'examiner'   => 'Examiner',
    ];

    protected $fillable = [
        'application_id',
        'lecturer_id',
        'role',
        'status',
        'assigned_at',
        'responded_at',
        'note',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function lecturer()
    {
        return $this->belongsTo(Dosen::class, 'lecturer_id');
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
        
        // If already a Carbon instance, just format it
        if ($value instanceof Carbon) {
            $this->attributes['assigned_at'] = $value->format('Y-m-d H:i:s');
            return;
        }
        
        // Otherwise, parse from string
        $this->attributes['assigned_at'] = Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s');
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
        
        // If already a Carbon instance, just format it
        if ($value instanceof Carbon) {
            $this->attributes['responded_at'] = $value->format('Y-m-d H:i:s');
            return;
        }
        
        // Otherwise, parse from string
        $this->attributes['responded_at'] = Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s');
    }
}
