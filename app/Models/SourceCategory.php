<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SourceCategory extends Model
{
    protected $table = 'source_categories';

    protected $fillable = ['name'];

    public function resources()
    {
        return $this->hasMany(Resources::class);
    }
}
