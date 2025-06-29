<?php

namespace App\Livewire\Sakon\Sliders;

use App\Models\Slider;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

#[Layout('components.layouts.dashboard')]
#[Title('Manage Sliders')]
class ListSlider extends Component
{
    use WithPagination, AuthorizesRequests;

    // Component properties
    public $search = '';
    public $showFilter = '';
    public $perPage = 10;
    public $confirmingSliderDeletion = false;
    public $sliderToDelete = null;
    public $sliderToDeleteHeading = '';

    // Real-time listeners
    protected $listeners = [
        'sliderCreated' => 'handleSliderCreated',
        'sliderUpdated' => 'handleSliderUpdated',
        'sliderDeleted' => 'handleSliderDeleted',
    ];

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedShowFilter()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    // Real-time updates
    public function handleSliderCreated()
    {
        $this->resetPage();
        session()->flash('success', 'Slider created successfully!');
    }

    public function handleSliderUpdated()
    {
        $this->resetPage();
        session()->flash('success', 'Slider updated successfully!');
    }

    public function handleSliderDeleted()
    {
        $this->resetPage();
        session()->flash('success', 'Slider deleted successfully!');
    }

    // Navigation methods
    public function createSlider()
    {
        return $this->redirect(route('administrator.sliders.create'), navigate: true);
    }

    public function viewSlider($sliderId)
    {
        return $this->redirect(route('administrator.sliders.show', $sliderId), navigate: true);
    }

    public function editSlider($sliderId)
    {
        return $this->redirect(route('administrator.sliders.edit', $sliderId), navigate: true);
    }

    // Delete functionality
    public function confirmDelete($sliderId)
    {
        Log::info('confirmDelete called with sliderId: ' . $sliderId);

        try {
            $slider = Slider::findOrFail($sliderId);
            Log::info('Slider found: ' . $slider->heading);

            // Set the properties for the modal
            $this->sliderToDelete = $sliderId;
            $this->sliderToDeleteHeading = $slider->heading;
            $this->confirmingSliderDeletion = true;

            Log::info('Modal should open now', [
                'sliderToDelete' => $this->sliderToDelete,
                'sliderToDeleteHeading' => $this->sliderToDeleteHeading,
                'confirmingSliderDeletion' => $this->confirmingSliderDeletion
            ]);

        } catch (\Exception $e) {
            Log::error('Error in confirmDelete: ' . $e->getMessage());
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        Log::info('delete method called');

        if (!$this->sliderToDelete) {
            session()->flash('error', 'No slider selected for deletion.');
            $this->cancelDelete();
            return;
        }

        try {
            $slider = Slider::findOrFail($this->sliderToDelete);
            $sliderHeading = $slider->heading;

            Log::info('Deleting slider: ' . $sliderHeading);

            // Delete the slider with media
            DB::transaction(function () use ($slider) {
                // Delete associated media
                $slider->clearMediaCollection('slider_images');
                
                // Delete the slider
                $slider->delete();
            });

            Log::info('Slider deleted successfully: ' . $sliderHeading);

            session()->flash('success', "Slider '{$sliderHeading}' has been deleted successfully.");

            $this->resetPage();
            $this->cancelDelete();

        } catch (\Exception $e) {
            Log::error('Error deleting slider: ' . $e->getMessage());
            session()->flash('error', 'Failed to delete slider: ' . $e->getMessage());
            $this->cancelDelete();
        }
    }

    public function cancelDelete()
    {
        Log::info('cancelDelete called');
        $this->confirmingSliderDeletion = false;
        $this->sliderToDelete = null;
        $this->sliderToDeleteHeading = '';
    }

    // Helper method to check if user can delete slider
    public function canUserDeleteSlider($slider)
    {
        // Check if user has permission (you can customize this logic)
        return auth()->user()->hasRole('Super Admin') || 
               auth()->user()->can('delete-slider') ||
               $slider->user_id === auth()->id();
    }

    // Data fetching
    public function getSlidersProperty()
    {
        try {
            return Slider::with(['user', 'media'])
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('heading', 'like', '%' . $this->search . '%')
                          ->orWhere('description', 'like', '%' . $this->search . '%')
                          ->orWhere('link', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->showFilter, function ($query) {
                    if ($this->showFilter === 'home') {
                        $query->forHome();
                    } elseif ($this->showFilter === 'frontend') {
                        $query->forFrontend();
                    } else {
                        $query->where('show', $this->showFilter);
                    }
                })
                ->orderBy('created_at', 'desc')
                ->paginate($this->perPage);
        } catch (\Exception $e) {
            Log::error('Error loading sliders', ['error' => $e->getMessage()]);
            session()->flash('error', 'Error loading sliders. Please try again.');
            return collect()->paginate($this->perPage);
        }
    }

    // Get show options for filter
    public function getShowOptionsProperty()
    {
        return [
            '' => 'All Locations',
            'home' => 'Home Page',
            'frontend' => 'Frontend',
            'both' => 'Both Pages',
        ];
    }

    public function render()
    {
        return view('livewire.sakon.sliders.list-slider', [
            'sliders' => $this->sliders,
            'showOptions' => $this->showOptions,
        ]);
    }
}