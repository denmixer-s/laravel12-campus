<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="h-8 w-8 rounded-lg bg-gradient-to-r from-green-500 to-teal-600 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </div>
                            <h1 class="text-3xl font-bold text-slate-800">{{ $page->title }}</h1>
                        </div>
                        <div class="flex items-center gap-4 text-sm text-slate-600">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.1m0 0l4-4a4 4 0 105.656-5.656l-4 4"/>
                                </svg>
                                /{{ $page->slug }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ $page->user ? $page->user->name : 'Unknown User' }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $this->getRelativeTime($page->created_at) }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Live Page Link -->
                        <a href="{{ $this->getPageUrl() }}"
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-green-100 hover:bg-green-200 text-green-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            View Live
                        </a>

                        @if($this->canEdit)
                        <button wire:click="editPage"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Page
                        </button>
                        @endif

                        <button wire:click="backToList"
                                class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to List
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Page Content -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-slate-800">Page Content</h2>
                        @if($page->content)
                        <button wire:click="toggleFullContent"
                                class="text-sm text-blue-600 hover:text-blue-800 transition-colors">
                            {{ $showFullContent ? 'Show Preview' : 'Show Full Content' }}
                        </button>
                        @endif
                    </div>

                    @if($page->content)
                        <div class="prose max-w-none">
                            @if($showFullContent)
                                <div class="text-slate-700 leading-relaxed">
                                    {!! $page->content !!}
                                </div>
                            @else
                                <div class="text-slate-700 leading-relaxed">
                                    {{ $this->getContentPreview() }}
                                    @if(strlen(strip_tags($page->content)) > 200)
                                        <button wire:click="toggleFullContent" class="text-blue-600 hover:text-blue-800 ml-2">
                                            Read more...
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-slate-500">No content available for this page.</p>
                        </div>
                    @endif
                </div>

                <!-- Featured Image -->
                @if($featuredImage)
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <h2 class="text-xl font-semibold text-slate-800 mb-6">Featured Image</h2>
                    <div class="space-y-4">
                        <div class="relative rounded-lg overflow-hidden border border-slate-200">
                            <img src="{{ $featuredImage->getUrl('featured_large') }}"
                                 alt="{{ $page->title }}"
                                 class="w-full h-auto object-cover">
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-slate-600">
                            <div>
                                <span class="font-medium">File:</span>
                                <span class="block truncate">{{ $featuredImage->file_name }}</span>
                            </div>
                            <div>
                                <span class="font-medium">Size:</span>
                                <span class="block">{{ $this->formatFileSize($featuredImage->size) }}</span>
                            </div>
                            <div>
                                <span class="font-medium">Type:</span>
                                <span class="block">{{ $featuredImage->mime_type }}</span>
                            </div>
                            <div>
                                <span class="font-medium">Added:</span>
                                <span class="block">{{ $this->getRelativeTime($featuredImage->created_at) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Gallery Images -->
                @if(!empty($galleryImages))
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <h2 class="text-xl font-semibold text-slate-800 mb-6">
                        Gallery Images
                        <span class="text-sm font-normal text-slate-500">({{ count($galleryImages) }} images)</span>
                    </h2>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($galleryImages as $index => $image)
                            <div class="relative group cursor-pointer"
                                 wire:click="openGalleryModal({{ json_encode($image) }})"
                                 wire:key="gallery-{{ $image['id'] }}">
                                <div class="aspect-square rounded-lg overflow-hidden border border-slate-200 hover:border-blue-400 transition-colors">
                                    <img src="{{ $image['urls']['medium'] }}"
                                         alt="{{ $image['name'] }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                </div>

                                <!-- Overlay on hover -->
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 rounded-lg transition-all flex items-center justify-center">
                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                        <div class="bg-white rounded-full p-2 shadow-lg">
                                            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Image info -->
                                <div class="mt-2">
                                    <p class="text-xs text-slate-600 truncate">{{ $image['name'] }}</p>
                                    <p class="text-xs text-slate-500">{{ $this->formatFileSize($image['size']) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Page Statistics -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">📊 Page Statistics</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-slate-600">Words:</span>
                            <span class="text-slate-800 font-medium">{{ number_format($this->getWordCount()) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Characters:</span>
                            <span class="text-slate-800 font-medium">{{ number_format($this->getCharacterCount()) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Images:</span>
                            <span class="text-slate-800 font-medium">{{ $imagesSummary['total'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Created:</span>
                            <span class="text-slate-800 font-medium">{{ $page->created_at ? $page->created_at->format('M d, Y') : 'Unknown' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600">Updated:</span>
                            <span class="text-slate-800 font-medium">{{ $page->updated_at ? $page->updated_at->format('M d, Y') : 'Never' }}</span>
                        </div>
                    </div>
                </div>

                <!-- SEO Analysis -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">🔍 SEO Analysis</h3>

                    <!-- SEO Score -->
                    <div class="mb-4">
                        <div class="flex justify-between text-sm text-slate-600 mb-1">
                            <span>SEO Score</span>
                            <span>{{ $this->seoScore }}/100</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            <div class="h-2 rounded-full transition-all duration-300 {{ $this->seoScore >= 80 ? 'bg-green-500' : ($this->seoScore >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                 style="width: {{ $this->seoScore }}%"></div>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">
                            @if($this->seoScore >= 80)
                                Excellent SEO optimization!
                            @elseif($this->seoScore >= 60)
                                Good SEO, room for improvement
                            @else
                                Needs SEO optimization
                            @endif
                        </p>
                    </div>

                    <!-- SEO Recommendations -->
                    @if(!empty($this->seoRecommendations))
                    <div>
                        <h4 class="text-sm font-medium text-slate-700 mb-2">Recommendations:</h4>
                        <ul class="space-y-1">
                            @foreach($this->seoRecommendations as $recommendation)
                            <li class="text-xs text-slate-600 flex items-start">
                                <svg class="w-3 h-3 text-yellow-500 mr-1 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                {{ $recommendation }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <svg class="w-8 h-8 text-green-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-green-600 font-medium">Great SEO!</p>
                        <p class="text-xs text-slate-500">No recommendations at this time</p>
                    </div>
                    @endif
                </div>

                <!-- Images Summary -->
                @if($imagesSummary['total'] > 0)
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">🖼️ Images Summary</h3>
                    <div class="space-y-3">
                        @if($imagesSummary['featured'] > 0)
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center text-sm text-slate-600">
                                <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Featured Image
                            </span>
                            <span class="text-slate-800 font-medium">{{ $imagesSummary['featured'] }}</span>
                        </div>
                        @endif

                        @if($imagesSummary['gallery'] > 0)
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center text-sm text-slate-600">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                Gallery Images
                            </span>
                            <span class="text-slate-800 font-medium">{{ $imagesSummary['gallery'] }}</span>
                        </div>
                        @endif

                        <div class="pt-2 border-t border-slate-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Total Images</span>
                                <span class="text-slate-800 font-bold">{{ $imagesSummary['total'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Quick Actions -->
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border border-blue-200 p-6">
                    <h3 class="text-lg font-semibold text-blue-800 mb-4">⚡ Quick Actions</h3>
                    <div class="space-y-3">
                        @if($this->canEdit)
                        <button wire:click="editPage"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit This Page
                        </button>
                        @endif

                        <a href="{{ $this->getPageUrl() }}"
                           target="_blank"
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            View Live Page
                        </a>

                        <button wire:click="backToList"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to List
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Modal -->
    @if($showGalleryModal && $selectedGalleryImage)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75" wire:key="gallery-modal">
        <div class="relative max-w-4xl max-h-[90vh] mx-4">
            <!-- Close Button -->
            <button wire:click="closeGalleryModal"
                    class="absolute top-4 right-4 z-10 bg-black bg-opacity-50 hover:bg-opacity-75 text-white rounded-full p-2 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <!-- Navigation Buttons -->
            @if(count($galleryImages) > 1)
            <button wire:click="prevGalleryImage"
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 z-10 bg-black bg-opacity-50 hover:bg-opacity-75 text-white rounded-full p-2 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <button wire:click="nextGalleryImage"
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 z-10 bg-black bg-opacity-50 hover:bg-opacity-75 text-white rounded-full p-2 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            @endif

            <!-- Image -->
            <div class="bg-white rounded-lg overflow-hidden shadow-2xl">
                <img src="{{ $selectedGalleryImage['urls']['large'] }}"
                     alt="{{ $selectedGalleryImage['name'] }}"
                     class="w-full h-auto max-h-[70vh] object-contain">

                <!-- Image Info -->
                <div class="p-4 bg-white">
                    <h3 class="text-lg font-semibold text-slate-800 mb-2">{{ $selectedGalleryImage['name'] }}</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-slate-600">
                        <div>
                            <span class="font-medium">File:</span>
                            <span class="block truncate">{{ $selectedGalleryImage['file_name'] }}</span>
                        </div>
                        <div>
                            <span class="font-medium">Size:</span>
                            <span class="block">{{ $this->formatFileSize($selectedGalleryImage['size']) }}</span>
                        </div>
                        <div>
                            <span class="font-medium">Type:</span>
                            <span class="block">{{ $selectedGalleryImage['mime_type'] }}</span>
                        </div>
                        <div>
                            <span class="font-medium">Position:</span>
                            <span class="block">
                                {{ array_search($selectedGalleryImage, $galleryImages) + 1 }} of {{ count($galleryImages) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        console.log('Livewire initialized for ShowPage component');
    });

    // Close gallery modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && @json($showGalleryModal)) {
            @this.call('closeGalleryModal');
        }
        if (@json($showGalleryModal) && @json(count($galleryImages) > 1)) {
            if (e.key === 'ArrowLeft') {
                @this.call('prevGalleryImage');
            } else if (e.key === 'ArrowRight') {
                @this.call('nextGalleryImage');
            }
        }
    });
</script>
