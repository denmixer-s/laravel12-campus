<?php

namespace App\Livewire\Frontend\Menu;

use App\Models\Menu;
use Livewire\Component;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;

class MegaMenu extends Component
{
    public Collection $menuItems;
    public bool $isMobileMenuOpen = false;
    public ?int $activeDropdown = null;
    public string $currentRoute = '';
    public string $currentUrl = '';

    public function mount()
    {
        $this->currentRoute = request()->route()?->getName() ?? '';
        $this->currentUrl = request()->url();
        $this->loadMenuItems();
    }

    public function loadMenuItems(): void
    {
        $this->menuItems = Menu::active()
            ->parents()
            ->forShow('header')
            ->ordered()
            ->with([
                'children' => function ($query) {
                    $query->active()->ordered()->with([
                        'children' => function ($subQuery) {
                            $subQuery->active()->ordered();
                        }
                    ]);
                }
            ])
            ->get()
            ->filter(function ($menu) {
                return $menu->canAccess();
            });
    }

    public function toggleMobileMenu(): void
    {
        $this->isMobileMenuOpen = !$this->isMobileMenuOpen;
        
        if ($this->isMobileMenuOpen) {
            $this->dispatch('mobile-menu-opened');
        } else {
            $this->dispatch('mobile-menu-closed');
        }
    }

    public function closeMobileMenu(): void
    {
        $this->isMobileMenuOpen = false;
        $this->activeDropdown = null;
        $this->dispatch('mobile-menu-closed');
    }

    public function toggleDropdown(int $menuId): void
    {
        $this->activeDropdown = $this->activeDropdown === $menuId ? null : $menuId;
    }

    public function isActive(Menu $menu): bool
    {
        // Check if current route matches menu route
        if ($menu->route_name && $this->currentRoute === $menu->route_name) {
            return true;
        }

        // Check if current URL matches menu URL
        if ($menu->url && $this->currentUrl === url($menu->url)) {
            return true;
        }

        // Check if any child menu is active
        foreach ($menu->children as $child) {
            if ($this->isActive($child)) {
                return true;
            }
        }

        return false;
    }

    public function getMenuUrl(Menu $menu): string
    {
        return $menu->getResolvedUrl();
    }

    public function isMegaMenu(Menu $menu): bool
    {
        return isset($menu->meta_data['mega_menu']) && $menu->meta_data['mega_menu'] === true;
    }

    public function getMegaMenuColumns(Menu $menu): int
    {
        return $menu->meta_data['columns'] ?? 4;
    }

    public function hasChildren(Menu $menu): bool
    {
        return $menu->children->isNotEmpty();
    }

    public function getAccessibleChildren(Menu $menu): Collection
    {
        return $menu->children->filter(function ($child) {
            return $child->canAccess();
        });
    }

    public function groupChildrenByGroup(Collection $children): Collection
    {
        return $children->groupBy(function ($child) {
            return $child->meta_data['group'] ?? 'default';
        });
    }

    #[On('refresh-menu')]
    public function refreshMenu(): void
    {
        $this->loadMenuItems();
    }

    #[On('close-mobile-menu')]
    public function handleCloseMobileMenu(): void
    {
        $this->closeMobileMenu();
    }

    public function getListeners(): array
    {
        return [
            'refresh-menu' => 'refreshMenu',
            'close-mobile-menu' => 'handleCloseMobileMenu',
        ];
    }

    public function render()
    {
        return view('livewire.frontend.menu.mega-menu');
    }
}