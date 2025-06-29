<?php

namespace App\Livewire\Sakon\Menus;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Rule;
use App\Models\Menu;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

#[Layout('components.layouts.dashboard')]
#[Title('Create Menu')]
class CreateMenu extends Component
{
    use AuthorizesRequests;

    // Form properties
    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('nullable|string|max:500')]
    public $url = '';

    #[Rule('nullable|string|max:255')]
    public $route_name = '';

    #[Rule('nullable|exists:menus,id')]
    public $parent_id = '';

    #[Rule('required|integer|min:0')]
    public $sort_order = 0;

    #[Rule('nullable|string|max:100')]
    public $icon = '';

    #[Rule('required|in:header,footer,sidebar,both,mobile')]
    public $show = 'header';

    #[Rule('required|in:_self,_blank,_parent,_top')]
    public $target = '_self';

    #[Rule('nullable|string|max:255')]
    public $permission = '';

    #[Rule('nullable|string|max:1000')]
    public $description = '';

    #[Rule('boolean')]
    public $is_active = true;

    // UI state
    public $isProcessing = false;
    public $showSuccessMessage = false;
    public $successMessage = '';
    public $urlType = 'manual'; // 'manual' or 'route'
    public $availableRoutes = [];
    public $availablePermissions = [];
    public $parentMenus = [];

    // Permission search functionality
    public $permissionSearch = '';

    // Real-time validation messages
    protected $messages = [
        'name.required' => 'Menu name is required.',
        'name.max' => 'Menu name cannot exceed 255 characters.',
        'url.max' => 'URL cannot exceed 500 characters.',
        'route_name.max' => 'Route name cannot exceed 255 characters.',
        'parent_id.exists' => 'Selected parent menu does not exist.',
        'sort_order.required' => 'Sort order is required.',
        'sort_order.integer' => 'Sort order must be a number.',
        'sort_order.min' => 'Sort order must be 0 or greater.',
        'show.required' => 'Display location is required.',
        'show.in' => 'Invalid display location selected.',
        'target.required' => 'Link target is required.',
        'target.in' => 'Invalid link target selected.',
    ];

    // Lifecycle hooks
    public function mount()
    {
        $this->authorize('create', Menu::class);
        $this->loadFormData();
        
        // Handle duplication
        if (request('duplicate')) {
            $this->duplicateFromExisting(request('duplicate'));
        }
    }

    public function hydrate()
    {
        $this->authorize('create', Menu::class);
    }

    // Real-time validation on property updates
    public function updatedName()
    {
        $this->validateOnly('name');
    }

    public function updatedUrl()
    {
        $this->validateOnly('url');
        if ($this->urlType === 'manual' && !empty($this->url)) {
            $this->route_name = '';
            $this->validateUrl();
        }
    }

    public function updatedRouteName()
    {
        $this->validateOnly('route_name');
        if ($this->urlType === 'route' && !empty($this->route_name)) {
            $this->url = '';
            $this->validateRoute();
        }
    }

    public function updatedParentId()
    {
        $this->validateOnly('parent_id');
        $this->updateSortOrder();
    }

    public function updatedSortOrder()
    {
        $this->validateOnly('sort_order');
    }

    public function updatedIcon()
    {
        $this->validateOnly('icon');
    }

    public function updatedShow()
    {
        $this->validateOnly('show');
    }

    public function updatedTarget()
    {
        $this->validateOnly('target');
    }

    public function updatedPermission()
    {
        $this->validateOnly('permission');
    }

    public function updatedDescription()
    {
        $this->validateOnly('description');
    }

    public function updatedIsActive()
    {
        // No validation needed for boolean
    }

    public function updatedUrlType()
    {
        if ($this->urlType === 'route') {
            $this->url = '';
        } else {
            $this->route_name = '';
        }
        $this->resetValidation(['url', 'route_name']);
    }

    public function updatedPermissionSearch()
    {
        $this->loadFilteredPermissions();
    }

    // Helper methods
    private function loadFormData()
    {
        // Load available routes
        $this->availableRoutes = $this->getNamedRoutes();
        
        // Load available permissions
        $this->availablePermissions = Permission::orderBy('name')->pluck('name', 'name')->toArray();
        
        // Load parent menus
        $this->parentMenus = Menu::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->pluck('name', 'id')
            ->toArray();
    }

    private function getNamedRoutes()
    {
        $routes = [];
        $routeCollection = Route::getRoutes();
        
        foreach ($routeCollection as $route) {
            $name = $route->getName();
            if ($name && !str_starts_with($name, 'ignition.') && !str_starts_with($name, '_')) {
                $routes[$name] = $name . ' (' . implode(', ', $route->methods()) . ')';
            }
        }
        
        ksort($routes);
        return $routes;
    }

    private function updateSortOrder()
    {
        if ($this->parent_id) {
            $maxOrder = Menu::where('parent_id', $this->parent_id)->max('sort_order') ?? -1;
            $this->sort_order = $maxOrder + 1;
        } else {
            $maxOrder = Menu::whereNull('parent_id')->max('sort_order') ?? -1;
            $this->sort_order = $maxOrder + 1;
        }
    }

    private function duplicateFromExisting($menuId)
    {
        try {
            $existingMenu = Menu::findOrFail($menuId);
            
            $this->name = $existingMenu->name . ' (Copy)';
            $this->url = $existingMenu->url;
            $this->route_name = $existingMenu->route_name;
            $this->parent_id = $existingMenu->parent_id;
            $this->icon = $existingMenu->icon;
            $this->show = $existingMenu->show;
            $this->target = $existingMenu->target;
            $this->permission = $existingMenu->permission;
            $this->description = $existingMenu->description;
            $this->is_active = $existingMenu->is_active;
            
            $this->urlType = !empty($existingMenu->route_name) ? 'route' : 'manual';
            $this->updateSortOrder();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to duplicate menu: ' . $e->getMessage());
        }
    }

    // Icon selection
    public function selectIcon($iconName)
    {
        $this->icon = $iconName;
    }

    public function loadFilteredPermissions()
    {
        if ($this->permissionSearch) {
            $this->availablePermissions = Permission::where('name', 'like', '%' . $this->permissionSearch . '%')
                ->orderBy('name')
                ->pluck('name', 'name')
                ->toArray();
        } else {
            $this->availablePermissions = Permission::orderBy('name')->pluck('name', 'name')->toArray();
        }
    }

    // Validation helpers
    public function validateUrl()
    {
        if ($this->urlType === 'manual' && !empty($this->url)) {
            // Basic URL validation
            if (!filter_var($this->url, FILTER_VALIDATE_URL) && !str_starts_with($this->url, '/')) {
                $this->addError('url', 'Please enter a valid URL or path starting with /');
                return false;
            }
            
            // Check if URL is already used by another menu
            $existingMenu = Menu::where('url', $this->url)->first();
            if ($existingMenu) {
                $this->addError('url', 'This URL is already used by another menu: ' . $existingMenu->name);
                return false;
            }
        }
        return true;
    }

    public function validateRoute()
    {
        if ($this->urlType === 'route' && !empty($this->route_name)) {
            // Check if route exists
            try {
                route($this->route_name);
            } catch (\Exception $e) {
                $this->addError('route_name', 'The selected route does not exist.');
                return false;
            }
            
            // Check if route is already used by another menu
            $existingMenu = Menu::where('route_name', $this->route_name)->first();
            if ($existingMenu) {
                $this->addError('route_name', 'This route is already used by another menu: ' . $existingMenu->name);
                return false;
            }
        }
        return true;
    }

    // Quick presets
    public function applyPreset($preset)
    {
        switch ($preset) {
            case 'dashboard':
                $this->name = 'Dashboard';
                $this->route_name = 'dashboard';
                $this->urlType = 'route';
                $this->icon = 'home';
                $this->show = 'sidebar';
                $this->description = 'Main dashboard overview';
                break;
            
            case 'users':
                $this->name = 'Users';
                $this->route_name = 'administrator.users.index';
                $this->urlType = 'route';
                $this->icon = 'users';
                $this->permission = 'view-user';
                $this->show = 'sidebar';
                $this->description = 'Manage user accounts';
                break;
            
            case 'settings':
                $this->name = 'Settings';
                $this->route_name = 'administrator.settings.index';
                $this->urlType = 'route';
                $this->icon = 'settings';
                $this->permission = 'manage-settings';
                $this->show = 'sidebar';
                $this->description = 'System configuration';
                break;
            
            case 'external':
                $this->name = 'External Link';
                $this->url = 'https://example.com';
                $this->urlType = 'manual';
                $this->target = '_blank';
                $this->icon = 'external-link';
                $this->description = 'Link to external website';
                break;
            
            default:
                $this->resetForm();
                break;
        }
        
        $this->updateSortOrder();
    }

    // Main create method
    public function create()
    {
        $this->authorize('create', Menu::class);
        
        $this->isProcessing = true;
        
        try {
            $this->validate();
            
            DB::transaction(function () {
                // Prepare data
                $data = [
                    'name' => trim($this->name),
                    'url' => $this->urlType === 'manual' ? trim($this->url) : null,
                    'route_name' => $this->urlType === 'route' ? trim($this->route_name) : null,
                    'parent_id' => $this->parent_id ?: null,
                    'sort_order' => $this->sort_order,
                    'icon' => trim($this->icon) ?: null,
                    'show' => $this->show,
                    'target' => $this->target,
                    'permission' => trim($this->permission) ?: null,
                    'description' => trim($this->description) ?: null,
                    'is_active' => $this->is_active,
                ];
                
                // Create the menu
                $menu = Menu::create($data);
                
                // Log activity
                \Log::info('Menu created', [
                    'menu_id' => $menu->id,
                    'menu_name' => $menu->name,
                    'created_by' => auth()->user()->id,
                    'created_by_name' => auth()->user()->name,
                ]);
            });
            
            // Success feedback
            $this->showSuccessMessage = true;
            $this->successMessage = "Menu '{$this->name}' has been created successfully.";
            
            // Store success message in session for the redirect
            session()->flash('success', "Menu '{$this->name}' has been created successfully.");
            
            // Dispatch event for real-time updates
            $this->dispatch('menuCreated', [
                'name' => $this->name,
            ]);
            
            // Immediate redirect to menus index
            return $this->redirect(route('administrator.menus.index'), navigate: true);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors are automatically handled by Livewire
            throw $e;
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create menu: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    // Form management
    public function resetForm()
    {
        $this->name = '';
        $this->url = '';
        $this->route_name = '';
        $this->parent_id = '';
        $this->sort_order = 0;
        $this->icon = '';
        $this->show = 'header';
        $this->target = '_self';
        $this->permission = '';
        $this->description = '';
        $this->is_active = true;
        $this->urlType = 'manual';
        $this->showSuccessMessage = false;
        $this->successMessage = '';
        $this->resetValidation();
        $this->updateSortOrder();
    }

    public function cancel()
    {
        return $this->redirect(route('administrator.menus.index'), navigate: true);
    }

    // Get computed properties
    public function getCanCreateProperty()
    {
        $hasName = !empty(trim($this->name));
        $hasValidUrl = ($this->urlType === 'manual' && !empty(trim($this->url))) || 
                       ($this->urlType === 'route' && !empty(trim($this->route_name)));
        
        return !$this->isProcessing && $hasName && $hasValidUrl;
    }

    public function getSelectedParentNameProperty()
    {
        if (!$this->parent_id) {
            return 'None (Top Level)';
        }
        
        return $this->parentMenus[$this->parent_id] ?? 'Unknown';
    }

    public function getUrlPreviewProperty()
    {
        if ($this->urlType === 'manual' && !empty($this->url)) {
            return $this->url;
        } elseif ($this->urlType === 'route' && !empty($this->route_name)) {
            try {
                return route($this->route_name);
            } catch (\Exception $e) {
                return 'Invalid route';
            }
        }
        
        return 'No URL specified';
    }

    public function render()
    {
        return view('livewire.sakon.menus.create-menu');
    }
}