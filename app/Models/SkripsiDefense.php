<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\FileNamingTrait;
use App\Traits\MultiTenantModelTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SkripsiDefense extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, InteractsWithMedia, Auditable, HasFactory, FileNamingTrait;

    public $table = 'skripsi_defenses';

    public const EAP_GRADE_SELECT = [
        'A'  => 'A',
        'A-' => 'A-',
        'B+' => 'B+',
        'B'  => 'B',
        'B-' => 'B-',
        'C+' => 'C+',
        'C'  => 'C',
        'D'  => 'D',
        'E'  => 'E',
    ];

    protected $casts = [
        'eap_score' => 'integer',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'application_id',
        'title',
        'title_en',
        'abstract',
        'eap_grade',
        'eap_score',
        'status',
        'admin_note',
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
        'signed_scientific_publication_statement',
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

    public function getSignedScientificPublicationStatementAttribute()
    {
        return $this->getMedia('signed_scientific_publication_statement')->last();
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

    /**
     * Query tanpa scope created_by — portal dosen memakai otorisasi pembimbing/penguji.
     */
    public static function queryForDosenPortal(): Builder
    {
        return static::withoutGlobalScopes();
    }

    public function examiners()
    {
        return $this->hasMany(SkripsiDefenseExaminer::class, 'skripsi_defense_id');
    }

    public function examiner1()
    {
        return $this->hasOne(SkripsiDefenseExaminer::class, 'skripsi_defense_id')
            ->where('role', 'examiner_1')
            ->with('dosen');
    }

    public function examiner2()
    {
        return $this->hasOne(SkripsiDefenseExaminer::class, 'skripsi_defense_id')
            ->where('role', 'examiner_2')
            ->with('dosen');
    }

    /**
     * Admin validation status for defense registration (pending / accepted / rejected).
     */
    public function validationStatus(): string
    {
        $status = $this->status ?? 'pending';

        return in_array($status, ['pending', 'accepted', 'rejected'], true) ? $status : 'pending';
    }

    public function isPendingValidation(): bool
    {
        return $this->validationStatus() === 'pending';
    }

    public function isAccepted(): bool
    {
        return $this->validationStatus() === 'accepted';
    }

    public function isRejected(): bool
    {
        return $this->validationStatus() === 'rejected';
    }

    /**
     * Align applications.status with defense admin validation (mahasiswa UI uses application status).
     */
    public function syncApplicationStatus(): void
    {
        if (!$this->application) {
            return;
        }

        $status = match ($this->validationStatus()) {
            'accepted' => 'approved',
            'rejected' => 'rejected',
            default => 'submitted',
        };

        if ($this->application->status !== $status) {
            $this->application->update(['status' => $status]);
            $this->application->refresh();
        }
    }

    public function eapGradeLabel(): string
    {
        if (! $this->eap_grade) {
            return '-';
        }

        return self::EAP_GRADE_SELECT[$this->eap_grade] ?? $this->eap_grade;
    }

    /** @return array<int, string> */
    public static function allowedEapGrades(): array
    {
        return array_keys(self::EAP_GRADE_SELECT);
    }

    public function whatsappMahasiswaAcceptanceUrl(): ?string
    {
        if (! $this->isAccepted()) {
            return null;
        }

        $this->loadMissing([
            'application.mahasiswa.user',
            'examiner1.dosen',
            'examiner2.dosen',
        ]);

        $phone = $this->application?->mahasiswa?->user?->whatsappNumberForLink();
        if (! $phone) {
            return null;
        }

        if (! $this->examiner1?->dosen || ! $this->examiner2?->dosen) {
            return null;
        }

        $penguji1 = $this->examiner1->dosen->nama;
        $penguji2 = $this->examiner2->dosen->nama;

        $message = "Anda mendapatkan :\n"
            ."Penguji 1: {$penguji1}\n"
            ."Penguji 2: {$penguji2}\n"
            ."Berkas persyaratan sidang telah lengkap. Silakan hubungi dewan dosen untuk mencari waktu sidang. Mahasiswa diharapkan memperhatikan bahwa berkas sidang harus sudah dikirimkan kepada para penguji paling lambat 5 hari kerja sebelum pelaksanaan sidang. Oleh karena itu, mahasiswa dimohon untuk mengantisipasi waktu tersebut dengan mencari jadwal sidang yang masih cukup longgar, agar berkas dapat diterima oleh penguji tepat waktu dan tidak dikirimkan secara mendadak.\n"
            ."Setelah disepakati tanggal dan waktunya serta sudah mengkonfirmasi ke Mas Samsudin (terkait ketersediaan ruang Ujian) silakan membalas email ini kembali dengan menyertakan informasi tentang :\n\n"
            ."Tanggal Sidang :\n"
            ."Waktu Sidang :\n"
            ."Tempat Sidang:\n"
            ."No. WA Mas Samsudin: 085728035352";

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($message);
    }

    public function dosenRoleLabel(int $dosenId): ?string
    {
        if ((int) ($this->examiner1?->dosen_id) === $dosenId) {
            return 'Penguji 1';
        }

        if ((int) ($this->examiner2?->dosen_id) === $dosenId) {
            return 'Penguji 2';
        }

        $application = $this->application;

        if (! $application) {
            return null;
        }

        $scorerIds = app(\App\Services\DefenseScoringService::class)->getScorerDosenIds($application);

        if (in_array($dosenId, $scorerIds, true)) {
            return 'Pembimbing';
        }

        return null;
    }
}
