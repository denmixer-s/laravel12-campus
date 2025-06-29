<?php

namespace App\Livewire\Sakon\Pages;

use App\Models\Page;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

#[Layout('components.layouts.dashboard')]
#[Title('View Page')]
class ShowPage extends Component
{
    use AuthorizesRequests;

    // Page model instance
    public Page $page;

    // Display properties
    public $featuredImage;
    public $galleryImages = [];
    public $imagesSummary = [];

    // UI state
    public $showFullContent = false;
    public $selectedGalleryImage = null;
    public $showGalleryModal = false;

    public function mount(Page $page)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Authorization check - temporarily disabled for debugging
        try {
            $this->authorize('view', $page);
        } catch (\Exception $e) {
            session()->flash('error', 'You do not have permission to view this page.');
            return redirect()->route('administrator.pages.index');
        }

        // Load the page with relationships
        $this->page = $page->load('user', 'media');

        Log::info('ShowPage mounted successfully', [
            'page_id' => $page->id,
            'title' => $page->title,
            'user_id' => $page->user_id ?? 'null',
        ]);

        // Load media data
        $this->loadMediaData();
    }

    protected function loadMediaData()
    {
        // Load featured image
        $this->featuredImage = $this->page->getFirstMedia('featured_image');

        // Load gallery images with responsive URLs
        $this->galleryImages = $this->page->getMedia('gallery_images')->map(function ($media) {
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

        // Load images summary
        $this->imagesSummary = $this->page->images_summary;

        Log::info('ShowPage media loaded', [
            'page_id' => $this->page->id,
            'has_featured' => $this->featuredImage ? 'yes' : 'no',
            'gallery_count' => count($this->galleryImages),
            'images_summary' => $this->imagesSummary,
        ]);
    }

    // Toggle full content display
    public function toggleFullContent()
    {
        $this->showFullContent = !$this->showFullContent;
    }

    // Gallery modal functionality
    public function openGalleryModal($imageData)
    {
        $this->selectedGalleryImage = $imageData;
        $this->showGalleryModal = true;
    }

    public function closeGalleryModal()
    {
        $this->showGalleryModal = false;
        $this->selectedGalleryImage = null;
    }

    public function nextGalleryImage()
    {
        if (!$this->selectedGalleryImage || empty($this->galleryImages)) {
            return;
        }

        $currentIndex = collect($this->galleryImages)->search(function ($image) {
            return $image['id'] === $this->selectedGalleryImage['id'];
        });

        if ($currentIndex !== false) {
            $nextIndex = ($currentIndex + 1) % count($this->galleryImages);
            $this->selectedGalleryImage = $this->galleryImages[$nextIndex];
        }
    }

    public function prevGalleryImage()
    {
        if (!$this->selectedGalleryImage || empty($this->galleryImages)) {
            return;
        }

        $currentIndex = collect($this->galleryImages)->search(function ($image) {
            return $image['id'] === $this->selectedGalleryImage['id'];
        });

        if ($currentIndex !== false) {
            $prevIndex = ($currentIndex - 1 + count($this->galleryImages)) % count($this->galleryImages);
            $this->selectedGalleryImage = $this->galleryImages[$prevIndex];
        }
    }

    // Navigation methods
    public function editPage()
    {
        return $this->redirect(route('administrator.pages.edit', $this->page), navigate: true);
    }

    public function backToList()
    {
        return $this->redirect(route('administrator.pages.index'), navigate: true);
    }

    // Helper methods
    public function getContentPreview($limit = 200)
    {
        if (!$this->page->content) {
            return 'No content available.';
        }

        $stripped = strip_tags($this->page->content);
        return strlen($stripped) > $limit ? substr($stripped, 0, $limit) . '...' : $stripped;
    }

    public function getWordCount()
    {
        if (!$this->page->content) {
            return 0;
        }

        return str_word_count(strip_tags($this->page->content));
    }

    public function getCharacterCount()
    {
        if (!$this->page->content) {
            return 0;
        }

        return strlen(strip_tags($this->page->content));
    }

    public function getPageUrl()
    {
        return url('/' . $this->page->slug);
    }

    // Check if user can edit this page
    public function getCanEditProperty()
    {
        // Simple check - you can customize this logic
        return auth()->user()->hasRole('Super Admin') ||
               auth()->user()->can('update-page') ||
               $this->page->user_id === auth()->id();
    }

    // Format file size
    public function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    // Get relative time
    public function getRelativeTime($dateTime)
    {
        return $dateTime ? $dateTime->diffForHumans() : 'Unknown';
    }

    // SEO and meta information
    public function getSeoScoreProperty()
    {
        $score = 0;
        $maxScore = 100;

        // Title check (20 points)
        if (!empty($this->page->title)) {
            if (strlen($this->page->title) >= 30 && strlen($this->page->title) <= 60) {
                $score += 20;
            } elseif (strlen($this->page->title) >= 10) {
                $score += 10;
            }
        }

        // Content check (25 points)
        if (!empty($this->page->content)) {
            $wordCount = $this->getWordCount();
            if ($wordCount >= 300) {
                $score += 25;
            } elseif ($wordCount >= 100) {
                $score += 15;
            } elseif ($wordCount >= 50) {
                $score += 10;
            }
        }

        // Slug check (15 points)
        if (!empty($this->page->slug)) {
            if (strlen($this->page->slug) <= 60 && preg_match('/^[a-z0-9-]+$/', $this->page->slug)) {
                $score += 15;
            } elseif (!empty($this->page->slug)) {
                $score += 10;
            }
        }

        // Featured image check (20 points)
        if ($this->featuredImage) {
            $score += 20;
        }

        // Gallery images check (10 points)
        if (!empty($this->galleryImages)) {
            $score += 10;
        }

        // Recent update check (10 points)
        if ($this->page->updated_at && $this->page->updated_at->gt(now()->subDays(30))) {
            $score += 10;
        }

        return min($score, $maxScore);
    }

    public function getSeoRecommendationsProperty()
    {
        $recommendations = [];

        // Title recommendations
        if (empty($this->page->title)) {
            $recommendations[] = 'Add a page title';
        } elseif (strlen($this->page->title) < 30) {
            $recommendations[] = 'Consider a longer, more descriptive title (30-60 characters)';
        } elseif (strlen($this->page->title) > 60) {
            $recommendations[] = 'Shorten the title to under 60 characters';
        }

        // Content recommendations
        $wordCount = $this->getWordCount();
        if ($wordCount < 50) {
            $recommendations[] = 'Add more content (aim for at least 300 words)';
        } elseif ($wordCount < 300) {
            $recommendations[] = 'Consider adding more detailed content';
        }

        // Featured image recommendation
        if (!$this->featuredImage) {
            $recommendations[] = 'Add a featured image to improve visual appeal';
        }

        // Slug recommendation
        if (empty($this->page->slug)) {
            $recommendations[] = 'Add a URL slug';
        } elseif (strlen($this->page->slug) > 60) {
            $recommendations[] = 'Shorten the URL slug';
        }

        return $recommendations;
    }

    public function render()
    {
        return view('livewire.sakon.pages.show-page');
    }
}
