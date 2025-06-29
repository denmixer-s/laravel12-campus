<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="h-8 w-8 rounded-lg bg-gradient-to-r from-emerald-500 to-teal-600 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <h1 class="text-3xl font-bold text-slate-800">Create New Permission</h1>
                        </div>
                        <p class="text-slate-600">Define a new permission to control access to specific features and actions</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <button wire:click="cancel"
                                type="button"
                                class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if($showSuccessMessage)
        <div class="mb-6">
            <div class="bg-green-50 rounded-lg border border-green-200 p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                    <!-- Permission Details Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-800 mb-4">Permission Details</h2>

                        <!-- Auto-Generation Toggle -->
                        <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <label class="flex items-center cursor-pointer">
                                <input wire:model.live="autoGenerateFromCategory"
                                       type="checkbox"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                                <span class="ml-3 text-sm font-medium text-blue-800">Auto-generate permission name from category and actions</span>
                            </label>
                            <p class="text-xs text-blue-600 mt-1 ml-7">When enabled, the permission name will be automatically generated based on your selections below.</p>
                        </div>

                        @if($autoGenerateFromCategory)
                        <!-- Category Selection -->
                        <div class="mb-6">
                            <label for="selectedCategory" class="block text-sm font-medium text-slate-700 mb-2">
                                Permission Category <span class="text-red-500">*</span>
                            </label>
                            <div class="flex gap-2">
                                <select wire:model.live="selectedCategory"
                                        id="selectedCategory"
                                        class="flex-1 px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                                    <option value="">Select a category</option>
                                    @foreach($permissionCategories as $category)
                                    <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                                    @endforeach
                                </select>
                                <input wire:model.live="selectedCategory"
                                       type="text"
                                       placeholder="Or enter custom..."
                                       class="flex-1 px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                            </div>
                        </div>

                        <!-- Actions Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-slate-700 mb-3">Actions (Optional)</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                @foreach($availableActions as $action)
                                <label class="flex items-center cursor-pointer p-2 hover:bg-slate-50 rounded">
                                    <input wire:model.live="selectedActions"
                                           type="checkbox"
                                           value="{{ $action }}"
                                           class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300 rounded">
                                    <span class="ml-2 text-sm text-slate-700 capitalize">{{ $action }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Permission Name Input -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                                Permission Name <span class="text-red-500">*</span>
                            </label>
                            <input wire:model.live.debounce.300ms="name"
                                   type="text"
                                   id="name"
                                   {{ $autoGenerateFromCategory ? 'readonly' : '' }}
                                   placeholder="Enter permission name (e.g., view-user, create-post, manage-admin)"
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors {{ $autoGenerateFromCategory ? 'bg-slate-50' : '' }} @error('name') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Description Input -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-2">
                                Description (Optional)
                            </label>
                            <textarea wire:model.live.debounce.300ms="description"
                                      id="description"
                                      rows="3"
                                      placeholder="Describe what this permission allows users to do..."
                                      class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors resize-none @error('description') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"></textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Guard Name Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-slate-700 mb-3">
                                Guard Type <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="flex items-center cursor-pointer p-3 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors {{ $guard_name === 'web' ? 'bg-emerald-50 border-emerald-300' : '' }}">
                                    <input wire:model.live="guard_name"
                                           type="radio"
                                           value="web"
                                           class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300">
                                    <div class="ml-3">
                                        <span class="text-sm font-medium text-slate-900">Web</span>
                                        <p class="text-xs text-slate-600">For web application users</p>
                                    </div>
                                </label>
                                <label class="flex items-center cursor-pointer p-3 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors {{ $guard_name === 'api' ? 'bg-emerald-50 border-emerald-300' : '' }}">
                                    <input wire:model.live="guard_name"
                                           type="radio"
                                           value="api"
                                           class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300">
                                    <div class="ml-3">
                                        <span class="text-sm font-medium text-slate-900">API</span>
                                        <p class="text-xs text-slate-600">For API access tokens</p>
                                    </div>
                                </label>
                            </div>
                            @error('guard_name')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Quick Presets -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-slate-700 mb-3">Quick Presets</label>
                            <div class="flex flex-wrap gap-2">
                                <button wire:click="applyPreset('admin')"
                                        type="button"
                                        class="px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-medium rounded-md transition-colors">
                                    Admin Access
                                </button>
                                <button wire:click="applyPreset('user-management')"
                                        type="button"
                                        class="px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-medium rounded-md transition-colors">
                                    User Management
                                </button>
                                <button wire:click="applyPreset('content')"
                                        type="button"
                                        class="px-3 py-1.5 bg-green-100 hover:bg-green-200 text-green-700 text-sm font-medium rounded-md transition-colors">
                                    Content Management
                                </button>
                                <button wire:click="applyPreset('api')"
                                        type="button"
                                        class="px-3 py-1.5 bg-purple-100 hover:bg-purple-200 text-purple-700 text-sm font-medium rounded-md transition-colors">
                                    API Access
                                </button>
                                <button wire:click="applyPreset('reset')"
                                        type="button"
                                        class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-md transition-colors">
                                    Clear All
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Role Assignment Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-semibold text-slate-800">Assign to Roles (Optional)</h2>
                            <span class="text-sm text-slate-500">
                                {{ $this->selectedRolesCount }} selected
                            </span>
                        </div>

                        <p class="text-sm text-slate-600 mb-4">Select which roles should have this permission by default.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-64 overflow-y-auto">
                            @forelse($this->availableRoles as $role)
                            <label class="flex items-center cursor-pointer p-3 hover:bg-slate-50 rounded-lg transition-colors">
                                <input wire:model.live="selectedRoles"
                                       type="checkbox"
                                       value="{{ $role->id }}"
                                       class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300 rounded">
                                <div class="ml-3 flex-1">
                                    <span class="text-sm font-medium text-slate-900">{{ $role->name }}</span>
                                    {{-- FIXED: Use preloaded users_count to prevent lazy loading --}}
                                    <p class="text-xs text-slate-500">{{ $role->users_count }} {{ Str::plural('user', $role->users_count) }}</p>
                                </div>
                            </label>
                            @empty
                            <p class="text-sm text-slate-500 col-span-full">No roles available</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Bulk Creation Card -->
                    @if($selectedCategory)
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-800 mb-4">Bulk Permission Creation</h2>
                        <p class="text-sm text-slate-600 mb-4">Generate multiple related permissions for the selected category at once.</p>

                        <div class="flex flex-wrap gap-3">
                            <button wire:click="generateCrudPermissions"
                                    type="button"
                                    class="inline-flex items-center px-4 py-2 bg-amber-100 hover:bg-amber-200 text-amber-700 font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                Generate Basic CRUD
                            </button>

                            <button wire:click="generateFullCrudPermissions"
                                    type="button"
                                    class="inline-flex items-center px-4 py-2 bg-orange-100 hover:bg-orange-200 text-orange-700 font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Generate Full CRUD
                            </button>
                        </div>

                        <div class="mt-4 p-3 bg-slate-50 rounded-lg">
                            <p class="text-xs text-slate-600">
                                <strong>Basic CRUD:</strong> view, create, edit, delete<br>
                                <strong>Full CRUD:</strong> view, view-any, create, edit, update, delete, restore, force-delete
                            </p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Creation Progress Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Creation Progress</h3>

                        <div class="space-y-4">
                            <!-- Permission Name Status -->
                            <div class="flex items-center">
                                @if(trim($name))
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    <div class="w-5 h-5 bg-slate-300 rounded-full mr-3"></div>
                                @endif
                                <span class="text-sm {{ trim($name) ? 'text-green-700' : 'text-slate-600' }}">
                                    Permission name provided
                                </span>
                            </div>

                            <!-- Guard Type Status -->
                            <div class="flex items-center">
                                @if(in_array($guard_name, ['web', 'api']))
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    <div class="w-5 h-5 bg-slate-300 rounded-full mr-3"></div>
                                @endif
                                <span class="text-sm {{ in_array($guard_name, ['web', 'api']) ? 'text-green-700' : 'text-slate-600' }}">
                                    Guard type selected
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
                                <div class="bg-gradient-to-r from-emerald-500 to-teal-600 h-2 rounded-full transition-all duration-300"
                                     style="width: {{ $this->canCreate ? '100' : (trim($name) ? '50' : '0') }}%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Permission Preview Card -->
                    @if($this->permissionPreview)
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Permission Preview</h3>

                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-slate-600">Name:</span>
                                <p class="font-medium text-slate-800 font-mono text-sm bg-slate-50 px-2 py-1 rounded">{{ $this->permissionPreview['name'] }}</p>
                            </div>

                            <div>
                                <span class="text-sm text-slate-600">Guard:</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium {{ $this->permissionPreview['guard_name'] === 'web' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ $this->permissionPreview['guard_name'] }}
                                </span>
                            </div>

                            <div>
                                <span class="text-sm text-slate-600">Category:</span>
                                <p class="font-medium text-slate-800">{{ $this->permissionPreview['category'] }}</p>
                            </div>

                            <div>
                                <span class="text-sm text-slate-600">Description:</span>
                                <p class="text-slate-800 text-sm">{{ $this->permissionPreview['description'] }}</p>
                            </div>

                            <div>
                                <span class="text-sm text-slate-600">Assigned Roles:</span>
                                <p class="font-medium text-slate-800">{{ $this->permissionPreview['roles_count'] }} role(s)</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <button type="submit"
                                wire:loading.attr="disabled"
                                {{ !$this->canCreate ? 'disabled' : '' }}
                                class="w-full inline-flex justify-center items-center px-4 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 disabled:from-slate-300 disabled:to-slate-400 text-white font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="create">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Create Permission
                            </span>
                            <span wire:loading wire:target="create" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Creating Permission...
                            </span>
                        </button>

                        <button wire:click="resetForm"
                                type="button"
                                class="w-full mt-3 inline-flex justify-center items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset Form
                        </button>
                    </div>

                    <!-- Tips Card -->
                    <div class="bg-blue-50 rounded-lg border border-blue-200 p-6">
                        <div class="flex items-center mb-3">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="text-sm font-semibold text-blue-800">💡 Naming Tips</h3>
                        </div>
                        <div class="text-sm text-blue-700 space-y-2">
                            <p><strong>Good Examples:</strong></p>
                            <ul class="list-disc list-inside space-y-1 text-xs">
                                <li><code>view-user</code> - View user profiles</li>
                                <li><code>create-post</code> - Create new posts</li>
                                <li><code>manage-admin</code> - Full admin access</li>
                                <li><code>api-access</code> - Access API endpoints</li>
                            </ul>
                            <p class="text-xs mt-3">Use kebab-case and be descriptive but concise.</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Loading Overlay -->
    <div wire:loading.flex wire:target="create,generateCrudPermissions,generateFullCrudPermissions" class="fixed inset-0 z-50 bg-white bg-opacity-75 flex items-center justify-center">
        <div class="flex flex-col items-center space-y-4">
            <svg class="animate-spin h-8 w-8 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-sm text-slate-600 font-medium">
                <span wire:loading wire:target="create">Creating permission...</span>
                <span wire:loading wire:target="generateCrudPermissions">Generating CRUD permissions...</span>
                <span wire:loading wire:target="generateFullCrudPermissions">Generating full CRUD permissions...</span>
            </p>
        </div>
    </div>
</div>
