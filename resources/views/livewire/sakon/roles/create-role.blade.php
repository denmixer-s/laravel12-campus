<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="h-8 w-8 rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                            <h1 class="text-3xl font-bold text-slate-800">Create New Role</h1>
                        </div>
                        <p class="text-slate-600">Define a new role and assign permissions to control user access</p>
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
                                   placeholder="Enter role name (e.g., Content Manager, Sales Admin)"
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

                        <!-- Quick Presets -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-slate-700 mb-3">Quick Presets</label>
                            <div class="flex flex-wrap gap-2">
                                <button wire:click="applyPreset('admin')" 
                                        type="button"
                                        class="px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-medium rounded-md transition-colors">
                                    Administrator
                                </button>
                                <button wire:click="applyPreset('editor')" 
                                        type="button"
                                        class="px-3 py-1.5 bg-green-100 hover:bg-green-200 text-green-700 text-sm font-medium rounded-md transition-colors">
                                    Content Editor
                                </button>
                                <button wire:click="applyPreset('viewer')" 
                                        type="button"
                                        class="px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-700 text-sm font-medium rounded-md transition-colors">
                                    Viewer Only
                                </button>
                                <button wire:click="applyPreset('reset')" 
                                        type="button"
                                        class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-md transition-colors">
                                    Clear All
                                </button>
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
                    <!-- Progress Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Creation Progress</h3>
                        
                        <div class="space-y-4">
                            <!-- Role Name Status -->
                            <div class="flex items-center">
                                @if(trim($name))
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    <div class="w-5 h-5 bg-slate-300 rounded-full mr-3"></div>
                                @endif
                                <span class="text-sm {{ trim($name) ? 'text-green-700' : 'text-slate-600' }}">
                                    Role name provided
                                </span>
                            </div>
                            
                            <!-- Permissions Status -->
                            <div class="flex items-center">
                                @if(count($selectedPermissions) > 0)
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    <div class="w-5 h-5 bg-slate-300 rounded-full mr-3"></div>
                                @endif
                                <span class="text-sm {{ count($selectedPermissions) > 0 ? 'text-green-700' : 'text-slate-600' }}">
                                    Permissions selected ({{ count($selectedPermissions) }})
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

                    <!-- Summary Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Role Summary</h3>
                        
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-slate-600">Name:</span>
                                <p class="font-medium text-slate-800">{{ $name ?: 'Not specified' }}</p>
                            </div>
                            
                            <div>
                                <span class="text-sm text-slate-600">Permissions:</span>
                                <p class="font-medium text-slate-800">{{ count($selectedPermissions) }} selected</p>
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
                                {{ !$this->canCreate ? 'disabled' : '' }}
                                class="w-full inline-flex justify-center items-center px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 disabled:from-slate-300 disabled:to-slate-400 text-white font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="create">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Create Role
                            </span>
                            <span wire:loading wire:target="create" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Creating Role...
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
                </div>
            </div>
        </form>
    </div>

    <!-- Loading Overlay -->
    <div wire:loading.flex wire:target="create" class="fixed inset-0 z-50 bg-white bg-opacity-75 flex items-center justify-center">
        <div class="flex flex-col items-center space-y-4">
            <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-sm text-slate-600 font-medium">Creating role and assigning permissions...</p>
        </div>
    </div>
</div>