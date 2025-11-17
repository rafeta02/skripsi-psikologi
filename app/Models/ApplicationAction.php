<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'action_type',
        'action_by',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    const ACTION_APPROVED = 'approved';
    const ACTION_REJECTED = 'rejected';
    const ACTION_REVISION_REQUESTED = 'revision_requested';
    const ACTION_SCHEDULED = 'scheduled';
    const ACTION_COMPLETED = 'completed';
    const ACTION_SUPERVISOR_ASSIGNED = 'supervisor_assigned';
    const ACTION_SUPERVISOR_CHANGED = 'supervisor_changed';

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function actionBy()
    {
        return $this->belongsTo(User::class, 'action_by');
    }

    /**
     * Scope for specific action type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('action_type', $type);
    }

    /**
     * Scope for latest action
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}

