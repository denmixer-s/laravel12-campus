<?php


namespace App\Policies;

use App\Models\User;
use App\Models\Faculty;

class FacultyPolicy
{
    /**
     * Determine whether the user can view any faculties.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission([
            'organizations.faculties.view',
            'organizations.view-all'
        ]);
    }

    /**
     * Determine whether the user can view the faculty.
     */
    public function view(User $user, Faculty $faculty): bool
    {
        // Users can view if they have general view permission
        if ($user->hasAnyPermission(['organizations.faculties.view', 'organizations.view-all'])) {
            return true;
        }

        // Users can view their own faculty
        if ($user->department && $user->faculty->id === $faculty->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create faculties.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('organizations.faculties.create');
    }

    /**
     * Determine whether the user can update the faculty.
     */
    public function update(User $user, Faculty $faculty): bool
    {
        // Check general edit permission
        if ($user->hasPermissionTo('organizations.faculties.edit')) {
            return true;
        }

        // Faculty Admin can edit their own faculty
        if ($user->hasRole('Faculty Admin') && $user->department && $user->faculty->id === $faculty->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the faculty.
     */
    public function delete(User $user, Faculty $faculty): bool
    {
        return $user->hasPermissionTo('organizations.faculties.delete');
    }

    /**
     * Determine whether the user can manage faculties.
     */
    public function manage(User $user): bool
    {
        return $user->hasAnyPermission([
            'organizations.faculties.manage',
            'organizations.manage-all'
        ]);
    }
}
