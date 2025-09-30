<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationScore extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'application_scores';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'application_result_defence_id',
        'examiner_id',
        'score',
        'note',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function application_result_defence()
    {
        return $this->belongsTo(ApplicationResultDefense::class, 'application_result_defence_id');
    }

    public function examiner()
    {
        return $this->belongsTo(Dosen::class, 'examiner_id');
    }
}
