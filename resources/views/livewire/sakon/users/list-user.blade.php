<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-800 mb-2">User Management</h1>
                        <p class="text-slate-600">Manage system users, roles, and permissions</p>
                    </div>

                    @can('create', App\Models\User::class)
                    <button wire:click="createUser"
                            class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Create New User
                    </button>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <!-- Search Input -->
                    <div class="lg:col-span-2">
                        <div class="relative">
                            <input wire:model.live.debounce.300ms="search"
                                   type="text"
                                   placeholder="Search users by name, email, or phone..."
                                   class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                            <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Role Filter -->
                    <div>
                        <select wire:model.live="roleFilter"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                            <option value="">All Roles</option>
                            @if($this->availableRoles && $this->availableRoles->count() > 0)
                                @foreach($this->availableRoles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <select wire:model.live="statusFilter"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <!-- Verification Filter -->
                    <div>
                        <select wire:model.live="verificationFilter"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                            <option value="">All Verification</option>
                            <option value="verified">Verified</option>
                            <option value="unverified">Unverified</option>
                        </select>
                    </div>
                </div>

                <!-- Active Filters & Clear -->
                @if($search || $roleFilter || $statusFilter || $verificationFilter)
                <div class="mt-4 pt-4 border-t border-slate-200">
                    <div class="flex items-center justify-between">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm text-slate-600">Active filters:</span>
                            @if($this->filterSummary && count($this->filterSummary) > 0)
                                @foreach($this->filterSummary as $filter)
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-emerald-100 text-emerald-800">
                                        {{ $filter }}
                                    </span>
                                @endforeach
                            @endif
                        </div>
                        <button wire:click="clearFilters"
                                class="text-sm text-slate-600 hover:text-slate-800 font-medium">
                            Clear all filters
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Bulk Actions -->
        @if(count($selectedUsers) > 0)
        <div class="mb-6">
            <div class="bg-blue-50 rounded-lg border border-blue-200 p-4">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center">
                        <span class="text-sm font-medium text-blue-800">
                            {{ count($selectedUsers) }} user(s) selected
                        </span>
                    </div>
                    <div class="flex items-center gap-3">
                        <select wire:model="bulkAction"
                                class="px-3 py-2 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                            <option value="">Choose action...</option>
                            <option value="activate">Activate Users</option>
                            <option value="deactivate">Deactivate Users</option>
                            <option value="verify_email">Verify Emails</option>
                            <option value="delete">Delete Users</option>
                        </select>
                        <button wire:click="executeBulkAction"
                                {{ !$bulkAction ? 'disabled' : '' }}
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-300 text-white font-medium rounded-lg transition-colors disabled:cursor-not-allowed text-sm">
                            Execute
                        </button>
                        <button wire:click="$set('selectedUsers', [])"
                                class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors text-sm">
                            Clear Selection
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            <!-- Table Controls -->
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <label class="flex items-center cursor-pointer">
                            <input wire:model.live="selectAll" 
                                   type="checkbox" 
                                   class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300 rounded">
                            <span class="ml-3 text-sm font-medium text-slate-700">Select All</span>
                        </label>
                        <span class="text-sm text-slate-500">
                            {{ $users->total() }} total users
                        </span>
                    </div>
                    <div class="flex items-center gap-4">
                        <select wire:model.live="perPage"
                                class="px-3 py-1 border border-slate-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors text-sm">
                            <option value="5">5 per page</option>
                            <option value="10">10 per page</option>
                            <option value="15">15 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider w-12">
                                <span class="sr-only">Select</span>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider cursor-pointer hover:text-slate-700"
                                wire:click="sortBy('name')">
                                <div class="flex items-center">
                                    User
                                    @if($sortField === 'name')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($sortDirection === 'asc')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            @endif
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Roles</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider cursor-pointer hover:text-slate-700"
                                wire:click="sortBy('created_at')">
                                <div class="flex items-center">
                                    Joined
                                    @if($sortField === 'created_at')
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($sortDirection === 'asc')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            @endif
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($users as $user)
                        <tr class="hover:bg-slate-50 transition-colors" wire:key="user-{{ $user->id }}">
                            <td class="px-6 py-4">
                                <input wire:model.live="selectedUsers" 
                                       type="checkbox" 
                                       value="{{ $user->id }}"
                                       class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300 rounded">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-emerald-400 to-teal-500 flex items-center justify-center">
                                            <span class="text-white font-medium text-sm">{{ substr($user->name, 0, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-slate-900">{{ $user->name }}</div>
                                        <div class="text-sm text-slate-500">{{ $user->email }}</div>
                                        @if($user->phone)
                                            <div class="text-xs text-slate-400">{{ $user->phone }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($user->roles->take(2) as $role)
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium {{ $role->name === 'Super Admin' ? 'bg-red-100 text-red-800' : 'bg-emerald-100 text-emerald-800' }}">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="text-sm text-slate-500">No roles</span>
                                    @endforelse
                                    @if($user->roles->count() > 2)
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-600">
                                            +{{ $user->roles->count() - 2 }} more
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ ($user->status ?? 'active') === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($user->status ?? 'active') }}
                                    </span>
                                    @if($user->email_verified_at)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Verified
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Pending
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500">
                                <div>{{ $user->created_at->format('M d, Y') }}</div>
                                <div class="text-xs">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- View Button -->
                                    <button wire:click="viewUser({{ $user->id }})"
                                            class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-medium rounded-md transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View
                                    </button>

                                    @if(!($user->hasRole('Super Admin') && !auth()->user()->hasRole('Super Admin')))
                                        @can('update', $user)
                                        <button wire:click="editUser({{ $user->id }})"
                                                class="inline-flex items-center px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-700 text-sm font-medium rounded-md transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </button>
                                        @endcan

                                        <!-- Quick Status Toggle -->
                                        @if($user->id !== auth()->id())
                                        <button wire:click="toggleUserStatus({{ $user->id }})"
                                                class="inline-flex items-center px-3 py-1.5 {{ ($user->status ?? 'active') === 'active' ? 'bg-red-100 hover:bg-red-200 text-red-700' : 'bg-green-100 hover:bg-green-200 text-green-700' }} text-sm font-medium rounded-md transition-colors">
                                            @if(($user->status ?? 'active') === 'active')
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18 21l-2.5-2.5m-10.5 0L3 21l2.5-2.5"/>
                                                </svg>
                                                Deactivate
                                            @else
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Activate
                                            @endif
                                        </button>
                                        @endif

                                        <!-- Delete Button -->
                                        @if($this->canUserDeleteUser($user))
                                        <button wire:click="confirmDelete({{ $user->id }})"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-medium rounded-md transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Delete
                                        </button>
                                        @else
                                        @php
                                            $lockReason = '';
                                            if($user->hasRole('Super Admin') && !auth()->user()->hasRole('Super Admin')) {
                                                $lockReason = 'Super Admin protection';
                                            } elseif($user->id === auth()->id()) {
                                                $lockReason = 'Cannot delete own account';
                                            } else {
                                                $lockReason = 'No delete permission';
                                            }
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-500 text-sm font-medium rounded-md"
                                              title="{{ $lockReason }}">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                            Locked
                                        </span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-sm font-medium rounded-md" title="Super Admin user cannot be modified">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                            Protected
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <h3 class="text-lg font-medium text-slate-900 mb-2">No users found</h3>
                                    <p class="text-slate-500">{{ $search || $roleFilter || $statusFilter || $verificationFilter ? 'Try adjusting your search criteria.' : 'Get started by creating your first user.' }}</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $users->links() }}
            </div>
            @endif
        </div>

        <!-- Delete Confirmation Modal -->
        @if($confirmingUserDeletion)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" wire:key="delete-modal-{{ $userToDelete }}">
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-medium text-gray-900">Delete User</h3>
                </div>

                <div class="mb-6">
                    <p class="text-sm text-gray-600 mb-3">
                        Are you sure you want to delete the user <strong class="text-gray-900">"{{ $userToDeleteName }}"</strong>?
                    </p>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                        <p class="text-sm text-red-800">
                            <strong>⚠️ Warning:</strong> This action cannot be undone and will permanently remove this user and revoke all their access.
                        </p>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button wire:click="cancelDelete"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button wire:click="delete"
                            wire:loading.attr="disabled"
                            wire:target="delete"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white font-medium rounded-lg transition-colors disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="delete">Delete User</span>
                        <span wire:loading wire:target="delete" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Deleting...
                        </span>
                    </button>
                </div>
            </div>
        </div>
        @endif

        <!-- Loading Overlay -->
        <div wire:loading.flex wire:target="search,roleFilter,statusFilter,verificationFilter,perPage" class="fixed inset-0 z-40 bg-white bg-opacity-75 flex items-center justify-center">
            <div class="flex items-center space-x-2">
                <svg class="animate-spin h-5 w-5 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-sm text-slate-600">Loading...</span>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        console.log('Livewire initialized for ListUser component');

        Livewire.on('userDeleted', (event) => {
            console.log('User deleted event received:', event);
        });
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && @json($confirmingUserDeletion)) {
            @this.call('cancelDelete');
        }
    });
</script>