<?php

namespace App\Livewire\Sakon\Permissions;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

#[Layout('components.layouts.dashboard')]
#[Title('Edit Permission')]
class EditPermission extends Component
{
    use AuthorizesRequests;

    // Route model binding
    public Permission $permission;

    // Form properties
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('nullable|string|max:500')]
    public $description = '';

    #[Validate('required|string|in:web,api')]
    public $guard_name = 'web';

    #[Validate('nullable|array')]
    public $selectedRoles = [];

    // UI state
    public $isProcessing = false;
    public $showSuccessMessage = false;
    public $successMessage = '';
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
                'unique:permissions,name,' . $this->permission->id,
            ],
            'description' => 'nullable|string|max:500',
            'guard_name' => 'required|string|in:web,api',
            'selectedRoles' => 'nullable|array',
            'selectedRoles.*' => 'exists:roles,id',
        ];
    }

    protected $messages = [
        'name.required' => 'Permission name is required.',
        'name.unique' => 'This permission name already exists.',
        'name.max' => 'Permission name cannot exceed 255 characters.',
        'description.max' => 'Description cannot exceed 500 characters.',
        'guard_name.required' => 'Guard name is required.',
        'guard_name.in' => 'Guard name must be either web or api.',
    ];

    // Lifecycle hooks
    public function mount(Permission $permission)
    {
        $this->permission = $permission->load('roles');
        $this->authorize('update', $this->permission);

        // Load permission data
        $this->name = $this->permission->name;
        $this->description = $this->permission->description ?? '';
        $this->guard_name = $this->permission->guard_name;
        $this->selectedRoles = $this->permission->roles->pluck('id')->toArray();

        // Store original data for change detection
        $this->originalData = [
            'name' => $this->name,
            'description' => $this->description,
            'guard_name' => $this->guard_name,
            'selectedRoles' => $this->selectedRoles,
        ];
    }

    public function hydrate()
    {
        $this->authorize('update', $this->permission);
    }

    // Real-time validation and change detection
    public function updatedName()
    {
        $this->validateOnly('name');
        $this->checkForChanges();
    }

    public function updatedDescription()
    {
        $this->validateOnly('description');
        $this->checkForChanges();
    }

    public function updatedGuardName()
    {
        $this->validateOnly('guard_name');
        $this->checkForChanges();
    }

    public function updatedSelectedRoles()
    {
        $this->validateOnly('selectedRoles');
        $this->checkForChanges();
    }

    // Helper methods
    private function checkForChanges()
    {
        $this->hasChanges = (
            $this->name !== $this->originalData['name'] ||
            $this->description !== $this->originalData['description'] ||
            $this->guard_name !== $this->originalData['guard_name'] ||
            array_diff($this->selectedRoles, $this->originalData['selectedRoles']) ||
            array_diff($this->originalData['selectedRoles'], $this->selectedRoles)
        );
    }

    // Main update method
    public function update()
    {
        $this->authorize('update', $this->permission);

        $this->isProcessing = true;

        try {
            $this->validate();

            DB::transaction(function () {
                // Update the permission
                $this->permission->update([
                    'name' => trim($this->name),
                    'guard_name' => $this->guard_name,
                    // Note: Spatie Permission doesn't have description by default
                    // You may need to add a migration to add description column
                ]);

                // Sync roles
                $roles = Role::select('id', 'name')->whereIn('id', $this->selectedRoles)->get();
                $this->permission->syncRoles($roles);

                // Log activity using Laravel's built-in logging
                \Log::info('Permission updated', [
                    'permission_id' => $this->permission->id,
                    'permission_name' => $this->permission->name,
                    'guard_name' => $this->permission->guard_name,
                    'description' => $this->description,
                    'roles_count' => count($this->selectedRoles),
                    'updated_by' => auth()->user()->id,
                    'updated_by_name' => auth()->user()->name,
                    'changes' => [
                        'name_changed' => $this->name !== $this->originalData['name'],
                        'guard_changed' => $this->guard_name !== $this->originalData['guard_name'],
                        'description_changed' => $this->description !== $this->originalData['description'],
                        'roles_changed' => count(array_diff($this->selectedRoles, $this->originalData['selectedRoles'])) > 0 ||
                                          count(array_diff($this->originalData['selectedRoles'], $this->selectedRoles)) > 0,
                    ],
                ]);
            });

            // Update original data
            $this->originalData = [
                'name' => $this->name,
                'description' => $this->description,
                'guard_name' => $this->guard_name,
                'selectedRoles' => $this->selectedRoles,
            ];
            $this->hasChanges = false;

            // Success feedback with session flash for redirect
            session()->flash('success', "Permission '{$this->name}' has been updated successfully with " . count($this->selectedRoles) . " role(s).");

            // Dispatch event for real-time updates
            $this->dispatch('permissionUpdated', [
                'id' => $this->permission->id,
                'name' => $this->name,
                'guard_name' => $this->guard_name,
                'roles_count' => count($this->selectedRoles)
            ]);

            // Redirect to permissions index
            return $this->redirect(route('administrator.permissions.index'), navigate: true);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors are automatically handled by Livewire
            throw $e;
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update permission: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    // Form management
    public function resetForm()
    {
        $this->name = $this->originalData['name'];
        $this->description = $this->originalData['description'];
        $this->guard_name = $this->originalData['guard_name'];
        $this->selectedRoles = $this->originalData['selectedRoles'];
        $this->hasChanges = false;
        $this->resetValidation();
    }

    public function cancel()
    {
        return $this->redirect(route('administrator.permissions.index'), navigate: true);
    }

    // Quick actions
    public function duplicatePermission()
    {
        return $this->redirect(route('administrator.permissions.create', [
            'duplicate' => $this->permission->id
        ]), navigate: true);
    }

    // Computed properties with proper eager loading
    public function getAvailableRolesProperty()
    {
        return Role::withCount('users')
                   ->orderBy('name')
                   ->get();
    }

    public function getSelectedRolesCountProperty()
    {
        return count($this->selectedRoles);
    }

    public function getCanUpdateProperty()
    {
        return !$this->isProcessing &&
               !empty(trim($this->name)) &&
               in_array($this->guard_name, ['web', 'api']) &&
               $this->hasChanges;
    }

    public function getRolesWithPermissionCountProperty()
    {
        return $this->permission->roles()->withCount('users')->get();
    }

    public function getAddedRolesProperty()
    {
        return array_diff($this->selectedRoles, $this->originalData['selectedRoles']);
    }

    public function getRemovedRolesProperty()
    {
        return array_diff($this->originalData['selectedRoles'], $this->selectedRoles);
    }

    public function getPermissionCategoryProperty()
    {
        $parts = explode('-', $this->permission->name);
        return count($parts) > 1 ? $parts[1] : 'general';
    }

    public function getPermissionStatsProperty()
    {
        return [
            'roles_count' => $this->permission->roles()->count(),
            'users_affected' => $this->permission->roles()->withCount('users')->get()->sum('users_count'),
            'created_date' => $this->permission->created_at->format('M d, Y'),
            'updated_date' => $this->permission->updated_at->format('M d, Y'),
            'created_datetime' => $this->permission->created_at->format('M d, Y \a\t H:i'),
            'updated_datetime' => $this->permission->updated_at->format('M d, Y \a\t H:i'),
            'created_human' => $this->permission->created_at->diffForHumans(),
            'updated_human' => $this->permission->updated_at->diffForHumans(),
        ];
    }

    public function getPermissionPreviewProperty()
    {
        return [
            'name' => $this->name,
            'guard_name' => $this->guard_name,
            'description' => $this->description ?: 'No description provided',
            'roles_count' => count($this->selectedRoles),
            'category' => $this->permissionCategory,
            'original_name' => $this->originalData['name'],
        ];
    }

    // Permission analysis
    public function getRelatedPermissionsProperty()
    {
        $category = $this->permissionCategory;
        if ($category === 'general') {
            return collect();
        }

        return Permission::where('name', 'like', '%-' . $category . '%')
                        ->where('id', '!=', $this->permission->id)
                        ->orderBy('name')
                        ->limit(5)
                        ->get();
    }

    // Render component
    public function render()
    {
        return view('livewire.sakon.permissions.edit-permission');
    }
}
