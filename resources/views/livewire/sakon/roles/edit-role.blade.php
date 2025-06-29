<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="h-8 w-8 rounded-lg bg-gradient-to-r from-amber-500 to-orange-600 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <h1 class="text-3xl font-bold text-slate-800">Edit Role</h1>
                        </div>
                        <p class="text-slate-600">Modify role permissions and settings for <span class="font-medium text-slate-800">"{{ $role->name }}"</span></p>
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
                        
                        <button wire:click="duplicateRole" 
                                type="button"
                                class="inline-flex items-center px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Duplicate
                        </button>
                    </div>
                </div>
                
                <!-- Role Info Bar -->
                <div class="mt-4 pt-4 border-t border-slate-200">
                    <div class="flex flex-wrap items-center gap-4 text-sm">
                        <div class="flex items-center text-slate-600">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                            </svg>
                            <span class="font-medium">{{ $this->usersWithRoleCount }}</span> users assigned
                        </div>
                        <div class="flex items-center text-slate-600">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span class="font-medium">{{ count($selectedPermissions) }}</span> permissions
                        </div>
                        <div class="flex items-center text-slate-600">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-8 0h16a2 2 0 002-2V9a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Created: {{ $role->created_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Changes Alert -->
        @if($hasChanges)
        <div class="mb-6">
            <div class="bg-amber-50 rounded-lg border border-amber-200 p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-amber-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <div class="flex-1">
                        <p class="text-amber-800 font-medium">You have unsaved changes</p>
                        <p class="text-amber-700 text-sm mt-1">Don't forget to save your changes before leaving this page.</p>
                    </div>
                    <button wire:click="resetForm" 
                            type="button"
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
                    <!-- Role Details Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-800 mb-4">Role Details</h2>
                        
                        <!-- Role Name Input -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                                Role Name <span class="text-red-500">*</span>
                            </label>
                            <input wire:model.live.debounce.300ms="name" 
                                   type="text" 
                                   id="name"
                                   placeholder="Enter role name"
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('name') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Role Statistics -->
                        <div class="grid grid-cols-2 gap-4 p-4 bg-slate-50 rounded-lg">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-slate-800">{{ $this->usersWithRoleCount }}</div>
                                <div class="text-sm text-slate-600">Users Assigned</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-slate-800">{{ $this->selectedPermissionsCount }}</div>
                                <div class="text-sm text-slate-600">Permissions</div>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-semibold text-slate-800">Permissions</h2>
                            <span class="text-sm text-slate-500">
                                {{ $this->selectedPermissionsCount }} of {{ $this->totalPermissionsCount }} selected
                            </span>
                        </div>

                        <!-- Permission Search -->
                        <div class="mb-4">
                            <div class="relative">
                                <input wire:model.live.debounce.300ms="permissionSearch" 
                                       type="text" 
                                       placeholder="Search permissions..." 
                                       class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Select All Toggle -->
                        <div class="mb-6 p-3 bg-slate-50 rounded-lg border border-slate-200">
                            <label class="flex items-center cursor-pointer">
                                <input wire:model.live="selectAll" 
                                       type="checkbox" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                                <span class="ml-3 text-sm font-medium text-slate-700">Select All Permissions</span>
                            </label>
                        </div>

                        <!-- Permission Groups -->
                        @error('selectedPermissions')
                            <div class="mb-4 p-3 bg-red-50 rounded-lg border border-red-200">
                                <p class="text-red-600 text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            </div>
                        @enderror

                        <div class="space-y-4 max-h-96 overflow-y-auto">
                            @foreach($permissionGroups as $groupName => $permissions)
                            <div class="border border-slate-200 rounded-lg">
                                <!-- Group Header -->
                                <div class="p-3 bg-slate-50 border-b border-slate-200">
                                    <div class="flex items-center justify-between">
                                        <label class="flex items-center cursor-pointer">
                                            <input wire:click="togglePermissionGroup('{{ $groupName }}')" 
                                                   type="checkbox" 
                                                   {{ $this->isGroupSelected($groupName) ? 'checked' : '' }}
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded {{ $this->isGroupPartiallySelected($groupName) ? 'indeterminate' : '' }}">
                                            <span class="ml-3 text-sm font-semibold text-slate-800 capitalize">
                                                {{ str_replace('_', ' ', $groupName) }}
                                            </span>
                                        </label>
                                        <span class="text-xs text-slate-500">
                                            {{ $permissions->count() }} permissions
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Group Permissions -->
                                <div class="p-3">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        @foreach($permissions as $permission)
                                        <label class="flex items-center cursor-pointer p-2 hover:bg-slate-50 rounded">
                                            <input wire:model.live="selectedPermissions" 
                                                   type="checkbox" 
                                                   value="{{ $permission->id }}"
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                                            <span class="ml-3 text-sm text-slate-700">{{ $permission->name }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
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
                                @if($name !== $originalData['name'])
                                    <svg class="w-5 h-5 text-amber-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @endif
                                <span class="text-sm {{ $name !== $originalData['name'] ? 'text-amber-700' : 'text-green-700' }}">
                                    Role name {{ $name !== $originalData['name'] ? 'modified' : 'unchanged' }}
                                </span>
                            </div>
                            
                            <!-- Permissions Change Status -->
                            <div class="flex items-center">
                                @if(count($this->addedPermissions) > 0 || count($this->removedPermissions) > 0)
                                    <svg class="w-5 h-5 text-amber-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @endif
                                <span class="text-sm {{ count($this->addedPermissions) > 0 || count($this->removedPermissions) > 0 ? 'text-amber-700' : 'text-green-700' }}">
                                    Permissions {{ count($this->addedPermissions) > 0 || count($this->removedPermissions) > 0 ? 'modified' : 'unchanged' }}
                                </span>
                            </div>
                        </div>

                        <!-- Changes Summary -->
                        @if($hasChanges)
                        <div class="mt-4 pt-4 border-t border-slate-200">
                            <h4 class="text-sm font-medium text-slate-800 mb-2">Changes Summary:</h4>
                            <div class="space-y-2 text-sm">
                                @if($name !== $originalData['name'])
                                <div class="flex items-center text-amber-700">
                                    <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Name changed
                                </div>
                                @endif
                                
                                @if(count($this->addedPermissions) > 0)
                                <div class="flex items-center text-green-700">
                                    <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                    </svg>
                                    +{{ count($this->addedPermissions) }} permissions added
                                </div>
                                @endif
                                
                                @if(count($this->removedPermissions) > 0)
                                <div class="flex items-center text-red-700">
                                    <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    -{{ count($this->removedPermissions) }} permissions removed
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Current Role Summary -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Role Summary</h3>
                        
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-slate-600">Current Name:</span>
                                <p class="font-medium text-slate-800">{{ $name ?: 'Not specified' }}</p>
                            </div>
                            
                            <div>
                                <span class="text-sm text-slate-600">Permissions:</span>
                                <p class="font-medium text-slate-800">{{ count($selectedPermissions) }} selected</p>
                            </div>
                            
                            <div>
                                <span class="text-sm text-slate-600">Users Assigned:</span>
                                <p class="font-medium text-slate-800">{{ $this->usersWithRoleCount }} users</p>
                            </div>
                            
                            @if(count($selectedPermissions) > 0)
                            <div>
                                <span class="text-sm text-slate-600">Permission Groups:</span>
                                <div class="mt-1 flex flex-wrap gap-1">
                                    @foreach($permissionGroups as $groupName => $permissions)
                                        @php
                                            $groupPermissionIds = $permissions->pluck('id')->toArray();
                                            $selectedInGroup = array_intersect($groupPermissionIds, $selectedPermissions);
                                        @endphp
                                        @if(count($selectedInGroup) > 0)
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst($groupName) }} ({{ count($selectedInGroup) }})
                                        </span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <button type="submit" 
                                wire:loading.attr="disabled"
                                {{ !$this->canUpdate ? 'disabled' : '' }}
                                class="w-full inline-flex justify-center items-center px-4 py-3 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 disabled:from-slate-300 disabled:to-slate-400 text-white font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="update">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Update Role
                            </span>
                            <span wire:loading wire:target="update" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Updating Role...
                            </span>
                        </button>
                        
                        @if(!$this->canUpdate && $hasChanges)
                        <p class="mt-2 text-xs text-slate-500 text-center">
                            {{ empty(trim($name)) ? 'Role name is required' : 'At least one permission must be selected' }}
                        </p>
                        @elseif(!$hasChanges)
                        <p class="mt-2 text-xs text-slate-500 text-center">
                            No changes to save
                        </p>
                        @endif
                        
                        <button wire:click="resetForm" 
                                type="button"
                                {{ !$hasChanges ? 'disabled' : '' }}
                                class="w-full mt-3 inline-flex justify-center items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 disabled:bg-slate-50 text-slate-700 disabled:text-slate-400 font-medium rounded-lg transition-colors duration-200 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset Changes
                        </button>
                    </div>

                    <!-- Danger Zone -->
                    @if($this->usersWithRoleCount > 0)
                    <div class="bg-white rounded-lg shadow-sm border border-red-200 p-6">
                        <h3 class="text-lg font-semibold text-red-800 mb-4">⚠️ Warning</h3>
                        <div class="text-sm text-red-700 space-y-2">
                            <p>This role is currently assigned to <strong>{{ $this->usersWithRoleCount }} {{ Str::plural('user', $this->usersWithRoleCount) }}</strong>.</p>
                            <p>Changes to permissions will immediately affect all users with this role.</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Loading Overlay -->
    <div wire:loading.flex wire:target="update" class="fixed inset-0 z-50 bg-white bg-opacity-75 flex items-center justify-center">
        <div class="flex flex-col items-center space-y-4">
            <svg class="animate-spin h-8 w-8 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-sm text-slate-600 font-medium">Updating role and permissions...</p>
        </div>
    </div>

    <!-- Unsaved Changes Warning -->
    @if($hasChanges)
    <script>
        window.addEventListener('beforeunload', function (e) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
        });
    </script>
    @endif
</div>