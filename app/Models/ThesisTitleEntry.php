<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThesisTitleEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nim',
        'angkatan',
        'pembimbing',
        'title',
        'title_en',
        'penguji_1',
        'penguji_2',
        'tanggal_sidang',
        'source',
        'created_by_id',
    ];

    protected $casts = [
        'tanggal_sidang' => 'date',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function tanggalSidangLabel(): string
    {
        return $this->tanggal_sidang?->format('d M Y') ?? '-';
    }
}
