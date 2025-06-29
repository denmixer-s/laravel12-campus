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
#[Title('Create Blog Post')]

class PostsCreate extends Component
{
    use WithFileUploads;

    // Basic post properties
    public $title     = '';
    public $slug      = '';
    public $excerpt   = '';
    public $content   = '';
    public $auto_slug = true;

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
                Rule::unique('blog_posts', 'slug'),
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
            // Clear slug when switching to manual mode to force user input
            if ($this->auto_slug === false) {
                // Keep existing slug or clear it
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
    }

    public function updatedBlogCategoryId()
    {
        $this->validateOnly('blog_category_id');
    }

    public function updatedFeaturedImage()
    {
        $this->validateOnly('featuredImage');
    }

    // Toggle methods
    public function toggleFeatured()
    {
        $this->is_featured = !$this->is_featured;
        $this->scheduleAutoSave();
    }

    public function toggleSticky()
    {
        $this->is_sticky = !$this->is_sticky;
        $this->scheduleAutoSave();
    }

    public function toggleComments()
    {
        $this->allow_comments = !$this->allow_comments;
        $this->scheduleAutoSave();
    }

    public function toggleAutoSave()
    {
        $this->autoSaveEnabled = !$this->autoSaveEnabled;
    }

    // Tag management
    public function addTag()
    {
        if (empty(trim($this->newTag))) {
            return;
        }

        $tagName = trim($this->newTag);

        // Find or create tag
        $tag = BlogTag::firstOrCreate(
            ['slug' => Str::slug($tagName)],
            [
                'name' => $tagName,
                'slug' => Str::slug($tagName),
                'color' => '#' . substr(md5($tagName), 0, 6), // Generate color from tag name
            ]
        );

        // Add to selected tags if not already there
        if (!in_array($tag->id, $this->selectedTags)) {
            $this->selectedTags[] = $tag->id;
        }

        $this->newTag = '';
        $this->refreshAvailableTags();
        $this->scheduleAutoSave();
    }

    public function removeTag($tagId)
    {
        $this->selectedTags = array_filter($this->selectedTags, fn($id) => $id != $tagId);
        $this->scheduleAutoSave();
    }

    // Media management
    public function removeFeaturedImage()
    {
        $this->featuredImage = null;
        $this->resetValidation('featuredImage');
    }

    public function removeGalleryImage($index)
    {
        if (isset($this->galleryImages[$index])) {
            unset($this->galleryImages[$index]);
            $this->galleryImages = array_values($this->galleryImages);
            $this->resetValidation('galleryImages.*');
        }
    }

