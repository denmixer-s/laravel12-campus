<?php

namespace App\Services;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MenuService
{
    /**
     * Cache duration in minutes
     */
    protected int $cacheDuration = 30;

    /**
     * Cache tags for invalidation
     */
    protected array $cacheTags = ['menu', 'navigation'];

    /**
     * Get optimized menu tree
     */
    public function getMenuTree(array $options = []): Collection
    {
        $cacheKey = $this->generateCacheKey('tree', $options);

        if (Cache::tags($this->cacheTags)->has($cacheKey)) {
            return Cache::tags($this->cacheTags)->get($cacheKey);
        }

        $query = Menu::query()
            ->with(['children' => function ($query) use ($options) {
                $query->active()
                      ->ordered();

                if (isset($options['max_depth']) && $options['max_depth'] > 0) {
                    // Use a more compatible depth filter
                    $query->whereRaw('(
                        SELECT COUNT(*)
                        FROM menus AS ancestors
                        WHERE ancestors.id != menus.id
                        AND menus.parent_id = ancestors.id
                    ) < ?', [$options['max_depth']]);
                }
            }])
            ->active()
            ->ordered()
            ->whereNull('parent_id');

        // Filter by location
        if (isset($options['location'])) {
            $query->forShow($options['location']);
        }

        // Filter by user permissions
        if (isset($options['user_id']) && $options['user_id']) {
            $user = \App\Models\User::find($options['user_id']);
            if ($user) {
                $query->allowedForUser($user);
            }
        } elseif (!isset($options['user_id'])) {
            // Default to current user if not specified
            if (Auth::check()) {
                $query->allowedForUser(Auth::user());
            } else {
                $query->whereNull('permission');
            }
        }

        $menus = $query->get();

        Cache::tags($this->cacheTags)->put($cacheKey, $menus, now()->addMinutes($this->cacheDuration));

        return $menus;
    }

    /**
     * Get breadcrumb trail
     */
    public function getBreadcrumbTrail(int $menuId): SupportCollection
    {
        $cacheKey = $this->generateCacheKey('breadcrumb', ['menu_id' => $menuId]);

        if (Cache::tags($this->cacheTags)->has($cacheKey)) {
            return Cache::tags($this->cacheTags)->get($cacheKey);
        }

        $menu = Menu::with('parent')->find($menuId);

        if (!$menu) {
            return collect();
        }

        // Use the compatible method
        $breadcrumbs = method_exists($menu, 'getMenuPath')
            ? $menu->getMenuPath()
            : $this->buildBreadcrumbPath($menu);

        Cache::tags($this->cacheTags)->put($cacheKey, $breadcrumbs, now()->addMinutes(15));

        return $breadcrumbs;
    }

    /**
     * Build breadcrumb path manually if method doesn't exist
     */
    protected function buildBreadcrumbPath(Menu $menu): SupportCollection
    {
        $breadcrumbs = collect();
        $current = $menu;

        while ($current) {
            $breadcrumbs->prepend($current);
            $current = $current->parent;
        }

        return $breadcrumbs;
    }

    /**
     * Get menu siblings
     */
    public function getMenuSiblings(int $menuId): Collection
    {
        $menu = Menu::find($menuId);

        if (!$menu) {
            return collect();
        }

        return Menu::where('parent_id', $menu->parent_id)
                  ->where('id', '!=', $menuId)
                  ->active()
                  ->ordered()
                  ->get();
    }

