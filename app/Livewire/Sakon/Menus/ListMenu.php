<?php

namespace App\Livewire\Sakon\Menus;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Menu;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

#[Layout('components.layouts.dashboard')]
#[Title('Manage Menus')]
class ListMenu extends Component
{
    use WithPagination, AuthorizesRequests;

    // Component properties
    public $search = '';
    public $locationFilter = '';
    public $statusFilter = '';
    public $perPage = 15;
    public $confirmingMenuDeletion = false;
    public $menuToDelete = null;
    public $menuToDeleteName = '';
    public $sortBy = 'sort_order';
    public $sortDirection = 'asc';

    // Real-time listeners
    protected $listeners = [
        'menuCreated' => 'handleMenuCreated',
        'menuUpdated' => 'handleMenuUpdated',
        'menuDeleted' => 'handleMenuDeleted',
        'menuOrderUpdated' => 'handleMenuOrderUpdated',
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

    public function updatedLocationFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    // Real-time updates
    public function handleMenuCreated()
    {
        $this->resetPage();
        session()->flash('success', 'Menu created successfully!');
    }

    public function handleMenuUpdated()
    {
        $this->resetPage();
        session()->flash('success', 'Menu updated successfully!');
    }

    public function handleMenuDeleted()
    {
        $this->resetPage();
        session()->flash('success', 'Menu deleted successfully!');
    }

    public function handleMenuOrderUpdated()
    {
        $this->resetPage();
        session()->flash('success', 'Menu order updated successfully!');
    }

    // Navigation methods
    public function createMenu()
    {
        return $this->redirect(route('administrator.menus.create'), navigate: true);
    }

    public function viewMenu($menuId)
    {
        return $this->redirect(route('administrator.menus.show', $menuId), navigate: true);
    }

    public function editMenu($menuId)
    {
        return $this->redirect(route('administrator.menus.edit', $menuId), navigate: true);
    }

    // Sorting
    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        
        $this->resetPage();
    }

    // Status toggle
    public function toggleStatus($menuId)
    {
        try {
            $menu = Menu::findOrFail($menuId);
            $menu->update(['is_active' => !$menu->is_active]);
            
            session()->flash('success', "Menu '{$menu->name}' status updated successfully.");
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update menu status: ' . $e->getMessage());
        }
    }

