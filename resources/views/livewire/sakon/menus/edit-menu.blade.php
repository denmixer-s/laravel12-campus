<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <div
                                class="h-8 w-8 rounded-lg bg-gradient-to-r from-amber-500 to-orange-600 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <h1 class="text-3xl font-bold text-slate-800">Edit Menu</h1>
                        </div>
                        <p class="text-slate-600">Modify menu item settings for <span
                                class="font-medium text-slate-800">"{{ $menu->name }}"</span></p>
                    </div>

                    <div class="flex items-center gap-3">
                        <button wire:click="cancel" type="button"
                            class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancel
                        </button>

                        <button wire:click="duplicateMenu" type="button"
                            class="inline-flex items-center px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            Duplicate
                        </button>
                    </div>
                </div>

                <!-- Menu Info Bar -->
                <div class="mt-4 pt-4 border-t border-slate-200">
                    <div class="flex flex-wrap items-center gap-4 text-sm">
                        <div class="flex items-center text-slate-600">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-medium">{{ $this->childrenCount }}</span> child items
                        </div>
                        <div class="flex items-center text-slate-600">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3a4 4 0 118 0v4m-4 6v6m-8 0h16a2 2 0 002-2V9a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Created: {{ $menu->created_at->format('M d, Y') }}
                        </div>
                        <div class="flex items-center text-slate-600">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Updated: {{ $menu->updated_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if ($showSuccessMessage)
            <div class="mb-6">
                <div class="bg-green-50 rounded-lg border border-green-200 p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-green-800 font-medium">{{ $successMessage }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Changes Alert -->
        @if ($hasChanges)
            <div class="mb-6">
                <div class="bg-amber-50 rounded-lg border border-amber-200 p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-amber-500 mr-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        <div class="flex-1">
                            <p class="text-amber-800 font-medium">You have unsaved changes</p>
                            <p class="text-amber-700 text-sm mt-1">Don't forget to save your changes before leaving this
                                page.</p>
                        </div>
                        <button wire:click="resetForm" type="button"
                            class="text-amber-600 hover:text-amber-800 text-sm font-medium">
                            Reset Changes
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <form wire:submit="update">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-800 mb-4">Basic Information</h2>

                        <!-- Menu Name -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                                Menu Name <span class="text-red-500">*</span>
                            </label>
                            <input wire:model.live.debounce.300ms="name" type="text" id="name"
                                placeholder="Enter menu name"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('name') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-2">
                                Description
                            </label>
                            <textarea wire:model.live.debounce.500ms="description" id="description" rows="3"
                                placeholder="Optional description for this menu item"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('description') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">{{ $description }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Menu Statistics -->
                        <div class="grid grid-cols-2 gap-4 p-4 bg-slate-50 rounded-lg">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-slate-800">{{ $this->childrenCount }}</div>
                                <div class="text-sm text-slate-600">Child Items</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-slate-800">{{ $menu->sort_order }}</div>
                                <div class="text-sm text-slate-600">Current Order</div>
                            </div>
                        </div>
                    </div>

                    <!-- URL Configuration -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-800 mb-4">URL Configuration</h2>

                        <!-- URL Type Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-slate-700 mb-3">URL Type</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input wire:model.live="urlType" type="radio" value="manual"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300">
                                    <span class="ml-2 text-sm text-slate-700">Manual URL</span>
                                </label>
                                <label class="flex items-center">
                                    <input wire:model.live="urlType" type="radio" value="route"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300">
                                    <span class="ml-2 text-sm text-slate-700">Named Route</span>
                                </label>
                            </div>
                        </div>

                        @if ($urlType === 'manual')
                            <!-- Manual URL -->
                            <div class="mb-6">
                                <label for="url" class="block text-sm font-medium text-slate-700 mb-2">
                                    URL <span class="text-red-500">*</span>
                                </label>
                                <input wire:model.live.debounce.300ms="url" type="text" id="url"
                                    placeholder="https://example.com or /dashboard"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('url') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('url')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @else
                            <!-- Named Route -->
                            <div class="mb-6">
                                <label for="route_name" class="block text-sm font-medium text-slate-700 mb-2">
                                    Route Name <span class="text-red-500">*</span>
                                </label>
                                <select wire:model.live="route_name" id="route_name"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('route_name') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Select a route...</option>
                                    @foreach ($availableRoutes as $routeName => $routeLabel)
                                        <option value="{{ $routeName }}">{{ $routeLabel }}</option>
                                    @endforeach
                                </select>
                                @error('route_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <!-- Link Target -->
                        <div class="mb-6">
                            <label for="target" class="block text-sm font-medium text-slate-700 mb-2">
                                Link Target
                            </label>
                            <select wire:model.live="target" id="target"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="_self">Same Window</option>
                                <option value="_blank">New Window/Tab</option>
                                <option value="_parent">Parent Frame</option>
                                <option value="_top">Top Frame</option>
                            </select>
                        </div>
                    </div>

                    <!-- Menu Hierarchy & Settings -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-800 mb-4">Hierarchy & Settings</h2>

                        <!-- Parent Menu -->
                        <div class="mb-6">
                            <label for="parent_id" class="block text-sm font-medium text-slate-700 mb-2">
                                Parent Menu
                            </label>
                            <select wire:model.live="parent_id" id="parent_id"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('parent_id') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                <option value="">None (Top Level)</option>
                                @foreach ($parentMenus as $id => $parentName)
                                    <option value="{{ $id }}">{{ $parentName }}</option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sort Order -->
                        <div class="mb-6">
                            <label for="sort_order" class="block text-sm font-medium text-slate-700 mb-2">
                                Sort Order
                            </label>
                            <input wire:model.live="sort_order" type="number" id="sort_order" min="0"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('sort_order') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('sort_order')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Display Location -->
                        <div class="mb-6">
                            <label for="show" class="block text-sm font-medium text-slate-700 mb-2">
                                Display Location
                            </label>
                            <select wire:model.live="show" id="show"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="header">Header Only</option>
                                <option value="footer">Footer Only</option>
                                <option value="sidebar">Sidebar Only</option>
                                <option value="both">Header & Footer</option>
                                <option value="mobile">Mobile Only</option>
                            </select>
                        </div>

                        <!-- Icon -->
                        <div class="mb-6">
                            <label for="icon" class="block text-sm font-medium text-slate-700 mb-2">
                                Icon (Optional)
                            </label>
                            <input wire:model.live.debounce.300ms="icon" type="text" id="icon"
                                placeholder="home, users, settings, etc."
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">

                            <!-- Common Icons -->
                            <div class="mt-2">
                                <p class="text-xs text-slate-500 mb-2">Common icons:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach (['home', 'users', 'settings', 'dashboard', 'menu', 'search', 'bell', 'mail', 'star', 'heart'] as $iconName)
                                        <button wire:click="selectIcon('{{ $iconName }}')" type="button"
                                            class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs rounded transition-colors {{ $icon === $iconName ? 'bg-blue-100 text-blue-700' : '' }}">
                                            {{ $iconName }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Permission -->
                        <div class="mb-6">
                            <label for="permission" class="block text-sm font-medium text-slate-700 mb-2">
                                Required Permission (Optional)
                            </label>
                            <select wire:model.live="permission" id="permission"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">No permission required</option>
                                @foreach ($availablePermissions as $permissionName)
                                    <option value="{{ $permissionName }}">{{ $permissionName }}</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-slate-500">Leave empty if this menu should be visible to all
                                users</p>
                        </div>

                        <!-- Active Status -->
                        <div class="mb-6">
                            <label class="flex items-center cursor-pointer">
                                <input wire:model.live="is_active" type="checkbox"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                                <span class="ml-3 text-sm font-medium text-slate-700">Active</span>
                            </label>
                            <p class="mt-1 text-xs text-slate-500">Inactive menus will not be displayed in navigation
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Update Status -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Update Status</h3>

                        <div class="space-y-4">
                            <!-- Changes Status -->
                            <div class="flex items-center">
                                @if ($hasChanges)
                                    <svg class="w-5 h-5 text-amber-500 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                                <span class="text-sm {{ $hasChanges ? 'text-amber-700' : 'text-green-700' }}">
                                    {{ $hasChanges ? 'Unsaved changes detected' : 'No pending changes' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Menu Summary -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Menu Summary</h3>

                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-slate-600">Name:</span>
                                <p class="font-medium text-slate-800">{{ $name ?: 'Not specified' }}</p>
                            </div>

                            <div>
                                <span class="text-sm text-slate-600">Type:</span>
                                <p class="font-medium text-slate-800">{{ ucfirst($urlType) }}
                                    {{ $urlType === 'manual' ? 'URL' : 'Route' }}</p>
                            </div>

                            @if ($urlType === 'manual' && $url)
                                <div>
                                    <span class="text-sm text-slate-600">URL:</span>
                                    <p class="font-medium text-slate-800 break-all">{{ $url }}</p>
                                </div>
                            @endif

                            @if ($urlType === 'route' && $route_name)
                                <div>
                                    <span class="text-sm text-slate-600">Route:</span>
                                    <p class="font-medium text-slate-800">{{ $route_name }}</p>
                                </div>
                            @endif

                            <div>
                                <span class="text-sm text-slate-600">Parent:</span>
                                <p class="font-medium text-slate-800">{{ $this->selectedParentName }}</p>
                            </div>

                            <div>
                                <span class="text-sm text-slate-600">Location:</span>
                                <p class="font-medium text-slate-800">{{ ucfirst($show) }}</p>
                            </div>

                            <div>
                                <span class="text-sm text-slate-600">Status:</span>
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium {{ $is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            @if ($permission)
                                <div>
                                    <span class="text-sm text-slate-600">Permission:</span>
                                    <p class="font-medium text-slate-800">{{ $permission }}</p>
                                </div>
                            @endif

                            <div>
                                <span class="text-sm text-slate-600">Children:</span>
                                <p class="font-medium text-slate-800">{{ $this->childrenCount }} items</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <button type="submit" wire:loading.attr="disabled" {{ !$this->canUpdate ? 'disabled' : '' }}
                            class="w-full inline-flex justify-center items-center px-4 py-3 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 disabled:from-slate-300 disabled:to-slate-400 text-white font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="update">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Update Menu
                            </span>
                            <span wire:loading wire:target="update" class="flex items-center">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                                Updating Menu...
                            </span>
                        </button>

                        @if (!$this->canUpdate && $hasChanges)
                            <p class="mt-2 text-xs text-slate-500 text-center">
                                {{ empty(trim($name)) ? 'Menu name is required' : 'Valid URL or route is required' }}
                            </p>
                        @elseif(!$hasChanges)
                            <p class="mt-2 text-xs text-slate-500 text-center">
                                No changes to save
                            </p>
                        @endif

                        <button wire:click="resetForm" type="button" {{ !$hasChanges ? 'disabled' : '' }}
                            class="w-full mt-3 inline-flex justify-center items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 disabled:bg-slate-50 text-slate-700 disabled:text-slate-400 font-medium rounded-lg transition-colors duration-200 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset Changes
                        </button>
                    </div>

                    <!-- Danger Zone -->
                    @if ($this->childrenCount > 0)
                        <div class="bg-white rounded-lg shadow-sm border border-red-200 p-6">
                            <h3 class="text-lg font-semibold text-red-800 mb-4">⚠️ Warning</h3>
                            <div class="text-sm text-red-700 space-y-2">
                                <p>This menu has <strong>{{ $this->childrenCount }}
                                        {{ Str::plural('child item', $this->childrenCount) }}</strong>.</p>
                                <p>Changes to this menu may affect the hierarchy and display of child items.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </form>

        <!-- Loading Overlay -->
        <div wire:loading.flex wire:target="update"
            class="fixed inset-0 z-50 bg-white bg-opacity-75 flex items-center justify-center">
            <div class="flex flex-col items-center space-y-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="text-sm text-slate-600 font-medium">Updating menu item...</p>
            </div>
        </div>

        <!-- Unsaved Changes Warning Script -->
        @if ($hasChanges)
            <script>
                window.addEventListener('beforeunload', function(e) {
                    e.preventDefault();
                    e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
                });
            </script>
        @endif
    </div>
</div>
