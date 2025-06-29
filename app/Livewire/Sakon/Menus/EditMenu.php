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
#[Title('Edit Menu')]
class EditMenu extends Component
{
    use AuthorizesRequests;

    // Route model binding
    public Menu $menu;

    // Form properties
    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('nullable|string|max:500')]
    public $url = '';

    #[Rule('nullable|string|max:255')]
    public $route_name = '';

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
    public $urlType = 'manual';
    public $availableRoutes = [];
    public $availablePermissions = [];
    public $parentMenus = [];
    public $originalData = [];
    public $hasChanges = false;

    // Real-time validation messages
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'route_name' => 'nullable|string|max:255',
            'parent_id' => [
                'nullable',
                'exists:menus,id',
                function ($attribute, $value, $fail) {
                    if ($value == $this->menu->id) {
                        $fail('A menu cannot be its own parent.');
                    }
                    
                    // Check if the selected parent is not a descendant
                    if ($value && $this->isDescendant($value, $this->menu->id)) {
                        $fail('Cannot set a descendant menu as parent.');
                    }
                },
            ],
            'sort_order' => 'required|integer|min:0',
            'icon' => 'nullable|string|max:100',
            'show' => 'required|in:header,footer,sidebar,both,mobile',
            'target' => 'required|in:_self,_blank,_parent,_top',
            'permission' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ];
    }

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
    public function mount(Menu $menu)
    {
        $this->menu = $menu;
        $this->authorize('update', $this->menu);
        
        // Load menu data
        $this->name = $this->menu->name;
        $this->url = $this->menu->url;
        $this->route_name = $this->menu->route_name;
        $this->parent_id = $this->menu->parent_id;
        $this->sort_order = $this->menu->sort_order;
        $this->icon = $this->menu->icon;
        $this->show = $this->menu->show;
        $this->target = $this->menu->target;
        $this->permission = $this->menu->permission;
        $this->description = $this->menu->description;
        $this->is_active = $this->menu->is_active;
        
        $this->urlType = !empty($this->menu->route_name) ? 'route' : 'manual';
        
        // Store original data for change detection
        $this->originalData = [
            'name' => $this->name,
            'url' => $this->url,
            'route_name' => $this->route_name,
            'parent_id' => $this->parent_id,
            'sort_order' => $this->sort_order,
            'icon' => $this->icon,
            'show' => $this->show,
            'target' => $this->target,
            'permission' => $this->permission,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ];
        
        $this->loadFormData();
    }

    public function hydrate()
    {
        $this->authorize('update', $this->menu);
    }

    // Real-time validation and change detection
    public function updatedName()
    {
        $this->validateOnly('name');
        $this->checkForChanges();
    }

    public function updatedUrl()
    {
        $this->validateOnly('url');
        if ($this->urlType === 'manual' && !empty($this->url)) {
            $this->route_name = '';
        }
        $this->checkForChanges();
    }

    public function updatedRouteName()
    {
        $this->validateOnly('route_name');
        if ($this->urlType === 'route' && !empty($this->route_name)) {
            $this->url = '';
        }
        $this->checkForChanges();
    }

    public function updatedParentId()
    {
        $this->validateOnly('parent_id');
        $this->checkForChanges();
    }

    public function updatedSortOrder()
    {
        $this->validateOnly('sort_order');
        $this->checkForChanges();
    }

    public function updatedShow()
    {
        $this->validateOnly('show');
        $this->checkForChanges();
    }

    public function updatedTarget()
    {
        $this->validateOnly('target');
        $this->checkForChanges();
    }

    public function updatedPermission()
    {
        $this->validateOnly('permission');
        $this->checkForChanges();
    }

    public function updatedDescription()
    {
        $this->validateOnly('description');
        $this->checkForChanges();
    }

    public function updatedIsActive()
    {
        $this->checkForChanges();
    }

    public function updatedUrlType()
    {
        if ($this->urlType === 'route') {
            $this->url = '';
        } else {
            $this->route_name = '';
        }
        $this->checkForChanges();
    }

    // Helper methods
    private function loadFormData()
    {
        // Load available routes
        $this->availableRoutes = $this->getNamedRoutes();
        
        // Load available permissions
        $this->availablePermissions = Permission::orderBy('name')->pluck('name', 'name')->toArray();
        
        // Load parent menus (exclude self and descendants)
        $this->parentMenus = Menu::whereNull('parent_id')
            ->where('id', '!=', $this->menu->id)
            ->where('is_active', true)
            ->whereNotIn('id', $this->getDescendantIds($this->menu->id))
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

    private function checkForChanges()
    {
        $this->hasChanges = (
            $this->name !== $this->originalData['name'] ||
            $this->url !== $this->originalData['url'] ||
            $this->route_name !== $this->originalData['route_name'] ||
            $this->parent_id != $this->originalData['parent_id'] ||
            $this->sort_order != $this->originalData['sort_order'] ||
            $this->icon !== $this->originalData['icon'] ||
            $this->show !== $this->originalData['show'] ||
            $this->target !== $this->originalData['target'] ||
            $this->permission !== $this->originalData['permission'] ||
            $this->description !== $this->originalData['description'] ||
            $this->is_active !== $this->originalData['is_active']
        );
    }

    private function isDescendant($parentId, $menuId)
    {
        $descendants = $this->getDescendantIds($menuId);
        return in_array($parentId, $descendants);
    }

    private function getDescendantIds($menuId)
    {
        $descendants = [];
        $children = Menu::where('parent_id', $menuId)->get();
        
        foreach ($children as $child) {
            $descendants[] = $child->id;
            $descendants = array_merge($descendants, $this->getDescendantIds($child->id));
        }
        
        return $descendants;
    }

    // Icon selection
    public function selectIcon($iconName)
    {
        $this->icon = $iconName;
        $this->checkForChanges();
    }

    // Main update method
    public function update()
    {
        $this->authorize('update', $this->menu);
        
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
                    'icon' => trim($this->icon),
                    'show' => $this->show,
                    'target' => $this->target,
                    'permission' => trim($this->permission) ?: null,
                    'description' => trim($this->description) ?: null,
                    'is_active' => $this->is_active,
                ];
                
                // Update the menu
                $this->menu->update($data);
                
                // Log activity
                \Log::info('Menu updated', [
                    'menu_id' => $this->menu->id,
                    'menu_name' => $this->menu->name,
                    'updated_by' => auth()->user()->id,
                    'updated_by_name' => auth()->user()->name,
                ]);
            });
            
            // Update original data
            $this->originalData = [
                'name' => $this->name,
                'url' => $this->url,
                'route_name' => $this->route_name,
                'parent_id' => $this->parent_id,
                'sort_order' => $this->sort_order,
                'icon' => $this->icon,
                'show' => $this->show,
                'target' => $this->target,
                'permission' => $this->permission,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ];
            $this->hasChanges = false;
            
            // Success feedback
            $this->showSuccessMessage = true;
            $this->successMessage = "Menu '{$this->name}' has been updated successfully.";
            
            // Store success message in session for the redirect
            session()->flash('success', "Menu '{$this->name}' has been updated successfully.");
            
            // Dispatch event for real-time updates
            $this->dispatch('menuUpdated', [
                'id' => $this->menu->id,
                'name' => $this->name,
            ]);
            
            // Redirect to menus index
            return $this->redirect(route('administrator.menus.index'), navigate: true);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors are automatically handled by Livewire
            throw $e;
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update menu: ' . $e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    // Form management
    public function resetForm()
    {
        $this->name = $this->originalData['name'];
        $this->url = $this->originalData['url'];
        $this->route_name = $this->originalData['route_name'];
        $this->parent_id = $this->originalData['parent_id'];
        $this->sort_order = $this->originalData['sort_order'];
        $this->icon = $this->originalData['icon'];
        $this->show = $this->originalData['show'];
        $this->target = $this->originalData['target'];
        $this->permission = $this->originalData['permission'];
        $this->description = $this->originalData['description'];
        $this->is_active = $this->originalData['is_active'];
        
        $this->urlType = !empty($this->originalData['route_name']) ? 'route' : 'manual';
        $this->hasChanges = false;
        $this->resetValidation();
    }

    public function cancel()
    {
        return $this->redirect(route('administrator.menus.index'), navigate: true);
    }

    // Quick actions
    public function duplicateMenu()
    {
        return $this->redirect(route('administrator.menus.create', [
            'duplicate' => $this->menu->id
        ]), navigate: true);
    }

    // Computed properties
    public function getCanUpdateProperty()
    {
        return !$this->isProcessing && !empty(trim($this->name)) && $this->hasChanges &&
               ($this->urlType === 'manual' ? !empty(trim($this->url)) : !empty(trim($this->route_name)));
    }

    public function getSelectedParentNameProperty()
    {
        if (!$this->parent_id) {
            return 'None (Top Level)';
        }
        
        return $this->parentMenus[$this->parent_id] ?? 'Unknown';
    }

    public function getChildrenCountProperty()
    {
        return $this->menu->children()->count();
    }

    public function render()
    {
        return view('livewire.sakon.menus.edit-menu');
    }
}