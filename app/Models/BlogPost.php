<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class BlogPost extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'user_id',
        'blog_category_id',
        'status',
        'published_at',
        'scheduled_at',
        'is_featured',
        'is_sticky',
        'allow_comments',
        'views_count',
        'comments_count',
        'likes_count',
        'shares_count',
        'reading_time',
        'admin_notes',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_sticky' => 'boolean',
        'allow_comments' => 'boolean',
        'views_count' => 'integer',
        'comments_count' => 'integer',
        'likes_count' => 'integer',
        'shares_count' => 'integer',
    ];

    protected $dates = [
        'published_at',
        'scheduled_at',
        'deleted_at',
    ];

    // Boot method for auto-generating slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (!$post->slug) {
                $post->slug = Str::slug($post->title);
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('title') && !$post->isDirty('slug')) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    // ===== RELATIONSHIPS =====

    /**
     * User relationship (Author)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Alias for user relationship for backward compatibility
     */
    public function author()
    {
        return $this->user();
    }

    /**
     * Category relationship (Single category)
     */
    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    /**
     * Categories method for compatibility (returns collection)
     * Note: This returns a collection, not a relationship
     */
    public function categories()
    {
        if ($this->relationLoaded('category') && $this->category) {
            return collect([$this->category]);
        } elseif ($this->blog_category_id) {
            return collect([$this->category]);
        }
        return collect();
    }

    /**
     * Tags many-to-many relationship
     */
    public function tags()
    {
        return $this->belongsToMany(BlogTag::class, 'blog_post_tags', 'blog_post_id', 'blog_tag_id');
    }

    /**
     * Comments relationship
     */
    public function comments()
    {
        return $this->hasMany(BlogComment::class, 'blog_post_id');
    }

    /**
     * Approved comments only
     */
    public function approvedComments()
    {
        return $this->comments()->where('status', 'approved');
    }

    /**
     * Views relationship
     */
    public function views()
    {
        return $this->hasMany(BlogPostView::class, 'blog_post_id');
    }

    /**
     * Likes relationship
     */
    public function likes()
    {
        return $this->hasMany(BlogPostLike::class, 'blog_post_id');
    }

    // ===== SCOPES =====

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeSticky($query)
    {
        return $query->where('is_sticky', true);
    }

    public function scopeByAuthor($query, $authorId)
    {
        return $query->where('user_id', $authorId);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('blog_category_id', $categoryId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%")
              ->orWhere('excerpt', 'like', "%{$search}%");
        });
    }

    // ===== ACCESSORS & MUTATORS =====

    public function getExcerptAttribute($value)
    {
        if ($value) {
            return $value;
        }

        return Str::limit(strip_tags($this->content), 160);
    }

    public function getReadTimeAttribute()
    {
        if ($this->reading_time) {
            return $this->reading_time;
        }

        $wordsPerMinute = 200;
        $wordCount = str_word_count(strip_tags($this->content));
        return max(1, ceil($wordCount / $wordsPerMinute));
    }

    public function getIsPublishedAttribute()
    {
        return $this->status === 'published' &&
               $this->published_at &&
               $this->published_at->isPast();
    }

    public function getUrlAttribute()
    {
        return route('blog.show', $this->slug);
    }

    // ===== MEDIA COLLECTIONS =====


    public function registerMediaCollections(): void
    {
        // Featured image collection (single image)
        $this->addMediaCollection('featured_image')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
            ->singleFile();

        // Gallery images collection (multiple images)
        $this->addMediaCollection('gallery_images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif']);
    }

    /**
     * Register media conversions for responsive images.
     */
    public function registerMediaConversions(Media $media = null): void
    {
        // Featured image conversions
        $this->addMediaConversion('featured_thumb')
            ->fit(Fit::Crop, 400, 300)
            ->quality(85)
            ->format('webp')
            ->performOnCollections('featured_image')
            ->nonQueued();

        $this->addMediaConversion('featured_medium')
            ->fit(Fit::Crop, 800, 600)
            ->quality(85)
            ->format('webp')
            ->performOnCollections('featured_image')
            ->nonQueued();

        $this->addMediaConversion('featured_large')
            ->fit(Fit::Crop, 1200, 900)
            ->quality(85)
            ->format('webp')
            ->performOnCollections('featured_image')
            ->nonQueued();

        // Gallery image conversions
        $this->addMediaConversion('gallery_thumb')
            ->fit(Fit::Crop, 300, 300)
            ->quality(85)
            ->format('webp')
            ->performOnCollections('gallery_images')
            ->nonQueued();

        $this->addMediaConversion('gallery_medium')
            ->fit(Fit::Crop, 600, 600)
            ->quality(85)
            ->format('webp')
            ->performOnCollections('gallery_images')
            ->nonQueued();

        $this->addMediaConversion('gallery_large')
            ->fit(Fit::Max, 1200, 1200)
            ->quality(90)
            ->format('webp')
            ->performOnCollections('gallery_images')
            ->nonQueued();
    }


    // ===== HELPER METHODS =====

    public function incrementViews($userId = null, $ipAddress = null)
    {
        $this->increment('views_count');

        if ($userId || $ipAddress) {
            BlogPostView::firstOrCreate([
                'blog_post_id' => $this->id,
                'user_id' => $userId,
                'ip_address' => $ipAddress,
                'viewed_at' => now(),
            ]);
        }
    }

    public function toggleLike($userId)
    {
        $like = $this->likes()->where('user_id', $userId)->first();

        if ($like) {
            $like->delete();
            $this->decrement('likes_count');
            return false;
        } else {
            $this->likes()->create(['user_id' => $userId]);
            $this->increment('likes_count');
            return true;
        }
    }

    public function isLikedBy($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function getRelatedPosts($limit = 5)
    {
        return static::published()
            ->where('id', '!=', $this->id)
            ->where('blog_category_id', $this->blog_category_id)
            ->orderByRaw('RAND()') // MySQL syntax
            ->limit($limit)
            ->get();
    }

    public function updateCommentsCount()
    {
        $this->update([
            'comments_count' => $this->approvedComments()->count()
        ]);
    }

    // ===== ROUTE MODEL BINDING =====

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
