<?php

namespace App\Livewire\Sakon\Sliders;

use App\Models\Slider;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Layout('components.layouts.dashboard')]
#[Title('View Slider')]
class ShowSlider extends Component
{
    use AuthorizesRequests;

    public Slider $slider;

    // UI state
    public $confirmingSliderDeletion = false;
    public $isDeleting = false;

    public function mount(Slider $slider)
    {
        $this->authorize('view', $slider);
        
        $this->slider = $slider->load(['user', 'media']);
        
        Log::info('ShowSlider component mounted', [
            'slider_id' => $slider->id,
            'user_id' => auth()->id()
        ]);
    }

    // Navigation methods
    public function editSlider()
    {
        $this->authorize('update', $this->slider);
        return $this->redirect(route('administrator.sliders.edit', $this->slider), navigate: true);
    }

    public function backToList()
    {
        return $this->redirect(route('administrator.sliders.index'), navigate: true);
    }

    // Delete functionality
    public function confirmDelete()
    {
        $this->authorize('delete', $this->slider);
        $this->confirmingSliderDeletion = true;
        
        Log::info('ShowSlider: Delete confirmation requested', [
            'slider_id' => $this->slider->id
        ]);
    }

    public function delete()
    {
        $this->authorize('delete', $this->slider);
        
        Log::info('ShowSlider: Delete method called', [
            'slider_id' => $this->slider->id
        ]);

        $this->isDeleting = true;

        try {
            $sliderHeading = $this->slider->heading;

            // Delete the slider with media
            DB::transaction(function () {
                // Delete associated media
                $this->slider->clearMediaCollection('slider_images');
                
                // Delete image_path file if exists
                if (!empty($this->slider->image_path)) {
                    Storage::disk('public')->delete($this->slider->image_path);
                }
                
                // Delete the slider
                $this->slider->delete();
            });

            Log::info('ShowSlider: Slider deleted successfully', [
                'slider_heading' => $sliderHeading
            ]);

            session()->flash('success', "Slider '{$sliderHeading}' has been deleted successfully.");

            return $this->redirect(route('administrator.sliders.index'), navigate: true);

        } catch (\Exception $e) {
            Log::error('ShowSlider: Error deleting slider', [
                'slider_id' => $this->slider->id,
                'error' => $e->getMessage()
            ]);
            
            session()->flash('error', 'Failed to delete slider: ' . $e->getMessage());
            $this->cancelDelete();
        } finally {
            $this->isDeleting = false;
        }
    }

    public function cancelDelete()
    {
        $this->confirmingSliderDeletion = false;
        
        Log::info('ShowSlider: Delete cancelled', [
            'slider_id' => $this->slider->id
        ]);
    }

    // Helper methods
    public function getCanUserEditSliderProperty()
    {
        return auth()->user()->can('update', $this->slider);
    }

    public function getCanUserDeleteSliderProperty()
    {
        return auth()->user()->can('delete', $this->slider);
    }

    public function getSliderImageUrlProperty()
    {
        return $this->slider->getSliderImageUrl();
    }

    public function getSliderThumbnailUrlProperty()
    {
        return $this->slider->getSliderImageUrl('thumbnail');
    }

    public function getResponsiveImageUrlsProperty()
    {
        return $this->slider->getResponsiveImageUrls();
    }

    public function getFormattedCreatedAtProperty()
    {
        return $this->slider->created_at->format('F j, Y \a\t g:i A');
    }

    public function getFormattedUpdatedAtProperty()
    {
        return $this->slider->updated_at->format('F j, Y \a\t g:i A');
    }

    public function getShowBadgeInfoProperty()
    {
        return [
            'text' => $this->slider->show_location,
            'class' => $this->slider->show_badge_color
        ];
    }

    // Check if slider has changed since creation
    public function getHasBeenModifiedProperty()
    {
        return $this->slider->created_at->ne($this->slider->updated_at);
    }

    public function render()
    {
        return view('livewire.sakon.sliders.show-slider');
    }
}