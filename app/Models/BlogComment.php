<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogComment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'blog_post_id', 'user_id', 'parent_id',
        'guest_name', 'guest_email', 'guest_website',
        'content', 'user_agent', 'ip_address',
        'status', 'approved_at', 'approved_by',
        'likes_count', 'replies_count'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'likes_count' => 'integer',
        'replies_count' => 'integer',
    ];

    // Relationships
    public function post(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class, 'blog_post_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(BlogComment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(BlogComment::class, 'parent_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    // Helper methods
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function getAuthorNameAttribute(): string
    {
        return $this->user ? $this->user->name : $this->guest_name;
    }

    public function getAuthorEmailAttribute(): ?string
    {
        return $this->user ? $this->user->email : $this->guest_email;
    }
}
