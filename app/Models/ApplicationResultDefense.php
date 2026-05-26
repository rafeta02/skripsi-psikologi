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
        'passed'   => 'Passed',
        'revision' => 'Revision',
        'failed'   => 'Failed',
    ];

    protected $fillable = [
        'application_id',
        'result',
        'note',
        'revision_deadline',
        'final_grade',
        'final_grade_letter',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'documentation',
        'invitation_document',
        'feedback_document',
        'minutes_document',
        'latest_script',
        'approval_page',
        'report_document',
        'revision_approval_sheet',
        'attendance_document',
        'form_document',
        'certificate_document',
        'publication_document',
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

    /**
     * Keep applications.status aligned with laporan hasil sidang (not defense registration approval).
     */
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

    /**
     * Dosen pembimbing (registration) + penguji sidang (SkripsiDefense).
     *
     * @return array<int>
     */
    public function getScorerDosenIds(): array
    {
        $application = $this->application;
        if (!$application) {
            return [];
        }

        $ids = [];

        $regApp = Application::where('mahasiswa_id', $application->mahasiswa_id)
            ->where('type', $application->type)
            ->where('stage', 'registration')
            ->orderByDesc('created_at')
            ->first();

        if ($regApp) {
            $supervisorIds = ApplicationAssignment::where('application_id', $regApp->id)
                ->where('role', 'supervisor')
                ->where('status', 'accepted')
                ->pluck('lecturer_id')
                ->all();
            $ids = array_merge($ids, $supervisorIds);
        }

        $skripsiDefense = SkripsiDefense::where('application_id', $application->id)->first();
        if ($skripsiDefense) {
            $examinerIds = $skripsiDefense->examiners()->pluck('dosen_id')->all();
            $ids = array_merge($ids, $examinerIds);
        }

        return array_values(array_unique(array_filter($ids)));
    }

    /**
     * Buat record ApplicationScore kosong untuk setiap penilai setelah admin memvalidasi.
     * Tidak berlaku untuk hasil failed — mahasiswa mendaftar ulang sidang.
     */
    public function provisionScoreAssignments(): void
    {
        if ($this->result === 'failed') {
            return;
        }

        foreach ($this->getScorerDosenIds() as $dosenId) {
            ApplicationScore::firstOrCreate(
                [
                    'application_result_defence_id' => $this->id,
                    'examiner_id' => $dosenId,
                ],
                []
            );
        }
    }

    /**
     * Calculate average score for each examiner
     */
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
                
                // Filter out null values
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

    /**
     * Calculate final score (average of all examiners' scores)
     */
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

    /**
     * Convert numeric score to letter grade
     */
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
        } else {
            return 'E';
        }
    }

    /**
     * Get grade description
     */
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

    /**
     * Get final grade letter (auto-calculated from final_score)
     */
    public function getFinalGradeLetterAttribute($value)
    {
        // If manually set, return it
        if ($value) {
            return $value;
        }
        
        // Otherwise, calculate from final_score
        $finalScore = $this->final_score;
        return self::convertScoreToGrade($finalScore);
    }

    public function getRevisionDeadlineAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setRevisionDeadlineAttribute($value)
    {
        $this->attributes['revision_deadline'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getDocumentationAttribute()
    {
        return $this->getMedia('documentation');
    }

    public function getInvitationDocumentAttribute()
    {
        return $this->getMedia('invitation_document');
    }

    public function getFeedbackDocumentAttribute()
    {
        return $this->getMedia('feedback_document');
    }

    public function getMinutesDocumentAttribute()
    {
        return $this->getMedia('minutes_document')->last();
    }

    public function getLatestScriptAttribute()
    {
        return $this->getMedia('latest_script')->last();
    }

    public function getApprovalPageAttribute()
    {
        return $this->getMedia('approval_page')->last();
    }

    public function getReportDocumentAttribute()
    {
        return $this->getMedia('report_document');
    }

    public function getRevisionApprovalSheetAttribute()
    {
        return $this->getMedia('revision_approval_sheet');
    }

    public function getAttendanceDocumentAttribute()
    {
        return $this->getMedia('attendance_document')->last();
    }

    public function getFormDocumentAttribute()
    {
        return $this->getMedia('form_document');
    }

    public function getCertificateDocumentAttribute()
    {
        return $this->getMedia('certificate_document')->last();
    }

    public function getPublicationDocumentAttribute()
    {
        return $this->getMedia('publication_document')->last();
    }
}