    /**
     * Search menus
     */
    public function searchMenus(string $query, array $options = []): Collection
    {
        $searchQuery = Menu::query()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('url', 'like', "%{$query}%")
                  ->orWhere('route_name', 'like', "%{$query}%");
            })
            ->active();

        if (isset($options['location'])) {
            $searchQuery->forShow($options['location']);
        }

        if (isset($options['user_permissions'])) {
            $searchQuery->allowed($options['user_permissions']);
        }

        return $searchQuery
            ->ordered()
            ->limit($options['limit'] ?? 10)
            ->get();
    }

    /**
     * Get popular menus (placeholder for analytics integration)
     */
    public function getPopularMenus(array $options = []): Collection
    {
        // This would integrate with your analytics system
        // For now, return most recently accessed or created menus

        $query = Menu::query()
            ->active()
            ->ordered();

        if (isset($options['location'])) {
            $query->forShow($options['location']);
        }

        if (Auth::check()) {
            $query->allowedForUser(Auth::user());
        } else {
            $query->whereNull('permission');
        }

        return $query
            ->latest('updated_at')
            ->limit($options['limit'] ?? 5)
            ->get();
    }

    /**
     * Get menu statistics
     */
    public function getMenuStatistics(array $options = []): array
    {
        $cacheKey = $this->generateCacheKey('stats', $options);

        if (Cache::tags($this->cacheTags)->has($cacheKey)) {
            return Cache::tags($this->cacheTags)->get($cacheKey);
        }

        $baseQuery = Menu::query();

        if (isset($options['location'])) {
            $baseQuery->forShow($options['location']);
        }

        $stats = [
            'total_menus' => $baseQuery->count(),
            'active_menus' => (clone $baseQuery)->active()->count(),
            'inactive_menus' => (clone $baseQuery)->where('is_active', false)->count(),
            'parent_menus' => (clone $baseQuery)->whereNull('parent_id')->count(),
            'child_menus' => (clone $baseQuery)->whereNotNull('parent_id')->count(),
            'protected_menus' => (clone $baseQuery)->whereNotNull('permission')->count(),
            'external_menus' => (clone $baseQuery)->where('target', '_blank')->count(),
            'menus_with_icons' => (clone $baseQuery)->whereNotNull('icon')->count(),
        ];

        // Calculate depth statistics
        $maxDepth = 0;
        $avgDepth = 0;

        if ($stats['total_menus'] > 0) {
            $menus = $baseQuery->with('parent')->get();
            $depths = $menus->map(function ($menu) {
                return $this->calculateMenuDepth($menu);
            });

            $maxDepth = $depths->max();
            $avgDepth = round($depths->avg(), 2);
        }

        $stats['max_depth'] = $maxDepth;
        $stats['average_depth'] = $avgDepth;

        Cache::tags($this->cacheTags)->put($cacheKey, $stats, now()->addMinutes(60));

        return $stats;
    }

    /**
     * Calculate menu depth compatible with existing methods
     */
    protected function calculateMenuDepth(Menu $menu): int
    {
        if (method_exists($menu, 'getDepth')) {
            return $menu->getDepth();
        } elseif (method_exists($menu, 'getHierarchyDepth')) {
            return $menu->getHierarchyDepth();
        }

        // Fallback calculation
        $depth = 0;
        $parent = $menu->parent;
        while ($parent) {
            $depth++;
            $parent = $parent->parent;
        }
        return $depth;
    }

    /**
     * Validate menu access for user
     */
    public function canAccessMenu(Menu $menu, ?int $userId = null): bool
    {
        $userId = $userId ?? Auth::id();

        if (!$menu->is_active) {
            return false;
        }

        if (!$menu->permission) {
            return true;
        }

        if (!$userId) {
            return false;
        }

        $user = Auth::user() ?? \App\Models\User::find($userId);

        if (!$user) {
            return false;
        }

        return $user->can($menu->permission) || $user->hasRole('Super Admin');
    }

    /**
     * Get menu tree as nested array for API responses
     */
    public function getMenuTreeArray(array $options = []): array
    {
        $menus = $this->getMenuTree($options);

        return $menus->map(function ($menu) {
            return $this->menuToArray($menu, true);
        })->toArray();
    }

    /**
     * Convert menu to array format
     */
    protected function menuToArray(Menu $menu, bool $includeChildren = true): array
    {
        // Use the new method if available, otherwise build manually
        if (method_exists($menu, 'toMenuArray')) {
            return $menu->toMenuArray($includeChildren);
        }

        $data = [
            'id' => $menu->id,
            'name' => $menu->name,
            'url' => method_exists($menu, 'getResolvedUrl') ? $menu->getResolvedUrl() : $menu->getUrl(),
            'route_name' => $menu->route_name,
            'icon' => $menu->icon,
            'target' => $menu->target,
            'description' => $menu->description,
            'is_active' => $menu->is_active,
            'has_children' => $menu->children->isNotEmpty(),
            'can_access' => $menu->canAccess(),
            'is_current' => method_exists($menu, 'isCurrentRoute') ? $menu->isCurrentRoute() : $menu->isCurrentlyActive(),
            'depth' => $this->calculateMenuDepth($menu),
            'sort_order' => $menu->sort_order,
            'show' => $menu->show,
            'permission' => $menu->permission,
        ];

        if ($includeChildren && $menu->children->isNotEmpty()) {
            $data['children'] = $menu->children->map(fn($child) => $this->menuToArray($child, true))->toArray();
        }

        return $data;
    }

    /**
     * Reorder menus
     */
    public function reorderMenus(array $menuOrder, ?int $parentId = null): bool
    {
        try {
            DB::transaction(function () use ($menuOrder, $parentId) {
                foreach ($menuOrder as $order => $menuId) {
                    Menu::where('id', $menuId)
                        ->where('parent_id', $parentId)
                        ->update(['sort_order' => $order]);
                }
            });

            $this->clearCache();
            return true;

        } catch (\Exception $e) {
            \Log::error('Failed to reorder menus', [
                'error' => $e->getMessage(),
                'menu_order' => $menuOrder,
                'parent_id' => $parentId
            ]);
            return false;
        }
    }

    /**
     * Duplicate menu with children
     */
    public function duplicateMenu(int $menuId, array $overrides = []): ?Menu
    {
        try {
            $originalMenu = Menu::with('children')->findOrFail($menuId);

            DB::transaction(function () use ($originalMenu, $overrides, &$duplicate) {
                // Use the new method if available
                if (method_exists($originalMenu, 'cloneWithChildren')) {
                    $duplicate = $originalMenu->cloneWithChildren($overrides);
                } else {
                    $duplicate = $this->cloneMenuManually($originalMenu, $overrides);
                }
            });

            $this->clearCache();
            return $duplicate;

        } catch (\Exception $e) {
            \Log::error('Failed to duplicate menu', [
                'error' => $e->getMessage(),
                'menu_id' => $menuId
            ]);
            return null;
        }
    }

    /**
     * Manual menu cloning if method doesn't exist
     */
    protected function cloneMenuManually(Menu $original, array $overrides = []): Menu
    {
        $clone = $original->replicate();

        // Apply overrides
        foreach ($overrides as $key => $value) {
            $clone->$key = $value;
        }

        // Ensure unique name if not overridden
        if (!isset($overrides['name'])) {
            $clone->name = $original->name . ' (Copy)';
        }

        $clone->save();

        // Clone children
        foreach ($original->children as $child) {
            $this->cloneMenuManually($child, ['parent_id' => $clone->id]);
        }

        return $clone;
    }

    /**
     * Import menus from array structure
     */
    public function importMenus(array $menuData, ?int $parentId = null): bool
    {
        try {
            DB::transaction(function () use ($menuData, $parentId) {
                foreach ($menuData as $index => $menuItem) {
                    $menu = Menu::create([
                        'name' => $menuItem['name'],
                        'url' => $menuItem['url'] ?? null,
                        'route_name' => $menuItem['route_name'] ?? null,
                        'parent_id' => $parentId,
                        'sort_order' => $menuItem['sort_order'] ?? $index,
                        'icon' => $menuItem['icon'] ?? null,
                        'show' => $menuItem['show'] ?? 'header',
                        'target' => $menuItem['target'] ?? '_self',
                        'permission' => $menuItem['permission'] ?? null,
                        'description' => $menuItem['description'] ?? null,
                        'is_active' => $menuItem['is_active'] ?? true,
                    ]);

                    // Import children if they exist
                    if (isset($menuItem['children']) && is_array($menuItem['children'])) {
                        $this->importMenus($menuItem['children'], $menu->id);
                    }
                }
            });

            $this->clearCache();
            return true;

        } catch (\Exception $e) {
            \Log::error('Failed to import menus', [
                'error' => $e->getMessage(),
                'data' => $menuData
            ]);
            return false;
        }
    }

    /**
     * Export menus to array structure
     */
    public function exportMenus(?int $parentId = null): array
    {
        $menus = Menu::where('parent_id', $parentId)
                    ->with('children')
                    ->ordered()
                    ->get();

        return $menus->map(function ($menu) {
            $data = $menu->toArray();

            if ($menu->children->isNotEmpty()) {
                $data['children'] = $this->exportMenus($menu->id);
            }

            return $data;
        })->toArray();
    }

    /**
     * Clear menu caches
     */
    public function clearCache(): void
    {
        Cache::tags($this->cacheTags)->flush();
    }

    /**
     * Generate cache key
     */
    protected function generateCacheKey(string $type, array $options = []): string
    {
        $keyParts = [
            'menu_service',
            $type,
            md5(serialize($options)),
            Auth::id() ?? 'guest'
        ];

        return implode(':', $keyParts);
    }

    /**
     * Warm up caches for common menu configurations
     */
    public function warmCache(): void
    {
        $locations = ['header', 'footer', 'sidebar'];
        $users = [null, Auth::id()]; // Guest and current user

        foreach ($locations as $location) {
            foreach ($users as $userId) {
                $this->getMenuTree([
                    'location' => $location,
                    'user_id' => $userId,
                    'active_only' => true
                ]);
            }
        }
    }

    /**
     * Get cache statistics
     */
    public function getCacheStatistics(): array
    {
        // This would depend on your cache driver
        // Basic implementation for demonstration
        return [
            'total_keys' => 0, // Would count cache keys
            'hit_rate' => 0.0, // Would calculate hit rate
            'memory_usage' => 0, // Would get memory usage
            'last_cleared' => Cache::get('menu_cache_last_cleared'),
        ];
    }
}
