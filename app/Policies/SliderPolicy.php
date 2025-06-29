<?php

namespace App\Policies;

use App\Models\Slider;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SliderPolicy
{
    /**
     * Determine whether the user can view any sliders.
     */
    public function viewAny(User $user): bool
    {
        // Super Admin can always view all sliders
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Check for specific permissions
        return $user->hasAnyPermission([
            'view-slider',
            'view-any-slider',
            'manage-slider',
            'manage-sliders'
        ]);
    }

    /**
     * Determine whether the user can view the slider.
     */
    public function view(User $user, Slider $slider): bool
    {
        // Super Admin can view any slider
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Users can view sliders they created
        if ($slider->user_id === $user->id) {
            return true;
        }

        // Check for specific permissions
        return $user->hasAnyPermission([
            'view-slider',
            'view-any-slider',
            'manage-slider',
            'manage-sliders'
        ]);
    }

    /**
     * Determine whether the user can create sliders.
     */
    public function create(User $user): bool
    {
        // Super Admin can always create sliders
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Check for specific permissions
        return $user->hasAnyPermission([
            'create-slider',
            'manage-slider',
            'manage-sliders'
        ]);
    }

    /**
     * Determine whether the user can update the slider.
     */
    public function update(User $user, Slider $slider): bool
    {
        // Super Admin can update any slider
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Users can update their own sliders
        if ($slider->user_id === $user->id) {
            return $user->hasAnyPermission([
                'update-slider',
                'update-own-slider',
                'manage-slider',
                'manage-sliders'
            ]);
        }

        // Users with appropriate permissions can update any slider
        return $user->hasAnyPermission([
            'update-slider',
            'update-any-slider',
            'manage-slider',
            'manage-sliders'
        ]);
    }

    /**
     * Determine whether the user can delete the slider.
     */
    public function delete(User $user, Slider $slider): bool
    {
        // Super Admin can delete any slider
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Users can delete their own sliders
        if ($slider->user_id === $user->id) {
            return $user->hasAnyPermission([
                'delete-slider',
                'delete-own-slider',
                'manage-slider',
                'manage-sliders'
            ]);
        }

        // Users with appropriate permissions can delete any slider
        return $user->hasAnyPermission([
            'delete-slider',
            'delete-any-slider',
            'manage-slider',
            'manage-sliders'
        ]);
    }

    /**
     * Determine whether the user can restore the slider.
     */
    public function restore(User $user, Slider $slider): bool
    {
        // Super Admin can restore any slider
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Users can restore their own sliders
        if ($slider->user_id === $user->id) {
            return $user->hasAnyPermission([
                'restore-slider',
                'restore-own-slider',
                'manage-slider',
                'manage-sliders'
            ]);
        }

        // Users with appropriate permissions can restore any slider
        return $user->hasAnyPermission([
            'restore-slider',
            'restore-any-slider',
            'manage-slider',
            'manage-sliders'
        ]);
    }

    /**
     * Determine whether the user can permanently delete the slider.
     */
    public function forceDelete(User $user, Slider $slider): bool
    {
        // Only Super Admin can force delete sliders
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Users with specific force delete permissions
        return $user->hasAnyPermission([
            'force-delete-slider',
            'force-delete-any-slider'
        ]);
    }

    /**
     * Determine whether the user can duplicate the slider.
     */
    public function duplicate(User $user, Slider $slider): bool
    {
        // If user can view the slider and create new sliders, they can duplicate
        return $this->view($user, $slider) && $this->create($user);
    }

    /**
     * Determine whether the user can manage slider images.
     */
    public function manageImages(User $user, Slider $slider): bool
    {
        // Super Admin can manage any slider images
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Users can manage images of their own sliders
        if ($slider->user_id === $user->id) {
            return $user->hasAnyPermission([
                'update-slider',
                'update-own-slider',
                'manage-slider-images',
                'manage-slider',
                'manage-sliders'
            ]);
        }

        // Users with appropriate permissions can manage any slider images
        return $user->hasAnyPermission([
            'update-slider',
            'update-any-slider',
            'manage-slider-images',
            'manage-slider',
            'manage-sliders'
        ]);
    }

    /**
     * Determine whether the user can publish/unpublish sliders.
     */
    public function publish(User $user, Slider $slider): bool
    {
        // Super Admin can publish/unpublish any slider
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Users can publish their own sliders if they have permission
        if ($slider->user_id === $user->id) {
            return $user->hasAnyPermission([
                'publish-slider',
                'publish-own-slider',
                'manage-slider',
                'manage-sliders'
            ]);
        }

        // Users with appropriate permissions can publish any slider
        return $user->hasAnyPermission([
            'publish-slider',
            'publish-any-slider',
            'manage-slider',
            'manage-sliders'
        ]);
    }

    /**
     * Determine whether the user can change slider display location.
     */
    public function changeDisplayLocation(User $user, Slider $slider): bool
    {
        // Super Admin can change display location of any slider
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Users can change display location of their own sliders
        if ($slider->user_id === $user->id) {
            return $user->hasAnyPermission([
                'update-slider',
                'update-own-slider',
                'change-slider-location',
                'manage-slider',
                'manage-sliders'
            ]);
        }

        // Users with appropriate permissions can change any slider's display location
        return $user->hasAnyPermission([
            'update-slider',
            'update-any-slider',
            'change-slider-location',
            'manage-slider',
            'manage-sliders'
        ]);
    }

    /**
     * Determine whether the user can view slider analytics/stats.
     */
    public function viewAnalytics(User $user, Slider $slider): bool
    {
        // Super Admin can view analytics for any slider
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Users can view analytics for their own sliders
        if ($slider->user_id === $user->id) {
            return $user->hasAnyPermission([
                'view-slider-analytics',
                'view-own-slider-analytics',
                'manage-slider',
                'manage-sliders'
            ]);
        }

        // Users with appropriate permissions can view any slider analytics
        return $user->hasAnyPermission([
            'view-slider-analytics',
            'view-any-slider-analytics',
            'manage-slider',
            'manage-sliders'
        ]);
    }

    /**
     * Determine whether the user can export sliders.
     */
    public function export(User $user): bool
    {
        // Super Admin can export sliders
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Check for specific permissions
        return $user->hasAnyPermission([
            'export-slider',
            'export-sliders',
            'manage-sliders'
        ]);
    }

    /**
     * Determine whether the user can import sliders.
     */
    public function import(User $user): bool
    {
        // Super Admin can import sliders
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Check for specific permissions
        return $user->hasAnyPermission([
            'import-slider',
            'import-sliders',
            'manage-sliders'
        ]);
    }

    /**
     * Determine whether the user can bulk delete sliders.
     */
    public function bulkDelete(User $user): bool
    {
        // Super Admin can bulk delete sliders
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Check for specific permissions
        return $user->hasAnyPermission([
            'bulk-delete-slider',
            'bulk-delete-sliders',
            'delete-slider',
            'delete-any-slider',
            'manage-sliders'
        ]);
    }

    /**
     * Determine whether the user can bulk update sliders.
     */
    public function bulkUpdate(User $user): bool
    {
        // Super Admin can bulk update sliders
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Check for specific permissions
        return $user->hasAnyPermission([
            'bulk-update-slider',
            'bulk-update-sliders',
            'update-slider',
            'update-any-slider',
            'manage-sliders'
        ]);
    }

    /**
     * Helper method to check if user owns the slider.
     */
    private function ownsSlider(User $user, Slider $slider): bool
    {
        return $slider->user_id === $user->id;
    }

    /**
     * Helper method to check if user is Super Admin.
     */
    private function isSuperAdmin(User $user): bool
    {
        return $user->hasRole('Super Admin');
    }

    /**
     * Helper method to check if user has any slider management permission.
     */
    private function hasSliderManagementPermission(User $user): bool
    {
        return $user->hasAnyPermission([
            'manage-slider',
            'manage-sliders'
        ]);
    }
}