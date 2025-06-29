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
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Layout('components.layouts.dashboard')]
#[Title('Edit Page')]
class EditPage extends Component
{
    use WithFileUploads;
    // Temporarily removed AuthorizesRequests for debugging

    // Page model instance
    public Page $page;

    // Form properties
    public $title = '';
    public $slug = '';
    public $content = '';
    public $auto_slug = false;

    // Media properties
    public $featuredImage;
    public $galleryImages = [];

    // Existing media management
    public $existingFeaturedImage;
    public $existingGalleryImages = [];
    public $removedGalleryImages = [];
    public $shouldRemoveFeaturedImage = false;

    // UI state
    public $isProcessing = false;
    public $showSuccessMessage = false;
    public $successMessage = '';

    // WYSIWYG editor state
    public $editorReady = false;

    // Gallery image selection for content insertion
    public $showGallerySelector = false;
    public $selectedGalleryImageForContent = null;

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
                Rule::unique('pages', 'slug')->ignore($this->page->id),
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

    public function mount(Page $page)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Check if page exists
        if (!$page->exists) {
            session()->flash('error', 'Page not found.');
            return redirect()->route('administrator.pages.index');
        }

        // Temporarily disable authorization for debugging
        // TODO: Re-enable authorization after debugging
        try {
            $this->authorize('update', $page);
        } catch (\Exception $e) {
            session()->flash('error', 'You do not have permission to edit this page.');
            return redirect()->route('administrator.pages.index');
        }

        // Load the page with relationships
        $this->page = $page->load('user', 'media');
        $this->title = $page->title ?? '';
        $this->slug = $page->slug ?? '';
        $this->content = $page->content ?? '';

        Log::info('EditPage mounted successfully', [
            'page_id' => $page->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'user_id' => $page->user_id ?? 'null',
            'content_length' => strlen($this->content),
        ]);

