<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-800 mb-2">Faculty Management</h1>
                        <p class="text-slate-600">Manage faculties and offices across universities</p>
                    </div>

                    @can('create', App\Models\Faculty::class)
                        <button wire:click="createFaculty"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create New Faculty
                        </button>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-slate-600">Total</p>
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
                        <p class="text-sm text-slate-600">Active</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $stats['active'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-slate-600">Faculties</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $stats['faculties'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-slate-600">Offices</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $stats['offices'] }}</p>
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
                        <p class="text-sm text-slate-600">Inactive</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $stats['inactive'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-amber-100 rounded-lg">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-slate-600">With Divisions</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $stats['with_divisions'] }}</p>
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
                                placeholder="Search faculties by name, code, description, or university..."
                                class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- University Filter -->
                    <div class="lg:w-56">
                        <select wire:model.live="universityFilter"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @foreach ($universityFilterOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type Filter -->
                    <div class="lg:w-40">
                        <select wire:model.live="typeFilter"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @foreach ($typeFilterOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="lg:w-40">
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
                    @if ($search || $universityFilter || $typeFilter || $statusFilter)
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

        <!-- Faculties Table -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            @if ($faculties->count() > 0)
                <!-- Table Header -->
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <div class="grid grid-cols-12 gap-4 items-center text-sm font-medium text-slate-700">
                        <div class="col-span-3">
                            <button wire:click="sortBy('name')"
                                class="flex items-center hover:text-slate-900 transition-colors">
                                Faculty Name
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $this->getSortIcon('name') }}" />
                                </svg>
                            </button>
                        </div>
                        <div class="col-span-2">University</div>
                        <div class="col-span-1 text-center">Type</div>
                        <div class="col-span-2 text-center">Status & Divisions</div>
                        <div class="col-span-2">
                            <button wire:click="sortBy('sort_order')"
                                class="flex items-center hover:text-slate-900 transition-colors">
                                Sort Order
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $this->getSortIcon('sort_order') }}" />
                                </svg>
                            </button>
                        </div>
                        <div class="col-span-2 text-center">Actions</div>
                    </div>
                </div>

                <!-- Table Body -->
                <div class="divide-y divide-slate-200">
                    @foreach ($faculties as $faculty)
                        <div class="px-6 py-4 hover:bg-slate-50 transition-colors"
                            wire:key="faculty-{{ $faculty->id }}">
                            <div class="grid grid-cols-12 gap-4 items-center">
                                <!-- Faculty Name & Info -->
                                <div class="col-span-3">
                                    <div class="flex items-start space-x-3">
                                        <!-- Faculty Icon -->
                                        <div class="flex-shrink-0">
                                            @php $typeBadge = $this->getTypeBadge($faculty->type) @endphp
                                            <div class="w-12 h-12 bg-gradient-to-r from-{{ $faculty->type === 'faculty' ? 'blue' : 'purple' }}-500 to-{{ $faculty->type === 'faculty' ? 'indigo' : 'pink' }}-600 rounded-lg flex items-center justify-center">
                                                <span class="text-white font-bold text-lg">{{ substr($faculty->name, 0, 1) }}</span>
                                            </div>
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-medium text-slate-900 truncate">
                                                {{ $faculty->name }}
                                            </h3>
                                            @if ($faculty->code)
                                                <div class="mt-1">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-800">
                                                        {{ $faculty->code }}
                                                    </span>
                                                </div>
                                            @endif
                                            @if ($faculty->description)
                                                <p class="mt-1 text-xs text-slate-600 line-clamp-2">
                                                    {{ $this->getContentPreview($faculty->description) }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- University -->
                                <div class="col-span-2">
                                    <div class="text-sm font-medium text-slate-900">{{ $faculty->university->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $faculty->university->code ?? 'No code' }}</div>
                                </div>

                                <!-- Type -->
                                <div class="col-span-1">
                                    <div class="flex justify-center">
                                        @php $typeBadge = $this->getTypeBadge($faculty->type) @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $typeBadge['class'] }}">
                                            {{ $typeBadge['text'] }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Status & Divisions -->
                                <div class="col-span-2">
                                    <div class="flex flex-col items-center space-y-2">
                                        @php $statusBadge = $this->getStatusBadge($faculty->is_active) @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusBadge['class'] }}">
                                            {{ $statusBadge['text'] }}
                                        </span>

                                        @if ($faculty->divisions_count > 0)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                                  title="Total Divisions">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                </svg>
                                                {{ $faculty->divisions_count }} divisions
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400">No divisions</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Sort Order -->
                                <div class="col-span-2">
                                    <div class="text-sm text-slate-900"># {{ $faculty->sort_order }}</div>
                                    <div class="text-xs text-slate-500">{{ $faculty->created_at->format('M d, Y') }}</div>
                                </div>

                                <!-- Actions -->
                                <div class="col-span-2">
                                    <div class="flex items-center justify-center space-x-2">
                                        <!-- View Button -->
                                        <button wire:click="viewFaculty({{ $faculty->id }})"
                                            class="inline-flex items-center px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded transition-colors"
                                            title="View faculty details">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View
                                        </button>

                                        @can('update', $faculty)
                                            <button wire:click="editFaculty({{ $faculty->id }})"
                                                class="inline-flex items-center px-2 py-1 bg-amber-100 hover:bg-amber-200 text-amber-700 text-xs font-medium rounded transition-colors"
                                                title="Edit faculty">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </button>
                                        @endcan

                                        @if ($this->canUserDeleteFaculty($faculty))
                                            <button wire:click="confirmDelete({{ $faculty->id }})"
                                                class="inline-flex items-center px-2 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-medium rounded transition-colors"
                                                title="Delete faculty">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-500 text-xs font-medium rounded"
                                                  title="No delete permission">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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
                    <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <h3 class="text-lg font-medium text-slate-900 mb-2">No faculties found</h3>
                    <p class="text-slate-500 mb-4">
                        {{ $search || $universityFilter || $typeFilter || $statusFilter ? 'Try adjusting your search criteria.' : 'Get started by creating your first faculty.' }}
                    </p>
                    @can('create', App\Models\Faculty::class)
                        <button wire:click="createFaculty"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create New Faculty
                        </button>
                    @endcan
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if ($faculties->hasPages())
            <div class="mt-8">
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                    {{ $faculties->links() }}
                </div>
            </div>
        @endif

        <!-- Delete Confirmation Modal -->
        @if ($confirmingFacultyDeletion)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                wire:key="delete-modal-{{ $facultyToDelete }}"
                wire:click.self="cancelDelete">
                <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 shadow-xl" @click.stop>
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-medium text-gray-900">Delete Faculty</h3>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-600 mb-3">
                            Are you sure you want to delete the faculty <strong
                                class="text-gray-900">"{{ $facultyToDeleteName }}"</strong>?
                        </p>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                            <p class="text-sm text-red-800">
                                <strong>⚠️ Warning:</strong> This action cannot be undone and will permanently remove
                                this faculty and all its associated data.
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button"
                                wire:click="cancelDelete"
                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button type="button"
                                wire:click="deleteFaculty"
                                wire:loading.attr="disabled"
                                wire:target="deleteFaculty"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white font-medium rounded-lg transition-colors disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="deleteFaculty">Delete Faculty</span>
                            <span wire:loading wire:target="deleteFaculty" class="flex items-center">
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
        <div wire:loading.flex wire:target="search,universityFilter,typeFilter,statusFilter,perPage,sortBy"
            class="fixed inset-0 z-40 bg-white bg-opacity-75 flex items-center justify-center">
            <div class="flex items-center space-x-2">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="text-sm text-slate-600">Loading...</span>
            </div>
        </div>

        <!-- Debug Information (Remove in production) -->
        @if (app()->environment('local'))
            <div class="fixed bottom-4 right-4 bg-black bg-opacity-75 text-white p-3 rounded-lg text-xs max-w-sm">
                <div><strong>Debug Info:</strong></div>
                <div>confirmingFacultyDeletion: {{ $confirmingFacultyDeletion ? 'true' : 'false' }}</div>
                <div>facultyToDelete: {{ $facultyToDelete ?? 'null' }}</div>
                <div>facultyToDeleteName: {{ $facultyToDeleteName ?? 'null' }}</div>
                <div>Current Filters:
                    {{ json_encode(['search' => $search, 'university' => $universityFilter, 'type' => $typeFilter, 'status' => $statusFilter]) }}
                </div>
            </div>
        @endif

        <!-- JavaScript for enhanced UX -->
        <script>
            document.addEventListener('livewire:initialized', () => {
                console.log('Livewire initialized for FacultiesList component');

                Livewire.on('facultyDeleted', (event) => {
                    console.log('Faculty deleted event received:', event);
                });
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && @json($confirmingFacultyDeletion)) {
                    @this.call('cancelDelete');
                }
            });

            document.addEventListener('click', function(e) {
                if (e.target.closest('[wire\\:click*="confirmDelete"]')) {
                    console.log('Delete button clicked:', e.target.closest('[wire\\:click*="confirmDelete"]')
                        .getAttribute('wire:click'));
                }

                // Debug delete faculty button click
                if (e.target.closest('[wire\\:click="deleteFaculty"]')) {
                    console.log('DELETE FACULTY BUTTON CLICKED!');
                    console.log('Button element:', e.target.closest('[wire\\:click="deleteFaculty"]'));
                }
            });

            // Additional debug for Livewire calls
            document.addEventListener('livewire:request', (event) => {
                if (event.detail.request.payload.updates && event.detail.request.payload.updates[0] && event.detail.request.payload.updates[0].method === 'deleteFaculty') {
                    console.log('Livewire deleteFaculty request started');
                }
            });

            document.addEventListener('livewire:response', (event) => {
                console.log('Livewire response received');
            });
        </script>
    </div>
</div>
