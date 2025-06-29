<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <div
                                class="h-8 w-8 rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <h1 class="text-3xl font-bold text-slate-800">Create New Menu</h1>
                        </div>
                        <p class="text-slate-600">Add a new menu item to your navigation system</p>
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

        <form wire:submit="create">
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
                                placeholder="Enter menu name (e.g., Dashboard, Users, Settings)"
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
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('description') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"></textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Quick Presets -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-slate-700 mb-3">Quick Presets</label>
                            <div class="flex flex-wrap gap-2">
                                <button wire:click="applyPreset('dashboard')" type="button"
                                    class="px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-medium rounded-md transition-colors">
                                    Dashboard
                                </button>
                                <button wire:click="applyPreset('users')" type="button"
                                    class="px-3 py-1.5 bg-green-100 hover:bg-green-200 text-green-700 text-sm font-medium rounded-md transition-colors">
                                    Users
                                </button>
                                <button wire:click="applyPreset('settings')" type="button"
                                    class="px-3 py-1.5 bg-purple-100 hover:bg-purple-200 text-purple-700 text-sm font-medium rounded-md transition-colors">
                                    Settings
                                </button>
                                <button wire:click="applyPreset('external')" type="button"
                                    class="px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-700 text-sm font-medium rounded-md transition-colors">
                                    External Link
                                </button>
                                <button wire:click="applyPreset('reset')" type="button"
                                    class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-md transition-colors">
                                    Clear All
                                </button>
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
                                placeholder="0"
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
                                            class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs rounded transition-colors">
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
                    <!-- Creation Progress -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Creation Progress</h3>

                        <div class="space-y-4">
                            <!-- Menu Name Status -->
                            <div class="flex items-center">
                                @if (trim($name))
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <div class="w-5 h-5 bg-slate-300 rounded-full mr-3"></div>
                                @endif
                                <span class="text-sm {{ trim($name) ? 'text-green-700' : 'text-slate-600' }}">
                                    Menu name provided
                                </span>
                            </div>

                            <!-- URL/Route Status -->
                            <div class="flex items-center">
                                @if (($urlType === 'manual' && trim($url)) || ($urlType === 'route' && trim($route_name)))
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <div class="w-5 h-5 bg-slate-300 rounded-full mr-3"></div>
                                @endif
                                <span
                                    class="text-sm {{ ($urlType === 'manual' && trim($url)) || ($urlType === 'route' && trim($route_name)) ? 'text-green-700' : 'text-slate-600' }}">
                                    URL/Route configured
                                </span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mt-4">
                            <div class="flex justify-between text-xs text-slate-600 mb-1">
                                <span>Completion</span>
                                <span>{{ $this->canCreate ? '100' : (trim($name) ? '50' : '0') }}%</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-300"
                                    style="width: {{ $this->canCreate ? '100' : (trim($name) ? '50' : '0') }}%"></div>
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
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <button type="submit" wire:loading.attr="disabled" {{ !$this->canCreate ? 'disabled' : '' }}
                            class="w-full inline-flex justify-center items-center px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 disabled:from-slate-300 disabled:to-slate-400 text-white font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="create">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Create Menu
                            </span>
                            <span wire:loading wire:target="create" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Creating Menu...
                            </span>
                        </button>

                        <button wire:click="resetForm" type="button"
                            class="w-full mt-3 inline-flex justify-center items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset Form
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Loading Overlay -->
    <div wire:loading.flex wire:target="create"
        class="fixed inset-0 z-50 bg-white bg-opacity-75 flex items-center justify-center">
        <div class="flex flex-col items-center space-y-4">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="text-sm text-slate-600 font-medium">Creating menu item...</p>
        </div>
    </div>
</div>
