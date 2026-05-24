<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkripsiDefenseExaminer extends Model
{
    use HasFactory;

    protected $table = 'skripsi_defense_examiner';

    protected $fillable = [
        'skripsi_defense_id',
        'dosen_id',
        'role',
    ];

    public function skripsiDefense()
    {
        return $this->belongsTo(SkripsiDefense::class, 'skripsi_defense_id');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }
}
