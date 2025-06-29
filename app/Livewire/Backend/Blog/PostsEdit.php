<?php
namespace App\Livewire\Backend\Blog;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.dashboard')]
#[Title('Edit Blog Post')]

class PostsEdit extends Component
{
    use WithFileUploads;

    // Post instance
    public BlogPost $post;

    // Basic post properties
    public $title     = '';
    public $slug      = '';
    public $excerpt   = '';
    public $content   = '';
    public $auto_slug = false; // Usually false for editing

    // Publishing options
    public $status         = 'draft';
    public $published_at   = '';
    public $scheduled_at   = '';
    public $is_featured    = false;
    public $is_sticky      = false;
    public $allow_comments = true;

    // Relationships
    public $blog_category_id = '';
    public $selectedTags     = [];
    public $newTag           = '';

    // SEO fields
    public $meta_title       = '';
    public $meta_description = '';
    public $meta_keywords    = '';

    // Media properties
    public $featuredImage;
    public $galleryImages = [];
    public $socialImages  = [];

    // Admin fields
    public $admin_notes = '';

    // UI state
    public $isProcessing       = false;
    public $isDraftSaving      = false;
    public $showSuccessMessage = false;
    public $successMessage     = '';
    public $lastSavedAt        = null;

    // Auto-save
    public $autoSaveEnabled  = true;
    public $autoSaveInterval = 30; // seconds

    // WYSIWYG editor state
    public $editorReady = false;

    // Computed properties for UI
    public $availableCategories;
    public $availableTags;

    // Current media files (existing)
    public $currentFeaturedImage = null;
    public $currentGalleryImages = [];
    public $currentSocialImages  = [];

    // Track changes
    public $hasChanges = false;

    // Track media deletions
    public $deletedGalleryImages = [];
    public $deletedSocialImages = [];

    // Validation rules
    protected function rules()
    {
        $rules = [
            'title'            => 'required|string|max:255',
            'slug'             => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', // English only
                Rule::unique('blog_posts', 'slug')->ignore($this->post->id),
            ],
            'excerpt'          => 'nullable|string|max:500',
            'content'          => 'required|string|min:10',
            'blog_category_id' => 'required|exists:blog_categories,id',
            'status'           => 'required|in:draft,published,scheduled,archived',
            'meta_title'       => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords'    => 'nullable|string|max:255',
            'admin_notes'      => 'nullable|string|max:1000',
            'featuredImage'    => 'nullable|image|max:10240', // 10MB
            'galleryImages.*'  => 'nullable|image|max:10240',
            'socialImages.*'   => 'nullable|image|max:10240',
            'selectedTags'     => 'array',
            'selectedTags.*'   => 'exists:blog_tags,id',
            'is_featured'      => 'boolean',
            'is_sticky'        => 'boolean',
            'allow_comments'   => 'boolean',
        ];

        // Add scheduling validation
        if ($this->status === 'scheduled') {
            $rules['scheduled_at'] = 'required|date|after:now';
        }

        // Published posts need published_at
        if ($this->status === 'published') {
            $rules['published_at'] = 'nullable|date';
        }

