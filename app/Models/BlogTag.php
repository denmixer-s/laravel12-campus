<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BlogTag extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'color', 'is_active', 'posts_count'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'posts_count' => 'integer',
    ];

    // Relationships
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(BlogPost::class, 'blog_post_tags');
    }

    public function publishedPosts(): BelongsToMany
    {
        return $this->posts()->where('status', 'published');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = \Str::slug($tag->name);
            }
        });
    }
}
