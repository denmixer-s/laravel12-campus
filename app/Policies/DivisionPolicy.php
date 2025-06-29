<?php
// app/Policies/DivisionPolicy.php
namespace App\Policies;

use App\Models\User;
use App\Models\Division;

class DivisionPolicy
{
    /**
     * Determine whether the user can view any divisions.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission([
            'organizations.divisions.view',
            'organizations.view-all'
        ]);
    }

    /**
     * Determine whether the user can view the division.
     */
    public function view(User $user, Division $division): bool
    {
        // Users can view if they have general view permission
        if ($user->hasAnyPermission(['organizations.divisions.view', 'organizations.view-all'])) {
            return true;
        }

        // Users can view their own division or divisions in their faculty
        if ($user->department) {
            // Own division
            if ($user->division->id === $division->id) {
                return true;
            }

            // Faculty admin can view divisions in their faculty
            if ($user->hasRole('Faculty Admin') && $user->faculty->id === $division->faculty_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create divisions.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('organizations.divisions.create');
    }

    /**
     * Determine whether the user can update the division.
     */
    public function update(User $user, Division $division): bool
    {
        // Check general edit permission
        if ($user->hasPermissionTo('organizations.divisions.edit')) {
            return true;
        }

        // Faculty Admin can edit divisions in their faculty
        if ($user->hasRole('Faculty Admin') && $user->department && $user->faculty->id === $division->faculty_id) {
            return true;
        }

        // Division Admin can edit their own division
        if ($user->hasRole('Division Admin') && $user->department && $user->division->id === $division->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the division.
     */
    public function delete(User $user, Division $division): bool
    {
        return $user->hasPermissionTo('organizations.divisions.delete');
    }

    /**
     * Determine whether the user can manage divisions.
     */
    public function manage(User $user): bool
    {
        return $user->hasAnyPermission([
            'organizations.divisions.manage',
            'organizations.manage-all'
        ]);
    }
}
