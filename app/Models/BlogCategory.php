<?php
namespace App\Models;


use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class BlogCategory extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name', 'slug', 'description', 'color', 'icon',
        'meta_title', 'meta_description', 'meta_keywords',
        'parent_id', 'sort_order', 'is_active', 'posts_count',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'sort_order'  => 'integer',
        'posts_count' => 'integer',
    ];

    // MediaLibrary
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        $this->addMediaCollection('banner_image')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Crop, 300, 300)
            ->nonQueued();

        $this->addMediaConversion('banner')
            ->fit(Fit::Crop, 1200, 400)
            ->nonQueued();
    }

    // Image helper methods
    public function getFeaturedImageUrl(?string $conversion = null): ?string
    {
        return $this->getFirstMediaUrl('featured_image', $conversion ?: '');
    }

    public function getBannerImageUrl(?string $conversion = 'banner'): ?string
    {
        return $this->getFirstMediaUrl('banner_image', $conversion);
    }

    // Relationships
    public function posts(): HasMany
    {
        return $this->hasMany(BlogPost::class);
    }

    public function publishedPosts(): HasMany
    {
        return $this->posts()->where('status', 'published');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(BlogCategory::class, 'parent_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    // Boot method for slug generation
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}
