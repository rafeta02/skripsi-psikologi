<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SkripsiDefense extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, InteractsWithMedia, Auditable, HasFactory;

    public $table = 'skripsi_defenses';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'application_id',
        'title',
        'abstract',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by_id',
    ];

    protected $appends = [
        'defence_document',
        'plagiarism_report',
        'ethics_statement',
        'research_instruments',
        'data_collection_letter',
        'research_module',
        'mbkm_recommendation_letter',
        'publication_statement',
        'defense_approval_page',
        'spp_receipt',
        'krs_latest',
        'eap_certificate',
        'transcript',
        'mbkm_report',
        'research_poster',
        'siakad_supervisor_screenshot',
        'supervision_logbook',
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

    public function getDefenceDocumentAttribute()
    {
        return $this->getMedia('defence_document')->last();
    }

    public function getPlagiarismReportAttribute()
    {
        return $this->getMedia('plagiarism_report')->last();
    }

    public function getEthicsStatementAttribute()
    {
        return $this->getMedia('ethics_statement');
    }

    public function getResearchInstrumentsAttribute()
    {
        return $this->getMedia('research_instruments');
    }

    public function getDataCollectionLetterAttribute()
    {
        return $this->getMedia('data_collection_letter');
    }

    public function getResearchModuleAttribute()
    {
        return $this->getMedia('research_module');
    }

    public function getMbkmRecommendationLetterAttribute()
    {
        return $this->getMedia('mbkm_recommendation_letter')->last();
    }

    public function getPublicationStatementAttribute()
    {
        return $this->getMedia('publication_statement')->last();
    }

    public function getDefenseApprovalPageAttribute()
    {
        return $this->getMedia('defense_approval_page');
    }

    public function getSppReceiptAttribute()
    {
        return $this->getMedia('spp_receipt')->last();
    }

    public function getKrsLatestAttribute()
    {
        return $this->getMedia('krs_latest')->last();
    }

    public function getEapCertificateAttribute()
    {
        return $this->getMedia('eap_certificate')->last();
    }

    public function getTranscriptAttribute()
    {
        return $this->getMedia('transcript')->last();
    }

    public function getMbkmReportAttribute()
    {
        return $this->getMedia('mbkm_report');
    }

    public function getResearchPosterAttribute()
    {
        return $this->getMedia('research_poster');
    }

    public function getSiakadSupervisorScreenshotAttribute()
    {
        return $this->getMedia('siakad_supervisor_screenshot')->last();
    }

    public function getSupervisionLogbookAttribute()
    {
        return $this->getMedia('supervision_logbook');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
