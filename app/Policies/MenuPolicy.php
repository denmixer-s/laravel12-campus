<?php

namespace App\Policies;

use App\Models\Menu;
use App\Models\User;

class MenuPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->can('view-menu');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Menu $menu): bool
    {
        return $user->hasRole('Super Admin') || $user->can('view-menu');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->can('create-menu');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Menu $menu): bool
    {
        return $user->hasRole('Super Admin') || $user->can('update-menu');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Menu $menu): bool
    {
        // Super Admin can delete any menu except system menus
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Regular users need delete permission
        if (!$user->can('delete-menu')) {
            return false;
        }

        // Cannot delete menu with children
        if ($menu->children()->count() > 0) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Menu $menu): bool
    {
        return $user->hasRole('Super Admin') || $user->can('restore-menu');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Menu $menu): bool
    {
        return $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can manage menu hierarchy.
     */
    public function manageHierarchy(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->can('manage-menu-hierarchy');
    }

    /**
     * Determine whether the user can reorder menus.
     */
    public function reorder(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->can('reorder-menu');
    }
}