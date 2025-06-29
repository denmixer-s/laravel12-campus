<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div
                                class="h-10 w-10 rounded-lg bg-gradient-to-r from-emerald-500 to-teal-600 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-slate-800 font-mono">{{ $permission->name }}</h1>
                                <p class="text-slate-600">Permission Details and Management</p>
                            </div>
                        </div>

                        <!-- Permission Badges -->
                        <div class="flex flex-wrap items-center gap-2 mt-4">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $permission->guard_name === 'web' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                {{ ucfirst($permission->guard_name) }} Guard
                            </span>

                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                {{ $permissionStats['roles_count'] }}
                                {{ Str::plural('Role', $permissionStats['roles_count']) }}
                            </span>

                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                </svg>
                                {{ $permissionStats['unique_users_affected'] }}
                                {{ Str::plural('User', $permissionStats['unique_users_affected']) }}
                            </span>

                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-slate-100 text-slate-800 capitalize">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                {{ $this->permissionCategory }}
                            </span>

                            @if ($securityAnalysis['is_high_risk'])
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                    High Risk
                                </span>
                            @endif

                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Created {{ $permissionStats['created_human'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-3">
                        <button wire:click="goToPermissionsList"
                            class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to List
                        </button>

                        @can('update', $permission)
                            <button wire:click="editPermission"
                                class="inline-flex items-center px-4 py-2 bg-amber-100 hover:bg-amber-200 text-amber-700 font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Permission
                            </button>
                        @endcan

                        <button wire:click="duplicatePermission"
                            class="inline-flex items-center px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            Duplicate
                        </button>

                        <button wire:click="exportPermissionData"
                            class="inline-flex items-center px-4 py-2 bg-green-100 hover:bg-green-200 text-green-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export Data
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Assigned Roles</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $permissionStats['roles_count'] }}</p>
                        <p class="text-xs text-slate-500">{{ $usageStats['assignment_percentage'] }}% of all roles</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Affected Users</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $permissionStats['unique_users_affected'] }}
                        </p>
                        <p class="text-xs text-slate-500">Unique users with this permission</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Risk Level</p>
                        <p
                            class="text-2xl font-bold {{ $securityAnalysis['risk_level'] === 'High' ? 'text-red-600' : ($securityAnalysis['risk_level'] === 'Medium' ? 'text-amber-600' : 'text-green-600') }}">
                            {{ $securityAnalysis['risk_level'] }}
                        </p>
                        <p class="text-xs text-slate-500">Security assessment</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-amber-100 rounded-lg">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Permission Age</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $permissionStats['age_in_days'] }}</p>
                        <p class="text-xs text-slate-500">Days since creation</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 mb-6">
            <div class="border-b border-slate-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button wire:click="setActiveTab('overview')"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'overview' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Overview
                    </button>

                    <button wire:click="setActiveTab('roles')"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'roles' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Roles ({{ $permissionStats['roles_count'] }})
                    </button>

                    <button wire:click="setActiveTab('users')"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'users' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                        Users ({{ $permissionStats['unique_users_affected'] }})
                    </button>

                    <button wire:click="setActiveTab('security')"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'security' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Security Analysis
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                @if ($activeTab === 'overview')
                    <div class="space-y-6">
                        <!-- Permission Information -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="bg-slate-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-800 mb-4">Permission Information</h3>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-slate-600">Permission Name</label>
                                        <p
                                            class="text-slate-900 font-medium font-mono bg-white px-3 py-2 rounded border">
                                            {{ $permission->name }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-slate-600">Guard Name</label>
                                        <p class="text-slate-900">{{ $permission->guard_name }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-slate-600">Category</label>
                                        <p class="text-slate-900 capitalize">{{ $this->permissionCategory }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-slate-600">Action</label>
                                        <p class="text-slate-900 capitalize">{{ $this->permissionAction }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-slate-600">Created At</label>
                                        <p class="text-slate-900">{{ $permissionStats['created_datetime'] }}</p>
                                        <p class="text-xs text-slate-500">{{ $permissionStats['created_human'] }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-slate-600">Updated At</label>
                                        <p class="text-slate-900">{{ $permissionStats['updated_datetime'] }}</p>
                                        <p class="text-xs text-slate-500">{{ $permissionStats['updated_human'] }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-slate-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-slate-800 mb-4">Usage Statistics</h3>
                                <div class="space-y-4">
                                    <div>
                                        <div class="flex justify-between text-sm font-medium text-slate-700 mb-1">
                                            <span>Role Assignment Coverage</span>
                                            <span>{{ $usageStats['assignment_percentage'] }}%</span>
                                        </div>
                                        <div class="w-full bg-slate-200 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 h-2 rounded-full transition-all duration-300"
                                                style="width: {{ $usageStats['assignment_percentage'] }}%"></div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="text-center p-3 bg-white rounded border">
                                            <div class="text-lg font-bold text-emerald-600">
                                                {{ $usageStats['assigned_roles'] }}</div>
                                            <div class="text-xs text-slate-600">Assigned Roles</div>
                                        </div>
                                        <div class="text-center p-3 bg-white rounded border">
                                            <div class="text-lg font-bold text-slate-600">
                                                {{ $usageStats['unassigned_roles'] }}</div>
                                            <div class="text-xs text-slate-600">Available Roles</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($relatedPermissions->count() > 0)
                            <div class="bg-slate-50 rounded-lg p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-slate-800">Related Permissions</h3>
                                    <span class="text-sm text-slate-500">In "{{ $this->permissionCategory }}"
                                        category</span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach ($relatedPermissions as $relatedPermission)
                                        <div
                                            class="flex items-center justify-between p-3 bg-white rounded border hover:border-emerald-300 transition-colors">
                                            <span
                                                class="text-sm font-mono text-slate-800">{{ $relatedPermission->name }}</span>
                                            <span class="text-xs text-slate-500">{{ $relatedPermission->roles_count }}
                                                roles</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @elseif($activeTab === 'roles')
                    <div class="space-y-6">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-slate-800">Assigned Roles</h3>
                                <span class="text-sm text-slate-500">{{ $rolesWithPermission->count() }} roles have
                                    this permission</span>
                            </div>

                            @if ($rolesWithPermission->count() > 0)
                                <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                                    <div class="overflow-x-auto">
                                        <table class="w-full">
                                            <thead class="bg-slate-50 border-b border-slate-200">
                                                <tr>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                                        Role</th>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                                        Users</th>
                                                    <th
                                                        class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">
                                                        Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-200">
                                                @foreach ($rolesWithPermission as $role)
                                                    <tr class="hover:bg-slate-50 transition-colors">
                                                        <td class="px-6 py-4">
                                                            <div class="flex items-center">
                                                                <div
                                                                    class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                                                    <span
                                                                        class="text-white font-medium text-sm">{{ substr($role->name, 0, 2) }}</span>
                                                                </div>
                                                                <div class="ml-3">
                                                                    <p class="text-sm font-medium text-slate-900">
                                                                        {{ $role->name }}</p>
                                                                    @if ($role->name === 'Super Admin')
                                                                        <span
                                                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                                            System Role
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4">
                                                            <span
                                                                class="text-sm font-medium text-slate-900">{{ $role->users_count }}</span>
                                                            <span
                                                                class="text-sm text-slate-500 ml-1">{{ Str::plural('user', $role->users_count) }}</span>
                                                        </td>
                                                        <td class="px-6 py-4 text-center">
                                                            @if ($role->name !== 'Super Admin')
                                                                <button
                                                                    wire:click="removeFromRole({{ $role->id }})"
                                                                    onclick="return confirm('Remove this permission from {{ $role->name }}?')"
                                                                    class="inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-medium rounded-md transition-colors">
                                                                    <svg class="w-4 h-4 mr-1" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M6 18L18 6M6 6l12 12" />
                                                                    </svg>
                                                                    Remove
                                                                </button>
                                                            @else
                                                                <span
                                                                    class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-500 text-sm font-medium rounded-md">
                                                                    Protected
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-12 bg-white rounded-lg border border-slate-200">
                                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 515.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 616 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-slate-900 mb-2">No roles assigned</h3>
                                    <p class="text-slate-500">This permission is not assigned to any roles yet.</p>
                                </div>
                            @endif
                        </div>

                        @if ($unassignedRoles->count() > 0)
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-slate-800">Available Roles</h3>
                                    <span class="text-sm text-slate-500">{{ $unassignedRoles->count() }} roles
                                        available for assignment</span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach ($unassignedRoles as $role)
                                        <div
                                            class="bg-white rounded-lg border border-slate-200 p-4 hover:border-emerald-300 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <div
                                                        class="h-8 w-8 rounded-full bg-gradient-to-r from-slate-400 to-slate-600 flex items-center justify-center">
                                                        <span
                                                            class="text-white font-medium text-sm">{{ substr($role->name, 0, 2) }}</span>
                                                    </div>
                                                    <div class="ml-3">
                                                        <p class="text-sm font-medium text-slate-900">
                                                            {{ $role->name }}</p>
                                                        <p class="text-xs text-slate-500">{{ $role->users_count }}
                                                            {{ Str::plural('user', $role->users_count) }}</p>
                                                    </div>
                                                </div>
                                                <button wire:click="assignToRole({{ $role->id }})"
                                                    class="inline-flex items-center px-2 py-1 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 text-xs font-medium rounded transition-colors">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                    Assign
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @elseif($activeTab === 'users')
                    <div class="space-y-6">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-slate-800">Users with this Permission</h3>
                                <span class="text-sm text-slate-500">{{ $permissionStats['unique_users_affected'] }}
                                    unique users affected</span>
                            </div>

                            @if ($rolesWithPermission->count() > 0)
                                <div class="space-y-6">
                                    @foreach ($rolesWithPermission as $role)
                                        @if ($role->users->count() > 0)
                                            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                                                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                                                    <div class="flex items-center justify-between">
                                                        <h4 class="text-sm font-semibold text-slate-800">Via
                                                            {{ $role->name }} Role</h4>
                                                        <span
                                                            class="text-xs text-slate-500">{{ $role->users->count() }}
                                                            users</span>
                                                    </div>
                                                </div>
                                                <div class="p-6">
                                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                                        @foreach ($role->users as $user)
                                                            <div class="flex items-center p-3 bg-slate-50 rounded-lg">
                                                                <div
                                                                    class="h-8 w-8 rounded-full bg-gradient-to-r from-emerald-500 to-teal-600 flex items-center justify-center">
                                                                    <span
                                                                        class="text-white font-medium text-sm">{{ substr($user->name, 0, 2) }}</span>
                                                                </div>
                                                                <div class="ml-3 flex-1 min-w-0">
                                                                    <p
                                                                        class="text-sm font-medium text-slate-900 truncate">
                                                                        {{ $user->name }}</p>
                                                                    <p class="text-xs text-slate-500 truncate">
                                                                        {{ $user->email }}</p>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12 bg-white rounded-lg border border-slate-200">
                                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-slate-900 mb-2">No users found</h3>
                                    <p class="text-slate-500">No users have this permission yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @elseif($activeTab === 'security')
                    <div class="space-y-6">
                        <div class="bg-white rounded-lg border border-slate-200 p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-slate-800">Risk Assessment</h3>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $securityAnalysis['risk_level'] === 'High' ? 'bg-red-100 text-red-800' : ($securityAnalysis['risk_level'] === 'Medium' ? 'bg-amber-100 text-amber-800' : 'bg-green-100 text-green-800') }}">
                                    {{ $securityAnalysis['risk_level'] }} Risk
                                </span>
                            </div>

                            @if (count($securityAnalysis['risk_factors']) > 0)
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-slate-800 mb-2">Risk Factors:</h4>
                                    <ul class="space-y-2">
                                        @foreach ($securityAnalysis['risk_factors'] as $factor)
                                            <li class="flex items-center text-sm text-slate-700">
                                                <svg class="w-4 h-4 text-amber-500 mr-2" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                                </svg>
                                                {{ $factor }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div>
                                <h4 class="text-sm font-medium text-slate-800 mb-2">Security Recommendations:</h4>
                                <ul class="space-y-2">
                                    @foreach ($securityAnalysis['recommendations'] as $recommendation)
                                        <li class="flex items-start text-sm text-slate-700">
                                            <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5 flex-shrink-0"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $recommendation }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg border border-slate-200 p-6">
                            <h3 class="text-lg font-semibold text-slate-800 mb-4">Usage Analysis</h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div class="text-center p-4 bg-slate-50 rounded-lg">
                                    <div class="text-2xl font-bold text-slate-800">
                                        {{ $usageStats['assignment_percentage'] }}%</div>
                                    <div class="text-sm text-slate-600">Role Coverage</div>
                                </div>
                                <div class="text-center p-4 bg-slate-50 rounded-lg">
                                    <div class="text-2xl font-bold text-slate-800">
                                        {{ $permissionStats['unique_users_affected'] }}</div>
                                    <div class="text-sm text-slate-600">Affected Users</div>
                                </div>
                                <div class="text-center p-4 bg-slate-50 rounded-lg">
                                    <div class="text-2xl font-bold text-slate-800">
                                        {{ $permissionStats['age_in_days'] }}</div>
                                    <div class="text-sm text-slate-600">Days Old</div>
                                </div>
                            </div>

                            @if ($usageStats['is_widely_used'])
                                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <h4 class="text-sm font-medium text-blue-800 mb-1">Widely Used Permission</h4>
                                    <p class="text-sm text-blue-700">This permission is assigned to over 50% of roles.
                                        Consider if this level of access is appropriate for all these roles.</p>
                                </div>
                            @elseif($usageStats['is_rarely_used'])
                                <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
                                    <h4 class="text-sm font-medium text-amber-800 mb-1">Rarely Used Permission</h4>
                                    <p class="text-sm text-amber-700">This permission is assigned to less than 20% of
                                        roles. Consider if this permission is still needed or if it should be
                                        consolidated.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Loading States -->
    <div wire:loading.flex wire:target="assignToRole,removeFromRole"
        class="fixed inset-0 z-50 bg-white bg-opacity-75 flex items-center justify-center">
        <div class="flex flex-col items-center space-y-4">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="text-sm text-slate-600 font-medium">
                <span wire:loading wire:target="assignToRole">Assigning permission to role...</span>
                <span wire:loading wire:target="removeFromRole">Removing permission from role...</span>
            </p>
        </div>
    </div>
</div>
