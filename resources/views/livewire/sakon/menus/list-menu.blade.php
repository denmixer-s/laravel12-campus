<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-800 mb-2">Menu Management</h1>
                        <p class="text-slate-600">Manage navigation menus and their hierarchy for your application</p>
                    </div>

                    @can('create', App\Models\Menu::class)
                        <button wire:click="createMenu"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create New Menu
                        </button>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-slate-600">Total</p>
                        <p class="text-lg font-bold text-slate-900">{{ $menuStats['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-slate-600">Active</p>
                        <p class="text-lg font-bold text-slate-900">{{ $menuStats['active'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-slate-600">Inactive</p>
                        <p class="text-lg font-bold text-slate-900">{{ $menuStats['inactive'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-slate-600">Parents</p>
                        <p class="text-lg font-bold text-slate-900">{{ $menuStats['parents'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-amber-100 rounded-lg">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-slate-600">Children</p>
                        <p class="text-lg font-bold text-slate-900">{{ $menuStats['children'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="flex flex-col lg:flex-row gap-4">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <div class="relative">
                            <input wire:model.live.debounce.300ms="search" type="text"
                                placeholder="Search menus by name, URL, or route..."
                                class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Location Filter -->
                    <div class="lg:w-48">
                        <select wire:model.live="locationFilter"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">All Locations</option>
                            <option value="header">Header</option>
                            <option value="footer">Footer</option>
                            <option value="sidebar">Sidebar</option>
                            <option value="both">Both</option>
                            <option value="mobile">Mobile</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="lg:w-32">
                        <select wire:model.live="statusFilter"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">All Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <!-- Per Page Selector -->
                    <div class="lg:w-32">
                        <select wire:model.live="perPage"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="10">10 per page</option>
                            <option value="15">15 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menus Table -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left">
                                <button wire:click="sortBy('name')"
                                    class="flex items-center text-xs font-medium text-slate-500 uppercase tracking-wider hover:text-slate-700">
                                    Menu
                                    @if ($sortBy === 'name')
                                        <svg class="w-4 h-4 ml-1 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7" />
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th
                                class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                URL/Route</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Location</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-4 text-left">
                                <button wire:click="sortBy('sort_order')"
                                    class="flex items-center text-xs font-medium text-slate-500 uppercase tracking-wider hover:text-slate-700">
                                    Order
                                    @if ($sortBy === 'sort_order')
                                        <svg class="w-4 h-4 ml-1 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7" />
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th
                                class="px-6 py-4 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($menus as $menu)
                            <tr class="hover:bg-slate-50 transition-colors" wire:key="menu-{{ $menu->id }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <!-- Hierarchy Indicator -->
                                        @if ($menu->parent_id)
                                            <div class="flex items-center mr-2">
                                                <svg class="w-4 h-4 text-slate-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                        @endif

                                        <!-- Icon -->
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if ($menu->icon)
                                                <div
                                                    class="h-10 w-10 rounded-lg bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <!-- You can implement icon mapping here -->
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                    </svg>
                                                </div>
                                            @else
                                                <div
                                                    class="h-10 w-10 rounded-lg bg-gradient-to-r from-slate-400 to-slate-500 flex items-center justify-center">
                                                    <span
                                                        class="text-white font-medium text-sm">{{ substr($menu->name, 0, 2) }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Menu Info -->
                                        <div class="ml-4">
                                            <div class="flex items-center">
                                                <div class="text-sm font-medium text-slate-900">{{ $menu->name }}
                                                </div>
                                                @if ($menu->children_count > 0)
                                                    <span
                                                        class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $menu->children_count }}
                                                        {{ Str::plural('child', $menu->children_count) }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if ($menu->parent)
                                                <div class="text-xs text-slate-500">
                                                    Parent: {{ $menu->parent->name }}
                                                </div>
                                            @endif
                                            @if ($menu->permission)
                                                <div class="text-xs text-amber-600">
                                                    🔒 {{ $menu->permission }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-900">
                                        @if ($menu->route_name)
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800 mb-1">
                                                Route: {{ $menu->route_name }}
                                            </span>
                                        @endif
                                        @if ($menu->url)
                                            <div class="text-xs text-slate-600 break-all">
                                                URL: {{ Str::limit($menu->url, 50) }}
                                            </div>
                                        @endif
                                        @if (!$menu->route_name && !$menu->url)
                                            <span class="text-xs text-slate-400">No URL/Route</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium
                                    @if ($menu->show === 'header') bg-blue-100 text-blue-800
                                    @elseif($menu->show === 'footer') bg-purple-100 text-purple-800
                                    @elseif($menu->show === 'sidebar') bg-green-100 text-green-800
                                    @elseif($menu->show === 'both') bg-amber-100 text-amber-800
                                    @else bg-slate-100 text-slate-800 @endif">
                                        {{ ucfirst($menu->show) }}
                                    </span>
                                    @if ($menu->target === '_blank')
                                        <span
                                            class="ml-1 inline-flex items-center px-1 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                            New Tab
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <button wire:click="toggleStatus({{ $menu->id }})"
                                        class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium transition-colors
                                        @if ($menu->is_active) bg-green-100 text-green-800 hover:bg-green-200
                                        @else bg-red-100 text-red-800 hover:bg-red-200 @endif">
                                        @if ($menu->is_active)
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Active
                                        @else
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Inactive
                                        @endif
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-900">
                                    <div class="flex items-center space-x-1">
                                        <span class="font-medium">{{ $menu->sort_order }}</span>

                                        <!-- Move buttons -->
                                        <div class="flex flex-col space-y-1">
                                            <button wire:click="moveUp({{ $menu->id }})"
                                                class="p-1 text-slate-400 hover:text-slate-600 transition-colors"
                                                title="Move up">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 15l7-7 7 7" />
                                                </svg>
                                            </button>
                                            <button wire:click="moveDown({{ $menu->id }})"
                                                class="p-1 text-slate-400 hover:text-slate-600 transition-colors"
                                                title="Move down">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <!-- View Button -->
                                        <button wire:click="viewMenu({{ $menu->id }})"
                                            class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-medium rounded-md transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View
                                        </button>

                                        @can('update', $menu)
                                            <button wire:click="editMenu({{ $menu->id }})"
                                                class="inline-flex items-center px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-700 text-sm font-medium rounded-md transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </button>
                                        @endcan

                                        <!-- Duplicate Button -->
                                        <button wire:click="duplicateMenu({{ $menu->id }})"
                                            class="inline-flex items-center px-3 py-1.5 bg-green-100 hover:bg-green-200 text-green-700 text-sm font-medium rounded-md transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                            Copy
                                        </button>

                                        <!-- Delete Button -->
                                        @if ($this->canUserDeleteMenu($menu))
                                            <button wire:click="confirmDelete({{ $menu->id }})"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-medium rounded-md transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        @else
                                            @php
                                                $lockReason = '';
                                                if ($menu->children()->count() > 0) {
                                                    $lockReason = 'Menu has child items';
                                                } else {
                                                    $lockReason = 'No delete permission';
                                                }
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-500 text-sm font-medium rounded-md"
                                                title="{{ $lockReason }}">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                                Locked
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-slate-300 mb-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-slate-900 mb-2">No menus found</h3>
                                        <p class="text-slate-500">
                                            {{ $search ? 'Try adjusting your search criteria.' : 'Get started by creating your first menu item.' }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($menus->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $menus->links() }}
                </div>
            @endif
        </div>

        <!-- Delete Confirmation Modal -->
        @if ($confirmingMenuDeletion)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                wire:key="delete-modal-{{ $menuToDelete }}">
                <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 shadow-xl">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-medium text-gray-900">Delete Menu</h3>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-600 mb-3">
                            Are you sure you want to delete the menu <strong
                                class="text-gray-900">"{{ $menuToDeleteName }}"</strong>?
                        </p>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                            <p class="text-sm text-red-800">
                                <strong>⚠️ Warning:</strong> This action cannot be undone and will permanently remove
                                this menu item.
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button wire:click="cancelDelete"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button wire:click="delete" wire:loading.attr="disabled" wire:target="delete"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white font-medium rounded-lg transition-colors disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="delete">Delete Menu</span>
                            <span wire:loading wire:target="delete" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Deleting...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Loading Overlay -->
        <div wire:loading.flex wire:target="search,locationFilter,statusFilter,perPage,sortBy"
            class="fixed inset-0 z-40 bg-white bg-opacity-75 flex items-center justify-center">
            <div class="flex items-center space-x-2">
                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                <span class="text-sm text-slate-600">Loading...</span>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        console.log('Livewire initialized for ListMenu component');

        Livewire.on('menuDeleted', (event) => {
            console.log('Menu deleted event received:', event);
        });
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && @json($confirmingMenuDeletion)) {
            @this.call('cancelDelete');
        }
    });
</script>
