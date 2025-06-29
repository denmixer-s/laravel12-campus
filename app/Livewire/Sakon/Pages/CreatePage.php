<?php

namespace App\Livewire\Sakon\Pages;

use App\Models\Page;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

#[Layout('components.layouts.dashboard')]
#[Title('Create Page')]
class CreatePage extends Component
{
    use WithFileUploads;

    // Form properties
    public $title = '';
    public $slug = '';
    public $content = '';
    public $auto_slug = true;

    // Media properties
    public $featuredImage;
    public $galleryImages = [];

    // UI state
    public $isProcessing = false;
    public $showSuccessMessage = false;
    public $successMessage = '';

    // WYSIWYG editor state
    public $editorReady = false;

    // Validation rules
    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('pages', 'slug'),
            ],
            'content' => 'nullable|string',
            'featuredImage' => 'nullable|image|max:10240', // 10MB
            'galleryImages.*' => 'nullable|image|max:10240',
        ];
    }

    protected $messages = [
        'title.required' => 'Page title is required.',
        'title.max' => 'Title cannot exceed 255 characters.',
        'slug.required' => 'Page slug is required.',
        'slug.max' => 'Slug cannot exceed 255 characters.',
        'slug.regex' => 'Slug must contain only lowercase letters, numbers, and hyphens.',
        'slug.unique' => 'This slug is already taken. Please choose another.',
        'featuredImage.image' => 'Featured image must be a valid image file.',
        'featuredImage.max' => 'Featured image cannot exceed 10MB.',
        'galleryImages.*.image' => 'All gallery images must be valid image files.',
        'galleryImages.*.max' => 'Gallery images cannot exceed 10MB each.',
    ];

    // Real-time validation
    public function updatedTitle()
    {
        $this->validateOnly('title');

        if ($this->auto_slug) {
            $this->slug = Str::slug($this->title);
            $this->validateOnly('slug');
        }
    }

    public function updatedSlug()
    {
        $this->slug = Str::slug($this->slug);
        $this->validateOnly('slug');
        $this->auto_slug = false;
    }

    public function updatedContent()
    {
        // Content validation is optional, so we don't need to validate here
        // unless you want to add specific content rules
    }

    public function updatedFeaturedImage()
    {
        $this->validateOnly('featuredImage');
    }

    public function updatedGalleryImages()
    {
        $this->validateOnly('galleryImages.*');
    }

    // Toggle auto-slug generation
    public function toggleAutoSlug()
    {
        $this->auto_slug = !$this->auto_slug;

        if ($this->auto_slug && $this->title) {
            $this->slug = Str::slug($this->title);
            $this->validateOnly('slug');
        }
    }

    // Remove featured image
    public function removeFeaturedImage()
    {
        $this->featuredImage = null;
        $this->resetValidation('featuredImage');
    }

    // Remove gallery image by index
    public function removeGalleryImage($index)
    {
        if (isset($this->galleryImages[$index])) {
            unset($this->galleryImages[$index]);
            $this->galleryImages = array_values($this->galleryImages); // Re-index array
            $this->resetValidation('galleryImages.*');
        }
    }

    // Create page method
    public function create()
    {
        Log::info('=== CreatePage: START ===');

        $this->isProcessing = true;

        try {
            // Validate all form data
            $this->validate();

            Log::info('CreatePage: Validation passed', [
                'title' => $this->title,
                'slug' => $this->slug,
                'has_featured_image' => $this->featuredImage ? 'yes' : 'no',
                'gallery_images_count' => count($this->galleryImages),
            ]);

            $page = null;

            // Create page and handle media in transaction
            DB::transaction(function () use (&$page) {
                // Create the page
                $page = Page::create([
                    'title' => trim($this->title),
                    'slug' => trim($this->slug),
                    'content' => $this->content,
                    'user_id' => auth()->id(),
                ]);

                Log::info('CreatePage: Page created in database', [
                    'page_id' => $page->id,
                    'title' => $page->title,
                    'slug' => $page->slug,
                ]);

                // Handle featured image upload
                if ($this->featuredImage) {
                    try {
                        Log::info('CreatePage: Uploading featured image');

                        $media = $page->addMedia($this->featuredImage->getRealPath())
                            ->usingName($this->title . ' - Featured Image')
                            ->usingFileName($this->featuredImage->getClientOriginalName())
                            ->toMediaCollection('featured_image');

                        Log::info('CreatePage: Featured image uploaded successfully', [
                            'media_id' => $media->id,
                            'file_name' => $media->file_name,
                        ]);

                    } catch (\Exception $e) {
                        Log::error('CreatePage: Featured image upload failed', [
                            'error' => $e->getMessage()
                        ]);
                        throw $e;
                    }
                }

                // Handle gallery images upload
                if (!empty($this->galleryImages)) {
                    try {
                        Log::info('CreatePage: Uploading gallery images', [
                            'count' => count($this->galleryImages)
                        ]);

                        foreach ($this->galleryImages as $index => $image) {
                            $media = $page->addMedia($image->getRealPath())
                                ->usingName($this->title . ' - Gallery Image ' . ($index + 1))
                                ->usingFileName($image->getClientOriginalName())
                                ->toMediaCollection('gallery_images');

                            Log::info('CreatePage: Gallery image uploaded', [
                                'index' => $index,
                                'media_id' => $media->id,
                                'file_name' => $media->file_name,
                            ]);
                        }

                    } catch (\Exception $e) {
                        Log::error('CreatePage: Gallery images upload failed', [
                            'error' => $e->getMessage()
                        ]);
                        throw $e;
                    }
                }
            });

            // Success
            $this->showSuccessMessage = true;
            $this->successMessage = "Page '{$this->title}' created successfully!";
            session()->flash('success', "Page '{$this->title}' created successfully!");

            Log::info('=== CreatePage: SUCCESS ===', [
                'page_id' => $page->id,
                'images_summary' => $page->images_summary,
            ]);

            return $this->redirect(route('administrator.pages.index'), navigate: true);

        } catch (\Exception $e) {
            Log::error('=== CreatePage: FAILED ===', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            session()->flash('error', 'Failed to create page: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    // Form management
    public function resetForm()
    {
        $this->title = '';
        $this->slug = '';
        $this->content = '';
        $this->auto_slug = true;
        $this->featuredImage = null;
        $this->galleryImages = [];
        $this->showSuccessMessage = false;
        $this->successMessage = '';
        $this->resetValidation();

        // Reset WYSIWYG editor
        $this->dispatch('resetEditor');
    }

    public function cancel()
    {
        return $this->redirect(route('administrator.pages.index'), navigate: true);
    }

    // Get computed properties
    public function getCanCreateProperty()
    {
        return !$this->isProcessing &&
               !empty(trim($this->title)) &&
               !empty(trim($this->slug)) &&
               $this->slug === Str::slug($this->slug);
    }

    // Image preview methods
    public function getFeaturedImagePreviewProperty()
    {
        if ($this->featuredImage && method_exists($this->featuredImage, 'temporaryUrl')) {
            try {
                return $this->featuredImage->temporaryUrl();
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    public function getGalleryImagePreviewsProperty()
    {
        $previews = [];
        foreach ($this->galleryImages as $index => $image) {
            if ($image && method_exists($image, 'temporaryUrl')) {
                try {
                    $previews[$index] = $image->temporaryUrl();
                } catch (\Exception $e) {
                    $previews[$index] = null;
                }
            }
        }
        return $previews;
    }

    // Get total images count
    public function getTotalImagesCountProperty()
    {
        $count = 0;
        if ($this->featuredImage) $count++;
        $count += count($this->galleryImages);
        return $count;
    }

    // Progress calculation
    public function getProgressProperty()
    {
        $progress = 0;

        // Title (30%)
        if (!empty(trim($this->title))) $progress += 30;

        // Slug (20%)
        if (!empty(trim($this->slug)) && $this->slug === Str::slug($this->slug)) $progress += 20;

        // Content (30%)
        if (!empty(trim($this->content))) $progress += 30;

        // Images (20%)
        if ($this->totalImagesCount > 0) $progress += 20;

        return $progress;
    }

    // Livewire events for WYSIWYG editor
    public function editorContentChanged($content)
    {
        $this->content = $content;
    }

    // Static method to handle AJAX image uploads for TinyMCE
    public static function handleEditorImageUpload($request)
    {
        try {
            if (!$request->hasFile('image')) {
                return response()->json([
                    'success' => false,
                    'error' => 'No image file provided'
                ], 400);
            }

            $file = $request->file('image');

            // Validate file
            if ($file->getSize() > 10 * 1024 * 1024) {
                return response()->json([
                    'success' => false,
                    'error' => 'Image too large. Maximum size is 10MB.'
                ], 400);
            }

            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file->getMimeType(), $allowedTypes)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid image type. Only JPEG, PNG, GIF, and WebP are allowed.'
                ], 400);
            }

            // Generate filename
            $extension = $file->getClientOriginalExtension();
            $filename = 'editor-image-' . time() . '-' . uniqid() . '.' . $extension;

            // Store in temp directory
            $path = $file->storeAs('temp/editor-images', $filename, 'public');

            if (!$path) {
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to store image'
                ], 500);
            }

            $url = asset('storage/' . $path);

            Log::info('Editor image uploaded via AJAX', [
                'filename' => $filename,
                'url' => $url
            ]);

            return response()->json([
                'success' => true,
                'url' => $url,
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName()
            ]);

        } catch (\Exception $e) {
            Log::error('AJAX editor image upload failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    // Clean up old temporary images
    private function cleanupOldTempImages()
    {
        try {
            $tempDir = storage_path('app/public/temp/editor-images');

            if (!is_dir($tempDir)) {
                return;
            }

            $files = glob($tempDir . '/*');
            $cutoff = now()->subHours(24)->timestamp;

            foreach ($files as $file) {
                if (is_file($file) && filemtime($file) < $cutoff) {
                    unlink($file);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error cleaning up temp images', ['error' => $e->getMessage()]);
        }
    }

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    public function render()
    {
        return view('livewire.sakon.pages.create-page');
    }
}
