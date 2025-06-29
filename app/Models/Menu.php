<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'route_name',
        'parent_id',
        'sort_order',
        'icon',
        'show',
        'target',
        'permission',
        'is_active',
        'description',
        'meta_data',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'meta_data'  => 'array',
        'sort_order' => 'integer',
    ];

    /**
     * Get the parent menu item.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    /**
     * Get the child menu items.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Get active child menu items.
     */
    public function activeChildren(): HasMany
    {
        return $this->children()->where('is_active', true);
    }

    /**
     * Scope: Get only active menus.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Get parent menus only.
     */
    public function scopeParents(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope: Get menus by location.
     */
    public function scopeByLocation(Builder $query, string $location): Builder
    {
        return $query->where('show', $location)->orWhere('show', 'both');
    }

    /**
     * Scope: Get ordered menus.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Check if user has permission to access this menu.
     */
    public function canAccess(): bool
    {
        if (empty($this->permission)) {
            return true;
        }

        if (! auth()->check()) {
            return false;
        }

        return auth()->user()->can($this->permission) || auth()->user()->hasRole('Super Admin');
    }

    /**
     * Get the URL for this menu item.
     */
    public function getUrl(): string
    {
        if (! empty($this->route_name)) {
            try {
                return route($this->route_name);
            } catch (\Exception $e) {
                // If route doesn't exist, fall back to URL
            }
        }

        return $this->url ?? '#';
    }

    /**
     * Check if this menu item is currently active.
     */
    public function isCurrentlyActive(): bool
    {
        $currentUrl = request()->url();
        $menuUrl    = $this->getUrl();

        // Exact match
        if ($currentUrl === $menuUrl) {
            return true;
        }

        // Check if current route matches
        if (! empty($this->route_name) && request()->routeIs($this->route_name)) {
            return true;
        }

        // Check if any child is active
        foreach ($this->activeChildren as $child) {
            if ($child->isCurrentlyActive()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the depth level of this menu item.
     */
    public function getDepth(): int
    {
        $depth  = 0;
        $parent = $this->parent;

        while ($parent) {
            $depth++;
            $parent = $parent->parent;
        }

        return $depth;
    }

    /**
     * Get all ancestors of this menu item.
     */
    public function getAncestors(): array
    {
        $ancestors = [];
        $parent    = $this->parent;

        while ($parent) {
            array_unshift($ancestors, $parent);
            $parent = $parent->parent;
        }

        return $ancestors;
    }

    /**
     * Get breadcrumb trail for this menu item.
     */
    public function getBreadcrumbs(): array
    {
        $breadcrumbs   = $this->getAncestors();
        $breadcrumbs[] = $this;

        return $breadcrumbs;
    }

    /**
     * Check if this menu has children.
     */
    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    /**
     * Check if this menu has accessible children.
     */
    public function hasAccessibleChildren(): bool
    {
        return $this->activeChildren->filter(fn($child) => $child->canAccess())->isNotEmpty();
    }

    /**
     * Get accessible children.
     */
    public function getAccessibleChildren()
    {
        return $this->activeChildren->filter(fn($child) => $child->canAccess());
    }

    /**
     * Get children count attribute (for compatibility with withCount)
     */
    public function getChildrenCountAttribute()
    {
        return $this->children()->count();
    }

    /**
     * Get active children count
     */
    public function getActiveChildrenCountAttribute()
    {
        return $this->children()->where('is_active', true)->count();
    }

    #####################

    // Add these new scopes to your existing Menu model
    public function scopeTree(Builder $query): Builder
    {
        return $query->with([
            'children'          => function ($query) {
                $query->active()->ordered();
            },
            'children.children' => function ($query) {
                $query->active()->ordered();
            },
        ]);
    }

    public function scopeForShow(Builder $query, string $location): Builder
    {
        return $query->where(function ($q) use ($location) {
            $q->where('show', $location)
                ->orWhere('show', 'both')
                ->orWhere('show', 'all');
        });
    }

    public function scopeAllowedForUser(Builder $query, $user = null): Builder
    {
        if (! $user) {
            return $query->whereNull('permission');
        }

        $userPermissions = $user->getAllPermissions()->pluck('name')->toArray();

        return $query->where(function ($q) use ($userPermissions) {
            $q->whereNull('permission')
                ->orWhereIn('permission', $userPermissions);
        });
    }

// Add these helper methods (they won't conflict with existing ones)
    public function getResolvedUrl(): string
    {
        if ($this->route_name) {
            try {
                return route($this->route_name);
            } catch (\Exception $e) {
                \Log::warning("Route '{$this->route_name}' not found for menu '{$this->name}'");
            }
        }
        return $this->url ?: '#';
    }

    public function isCurrentRoute(): bool
    {
        $currentUrl   = request()->url();
        $currentRoute = request()->route()?->getName();

        if ($this->url && $currentUrl === $this->url) {
            return true;
        }

        if ($this->route_name && $currentRoute === $this->route_name) {
            return true;
        }

        return false;
    }

    public function getMenuPath(): \Illuminate\Support\Collection
    {
        $path = $this->getBreadcrumbs(); // Use your existing method
        return collect($path);
    }

}
