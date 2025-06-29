<?php
namespace App\Livewire\Frontend\Menu;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class FrontendMenu extends Component
{
    /**
     * Menu display location configuration
     */
    public string $location = 'header';

    /**
     * Menu layout variant
     */
    public string $variant = 'default';

    /**
     * Active menu tracking
     */
    public ?int $activeMenuId    = null;
    public ?string $currentRoute = null;
    public ?string $currentUrl   = null;

    /**
     * Mobile menu state
     */
    public bool $showMobileMenu = false;

    /**
     * Display configuration
     */
    public int $maxDepth         = 0;
    public bool $showIcons       = true;
    public bool $showBreadcrumbs = false;
    public bool $cacheEnabled    = true;

    /**
     * Styling configuration with defaults
     */
    public array $theme = [
        'primary'   => 'blue',
        'secondary' => 'indigo',
        'accent'    => 'orange',
    ];

    public array $cssClasses = [
        'container' => 'menu-container',
        'desktop'   => 'menu-desktop',
        'mobile'    => 'menu-mobile',
        'item'      => 'menu-item',
        'link'      => 'menu-link',
        'active'    => 'menu-active',
        'dropdown'  => 'menu-dropdown',
        'icon'      => 'menu-icon',
        'text'      => 'menu-text',
    ];

    /**
     * Default CSS classes (fallback)
     */
    protected array $defaultCssClasses = [
        'container' => 'menu-container',
        'desktop'   => 'menu-desktop',
        'mobile'    => 'menu-mobile',
        'item'      => 'menu-item',
        'link'      => 'menu-link',
        'active'    => 'menu-active',
        'dropdown'  => 'menu-dropdown',
        'icon'      => 'menu-icon',
        'text'      => 'menu-text',
    ];

    /**
     * Component initialization
     */
    // public function mount(
    //     string $location = 'header',
    //     string $variant = 'default',
    //     ?int $activeMenuId = null,
    //     int $maxDepth = 0,
    //     bool $showIcons = true,
    //     bool $showBreadcrumbs = false,
    //     bool $cacheEnabled = true,
    //     array $theme = [],
    //     array $cssClasses = []
    // ): void {
    //     $this->location = $location;
    //     $this->variant = $variant;
    //     $this->activeMenuId = $activeMenuId;
    //     $this->maxDepth = $maxDepth;
    //     $this->showIcons = $showIcons;
    //     $this->showBreadcrumbs = $showBreadcrumbs;
    //     $this->cacheEnabled = $cacheEnabled;

    //     // Merge theme with defaults
    //     $this->theme = array_merge($this->theme, $theme);

    //     // Merge CSS classes with defaults, ensuring all keys exist
    //     $this->cssClasses = array_merge($this->defaultCssClasses, $this->cssClasses, $cssClasses);

    //     $this->currentRoute = Request::route()?->getName();
    //     $this->currentUrl = Request::url();

    //     $this->detectActiveMenu();
    // }

    public function mount(
        string $location = 'header',
        string $variant = 'default',
        ?int $activeMenuId = null,
        int $maxDepth = 0,
        bool $showIcons = true,
        bool $showBreadcrumbs = false,
        bool $cacheEnabled = true,
        array $theme = [],
        array $cssClasses = []
    ): void {
        $this->location        = $location;
        $this->variant         = $variant;
        $this->activeMenuId    = $activeMenuId;
        $this->maxDepth        = $maxDepth;
        $this->showIcons       = $showIcons;
        $this->showBreadcrumbs = $showBreadcrumbs;
        $this->cacheEnabled    = $cacheEnabled;

        // Fix: Ensure all theme keys exist with proper defaults
        $defaultTheme = [
            'primary'   => 'blue',
            'secondary' => 'indigo',
            'accent'    => 'orange',
        ];

        // Merge provided theme with defaults, ensuring all keys exist
        $this->theme = array_merge($defaultTheme, $theme);

        // Merge CSS classes with defaults, ensuring all keys exist
        $this->cssClasses = array_merge($this->defaultCssClasses, $this->cssClasses, $cssClasses);

        $this->currentRoute = Request::route()?->getName();
        $this->currentUrl   = Request::url();

        $this->detectActiveMenu();
    }

    /**
     * Get CSS class with fallback
     */
    public function getCssClass(string $key): string
    {
        return $this->cssClasses[$key] ?? $this->defaultCssClasses[$key] ?? "menu-{$key}";
    }

    /**
     * Get theme value with fallback
     */
    public function getThemeValue(string $key): string
    {
        $defaults = [
            'primary'   => 'blue',
            'secondary' => 'indigo',
            'accent'    => 'orange',
        ];

        return $this->theme[$key] ?? $defaults[$key] ?? 'blue';
    }

