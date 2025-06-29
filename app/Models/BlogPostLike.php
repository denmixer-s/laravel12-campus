<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogPostLike extends Model
{
    protected $fillable = [
        'blog_post_id', 'user_id', 'type'
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

    // Scopes
    public function scopeLikes($query)
    {
        return $query->where('type', 'like');
    }

    public function scopeDislikes($query)
    {
        return $query->where('type', 'dislike');
    }
}
