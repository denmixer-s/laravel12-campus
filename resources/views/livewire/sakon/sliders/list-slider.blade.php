<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-800 mb-2">Slider Management</h1>
                        <p class="text-slate-600">Manage homepage and frontend sliders for your website</p>
                    </div>

                    @can('create', App\Models\Slider::class)
                        <button wire:click="createSlider"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create New Slider
                        </button>
                    @endcan
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
                                placeholder="Search sliders by heading, description, or link..."
                                class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Show Location Filter -->
                    <div class="lg:w-48">
                        <select wire:model.live="showFilter"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @foreach ($showOptions as $value => $label)
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
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sliders Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($sliders as $slider)
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow duration-200"
                    wire:key="slider-{{ $slider->id }}">
                    <!-- Slider Image -->
                    <div class="aspect-video relative overflow-hidden bg-slate-100">
                        @if (!empty($slider->image_path) || $slider->hasMedia('slider_images'))
                            @if (!empty($slider->image_path))
                                <img src="{{ asset('storage/' . $slider->image_path) }}" alt="{{ $slider->heading }}"
                                    class="w-full h-full object-cover" loading="lazy">
                            @else
                                <img src="{{ $slider->getFirstMedia('slider_images')->getUrl() }}"
                                    alt="{{ $slider->heading }}" class="w-full h-full object-cover" loading="lazy">
                            @endif
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-slate-200">
                                <svg class="w-16 h-16 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif

                        <!-- Show Location Badge -->
                        <div class="absolute top-3 left-3">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $slider->show_badge_color }}">
                                {{ $slider->show_location }}
                            </span>
                        </div>
                    </div>

                    <!-- Slider Content -->
                    <div class="p-6">
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-slate-800 mb-2 line-clamp-2">{{ $slider->heading }}
                            </h3>
                            @if ($slider->description)
                                <p class="text-sm text-slate-600 line-clamp-3">{{ $slider->description }}</p>
                            @endif
                        </div>

                        <!-- Link -->
                        <div class="mb-4">
                            <div class="flex items-center text-sm text-slate-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                <span class="truncate">{{ $slider->link }}</span>
                            </div>
                        </div>

                        <!-- Meta Information -->
                        <div class="flex items-center justify-between text-xs text-slate-500 mb-4">
                            <div class="flex items-center">
                                <div
                                    class="h-6 w-6 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center mr-2">
                                    <span
                                        class="text-white font-medium text-xs">{{ substr($slider->user->name, 0, 1) }}</span>
                                </div>
                                <span>{{ $slider->user->name }}</span>
                            </div>
                            <span>{{ $slider->created_at->format('M d, Y') }}</span>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                            <div class="flex items-center space-x-2">
                                <!-- View Button -->
                                <button wire:click="viewSlider({{ $slider->id }})"
                                    class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-medium rounded-md transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View
                                </button>

                                @can('update', $slider)
                                    <button wire:click="editSlider({{ $slider->id }})"
                                        class="inline-flex items-center px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-700 text-sm font-medium rounded-md transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </button>
                                @endcan
                            </div>

                            <!-- Delete Button -->
                            @if ($this->canUserDeleteSlider($slider))
                                <button wire:click="confirmDelete({{ $slider->id }})"
                                    class="inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-medium rounded-md transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </button>
                            @else
                                <span
                                    class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-500 text-sm font-medium rounded-md"
                                    title="No delete permission">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Locked
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-12 text-center">
                        <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="text-lg font-medium text-slate-900 mb-2">No sliders found</h3>
                        <p class="text-slate-500 mb-4">
                            {{ $search ? 'Try adjusting your search criteria.' : 'Get started by creating your first slider.' }}
                        </p>
                        @can('create', App\Models\Slider::class)
                            <button wire:click="createSlider"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Create New Slider
                            </button>
                        @endcan
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($sliders->hasPages())
            <div class="mt-8">
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                    {{ $sliders->links() }}
                </div>
            </div>
        @endif

        <!-- Delete Confirmation Modal -->
        @if ($confirmingSliderDeletion)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                wire:key="delete-modal-{{ $sliderToDelete }}">
                <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 shadow-xl">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-medium text-gray-900">Delete Slider</h3>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-600 mb-3">
                            Are you sure you want to delete the slider <strong
                                class="text-gray-900">"{{ $sliderToDeleteHeading }}"</strong>?
                        </p>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                            <p class="text-sm text-red-800">
                                <strong>⚠️ Warning:</strong> This action cannot be undone and will permanently remove
                                this slider and its associated image.
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
                            <span wire:loading.remove wire:target="delete">Delete Slider</span>
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
        <div wire:loading.flex wire:target="search,showFilter,perPage"
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
            <div>confirmingSliderDeletion: {{ $confirmingSliderDeletion ? 'true' : 'false' }}</div>
            <div>sliderToDelete: {{ $sliderToDelete ?? 'null' }}</div>
            <div>sliderToDeleteHeading: {{ $sliderToDeleteHeading ?? 'null' }}</div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        console.log('Livewire initialized for ListSlider component');

        Livewire.on('sliderDeleted', (event) => {
            console.log('Slider deleted event received:', event);
        });
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && @json($confirmingSliderDeletion)) {
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
