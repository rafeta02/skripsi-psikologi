<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dosen extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'dosens';

    public const GENDER_SELECT = [
        'male'   => 'Laki-Laki',
        'female' => 'Perempuan',
    ];

    protected $dates = [
        'tanggal_lahir',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'nip',
        'nidn',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'gender',
        'prodi_id',
        'jenjang_id',
        'fakultas_id',
        'riset_grup_id',
        'mbkm_availability',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'mbkm_availability' => 'boolean',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getTanggalLahirAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setTanggalLahirAttribute($value)
    {
        $this->attributes['tanggal_lahir'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }

    public function jenjang()
    {
        return $this->belongsTo(Jenjang::class, 'jenjang_id');
    }

    public function fakultas()
    {
        return $this->belongsTo(Faculty::class, 'fakultas_id');
    }

    public function keilmuans()
    {
        return $this->belongsToMany(Keilmuan::class);
    }

    public function riset_grup()
    {
        return $this->belongsTo(ResearchGroup::class, 'riset_grup_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'dosen_id');
    }

    public function scopeAvailableForMbkm($query)
    {
        return $query->where('mbkm_availability', true);
    }

    public function isAvailableForMbkm(): bool
    {
        return (bool) $this->mbkm_availability;
    }

    public static function groupedByResearchGroupForMbkm(?int $includeDosenId = null): array
    {
        $dosens = static::query()
            ->availableForMbkm()
            ->whereNotNull('riset_grup_id')
            ->orderBy('nama')
            ->get(['id', 'nama', 'riset_grup_id']);

        if ($includeDosenId && !$dosens->contains('id', $includeDosenId)) {
            $extra = static::query()
                ->where('id', $includeDosenId)
                ->whereNotNull('riset_grup_id')
                ->first(['id', 'nama', 'riset_grup_id']);

            if ($extra) {
                $dosens->push($extra);
            }
        }

        return $dosens
            ->sortBy('nama')
            ->groupBy('riset_grup_id')
            ->map(fn ($items) => $items->map(fn ($d) => [
                'id' => $d->id,
                'nama' => $d->nama,
            ])->values())
            ->toArray();
    }
}
