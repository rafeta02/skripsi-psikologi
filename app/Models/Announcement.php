<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'announcements';

    public const STATUS_SELECT = [
        'draft'     => 'Draft',
        'published' => 'Published',
    ];

    public const AUDIENCE_SELECT = [
        'all'       => 'Semua',
        'mahasiswa' => 'Mahasiswa',
        'dosen'     => 'Dosen',
    ];

    protected $dates = [
        'published_at',
        'expires_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'is_pinned'    => 'boolean',
        'published_at' => 'datetime',
        'expires_at'   => 'datetime',
    ];

    protected $fillable = [
        'title',
        'body',
        'audience',
        'status',
        'published_at',
        'expires_at',
        'is_pinned',
        'created_by_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query
            ->where('status', 'published')
            ->where(function (Builder $q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->where(function (Builder $q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    public function scopeForAudience(Builder $query, string $audience): Builder
    {
        return $query->whereIn('audience', ['all', $audience]);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at');
    }

    public function audienceLabel(): string
    {
        return self::AUDIENCE_SELECT[$this->audience] ?? ucfirst($this->audience);
    }

    public function statusLabel(): string
    {
        return self::STATUS_SELECT[$this->status] ?? ucfirst($this->status);
    }

    public static function recentForAudience(string $audience, int $limit = 5)
    {
        return static::query()
            ->visible()
            ->forAudience($audience)
            ->ordered()
            ->limit($limit)
            ->get();
    }
}
