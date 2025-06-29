<?php

namespace App\Livewire\Sakon\Roles;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.dashboard')]
#[Title('Create Role')]
class CreateRole extends Component
{
    use AuthorizesRequests;

    // Form properties
    #[Rule('required|string|max:255|unique:roles,name')]
    public $name = '';

    #[Rule('required|array|min:1')]
    public $selectedPermissions = [];

    // UI state
    public $isProcessing = false;
    public $showSuccessMessage = false;
    public $successMessage = '';
    public $selectAll = false;
    public $permissionGroups = [];

    // Real-time validation messages
    protected $messages = [
        'name.required' => 'Role name is required.',
        'name.unique' => 'This role name already exists.',
        'name.max' => 'Role name cannot exceed 255 characters.',
        'selectedPermissions.required' => 'Please select at least one permission.',
        'selectedPermissions.min' => 'Please select at least one permission.',
    ];

    // Lifecycle hooks
    public function mount()
    {
        $this->authorize('create', Role::class);
        $this->loadPermissionGroups();
    }

    public function hydrate()
    {
        $this->authorize('create', Role::class);
    }

    // Real-time validation on property updates
    public function updatedName()
    {
        $this->validateOnly('name');
    }

    public function updatedSelectedPermissions()
    {
        $this->validateOnly('selectedPermissions');
        $this->updateSelectAllState();
    }

    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedPermissions = Permission::pluck('id')->toArray();
        } else {
            $this->selectedPermissions = [];
        }
    }

    // Helper methods
    private function loadPermissionGroups()
    {
        $permissions = Permission::orderBy('name')->get();
        
        $this->permissionGroups = $permissions->groupBy(function ($permission) {
            $parts = explode('-', $permission->name);
            return count($parts) > 1 ? $parts[1] : 'general';
        })->map(function ($group) {
            return $group->sortBy('name');
        });
    }

    private function updateSelectAllState()
    {
        $totalPermissions = Permission::count();
        $this->selectAll = count($this->selectedPermissions) === $totalPermissions;
    }

    // Permission group management
    public function togglePermissionGroup($groupName)
    {
        $groupPermissions = $this->permissionGroups[$groupName] ?? collect();
        $groupPermissionIds = $groupPermissions->pluck('id')->toArray();
        
        $isGroupSelected = !array_diff($groupPermissionIds, $this->selectedPermissions);
        
        if ($isGroupSelected) {
            // Deselect all permissions in this group
            $this->selectedPermissions = array_diff($this->selectedPermissions, $groupPermissionIds);
        } else {
            // Select all permissions in this group
            $this->selectedPermissions = array_unique(array_merge($this->selectedPermissions, $groupPermissionIds));
        }
        
        $this->updateSelectAllState();
    }

    public function isGroupSelected($groupName)
    {
        $groupPermissions = $this->permissionGroups[$groupName] ?? collect();
        $groupPermissionIds = $groupPermissions->pluck('id')->toArray();
        
        return !array_diff($groupPermissionIds, $this->selectedPermissions);
    }

    public function isGroupPartiallySelected($groupName)
    {
        $groupPermissions = $this->permissionGroups[$groupName] ?? collect();
        $groupPermissionIds = $groupPermissions->pluck('id')->toArray();
        
        $selectedInGroup = array_intersect($groupPermissionIds, $this->selectedPermissions);
        
        return count($selectedInGroup) > 0 && count($selectedInGroup) < count($groupPermissionIds);
    }

    // Main create method
    public function create()
    {
        $this->authorize('create', Role::class);
        
        $this->isProcessing = true;
        
        try {
            $this->validate();
            
            DB::transaction(function () {
                // Create the role
                $role = Role::create([
                    'name' => trim($this->name),
                    'guard_name' => 'web'
                ]);
                
                // Attach permissions
                $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();
                $role->syncPermissions($permissions);
                
                // Log activity using Laravel's built-in logging
                \Log::info('Role created', [
                    'role_name' => $role->name,
                    'permissions_count' => count($this->selectedPermissions),
                    'created_by' => auth()->user()->id,
                    'created_by_name' => auth()->user()->name,
                ]);
            });
            
            // Success feedback
            $this->showSuccessMessage = true;
            $this->successMessage = "Role '{$this->name}' has been created successfully with " . count($this->selectedPermissions) . " permissions.";
            
            // Store success message in session for the redirect
            session()->flash('success', "Role '{$this->name}' has been created successfully with " . count($this->selectedPermissions) . " permissions.");
            
            // Dispatch event for real-time updates
            $this->dispatch('roleCreated', [
                'name' => $this->name,
                'permissions_count' => count($this->selectedPermissions)
            ]);
            
            // Immediate redirect to roles index
            return $this->redirect(route('administrator.roles.index'), navigate: true);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors are automatically handled by Livewire
            throw $e;
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create role: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    // Form management
    public function resetForm()
    {
        $this->name = '';
        $this->selectedPermissions = [];
        $this->selectAll = false;
        $this->showSuccessMessage = false;
        $this->successMessage = '';
        $this->resetValidation();
    }

    public function cancel()
    {
        return $this->redirect(route('administrator.roles.index'), navigate: true);
    }

    // Quick presets for common role types
    public function applyPreset($preset)
    {
        switch ($preset) {
            case 'admin':
                $this->name = 'Administrator';
                $this->selectedPermissions = Permission::where('name', 'not like', '%delete%')
                    ->where('name', '!=', 'manage-system')
                    ->pluck('id')->toArray();
                break;
            
            case 'editor':
                $this->name = 'Content Editor';
                $this->selectedPermissions = Permission::where('name', 'like', '%content%')
                    ->orWhere('name', 'view-user')
                    ->pluck('id')->toArray();
                break;
            
            case 'viewer':
                $this->name = 'Viewer';
                $this->selectedPermissions = Permission::where('name', 'like', 'view-%')
                    ->pluck('id')->toArray();
                break;
            
            default:
                $this->resetForm();
                break;
        }
    }

    // Real-time search for permissions
    public $permissionSearch = '';
    
    public function updatedPermissionSearch()
    {
        $this->loadFilteredPermissions();
    }
    
    public function loadFilteredPermissions()
    {
        $query = Permission::query();
        
        if ($this->permissionSearch) {
            $query->where('name', 'like', '%' . $this->permissionSearch . '%');
        }
        
        $permissions = $query->orderBy('name')->get();
        
        $this->permissionGroups = $permissions->groupBy(function ($permission) {
            $parts = explode('-', $permission->name);
            return count($parts) > 1 ? $parts[1] : 'general';
        })->map(function ($group) {
            return $group->sortBy('name');
        });
    }

    // Get computed properties
    public function getSelectedPermissionsCountProperty()
    {
        return count($this->selectedPermissions);
    }

    public function getTotalPermissionsCountProperty()
    {
        return Permission::count();
    }

    public function getCanCreateProperty()
    {
        return !$this->isProcessing && !empty(trim($this->name)) && count($this->selectedPermissions) > 0;
    }

    // Render component
    public function render()
    {
        return view('livewire.sakon.roles.create-role');
    }
}