        // Load existing media
        $this->loadExistingMedia();
    }

    protected function loadExistingMedia()
    {
        // Load existing featured image
        $this->existingFeaturedImage = $this->page->getFirstMedia('featured_image');

        // Load existing gallery images
        $this->existingGalleryImages = $this->page->getMedia('gallery_images')->map(function ($media) {
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
        // Content validation is optional
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

    // Featured image management
    public function removeFeaturedImage()
    {
        $this->featuredImage = null;
        $this->shouldRemoveFeaturedImage = true;
        $this->resetValidation('featuredImage');
    }

    public function keepExistingFeaturedImage()
    {
        $this->shouldRemoveFeaturedImage = false;
        $this->featuredImage = null;
    }

    // Gallery images management
    public function removeGalleryImage($index)
    {
        if (isset($this->galleryImages[$index])) {
            unset($this->galleryImages[$index]);
            $this->galleryImages = array_values($this->galleryImages); // Re-index array
            $this->resetValidation('galleryImages.*');
        }
    }

    public function removeExistingGalleryImage($mediaId)
    {
        // Add to removal list
        if (!in_array($mediaId, $this->removedGalleryImages)) {
            $this->removedGalleryImages[] = $mediaId;
        }

        // Remove from existing gallery images array
        $this->existingGalleryImages = array_filter($this->existingGalleryImages, function ($image) use ($mediaId) {
            return $image['id'] !== $mediaId;
        });

        // Re-index the array
        $this->existingGalleryImages = array_values($this->existingGalleryImages);
    }

    public function restoreExistingGalleryImage($mediaId)
    {
        // Remove from removal list
        $this->removedGalleryImages = array_filter($this->removedGalleryImages, function ($id) use ($mediaId) {
            return $id !== $mediaId;
        });

        // Reload existing media to restore the image
        $this->loadExistingMedia();
    }

    // Gallery image URL insertion for content
    public function openGallerySelector()
    {
        $this->showGallerySelector = true;
    }

    public function closeGallerySelector()
    {
        $this->showGallerySelector = false;
        $this->selectedGalleryImageForContent = null;
    }

    public function selectGalleryImageForContent($imageData)
    {
        $this->selectedGalleryImageForContent = $imageData;
    }

    public function insertGalleryImageToContent($size = 'medium')
    {
        if (!$this->selectedGalleryImageForContent) {
            return;
        }

        $imageData = $this->selectedGalleryImageForContent;
        $imageUrl = $imageData['urls'][$size] ?? $imageData['urls']['original'];
        $altText = $imageData['name'] ?? 'Gallery Image';

        // Create HTML for the image
        $imageHtml = sprintf(
            '<img src="%s" alt="%s" style="max-width: 100%%; height: auto;" class="gallery-image">',
            $imageUrl,
            htmlspecialchars($altText)
        );

        // Dispatch event to insert into TinyMCE
        $this->dispatch('insertImageIntoEditor', $imageHtml);

        // Close the selector
        $this->closeGallerySelector();

        // Show success message
        session()->flash('info', 'Gallery image inserted into content successfully!');
    }

    public function getGalleryImageUrl($imageData, $size = 'medium')
    {
        return $imageData['urls'][$size] ?? $imageData['urls']['original'];
    }

    // Update page method
    public function update()
    {
        Log::info('=== EditPage: START ===', ['page_id' => $this->page->id]);

        $this->isProcessing = true;

        try {
            // Validate all form data
            $this->validate();

            Log::info('EditPage: Validation passed', [
                'page_id' => $this->page->id,
                'title' => $this->title,
                'slug' => $this->slug,
                'has_new_featured_image' => $this->featuredImage ? 'yes' : 'no',
                'should_remove_featured' => $this->shouldRemoveFeaturedImage ? 'yes' : 'no',
                'new_gallery_images_count' => count($this->galleryImages),
                'removed_gallery_images_count' => count($this->removedGalleryImages),
            ]);

            // Update page and handle media in transaction
            DB::transaction(function () {
                // Update the page basic info
                $this->page->update([
                    'title' => trim($this->title),
                    'slug' => trim($this->slug),
                    'content' => $this->content,
                ]);

                Log::info('EditPage: Page updated in database', [
                    'page_id' => $this->page->id,
                    'title' => $this->page->title,
                    'slug' => $this->page->slug,
                ]);

                // Handle featured image
                if ($this->shouldRemoveFeaturedImage) {
                    $this->page->clearMediaCollection('featured_image');
                    Log::info('EditPage: Existing featured image removed');
                } elseif ($this->featuredImage) {
                    // Remove existing and add new
                    $this->page->clearMediaCollection('featured_image');

                    $media = $this->page->addMedia($this->featuredImage->getRealPath())
                        ->usingName($this->title . ' - Featured Image')
                        ->usingFileName($this->featuredImage->getClientOriginalName())
                        ->toMediaCollection('featured_image');

                    Log::info('EditPage: New featured image uploaded', [
                        'media_id' => $media->id,
                        'file_name' => $media->file_name,
                    ]);
                }

                // Remove gallery images marked for removal
                foreach ($this->removedGalleryImages as $mediaId) {
                    $media = $this->page->getMedia('gallery_images')->where('id', $mediaId)->first();
                    if ($media) {
                        $media->delete();
                        Log::info('EditPage: Gallery image removed', ['media_id' => $mediaId]);
                    }
                }

                // Add new gallery images
                if (!empty($this->galleryImages)) {
                    Log::info('EditPage: Adding new gallery images', [
                        'count' => count($this->galleryImages)
                    ]);

                    foreach ($this->galleryImages as $index => $image) {
                        $media = $this->page->addMedia($image->getRealPath())
                            ->usingName($this->title . ' - Gallery Image ' . ($index + 1))
                            ->usingFileName($image->getClientOriginalName())
                            ->toMediaCollection('gallery_images');

                        Log::info('EditPage: Gallery image uploaded', [
                            'index' => $index,
                            'media_id' => $media->id,
                            'file_name' => $media->file_name,
                        ]);
                    }
                }
            });

            // Reload the page to get fresh data
            $this->page->refresh();
            $this->loadExistingMedia();

            // Reset form state
            $this->featuredImage = null;
            $this->galleryImages = [];
            $this->removedGalleryImages = [];
            $this->shouldRemoveFeaturedImage = false;

            // Success
            $this->showSuccessMessage = true;
            $this->successMessage = "Page '{$this->title}' updated successfully!";
            session()->flash('success', "Page '{$this->title}' updated successfully!");

            Log::info('=== EditPage: SUCCESS ===', [
                'page_id' => $this->page->id,
                'images_summary' => $this->page->images_summary,
            ]);

            // Dispatch update event
            $this->dispatch('pageUpdated');

        } catch (\Exception $e) {
            Log::error('=== EditPage: FAILED ===', [
                'page_id' => $this->page->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            session()->flash('error', 'Failed to update page: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    // Form management
    public function resetChanges()
    {
        // Reset to original values
        $this->title = $this->page->title;
        $this->slug = $this->page->slug;
        $this->content = $this->page->content ?? '';
        $this->auto_slug = false;

        // Reset media changes
        $this->featuredImage = null;
        $this->galleryImages = [];
        $this->removedGalleryImages = [];
        $this->shouldRemoveFeaturedImage = false;

        // Reset UI state
        $this->showSuccessMessage = false;
        $this->successMessage = '';
        $this->showGallerySelector = false;
        $this->selectedGalleryImageForContent = null;

        // Reload existing media
        $this->loadExistingMedia();

        $this->resetValidation();

        // Reset WYSIWYG editor
        $this->dispatch('resetEditor', $this->content);
    }

    public function cancel()
    {
        return $this->redirect(route('administrator.pages.index'), navigate: true);
    }

    // Get computed properties
    public function getCanUpdateProperty()
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

    // Get total images count (existing + new)
    public function getTotalImagesCountProperty()
    {
        $count = 0;

        // Count existing featured image (if not marked for removal)
        if ($this->existingFeaturedImage && !$this->shouldRemoveFeaturedImage) {
            $count++;
        }

        // Count new featured image
        if ($this->featuredImage) {
            $count++;
        }

        // Count existing gallery images (minus removed ones)
        $existingGalleryCount = count($this->existingGalleryImages) - count($this->removedGalleryImages);
        $count += $existingGalleryCount;

        // Count new gallery images
        $count += count($this->galleryImages);

        return $count;
    }

    // Get all available gallery images for content insertion
    public function getAvailableGalleryImagesProperty()
    {
        return $this->existingGalleryImages;
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

    // Check if form has changes
    public function getHasChangesProperty()
    {
        return $this->title !== $this->page->title ||
               $this->slug !== $this->page->slug ||
               $this->content !== ($this->page->content ?? '') ||
               $this->featuredImage !== null ||
               !empty($this->galleryImages) ||
               !empty($this->removedGalleryImages) ||
               $this->shouldRemoveFeaturedImage;
    }

    // Livewire events for WYSIWYG editor
    public function editorContentChanged($content)
    {
        $this->content = $content;
    }

    public function render()
    {
        return view('livewire.sakon.pages.edit-page');
    }
}
