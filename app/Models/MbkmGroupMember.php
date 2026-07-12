<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\FileNamingTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MbkmGroupMember extends Model implements HasMedia
{
    use SoftDeletes, Auditable, HasFactory, InteractsWithMedia, FileNamingTrait;

    public $table = 'mbkm_group_members';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'requirements_completed_at',
    ];

    public const ROLE_SELECT = [
        'ketua'   => 'Ketua',
        'anggota' => 'Anggota',
    ];

    public const REQUIREMENTS_STATUS_SELECT = [
        'incomplete' => 'Belum lengkap',
        'complete'   => 'Lengkap',
    ];

    protected $appends = [
        'khs_all',
        'krs_latest',
        'spp',
        'recognition_form',
    ];

    protected $fillable = [
        'mbkm_registration_id',
        'mahasiswa_id',
        'role',
        'title',
        'title_en',
        'total_sks_taken',
        'sks_mkp_taken',
        'nilai_mk_kuantitatif',
        'nilai_mk_kualitatif',
        'nilai_mk_statistika_dasar',
        'nilai_mk_statistika_lanjutan',
        'nilai_mk_konstruksi_tes',
        'nilai_mk_tps',
        'requirements_status',
        'requirements_completed_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions($media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('khs_all');
        $this->addMediaCollection('krs_latest')->singleFile();
        $this->addMediaCollection('spp')->singleFile();
        $this->addMediaCollection('recognition_form')->singleFile();
    }

    public function mbkm_registration()
    {
        return $this->belongsTo(MbkmRegistration::class, 'mbkm_registration_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function getKhsAllAttribute()
    {
        return $this->getMedia('khs_all');
    }

    public function getKrsLatestAttribute()
    {
        return $this->getMedia('krs_latest')->last();
    }

    public function getSppAttribute()
    {
        return $this->getMedia('spp')->last();
    }

    public function getRecognitionFormAttribute()
    {
        return $this->getMedia('recognition_form')->last();
    }

    public function hasCompleteDocuments(): bool
    {
        return $this->getMedia('khs_all')->isNotEmpty()
            && $this->getMedia('krs_latest')->isNotEmpty()
            && $this->getMedia('spp')->isNotEmpty();
    }

    public function hasCompleteGrades(): bool
    {
        return filled($this->title)
            && $this->total_sks_taken !== null
            && $this->sks_mkp_taken !== null
            && filled($this->nilai_mk_kuantitatif)
            && filled($this->nilai_mk_kualitatif)
            && filled($this->nilai_mk_statistika_dasar)
            && filled($this->nilai_mk_statistika_lanjutan)
            && filled($this->nilai_mk_konstruksi_tes)
            && filled($this->nilai_mk_tps);
    }

    public function refreshRequirementsStatus(): void
    {
        $complete = $this->hasCompleteGrades() && $this->hasCompleteDocuments();

        $this->forceFill([
            'requirements_status' => $complete ? 'complete' : 'incomplete',
            'requirements_completed_at' => $complete ? ($this->requirements_completed_at ?? now()) : null,
        ])->save();
    }

    public function isRequirementsComplete(): bool
    {
        return $this->requirements_status === 'complete';
    }
}
