<?php

// app/Policies/UniversityPolicy.php
namespace App\Policies;

use App\Models\User;
use App\Models\University;

class UniversityPolicy
{
    /**
     * Determine whether the user can view any universities.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission([
            'organizations.universities.view',
            'organizations.view-all'
        ]);
    }

    /**
     * Determine whether the user can view the university.
     */
    public function view(User $user, University $university): bool
    {
        return $user->hasAnyPermission([
            'organizations.universities.view',
            'organizations.view-all'
        ]);
    }

    /**
     * Determine whether the user can create universities.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('organizations.universities.create');
    }

    /**
     * Determine whether the user can update the university.
     */
    public function update(User $user, University $university): bool
    {
        return $user->hasPermissionTo('organizations.universities.edit');
    }

    /**
     * Determine whether the user can delete the university.
     */
    public function delete(User $user, University $university): bool
    {
        return $user->hasPermissionTo('organizations.universities.delete');
    }

    /**
     * Determine whether the user can manage universities.
     */
    public function manage(User $user): bool
    {
        return $user->hasAnyPermission([
            'organizations.universities.manage',
            'organizations.manage-all'
        ]);
    }
}
