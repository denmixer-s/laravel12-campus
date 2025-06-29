<?php
// app/Policies/DepartmentPolicy.php
namespace App\Policies;

use App\Models\User;
use App\Models\Department;

class DepartmentPolicy
{
    /**
     * Determine whether the user can view any departments.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission([
            'organizations.departments.view',
            'organizations.view-all'
        ]);
    }

    /**
     * Determine whether the user can view the department.
     */
    public function view(User $user, Department $department): bool
    {
        // Users can view if they have general view permission
        if ($user->hasAnyPermission(['organizations.departments.view', 'organizations.view-all'])) {
            return true;
        }

        // Users can view their own department or departments in their scope
        if ($user->department) {
            // Own department
            if ($user->department->id === $department->id) {
                return true;
            }

            // Faculty admin can view departments in their faculty
            if ($user->hasRole('Faculty Admin') && $user->faculty->id === $department->division->faculty_id) {
                return true;
            }

            // Division admin can view departments in their division
            if ($user->hasRole('Division Admin') && $user->division->id === $department->division_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create departments.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('organizations.departments.create');
    }

    /**
     * Determine whether the user can update the department.
     */
    public function update(User $user, Department $department): bool
    {
        // Check general edit permission
        if ($user->hasPermissionTo('organizations.departments.edit')) {
            return true;
        }

        // Faculty Admin can edit departments in their faculty
        if ($user->hasRole('Faculty Admin') && $user->department && $user->faculty->id === $department->division->faculty_id) {
            return true;
        }

        // Division Admin can edit departments in their division
        if ($user->hasRole('Division Admin') && $user->department && $user->division->id === $department->division_id) {
            return true;
        }

        // Department Admin can edit their own department
        if ($user->hasRole('Department Admin') && $user->department && $user->department->id === $department->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the department.
     */
    public function delete(User $user, Department $department): bool
    {
        return $user->hasPermissionTo('organizations.departments.delete');
    }

    /**
     * Determine whether the user can manage departments.
     */
    public function manage(User $user): bool
    {
        return $user->hasAnyPermission([
            'organizations.departments.manage',
            'organizations.manage-all'
        ]);
    }
}
