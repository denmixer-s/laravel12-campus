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
#[Title('Create Permission')]
class CreatePermission extends Component
{
    use AuthorizesRequests;

    // Form properties
    #[Validate('required|string|max:255|unique:permissions,name')]
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
    public $permissionCategories = [];
    public $selectedCategory = '';

    // Auto-generation settings
    public $autoGenerateFromCategory = false;
    public $selectedActions = [];
    public $availableActions = ['view', 'create', 'edit', 'update', 'delete', 'restore', 'force-delete'];

    // Real-time validation messages
    protected $messages = [
        'name.required' => 'Permission name is required.',
        'name.unique' => 'This permission name already exists.',
        'name.max' => 'Permission name cannot exceed 255 characters.',
        'description.max' => 'Description cannot exceed 500 characters.',
        'guard_name.required' => 'Guard name is required.',
        'guard_name.in' => 'Guard name must be either web or api.',
    ];

    // Lifecycle hooks
    public function mount()
    {
        $this->authorize('create', Permission::class);
        $this->loadPermissionCategories();
    }

    public function hydrate()
    {
        $this->authorize('create', Permission::class);
    }

    // Real-time validation on property updates
    public function updatedName()
    {
        $this->validateOnly('name');
    }

    public function updatedDescription()
    {
        $this->validateOnly('description');
    }

    public function updatedGuardName()
    {
        $this->validateOnly('guard_name');
    }

    public function updatedSelectedRoles()
    {
        $this->validateOnly('selectedRoles');
    }

    public function updatedSelectedCategory()
    {
        if ($this->autoGenerateFromCategory && $this->selectedCategory) {
            $this->generatePermissionName();
        }
    }

    public function updatedSelectedActions()
    {
        if ($this->autoGenerateFromCategory && $this->selectedCategory) {
            $this->generatePermissionName();
        }
    }

    public function updatedAutoGenerateFromCategory()
    {
        if ($this->autoGenerateFromCategory) {
            $this->generatePermissionName();
        } else {
            $this->name = '';
        }
    }

    // FIXED: Helper methods with proper eager loading to prevent lazy loading errors
    private function loadPermissionCategories()
    {
        // Get existing permission categories from database - only select needed columns
        $existingPermissions = Permission::select('name')->orderBy('name')->get();

        $categories = $existingPermissions->map(function ($permission) {
            $parts = explode('-', $permission->name);
            return count($parts) > 1 ? $parts[1] : 'general';
        })->unique()->sort()->values();

        // Add common categories that might not exist yet
        $commonCategories = [
            'user', 'role', 'permission', 'post', 'page', 'product',
            'order', 'payment', 'setting', 'report', 'admin', 'api'
        ];

        $this->permissionCategories = $categories->merge($commonCategories)->unique()->sort()->values()->toArray();
    }

    private function generatePermissionName()
    {
        if (!$this->selectedCategory) {
            return;
        }

        if (count($this->selectedActions) === 1) {
            // Single action: "view-user", "create-post"
            $this->name = $this->selectedActions[0] . '-' . Str::singular($this->selectedCategory);
        } elseif (count($this->selectedActions) > 1) {
            // Multiple actions: generate based on first selected or most common pattern
            $this->name = $this->selectedActions[0] . '-' . Str::singular($this->selectedCategory);
        } else {
            // No specific action: "manage-user"
            $this->name = 'manage-' . Str::singular($this->selectedCategory);
        }
    }

    // Permission generation methods
    public function generateCrudPermissions()
    {
        if (!$this->selectedCategory) {
            session()->flash('error', 'Please select a category first.');
            return;
        }

        $category = Str::singular($this->selectedCategory);
        $permissions = [
            "view-{$category}",
            "create-{$category}",
            "edit-{$category}",
            "delete-{$category}"
        ];

        return $this->createMultiplePermissions($permissions);
    }

    public function generateFullCrudPermissions()
    {
        if (!$this->selectedCategory) {
            session()->flash('error', 'Please select a category first.');
            return;
        }

        $category = Str::singular($this->selectedCategory);
        $permissions = [
            "view-{$category}",
            "view-any-{$category}",
            "create-{$category}",
            "edit-{$category}",
            "update-{$category}",
            "delete-{$category}",
            "restore-{$category}",
            "force-delete-{$category}"
        ];

        return $this->createMultiplePermissions($permissions);
    }

