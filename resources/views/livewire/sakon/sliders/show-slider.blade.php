<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="h-8 w-8 rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                            <h1 class="text-3xl font-bold text-slate-800">View Slider</h1>
                        </div>
                        <p class="text-slate-600">{{ $slider->heading }}</p>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        @if($this->canUserEditSlider)
                        <button wire:click="editSlider" 
                                class="inline-flex items-center px-4 py-2 bg-amber-100 hover:bg-amber-200 text-amber-700 font-medium rounded-lg transition-colors duration-200">
                            ✏️ Edit
                        </button>
                        @endif
                        
                        @if($this->canUserDeleteSlider)
                        <button wire:click="confirmDelete" 
                                class="inline-flex items-center px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 font-medium rounded-lg transition-colors duration-200">
                            🗑️ Delete
                        </button>
                        @endif
                        
                        <button wire:click="backToList" 
                                class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors duration-200">
                            ← Back to List
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Slider Image Card -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
                    <div class="aspect-video relative overflow-hidden bg-slate-100">
                        @if($slider->hasSliderImage())
                            <img src="{{ $this->sliderImageUrl }}" 
                                 alt="{{ $slider->heading }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-slate-100">
                                <div class="text-center">
                                    <div class="w-24 h-24 bg-slate-300 rounded-lg mx-auto mb-4 flex items-center justify-center">
                                        <span class="text-4xl text-slate-500">🖼️</span>
                                    </div>
                                    <p class="text-slate-500 font-medium">No image available</p>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Display Location Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $this->showBadgeInfo['class'] }}">
                                {{ $this->showBadgeInfo['text'] }}
                            </span>
                        </div>
                        
                        <!-- Link Indicator -->
                        <div class="absolute top-4 right-4">
                            @if(filter_var($slider->link, FILTER_VALIDATE_URL))
                                <a href="{{ $slider->link }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center px-3 py-1 bg-white bg-opacity-90 hover:bg-opacity-100 text-slate-700 rounded-full text-sm font-medium transition-all shadow-sm hover:shadow-md">
                                    🔗 Visit Link
                                </a>
                            @else
                                <span class="inline-flex items-center px-3 py-1 bg-white bg-opacity-90 text-slate-700 rounded-full text-sm font-medium shadow-sm">
                                    @if(str_starts_with($slider->link, '/'))
                                        📁 Site Path
                                    @elseif(str_starts_with($slider->link, '#'))
                                        ⚓ Anchor
                                    @else
                                        🔗 Link
                                    @endif
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Slider Details Card -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <h2 class="text-2xl font-bold text-slate-800 mb-4">{{ $slider->heading }}</h2>
                    
                    @if($slider->description)
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-slate-600 mb-2">Description</h3>
                        <p class="text-slate-700 leading-relaxed">{{ $slider->description }}</p>
                    </div>
                    @endif
                    
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-slate-600 mb-2">Link</h3>
                        <div class="flex items-center">
                            @if(filter_var($slider->link, FILTER_VALIDATE_URL))
                                <a href="{{ $slider->link }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="text-blue-600 hover:text-blue-800 hover:underline break-all">
                                    {{ $slider->link }}
                                </a>
                            @else
                                <span class="text-slate-700 break-all">{{ $slider->link }}</span>
                            @endif
                            <button onclick="copyToClipboard('{{ $slider->link }}')" 
                                    class="ml-2 p-1 text-slate-400 hover:text-slate-600 transition-colors"
                                    title="Copy link">
                                📋
                            </button>
                        </div>
                        <div class="mt-1 text-xs text-slate-500">
                            @if(filter_var($slider->link, FILTER_VALIDATE_URL))
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">
                                    🌐 External URL
                                </span>
                            @elseif(str_starts_with($slider->link, '/'))
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-green-100 text-green-700">
                                    📁 Site Path
                                </span>
                            @elseif(str_starts_with($slider->link, '#'))
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-purple-100 text-purple-700">
                                    ⚓ Page Anchor
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">
                                    🔗 Relative Link
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-slate-600 mb-2">Display Location</h3>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $this->showBadgeInfo['class'] }}">
                                {{ $this->showBadgeInfo['text'] }}
                            </span>
                            <span class="ml-3 text-sm text-slate-500">
                                @switch($slider->show)
                                    @case('home')
                                        This slider appears only on the home page
                                        @break
                                    @case('frontend')
                                        This slider appears only on frontend pages
                                        @break
                                    @case('both')
                                        This slider appears on both home and frontend pages
                                        @break
                                @endswitch
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Responsive Preview Card -->
                @if($slider->hasSliderImage())
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">📱 Responsive Preview</h3>
                    <div class="space-y-6">
                        <!-- Desktop Preview -->
                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2 flex items-center">
                                💻 Desktop (1920x1080)
                            </h4>
                            <div class="aspect-video bg-slate-100 rounded-lg overflow-hidden">
                                <img src="{{ $this->responsiveImageUrls['desktop'] }}" 
                                     alt="{{ $slider->heading }}"
                                     class="w-full h-full object-cover">
                            </div>
                        </div>

                        <!-- Tablet Preview -->
                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2 flex items-center">
                                📱 Tablet (768px)
                            </h4>
                            <div class="max-w-md mx-auto">
                                <div class="aspect-video bg-slate-100 rounded-lg overflow-hidden">
                                    <img src="{{ $this->responsiveImageUrls['tablet'] }}" 
                                         alt="{{ $slider->heading }}"
                                         class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>

                        <!-- Mobile Preview -->
                        <div>
                            <h4 class="text-sm font-medium text-slate-600 mb-2 flex items-center">
                                📱 Mobile (375px)
                            </h4>
                            <div class="max-w-xs mx-auto">
                                <div class="aspect-video bg-slate-100 rounded-lg overflow-hidden">
                                    <img src="{{ $this->responsiveImageUrls['mobile'] }}" 
                                         alt="{{ $slider->heading }}"
                                         class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions Card -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">⚡ Quick Actions</h3>
                    <div class="space-y-3">
                        @if($this->canUserEditSlider)
                        <button wire:click="editSlider"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-amber-100 hover:bg-amber-200 text-amber-700 font-medium rounded-lg transition-colors">
                            ✏️ Edit Slider
                        </button>
                        @endif

                        @if(filter_var($slider->link, FILTER_VALIDATE_URL))
                        <a href="{{ $slider->link }}" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium rounded-lg transition-colors">
                            🔗 Visit Link
                        </a>
                        @else
                        <div class="w-full inline-flex items-center justify-center px-4 py-2 bg-slate-100 text-slate-500 font-medium rounded-lg">
                            @if(str_starts_with($slider->link, '/'))
                                📁 Site Path
                            @elseif(str_starts_with($slider->link, '#'))
                                ⚓ Page Anchor
                            @else
                                🔗 Relative Link
                            @endif
                        </div>
                        @endif

                        @if($slider->hasSliderImage())
                        <button onclick="downloadImage('{{ $this->sliderImageUrl }}', '{{ $slider->heading }}')"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-100 hover:bg-green-200 text-green-700 font-medium rounded-lg transition-colors">
                            💾 Download Image
                        </button>
                        @endif

                        @if($this->canUserDeleteSlider)
                        <button wire:click="confirmDelete"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 font-medium rounded-lg transition-colors">
                            🗑️ Delete Slider
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Slider Details Card -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">📋 Details</h3>
                    <div class="space-y-4">
                        <div>
                            <span class="text-sm text-slate-600">ID:</span>
                            <p class="font-mono text-sm text-slate-800">#{{ $slider->id }}</p>
                        </div>

                        <div>
                            <span class="text-sm text-slate-600">Created by:</span>
                            <div class="flex items-center mt-1">
                                <div class="h-6 w-6 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center mr-2">
                                    <span class="text-white font-medium text-xs">{{ substr($slider->user->name, 0, 1) }}</span>
                                </div>
                                <span class="text-sm font-medium text-slate-800">{{ $slider->user->name }}</span>
                            </div>
                        </div>

                        <div>
                            <span class="text-sm text-slate-600">Created:</span>
                            <p class="text-sm font-medium text-slate-800">{{ $this->formattedCreatedAt }}</p>
                        </div>

                        @if($this->hasBeenModified)
                        <div>
                            <span class="text-sm text-slate-600">Last updated:</span>
                            <p class="text-sm font-medium text-slate-800">{{ $this->formattedUpdatedAt }}</p>
                        </div>
                        @endif

                        @if($slider->hasSliderImage())
                        <div>
                            <span class="text-sm text-slate-600">Image:</span>
                            <p class="text-sm font-medium text-slate-800">✅ Available</p>
                            @if($slider->hasMedia('slider_images'))
                                @php $media = $slider->getFirstMedia('slider_images'); @endphp
                                <div class="text-xs text-slate-500 mt-1">
                                    <p>Size: {{ number_format($media->size / 1024, 1) }} KB</p>
                                    <p>Dimensions: {{ $media->getCustomProperty('width', 'N/A') }}x{{ $media->getCustomProperty('height', 'N/A') }}</p>
                                    <p>Format: {{ strtoupper($media->extension) }}</p>
                                </div>
                            @endif
                        </div>
                        @else
                        <div>
                            <span class="text-sm text-slate-600">Image:</span>
                            <p class="text-sm text-amber-600 font-medium">⚠️ Not available</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- SEO & Technical Info Card -->
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">🔧 Technical Information</h3>
                    <div class="space-y-4">
                        <div>
                            <span class="text-sm text-slate-600">Heading Length:</span>
                            <p class="text-sm font-medium text-slate-800">{{ strlen($slider->heading) }} characters</p>
                        </div>

                        @if($slider->description)
                        <div>
                            <span class="text-sm text-slate-600">Description Length:</span>
                            <p class="text-sm font-medium text-slate-800">{{ strlen($slider->description) }} characters</p>
                        </div>
                        @endif

                        <div>
                            <span class="text-sm text-slate-600">Link Type:</span>
                            @if(filter_var($slider->link, FILTER_VALIDATE_URL))
                                <p class="text-sm font-medium text-blue-700">🌐 External URL</p>
                            @elseif(str_starts_with($slider->link, '/'))
                                <p class="text-sm font-medium text-green-700">📁 Site Path</p>
                            @elseif(str_starts_with($slider->link, '#'))
                                <p class="text-sm font-medium text-purple-700">⚓ Page Anchor</p>
                            @else
                                <p class="text-sm font-medium text-amber-700">🔗 Relative Link</p>
                            @endif
                        </div>

                        <div>
                            <span class="text-sm text-slate-600">Link Target:</span>
                            @if(filter_var($slider->link, FILTER_VALIDATE_URL))
                                <p class="text-sm font-medium text-slate-800">{{ parse_url($slider->link, PHP_URL_HOST) ?? 'Invalid URL' }}</p>
                            @else
                                <p class="text-sm font-medium text-slate-800">{{ $slider->link }}</p>
                            @endif
                        </div>

                        <div>
                            <span class="text-sm text-slate-600">Status:</span>
                            <div class="flex items-center mt-1">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-sm font-medium text-green-700">✅ Active</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Card -->
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border border-blue-200 p-6">
                    <h3 class="text-lg font-semibold text-blue-800 mb-4">🧭 Navigation</h3>
                    <div class="space-y-3">
                        <button wire:click="backToList"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-white hover:bg-blue-50 text-blue-700 font-medium rounded-lg transition-colors border border-blue-200">
                            ← Back to Sliders List
                        </button>
                        
                        <div class="text-xs text-blue-600 text-center">
                            <p>You can also navigate using your browser's back button</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($confirmingSliderDeletion)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" wire:key="delete-modal-{{ $slider->id }}">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 shadow-xl">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <span class="text-xl">⚠️</span>
                </div>
                <h3 class="ml-3 text-lg font-medium text-gray-900">Delete Slider</h3>
            </div>

            <div class="mb-6">
                <p class="text-sm text-gray-600 mb-3">
                    Are you sure you want to delete the slider <strong class="text-gray-900">"{{ $slider->heading }}"</strong>?
                </p>
                <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                    <p class="text-sm text-red-800">
                        <strong>⚠️ Warning:</strong> This action cannot be undone and will permanently remove this slider and its associated image.
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
                    <span wire:loading.remove wire:target="delete">🗑️ Delete Slider</span>
                    <span wire:loading wire:target="delete" class="flex items-center">
                        ⏳ Deleting...
                    </span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            showToast('📋 Link copied to clipboard!', 'success');
        }).catch(() => {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            showToast('📋 Link copied to clipboard!', 'success');
        });
    }

    function downloadImage(url, filename) {
        const link = document.createElement('a');
        link.href = url;
        link.download = filename.replace(/[^a-z0-9]/gi, '_').toLowerCase() + '.jpg';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        showToast('💾 Image download started!', 'success');
    }

    function showToast(message, type = 'success') {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 px-4 py-2 rounded-lg text-white font-medium transition-all duration-300 ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        toast.textContent = message;
        
        // Add to page
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => toast.style.opacity = '1', 100);
        
        // Remove after 3 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => {
                if (document.body.contains(toast)) {
                    document.body.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && @json($confirmingSliderDeletion)) {
            @this.call('cancelDelete');
        }
    });
</script>