    /**
     * Get the optimized menu tree
     */
    #[Computed(persist: true)]
    public function menuTree(): Collection
    {
        $cacheKey = $this->getCacheKey('tree');

        if ($this->cacheEnabled && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $query = Menu::with(['children' => function ($query) {
            $query->active()
                ->ordered()
                ->when($this->maxDepth > 0, function ($q) {
                    return $q->whereRaw('(SELECT COUNT(*) FROM menus AS parent WHERE parent.id = menus.parent_id) <= ?', [$this->maxDepth]);
                });
        }])
            ->active()
            ->ordered()
            ->whereNull('parent_id');

        // Use scopes if they exist
        if (method_exists(Menu::class, 'scopeForShow')) {
            $query->forShow($this->location);
        } else {
            // Fallback to existing byLocation scope
            $query->byLocation($this->location);
        }

        // Filter by user permissions
        if (Auth::check()) {
            if (method_exists(Menu::class, 'scopeAllowedForUser')) {
                $query->allowedForUser(Auth::user());
            } else {
                // Fallback: just show public menus for logged-in users
                $query->where(function ($q) {
                    $q->whereNull('permission');
                    // Add user permission check here if needed
                });
            }
        } else {
            $query->whereNull('permission');
        }

        $menus = $query->get();

        if ($this->cacheEnabled) {
            Cache::put($cacheKey, $menus, now()->addMinutes(30));
        }

        return $menus;
    }

    /**
     * Get the breadcrumb trail
     */
    #[Computed(persist: true)]
    public function breadcrumbTrail(): SupportCollection
    {
        if (! $this->showBreadcrumbs || ! $this->activeMenuId) {
            return collect();
        }

        $cacheKey = $this->getCacheKey('breadcrumb', $this->activeMenuId);

        if ($this->cacheEnabled && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $activeMenu = Menu::find($this->activeMenuId);

        if (! $activeMenu) {
            return collect();
        }

        // Use different methods based on what's available
        if (method_exists($activeMenu, 'getMenuPath')) {
            $breadcrumbs = $activeMenu->getMenuPath();
        } elseif (method_exists($activeMenu, 'getBreadcrumbs')) {
            $breadcrumbs = collect($activeMenu->getBreadcrumbs());
        } else {
            // Fallback: build breadcrumbs manually
            $breadcrumbs = $this->buildBreadcrumbPath($activeMenu);
        }

        if ($this->cacheEnabled) {
            Cache::put($cacheKey, $breadcrumbs, now()->addMinutes(15));
        }

        return $breadcrumbs;
    }

    /**
     * Build breadcrumb path manually
     */
    protected function buildBreadcrumbPath(Menu $menu): SupportCollection
    {
        $breadcrumbs = collect();
        $current     = $menu;

        while ($current) {
            $breadcrumbs->prepend($current);
            $current = $current->parent;
        }

        return $breadcrumbs;
    }

    /**
     * Get menu statistics
     */
    #[Computed]
    public function menuStats(): array
    {
        $tree = $this->menuTree;

        return [
            'total_items'  => $tree->count(),
            'has_children' => $tree->some(fn($menu) => $menu->children->isNotEmpty()),
            'max_depth'    => $this->calculateMaxDepth($tree),
            'active_items' => $tree->filter(fn($menu) => $this->isMenuActive($menu))->count(),
        ];
    }

    /**
     * Toggle mobile menu
     */
    public function toggleMobileMenu(): void
    {
        $this->showMobileMenu = ! $this->showMobileMenu;

        $this->dispatch('mobile-menu-toggled', [
            'isOpen'   => $this->showMobileMenu,
            'location' => $this->location,
        ]);
    }

    /**
     * Handle menu item click
     */
    public function handleMenuClick(int $menuId, ?string $url = null, string $target = '_self'): void
    {
        $menu = $this->findMenuById($menuId);

        if (! $menu || ! $this->canAccessMenu($menu)) {
            $this->dispatch('menu-access-denied', ['menuId' => $menuId]);
            return;
        }

        $this->setActiveMenu($menuId);

        // Close mobile menu
        if ($this->showMobileMenu) {
            $this->showMobileMenu = false;
        }

        // Get the actual URL
        $actualUrl = $url ?: $this->getMenuUrl($menu);

        // Dispatch events
        $this->dispatch('menu-clicked', [
            'menu'   => $menu->toArray(),
            'url'    => $actualUrl,
            'target' => $target,
        ]);

        // Handle navigation
        $this->handleNavigation($actualUrl, $target);

        // Track analytics if needed
        $this->trackMenuClick($menu);
    }

    /**
     * Get menu URL with fallback methods
     */
    protected function getMenuUrl(Menu $menu): string
    {
        if (method_exists($menu, 'getResolvedUrl')) {
            return $menu->getResolvedUrl();
        } elseif (method_exists($menu, 'getUrl')) {
            return $menu->getUrl();
        } else {
            // Fallback
            if ($menu->route_name) {
                try {
                    return route($menu->route_name);
                } catch (\Exception $e) {
                    \Log::warning("Route '{$menu->route_name}' not found for menu '{$menu->name}'");
                }
            }
            return $menu->url ?: '#';
        }
    }

    /**
     * Set active menu
     */
    public function setActiveMenu(int $menuId): void
    {
        $this->activeMenuId = $menuId;

        // Clear computed properties that depend on active menu
        unset($this->breadcrumbTrail);

        $this->dispatch('menu-activated', [
            'menuId'   => $menuId,
            'location' => $this->location,
        ]);
    }

    /**
     * Listen for menu updates
     */
    #[On('menu-structure-updated')]
    public function handleMenuUpdate(): void
    {
        $this->clearCache();
        unset($this->menuTree, $this->breadcrumbTrail);
    }

    /**
     * Listen for active menu changes
     */
    #[On('set-active-menu')]
    public function handleActiveMenuChange(int $menuId): void
    {
        $this->setActiveMenu($menuId);
    }

    /**
     * Listen for user permission changes
     */
    #[On('user-permissions-updated')]
    public function handlePermissionUpdate(): void
    {
        $this->clearCache();
        unset($this->menuTree);
    }

    /**
     * Check if menu item is active
     */
    public function isMenuActive(Menu $menu): bool
    {
        // Direct match
        if ($this->activeMenuId === $menu->id) {
            return true;
        }

        // Use enhanced route checking if available
        if (method_exists($menu, 'isCurrentRoute') && $menu->isCurrentRoute()) {
            return true;
        } elseif (method_exists($menu, 'isCurrentlyActive') && $menu->isCurrentlyActive()) {
            return true;
        }

        // Basic route/URL matching
        if ($this->currentRoute && $menu->route_name === $this->currentRoute) {
            return true;
        }

        if ($this->currentUrl && $menu->url === $this->currentUrl) {
            return true;
        }

        // Check if any descendant is active
        return $menu->children->some(fn($child) => $this->isMenuActive($child));
    }

    /**
     * Check if menu has active descendant
     */
    public function hasActiveDescendant(Menu $menu): bool
    {
        return $menu->children->some(fn($child) =>
            $this->isMenuActive($child) || $this->hasActiveDescendant($child)
        );
    }

    /**
     * Get CSS classes for menu item
     */
    public function getMenuItemClasses(Menu $menu, string $context = 'desktop'): string
    {
        $classes = [
            $this->getCssClass('item'),
            "menu-item-{$context}",
            "menu-depth-{$this->getMenuDepth($menu)}",
        ];

        if ($this->isMenuActive($menu)) {
            $classes[] = $this->getCssClass('active');
            $classes[] = 'menu-current';
        }

        if ($this->hasActiveDescendant($menu)) {
            $classes[] = 'menu-ancestor';
        }

        if ($menu->children->isNotEmpty()) {
            $classes[] = $this->getCssClass('dropdown');
            $classes[] = 'menu-has-children';
        }

        if ($menu->permission) {
            $classes[] = 'menu-protected';
        }

        if ($menu->target === '_blank') {
            $classes[] = 'menu-external';
        }

        return implode(' ', array_filter($classes));
    }

    /**
     * Get theme class for element
     */
    public function getThemeClass(string $element, string $variant = 'default'): string
    {
        $primary   = $this->getThemeValue('primary');
        $secondary = $this->getThemeValue('secondary');
        $accent    = $this->getThemeValue('accent');

        $themeMap = [
            'primary'   => "text-{$primary}-600 bg-{$primary}-50",
            'secondary' => "text-{$secondary}-600 bg-{$secondary}-50",
            'accent'    => "hover:bg-{$accent}-200",
            'border'    => "border-{$primary}-200",
        ];

        return $themeMap[$element] ?? '';
    }

    /**
     * Generate cache key
     */
    protected function getCacheKey(string $type, mixed $identifier = null): string
    {
        $parts = [
            'frontend_menu',
            $type,
            $this->location,
            $this->variant,
            Auth::id() ?? 'guest',
            $identifier,
        ];

        return implode(':', array_filter($parts));
    }

    /**
     * Clear all related cache
     */
    protected function clearCache(): void
    {
        $patterns = [
            $this->getCacheKey('tree'),
            $this->getCacheKey('breadcrumb', '*'),
        ];

        foreach ($patterns as $pattern) {
            if (str_contains($pattern, '*')) {
                Cache::flush();
                break;
            } else {
                Cache::forget($pattern);
            }
        }
    }

    /**
     * Detect active menu based on current context
     */
    protected function detectActiveMenu(): void
    {
        if ($this->activeMenuId) {
            return;
        }

        // Try to find active menu by route
        if ($this->currentRoute) {
            $menu = Menu::where('route_name', $this->currentRoute)
                ->where('is_active', true)
                ->first();

            if ($menu) {
                $this->activeMenuId = $menu->id;
                return;
            }
        }

        // Try to find active menu by URL
        if ($this->currentUrl) {
            $menu = Menu::where('url', $this->currentUrl)
                ->where('is_active', true)
                ->first();

            if ($menu) {
                $this->activeMenuId = $menu->id;
            }
        }
    }

    /**
     * Find menu by ID within the tree
     */
    protected function findMenuById(int $menuId): ?Menu
    {
        foreach ($this->menuTree as $menu) {
            if ($menu->id === $menuId) {
                return $menu;
            }

            $found = $this->searchInChildren($menu->children, $menuId);
            if ($found) {
                return $found;
            }
        }

        return null;
    }

    /**
     * Search for menu in children recursively
     */
    protected function searchInChildren(Collection $children, int $menuId): ?Menu
    {
        foreach ($children as $child) {
            if ($child->id === $menuId) {
                return $child;
            }

            if ($child->children->isNotEmpty()) {
                $found = $this->searchInChildren($child->children, $menuId);
                if ($found) {
                    return $found;
                }
            }
        }

        return null;
    }

    /**
     * Check if user can access menu
     */
    protected function canAccessMenu(Menu $menu): bool
    {
        return $menu->canAccess();
    }

    /**
     * Get menu depth (compatible with existing method)
     */
    protected function getMenuDepth(Menu $menu): int
    {
        if (method_exists($menu, 'getDepth')) {
            return $menu->getDepth();
        } elseif (method_exists($menu, 'getHierarchyDepth')) {
            return $menu->getHierarchyDepth();
        }

        // Fallback calculation
        $depth  = 0;
        $parent = $menu->parent;
        while ($parent) {
            $depth++;
            $parent = $parent->parent;
        }
        return $depth;
    }

    /**
     * Handle navigation based on URL and target
     */
    protected function handleNavigation(string $url, string $target): void
    {
        if ($url === '#' || empty($url)) {
            return;
        }

        switch ($target) {
            case '_blank':
                $this->dispatch('open-external-link', ['url' => $url]);
                break;
            case '_self':
            default:
                $this->dispatch('navigate-to', ['url' => $url]);
                break;
        }
    }

    /**
     * Track menu click for analytics
     */
    protected function trackMenuClick(Menu $menu): void
    {
        $this->dispatch('menu-analytics', [
            'action'    => 'click',
            'menu_id'   => $menu->id,
            'menu_name' => $menu->name,
            'location'  => $this->location,
            'user_id'   => Auth::id(),
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Calculate maximum depth in menu tree
     */
    protected function calculateMaxDepth(Collection $menus, int $currentDepth = 0): int
    {
        if ($menus->isEmpty()) {
            return $currentDepth;
        }

        $maxDepth = $currentDepth;

        foreach ($menus as $menu) {
            if ($menu->children->isNotEmpty()) {
                $childDepth = $this->calculateMaxDepth($menu->children, $currentDepth + 1);
                $maxDepth   = max($maxDepth, $childDepth);
            }
        }

        return $maxDepth;
    }

    /**
     * Get component view data
     */
    protected function getViewData(): array
    {
        return [
            'menuTree'    => $this->menuTree,
            'breadcrumbs' => $this->breadcrumbTrail,
            'stats'       => $this->menuStats,
            'theme'       => $this->theme,
            'cssClasses'  => $this->cssClasses, // Ensure this is always available
            'config'      => [
                'location'        => $this->location,
                'variant'         => $this->variant,
                'showIcons'       => $this->showIcons,
                'showBreadcrumbs' => $this->showBreadcrumbs,
                'maxDepth'        => $this->maxDepth,
                'showMobileMenu'  => $this->showMobileMenu,
            ],
        ];
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.frontend.menu.frontend-menu', $this->getViewData());
    }
}
