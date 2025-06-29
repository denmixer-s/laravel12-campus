<?php

namespace App\Policies;

use App\Models\Page;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any pages.
     */
    public function viewAny(User $user): bool
    {
        // Users can view the pages list if they have any page-related permission
        return $user->hasAnyPermission([
            'view-page',
            'create-page',
            'update-page',
            'delete-page'
        ]) || $user->hasAnyRole([
            'Super Admin',
            'Admin',
            'Editor',
            'Author'
        ]);
    }

    /**
     * Determine whether the user can view the page.
     */
    public function view(User $user, Page $page): bool
    {
        // Super Admin can view all pages
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Admin can view all pages
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Users with view-page permission can view all pages
        if ($user->hasPermissionTo('view-page')) {
            return true;
        }

        // Editors can view all pages
        if ($user->hasRole('Editor')) {
            return true;
        }

        // Authors can view their own pages
        if ($user->hasRole('Author')) {
            return $page->user_id === $user->id;
        }

        // Users can view their own pages
        return $page->user_id === $user->id;
    }

    /**
     * Determine whether the user can create pages.
     */
    public function create(User $user): bool
    {
        // Super Admin can create pages
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Admin can create pages
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Users with create-page permission can create pages
        if ($user->hasPermissionTo('create-page')) {
            return true;
        }

        // Editors can create pages
        if ($user->hasRole('Editor')) {
            return true;
        }

        // Authors can create pages
        if ($user->hasRole('Author')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the page.
     */
    public function update(User $user, Page $page): bool
    {
        // Super Admin can update all pages
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Admin can update all pages
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Users with update-page permission can update all pages
        if ($user->hasPermissionTo('update-page')) {
            return true;
        }

        // Editors can update all pages
        if ($user->hasRole('Editor')) {
            return true;
        }

        // Authors can update their own pages
        if ($user->hasRole('Author')) {
            return $page->user_id === $user->id;
        }

        // Users can update their own pages
        return $page->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the page.
     */
    public function delete(User $user, Page $page): bool
    {
        // Super Admin can delete all pages
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Admin can delete all pages
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Users with delete-page permission can delete all pages
        if ($user->hasPermissionTo('delete-page')) {
            return true;
        }

        // Editors can delete all pages
        if ($user->hasRole('Editor')) {
            return true;
        }

        // Authors can delete their own pages
        if ($user->hasRole('Author')) {
            return $page->user_id === $user->id;
        }

        // Users can delete their own pages if they have permission
        if ($user->hasPermissionTo('delete-own-page')) {
            return $page->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the page.
     */
    public function restore(User $user, Page $page): bool
    {
        // Super Admin can restore all pages
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Admin can restore all pages
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Users with restore-page permission can restore pages
        if ($user->hasPermissionTo('restore-page')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the page.
     */
    public function forceDelete(User $user, Page $page): bool
    {
        // Only Super Admin can force delete pages
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Users with force-delete-page permission can force delete
        if ($user->hasPermissionTo('force-delete-page')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can manage page media.
     */
    public function manageMedia(User $user, Page $page): bool
    {
        // Use the same logic as update for media management
        return $this->update($user, $page);
    }

    /**
     * Determine whether the user can publish/unpublish the page.
     */
    public function publish(User $user, Page $page): bool
    {
        // Super Admin can publish/unpublish all pages
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Admin can publish/unpublish all pages
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Users with publish-page permission can publish/unpublish
        if ($user->hasPermissionTo('publish-page')) {
            return true;
        }

        // Editors can publish/unpublish all pages
        if ($user->hasRole('Editor')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view page analytics.
     */
    public function viewAnalytics(User $user, Page $page): bool
    {
        // Super Admin can view all analytics
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Admin can view all analytics
        if ($user->hasRole('Admin')) {
            return true;
        }

        // Users with view-analytics permission can view analytics
        if ($user->hasPermissionTo('view-page-analytics')) {
            return true;
        }

        // Authors can view analytics for their own pages
        if ($user->hasRole('Author')) {
            return $page->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine if the user can access the pages administration area.
     */
    public function accessAdministration(User $user): bool
    {
        return $user->hasAnyRole([
            'Super Admin',
            'Admin',
            'Editor',
            'Author'
        ]) || $user->hasAnyPermission([
            'view-page',
            'create-page',
            'update-page',
            'delete-page'
        ]);
    }

    /**
     * Helper method to check if user owns the page.
     */
    public function owns(User $user, Page $page): bool
    {
        return $page->user_id === $user->id;
    }

    /**
     * Helper method to check if user is Super Admin.
     */
    public function isSuperAdmin(User $user): bool
    {
        return $user->hasRole('Super Admin');
    }

    /**
     * Helper method to check if user is Admin or higher.
     */
    public function isAdminOrHigher(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin']);
    }

    /**
     * Helper method to check if user can manage all pages.
     */
    public function canManageAllPages(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin', 'Editor']) ||
               $user->hasAnyPermission(['update-page', 'delete-page']);
    }
}
