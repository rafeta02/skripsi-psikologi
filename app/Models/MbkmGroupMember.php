<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MbkmGroupMember extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'mbkm_group_members';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const ROLE_SELECT = [
        'ketua'   => 'Ketua',
        'anggota' => 'Anggota',
    ];

    protected $fillable = [
        'mbkm_registration_id',
        'mahasiswa_id',
        'role',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function mbkm_registration()
    {
        return $this->belongsTo(MbkmRegistration::class, 'mbkm_registration_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }
}
