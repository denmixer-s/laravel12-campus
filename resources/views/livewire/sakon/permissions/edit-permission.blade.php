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
                            <h1 class="text-3xl font-bold text-slate-800">Edit Permission</h1>
                        </div>
                        <p class="text-slate-600">Modify permission settings and role assignments for <span
                                class="font-medium text-slate-800 font-mono">"{{ $permission->name }}"</span></p>
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

                        <button wire:click="duplicatePermission" type="button"
                            class="inline-flex items-center px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            Duplicate
                        </button>
                    </div>
                </div>

                <!-- Permission Info Bar -->
                <div class="mt-4 pt-4 border-t border-slate-200">
                    <div class="flex flex-wrap items-center gap-4 text-sm">
                        <div class="flex items-center text-slate-600">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="font-medium">{{ $this->permissionStats['roles_count'] }}</span> roles assigned
                        </div>
                        <div class="flex items-center text-slate-600">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                            <span class="font-medium">{{ $this->permissionStats['users_affected'] }}</span> users
                            affected
                        </div>
                        <div class="flex items-center text-slate-600">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium {{ $permission->guard_name === 'web' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ $permission->guard_name }}
                            </span>
                        </div>
                        <div class="flex items-center text-slate-600">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3a4 4 0 118 0v4m-4 6v6m-8 0h16a2 2 0 002-2V9a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Created: {{ $this->permissionStats['created_date'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                    <!-- Permission Details Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-800 mb-4">Permission Details</h2>

                        <!-- Permission Name Input -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                                Permission Name <span class="text-red-500">*</span>
                            </label>
                            <input wire:model.live.debounce.300ms="name" type="text" id="name"
                                placeholder="Enter permission name"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors font-mono @error('name') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
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

                        <!-- Description Input -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-2">
                                Description (Optional)
                            </label>
                            <textarea wire:model.live.debounce.300ms="description" id="description" rows="3"
                                placeholder="Describe what this permission allows users to do..."
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors resize-none @error('description') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">{{ $description }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Guard Name (Read-only) -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-slate-700 mb-3">
                                Guard Type <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <div
                                    class="flex items-center p-3 border-2 rounded-lg {{ $guard_name === 'web' ? 'border-amber-300 bg-amber-50' : 'border-slate-200 bg-slate-50' }}">
                                    <input type="radio" value="web" {{ $guard_name === 'web' ? 'checked' : '' }}
                                        disabled class="h-4 w-4 text-amber-600 border-slate-300">
                                    <div class="ml-3">
                                        <span class="text-sm font-medium text-slate-900">Web</span>
                                        <p class="text-xs text-slate-600">For web application users</p>
                                    </div>
                                </div>
                                <div
                                    class="flex items-center p-3 border-2 rounded-lg {{ $guard_name === 'api' ? 'border-amber-300 bg-amber-50' : 'border-slate-200 bg-slate-50' }}">
                                    <input type="radio" value="api" {{ $guard_name === 'api' ? 'checked' : '' }}
                                        disabled class="h-4 w-4 text-amber-600 border-slate-300">
                                    <div class="ml-3">
                                        <span class="text-sm font-medium text-slate-900">API</span>
                                        <p class="text-xs text-slate-600">For API access tokens</p>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-slate-500">⚠️ Guard type cannot be changed for existing
                                permissions</p>
                        </div>
                    </div>

                    <!-- Role Assignment Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-semibold text-slate-800">Role Assignment</h2>
                            <span class="text-sm text-slate-500">
                                {{ $this->selectedRolesCount }} selected
                            </span>
                        </div>

                        <p class="text-sm text-slate-600 mb-4">Select which roles should have this permission.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-64 overflow-y-auto">
                            @forelse($this->availableRoles as $role)
                                <label
                                    class="flex items-center cursor-pointer p-3 hover:bg-slate-50 rounded-lg transition-colors">
                                    <input wire:model.live="selectedRoles" type="checkbox"
                                        value="{{ $role->id }}"
                                        class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-slate-300 rounded">
                                    <div class="ml-3 flex-1">
                                        <span class="text-sm font-medium text-slate-900">{{ $role->name }}</span>
                                        <p class="text-xs text-slate-500">{{ $role->users_count }}
                                            {{ Str::plural('user', $role->users_count) }}</p>
                                    </div>
                                </label>
                            @empty
                                <p class="text-sm text-slate-500 col-span-full">No roles available</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Update Status Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Update Status</h3>

                        <div class="space-y-4">
                            <!-- Name Change Status -->
                            <div class="flex items-center">
                                @if ($name !== $originalData['name'])
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
                                <span
                                    class="text-sm {{ $name !== $originalData['name'] ? 'text-amber-700' : 'text-green-700' }}">
                                    Permission name {{ $name !== $originalData['name'] ? 'modified' : 'unchanged' }}
                                </span>
                            </div>

                            <!-- Roles Change Status -->
                            <div class="flex items-center">
                                @if (count($this->addedRoles) > 0 || count($this->removedRoles) > 0)
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
                                <span
                                    class="text-sm {{ count($this->addedRoles) > 0 || count($this->removedRoles) > 0 ? 'text-amber-700' : 'text-green-700' }}">
                                    Role assignments
                                    {{ count($this->addedRoles) > 0 || count($this->removedRoles) > 0 ? 'modified' : 'unchanged' }}
                                </span>
                            </div>
                        </div>

                        <!-- Changes Summary -->
                        @if ($hasChanges)
                            <div class="mt-4 pt-4 border-t border-slate-200">
                                <h4 class="text-sm font-medium text-slate-800 mb-2">Changes Summary:</h4>
                                <div class="space-y-2 text-sm">
                                    @if ($name !== $originalData['name'])
                                        <div class="flex items-center text-amber-700">
                                            <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Name changed
                                        </div>
                                    @endif

                                    @if (count($this->addedRoles) > 0)
                                        <div class="flex items-center text-green-700">
                                            <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            +{{ count($this->addedRoles) }} roles added
                                        </div>
                                    @endif

                                    @if (count($this->removedRoles) > 0)
                                        <div class="flex items-center text-red-700">
                                            <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            -{{ count($this->removedRoles) }} roles removed
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
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
                                Update Permission
                            </span>
                            <span wire:loading wire:target="update" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Updating Permission...
                            </span>
                        </button>

                        @if (!$this->canUpdate && $hasChanges)
                            <p class="mt-2 text-xs text-slate-500 text-center">
                                {{ empty(trim($name)) ? 'Permission name is required' : 'Invalid guard type selected' }}
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

                    <!-- Impact Warning -->
                    @if ($this->permissionStats['users_affected'] > 0)
                        <div class="bg-white rounded-lg shadow-sm border border-red-200 p-6">
                            <h3 class="text-lg font-semibold text-red-800 mb-4">⚠️ Impact Warning</h3>
                            <div class="text-sm text-red-700 space-y-2">
                                <p>This permission is currently assigned to
                                    <strong>{{ $this->permissionStats['roles_count'] }}
                                        {{ Str::plural('role', $this->permissionStats['roles_count']) }}</strong>.</p>
                                <p>Changes will immediately affect
                                    <strong>{{ $this->permissionStats['users_affected'] }}
                                        {{ Str::plural('user', $this->permissionStats['users_affected']) }}</strong>
                                    across the system.</p>
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
                <p class="text-sm text-slate-600 font-medium">Updating permission and role assignments...</p>
            </div>
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
