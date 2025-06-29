<?php

namespace App\Livewire\Sakon\Menus;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Menu;

#[Layout('components.layouts.dashboard')]
#[Title('Menu Details')]
class ShowMenu extends Component
{
    public Menu $menu;
    public $activeTab = 'overview';

    public function mount(Menu $menu)
    {
        $this->menu = $menu->load(['parent', 'children.children', 'children' => function($query) {
            $query->orderBy('sort_order');
        }]);
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function goToMenusList()
    {
        return $this->redirect(route('administrator.menus.index'), navigate: true);
    }

    public function editMenu()
    {
        return $this->redirect(route('administrator.menus.edit', $this->menu), navigate: true);
    }

    public function duplicateMenu()
    {
        return $this->redirect(route('administrator.menus.create', [
            'duplicate' => $this->menu->id
        ]), navigate: true);
    }

    public function toggleStatus()
    {
        try {
            $this->menu->update(['is_active' => !$this->menu->is_active]);
            
            $status = $this->menu->is_active ? 'activated' : 'deactivated';
            session()->flash('success', "Menu '{$this->menu->name}' has been {$status} successfully.");
            
            // Refresh the menu data
            $this->menu = $this->menu->fresh();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update menu status: ' . $e->getMessage());
        }
    }

    public function getMenuStatsProperty()
    {
        return [
            'children_count' => $this->menu->children()->count(),
            'active_children_count' => $this->menu->children()->where('is_active', true)->count(),
            'depth' => $this->menu->getDepth(),
            'created_date' => $this->menu->created_at->format('M d, Y'),
            'updated_date' => $this->menu->updated_at->format('M d, Y'),
            'created_datetime' => $this->menu->created_at->format('M d, Y \a\t H:i'),
            'updated_datetime' => $this->menu->updated_at->format('M d, Y \a\t H:i'),
            'created_human' => $this->menu->created_at->diffForHumans(),
            'updated_human' => $this->menu->updated_at->diffForHumans(),
            'url' => $this->menu->getUrl(),
            'can_access' => $this->menu->canAccess(),
            'breadcrumbs' => $this->menu->getBreadcrumbs(),
        ];
    }

    public function getMenuHierarchyProperty()
    {
        // Get the full hierarchy starting from this menu
        $hierarchy = collect();
        
        // Add current menu
        $hierarchy->push($this->menu);
        
        // Add children recursively
        $this->addChildrenToHierarchy($this->menu, $hierarchy, 1);
        
        return $hierarchy;
    }

    private function addChildrenToHierarchy($menu, &$hierarchy, $level)
    {
        foreach ($menu->children as $child) {
            $child->hierarchy_level = $level;
            $hierarchy->push($child);
            
            if ($child->children->count() > 0) {
                $this->addChildrenToHierarchy($child, $hierarchy, $level + 1);
            }
        }
    }

    public function getSiblingMenusProperty()
    {
        if ($this->menu->parent_id) {
            return Menu::where('parent_id', $this->menu->parent_id)
                ->where('id', '!=', $this->menu->id)
                ->orderBy('sort_order')
                ->get();
        }
        
        return Menu::whereNull('parent_id')
            ->where('id', '!=', $this->menu->id)
            ->orderBy('sort_order')
            ->get();
    }

    public function getMenuAccessInfoProperty()
    {
        $info = [
            'requires_auth' => !empty($this->menu->permission),
            'permission_required' => $this->menu->permission,
            'current_user_can_access' => $this->menu->canAccess(),
            'is_external_link' => !empty($this->menu->url) && (str_starts_with($this->menu->url, 'http://') || str_starts_with($this->menu->url, 'https://')),
            'opens_in_new_tab' => $this->menu->target === '_blank',
        ];
        
        return $info;
    }

    public function render()
    {
        return view('livewire.sakon.menus.show-menu', [
            'menuStats' => $this->menuStats,
            'menuHierarchy' => $this->menuHierarchy,
            'siblingMenus' => $this->siblingMenus,
            'menuAccessInfo' => $this->menuAccessInfo,
        ]);
    }
}