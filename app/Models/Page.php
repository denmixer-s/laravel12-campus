<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;

class Page extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the page.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Register media collections for the page.
     */
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

    /**
     * Get the featured image URL with fallback.
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('featured_image');
        return $media ? $media->getUrl() : null;
    }

    /**
     * Get the featured image responsive URLs.
     */
    public function getFeaturedImageResponsiveAttribute(): array
    {
        $media = $this->getFirstMedia('featured_image');

        if (!$media) {
            return [];
        }

        return [
            'original' => $media->getUrl(),
            'large' => $media->getUrl('featured_large'),
            'medium' => $media->getUrl('featured_medium'),
            'thumb' => $media->getUrl('featured_thumb'),
        ];
    }

    /**
     * Get gallery images with responsive URLs.
     */
    public function getGalleryImagesResponsiveAttribute(): array
    {
        return $this->getMedia('gallery_images')->map(function ($media) {
            return [
                'id' => $media->id,
                'name' => $media->name,
                'file_name' => $media->file_name,
                'mime_type' => $media->mime_type,
                'size' => $media->size,
                'urls' => [
                    'original' => $media->getUrl(),
                    'large' => $media->getUrl('gallery_large'),
                    'medium' => $media->getUrl('gallery_medium'),
                    'thumb' => $media->getUrl('gallery_thumb'),
                ],
            ];
        })->toArray();
    }

    /**
     * Check if page has featured image.
     */
    public function hasFeaturedImage(): bool
    {
        return $this->hasMedia('featured_image');
    }

    /**
     * Check if page has gallery images.
     */
    public function hasGalleryImages(): bool
    {
        return $this->hasMedia('gallery_images');
    }

    /**
     * Get total images count across all collections.
     */
    public function getTotalImagesCountAttribute(): int
    {
        return $this->getMedia('featured_image')->count() +
               $this->getMedia('gallery_images')->count();
    }

    /**
     * Get page images summary for admin display.
     */
    public function getImagesSummaryAttribute(): array
    {
        return [
            'featured' => $this->getMedia('featured_image')->count(),
            'gallery' => $this->getMedia('gallery_images')->count(),
            'total' => $this->total_images_count,
        ];
    }

    /**
     * Scope to filter pages with featured images.
     */
    public function scopeWithFeaturedImage($query)
    {
        return $query->whereHas('media', function ($q) {
            $q->where('collection_name', 'featured_image');
        });
    }

    /**
     * Scope to filter pages with gallery images.
     */
    public function scopeWithGalleryImages($query)
    {
        return $query->whereHas('media', function ($q) {
            $q->where('collection_name', 'gallery_images');
        });
    }

    /**
     * Scope to filter pages by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to search pages by title or content.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%")
              ->orWhere('slug', 'like', "%{$search}%");
        });
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = \Str::slug($page->title);
            }
        });

        static::updating(function ($page) {
            if ($page->isDirty('title') && empty($page->getOriginal('slug'))) {
                $page->slug = \Str::slug($page->title);
            }
        });
    }
}
