<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationScore extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'application_scores';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'application_id',
        'application_result_defence_id',
        'examiner_id',
        'penulisan',
        'isi',
        'analisis',
        'teoritis',
        'faktual',
        'pemecahan_masalah',
        'penyampaian',
        'sum',
        'score',
        'note',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const SCORE_COMPONENT_LABELS = [
        'penulisan' => 'Sistematika penulisan',
        'isi' => 'Isi (masalah yang diajukan, relevansinya, bahasa, arti bagi pengembangan ilmu dan kegunaan)',
        'analisis' => 'Analisis (pembahasan dan penarikan simpulan: formulasi masalah, penggunaan literatur, integrasi data empirik dan teoritik, analisis data)',
        'teoritis' => 'Penguasaan pengetahuan teoritis (komprehensif yang menyangkut skripsi)',
        'faktual' => 'Penguasaan pengetahuan faktual (komprehensif yang menyangkut skripsi)',
        'pemecahan_masalah' => 'Cara menanggapi dan memecahkan masalah (kemandirian, kreativitas, pandangan, cara berpikir, cara kerja, objektivitas, dan etika ilmiah)',
        'penyampaian' => 'Cara penyampaian (sistematis, logis, runtut)',
    ];

    public static function scoreComponentLabels(): array
    {
        return self::SCORE_COMPONENT_LABELS;
    }

    public static function scoreComponentKeys(): array
    {
        return array_keys(self::SCORE_COMPONENT_LABELS);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function application_result_defence()
    {
        return $this->belongsTo(ApplicationResultDefense::class, 'application_result_defence_id');
    }

    public function examiner()
    {
        return $this->belongsTo(Dosen::class, 'examiner_id');
    }

    public function isComplete(): bool
    {
        return $this->score !== null;
    }
}
