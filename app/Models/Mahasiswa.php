<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mahasiswa extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'mahasiswas';

    public const GENDER_SELECT = [
        'male'   => 'Laki - Laki',
        'female' => 'Perempuan',
    ];

    protected $dates = [
        'tanggal_lahir',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const KELAS_SELECT = [
        'A' => 'A',
        'B' => 'B',
        'C' => 'C',
        'D' => 'D',
        'E' => 'E',
        'F' => 'F',
    ];

    protected $fillable = [
        'nim',
        'nama',
        'tahun_masuk',
        'kelas',
        'prodi_id',
        'jenjang_id',
        'fakultas_id',
        'tanggal_lahir',
        'tempat_lahir',
        'gender',
        'alamat',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
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

    public function getTanggalLahirAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setTanggalLahirAttribute($value)
    {
        $this->attributes['tanggal_lahir'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }
}
