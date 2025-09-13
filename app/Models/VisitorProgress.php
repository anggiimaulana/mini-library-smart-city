<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorProgress extends Model
{
    protected $table = 'visitor_progress';

    protected $fillable = [
        'visitor_id',
        'resource_id',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function resource()
    {
        return $this->belongsTo(Resources::class, 'resource_id');
    }

    // Scope untuk progress yang completed
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    // Scope untuk progress berdasarkan visitor
    public function scopeForVisitor($query, $visitorId)
    {
        return $query->where('visitor_id', $visitorId);
    }
}
