<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminAlert extends Model
{
    public const TYPE_REVIEWER_NO_RESPONSE = 'reviewer_no_response';
    public const TYPE_REVIEWER_FEEDBACK_WARNING = 'reviewer_feedback_warning';
    public const TYPE_REVIEWER_FEEDBACK_OVERDUE = 'reviewer_feedback_overdue';

    protected $fillable = [
        'alert_type',
        'application_id',
        'assignment_id',
        'dosen_id',
        'severity',
        'message',
        'metadata',
        'resolved_at',
        'resolved_by',
    ];

    protected $casts = [
        'metadata' => 'array',
        'resolved_at' => 'datetime',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(ApplicationAssignment::class, 'assignment_id');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    public function scopeUnresolved($query)
    {
        return $query->whereNull('resolved_at');
    }

    public function resolve(?int $userId = null): void
    {
        $this->update([
            'resolved_at' => now(),
            'resolved_by' => $userId ?? auth()->id(),
        ]);
    }
}