    public function removeSocialImage($index)
    {
        if (isset($this->socialImages[$index])) {
            unset($this->socialImages[$index]);
            $this->socialImages = array_values($this->socialImages);
            $this->resetValidation('socialImages.*');
        }
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

            // Create/update draft - simplified logic for auto-save
            $draftData = [
                'title' => $this->title ?: 'Untitled Draft',
                'slug' => $this->slug ?: Str::slug($this->title ?: 'untitled-draft-' . time()),
                'excerpt' => $this->excerpt,
                'content' => $this->content,
                'blog_category_id' => $this->blog_category_id ?: null,
                'status' => 'draft',
                'user_id' => auth()->id(),
                'is_featured' => $this->is_featured,
                'is_sticky' => $this->is_sticky,
                'allow_comments' => $this->allow_comments,
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'meta_keywords' => $this->meta_keywords,
                'admin_notes' => $this->admin_notes,
            ];

            // This would be enhanced to check for existing draft
            // For now, just log the auto-save attempt
            Log::info('Auto-save draft attempted', [
                'title' => $this->title,
                'content_length' => strlen($this->content),
            ]);

            $this->lastSavedAt = now();
            $this->dispatch('autoSaveSuccess');

        } catch (\Exception $e) {
            Log::error('Auto-save failed', ['error' => $e->getMessage()]);
            $this->dispatch('autoSaveFailed');
        } finally {
            $this->isDraftSaving = false;
        }
    }

    // Main create method
    public function create()
    {
        Log::info('=== PostsCreate: START ===');

        $this->isProcessing = true;

        try {
            // Validate all form data
            $this->validate();

            Log::info('PostsCreate: Validation passed', [
                'title' => $this->title,
                'slug' => $this->slug,
                'status' => $this->status,
                'category_id' => $this->blog_category_id,
                'tags_count' => count($this->selectedTags),
            ]);

            $post = null;

            // Create post and handle media in transaction
            DB::transaction(function () use (&$post) {
                // Prepare post data
                $postData = [
                    'title' => trim($this->title),
                    'slug' => trim($this->slug),
                    'excerpt' => $this->excerpt,
                    'content' => $this->content,
                    'blog_category_id' => $this->blog_category_id,
                    'user_id' => auth()->id(),
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
                }

                // Create the post
                $post = BlogPost::create($postData);

                Log::info('PostsCreate: Post created in database', [
                    'post_id' => $post->id,
                    'title' => $post->title,
                    'status' => $post->status,
                ]);

                // Attach tags
                if (!empty($this->selectedTags)) {
                    $post->tags()->attach($this->selectedTags);

                    // Update tags post count
                    BlogTag::whereIn('id', $this->selectedTags)
                        ->increment('posts_count');

                    Log::info('PostsCreate: Tags attached', [
                        'tags_count' => count($this->selectedTags),
                    ]);
                }

                // Update category post count
                BlogCategory::where('id', $this->blog_category_id)
                    ->increment('posts_count');

                // Handle featured image upload
                if ($this->featuredImage) {
                    $this->uploadFeaturedImage($post);
                }

                // Handle gallery images upload
                if (!empty($this->galleryImages)) {
                    $this->uploadGalleryImages($post);
                }

                // Handle social images upload
                if (!empty($this->socialImages)) {
                    $this->uploadSocialImages($post);
                }
            });

            // Success
            $this->showSuccessMessage = true;
            $this->successMessage = "Post '{$this->title}' created successfully!";
            session()->flash('success', "Post '{$this->title}' created successfully!");

            Log::info('=== PostsCreate: SUCCESS ===', [
                'post_id' => $post->id,
                'status' => $post->status,
            ]);

            return $this->redirect(route('administrator.blog.posts.index'), navigate: true);

        } catch (\Exception $e) {
            Log::error('=== PostsCreate: FAILED ===', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            session()->flash('error', 'Failed to create post: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    // Save as draft method
    public function saveDraft()
    {
        $originalStatus = $this->status;
        $this->status = 'draft';

        $result = $this->create();

        if (!$result) {
            $this->status = $originalStatus;
        }

        return $result;
    }

    // Media upload helpers
    private function uploadFeaturedImage($post)
    {
        try {
            Log::info('PostsCreate: Uploading featured image');

            $media = $post->addMedia($this->featuredImage->getRealPath())
                ->usingName($this->title . ' - Featured Image')
                ->usingFileName($this->featuredImage->getClientOriginalName())
                ->toMediaCollection('featured_image');

            Log::info('PostsCreate: Featured image uploaded successfully', [
                'media_id' => $media->id,
                'file_name' => $media->file_name,
            ]);

        } catch (\Exception $e) {
            Log::error('PostsCreate: Featured image upload failed', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    private function uploadGalleryImages($post)
    {
        try {
            Log::info('PostsCreate: Uploading gallery images', [
                'count' => count($this->galleryImages),
            ]);

            foreach ($this->galleryImages as $index => $image) {
                $media = $post->addMedia($image->getRealPath())
                    ->usingName($this->title . ' - Gallery Image ' . ($index + 1))
                    ->usingFileName($image->getClientOriginalName())
                    ->toMediaCollection('gallery');

                Log::info('PostsCreate: Gallery image uploaded', [
                    'index' => $index,
                    'media_id' => $media->id,
                ]);
            }

        } catch (\Exception $e) {
            Log::error('PostsCreate: Gallery images upload failed', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    private function uploadSocialImages($post)
    {
        try {
            foreach ($this->socialImages as $index => $image) {
                $post->addMedia($image->getRealPath())
                    ->usingName($this->title . ' - Social Image ' . ($index + 1))
                    ->usingFileName($image->getClientOriginalName())
                    ->toMediaCollection('social_images');
            }
        } catch (\Exception $e) {
            Log::error('PostsCreate: Social images upload failed', [
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
        $this->title = '';
        $this->slug = '';
        $this->excerpt = '';
        $this->content = '';
        $this->auto_slug = true;
        $this->status = 'draft';
        $this->blog_category_id = '';
        $this->selectedTags = [];
        $this->meta_title = '';
        $this->meta_description = '';
        $this->meta_keywords = '';
        $this->featuredImage = null;
        $this->galleryImages = [];
        $this->socialImages = [];
        $this->is_featured = false;
        $this->is_sticky = false;
        $this->allow_comments = true;
        $this->admin_notes = '';
        $this->published_at = '';
        $this->scheduled_at = '';
        $this->resetValidation();

        $this->dispatch('resetEditor');
    }

    public function cancel()
    {
        return $this->redirect(route('administrator.blog.posts.index'), navigate: true);
    }

    // Computed properties
    public function getCanCreateProperty()
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
            } elseif ($field === 'featuredImage' && $this->featuredImage) {
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
        return BlogTag::whereIn('id', $this->selectedTags)->get();
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

    // Livewire events
    public function editorContentChanged($content)
    {
        $this->content = $content;
        $this->scheduleAutoSave();
    }

    // Component lifecycle
    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->loadCategories();
        $this->refreshAvailableTags();

        // Set default published time
        $this->published_at = now()->format('Y-m-d H:i');
    }

    public function render()
    {
        return view('livewire.backend.blog.posts-create');
    }
}
