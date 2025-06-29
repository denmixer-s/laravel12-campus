<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-800 mb-2">Role Management</h1>
                        <p class="text-slate-600">Manage user roles and permissions for your application</p>
                    </div>

                    @can('create', App\Models\Role::class)
                        <button wire:click="createRole"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create New Role
                        </button>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="flex flex-col md:flex-row gap-4">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <div class="relative">
                            <input wire:model.live.debounce.300ms="search" type="text"
                                placeholder="Search roles or permissions..."
                                class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Per Page Selector -->
                    <div class="md:w-32">
                        <select wire:model.live="perPage"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="5">5 per page</option>
                            <option value="10">10 per page</option>
                            <option value="15">15 per page</option>
                            <option value="25">25 per page</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roles Table -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Role</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Permissions</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Users</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Created</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($roles as $role)
                            <tr class="hover:bg-slate-50 transition-colors" wire:key="role-{{ $role->id }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div
                                                class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                                                <span
                                                    class="text-white font-medium text-sm">{{ substr($role->name, 0, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-slate-900">{{ $role->name }}</div>
                                            @if ($role->name === 'Super Admin')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    System Role
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($role->permissions->take(3) as $permission)
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $permission->name }}
                                            </span>
                                        @empty
                                            <span class="text-sm text-slate-500">No permissions</span>
                                        @endforelse
                                        @if ($role->permissions->count() > 3)
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-600">
                                                +{{ $role->permissions->count() - 3 }} more
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <span
                                            class="text-sm font-medium text-slate-900">{{ $role->users->count() }}</span>
                                        <span
                                            class="text-sm text-slate-500 ml-1">{{ Str::plural('user', $role->users->count()) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ $role->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <!-- View Button -->
                                        <button wire:click="viewRole({{ $role->id }})"
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

                                        @if ($role->name !== 'Super Admin')
                                            @can('update', $role)
                                                <button wire:click="editRole({{ $role->id }})"
                                                    class="inline-flex items-center px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-700 text-sm font-medium rounded-md transition-colors">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit
                                                </button>
                                            @endcan

                                            <!-- Delete Button -->
                                            @if ($this->canUserDeleteRole($role))
                                                <button wire:click="confirmDelete({{ $role->id }})"
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
                                                    if ($role->users->count() > 0) {
                                                        $lockReason = 'Role has assigned users';
                                                    } elseif (auth()->user()->hasRole($role->name)) {
                                                        $lockReason = 'Cannot delete your own role';
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
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 text-sm font-medium rounded-md"
                                                title="System role cannot be deleted">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                                Protected
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-slate-300 mb-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-slate-900 mb-2">No roles found</h3>
                                        <p class="text-slate-500">
                                            {{ $search ? 'Try adjusting your search criteria.' : 'Get started by creating your first role.' }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($roles->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $roles->links() }}
                </div>
            @endif
        </div>

        <!-- Delete Confirmation Modal -->
        @if ($confirmingRoleDeletion)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                wire:key="delete-modal-{{ $roleToDelete }}">
                <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 shadow-xl">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-medium text-gray-900">Delete Role</h3>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-600 mb-3">
                            Are you sure you want to delete the role <strong
                                class="text-gray-900">"{{ $roleToDeleteName }}"</strong>?
                        </p>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                            <p class="text-sm text-red-800">
                                <strong>⚠️ Warning:</strong> This action cannot be undone and will permanently remove
                                this role and all its permissions.
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
                            <span wire:loading.remove wire:target="delete">Delete Role</span>
                            <span wire:loading wire:target="delete" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
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
        <div wire:loading.flex wire:target="search,perPage"
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
            <div>confirmingRoleDeletion: {{ $confirmingRoleDeletion ? 'true' : 'false' }}</div>
            <div>roleToDelete: {{ $roleToDelete ?? 'null' }}</div>
            <div>roleToDeleteName: {{ $roleToDeleteName ?? 'null' }}</div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        console.log('Livewire initialized for ListRole component');

        Livewire.on('roleDeleted', (event) => {
            console.log('Role deleted event received:', event);
        });
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && @json($confirmingRoleDeletion)) {
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
