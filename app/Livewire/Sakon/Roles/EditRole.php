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
#[Title('Edit Role')]
class EditRole extends Component
{
    use AuthorizesRequests;

    // Route model binding
    public Role $role;

    // Form properties
    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('required|array|min:1')]
    public $selectedPermissions = [];

    // UI state
    public $isProcessing = false;
    public $showSuccessMessage = false;
    public $successMessage = '';
    public $selectAll = false;
    public $permissionGroups = [];
    public $originalData = [];
    public $hasChanges = false;

    // Real-time validation messages
    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:roles,name,' . $this->role->id,
            ],
            'selectedPermissions' => 'required|array|min:1',
            'selectedPermissions.*' => 'exists:permissions,id',
        ];
    }

    protected $messages = [
        'name.required' => 'Role name is required.',
        'name.unique' => 'This role name already exists.',
        'name.max' => 'Role name cannot exceed 255 characters.',
        'selectedPermissions.required' => 'Please select at least one permission.',
        'selectedPermissions.min' => 'Please select at least one permission.',
    ];

    // Lifecycle hooks
    public function mount(Role $role)
    {
        $this->role = $role;
        
        // Prevent editing Super Admin role
        if ($this->role->name === 'Super Admin') {
            session()->flash('error', 'Super Admin role cannot be edited.');
            return $this->redirect(route('administrator.roles.index'), navigate: true);
        }

        $this->authorize('update', $this->role);
        
        // Load role data
        $this->name = $this->role->name;
        $this->selectedPermissions = $this->role->permissions->pluck('id')->toArray();
        
        // Store original data for change detection
        $this->originalData = [
            'name' => $this->name,
            'selectedPermissions' => $this->selectedPermissions,
        ];
        
        $this->loadPermissionGroups();
        $this->updateSelectAllState();
    }

    public function hydrate()
    {
        $this->authorize('update', $this->role);
    }

    // Real-time validation and change detection
    public function updatedName()
    {
        $this->validateOnly('name');
        $this->checkForChanges();
    }

    public function updatedSelectedPermissions()
    {
        $this->validateOnly('selectedPermissions');
        $this->updateSelectAllState();
        $this->checkForChanges();
    }

    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedPermissions = Permission::pluck('id')->toArray();
        } else {
            $this->selectedPermissions = [];
        }
        $this->checkForChanges();
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

    private function checkForChanges()
    {
        $this->hasChanges = (
            $this->name !== $this->originalData['name'] ||
            array_diff($this->selectedPermissions, $this->originalData['selectedPermissions']) ||
            array_diff($this->originalData['selectedPermissions'], $this->selectedPermissions)
        );
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
        $this->checkForChanges();
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

    // Main update method
    public function update()
    {
        $this->authorize('update', $this->role);
        
        // Check if Super Admin role is being edited (double check)
        if ($this->role->name === 'Super Admin') {
            session()->flash('error', 'Super Admin role cannot be edited.');
            return;
        }
        
        $this->isProcessing = true;
        
        try {
            $this->validate();
            
            DB::transaction(function () {
                // Update the role
                $this->role->update([
                    'name' => trim($this->name),
                ]);
                
                // Sync permissions
                $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();
                $this->role->syncPermissions($permissions);
                
                // Log activity using Laravel's built-in logging
                \Log::info('Role updated', [
                    'role_id' => $this->role->id,
                    'role_name' => $this->role->name,
                    'permissions_count' => count($this->selectedPermissions),
                    'updated_by' => auth()->user()->id,
                    'updated_by_name' => auth()->user()->name,
                    'changes' => [
                        'name_changed' => $this->name !== $this->originalData['name'],
                        'permissions_changed' => count(array_diff($this->selectedPermissions, $this->originalData['selectedPermissions'])) > 0 ||
                                               count(array_diff($this->originalData['selectedPermissions'], $this->selectedPermissions)) > 0,
                    ],
                ]);
            });
            
            // Update original data
            $this->originalData = [
                'name' => $this->name,
                'selectedPermissions' => $this->selectedPermissions,
            ];
            $this->hasChanges = false;
            
            // Success feedback with session flash for redirect
            session()->flash('success', "Role '{$this->name}' has been updated successfully with " . count($this->selectedPermissions) . " permissions.");
            
            // Dispatch event for real-time updates
            $this->dispatch('roleUpdated', [
                'id' => $this->role->id,
                'name' => $this->name,
                'permissions_count' => count($this->selectedPermissions)
            ]);
            
            // Redirect to roles index
            return $this->redirect(route('administrator.roles.index'), navigate: true);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors are automatically handled by Livewire
            throw $e;
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update role: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    // Form management
    public function resetForm()
    {
        $this->name = $this->originalData['name'];
        $this->selectedPermissions = $this->originalData['selectedPermissions'];
        $this->updateSelectAllState();
        $this->hasChanges = false;
        $this->resetValidation();
    }

    public function cancel()
    {
        return $this->redirect(route('administrator.roles.index'), navigate: true);
    }

    // Permission search functionality
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

    // Computed properties
    public function getSelectedPermissionsCountProperty()
    {
        return count($this->selectedPermissions);
    }

    public function getTotalPermissionsCountProperty()
    {
        return Permission::count();
    }

    public function getCanUpdateProperty()
    {
        return !$this->isProcessing && !empty(trim($this->name)) && count($this->selectedPermissions) > 0 && $this->hasChanges;
    }

    public function getUsersWithRoleCountProperty()
    {
        return $this->role->users()->count();
    }

    public function getAddedPermissionsProperty()
    {
        return array_diff($this->selectedPermissions, $this->originalData['selectedPermissions']);
    }

    public function getRemovedPermissionsProperty()
    {
        return array_diff($this->originalData['selectedPermissions'], $this->selectedPermissions);
    }

    // Quick actions
    public function duplicateRole()
    {
        return $this->redirect(route('administrator.roles.create', [
            'duplicate' => $this->role->id
        ]), navigate: true);
    }

    // Render component
    public function render()
    {
        return view('livewire.sakon.roles.edit-role');
    }
}