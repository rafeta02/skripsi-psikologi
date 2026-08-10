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
        'passed'   => 'Lulus tanpa revisi',
        'revision' => 'Lulus dengan revisi',
        'failed'   => 'Tidak Lulus',
    ];

    protected $fillable = [
        'application_id',
        'final_title',
        'final_title_en',
        'result',
        'note',
        'revision_deadline',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'title_change_form',
        'minutes_document',
        'latest_script',
        'documentation',
        'approval_page',
        'invitation_document',
        'feedback_document',
        'revision_approval_sheet',
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

    public function scores()
    {
        return $this->hasMany(ApplicationScore::class, 'application_result_defence_id');
    }

    public function isValidatedByAdmin(): bool
    {
        if (!$this->application_id) {
            return false;
        }

        return ApplicationAction::where('application_id', $this->application_id)
            ->where('action_type', 'result_defense_approved')
            ->exists();
    }

    public function isRejectedByAdmin(): bool
    {
        if (!$this->application_id) {
            return false;
        }

        return ApplicationAction::where('application_id', $this->application_id)
            ->where('action_type', 'result_defense_rejected')
            ->exists();
    }

    public function isFinalizedByAdmin(): bool
    {
        if (!$this->application_id) {
            return false;
        }

        return ApplicationAction::where('application_id', $this->application_id)
            ->where('action_type', 'defense_finalized')
            ->exists();
    }

    public function isScoringComplete(): bool
    {
        $total = $this->scores()->count();
        if ($total === 0) {
            return false;
        }

        $completed = $this->scores()->whereNotNull('score')->count();

        return $completed === $total;
    }

    public function syncApplicationStatus(): void
    {
        if (!$this->application) {
            return;
        }

        if ($this->isRejectedByAdmin()) {
            $status = 'rejected';
        } else {
            $status = match ($this->result) {
                'passed' => $this->isValidatedByAdmin() ? 'result' : 'submitted',
                'revision' => $this->isValidatedByAdmin() ? 'revision' : 'submitted',
                'failed' => $this->isValidatedByAdmin() ? 'rejected' : 'submitted',
                default => $this->application->status,
            };
        }

        if ($this->application->status !== $status) {
            $this->application->update(['status' => $status]);
            $this->application->refresh();
        }
    }

    public function adminValidationStatusHtml(): string
    {
        if ($this->isRejectedByAdmin()) {
            return '<span class="badge badge-danger badge-lg">Ditolak Admin</span>';
        }

        if ($this->result !== 'passed' && $this->result !== 'revision' && $this->result !== 'failed') {
            return '<span class="badge badge-secondary badge-lg">-</span>';
        }

        if ($this->isValidatedByAdmin()) {
            return '<span class="badge badge-success badge-lg">Disetujui Admin</span>';
        }

        return '<span class="badge badge-warning badge-lg">Menunggu Validasi Admin</span>';
    }

    public function getScorerDosenIds(): array
    {
        if (!$this->application) {
            return [];
        }

        return app(\App\Services\DefenseScoringService::class)
            ->getScorerDosenIds($this->application);
    }

    public function provisionScoreAssignments(): void
    {
        if ($this->result === 'failed' || !$this->application) {
            return;
        }

        foreach ($this->getScorerDosenIds() as $dosenId) {
            ApplicationScore::firstOrCreate(
                [
                    'application_result_defence_id' => $this->id,
                    'examiner_id' => $dosenId,
                ],
                [
                    'application_id' => $this->application_id,
                ]
            );
        }
    }

    public function getExaminerScoresAttribute()
    {
        return $this->scores()
            ->with('examiner')
            ->get()
            ->map(function ($score) {
                $components = [
                    $score->penulisan,
                    $score->isi,
                    $score->analisis,
                    $score->teoritis,
                    $score->faktual,
                    $score->pemecahan_masalah,
                    $score->penyampaian,
                ];

                $validComponents = array_filter($components, function ($value) {
                    return $value !== null;
                });

                $average = count($validComponents) > 0
                    ? array_sum($validComponents) / count($validComponents)
                    : 0;

                return [
                    'examiner' => $score->examiner,
                    'components' => [
                        'penulisan' => $score->penulisan,
                        'isi' => $score->isi,
                        'analisis' => $score->analisis,
                        'teoritis' => $score->teoritis,
                        'faktual' => $score->faktual,
                        'pemecahan_masalah' => $score->pemecahan_masalah,
                        'penyampaian' => $score->penyampaian,
                    ],
                    'sum' => $score->sum,
                    'average' => round($average, 2),
                    'score' => $score->score,
                    'note' => $score->note,
                ];
            });
    }

    public function getFinalScoreAttribute()
    {
        $scores = $this->scores()->get();

        if ($scores->isEmpty()) {
            return 0;
        }

        $totalScore = 0;
        $count = 0;

        foreach ($scores as $score) {
            if ($score->score !== null) {
                $totalScore += $score->score;
                $count++;
            }
        }

        return $count > 0 ? round($totalScore / $count, 2) : 0;
    }

    public static function convertScoreToGrade($score)
    {
        if ($score >= 85) {
            return 'A';
        } elseif ($score >= 80) {
            return 'A-';
        } elseif ($score >= 75) {
            return 'B+';
        } elseif ($score >= 70) {
            return 'B';
        } elseif ($score >= 65) {
            return 'C+';
        } elseif ($score >= 60) {
            return 'C';
        } elseif ($score >= 55) {
            return 'D';
        }

        return 'E';
    }

    public static function getGradeDescription($grade)
    {
        $descriptions = [
            'A' => 'Sangat Baik (≥ 85)',
            'A-' => 'Sangat Baik (80-84)',
            'B+' => 'Baik (75-79)',
            'B' => 'Baik (70-74)',
            'C+' => 'Cukup (65-69)',
            'C' => 'Cukup (60-64)',
            'D' => 'Kurang (55-59)',
            'E' => 'Sangat Kurang (< 55)',
        ];

        return $descriptions[$grade] ?? '';
    }

    public function getFinalGradeLetterAttribute()
    {
        return self::convertScoreToGrade($this->final_score);
    }

    public function getRevisionDeadlineAttribute($value)
    {
        if (!$value) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance($value)->format(config('panel.date_format'));
        }

        try {
            return Carbon::createFromFormat('Y-m-d', substr((string) $value, 0, 10))
                ->format(config('panel.date_format'));
        } catch (\Throwable $e) {
            return Carbon::parse($value)->format(config('panel.date_format'));
        }
    }

    public function setRevisionDeadlineAttribute($value)
    {
        if (!$value) {
            $this->attributes['revision_deadline'] = null;
            return;
        }

        if ($value instanceof \DateTimeInterface) {
            $this->attributes['revision_deadline'] = Carbon::instance($value)->format('Y-m-d');
            return;
        }

        try {
            $this->attributes['revision_deadline'] = Carbon::createFromFormat(
                config('panel.date_format'),
                $value
            )->format('Y-m-d');
        } catch (\Throwable $e) {
            $this->attributes['revision_deadline'] = Carbon::parse($value)->format('Y-m-d');
        }
    }

    public function getTitleChangeFormAttribute()
    {
        return $this->getMedia('title_change_form')->last();
    }

    public function getMinutesDocumentAttribute()
    {
        return $this->getMedia('minutes_document')->last();
    }

    public function getLatestScriptAttribute()
    {
        return $this->getMedia('latest_script')->last();
    }

    public function getDocumentationAttribute()
    {
        return $this->getMedia('documentation');
    }

    public function getApprovalPageAttribute()
    {
        return $this->getMedia('approval_page')->last();
    }

    public function getInvitationDocumentAttribute()
    {
        return $this->getMedia('invitation_document');
    }

    public function getFeedbackDocumentAttribute()
    {
        return $this->getMedia('feedback_document');
    }

    public function getRevisionApprovalSheetAttribute()
    {
        return $this->getMedia('revision_approval_sheet')->last();
    }

    public static function submissionWarningStartDays(): int
    {
        return (int) config('thesis.defense_result_submission_warning_start_days', 14);
    }

    public static function daysSinceDefenseHeld(Carbon $defenseHeldAt): int
    {
        return max(0, $defenseHeldAt->copy()->startOfDay()->diffInDays(now()->startOfDay()));
    }

    public static function whatsappSubmissionReminderUrl(
        $mahasiswa,
        Application $application,
        Carbon $defenseHeldAt,
        int $daysSinceDefense
    ): ?string {
        $phone = $mahasiswa->user?->whatsappNumberForLink();
        if (! $phone) {
            return null;
        }

        $nama = $mahasiswa->nama ?? 'Mahasiswa';
        $nim = $mahasiswa->nim ?? '-';
        $judul = $application->skripsiDefense?->title
            ?? $application->skripsiSeminar?->title
            ?? '-';
        $defenseLabel = $defenseHeldAt->format('d M Y');
        $jalur = $application->type === 'mbkm' ? 'MBKM' : 'Reguler';

        $message = "Yth. {$nama},\n\n"
            ."Sidang skripsi ({$jalur}) Anda telah dilaksanakan pada {$defenseLabel}. "
            ."Sudah {$daysSinceDefense} hari berlalu, namun kami belum menerima Laporan Hasil Sidang Anda.\n\n"
            ."Mohon segera login ke SIMSKRIPSI untuk mengunggah laporan hasil sidang.\n\n"
            ."Detail:\n"
            ."- NIM: {$nim}\n"
            ."- Judul: {$judul}\n\n"
            .'Terima kasih.';

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($message);
    }
}
