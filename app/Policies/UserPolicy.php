<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any users.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission([
            'users.view',
            'organizations.staff.view',
            'users.create',
            'users.edit',
            'users.delete'
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Users can always view their own profile
        if ($user->id === $model->id) {
            return true;
        }

        // Check general view permission
        if ($user->hasAnyPermission(['users.view', 'organizations.staff.view'])) {
            // Additional scope checking for organization hierarchy
            return $this->canAccessUserInScope($user, $model);
        }

        return false;
    }

    /**
     * Determine whether the user can create users.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyPermission([
            'users.create',
            'organizations.staff.create'
        ]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Users can edit their own profile with appropriate permission
        if ($user->id === $model->id) {
            return $user->hasPermissionTo('users.edit-profile');
        }

        // Super Admin users can only be edited by other Super Admins
        if ($model->hasRole('Super Admin')) {
            return $user->hasRole('Super Admin') &&
                   $user->hasAnyPermission(['users.edit', 'organizations.staff.edit']);
        }

        // System Admin users can only be edited by Super Admin or other System Admins
        if ($model->hasRole('System Admin')) {
            return $user->hasAnyRole(['Super Admin', 'System Admin']) &&
                   $user->hasAnyPermission(['users.edit', 'organizations.staff.edit']);
        }

        // Check if user has edit permission and can access the target user
        if ($user->hasAnyPermission(['users.edit', 'organizations.staff.edit'])) {
            return $this->canManageUserInScope($user, $model);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Cannot delete yourself
        if ($user->id === $model->id) {
            return false;
        }

        // Cannot delete Super Admin users (except by Super Admins)
        if ($model->hasRole('Super Admin')) {
            return $user->hasRole('Super Admin') && $user->hasPermissionTo('users.delete');
        }

        // Cannot delete System Admin users (except by Super Admin or System Admin)
        if ($model->hasRole('System Admin')) {
            return $user->hasAnyRole(['Super Admin', 'System Admin']) &&
                   $user->hasPermissionTo('users.delete');
        }

        // Check if user has delete permission and can manage the target user
        if ($user->hasPermissionTo('users.delete')) {
            return $this->canManageUserInScope($user, $model);
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->hasPermissionTo('users.delete') &&
               $this->canManageUserInScope($user, $model);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasRole('Super Admin') &&
               $user->hasPermissionTo('users.delete');
    }

    /**
     * Determine whether the user can assign roles to the model.
     */
    public function assignRoles(User $user, User $model): bool
    {
        // Super Admin users can only have roles assigned by Super Admins
        if ($model->hasRole('Super Admin')) {
            return $user->hasRole('Super Admin') &&
                   $user->hasPermissionTo('users.assign-roles');
        }

        // System Admin users can only have roles assigned by Super Admin or System Admin
        if ($model->hasRole('System Admin')) {
            return $user->hasAnyRole(['Super Admin', 'System Admin']) &&
                   $user->hasPermissionTo('users.assign-roles');
        }

        return $user->hasPermissionTo('users.assign-roles') &&
               $this->canManageUserInScope($user, $model);
    }

    /**
     * Determine whether the user can manage permissions for the model.
     */
    public function managePermissions(User $user, User $model): bool
    {
        // Only Super Admin can manage permissions
        return $user->hasRole('Super Admin') &&
               $user->hasPermissionTo('users.manage-permissions');
    }

    /**
     * Determine whether the user can assign department to the model.
     */
    public function assignDepartment(User $user, User $model): bool
    {
        return $user->hasPermissionTo('organizations.staff.assign-department') &&
               $this->canManageUserInScope($user, $model);
    }

    /**
     * Determine whether the user can manage email verification for the model.
     */
    public function manageEmailVerification(User $user, User $model): bool
    {
        return $user->hasAnyPermission(['users.edit', 'organizations.staff.edit']) &&
               $this->canManageUserInScope($user, $model);
    }

    /**
     * Determine whether the user can impersonate the model.
     */
    public function impersonate(User $user, User $model): bool
    {
        // Cannot impersonate yourself
        if ($user->id === $model->id) {
            return false;
        }

        // Only Super Admin can impersonate other users
        return $user->hasRole('Super Admin');
    }

    /**
     * Check if user can access another user within their organizational scope.
     */
    private function canAccessUserInScope(User $user, User $model): bool
    {
        // Super Admin and System Admin can access all users
        if ($user->hasAnyRole(['Super Admin', 'System Admin'])) {
            return true;
        }

        // Organization Admin can access all staff users
        if ($user->hasRole('Organization Admin')) {
            return true;
        }

        // If target user has no department, only higher-level admins can access
        if (!$model->department) {
            return $user->hasAnyRole(['Super Admin', 'System Admin', 'Organization Admin']);
        }

        // If current user has no department, they can't access organization users
        if (!$user->department) {
            return false;
        }

        // Faculty Admin can access users in their faculty
        if ($user->hasRole('Faculty Admin')) {
            return $user->faculty->id === $model->faculty->id;
        }

        // Division Admin can access users in their division
        if ($user->hasRole('Division Admin')) {
            return $user->division->id === $model->division->id;
        }

        // Department Admin can access users in their department
        if ($user->hasRole('Department Admin')) {
            return $user->department->id === $model->department->id;
        }

        // Staff can only access their own profile (handled in view method)
        return false;
    }

    /**
     * Check if user can manage another user within their organizational scope.
     */
    private function canManageUserInScope(User $user, User $model): bool
    {
        // Super Admin and System Admin can manage all users
        if ($user->hasAnyRole(['Super Admin', 'System Admin'])) {
            return true;
        }

        // Organization Admin can manage all staff users
        if ($user->hasRole('Organization Admin')) {
            return true;
        }

        // If target user has no department, only higher-level admins can manage
        if (!$model->department) {
            return $user->hasAnyRole(['Super Admin', 'System Admin', 'Organization Admin']);
        }

        // If current user has no department, they can't manage organization users
        if (!$user->department) {
            return false;
        }

        // Cannot manage users with equal or higher roles within the same scope
        if ($this->hasEqualOrHigherRole($model, $user)) {
            return false;
        }

        // Faculty Admin can manage users in their faculty
        if ($user->hasRole('Faculty Admin')) {
            return $user->faculty->id === $model->faculty->id;
        }

        // Division Admin can manage users in their division
        if ($user->hasRole('Division Admin')) {
            return $user->division->id === $model->division->id;
        }

        // Department Admin can manage users in their department
        if ($user->hasRole('Department Admin')) {
            return $user->department->id === $model->department->id;
        }

        return false;
    }

    /**
     * Check if target user has equal or higher role than current user.
     */
    private function hasEqualOrHigherRole(User $targetUser, User $currentUser): bool
    {
        $roleHierarchy = [
            'Super Admin' => 7,
            'System Admin' => 6,
            'Organization Admin' => 5,
            'Faculty Admin' => 4,
            'Division Admin' => 3,
            'Department Admin' => 2,
            'Staff' => 1,
            'User' => 0
        ];

        $targetLevel = 0;
        $currentLevel = 0;

        foreach ($roleHierarchy as $role => $level) {
            if ($targetUser->hasRole($role)) {
                $targetLevel = max($targetLevel, $level);
            }
            if ($currentUser->hasRole($role)) {
                $currentLevel = max($currentLevel, $level);
            }
        }

        return $targetLevel >= $currentLevel;
    }

    /**
     * Check if user can view staff list.
     */
    public function viewStaffList(User $user): bool
    {
        return $user->hasAnyPermission([
            'organizations.staff.view',
            'users.view'
        ]);
    }

    /**
     * Check if user can export user data.
     */
    public function export(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'System Admin', 'Organization Admin']) &&
               $user->hasPermissionTo('users.view');
    }

    /**
     * Check if user can import user data.
     */
    public function import(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'System Admin', 'Organization Admin']) &&
               $user->hasPermissionTo('users.create');
    }
}
