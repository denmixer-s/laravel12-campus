<?php

namespace App\Livewire\Sakon\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

#[Layout('components.layouts.dashboard')]
#[Title('Manage Roles')]
class ListRole extends Component
{
    use WithPagination, AuthorizesRequests;

    // Component properties
    public $search = '';
    public $perPage = 10;
    public $confirmingRoleDeletion = false;
    public $roleToDelete = null;
    public $roleToDeleteName = '';

    // Real-time search
    protected $listeners = [
        'roleCreated' => 'handleRoleCreated',
        'roleUpdated' => 'handleRoleUpdated',
        'roleDeleted' => 'handleRoleDeleted',
    ];

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    // Real-time updates
    public function handleRoleCreated()
    {
        $this->resetPage();
        session()->flash('success', 'Role created successfully!');
    }

    public function handleRoleUpdated()
    {
        $this->resetPage();
        session()->flash('success', 'Role updated successfully!');
    }

    public function handleRoleDeleted()
    {
        $this->resetPage();
        session()->flash('success', 'Role deleted successfully!');
    }

    // Navigation methods
    public function createRole()
    {
        return $this->redirect(route('administrator.roles.create'), navigate: true);
    }

    public function viewRole($roleId)
    {
        return $this->redirect(route('administrator.roles.show', $roleId), navigate: true);
    }

    public function editRole($roleId)
    {
        return $this->redirect(route('administrator.roles.edit', $roleId), navigate: true);
    }

    // Simplified Delete functionality
    public function confirmDelete($roleId)
    {
        Log::info('confirmDelete called with roleId: ' . $roleId);

        try {
            $role = Role::findOrFail($roleId);
            Log::info('Role found: ' . $role->name);

            // Basic validation checks
            if ($role->name === 'Super Admin') {
                session()->flash('error', 'Super Admin role cannot be deleted.');
                return;
            }

            if (auth()->user()->hasRole($role->name)) {
                session()->flash('error', 'You cannot delete your own role.');
                return;
            }

            $userCount = $role->users()->count();
            if ($userCount > 0) {
                session()->flash('error', "Cannot delete role '{$role->name}' because it is assigned to {$userCount} user(s). Remove users from this role first.");
                return;
            }

            // Set the properties for the modal
            $this->roleToDelete = $roleId;
            $this->roleToDeleteName = $role->name;
            $this->confirmingRoleDeletion = true;

            Log::info('Modal should open now', [
                'roleToDelete' => $this->roleToDelete,
                'roleToDeleteName' => $this->roleToDeleteName,
                'confirmingRoleDeletion' => $this->confirmingRoleDeletion
            ]);

        } catch (\Exception $e) {
            Log::error('Error in confirmDelete: ' . $e->getMessage());
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        Log::info('delete method called');

        if (!$this->roleToDelete) {
            session()->flash('error', 'No role selected for deletion.');
            $this->cancelDelete();
            return;
        }

        try {
            $role = Role::findOrFail($this->roleToDelete);
            $roleName = $role->name;

            Log::info('Deleting role: ' . $roleName);

            // Final validation
            if ($role->name === 'Super Admin') {
                session()->flash('error', 'Super Admin role cannot be deleted.');
                $this->cancelDelete();
                return;
            }

            if ($role->users()->count() > 0) {
                session()->flash('error', 'Role still has users assigned.');
                $this->cancelDelete();
                return;
            }

            // Delete the role
            DB::transaction(function () use ($role) {
                $role->permissions()->detach();
                $role->delete();
            });

            Log::info('Role deleted successfully: ' . $roleName);

            session()->flash('success', "Role '{$roleName}' has been deleted successfully.");

            $this->resetPage();
            $this->cancelDelete();

        } catch (\Exception $e) {
            Log::error('Error deleting role: ' . $e->getMessage());
            session()->flash('error', 'Failed to delete role: ' . $e->getMessage());
            $this->cancelDelete();
        }
    }

    public function cancelDelete()
    {
        Log::info('cancelDelete called');
        $this->confirmingRoleDeletion = false;
        $this->roleToDelete = null;
        $this->roleToDeleteName = '';
    }

    // Helper method to check if user can delete role
    public function canUserDeleteRole($role)
    {
        // Super Admin role cannot be deleted
        if ($role->name === 'Super Admin') {
            return false;
        }

        // User cannot delete their own role
        if (auth()->user()->hasRole($role->name)) {
            return false;
        }

        // Role with users cannot be deleted
        if ($role->users()->count() > 0) {
            return false;
        }

        // Check if user has permission (simplified)
        return auth()->user()->hasRole('Super Admin') || auth()->user()->can('delete-role');
    }

    // Data fetching
    public function getRolesProperty()
    {
        try {
            return Role::with(['permissions', 'users'])
                ->when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->orderBy('created_at', 'desc')
                ->paginate($this->perPage);
        } catch (\Exception $e) {
            Log::error('Error loading roles', ['error' => $e->getMessage()]);
            session()->flash('error', 'Error loading roles. Please try again.');
            return collect()->paginate($this->perPage);
        }
    }

    public function render()
    {
        return view('livewire.sakon.roles.list-role', [
            'roles' => $this->roles,
        ]);
    }
}
