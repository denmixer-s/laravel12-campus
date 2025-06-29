<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any permissions.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->can('view-permission');
    }

    /**
     * Determine whether the user can view the permission.
     */
    public function view(User $user, Permission $permission): bool
    {
        return $user->hasRole('Super Admin') || $user->can('view-permission');
    }

    /**
     * Determine whether the user can create permissions.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->can('create-permission');
    }

    /**
     * Determine whether the user can update the permission.
     */
    public function update(User $user, Permission $permission): bool
    {
        return $user->hasRole('Super Admin') || $user->can('edit-permission');
    }

    /**
     * Determine whether the user can delete the permission.
     */
    public function delete(User $user, Permission $permission): bool
    {
        // Prevent deletion if permission is assigned to roles
        if ($permission->roles()->count() > 0) {
            return false;
        }

        return $user->hasRole('Super Admin') || $user->can('delete-permission');
    }

    /**
     * Determine whether the user can restore the permission.
     */
    public function restore(User $user, Permission $permission): bool
    {
        return $user->hasRole('Super Admin') || $user->can('restore-permission');
    }

    /**
     * Determine whether the user can permanently delete the permission.
     */
    public function forceDelete(User $user, Permission $permission): bool
    {
        return $user->hasRole('Super Admin') || $user->can('force-delete-permission');
    }
}
