<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resources extends Model
{
    protected $table = 'resources';
    protected $fillable = [
        'content_id',
        'title',
        'author',
        'year',
        'source_category_id',
        'link'
    ];

    protected $casts = [
        'year' => 'integer',
    ];

    public function sourceCategory()
    {
        return $this->belongsTo(SourceCategory::class);
    }

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function progress()
    {
        return $this->hasMany(VisitorProgress::class, 'resource_id');
    }

    public function getProgressCountAttribute()
    {
        return $this->progress()->count();
    }

    public function getCompletedCountAttribute()
    {
        return $this->progress()->where('is_completed', true)->count();
    }

    // Scope untuk filter berdasarkan content
    public function scopeForContent($query, $contentId)
    {
        return $query->where('content_id', $contentId);
    }

    // Scope untuk filter berdasarkan category
    public function scopeForCategory($query, $categoryId)
    {
        return $query->where('source_category_id', $categoryId);
    }
}
