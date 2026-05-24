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
        return $this->getMedia('form_document')->last();
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
