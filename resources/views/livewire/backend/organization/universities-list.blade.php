<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-800 mb-2">University Management</h1>
                        <p class="text-slate-600">Manage all universities in your organization</p>
                    </div>

                    @can('create', App\Models\University::class)
                        <button wire:click="createUniversity"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create New University
                        </button>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-slate-600">Total Universities</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-slate-600">Active Universities</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $stats['active'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-slate-600">Inactive Universities</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $stats['inactive'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-slate-600">With Faculties</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $stats['with_faculties'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="flex flex-col lg:flex-row gap-4">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <div class="relative">
                            <input wire:model.live.debounce.300ms="search" type="text"
                                placeholder="Search universities by name, code, description, address, or email..."
                                class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="lg:w-48">
                        <select wire:model.live="statusFilter"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @foreach ($statusFilterOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Per Page Selector -->
                    <div class="lg:w-32">
                        <select wire:model.live="perPage"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="5">5 per page</option>
                            <option value="10">10 per page</option>
                            <option value="15">15 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                        </select>
                    </div>

                    <!-- Clear Filters -->
                    @if ($search || $statusFilter)
                        <button wire:click="clearFilters"
                            class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Universities Table -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            @if ($universities->count() > 0)
                <!-- Table Header -->
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <div class="grid grid-cols-12 gap-4 items-center text-sm font-medium text-slate-700">
                        <div class="col-span-4">
                            <button wire:click="sortBy('name')"
                                class="flex items-center hover:text-slate-900 transition-colors">
                                University Name
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $this->getSortIcon('name') }}" />
                                </svg>
                            </button>
                        </div>
                        <div class="col-span-2">
                            <button wire:click="sortBy('code')"
                                class="flex items-center hover:text-slate-900 transition-colors">
                                Code
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $this->getSortIcon('code') }}" />
                                </svg>
                            </button>
                        </div>
                        <div class="col-span-2 text-center">Status & Faculties</div>
                        <div class="col-span-2">
                            <button wire:click="sortBy('created_at')"
                                class="flex items-center hover:text-slate-900 transition-colors">
                                Created
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $this->getSortIcon('created_at') }}" />
                                </svg>
                            </button>
                        </div>
                        <div class="col-span-2 text-center">Actions</div>
                    </div>
                </div>

                <!-- Table Body -->
                <div class="divide-y divide-slate-200">
                    @foreach ($universities as $university)
                        <div class="px-6 py-4 hover:bg-slate-50 transition-colors"
                            wire:key="university-{{ $university->id }}">
                            <div class="grid grid-cols-12 gap-4 items-center">
                                <!-- University Name & Info -->
                                <div class="col-span-4">
                                    <div class="flex items-start space-x-3">
                                        <!-- University Icon -->
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                                <span class="text-white font-bold text-lg">{{ substr($university->name, 0, 1) }}</span>
                                            </div>
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-medium text-slate-900 truncate">
                                                {{ $university->name }}</h3>
                                            @if ($university->website)
                                                <div class="mt-1 flex items-center space-x-2">
                                                    <a href="{{ $university->website }}" target="_blank"
                                                        class="text-xs text-blue-600 hover:text-blue-800 transition-colors"
                                                        title="Visit website">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            @endif
                                            @if ($university->description)
                                                <p class="mt-1 text-xs text-slate-600 line-clamp-2">
                                                    {{ $this->getContentPreview($university->description) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Code -->
                                <div class="col-span-2">
                                    @if ($university->code)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                            {{ $university->code }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">No code</span>
                                    @endif
                                </div>

                                <!-- Status & Faculties -->
                                <div class="col-span-2">
                                    <div class="flex flex-col items-center space-y-2">
                                        @php $statusBadge = $this->getStatusBadge($university->is_active) @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusBadge['class'] }}">
                                            {{ $statusBadge['text'] }}
                                        </span>

                                        @if ($university->faculties_count > 0)
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                                title="Total Faculties">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                {{ $university->faculties_count }} faculties
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400">No faculties</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Created Date -->
                                <div class="col-span-2">
                                    <div class="text-sm text-slate-900">{{ $university->created_at->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-slate-500">{{ $university->created_at->format('H:i') }}</div>
                                </div>

                                <!-- Actions -->
                                <div class="col-span-2">
                                    <div class="flex items-center justify-center space-x-2">
                                        <!-- View Button -->
                                        <button wire:click="viewUniversity({{ $university->id }})"
                                            class="inline-flex items-center px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded transition-colors"
                                            title="View university details">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View
                                        </button>

                                        @can('update', $university)
                                            <button wire:click="editUniversity({{ $university->id }})"
                                                class="inline-flex items-center px-2 py-1 bg-amber-100 hover:bg-amber-200 text-amber-700 text-xs font-medium rounded transition-colors"
                                                title="Edit university">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </button>
                                        @endcan

                                        @if ($this->canUserDeleteUniversity($university))
                                            <button wire:click="confirmDelete({{ $university->id }})"
                                                class="inline-flex items-center px-2 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-medium rounded transition-colors"
                                                title="Delete university">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-500 text-xs font-medium rounded"
                                                title="No delete permission">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                                Locked
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <h3 class="text-lg font-medium text-slate-900 mb-2">No universities found</h3>
                    <p class="text-slate-500 mb-4">
                        {{ $search || $statusFilter ? 'Try adjusting your search criteria.' : 'Get started by creating your first university.' }}
                    </p>
                    @can('create', App\Models\University::class)
                        <button wire:click="createUniversity"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create New University
                        </button>
                    @endcan
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if ($universities->hasPages())
            <div class="mt-8">
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                    {{ $universities->links() }}
                </div>
            </div>
        @endif

        <!-- Delete Confirmation Modal -->
        @if ($confirmingUniversityDeletion)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                wire:key="delete-modal-{{ $universityToDelete }}">
                <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 shadow-xl">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-medium text-gray-900">Delete University</h3>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-600 mb-3">
                            Are you sure you want to delete the university <strong
                                class="text-gray-900">"{{ $universityToDeleteName }}"</strong>?
                        </p>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                            <p class="text-sm text-red-800">
                                <strong>⚠️ Warning:</strong> This action cannot be undone and will permanently remove
                                this university and all its associated data.
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button wire:click="cancelDelete"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button wire:click="deleteUniversity" wire:loading.attr="disabled" wire:target="deleteUniversity"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white font-medium rounded-lg transition-colors disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="deleteUniversity">Delete University</span>
                            <span wire:loading wire:target="deleteUniversity" class="flex items-center">
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
        <div wire:loading.flex wire:target="search,statusFilter,perPage,sortBy"
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
            <div>confirmingUniversityDeletion: {{ $confirmingUniversityDeletion ? 'true' : 'false' }}</div>
            <div>universityToDelete: {{ $universityToDelete ?? 'null' }}</div>
            <div>universityToDeleteName: {{ $universityToDeleteName ?? 'null' }}</div>
            <div>Current Filters:
                {{ json_encode(['search' => $search, 'statusFilter' => $statusFilter]) }}
            </div>
        </div>
    @endif
</div>

    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            console.log('Livewire initialized for UniversitiesList component');

            Livewire.on('universityDeleted', (event) => {
                console.log('University deleted event received:', event);
            });
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && @json($confirmingUniversityDeletion)) {
                @this.call('cancelDelete');
            }
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('[wire\\:click*="confirmDelete"]')) {
                console.log('Delete button clicked:', e.target.closest('[wire\\:click*="confirmDelete"]')
                    .getAttribute('wire:click'));
            }

            // Debug delete university button click
            if (e.target.closest('[wire\\:click="deleteUniversity"]')) {
                console.log('DELETE UNIVERSITY BUTTON CLICKED!');
                console.log('Button element:', e.target.closest('[wire\\:click="deleteUniversity"]'));
            }
        });

        // Additional debug for Livewire calls
        document.addEventListener('livewire:request', (event) => {
            if (event.detail.request.payload.updates[0].method === 'deleteUniversity') {
                console.log('Livewire deleteUniversity request started');
            }
        });

        document.addEventListener('livewire:response', (event) => {
            console.log('Livewire response received');
        });
    </script>
</div>
