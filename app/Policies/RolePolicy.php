<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any roles.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->can('view-role');
    }

    /**
     * Determine whether the user can view the role.
     */
    public function view(User $user, Role $role): bool
    {
        return $user->hasRole('Super Admin') || $user->can('view-role');
    }

    /**
     * Determine whether the user can create roles.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->can('create-role');
    }

    /**
     * Determine whether the user can update the role.
     */
    public function update(User $user, Role $role): bool
    {
        // Super Admin role cannot be edited
        if ($role->name === 'Super Admin') {
            return false;
        }

        return $user->hasRole('Super Admin') || $user->can('update-role');
    }

    /**
     * Determine whether the user can delete the role.
     */
    public function delete(User $user, Role $role): bool
    {
        // Super Admin role cannot be deleted
        if ($role->name === 'Super Admin') {
            return false;
        }

        // Users cannot delete their own role
        if ($user->hasRole($role->name)) {
            return false;
        }

        // Role with assigned users cannot be deleted
        if ($role->users()->count() > 0) {
            return false;
        }

        return $user->hasRole('Super Admin') || $user->can('delete-role');
    }

    /**
     * Determine whether the user can restore the role.
     */
    public function restore(User $user, Role $role): bool
    {
        return $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can permanently delete the role.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        return $user->hasRole('Super Admin');
    }
}
