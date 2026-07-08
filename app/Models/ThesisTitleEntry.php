<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThesisTitleEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_nama',
        'nim',
        'prodi',
        'type',
        'title',
        'title_en',
        'year',
        'note',
        'source',
        'created_by_id',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
