<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="h-10 w-10 rounded-lg bg-gradient-to-r from-purple-500 to-indigo-600 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-slate-800">{{ $role->name }}</h1>
                                <p class="text-slate-600">Role Details and Management</p>
                            </div>
                        </div>

                        <!-- Role Badges -->
                        <div class="flex flex-wrap items-center gap-2 mt-4">
                            @if($role->name === 'Super Admin')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    System Role
                                </span>
                            @endif

                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                                </svg>
                                {{ $roleStats['users_count'] }} {{ Str::plural('User', $roleStats['users_count']) }}
                            </span>

                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                {{ $roleStats['permissions_count'] }} {{ Str::plural('Permission', $roleStats['permissions_count']) }}
                            </span>

                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-slate-100 text-slate-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-8 0h16a2 2 0 002-2V9a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Created {{ $roleStats['created_human'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-3">
                        <button wire:click="goToRolesList"
                                class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to List
                        </button>

                        @if($role->name !== 'Super Admin')
                        <button wire:click="editRole"
                                class="inline-flex items-center px-4 py-2 bg-amber-100 hover:bg-amber-200 text-amber-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Role
                        </button>
                        @endif

                        <button wire:click="duplicateRole"
                                class="inline-flex items-center px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Duplicate
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Total Users</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $roleStats['users_count'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Permissions</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $roleStats['permissions_count'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-8 0h16a2 2 0 002-2V9a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Created</p>
                        <p class="text-lg font-bold text-slate-900">{{ $roleStats['created_date'] }}</p>
                        <p class="text-xs text-slate-500">{{ $roleStats['created_human'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-amber-100 rounded-lg">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Last Updated</p>
                        <p class="text-lg font-bold text-slate-900">{{ $roleStats['updated_date'] }}</p>
                        <p class="text-xs text-slate-500">{{ $roleStats['updated_human'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 mb-6">
            <div class="border-b border-slate-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button wire:click="setActiveTab('overview')"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'overview' ? 'border-blue-500 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Overview
                    </button>

                    <button wire:click="setActiveTab('users')"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'users' ? 'border-blue-500 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                        </svg>
                        Users ({{ $roleStats['users_count'] }})
                    </button>

                    <button wire:click="setActiveTab('permissions')"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'permissions' ? 'border-blue-500 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Permissions ({{ $roleStats['permissions_count'] }})
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Overview Tab -->
                @if($activeTab === 'overview')
                <div class="space-y-6">
                    <!-- Role Information -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-slate-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-800 mb-4">Role Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Role Name</label>
                                    <p class="text-slate-900 font-medium">{{ $role->name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Guard Name</label>
                                    <p class="text-slate-900">{{ $role->guard_name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Created At</label>
                                    <p class="text-slate-900">{{ $roleStats['created_datetime'] }}</p>
                                    <p class="text-xs text-slate-500">{{ $roleStats['created_human'] }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Updated At</label>
                                    <p class="text-slate-900">{{ $roleStats['updated_datetime'] }}</p>
                                    <p class="text-xs text-slate-500">{{ $roleStats['updated_human'] }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Permission Groups Overview -->
                        <div class="bg-slate-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-800 mb-4">Permission Groups</h3>
                            <div class="space-y-3">
                                @forelse($permissionGroups as $groupName => $permissions)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-700 capitalize">{{ str_replace('_', ' ', $groupName) }}</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $permissions->count() }} permissions
                                    </span>
                                </div>
                                @empty
                                <p class="text-slate-500 text-sm">No permissions assigned</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Recent Users -->
                    <div class="bg-slate-50 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-slate-800">Recent Users</h3>
                            <button wire:click="setActiveTab('users')" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View All →
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @forelse($role->users->take(6) as $user)
                            <div class="flex items-center p-3 bg-white rounded-lg border border-slate-200">
                                <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                    <span class="text-white font-medium text-sm">{{ substr($user->name, 0, 2) }}</span>
                                </div>
                                <div class="ml-3 flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-900 truncate">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ $user->email }}</p>
                                </div>
                            </div>
                            @empty
                            <p class="text-slate-500 text-sm col-span-full">No users assigned to this role</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                @endif

                <!-- Users Tab -->
                @if($activeTab === 'users')
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800">Users with this Role</h3>
                        <p class="text-sm text-slate-600">Users assigned to {{ $role->name }}</p>
                    </div>

                    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-slate-50 border-b border-slate-200">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Joined</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    @forelse($role->users as $user)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                                    <span class="text-white font-medium text-sm">{{ substr($user->name, 0, 2) }}</span>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-slate-900">{{ $user->name }}</p>
                                                    @if($user->hasRole('Super Admin'))
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                        Super Admin
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-900">{{ $user->email }}</td>
                                        <td class="px-6 py-4 text-sm text-slate-500">{{ $user->created_at->format('M d, Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                                                </svg>
                                                <h3 class="text-lg font-medium text-slate-900 mb-2">No users found</h3>
                                                <p class="text-slate-500">No users are assigned to this role yet.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Permissions Tab -->
                @if($activeTab === 'permissions')
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800">Role Permissions</h3>
                        <p class="text-sm text-slate-600">Permissions granted to {{ $role->name }}</p>
                    </div>

                    <div class="space-y-4">
                        @forelse($permissionGroups as $groupName => $permissions)
                        <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-semibold text-slate-800 capitalize">{{ str_replace('_', ' ', $groupName) }}</h4>
                                    <span class="text-xs text-slate-500">{{ $permissions->count() }} permissions</span>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($permissions as $permission)
                                    <div class="flex items-center p-3 bg-green-50 rounded-lg border border-green-200">
                                        <svg class="w-4 h-4 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="text-sm font-medium text-green-800">{{ $permission->name }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <h3 class="text-lg font-medium text-slate-900 mb-2">No permissions found</h3>
                            <p class="text-slate-500">This role has no permissions assigned.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
