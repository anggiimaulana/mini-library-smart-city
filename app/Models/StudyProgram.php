<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyProgram extends Model
{
    protected $table = 'study_programs';

    protected $fillable = [
        'name',
        'major_id',
    ];

    public function major()
    {
        return $this->belongsTo(Major::class);
    }
}
