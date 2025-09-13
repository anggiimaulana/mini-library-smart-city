<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $table = 'majors';
    protected $fillable = ['name'];

    public function studyPrograms()
    {
        return $this->hasMany(StudyProgram::class);
    }
}
