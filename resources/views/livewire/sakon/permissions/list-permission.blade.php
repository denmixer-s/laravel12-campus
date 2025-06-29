<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-800 mb-2">Permission Management</h1>
                        <p class="text-slate-600">Manage system permissions and access controls</p>
                    </div>

                    @can('create', Spatie\Permission\Models\Permission::class)
                        <button wire:click="createPermission"
                            class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create New Permission
                        </button>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Total</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Web Guard</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $stats['web_guard'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">API Guard</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $stats['api_guard'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-emerald-100 rounded-lg">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Assigned</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $stats['assigned'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-amber-100 rounded-lg">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">Unassigned</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $stats['unassigned'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Search Input -->
                    <div class="md:col-span-2">
                        <div class="relative">
                            <input wire:model.live.debounce.300ms="search" type="text"
                                placeholder="Search permissions..."
                                class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                            <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Guard Filter -->
                    <div>
                        <select wire:model.live="filterGuard"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                            <option value="">All Guards</option>
                            <option value="web">Web</option>
                            <option value="api">API</option>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <select wire:model.live="filterCategory"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                            <option value="">All Categories</option>
                            @foreach ($availableCategories as $category)
                                <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Per Page Selector -->
                    <div>
                        <select wire:model.live="perPage"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                            <option value="5">5 per page</option>
                            <option value="10">10 per page</option>
                            <option value="15">15 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                        </select>
                    </div>
                </div>

                <!-- Bulk Actions -->
                @if (count($selectedPermissions) > 0)
                    <div class="mt-4 pt-4 border-t border-slate-200">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">{{ count($selectedPermissions) }} permission(s)
                                selected</span>
                            <div class="flex items-center gap-2">
                                <button wire:click="bulkDelete"
                                    onclick="return confirm('Are you sure you want to delete the selected permissions?')"
                                    class="inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-medium rounded-md transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete Selected
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Permissions Table -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left">
                                <label class="flex items-center cursor-pointer">
                                    <input wire:model.live="selectAll" type="checkbox"
                                        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300 rounded">
                                    <span
                                        class="ml-2 text-xs font-medium text-slate-500 uppercase tracking-wider">Permission</span>
                                </label>
                            </th>
                            <th
                                class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Guard</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Category</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Roles</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Created</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($permissions as $permission)
                            <tr class="hover:bg-slate-50 transition-colors"
                                wire:key="permission-{{ $permission->id }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <input wire:model.live="selectedPermissions" type="checkbox"
                                            value="{{ $permission->id }}"
                                            class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300 rounded mr-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div
                                                    class="h-10 w-10 rounded-full bg-gradient-to-r from-emerald-400 to-teal-500 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-slate-900 font-mono">
                                                    {{ $permission->name }}</div>
                                                @if ($permission->roles_count > 0)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                        Assigned
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                        Unassigned
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium {{ $permission->guard_name === 'web' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ $permission->guard_name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $parts = explode('-', $permission->name);
                                        $category = count($parts) > 1 ? $parts[1] : 'general';
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-800 capitalize">
                                        {{ $category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <span
                                            class="text-sm font-medium text-slate-900">{{ $permission->roles_count }}</span>
                                        <span
                                            class="text-sm text-slate-500 ml-1">{{ Str::plural('role', $permission->roles_count) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ $permission->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <!-- View Button -->
                                        <button wire:click="viewPermission({{ $permission->id }})"
                                            class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-medium rounded-md transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View
                                        </button>

                                        @can('update', $permission)
                                            <button wire:click="editPermission({{ $permission->id }})"
                                                class="inline-flex items-center px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-700 text-sm font-medium rounded-md transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </button>
                                        @endcan

                                        <!-- Delete Button -->
                                        @if ($this->canUserDeletePermission($permission))
                                            <button wire:click="confirmDelete({{ $permission->id }})"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-medium rounded-md transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        @else
                                            @php
                                                $lockReason = '';
                                                if ($permission->roles_count > 0) {
                                                    $lockReason = 'Permission has assigned roles';
                                                } else {
                                                    $lockReason = 'No delete permission';
                                                }
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-500 text-sm font-medium rounded-md"
                                                title="{{ $lockReason }}">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                                Locked
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-slate-300 mb-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-slate-900 mb-2">No permissions found</h3>
                                        <p class="text-slate-500">
                                            {{ $search ? 'Try adjusting your search criteria.' : 'Get started by creating your first permission.' }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($permissions->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $permissions->links() }}
                </div>
            @endif
        </div>

        <!-- Delete Confirmation Modal -->
        @if ($confirmingPermissionDeletion)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                wire:key="delete-modal-{{ $permissionToDelete }}">
                <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 shadow-xl">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-medium text-gray-900">Delete Permission</h3>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-600 mb-3">
                            Are you sure you want to delete the permission <strong
                                class="text-gray-900 font-mono">"{{ $permissionToDeleteName }}"</strong>?
                        </p>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                            <p class="text-sm text-red-800">
                                <strong>⚠️ Warning:</strong> This action cannot be undone and will permanently remove
                                this permission from the system.
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button wire:click="cancelDelete"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button wire:click="delete" wire:loading.attr="disabled" wire:target="delete"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white font-medium rounded-lg transition-colors disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="delete">Delete Permission</span>
                            <span wire:loading wire:target="delete" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Deleting...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Loading Overlay -->
        <div wire:loading.flex wire:target="search,perPage,filterGuard,filterCategory"
            class="fixed inset-0 z-40 bg-white bg-opacity-75 flex items-center justify-center">
            <div class="flex items-center space-x-2">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="text-sm text-slate-600">Loading...</span>
            </div>
        </div>
    </div>

    <!-- Debug Information (Remove in production) -->
    @if (app()->environment('local'))
        <div class="fixed bottom-4 right-4 bg-black bg-opacity-75 text-white p-3 rounded-lg text-xs max-w-sm">
            <div><strong>Debug Info:</strong></div>
            <div>confirmingPermissionDeletion: {{ $confirmingPermissionDeletion ? 'true' : 'false' }}</div>
            <div>permissionToDelete: {{ $permissionToDelete ?? 'null' }}</div>
            <div>permissionToDeleteName: {{ $permissionToDeleteName ?? 'null' }}</div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        console.log('Livewire initialized for ListPermission component');

        Livewire.on('permissionDeleted', (event) => {
            console.log('Permission deleted event received:', event);
        });
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && @json($confirmingPermissionDeletion)) {
            @this.call('cancelDelete');
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('[wire\\:click*="confirmDelete"]')) {
            console.log('Delete button clicked:', e.target.closest('[wire\\:click*="confirmDelete"]')
                .getAttribute('wire:click'));
        }
    });
</script>
