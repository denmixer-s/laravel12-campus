<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="h-16 w-16 rounded-full bg-gradient-to-r from-emerald-500 to-teal-600 flex items-center justify-center">
                                <span class="text-white font-bold text-xl">{{ substr($user->name, 0, 2) }}</span>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-slate-800">{{ $user->name }}</h1>
                                <p class="text-slate-600">{{ $user->email }}</p>
                                @if($user->phone)
                                    <p class="text-slate-500 text-sm">{{ $user->phone }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- User Badges -->
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $userStats['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($userStats['status'] === 'active')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    @endif
                                </svg>
                                {{ ucfirst($userStats['status']) }}
                            </span>

                            @if($userStats['email_verified'])
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Email Verified
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Email Pending
                                </span>
                            @endif

                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                {{ $userStats['roles_count'] }} {{ Str::plural('Role', $userStats['roles_count']) }}
                            </span>

                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-slate-100 text-slate-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-8 0h16a2 2 0 002-2V9a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Joined {{ $userStats['created_human'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-3">
                        <button wire:click="goToUsersList"
                                class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to List
                        </button>

                        @if(!($user->hasRole('Super Admin') && !auth()->user()->hasRole('Super Admin')))
                            @can('update', $user)
                            <button wire:click="editUser"
                                    class="inline-flex items-center px-4 py-2 bg-amber-100 hover:bg-amber-200 text-amber-700 font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit User
                            </button>
                            @endcan
                        @endif

                        <button wire:click="duplicateUser"
                                class="inline-flex items-center px-4 py-2 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 font-medium rounded-lg transition-colors duration-200">
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
                    <div class="p-2 bg-emerald-100 rounded-lg">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Total Roles</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $userStats['roles_count'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Permissions</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $userStats['permissions_count'] }}</p>
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
                        <p class="text-sm font-medium text-slate-600">Member Since</p>
                        <p class="text-lg font-bold text-slate-900">{{ $userStats['created_date'] }}</p>
                        <p class="text-xs text-slate-500">{{ $userStats['created_human'] }}</p>
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
                        <p class="text-sm font-medium text-slate-600">Last Login</p>
                        <p class="text-lg font-bold text-slate-900">{{ $userStats['last_login'] === 'Never' ? 'Never' : $userStats['last_login'] }}</p>
                        <p class="text-xs text-slate-500">{{ $userStats['last_login_human'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation and Content -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 mb-6">
            <div class="border-b border-slate-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button wire:click="setActiveTab('overview')"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'overview' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Overview
                    </button>

                    <button wire:click="setActiveTab('roles')"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'roles' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Roles ({{ $userStats['roles_count'] }})
                    </button>

                    <button wire:click="setActiveTab('permissions')"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'permissions' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Permissions ({{ $userStats['permissions_count'] }})
                    </button>

                    <button wire:click="setActiveTab('activity')"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors {{ $activeTab === 'activity' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Activity
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Overview Tab -->
                @if($activeTab === 'overview')
                <div class="space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- User Information -->
                        <div class="bg-slate-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-800 mb-4">User Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Full Name</label>
                                    <p class="text-slate-900 font-medium">{{ $user->name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Email Address</label>
                                    <p class="text-slate-900">{{ $user->email }}</p>
                                </div>
                                @if($user->phone)
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Phone Number</label>
                                    <p class="text-slate-900">{{ $user->phone }}</p>
                                </div>
                                @endif
                                @if($user->address)
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Address</label>
                                    <p class="text-slate-900">{{ $user->address }}</p>
                                </div>
                                @endif
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Status</label>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium {{ $userStats['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($userStats['status']) }}
                                        </span>
                                        @if($user->id !== auth()->id() && !($user->hasRole('Super Admin') && !auth()->user()->hasRole('Super Admin')))
                                            @can('update', $user)
                                            <button wire:click="toggleUserStatus"
                                                    class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                                {{ $userStats['status'] === 'active' ? 'Deactivate' : 'Activate' }}
                                            </button>
                                            @endcan
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-600">Email Verification</label>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium {{ $userStats['email_verified'] ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $userStats['email_verified'] ? 'Verified' : 'Pending' }}
                                        </span>
                                        @can('update', $user)
                                        <button wire:click="toggleEmailVerification"
                                                class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                            {{ $userStats['email_verified'] ? 'Remove Verification' : 'Mark as Verified' }}
                                        </button>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Completeness -->
                        <div class="bg-slate-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-slate-800 mb-4">Profile Completeness</h3>
                            <div class="mb-4">
                                <div class="flex justify-between text-sm text-slate-600 mb-2">
                                    <span>Profile Complete</span>
                                    <span>{{ $profileCompleteness['percentage'] }}%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 h-3 rounded-full transition-all duration-300" 
                                         style="width: {{ $profileCompleteness['percentage'] }}%"></div>
                                </div>
                            </div>
                            <div class="space-y-2">
                                @foreach($profileCompleteness['fields'] as $field => $completed)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-slate-700 capitalize">{{ str_replace('_', ' ', $field) }}</span>
                                    @if($completed)
                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-slate-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Quick Actions</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            @if(!$userStats['email_verified'])
                            <button wire:click="resendVerificationEmail"
                                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Resend Verification
                            </button>
                            @endif

                            <button wire:click="sendPasswordReset"
                                    class="inline-flex items-center justify-center px-4 py-2 bg-amber-100 hover:bg-amber-200 text-amber-700 font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 0 1 2 2m4 0a6 6 0 0 1-7.743 5.743L11 17H9v2H7v2H4a1 1 0 0 1-1-1v-2.586a1 1 0 0 1 .293-.707l5.964-5.964A6 6 0 1 1 21 9z"/>
                                </svg>
                                Send Password Reset
                            </button>

                            @if($user->id !== auth()->id() && !($user->hasRole('Super Admin') && !auth()->user()->hasRole('Super Admin')))
                                @can('update', $user)
                                <button wire:click="toggleUserStatus"
                                        class="inline-flex items-center justify-center px-4 py-2 {{ $userStats['status'] === 'active' ? 'bg-red-100 hover:bg-red-200 text-red-700' : 'bg-green-100 hover:bg-green-200 text-green-700' }} font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($userStats['status'] === 'active')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18 21l-2.5-2.5m-10.5 0L3 21l2.5-2.5"/>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        @endif
                                    </svg>
                                    {{ $userStats['status'] === 'active' ? 'Deactivate User' : 'Activate User' }}
                                </button>
                                @endcan
                            @endif

                            @can('update', $user)
                            <button wire:click="toggleEmailVerification"
                                    class="inline-flex items-center justify-center px-4 py-2 bg-purple-100 hover:bg-purple-200 text-purple-700 font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $userStats['email_verified'] ? 'Remove Verification' : 'Verify Email' }}
                            </button>
                            @endcan
                        </div>
                    </div>
                </div>
                @endif

                <!-- Roles Tab -->
                @if($activeTab === 'roles')
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800">User Roles</h3>
                        <p class="text-sm text-slate-600">Roles assigned to {{ $user->name }}</p>
                    </div>

                    <div class="space-y-4">
                        @forelse($userRolesGrouped as $groupName => $roles)
                        <div class="bg-slate-50 rounded-lg p-6">
                            <h4 class="text-sm font-semibold text-slate-800 capitalize mb-4">{{ str_replace('_', ' ', $groupName) }} Roles</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($roles as $role)
                                <div class="flex items-center p-3 bg-white rounded-lg border border-slate-200">
                                    <div class="p-2 {{ $role->name === 'Super Admin' ? 'bg-red-100' : 'bg-emerald-100' }} rounded-lg">
                                        <svg class="w-4 h-4 {{ $role->name === 'Super Admin' ? 'text-red-600' : 'text-emerald-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-slate-900">{{ $role->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $role->permissions->count() }} permissions</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <h3 class="text-lg font-medium text-slate-900 mb-2">No roles assigned</h3>
                            <p class="text-slate-500">This user has no roles assigned.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                @endif

                <!-- Permissions Tab -->
                @if($activeTab === 'permissions')
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800">User Permissions</h3>
                        <p class="text-sm text-slate-600">All permissions granted to {{ $user->name }} through assigned roles</p>
                    </div>

                    <div class="space-y-4">
                        @forelse($userPermissionsGrouped as $groupName => $permissions)
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <h3 class="text-lg font-medium text-slate-900 mb-2">No permissions found</h3>
                            <p class="text-slate-500">This user has no permissions through their assigned roles.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                @endif

                <!-- Activity Tab -->
                @if($activeTab === 'activity')
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800">Account Activity</h3>
                        <p class="text-sm text-slate-600">Recent activity and security information for {{ $user->name }}</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Security Information -->
                        <div class="bg-slate-50 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-slate-800 mb-4">Security Information</h4>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-slate-200">
                                    <div class="flex items-center">
                                        <div class="p-2 bg-blue-100 rounded-lg">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-slate-900">Password Security</p>
                                            <p class="text-xs text-slate-500">Last updated {{ $accountSecurity['password_updated']->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800">
                                        Secure
                                    </span>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-slate-200">
                                    <div class="flex items-center">
                                        <div class="p-2 bg-purple-100 rounded-lg">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-slate-900">Two-Factor Authentication</p>
                                            <p class="text-xs text-slate-500">Additional security layer</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium {{ $accountSecurity['two_factor_enabled'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $accountSecurity['two_factor_enabled'] ? 'Enabled' : 'Disabled' }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-slate-200">
                                    <div class="flex items-center">
                                        <div class="p-2 bg-amber-100 rounded-lg">
                                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-slate-900">Account Lock Status</p>
                                            <p class="text-xs text-slate-500">Failed login attempts: {{ $accountSecurity['failed_login_attempts'] }}</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium {{ $accountSecurity['account_locked'] ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $accountSecurity['account_locked'] ? 'Locked' : 'Unlocked' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Activity Summary -->
                        <div class="bg-slate-50 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-slate-800 mb-4">Activity Summary</h4>
                            <div class="space-y-4">
                                <div class="p-3 bg-white rounded-lg border border-slate-200">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-slate-900">Total Logins</span>
                                        <span class="text-lg font-bold text-slate-900">{{ $activitySummary['total_logins'] }}</span>
                                    </div>
                                </div>

                                <div class="p-3 bg-white rounded-lg border border-slate-200">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-slate-900">Last IP Address</span>
                                        <span class="text-sm text-slate-600 font-mono">{{ $activitySummary['last_ip_address'] }}</span>
                                    </div>
                                </div>

                                <div class="p-3 bg-white rounded-lg border border-slate-200">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-slate-900">Active Sessions</span>
                                        <span class="text-lg font-bold text-slate-900">{{ $activitySummary['browser_sessions'] }}</span>
                                    </div>
                                </div>

                                <div class="p-3 bg-white rounded-lg border border-slate-200">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-slate-900">Last Login</span>
                                        <span class="text-sm text-slate-600">{{ $userStats['last_login_human'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity Log -->
                    <div class="bg-white rounded-lg border border-slate-200 p-6">
                        <h4 class="text-lg font-semibold text-slate-800 mb-4">Recent Activity</h4>
                        @if(isset($activitySummary['recent_activities']) && count($activitySummary['recent_activities']) > 0)
                            <div class="space-y-3">
                                @foreach($activitySummary['recent_activities'] as $activity)
                                <div class="flex items-start p-3 bg-slate-50 rounded-lg">
                                    <div class="p-2 bg-blue-100 rounded-lg">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-slate-900">{{ $activity['description'] }}</p>
                                        <p class="text-xs text-slate-500">{{ $activity['created_at'] }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h3 class="text-lg font-medium text-slate-900 mb-2">No recent activity</h3>
                                <p class="text-slate-500">No activity logs are available for this user.</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>