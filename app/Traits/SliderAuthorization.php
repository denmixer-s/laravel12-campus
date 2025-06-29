<?php

namespace App\Traits;

use App\Models\Slider;
use Illuminate\Support\Facades\Gate;

trait SliderAuthorization
{
    /**
     * Check if the current user can view any sliders.
     */
    public function canViewAnySliders(): bool
    {
        return Gate::allows('viewAny', Slider::class);
    }

    /**
     * Check if the current user can view a specific slider.
     */
    public function canViewSlider(Slider $slider): bool
    {
        return Gate::allows('view', $slider);
    }

    /**
     * Check if the current user can create sliders.
     */
    public function canCreateSliders(): bool
    {
        return Gate::allows('create', Slider::class);
    }

    /**
     * Check if the current user can update a specific slider.
     */
    public function canUpdateSlider(Slider $slider): bool
    {
        return Gate::allows('update', $slider);
    }

    /**
     * Check if the current user can delete a specific slider.
     */
    public function canDeleteSlider(Slider $slider): bool
    {
        return Gate::allows('delete', $slider);
    }

    /**
     * Check if the current user can duplicate a specific slider.
     */
    public function canDuplicateSlider(Slider $slider): bool
    {
        return Gate::allows('duplicate', $slider);
    }

    /**
     * Check if the current user can manage images for a specific slider.
     */
    public function canManageSliderImages(Slider $slider): bool
    {
        return Gate::allows('manageImages', $slider);
    }

    /**
     * Check if the current user can publish/unpublish a specific slider.
     */
    public function canPublishSlider(Slider $slider): bool
    {
        return Gate::allows('publish', $slider);
    }

    /**
     * Check if the current user can change display location of a specific slider.
     */
    public function canChangeSliderLocation(Slider $slider): bool
    {
        return Gate::allows('changeDisplayLocation', $slider);
    }

    /**
     * Check if the current user can view analytics for a specific slider.
     */
    public function canViewSliderAnalytics(Slider $slider): bool
    {
        return Gate::allows('viewAnalytics', $slider);
    }

    /**
     * Check if the current user can perform bulk operations on sliders.
     */
    public function canPerformBulkOperations(): bool
    {
        return Gate::allows('bulk-slider-operations');
    }

    /**
     * Check if the current user can export sliders.
     */
    public function canExportSliders(): bool
    {
        return Gate::allows('export', Slider::class);
    }

    /**
     * Check if the current user can import sliders.
     */
    public function canImportSliders(): bool
    {
        return Gate::allows('import', Slider::class);
    }

    /**
     * Check if the current user can access the slider admin area.
     */
    public function canAccessSliderAdmin(): bool
    {
        return Gate::allows('access-slider-admin');
    }

    /**
     * Check if the current user can upload slider images.
     */
    public function canUploadSliderImages(): bool
    {
        return Gate::allows('upload-slider-images');
    }

    /**
     * Check if the current user can change slider visibility.
     */
    public function canChangeSliderVisibility(): bool
    {
        return Gate::allows('change-slider-visibility');
    }

    /**
     * Check if the current user can view the slider dashboard.
     */
    public function canViewSliderDashboard(): bool
    {
        return Gate::allows('view-slider-dashboard');
    }

    /**
     * Check if the current user owns a specific slider.
     */
    public function ownsSlider(Slider $slider): bool
    {
        return auth()->check() && $slider->user_id === auth()->id();
    }

    /**
     * Check if the current user is a Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        return auth()->check() && auth()->user()->hasRole('Super Admin');
    }

    /**
     * Get the authorization message for a specific action.
     */
    public function getAuthorizationMessage(string $action, ?Slider $slider = null): string
    {
        $messages = [
            'view' => 'You do not have permission to view this slider.',
            'create' => 'You do not have permission to create sliders.',
            'update' => 'You do not have permission to update this slider.',
            'delete' => 'You do not have permission to delete this slider.',
            'duplicate' => 'You do not have permission to duplicate this slider.',
            'manage_images' => 'You do not have permission to manage images for this slider.',
            'publish' => 'You do not have permission to publish/unpublish this slider.',
            'change_location' => 'You do not have permission to change the display location of this slider.',
            'view_analytics' => 'You do not have permission to view analytics for this slider.',
            'bulk_operations' => 'You do not have permission to perform bulk operations on sliders.',
            'export' => 'You do not have permission to export sliders.',
            'import' => 'You do not have permission to import sliders.',
        ];

        return $messages[$action] ?? 'You do not have permission to perform this action.';
    }

    /**
     * Get available actions for the current user on a specific slider.
     */
    public function getAvailableSliderActions(Slider $slider): array
    {
        $actions = [];

        if ($this->canViewSlider($slider)) {
            $actions[] = 'view';
        }

        if ($this->canUpdateSlider($slider)) {
            $actions[] = 'update';
        }

        if ($this->canDeleteSlider($slider)) {
            $actions[] = 'delete';
        }

        if ($this->canDuplicateSlider($slider)) {
            $actions[] = 'duplicate';
        }

        if ($this->canManageSliderImages($slider)) {
            $actions[] = 'manage_images';
        }

        if ($this->canPublishSlider($slider)) {
            $actions[] = 'publish';
        }

        if ($this->canChangeSliderLocation($slider)) {
            $actions[] = 'change_location';
        }

        if ($this->canViewSliderAnalytics($slider)) {
            $actions[] = 'view_analytics';
        }

        return $actions;
    }

    /**
     * Check multiple permissions at once.
     */
    public function canPerformAnySliderAction(Slider $slider, array $actions): bool
    {
        $availableActions = $this->getAvailableSliderActions($slider);
        
        return !empty(array_intersect($actions, $availableActions));
    }

    /**
     * Check if user has all specified permissions.
     */
    public function canPerformAllSliderActions(Slider $slider, array $actions): bool
    {
        $availableActions = $this->getAvailableSliderActions($slider);
        
        return empty(array_diff($actions, $availableActions));
    }
}