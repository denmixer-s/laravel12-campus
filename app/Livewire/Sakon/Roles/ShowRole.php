<?php

namespace App\Livewire\Sakon\Roles;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Spatie\Permission\Models\Role;

#[Layout('components.layouts.dashboard')]
#[Title('Role Details')]
class ShowRole extends Component
{
    public Role $role;
    public $activeTab = 'overview';

    public function mount(Role $role)
    {
        $this->role = $role;
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function goToRolesList()
    {
        return $this->redirect(route('administrator.roles.index'), navigate: true);
    }

    public function editRole()
    {
        return $this->redirect(route('administrator.roles.edit', $this->role), navigate: true);
    }

    public function duplicateRole()
    {
        return $this->redirect(route('administrator.roles.create', [
            'duplicate' => $this->role->id
        ]), navigate: true);
    }

    public function getRoleStatsProperty()
    {
        return [
            'users_count' => $this->role->users()->count(),
            'permissions_count' => $this->role->permissions()->count(),
            'created_date' => $this->role->created_at->format('M d, Y'),
            'updated_date' => $this->role->updated_at->format('M d, Y'),
            'created_datetime' => $this->role->created_at->format('M d, Y \a\t H:i'),
            'updated_datetime' => $this->role->updated_at->format('M d, Y \a\t H:i'),
            'created_human' => $this->role->created_at->diffForHumans(),
            'updated_human' => $this->role->updated_at->diffForHumans(),
        ];
    }

    public function getPermissionGroupsProperty()
    {
        return $this->role->permissions->groupBy(function ($permission) {
            $parts = explode('-', $permission->name);
            return count($parts) > 1 ? $parts[1] : 'general';
        })->map(function ($group) {
            return $group->sortBy('name');
        });
    }

    public function render()
    {
        return view('livewire.sakon.roles.show-role', [
            'roleStats' => $this->roleStats,
            'permissionGroups' => $this->permissionGroups,
        ]);
    }
}
