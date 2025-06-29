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

#[Layout('components.layouts.dashboard')]
#[Title('Create Slider')]
class CreateSlider extends Component
{
    use WithFileUploads;

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
            'sliderImage' => 'required|image|max:10240',
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
        'sliderImage.required' => 'Please upload a slider image.',
        'sliderImage.image' => 'The uploaded file must be an image.',
        'sliderImage.max' => 'The image size cannot exceed 10MB.',
    ];

    // Custom link validation method
    public function isValidLink($link)
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

    // CREATE METHOD - Using Media Library with fallback
    public function create()
    {
        Log::info('=== CreateSlider: MEDIA LIBRARY VERSION START ===');
        
        $this->isProcessing = true;
        
        try {
            // Validate
            $this->validate();
            
            Log::info('CreateSlider: Validation passed', [
                'heading' => $this->heading,
                'has_image' => $this->sliderImage ? 'yes' : 'no'
            ]);
            
            $slider = null;
            
            // Create slider and handle media in transaction
            DB::transaction(function () use (&$slider) {
                // Create the slider first
                $slider = Slider::create([
                    'heading' => trim($this->heading),
                    'description' => trim($this->description) ?: null,
                    'link' => trim($this->link),
                    'show' => $this->show,
                    'user_id' => auth()->id(),
                ]);
                
                Log::info('CreateSlider: Slider created in database', [
                    'slider_id' => $slider->id
                ]);
                
                // Handle media upload
                if ($this->sliderImage) {
                    try {
                        Log::info('CreateSlider: Starting media upload', [
                            'original_name' => $this->sliderImage->getClientOriginalName(),
                            'size' => $this->sliderImage->getSize(),
                            'mime_type' => $this->sliderImage->getMimeType()
                        ]);
                        
                        // Try Media Library first
                        $media = $slider->addMedia($this->sliderImage->getRealPath())
                            ->usingName($this->heading)
                            ->usingFileName($this->sliderImage->getClientOriginalName())
                            ->toMediaCollection('slider_images');
                        
                        Log::info('CreateSlider: Media uploaded successfully via Media Library', [
                            'media_id' => $media->id,
                            'file_name' => $media->file_name,
                            'collection' => $media->collection_name,
                            'url' => $media->getUrl()
                        ]);
                        
                    } catch (\Exception $mediaError) {
                        Log::error('CreateSlider: Media upload failed, trying fallback', [
                            'error' => $mediaError->getMessage()
                        ]);
                        
                        // Fallback: Store using simple Laravel storage
                        $imagePath = $this->sliderImage->store('sliders', 'public');
                        $slider->update(['image_path' => $imagePath]);
                        
                        Log::info('CreateSlider: Fallback storage successful', [
                            'image_path' => $imagePath
                        ]);
                    }
                }
            });
            
            // Success
            $this->showSuccessMessage = true;
            $this->successMessage = "Slider '{$this->heading}' created successfully!";
            session()->flash('success', "Slider '{$this->heading}' created successfully!");
            
            Log::info('=== CreateSlider: MEDIA LIBRARY VERSION SUCCESS ===', [
                'slider_id' => $slider->id,
                'has_media' => $slider->hasMedia('slider_images'),
                'has_image_path' => !empty($slider->image_path)
            ]);
            
            return $this->redirect(route('administrator.sliders.index'), navigate: true);
            
        } catch (\Exception $e) {
            Log::error('=== CreateSlider: MEDIA LIBRARY VERSION FAILED ===', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Failed to create slider: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    // Form management
    public function resetForm()
    {
        $this->heading = '';
        $this->description = '';
        $this->link = '';
        $this->show = 'frontend';
        $this->sliderImage = null;
        $this->showSuccessMessage = false;
        $this->successMessage = '';
        $this->resetValidation();
    }

    public function cancel()
    {
        return $this->redirect(route('administrator.sliders.index'), navigate: true);
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
    public function getCanCreateProperty()
    {
        return !$this->isProcessing && 
               !empty(trim($this->heading)) && 
               !empty(trim($this->link)) && 
               $this->isValidLink(trim($this->link)) &&
               !empty($this->show) && 
               $this->sliderImage;
    }

    // Image preview
    public function getImagePreviewProperty()
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
        return view('livewire.sakon.sliders.create-slider');
    }
}