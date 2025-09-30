<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prodi extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'prodis';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'code',
        'name',
        'slug',
        'jenjang_id',
        'fakultas_id',
        'description',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function jenjang()
    {
        return $this->belongsTo(Jenjang::class, 'jenjang_id');
    }

    public function fakultas()
    {
        return $this->belongsTo(Faculty::class, 'fakultas_id');
    }
}
