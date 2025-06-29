<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogPostView extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'blog_post_id', 'user_id', 'ip_address',
        'user_agent', 'referer', 'viewed_at'
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
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
}