    // Delete functionality
    public function confirmDelete($menuId)
    {
        Log::info('confirmDelete called with menuId: ' . $menuId);

        try {
            $menu = Menu::with('children')->findOrFail($menuId);
            Log::info('Menu found: ' . $menu->name);

            // Check if menu has children
            if ($menu->children()->count() > 0) {
                session()->flash('error', "Cannot delete menu '{$menu->name}' because it has child menu items. Remove child items first.");
                return;
            }

            // Set the properties for the modal
            $this->menuToDelete = $menuId;
            $this->menuToDeleteName = $menu->name;
            $this->confirmingMenuDeletion = true;

            Log::info('Modal should open now', [
                'menuToDelete' => $this->menuToDelete,
                'menuToDeleteName' => $this->menuToDeleteName,
                'confirmingMenuDeletion' => $this->confirmingMenuDeletion
            ]);

        } catch (\Exception $e) {
            Log::error('Error in confirmDelete: ' . $e->getMessage());
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        Log::info('delete method called');

        if (!$this->menuToDelete) {
            session()->flash('error', 'No menu selected for deletion.');
            $this->cancelDelete();
            return;
        }

        try {
            $menu = Menu::findOrFail($this->menuToDelete);
            $menuName = $menu->name;

            Log::info('Deleting menu: ' . $menuName);

            // Final validation
            if ($menu->children()->count() > 0) {
                session()->flash('error', 'Menu still has child items.');
                $this->cancelDelete();
                return;
            }

            // Delete the menu
            DB::transaction(function () use ($menu) {
                $menu->delete();
            });

            Log::info('Menu deleted successfully: ' . $menuName);

            session()->flash('success', "Menu '{$menuName}' has been deleted successfully.");

            $this->resetPage();
            $this->cancelDelete();

        } catch (\Exception $e) {
            Log::error('Error deleting menu: ' . $e->getMessage());
            session()->flash('error', 'Failed to delete menu: ' . $e->getMessage());
            $this->cancelDelete();
        }
    }

    public function cancelDelete()
    {
        Log::info('cancelDelete called');
        $this->confirmingMenuDeletion = false;
        $this->menuToDelete = null;
        $this->menuToDeleteName = '';
    }

    // Helper method to check if user can delete menu
    public function canUserDeleteMenu($menu)
    {
        // Menu with children cannot be deleted
        if ($menu->children()->count() > 0) {
            return false;
        }

        // Check if user has permission (simplified)
        return auth()->user()->hasRole('Super Admin') || auth()->user()->can('delete-menu');
    }

    // Quick actions
    public function duplicateMenu($menuId)
    {
        return $this->redirect(route('administrator.menus.create', [
            'duplicate' => $menuId
        ]), navigate: true);
    }

    public function moveUp($menuId)
    {
        try {
            $menu = Menu::findOrFail($menuId);
            $previousMenu = Menu::where('parent_id', $menu->parent_id)
                ->where('sort_order', '<', $menu->sort_order)
                ->orderBy('sort_order', 'desc')
                ->first();

            if ($previousMenu) {
                DB::transaction(function () use ($menu, $previousMenu) {
                    $tempOrder = $menu->sort_order;
                    $menu->update(['sort_order' => $previousMenu->sort_order]);
                    $previousMenu->update(['sort_order' => $tempOrder]);
                });

                session()->flash('success', "Menu '{$menu->name}' moved up successfully.");
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to move menu: ' . $e->getMessage());
        }
    }

    public function moveDown($menuId)
    {
        try {
            $menu = Menu::findOrFail($menuId);
            $nextMenu = Menu::where('parent_id', $menu->parent_id)
                ->where('sort_order', '>', $menu->sort_order)
                ->orderBy('sort_order', 'asc')
                ->first();

            if ($nextMenu) {
                DB::transaction(function () use ($menu, $nextMenu) {
                    $tempOrder = $menu->sort_order;
                    $menu->update(['sort_order' => $nextMenu->sort_order]);
                    $nextMenu->update(['sort_order' => $tempOrder]);
                });

                session()->flash('success', "Menu '{$menu->name}' moved down successfully.");
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to move menu: ' . $e->getMessage());
        }
    }

    // Data fetching
    public function getMenusProperty()
    {
        try {
            $query = Menu::with(['parent', 'children'])
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('url', 'like', '%' . $this->search . '%')
                          ->orWhere('route_name', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->locationFilter, function ($query) {
                    $query->where('show', $this->locationFilter);
                })
                ->when($this->statusFilter !== '', function ($query) {
                    $query->where('is_active', $this->statusFilter === '1');
                });

            // Apply sorting
            if ($this->sortBy === 'sort_order') {
                $query->orderBy('parent_id', 'asc')
                      ->orderBy('sort_order', $this->sortDirection);
            } else {
                $query->orderBy($this->sortBy, $this->sortDirection);
            }

            return $query->paginate($this->perPage);
        } catch (\Exception $e) {
            Log::error('Error loading menus', ['error' => $e->getMessage()]);
            session()->flash('error', 'Error loading menus. Please try again.');
            return collect()->paginate($this->perPage);
        }
    }

    public function getMenuStatsProperty()
    {
        return [
            'total' => Menu::count(),
            'active' => Menu::where('is_active', true)->count(),
            'inactive' => Menu::where('is_active', false)->count(),
            'parents' => Menu::whereNull('parent_id')->count(),
            'children' => Menu::whereNotNull('parent_id')->count(),
        ];
    }

    public function render()
    {
        return view('livewire.sakon.menus.list-menu', [
            'menus' => $this->menus,
            'menuStats' => $this->menuStats,
        ]);
    }
}