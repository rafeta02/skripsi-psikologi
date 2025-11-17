<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\FileNamingTrait;
use App\Traits\MultiTenantModelTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MbkmRegistration extends Model implements HasMedia
{
    use SoftDeletes, MultiTenantModelTrait, InteractsWithMedia, Auditable, HasFactory, FileNamingTrait;

    public $table = 'mbkm_registrations';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'khs_all',
        'krs_latest',
        'spp',
        'proposal_mbkm',
        'recognition_form',
    ];

    protected $fillable = [
        'application_id',
        'research_group_id',
        'preference_supervision_id',
        'theme_id',
        'title_mbkm',
        'title',
        'total_sks_taken',
        'nilai_mk_kuantitatif',
        'nilai_mk_kualitatif',
        'nilai_mk_statistika_dasar',
        'nilai_mk_statistika_lanjutan',
        'nilai_mk_konstruksi_tes',
        'nilai_mk_tps',
        'sks_mkp_taken',
        'note',
        'approval_date',
        'rejection_reason',
        'revision_notes',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by_id',
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

    public function research_group()
    {
        return $this->belongsTo(ResearchGroup::class, 'research_group_id');
    }

    public function preference_supervision()
    {
        return $this->belongsTo(Dosen::class, 'preference_supervision_id');
    }

    public function theme()
    {
        return $this->belongsTo(Keilmuan::class, 'theme_id');
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

    public function getProposalMbkmAttribute()
    {
        return $this->getMedia('proposal_mbkm')->last();
    }

    public function getRecognitionFormAttribute()
    {
        return $this->getMedia('recognition_form')->last();
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function groupMembers()
    {
        return $this->hasMany(MbkmGroupMember::class, 'mbkm_registration_id');
    }
}
