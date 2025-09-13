<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Content extends Model
{
    protected $table = 'contents';

    protected $fillable = [
        'title',
        'slug',
        'image',
        'order',
        'description',
        'challenge_solution',
        'technology',
        'implementation'
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function resources()
    {
        return $this->hasMany(Resources::class);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-pillar.jpg');
    }

    // Scope untuk ordering
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    // Get route untuk pillar
    public function getRouteAttribute()
    {
        return route('pillar.show', $this->slug);
    }

    protected static function booted()
    {
        static::creating(function ($content) {
            $content->slug = static::generateUniqueSlug($content->title);
        });

        static::updating(function ($content) {
            if ($content->isDirty('title')) {
                $content->slug = static::generateUniqueSlug($content->title, $content->id);
            }
        });
    }

    protected static function generateUniqueSlug(string $title, $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }
}
