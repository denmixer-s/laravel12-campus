<?php

namespace App\Livewire\Frontend\Sliders;

use App\Models\Slider;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CarouselSlider extends Component
{
    // Component properties
    public string $location = 'both'; // 'home', 'frontend', 'both'
    public int $autoplayDelay = 5000; // 5 seconds
    public bool $showIndicators = true;
    public bool $showNavigation = true;
    public bool $autoplay = true;
    public bool $pauseOnHover = true;
    public string $transition = 'slide'; // 'slide', 'fade'
    public int $cacheTime = 300; // 5 minutes

    // UI State
    public int $currentSlide = 0;
    public Collection $sliders;
    public bool $isLoaded = false;

    // Component lifecycle
    public function mount(
        string $location = 'both',
        int $autoplayDelay = 5000,
        bool $showIndicators = true,
        bool $showNavigation = true,
        bool $autoplay = true,
        bool $pauseOnHover = true,
        string $transition = 'slide',
        int $cacheTime = 300
    ) {
        $this->location = $location;
        $this->autoplayDelay = $autoplayDelay;
        $this->showIndicators = $showIndicators;
        $this->showNavigation = $showNavigation;
        $this->autoplay = $autoplay;
        $this->pauseOnHover = $pauseOnHover;
        $this->transition = $transition;
        $this->cacheTime = $cacheTime;

        $this->loadSliders();
    }

    // Load sliders from database with caching
    public function loadSliders()
    {
        try {
            $cacheKey = "sliders_{$this->location}";
            
            $this->sliders = Cache::remember($cacheKey, $this->cacheTime, function () {
                return Slider::with(['user', 'media'])
                    ->when($this->location === 'home', function ($query) {
                        $query->forHome();
                    })
                    ->when($this->location === 'frontend', function ($query) {
                        $query->forFrontend();
                    })
                    ->when($this->location === 'both', function ($query) {
                        $query->where(function ($q) {
                            $q->where('show', 'both')
                              ->orWhere('show', 'home')
                              ->orWhere('show', 'frontend');
                        });
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();
            });

            $this->isLoaded = true;

            Log::info('CarouselSlider: Sliders loaded', [
                'location' => $this->location,
                'count' => $this->sliders->count(),
                'cache_key' => $cacheKey
            ]);

        } catch (\Exception $e) {
            Log::error('CarouselSlider: Failed to load sliders', [
                'error' => $e->getMessage(),
                'location' => $this->location
            ]);
            
            $this->sliders = collect();
            $this->isLoaded = true;
        }
    }

    // Navigation methods
    public function nextSlide()
    {
        if ($this->sliders->isEmpty()) return;
        
        $this->currentSlide = ($this->currentSlide + 1) % $this->sliders->count();
        $this->dispatch('slide-changed', $this->currentSlide);
    }

    public function previousSlide()
    {
        if ($this->sliders->isEmpty()) return;
        
        $this->currentSlide = $this->currentSlide === 0 
            ? $this->sliders->count() - 1 
            : $this->currentSlide - 1;
            
        $this->dispatch('slide-changed', $this->currentSlide);
    }

    public function goToSlide(int $index)
    {
        if ($this->sliders->isEmpty() || $index < 0 || $index >= $this->sliders->count()) {
            return;
        }
        
        $this->currentSlide = $index;
        $this->dispatch('slide-changed', $this->currentSlide);
    }

    // Track image click - ENHANCED METHOD
    public function trackImageClick(int $sliderId, string $link, string $clickType = 'image')
    {
        try {
            Log::info('CarouselSlider: Image clicked', [
                'slider_id' => $sliderId,
                'link' => $link,
                'click_type' => $clickType,
                'user_agent' => request()->userAgent(),
                'ip' => request()->ip(),
                'referrer' => request()->header('referer'),
                'timestamp' => now()
            ]);

            // Dispatch event for JavaScript to handle
            $this->dispatch('slider-image-clicked', [
                'sliderId' => $sliderId,
                'link' => $link,
                'clickType' => $clickType
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('CarouselSlider: Failed to track image click', [
                'error' => $e->getMessage(),
                'slider_id' => $sliderId
            ]);
            return false;
        }
    }

    // Track slide interaction (for button clicks)
    public function trackSlideClick(int $sliderId, string $link)
    {
        return $this->trackImageClick($sliderId, $link, 'button');
    }

    // NEW METHOD: Handle slider link click - This is the key method!
    public function handleSliderClick(int $sliderId, string $link)
    {
        // Track the click
        $this->trackImageClick($sliderId, $link, 'slider');
        
        // Find the slider
        $slider = $this->sliders->where('id', $sliderId)->first();
        
        if (!$slider) {
            Log::warning('CarouselSlider: Slider not found', ['slider_id' => $sliderId]);
            return;
        }

        // Clean the link
        $link = trim($link);
        
        if (empty($link)) {
            return;
        }

        // Determine link type and dispatch appropriate JavaScript event
        if (filter_var($link, FILTER_VALIDATE_URL)) {
            // External URL
            $this->dispatch('open-external-link', ['url' => $link]);
        } elseif (str_starts_with($link, '#')) {
            // Anchor link
            $this->dispatch('scroll-to-anchor', ['anchor' => $link]);
        } elseif (str_starts_with($link, '/')) {
            // Internal absolute path
            $this->dispatch('navigate-to', ['url' => $link]);
        } else {
            // Relative path - make it absolute
            $this->dispatch('navigate-to', ['url' => '/' . ltrim($link, '/')]);
        }

        Log::info('CarouselSlider: Slider click handled', [
            'slider_id' => $sliderId,
            'link' => $link,
            'link_type' => $this->getLinkType($link)
        ]);
    }

    // Helper method to determine link type
    private function getLinkType(string $link): string
    {
        if (filter_var($link, FILTER_VALIDATE_URL)) {
            return 'external';
        } elseif (str_starts_with($link, '#')) {
            return 'anchor';
        } elseif (str_starts_with($link, '/')) {
            return 'internal_absolute';
        } else {
            return 'internal_relative';
        }
    }

    // Clear cache (useful for admin updates)
    public function refreshSliders()
    {
        $cacheKey = "sliders_{$this->location}";
        Cache::forget($cacheKey);
        $this->loadSliders();
        $this->currentSlide = 0;
        
        $this->dispatch('sliders-refreshed');
    }

    // Computed properties
    public function getCurrentSliderProperty()
    {
        if ($this->sliders->isEmpty()) {
            return null;
        }
        
        return $this->sliders->get($this->currentSlide);
    }

    public function getHasSlidersProperty(): bool
    {
        return $this->sliders->isNotEmpty();
    }

    public function getSliderCountProperty(): int
    {
        return $this->sliders->count();
    }

    public function getSlidersCollectionProperty()
    {
        return $this->sliders;
    }

    // Get slider image URL with fallback
    public function getSliderImageUrl($slider, string $conversion = ''): string
    {
        try {
            if ($slider->hasSliderImage()) {
                return $slider->getSliderImageUrl($conversion);
            }
        } catch (\Exception $e) {
            Log::warning('CarouselSlider: Failed to get image URL', [
                'slider_id' => $slider->id,
                'error' => $e->getMessage()
            ]);
        }
        
        // Return a default placeholder image
        return 'https://via.placeholder.com/1920x1080/1e293b/ffffff?text=RMUTI+Slider+Image';
    }

    // Get responsive image URLs
    public function getResponsiveImageUrls($slider): array
    {
        try {
            if ($slider->hasSliderImage()) {
                return $slider->getResponsiveImageUrls();
            }
        } catch (\Exception $e) {
            Log::warning('CarouselSlider: Failed to get responsive URLs', [
                'slider_id' => $slider->id,
                'error' => $e->getMessage()
            ]);
        }
        
        // Return placeholder images for different sizes
        return [
            'desktop' => 'https://via.placeholder.com/1920x1080/1e293b/ffffff?text=RMUTI+Desktop+1920x1080',
            'tablet' => 'https://via.placeholder.com/768x432/1e293b/ffffff?text=RMUTI+Tablet+768x432',
            'mobile' => 'https://via.placeholder.com/375x211/1e293b/ffffff?text=RMUTI+Mobile+375x211',
        ];
    }

    // Check if slider has a link
    public function hasSliderLink($slider): bool
    {
        return !empty(trim($slider->link ?? ''));
    }

    // Get cleaned slider link
    public function getSliderLink($slider): string
    {
        return trim($slider->link ?? '');
    }

    // Render component
    public function render()
    {
        return view('livewire.frontend.sliders.carousel-slider');
    }
}