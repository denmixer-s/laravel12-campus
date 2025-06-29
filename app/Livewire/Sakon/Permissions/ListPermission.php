<?php

namespace App\Livewire\Sakon\Permissions;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

#[Layout('components.layouts.dashboard')]
#[Title('Manage Permissions')]
class ListPermission extends Component
{
    use WithPagination, AuthorizesRequests;

    // Component properties
    public $search = '';
    public $perPage = 10;
    public $filterGuard = '';
    public $filterCategory = '';
    public $confirmingPermissionDeletion = false;
    public $permissionToDelete = null;
    public $permissionToDeleteName = '';

    // Real-time search
    protected $listeners = [
        'permissionCreated' => 'handlePermissionCreated',
        'permissionsCreated' => 'handlePermissionsCreated',
        'permissionUpdated' => 'handlePermissionUpdated',
        'permissionDeleted' => 'handlePermissionDeleted',
    ];

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->authorize('viewAny', Permission::class);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedFilterGuard()
    {
        $this->resetPage();
    }

    public function updatedFilterCategory()
    {
        $this->resetPage();
    }

    // Real-time updates
    public function handlePermissionCreated()
    {
        $this->resetPage();
        session()->flash('success', 'Permission created successfully!');
    }

    public function handlePermissionsCreated($data)
    {
        $this->resetPage();
        $count = $data['count'] ?? 1;
        session()->flash('success', "{$count} permission(s) created successfully!");
    }

    public function handlePermissionUpdated()
    {
        $this->resetPage();
        session()->flash('success', 'Permission updated successfully!');
    }

    public function handlePermissionDeleted()
    {
        $this->resetPage();
        session()->flash('success', 'Permission deleted successfully!');
    }

    // Navigation methods
    public function createPermission()
    {
        return $this->redirect(route('administrator.permissions.create'), navigate: true);
    }

    public function viewPermission($permissionId)
    {
        return $this->redirect(route('administrator.permissions.show', $permissionId), navigate: true);
    }

    public function editPermission($permissionId)
    {
        return $this->redirect(route('administrator.permissions.edit', $permissionId), navigate: true);
    }

    // Delete functionality
    public function confirmDelete($permissionId)
    {
        Log::info('confirmDelete called with permissionId: ' . $permissionId);

        try {
            $permission = Permission::findOrFail($permissionId);
            Log::info('Permission found: ' . $permission->name);

            // Check if permission is assigned to any roles
            $roleCount = $permission->roles()->count();
            if ($roleCount > 0) {
                session()->flash('error', "Cannot delete permission '{$permission->name}' because it is assigned to {$roleCount} role(s). Remove from roles first.");
                return;
            }

            // Set the properties for the modal
            $this->permissionToDelete = $permissionId;
            $this->permissionToDeleteName = $permission->name;
            $this->confirmingPermissionDeletion = true;

            Log::info('Modal should open now', [
                'permissionToDelete' => $this->permissionToDelete,
                'permissionToDeleteName' => $this->permissionToDeleteName,
                'confirmingPermissionDeletion' => $this->confirmingPermissionDeletion
            ]);

        } catch (\Exception $e) {
            Log::error('Error in confirmDelete: ' . $e->getMessage());
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        Log::info('delete method called');

        if (!$this->permissionToDelete) {
            session()->flash('error', 'No permission selected for deletion.');
            $this->cancelDelete();
            return;
        }

        try {
            $permission = Permission::findOrFail($this->permissionToDelete);
            $permissionName = $permission->name;

            Log::info('Deleting permission: ' . $permissionName);

            // Final validation - check if still assigned to roles
            if ($permission->roles()->count() > 0) {
                session()->flash('error', 'Permission still has roles assigned.');
                $this->cancelDelete();
                return;
            }

            // Delete the permission
            DB::transaction(function () use ($permission) {
                $permission->roles()->detach();
                $permission->delete();
            });

            Log::info('Permission deleted successfully: ' . $permissionName);

            session()->flash('success', "Permission '{$permissionName}' has been deleted successfully.");

            $this->resetPage();
            $this->cancelDelete();

        } catch (\Exception $e) {
            Log::error('Error deleting permission: ' . $e->getMessage());
            session()->flash('error', 'Failed to delete permission: ' . $e->getMessage());
            $this->cancelDelete();
        }
    }

    public function cancelDelete()
    {
        Log::info('cancelDelete called');
        $this->confirmingPermissionDeletion = false;
        $this->permissionToDelete = null;
        $this->permissionToDeleteName = '';
    }

    // Helper method to check if user can delete permission
    public function canUserDeletePermission($permission)
    {
        // Permission with roles cannot be deleted
        if ($permission->roles_count > 0) {
            return false;
        }

        // Check if user has permission
        return auth()->user()->hasRole('Super Admin') || auth()->user()->can('delete-permission');
    }

    // Data fetching with proper eager loading
    public function getPermissionsProperty()
    {
        try {
            return Permission::withCount('roles')
                ->when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->when($this->filterGuard, function ($query) {
                    $query->where('guard_name', $this->filterGuard);
                })
                ->when($this->filterCategory, function ($query) {
                    $query->where('name', 'like', '%-' . $this->filterCategory . '%');
                })
                ->orderBy('name')
                ->paginate($this->perPage);
        } catch (\Exception $e) {
            Log::error('Error loading permissions', ['error' => $e->getMessage()]);
            session()->flash('error', 'Error loading permissions. Please try again.');
            return collect()->paginate($this->perPage);
        }
    }

    public function getAvailableCategoriesProperty()
    {
        $permissions = Permission::select('name')->get();

        return $permissions->map(function ($permission) {
            $parts = explode('-', $permission->name);
            return count($parts) > 1 ? $parts[1] : 'general';
        })->unique()->sort()->values();
    }

    public function getPermissionStatsProperty()
    {
        return [
            'total' => Permission::count(),
            'web_guard' => Permission::where('guard_name', 'web')->count(),
            'api_guard' => Permission::where('guard_name', 'api')->count(),
            'assigned' => Permission::has('roles')->count(),
            'unassigned' => Permission::doesntHave('roles')->count(),
        ];
    }

    // Bulk operations
    public $selectedPermissions = [];
    public $selectAll = false;

    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedPermissions = $this->permissions->pluck('id')->toArray();
        } else {
            $this->selectedPermissions = [];
        }
    }

    public function bulkDelete()
    {
        if (empty($this->selectedPermissions)) {
            session()->flash('error', 'Please select permissions to delete.');
            return;
        }

        try {
            DB::transaction(function () {
                Permission::whereIn('id', $this->selectedPermissions)
                    ->whereDoesntHave('roles')
                    ->delete();
            });

            $deletedCount = count($this->selectedPermissions);
            session()->flash('success', "{$deletedCount} permission(s) deleted successfully.");

            $this->selectedPermissions = [];
            $this->selectAll = false;
            $this->resetPage();

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete permissions: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.sakon.permissions.list-permission', [
            'permissions' => $this->permissions,
            'availableCategories' => $this->availableCategories,
            'stats' => $this->permissionStats,
        ]);
    }
}