    private function createMultiplePermissions($permissionNames)
    {
        $this->isProcessing = true;
        $created = [];
        $skipped = [];

        try {
            DB::transaction(function () use ($permissionNames, &$created, &$skipped) {
                foreach ($permissionNames as $permissionName) {
                    if (Permission::where('name', $permissionName)->exists()) {
                        $skipped[] = $permissionName;
                        continue;
                    }

                    $permission = Permission::create([
                        'name' => $permissionName,
                        'guard_name' => $this->guard_name,
                    ]);

                    // Assign to selected roles if any
                    if (!empty($this->selectedRoles)) {
                        $roles = Role::select('id', 'name')->whereIn('id', $this->selectedRoles)->get();
                        $permission->assignRole($roles);
                    }

                    $created[] = $permissionName;
                }

                // Log activity
                \Log::info('Multiple permissions created', [
                    'created_permissions' => $created,
                    'skipped_permissions' => $skipped,
                    'guard_name' => $this->guard_name,
                    'created_by' => auth()->user()->id,
                    'created_by_name' => auth()->user()->name,
                ]);
            });

            $message = count($created) . ' permission(s) created successfully.';
            if (count($skipped) > 0) {
                $message .= ' ' . count($skipped) . ' permission(s) already existed and were skipped.';
            }

            session()->flash('success', $message);
            $this->dispatch('permissionsCreated', ['count' => count($created)]);

            // Reset form
            $this->resetForm();

            return $this->redirect(route('administrator.permissions.index'), navigate: true);

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create permissions: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    // Main create method
    public function create()
    {
        $this->authorize('create', Permission::class);

        $this->isProcessing = true;

        try {
            $this->validate();

            DB::transaction(function () {
                // Create the permission
                $permission = Permission::create([
                    'name' => trim($this->name),
                    'guard_name' => $this->guard_name
                ]);

                // Assign to selected roles if any
                if (!empty($this->selectedRoles)) {
                    $roles = Role::select('id', 'name')->whereIn('id', $this->selectedRoles)->get();
                    $permission->assignRole($roles);
                }

                // Log activity using Laravel's built-in logging
                \Log::info('Permission created', [
                    'permission_name' => $permission->name,
                    'guard_name' => $permission->guard_name,
                    'description' => $this->description,
                    'assigned_roles' => count($this->selectedRoles),
                    'created_by' => auth()->user()->id,
                    'created_by_name' => auth()->user()->name,
                ]);
            });

            // Success feedback
            $this->showSuccessMessage = true;
            $this->successMessage = "Permission '{$this->name}' has been created successfully" .
                (count($this->selectedRoles) > 0 ? " and assigned to " . count($this->selectedRoles) . " role(s)." : ".");

            // Store success message in session for the redirect
            session()->flash('success', $this->successMessage);

            // Dispatch event for real-time updates
            $this->dispatch('permissionCreated', [
                'name' => $this->name,
                'guard_name' => $this->guard_name,
                'roles_count' => count($this->selectedRoles)
            ]);

            // Immediate redirect to permissions index
            return $this->redirect(route('administrator.permissions.index'), navigate: true);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors are automatically handled by Livewire
            throw $e;
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create permission: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    // Form management
    public function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->guard_name = 'web';
        $this->selectedRoles = [];
        $this->selectedCategory = '';
        $this->selectedActions = [];
        $this->autoGenerateFromCategory = false;
        $this->showSuccessMessage = false;
        $this->successMessage = '';
        $this->resetValidation();
    }

    public function cancel()
    {
        return $this->redirect(route('administrator.permissions.index'), navigate: true);
    }

    // Quick presets for common permission types
    public function applyPreset($preset)
    {
        switch ($preset) {
            case 'admin':
                $this->selectedCategory = 'admin';
                $this->selectedActions = ['view', 'create', 'edit', 'delete'];
                $this->name = 'admin-access';
                $this->description = 'Full administrative access to the system';
                break;

            case 'user-management':
                $this->selectedCategory = 'user';
                $this->selectedActions = ['view', 'create', 'edit', 'delete'];
                $this->name = 'manage-user';
                $this->description = 'Manage user accounts and profiles';
                break;

            case 'content':
                $this->selectedCategory = 'post';
                $this->selectedActions = ['view', 'create', 'edit'];
                $this->name = 'manage-content';
                $this->description = 'Create and edit content';
                break;

            case 'api':
                $this->guard_name = 'api';
                $this->selectedCategory = 'api';
                $this->name = 'api-access';
                $this->description = 'Access to API endpoints';
                break;

            default:
                $this->resetForm();
                break;
        }

        $this->autoGenerateFromCategory = false;
    }

    // FIXED: Get computed properties with proper eager loading to prevent lazy loading errors
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

    public function getCanCreateProperty()
    {
        return !$this->isProcessing &&
               !empty(trim($this->name)) &&
               in_array($this->guard_name, ['web', 'api']);
    }

    public function getPermissionPreviewProperty()
    {
        if (!$this->name) {
            return null;
        }

        return [
            'name' => $this->name,
            'guard_name' => $this->guard_name,
            'description' => $this->description ?: 'No description provided',
            'roles_count' => count($this->selectedRoles),
            'category' => $this->selectedCategory ?: 'Uncategorized'
        ];
    }

    // Render component
    public function render()
    {
        return view('livewire.sakon.permissions.create-permission');
    }
}
