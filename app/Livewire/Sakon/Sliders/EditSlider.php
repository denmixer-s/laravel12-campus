<?php

namespace App\Livewire\Sakon\Sliders;

use App\Models\Slider;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Layout('components.layouts.dashboard')]
#[Title('Edit Slider')]
class EditSlider extends Component
{
    use WithFileUploads, AuthorizesRequests;

    public Slider $slider;

    // Form properties
    public $heading = '';
    public $description = '';
    public $link = '';
    public $show = 'frontend';
    public $sliderImage;

    // UI state
    public $isProcessing = false;
    public $showSuccessMessage = false;
    public $successMessage = '';
    public $showImagePreview = false;
    public $currentImageUrl = '';

    // Validation rules
    protected function rules()
    {
        return [
            'heading' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'link' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    // Allow URLs, paths, and anchors
                    if (!$this->isValidLink($value)) {
                        $fail('Please enter a valid URL (https://example.com), path (/about), or anchor (#section).');
                    }
                },
            ],
            'show' => 'required|in:home,frontend,both',
            'sliderImage' => 'nullable|image|max:10240',
        ];
    }

    protected $messages = [
        'heading.required' => 'Slider heading is required.',
        'heading.max' => 'Heading cannot exceed 255 characters.',
        'description.max' => 'Description cannot exceed 1000 characters.',
        'link.required' => 'Link is required.',
        'link.max' => 'Link cannot exceed 255 characters.',
        'show.required' => 'Please select where to show this slider.',
        'show.in' => 'Invalid show location selected.',
        'sliderImage.image' => 'The uploaded file must be an image.',
        'sliderImage.max' => 'The image size cannot exceed 10MB.',
    ];

    // Custom link validation method
    private function isValidLink($link)
    {
        $link = trim($link);
        
        // Check if it's a valid URL
        if (filter_var($link, FILTER_VALIDATE_URL)) {
            return true;
        }
        
        // Check if it's a valid path (starts with /)
        if (preg_match('/^\/[a-zA-Z0-9\/_\-\.\?\=\&]*$/', $link)) {
            return true;
        }
        
        // Check if it's a valid anchor (starts with #)
        if (preg_match('/^#[a-zA-Z0-9_\-]*$/', $link)) {
            return true;
        }
        
        // Check if it's a valid relative path (no protocol, starts with letter or .)
        if (preg_match('/^[a-zA-Z0-9\.][\w\/_\-\.\?\=\&]*$/', $link)) {
            return true;
        }
        
        return false;
    }

    public function mount(Slider $slider)
    {
        $this->authorize('update', $slider);
        
        $this->slider = $slider;
        $this->heading = $slider->heading;
        $this->description = $slider->description ?? '';
        $this->link = $slider->link;
        $this->show = $slider->show;
        
        // Set current image URL
        if ($slider->hasSliderImage()) {
            $this->currentImageUrl = $slider->getSliderImageUrl();
            $this->showImagePreview = true;
        }
        
        Log::info('EditSlider component mounted', [
            'slider_id' => $slider->id,
            'has_current_image' => $slider->hasSliderImage()
        ]);
    }

    // Real-time validation
    public function updatedHeading()
    {
        $this->validateOnly('heading');
    }

    public function updatedDescription()
    {
        $this->validateOnly('description');
    }

    public function updatedLink()
    {
        $this->validateOnly('link');
    }

    public function updatedShow()
    {
        $this->validateOnly('show');
    }

    public function updatedSliderImage()
    {
        $this->validateOnly('sliderImage');
    }

    // UPDATE METHOD
    public function update()
    {
        Log::info('=== EditSlider: UPDATE START ===', ['slider_id' => $this->slider->id]);
        
        $this->isProcessing = true;
        
        try {
            // Validate
            $this->validate();
            
            Log::info('EditSlider: Validation passed', [
                'slider_id' => $this->slider->id,
                'has_new_image' => $this->sliderImage ? 'yes' : 'no'
            ]);
            
            // Update slider and handle media in transaction
            DB::transaction(function () {
                // Update the slider data
                $this->slider->update([
                    'heading' => trim($this->heading),
                    'description' => trim($this->description) ?: null,
                    'link' => trim($this->link),
                    'show' => $this->show,
                ]);
                
                Log::info('EditSlider: Slider data updated', [
                    'slider_id' => $this->slider->id
                ]);
                
                // Handle new image upload if provided
                if ($this->sliderImage) {
                    try {
                        Log::info('EditSlider: Processing new image upload', [
                            'original_name' => $this->sliderImage->getClientOriginalName(),
                            'size' => $this->sliderImage->getSize(),
                            'mime_type' => $this->sliderImage->getMimeType()
                        ]);
                        
                        // Clear existing media first
                        $this->slider->clearMediaCollection('slider_images');
                        
                        // Clear image_path if it exists
                        if (!empty($this->slider->image_path)) {
                            Storage::disk('public')->delete($this->slider->image_path);
                            $this->slider->update(['image_path' => null]);
                        }
                        
                        // Add new media
                        $media = $this->slider->addMedia($this->sliderImage->getRealPath())
                            ->usingName($this->heading)
                            ->usingFileName($this->sliderImage->getClientOriginalName())
                            ->toMediaCollection('slider_images');
                        
                        Log::info('EditSlider: New media uploaded successfully', [
                            'media_id' => $media->id,
                            'file_name' => $media->file_name,
                            'collection' => $media->collection_name
                        ]);
                        
                        // Update current image URL for display
                        $this->currentImageUrl = $media->getUrl();
                        
                    } catch (\Exception $mediaError) {
                        Log::error('EditSlider: Media upload failed, trying fallback', [
                            'error' => $mediaError->getMessage()
                        ]);
                        
                        // Fallback: Store using simple Laravel storage
                        $imagePath = $this->sliderImage->store('sliders', 'public');
                        $this->slider->update(['image_path' => $imagePath]);
                        $this->currentImageUrl = Storage::url($imagePath);
                        
                        Log::info('EditSlider: Fallback storage successful', [
                            'image_path' => $imagePath
                        ]);
                    }
                }
            });
            
            // Clear the new image upload field
            $this->sliderImage = null;
            
            // Success message
            session()->flash('success', "Slider '{$this->heading}' updated successfully!");
            
            Log::info('=== EditSlider: UPDATE SUCCESS ===', [
                'slider_id' => $this->slider->id
            ]);
            
            // Refresh the slider model
            $this->slider->refresh();
            
            // Redirect to index
            return $this->redirect(route('administrator.sliders.index'), navigate: true);
            
        } catch (\Exception $e) {
            Log::error('=== EditSlider: UPDATE FAILED ===', [
                'slider_id' => $this->slider->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Failed to update slider: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    // Remove current image
    public function removeCurrentImage()
    {
        try {
            DB::transaction(function () {
                // Clear media library images
                $this->slider->clearMediaCollection('slider_images');
                
                // Clear image_path if it exists
                if (!empty($this->slider->image_path)) {
                    Storage::disk('public')->delete($this->slider->image_path);
                    $this->slider->update(['image_path' => null]);
                }
                
                Log::info('EditSlider: Current image removed', [
                    'slider_id' => $this->slider->id
                ]);
            });
            
            $this->currentImageUrl = '';
            $this->showImagePreview = false;
            session()->flash('success', 'Image removed successfully.');
            
        } catch (\Exception $e) {
            Log::error('EditSlider: Failed to remove image', [
                'slider_id' => $this->slider->id,
                'error' => $e->getMessage()
            ]);
            session()->flash('error', 'Failed to remove image: ' . $e->getMessage());
        }
    }

    // Navigation methods
    public function cancel()
    {
        return $this->redirect(route('administrator.sliders.index'), navigate: true);
    }

    public function viewSlider()
    {
        return $this->redirect(route('administrator.sliders.show', $this->slider), navigate: true);
    }

    // Get show options
    public function getShowOptionsProperty()
    {
        return [
            'home' => 'Home Page Only',
            'frontend' => 'Frontend Only',
            'both' => 'Both Home & Frontend',
        ];
    }

    // Get computed properties
    public function getCanUpdateProperty()
    {
        return !$this->isProcessing && 
               !empty(trim($this->heading)) && 
               !empty(trim($this->link)) && 
               $this->isValidLink(trim($this->link)) &&
               !empty($this->show);
    }

    // Check if data has changed
    public function getHasChangesProperty()
    {
        return $this->heading !== $this->slider->heading ||
               $this->description !== ($this->slider->description ?? '') ||
               $this->link !== $this->slider->link ||
               $this->show !== $this->slider->show ||
               $this->sliderImage !== null;
    }

    // Image preview
    public function getNewImagePreviewProperty()
    {
        if ($this->sliderImage && method_exists($this->sliderImage, 'temporaryUrl')) {
            try {
                return $this->sliderImage->temporaryUrl();
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    // Link preview
    public function getLinkPreviewProperty()
    {
        if (!$this->link) {
            return null;
        }
        
        $link = trim($this->link);
        
        // Handle different link types
        if (filter_var($link, FILTER_VALIDATE_URL)) {
            // Full URL - extract domain
            $domain = parse_url($link, PHP_URL_HOST);
            return $domain ? str_replace('www.', '', $domain) : $link;
        } elseif (str_starts_with($link, '/')) {
            // Path - show as site path
            return 'Site path: ' . $link;
        } elseif (str_starts_with($link, '#')) {
            // Anchor - show as page anchor
            return 'Page anchor: ' . $link;
        } else {
            // Relative path
            return 'Relative: ' . $link;
        }
    }

    public function render()
    {
        return view('livewire.sakon.sliders.edit-slider');
    }
}