        return $rules;
    }

    protected $messages = [
        'title.required'            => 'Post title is required.',
        'title.max'                 => 'Title cannot exceed 255 characters.',
        'slug.required'             => 'Post slug is required.',
        'slug.max'                  => 'Slug cannot exceed 255 characters.',
        'slug.regex'                => 'Slug must contain only English letters, numbers, and hyphens (no Thai characters).',
        'slug.unique'               => 'This slug is already taken. Please choose another.',
        'content.required'          => 'Post content is required.',
        'content.min'               => 'Post content must be at least 10 characters.',
        'blog_category_id.required' => 'Please select a category.',
        'blog_category_id.exists'   => 'Selected category is invalid.',
        'meta_title.max'            => 'Meta title should not exceed 60 characters.',
        'meta_description.max'      => 'Meta description should not exceed 160 characters.',
        'scheduled_at.required'     => 'Scheduled date is required for scheduled posts.',
        'scheduled_at.after'        => 'Scheduled date must be in the future.',
        'featuredImage.image'       => 'Featured image must be a valid image file.',
        'featuredImage.max'         => 'Featured image cannot exceed 10MB.',
    ];

    // Real-time validation and auto-slug with Thai support
    public function updatedTitle()
    {
        $this->validateOnly('title');

        if ($this->auto_slug) {
            $this->slug = $this->generateSlugFromTitle($this->title);
            $this->validateOnly('slug');
        }

        // Auto-generate meta title if empty
        if (empty($this->meta_title)) {
            $this->meta_title = Str::limit($this->title, 57);
        }

        $this->markAsChanged();
        $this->scheduleAutoSave();
    }

    public function updatedSlug()
    {
        // Only clean and validate if not auto-generating
        if (!$this->auto_slug) {
            // Clean the slug input for manual entry
            $originalSlug = $this->slug;
            $cleanedSlug = $this->cleanSlugInput($this->slug);

            // Only update if it actually changed to avoid infinite loop
            if ($cleanedSlug !== $originalSlug) {
                $this->slug = $cleanedSlug;
            }

            // Validate the cleaned slug
            $this->validateOnly('slug');
        }

        $this->markAsChanged();
        $this->scheduleAutoSave();
    }

    /**
     * Toggle auto-slug functionality
     */
    public function toggleAutoSlug()
    {
        $this->auto_slug = !$this->auto_slug;

        if ($this->auto_slug && !empty($this->title)) {
            // Re-generate slug from title when switching to auto mode
            $this->slug = $this->generateSlugFromTitle($this->title);
        } else {
            // Keep existing slug when switching to manual mode
            if ($this->auto_slug === false) {
                $this->slug = $this->slug ?: '';
            }
        }

        // Clear any slug validation errors when toggling
        $this->resetValidation('slug');

        // Validate the new slug state
        if (!empty($this->slug)) {
            $this->validateOnly('slug');
        }

        // Dispatch event to update frontend
        $this->dispatch('autoSlugToggled', ['autoSlug' => $this->auto_slug]);
    }

    /**
     * Manual slug update handler (called from JavaScript)
     */
    public function updateSlugManually($slug)
    {
        if (!$this->auto_slug) {
            $this->slug = $this->cleanSlugInput($slug);
            $this->validateOnly('slug');
            $this->markAsChanged();
        }
    }

    /**
     * Generate English slug from Thai/English title
     */
    private function generateSlugFromTitle($title)
    {
        if (empty($title)) {
            return '';
        }

        // Thai to English transliteration mapping (improved)
        $thaiToEnglish = [
            // Consonants
            'ก' => 'k', 'ข' => 'kh', 'ฃ' => 'kh', 'ค' => 'kh', 'ฅ' => 'kh', 'ฆ' => 'kh',
            'ง' => 'ng', 'จ' => 'ch', 'ฉ' => 'ch', 'ช' => 'ch', 'ซ' => 's', 'ฌ' => 'ch',
            'ญ' => 'y', 'ฎ' => 'd', 'ฏ' => 't', 'ฐ' => 'th', 'ฑ' => 'th', 'ฒ' => 'th',
            'ณ' => 'n', 'ด' => 'd', 'ต' => 't', 'ถ' => 'th', 'ท' => 'th', 'ธ' => 'th',
            'น' => 'n', 'บ' => 'b', 'ป' => 'p', 'ผ' => 'ph', 'ฝ' => 'f', 'พ' => 'ph',
            'ฟ' => 'f', 'ภ' => 'ph', 'ม' => 'm', 'ย' => 'y', 'ร' => 'r', 'ล' => 'l',
            'ว' => 'w', 'ศ' => 's', 'ษ' => 's', 'ส' => 's', 'ห' => 'h', 'ฬ' => 'l',
            'อ' => 'o', 'ฮ' => 'h',

            // Vowels
            'ะ' => 'a', 'า' => 'a', 'ิ' => 'i', 'ี' => 'i', 'ึ' => 'ue', 'ื' => 'ue',
            'ุ' => 'u', 'ู' => 'u', 'เ' => 'e', 'แ' => 'ae', 'โ' => 'o', 'ใ' => 'ai',
            'ไ' => 'ai', 'ฤ' => 'rue', 'ฦ' => 'lue',

            // Complex vowels
            'เอ' => 'e', 'แอ' => 'ae', 'โอ' => 'o', 'เอา' => 'ao', 'เอี' => 'i',
            'เอื' => 'ue', 'เอา' => 'ao', 'ไอ' => 'ai', 'ใอ' => 'ai',

            // Tone marks (usually ignored in transliteration)
            '่' => '', '้' => '', '๊' => '', '๋' => '',

            // Special characters
            '์' => '',  // maikhantat (silent)
            'ๆ' => '2', // repetition sign
            '฿' => 'baht',
        ];

        // Apply transliteration
        $transliterated = strtr($title, $thaiToEnglish);

        // Clean up and create slug
        $slug = Str::slug($transliterated);

        // If slug is empty or too short, try alternative approach
        if (empty($slug) || strlen($slug) < 2) {
            $slug = $this->createFallbackSlug($title);
        }

        return $slug;
    }

    /**
     * Create fallback slug for complex Thai text
     */
    private function createFallbackSlug($title)
    {
        // Remove all non-alphanumeric except spaces and hyphens
        $cleaned = preg_replace('/[^a-zA-Z0-9\s\-_ก-๙]/', '', $title);

        // If still contains Thai, create generic slug
        if (preg_match('/[ก-๙]/', $cleaned)) {
            return 'thai-post-' . time();
        }

        // Try standard Str::slug
        return Str::slug($cleaned) ?: 'post-' . time();
    }

    /**
     * Clean slug input for manual entry (English only)
     */
    private function cleanSlugInput($input)
    {
        if (empty($input)) {
            return '';
        }

        // Convert to lowercase first
        $cleaned = strtolower(trim($input));

        // Remove Thai characters completely
        $cleaned = preg_replace('/[ก-๙]/', '', $cleaned);

        // Replace spaces and special characters with hyphens
        $cleaned = preg_replace('/[^a-z0-9\-]/', '-', $cleaned);

        // Clean up multiple consecutive hyphens
        $cleaned = preg_replace('/-+/', '-', $cleaned);

        // Remove leading and trailing hyphens
        $cleaned = trim($cleaned, '-');

        return $cleaned;
    }

    public function updatedExcerpt()
    {
        $this->validateOnly('excerpt');

        // Auto-generate meta description if empty
        if (empty($this->meta_description) && !empty($this->excerpt)) {
            $this->meta_description = Str::limit(strip_tags($this->excerpt), 157);
        }

        $this->markAsChanged();
        $this->scheduleAutoSave();
    }

    public function updatedContent()
    {
        $this->validateOnly('content');

        // Auto-generate excerpt if empty
        if (empty($this->excerpt)) {
            $this->excerpt = Str::limit(strip_tags($this->content), 197);
        }

        // Auto-generate meta description if empty
        if (empty($this->meta_description)) {
            $this->meta_description = Str::limit(strip_tags($this->content), 157);
        }

        $this->markAsChanged();
        $this->scheduleAutoSave();
    }

    public function updatedStatus()
    {
        $this->validateOnly('status');

        // Set published_at when publishing
        if ($this->status === 'published' && empty($this->published_at)) {
            $this->published_at = now()->format('Y-m-d H:i');
        }

        // Clear scheduled_at if not scheduling
        if ($this->status !== 'scheduled') {
            $this->scheduled_at = '';
        }

        $this->markAsChanged();
    }

    public function updatedBlogCategoryId()
    {
        $this->validateOnly('blog_category_id');
        $this->markAsChanged();
    }

    public function updatedFeaturedImage()
    {
        $this->validateOnly('featuredImage');
        $this->markAsChanged();
    }

    // Toggle methods
    public function toggleFeatured()
    {
        $this->is_featured = !$this->is_featured;
        $this->markAsChanged();
        $this->scheduleAutoSave();
    }

    public function toggleSticky()
    {
        $this->is_sticky = !$this->is_sticky;
        $this->markAsChanged();
        $this->scheduleAutoSave();
    }

    public function toggleComments()
    {
        $this->allow_comments = !$this->allow_comments;
        $this->markAsChanged();
        $this->scheduleAutoSave();
    }

    public function toggleAutoSave()
    {
        $this->autoSaveEnabled = !$this->autoSaveEnabled;
    }

    // Tag management - Fixed
    public function addTag()
    {
        if (empty(trim($this->newTag))) {
            return;
        }

        $tagName = trim($this->newTag);

        try {
            // Find or create tag
            $tag = BlogTag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                [
                    'name' => $tagName,
                    'slug' => Str::slug($tagName),
                    'color' => '#' . substr(md5($tagName), 0, 6), // Generate color from tag name
                    'is_active' => true,
                ]
            );

            // Add to selected tags if not already there
            if (!in_array($tag->id, $this->selectedTags)) {
                $this->selectedTags[] = $tag->id;
            }

            $this->newTag = '';
            $this->refreshAvailableTags();
            $this->markAsChanged();
            $this->scheduleAutoSave();

            Log::info('Tag added successfully', [
                'tag_id' => $tag->id,
                'tag_name' => $tag->name,
                'selected_tags' => $this->selectedTags,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to add tag', [
                'tag_name' => $tagName,
                'error' => $e->getMessage(),
            ]);

            session()->flash('error', 'Failed to add tag: ' . $e->getMessage());
        }
    }

    public function removeTag($tagId)
    {
        $this->selectedTags = array_filter($this->selectedTags, fn($id) => $id != $tagId);
        $this->selectedTags = array_values($this->selectedTags); // Re-index array
        $this->markAsChanged();
        $this->scheduleAutoSave();

        Log::info('Tag removed', [
            'removed_tag_id' => $tagId,
            'remaining_tags' => $this->selectedTags,
        ]);
    }

    // Media management - Fixed
    public function removeFeaturedImage()
    {
        $this->featuredImage = null;
        $this->resetValidation('featuredImage');
        $this->markAsChanged();
    }

    public function removeCurrentFeaturedImage()
    {
        $this->currentFeaturedImage = null;
        $this->markAsChanged();
    }

    public function removeGalleryImage($index)
    {
        if (isset($this->galleryImages[$index])) {
            unset($this->galleryImages[$index]);
            $this->galleryImages = array_values($this->galleryImages);
            $this->resetValidation('galleryImages.*');
            $this->markAsChanged();
        }
    }

    public function removeCurrentGalleryImage($index)
    {
        if (isset($this->currentGalleryImages[$index])) {
            // Track deleted images for cleanup
            $deletedImage = $this->currentGalleryImages[$index];
            $this->deletedGalleryImages[] = $deletedImage['id'];

            unset($this->currentGalleryImages[$index]);
            $this->currentGalleryImages = array_values($this->currentGalleryImages);
            $this->markAsChanged();

            Log::info('Gallery image marked for deletion', [
                'media_id' => $deletedImage['id'],
                'index' => $index,
            ]);
        }
    }

    public function removeSocialImage($index)
    {
        if (isset($this->socialImages[$index])) {
            unset($this->socialImages[$index]);
            $this->socialImages = array_values($this->socialImages);
            $this->resetValidation('socialImages.*');
            $this->markAsChanged();
        }
    }

    public function removeCurrentSocialImage($index)
    {
        if (isset($this->currentSocialImages[$index])) {
            // Track deleted images for cleanup
            $deletedImage = $this->currentSocialImages[$index];
            $this->deletedSocialImages[] = $deletedImage['id'];

            unset($this->currentSocialImages[$index]);
            $this->currentSocialImages = array_values($this->currentSocialImages);
            $this->markAsChanged();

            Log::info('Social image marked for deletion', [
                'media_id' => $deletedImage['id'],
                'index' => $index,
            ]);
        }
    }

    // Change tracking
    private function markAsChanged()
    {
        $this->hasChanges = true;
    }

    // Auto-save functionality
    public function scheduleAutoSave()
    {
        if (!$this->autoSaveEnabled) {
            return;
        }

        $this->dispatch('scheduleAutoSave', [
            'interval' => $this->autoSaveInterval * 1000, // Convert to milliseconds
        ]);
    }

    public function autoSaveDraft()
    {
        if ($this->isDraftSaving || $this->isProcessing) {
            return;
        }

        // Don't auto-save if no meaningful content
        if (empty(trim($this->title)) && empty(trim($this->content))) {
            return;
        }

        $this->isDraftSaving = true;

        try {
            // Basic validation for auto-save
            $this->validate([
                'title' => 'nullable|string|max:255',
                'content' => 'nullable|string',
            ]);

            // Update post data - simplified logic for auto-save
            $this->post->update([
                'title' => $this->title ?: 'Untitled Draft',
                'slug' => $this->slug ?: Str::slug($this->title ?: 'untitled-draft-' . time()),
                'excerpt' => $this->excerpt,
                'content' => $this->content,
                'blog_category_id' => $this->blog_category_id ?: null,
                'is_featured' => $this->is_featured,
                'is_sticky' => $this->is_sticky,
                'allow_comments' => $this->allow_comments,
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'meta_keywords' => $this->meta_keywords,
                'admin_notes' => $this->admin_notes,
                'updated_at' => now(),
            ]);

            $this->lastSavedAt = now();
            $this->dispatch('autoSaveSuccess');

            Log::info('Auto-save successful', [
                'post_id' => $this->post->id,
                'title' => $this->title,
                'content_length' => strlen($this->content),
            ]);

        } catch (\Exception $e) {
            Log::error('Auto-save failed', ['error' => $e->getMessage()]);
            $this->dispatch('autoSaveFailed');
        } finally {
            $this->isDraftSaving = false;
        }
    }

    // Main update method - Fixed
    public function update()
    {
        Log::info('=== PostsEdit: START ===', ['post_id' => $this->post->id]);

        $this->isProcessing = true;

        try {
            // Validate all form data
            $this->validate();

            Log::info('PostsEdit: Validation passed', [
                'post_id' => $this->post->id,
                'title' => $this->title,
                'slug' => $this->slug,
                'status' => $this->status,
                'category_id' => $this->blog_category_id,
                'tags_count' => count($this->selectedTags),
            ]);

            // Update post and handle media in transaction
            DB::transaction(function () {
                // Get original category and tags for count updates
                $originalCategoryId = $this->post->blog_category_id;
                $originalTagIds = $this->post->tags->pluck('id')->toArray();

                // Prepare post data
                $postData = [
                    'title' => trim($this->title),
                    'slug' => trim($this->slug),
                    'excerpt' => $this->excerpt,
                    'content' => $this->content,
                    'blog_category_id' => $this->blog_category_id,
                    'status' => $this->status,
                    'is_featured' => $this->is_featured,
                    'is_sticky' => $this->is_sticky,
                    'allow_comments' => $this->allow_comments,
                    'meta_title' => $this->meta_title,
                    'meta_description' => $this->meta_description,
                    'meta_keywords' => $this->meta_keywords,
                    'admin_notes' => $this->admin_notes,
                    'reading_time' => $this->calculateReadingTime($this->content),
                ];

                // Set publishing dates
                if ($this->status === 'published') {
                    $postData['published_at'] = $this->published_at ?
                        Carbon::parse($this->published_at) : now();
                } elseif ($this->status === 'scheduled') {
                    $postData['scheduled_at'] = Carbon::parse($this->scheduled_at);
                } else {
                    $postData['published_at'] = null;
                    $postData['scheduled_at'] = null;
                }

                // Update the post
                $this->post->update($postData);

                Log::info('PostsEdit: Post updated in database', [
                    'post_id' => $this->post->id,
                    'title' => $this->post->title,
                    'status' => $this->post->status,
                ]);

                // Update tags - Fixed sync logic
                if ($this->selectedTags !== $originalTagIds) {
                    // Sync tags (this will automatically attach/detach)
                    $this->post->tags()->sync($this->selectedTags);

                    // Update old tags post count (decrement)
                    $removedTags = array_diff($originalTagIds, $this->selectedTags);
                    if (!empty($removedTags)) {
                        BlogTag::whereIn('id', $removedTags)->decrement('posts_count');
                    }

                    // Update new tags post count (increment)
                    $addedTags = array_diff($this->selectedTags, $originalTagIds);
                    if (!empty($addedTags)) {
                        BlogTag::whereIn('id', $addedTags)->increment('posts_count');
                    }

                    Log::info('PostsEdit: Tags updated', [
                        'new_tags_count' => count($this->selectedTags),
                        'added_tags' => $addedTags,
                        'removed_tags' => $removedTags,
                    ]);
                }

                // Update category post count
                if ($originalCategoryId != $this->blog_category_id) {
                    // Decrement old category
                    if ($originalCategoryId) {
                        BlogCategory::where('id', $originalCategoryId)->decrement('posts_count');
                    }
                    // Increment new category
                    BlogCategory::where('id', $this->blog_category_id)->increment('posts_count');
                }

                // Handle media updates - Fixed
                $this->handleMediaUpdates();
            });

            // Reset change tracking
            $this->hasChanges = false;

            // Success
            $this->showSuccessMessage = true;
            $this->successMessage = "Post '{$this->title}' updated successfully!";
            session()->flash('success', "Post '{$this->title}' updated successfully!");

            Log::info('=== PostsEdit: SUCCESS ===', [
                'post_id' => $this->post->id,
                'status' => $this->post->status,
            ]);

            return $this->redirect(route('administrator.blog.posts'), navigate: true);

        } catch (\Exception $e) {
            Log::error('=== PostsEdit: FAILED ===', [
                'post_id' => $this->post->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            session()->flash('error', 'Failed to update post: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    // Save as draft method
    public function saveDraft()
    {
        $originalStatus = $this->status;
        $this->status = 'draft';

        $result = $this->update();

        if (!$result) {
            $this->status = $originalStatus;
        }

        return $result;
    }

    // Handle media updates - Fixed
    private function handleMediaUpdates()
    {
        try {
            // Handle featured image
            if ($this->currentFeaturedImage === null && $this->post->hasMedia('featured_image')) {
                $this->post->clearMediaCollection('featured_image');
                Log::info('Cleared existing featured image');
            }

            if ($this->featuredImage) {
                // Clear existing and upload new
                $this->post->clearMediaCollection('featured_image');
                $this->uploadFeaturedImage();
            }

            // Handle deleted gallery images
            if (!empty($this->deletedGalleryImages)) {
                foreach ($this->deletedGalleryImages as $mediaId) {
                    $media = $this->post->getMedia('gallery')->where('id', $mediaId)->first();
                    if ($media) {
                        $media->delete();
                        Log::info('Deleted gallery image', ['media_id' => $mediaId]);
                    }
                }
                $this->deletedGalleryImages = []; // Reset after deletion
            }

            // Handle deleted social images
            if (!empty($this->deletedSocialImages)) {
                foreach ($this->deletedSocialImages as $mediaId) {
                    $media = $this->post->getMedia('social_images')->where('id', $mediaId)->first();
                    if ($media) {
                        $media->delete();
                        Log::info('Deleted social image', ['media_id' => $mediaId]);
                    }
                }
                $this->deletedSocialImages = []; // Reset after deletion
            }

            // Upload new gallery images
            if (!empty($this->galleryImages)) {
                $this->uploadGalleryImages();
            }

            // Upload new social images
            if (!empty($this->socialImages)) {
                $this->uploadSocialImages();
            }

        } catch (\Exception $e) {
            Log::error('Media update failed', [
                'post_id' => $this->post->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    // Media upload helpers
    private function uploadFeaturedImage()
    {
        try {
            Log::info('PostsEdit: Uploading featured image');

            $media = $this->post->addMedia($this->featuredImage->getRealPath())
                ->usingName($this->title . ' - Featured Image')
                ->usingFileName($this->featuredImage->getClientOriginalName())
                ->toMediaCollection('featured_image');

            Log::info('PostsEdit: Featured image uploaded successfully', [
                'media_id' => $media->id,
                'file_name' => $media->file_name,
            ]);

        } catch (\Exception $e) {
            Log::error('PostsEdit: Featured image upload failed', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    private function uploadGalleryImages()
    {
        try {
            Log::info('PostsEdit: Uploading gallery images', [
                'count' => count($this->galleryImages),
            ]);

            foreach ($this->galleryImages as $index => $image) {
                $media = $this->post->addMedia($image->getRealPath())
                    ->usingName($this->title . ' - Gallery Image ' . ($index + 1))
                    ->usingFileName($image->getClientOriginalName())
                    ->toMediaCollection('gallery');

                Log::info('PostsEdit: Gallery image uploaded', [
                    'index' => $index,
                    'media_id' => $media->id,
                ]);
            }

            // Clear uploaded images after successful upload
            $this->galleryImages = [];

        } catch (\Exception $e) {
            Log::error('PostsEdit: Gallery images upload failed', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    private function uploadSocialImages()
    {
        try {
            foreach ($this->socialImages as $index => $image) {
                $media = $this->post->addMedia($image->getRealPath())
                    ->usingName($this->title . ' - Social Image ' . ($index + 1))
                    ->usingFileName($image->getClientOriginalName())
                    ->toMediaCollection('social_images');

                Log::info('PostsEdit: Social image uploaded', [
                    'index' => $index,
                    'media_id' => $media->id,
                ]);
            }

            // Clear uploaded images after successful upload
            $this->socialImages = [];

        } catch (\Exception $e) {
            Log::error('PostsEdit: Social images upload failed', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    // Utility methods
    private function calculateReadingTime($content)
    {
        $wordCount = str_word_count(strip_tags($content));
        $wordsPerMinute = 200; // Average reading speed
        return max(1, ceil($wordCount / $wordsPerMinute));
    }

    // Form management
    public function resetForm()
    {
        $this->loadPostData();
        $this->resetValidation();
        $this->hasChanges = false;
        $this->dispatch('resetEditor');
    }

    public function cancel()
    {
        if ($this->hasChanges) {
            // Could add confirmation dialog here
            $this->dispatch('confirmCancel');
            return;
        }

        return $this->redirect(route('administrator.blog.posts'), navigate: true);
    }

    public function confirmCancel()
    {
        return $this->redirect(route('administrator.blog.posts'), navigate: true);
    }

    // Computed properties
    public function getCanUpdateProperty()
    {
        return !$this->isProcessing &&
            !empty(trim($this->title)) &&
            !empty(trim($this->slug)) &&
            !empty(trim($this->content)) &&
            !empty($this->blog_category_id);
    }

    public function getProgressProperty()
    {
        $progress = 0;
        $fields = [
            'title' => 20,
            'content' => 25,
            'blog_category_id' => 15,
            'excerpt' => 10,
            'selectedTags' => 10,
            'featuredImage' => 10,
            'meta_description' => 10,
        ];

        foreach ($fields as $field => $weight) {
            if ($field === 'selectedTags' && !empty($this->selectedTags)) {
                $progress += $weight;
            } elseif ($field === 'featuredImage' && ($this->featuredImage || $this->currentFeaturedImage)) {
                $progress += $weight;
            } elseif ($field === 'blog_category_id' && !empty($this->blog_category_id)) {
                $progress += $weight;
            } elseif (is_string($this->$field) && !empty(trim($this->$field))) {
                $progress += $weight;
            }
        }

        return $progress;
    }

    public function getSelectedTagsDataProperty()
    {
        if (empty($this->selectedTags)) {
            return collect();
        }

        return BlogTag::whereIn('id', $this->selectedTags)->get();
    }

    public function getHasChangesIndicatorProperty()
    {
        return $this->hasChanges ? '●' : '';
    }

    // Data loading
    private function loadCategories()
    {
        $this->availableCategories = BlogCategory::where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    private function refreshAvailableTags()
    {
        $this->availableTags = BlogTag::where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    private function loadPostData()
    {
        // Ensure post is loaded with relationships
        $this->post->load(['tags', 'category', 'user']);

        // Load basic post data
        $this->title = $this->post->title ?? '';
        $this->slug = $this->post->slug ?? '';
        $this->excerpt = $this->post->excerpt ?? '';
        $this->content = $this->post->content ?? '';

        // Publishing options
        $this->status = $this->post->status ?? 'draft';
        $this->published_at = $this->post->published_at ?
            $this->post->published_at->format('Y-m-d H:i') : '';
        $this->scheduled_at = $this->post->scheduled_at ?
            $this->post->scheduled_at->format('Y-m-d H:i') : '';
        $this->is_featured = (bool) $this->post->is_featured;
        $this->is_sticky = (bool) $this->post->is_sticky;
        $this->allow_comments = (bool) $this->post->allow_comments;

        // Relationships
        $this->blog_category_id = $this->post->blog_category_id ?? '';
        $this->selectedTags = $this->post->tags ? $this->post->tags->pluck('id')->toArray() : [];

        // SEO fields
        $this->meta_title = $this->post->meta_title ?? '';
        $this->meta_description = $this->post->meta_description ?? '';
        $this->meta_keywords = $this->post->meta_keywords ?? '';

        // Admin fields
        $this->admin_notes = $this->post->admin_notes ?? '';

        Log::info('PostsEdit: Data loaded', [
            'post_id' => $this->post->id,
            'title' => $this->title,
            'status' => $this->status,
            'category_id' => $this->blog_category_id,
            'tags_count' => count($this->selectedTags),
        ]);
    }

    private function loadCurrentMedia()
    {
        try {
            // Load current featured image
            $featuredMedia = $this->post->getFirstMedia('featured_image');
            $this->currentFeaturedImage = $featuredMedia ? [
                'id' => $featuredMedia->id,
                'url' => $featuredMedia->getUrl(),
                'name' => $featuredMedia->name,
                'size' => $featuredMedia->human_readable_size,
            ] : null;

            // Load current gallery images
            $galleryMedia = $this->post->getMedia('gallery');
            $this->currentGalleryImages = $galleryMedia->map(function ($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                    'name' => $media->name,
                    'size' => $media->human_readable_size,
                ];
            })->toArray();

            // Load current social images
            $socialMedia = $this->post->getMedia('social_images');
            $this->currentSocialImages = $socialMedia->map(function ($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                    'name' => $media->name,
                    'size' => $media->human_readable_size,
                ];
            })->toArray();

            Log::info('PostsEdit: Media loaded', [
                'post_id' => $this->post->id,
                'featured_image' => $this->currentFeaturedImage ? 'Yes' : 'No',
                'gallery_count' => count($this->currentGalleryImages),
                'social_count' => count($this->currentSocialImages),
            ]);

        } catch (\Exception $e) {
            Log::error('PostsEdit: Failed to load media', [
                'post_id' => $this->post->id,
                'error' => $e->getMessage(),
            ]);

            // Set defaults if loading fails
            $this->currentFeaturedImage = null;
            $this->currentGalleryImages = [];
            $this->currentSocialImages = [];
        }
    }

    // Livewire events
    public function editorContentChanged($content)
    {
        $this->content = $content;
        $this->markAsChanged();
        $this->scheduleAutoSave();
    }

    // Delete post
    public function deletePost()
    {
        Log::info('=== PostsEdit: DELETE START ===', ['post_id' => $this->post->id]);

        try {
            DB::transaction(function () {
                // Update category post count
                if ($this->post->blog_category_id) {
                    BlogCategory::where('id', $this->post->blog_category_id)
                        ->decrement('posts_count');
                }

                // Update tags post count
                $tagIds = $this->post->tags->pluck('id')->toArray();
                if (!empty($tagIds)) {
                    BlogTag::whereIn('id', $tagIds)->decrement('posts_count');
                }

                // Clear all media
                $this->post->clearMediaCollection('featured_image');
                $this->post->clearMediaCollection('gallery');
                $this->post->clearMediaCollection('social_images');

                // Delete the post
                $this->post->delete();
            });

            session()->flash('success', "Post '{$this->post->title}' deleted successfully!");

            Log::info('=== PostsEdit: DELETE SUCCESS ===', ['post_id' => $this->post->id]);

            return $this->redirect(route('administrator.blog.posts'), navigate: true);

        } catch (\Exception $e) {
            Log::error('=== PostsEdit: DELETE FAILED ===', [
                'post_id' => $this->post->id,
                'error' => $e->getMessage(),
            ]);

            session()->flash('error', 'Failed to delete post: ' . $e->getMessage());
        }
    }

    // Component lifecycle
    public function mount(BlogPost $post)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->post = $post;

        // Check if user can edit this post
        // You might want to add authorization logic here
        // if (!auth()->user()->can('update', $post)) {
        //     abort(403);
        // }

        $this->loadCategories();
        $this->refreshAvailableTags();
        $this->loadPostData();

        // Auto-slug is usually disabled for editing
        $this->auto_slug = false;

        // Load current media after post data is loaded
        $this->loadCurrentMedia();

        // Reset change tracking after initial load
        $this->hasChanges = false;
    }

    public function hydrate()
    {
        // Ensure data is available after Livewire rehydration
        if ($this->post && empty($this->title)) {
            $this->loadPostData();
        }
    }

    public function render()
    {
        return view('livewire.backend.blog.posts-edit');
    }
}
