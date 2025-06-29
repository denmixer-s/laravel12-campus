<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Slider extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'heading',
        'description',
        'link',
        'show',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Define show options
    public const SHOW_OPTIONS = [
        'home' => 'Home Page Only',
        'frontend' => 'Frontend Only',
        'both' => 'Both Home & Frontend',
    ];

    /**
     * Get the user that owns the slider
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('slider_images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
            ->singleFile();
    }

    /**
     * Register media conversions - FIXED DEPRECATION WARNING
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        // Only add conversions if media exists and is an image
        if ($media && str_starts_with($media->mime_type, 'image/')) {
            // Main slider image
            $this->addMediaConversion('slider')
                ->width(1920)
                ->height(1080)
                ->quality(85)
                ->performOnCollections('slider_images')
                ->nonQueued();

            // Thumbnail for admin
            $this->addMediaConversion('thumbnail')
                ->width(300)
                ->height(200)
                ->quality(75)
                ->performOnCollections('slider_images')
                ->nonQueued();

            // Small thumbnail for lists
            $this->addMediaConversion('thumb-small')
                ->width(150)
                ->height(100)
                ->quality(70)
                ->performOnCollections('slider_images')
                ->nonQueued();
        }
    }

    /**
     * Check if slider has image (works with both Media Library and simple storage)
     */
    public function hasSliderImage(): bool
    {
        // Check Media Library first
        if ($this->hasMedia('slider_images')) {
            return true;
        }
        
        // Check simple image_path
        return !empty($this->image_path);
    }

    /**
     * Get the slider image URL (works with both Media Library and simple storage)
     */
    public function getSliderImageUrl(string $conversion = ''): string
    {
        // Try Media Library first
        $media = $this->getFirstMedia('slider_images');
        if ($media) {
            return $conversion ? $media->getUrl($conversion) : $media->getUrl();
        }
        
        // Use simple image_path if available
        if (!empty($this->image_path)) {
            return Storage::url($this->image_path);
        }
        
        // Default placeholder
        return asset('images/placeholder-slider.jpg');
    }

    /**
     * Get responsive image URLs
     */
    public function getResponsiveImageUrls(): array
    {
        $media = $this->getFirstMedia('slider_images');
        
        if ($media) {
            return [
                'desktop' => $media->getUrl('slider'),
                'tablet' => $media->getUrl('slider'),
                'mobile' => $media->getUrl('slider'),
            ];
        }
        
        if (!empty($this->image_path)) {
            $url = Storage::url($this->image_path);
            return [
                'desktop' => $url,
                'tablet' => $url,
                'mobile' => $url,
            ];
        }
        
        return [
            'desktop' => asset('images/placeholder-slider.jpg'),
            'tablet' => asset('images/placeholder-slider.jpg'),
            'mobile' => asset('images/placeholder-slider.jpg'),
        ];
    }

    /**
     * Scope for specific show location
     */
    public function scopeForLocation($query, string $location)
    {
        return $query->where('show', $location)->orWhere('show', 'both');
    }

    /**
     * Scope for home page
     */
    public function scopeForHome($query)
    {
        return $query->where(function ($q) {
            $q->where('show', 'home')->orWhere('show', 'both');
        });
    }

    /**
     * Scope for frontend
     */
    public function scopeForFrontend($query)
    {
        return $query->where(function ($q) {
            $q->where('show', 'frontend')->orWhere('show', 'both');
        });
    }

    /**
     * Get formatted show location
     */
    public function getShowLocationAttribute(): string
    {
        return self::SHOW_OPTIONS[$this->show] ?? $this->show;
    }

    /**
     * Get badge color for show location
     */
    public function getShowBadgeColorAttribute(): string
    {
        return match($this->show) {
            'home' => 'bg-blue-100 text-blue-800',
            'frontend' => 'bg-green-100 text-green-800',
            'both' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}