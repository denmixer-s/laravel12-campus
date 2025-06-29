<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <div
                                class="h-8 w-8 rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <h1 class="text-3xl font-bold text-slate-800">Create New Slider</h1>
                        </div>
                        <p class="text-slate-600">Add a new slider to your website homepage or frontend</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <button wire:click="cancel" type="button"
                            class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if ($showSuccessMessage)
            <div class="mb-6">
                <div class="bg-green-50 rounded-lg border border-green-200 p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-green-800 font-medium">{{ $successMessage }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form wire:submit="create">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Slider Details Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-800 mb-6">Slider Details</h2>

                        <!-- Heading Input -->
                        <div class="mb-6">
                            <label for="heading" class="block text-sm font-medium text-slate-700 mb-2">
                                Slider Heading <span class="text-red-500">*</span>
                            </label>
                            <input wire:model.live.debounce.300ms="heading" type="text" id="heading"
                                placeholder="Enter compelling slider heading"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('heading') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('heading')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Description Textarea -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-2">
                                Description <span class="text-slate-500">(Optional)</span>
                            </label>
                            <textarea wire:model.live.debounce.300ms="description" id="description" rows="4"
                                placeholder="Enter slider description or call-to-action text"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('description') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"></textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-1 text-xs text-slate-500">{{ strlen($description) }}/1000 characters</p>
                        </div>

                        <!-- Link Input -->
                        <div class="mb-6">
                            <label for="link" class="block text-sm font-medium text-slate-700 mb-2">
                                Link <span class="text-red-500">*</span>
                            </label>
                            <input wire:model.live.debounce.300ms="link" type="text" id="link"
                                placeholder="https://example.com, /about, #section, or contact"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('link') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('link')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            @if ($this->linkPreview)
                                <div class="mt-2 flex items-center text-sm text-green-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ $this->linkPreview }}
                                </div>
                            @endif
                            <div class="mt-2 text-xs text-slate-500">
                                <p><strong>Supported formats:</strong></p>
                                <ul class="mt-1 space-y-1">
                                    <li>• <span class="font-medium">URLs:</span> https://example.com</li>
                                    <li>• <span class="font-medium">Paths:</span> /about, /contact, /products</li>
                                    <li>• <span class="font-medium">Anchors:</span> #section, #top</li>
                                    <li>• <span class="font-medium">Relative:</span> contact, about.html</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Show Location Select -->
                        <div class="mb-6">
                            <label for="show" class="block text-sm font-medium text-slate-700 mb-2">
                                Display Location <span class="text-red-500">*</span>
                            </label>
                            <select wire:model.live="show" id="show"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('show') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                <option value="">Select display location</option>
                                @foreach ($this->showOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('show')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Image Upload Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-800 mb-6">Slider Image</h2>

                        <!-- File Upload Area -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Upload Image <span class="text-red-500">*</span>
                            </label>

                            @if ($this->imagePreview)
                                <!-- Image Preview -->
                                <div class="mb-4">
                                    <div class="relative inline-block">
                                        <img src="{{ $this->imagePreview }}" alt="Preview"
                                            class="w-full max-w-md h-48 object-cover rounded-lg border border-slate-200">
                                        <button wire:click="$set('sliderImage', null)" type="button"
                                            class="absolute top-2 right-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-full p-1 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <!-- File Input -->
                            <div
                                class="border-2 border-dashed border-slate-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors @error('sliderImage') border-red-300 @enderror">
                                <input wire:model="sliderImage" type="file" accept="image/*" class="hidden"
                                    id="slider-image-upload">

                                <label for="slider-image-upload" class="cursor-pointer">
                                    <div class="space-y-2">
                                        <svg class="w-12 h-12 text-slate-400 mx-auto" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <div>
                                            <p class="text-slate-600 font-medium">Click to upload image</p>
                                            <p class="text-slate-500 text-sm">PNG, JPG, WebP up to 10MB</p>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            @error('sliderImage')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror

                            <!-- Image Guidelines -->
                            <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                <h4 class="text-sm font-medium text-blue-800 mb-2">Image Guidelines:</h4>
                                <ul class="text-sm text-blue-700 space-y-1">
                                    <li>• Recommended size: 1920x1080 pixels (16:9 aspect ratio)</li>
                                    <li>• Formats: JPEG, PNG, WebP, GIF</li>
                                    <li>• Maximum file size: 10MB</li>
                                    <li>• High-quality images work best for responsive display</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Progress Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Creation Progress</h3>

                        <div class="space-y-4">
                            <!-- Heading Status -->
                            <div class="flex items-center">
                                @if (trim($heading))
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <div class="w-5 h-5 bg-slate-300 rounded-full mr-3"></div>
                                @endif
                                <span class="text-sm {{ trim($heading) ? 'text-green-700' : 'text-slate-600' }}">
                                    Heading provided
                                </span>
                            </div>

                            <!-- Link Status -->
                            <div class="flex items-center">
                                @if (trim($link) && $this->linkPreview)
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <div class="w-5 h-5 bg-slate-300 rounded-full mr-3"></div>
                                @endif
                                <span
                                    class="text-sm {{ trim($link) && $this->linkPreview ? 'text-green-700' : 'text-slate-600' }}">
                                    Valid link
                                </span>
                            </div>

                            <!-- Show Location Status -->
                            <div class="flex items-center">
                                @if ($show)
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <div class="w-5 h-5 bg-slate-300 rounded-full mr-3"></div>
                                @endif
                                <span class="text-sm {{ $show ? 'text-green-700' : 'text-slate-600' }}">
                                    Display location selected
                                </span>
                            </div>

                            <!-- Image Status -->
                            <div class="flex items-center">
                                @if ($sliderImage)
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <div class="w-5 h-5 bg-slate-300 rounded-full mr-3"></div>
                                @endif
                                <span class="text-sm {{ $sliderImage ? 'text-green-700' : 'text-slate-600' }}">
                                    Image uploaded
                                </span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mt-4">
                            @php
                                $progress = 0;
                                if (trim($heading)) {
                                    $progress += 25;
                                }
                                if (trim($link) && $this->linkPreview) {
                                    $progress += 25;
                                }
                                if ($show) {
                                    $progress += 25;
                                }
                                if ($sliderImage) {
                                    $progress += 25;
                                }
                            @endphp
                            <div class="flex justify-between text-xs text-slate-600 mb-1">
                                <span>Completion</span>
                                <span>{{ $progress }}%</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-300"
                                    style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Slider Summary</h3>

                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-slate-600">Heading:</span>
                                <p class="font-medium text-slate-800">{{ $heading ?: 'Not specified' }}</p>
                            </div>

                            @if ($description)
                                <div>
                                    <span class="text-sm text-slate-600">Description:</span>
                                    <p class="text-sm text-slate-800 line-clamp-3">{{ $description }}</p>
                                </div>
                            @endif

                            <div>
                                <span class="text-sm text-slate-600">Link:</span>
                                <p class="text-sm text-slate-800 break-all">{{ $link ?: 'Not specified' }}</p>
                            </div>

                            <div>
                                <span class="text-sm text-slate-600">Display Location:</span>
                                <p class="font-medium text-slate-800">
                                    @if ($show)
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium {{ $show === 'home'
                                                ? 'bg-blue-100 text-blue-800'
                                                : ($show === 'frontend'
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-purple-100 text-purple-800') }}">
                                            {{ $this->showOptions[$show] ?? $show }}
                                        </span>
                                    @else
                                        Not selected
                                    @endif
                                </p>
                            </div>

                            <div>
                                <span class="text-sm text-slate-600">Image:</span>
                                <p class="font-medium text-slate-800">{{ $sliderImage ? 'Uploaded' : 'Not uploaded' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <button type="submit" wire:loading.attr="disabled" {{ !$this->canCreate ? 'disabled' : '' }}
                            class="w-full inline-flex justify-center items-center px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 disabled:from-slate-300 disabled:to-slate-400 text-white font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="create">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Create Slider
                            </span>
                            <span wire:loading wire:target="create" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Creating Slider...
                            </span>
                        </button>

                        @if (!$this->canCreate)
                            <p class="mt-2 text-xs text-slate-500 text-center">
                                Please complete all required fields
                            </p>
                        @endif

                        <button wire:click="resetForm" type="button"
                            class="w-full mt-3 inline-flex justify-center items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset Form
                        </button>
                    </div>

                    <!-- Tips Card -->
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border border-blue-200 p-6">
                        <h3 class="text-lg font-semibold text-blue-800 mb-4">💡 Pro Tips</h3>
                        <div class="space-y-3 text-sm text-blue-700">
                            <div class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Use high-contrast text on images for better readability</span>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Keep headings concise and action-oriented</span>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Test links before publishing</span>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Images will be automatically optimized for different devices</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Loading Overlay -->
    <div wire:loading.flex wire:target="create,sliderImage"
        class="fixed inset-0 z-50 bg-white bg-opacity-75 flex items-center justify-center">
        <div class="flex flex-col items-center space-y-4">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="text-sm text-slate-600 font-medium" wire:loading.remove wire:target="sliderImage">Creating
                slider...</p>
            <p class="text-sm text-slate-600 font-medium" wire:loading wire:target="sliderImage">Uploading image...
            </p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        // Handle drag and drop for file upload
        const dropZone = document.querySelector('[for="slider-image-upload"]').parentElement;

        if (dropZone) {
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('border-blue-400', 'bg-blue-50');
            });

            dropZone.addEventListener('dragleave', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-blue-400', 'bg-blue-50');
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-blue-400', 'bg-blue-50');

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    const fileInput = document.getElementById('slider-image-upload');
                    if (fileInput) {
                        fileInput.files = files;
                        fileInput.dispatchEvent(new Event('change', {
                            bubbles: true
                        }));
                    }
                }
            });
        }
    });
</script>
