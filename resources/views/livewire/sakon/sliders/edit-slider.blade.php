<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <div
                                class="h-8 w-8 rounded-lg bg-gradient-to-r from-amber-500 to-orange-600 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <h1 class="text-3xl font-bold text-slate-800">Edit Slider</h1>
                        </div>
                        <p class="text-slate-600">Update slider: <span class="font-medium">{{ $slider->heading }}</span>
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <button wire:click="viewSlider" type="button"
                            class="inline-flex items-center px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            View
                        </button>

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

        <form wire:submit="update">
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
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('heading') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
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
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors resize-none @error('description') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">{{ $description }}</textarea>
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
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('link') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
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
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
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
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors @error('show') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
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

                    <!-- Image Management Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-800 mb-6">Slider Image</h2>

                        <!-- Current Image Display -->
                        @if ($showImagePreview && $currentImageUrl)
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-slate-700 mb-3">Current Image</label>
                                <div class="relative inline-block">
                                    <img src="{{ $currentImageUrl }}" alt="{{ $slider->heading }}"
                                        class="w-full max-w-md h-48 object-cover rounded-lg border border-slate-200 shadow-sm">
                                    <button wire:click="removeCurrentImage" type="button"
                                        class="absolute top-2 right-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-full p-2 transition-colors shadow-sm"
                                        title="Remove current image">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <!-- New Image Upload -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                {{ $showImagePreview ? 'Replace Image' : 'Upload New Image' }}
                            </label>

                            @if ($this->newImagePreview)
                                <!-- New Image Preview -->
                                <div class="mb-4">
                                    <div class="relative inline-block">
                                        <img src="{{ $this->newImagePreview }}" alt="New image preview"
                                            class="w-full max-w-md h-48 object-cover rounded-lg border border-amber-200 shadow-sm">
                                        <button wire:click="$set('sliderImage', null)" type="button"
                                            class="absolute top-2 right-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-full p-1 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="mt-2 text-sm text-amber-600 font-medium">New image ready to upload</p>
                                </div>
                            @endif

                            <!-- File Input -->
                            <div
                                class="border-2 border-dashed border-slate-300 rounded-lg p-6 text-center hover:border-amber-400 transition-colors @error('sliderImage') border-red-300 @enderror">
                                <input wire:model="sliderImage" type="file" accept="image/*" class="hidden"
                                    id="slider-image-upload-edit">

                                <label for="slider-image-upload-edit" class="cursor-pointer">
                                    <div class="space-y-2">
                                        <svg class="w-12 h-12 text-slate-400 mx-auto" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <div>
                                            <p class="text-slate-600 font-medium">Click to select new image</p>
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
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Changes Status Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Update Status</h3>

                        @if ($this->hasChanges)
                            <div class="flex items-center text-amber-600 mb-4">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                <span class="font-medium">You have unsaved changes</span>
                            </div>
                        @else
                            <div class="flex items-center text-green-600 mb-4">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="font-medium">No changes detected</span>
                            </div>
                        @endif

                        <div class="space-y-4">
                            <!-- Validation Status -->
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
                                    Valid heading
                                </span>
                            </div>

                            <!-- Link Status -->
                            <div class="flex items-center">
                                @if (trim($link) && app(\App\Livewire\Sakon\Sliders\EditSlider::class)->isValidLink(trim($link)))
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
                                    Display location set
                                </span>
                            </div>

                            <div class="flex items-center">
                                @if ($showImagePreview || $sliderImage)
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <div class="w-5 h-5 bg-amber-400 rounded-full mr-3"></div>
                                @endif
                                <span
                                    class="text-sm {{ $showImagePreview || $sliderImage ? 'text-green-700' : 'text-amber-700' }}">
                                    {{ $showImagePreview || $sliderImage ? 'Image available' : 'No image' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Slider Information Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Slider Information</h3>

                        <div class="space-y-4">
                            <div>
                                <span class="text-sm text-slate-600">Created by:</span>
                                <div class="flex items-center mt-1">
                                    <div
                                        class="h-6 w-6 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center mr-2">
                                        <span
                                            class="text-white font-medium text-xs">{{ substr($slider->user->name, 0, 1) }}</span>
                                    </div>
                                    <span class="text-sm font-medium text-slate-800">{{ $slider->user->name }}</span>
                                </div>
                            </div>

                            <div>
                                <span class="text-sm text-slate-600">Created:</span>
                                <p class="text-sm font-medium text-slate-800">
                                    {{ $slider->created_at->format('M j, Y \a\t g:i A') }}</p>
                            </div>

                            @if ($slider->created_at->ne($slider->updated_at))
                                <div>
                                    <span class="text-sm text-slate-600">Last updated:</span>
                                    <p class="text-sm font-medium text-slate-800">
                                        {{ $slider->updated_at->format('M j, Y \a\t g:i A') }}</p>
                                </div>
                            @endif

                            <div>
                                <span class="text-sm text-slate-600">Current status:</span>
                                <div class="mt-1">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium {{ $slider->show_badge_color }}">
                                        {{ $slider->show_location }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <button type="submit" wire:loading.attr="disabled" {{ !$this->canUpdate ? 'disabled' : '' }}
                            class="w-full inline-flex justify-center items-center px-4 py-3 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 disabled:from-slate-300 disabled:to-slate-400 text-white font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="update">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Update Slider
                            </span>
                            <span wire:loading wire:target="update" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Updating Slider...
                            </span>
                        </button>

                        @if (!$this->canUpdate)
                            <p class="mt-2 text-xs text-slate-500 text-center">
                                Please complete all required fields
                            </p>
                        @endif
                    </div>

                    <!-- Tips Card -->
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-lg border border-amber-200 p-6">
                        <h3 class="text-lg font-semibold text-amber-800 mb-4">✏️ Edit Tips</h3>
                        <div class="space-y-3 text-sm text-amber-700">
                            <div class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Changes are saved automatically when you click "Update Slider"</span>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Uploading a new image will replace the current one</span>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>You can remove the current image and add a new one later</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Loading Overlay -->
    <div wire:loading.flex wire:target="update,sliderImage,removeCurrentImage"
        class="fixed inset-0 z-50 bg-white bg-opacity-75 flex items-center justify-center">
        <div class="flex flex-col items-center space-y-4">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="text-sm text-slate-600 font-medium" wire:loading.remove
                wire:target="sliderImage,removeCurrentImage">Updating slider...</p>
            <p class="text-sm text-slate-600 font-medium" wire:loading wire:target="sliderImage">Processing image...
            </p>
            <p class="text-sm text-slate-600 font-medium" wire:loading wire:target="removeCurrentImage">Removing
                image...</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        // Handle drag and drop for file upload
        const dropZone = document.querySelector('[for="slider-image-upload-edit"]').parentElement;

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-amber-400', 'bg-amber-50');
        });

        dropZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-amber-400', 'bg-amber-50');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-amber-400', 'bg-amber-50');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const fileInput = document.getElementById('slider-image-upload-edit');
                fileInput.files = files;
                fileInput.dispatchEvent(new Event('change', {
                    bubbles: true
                }));
            }
        });

        // Warn user about unsaved changes
        let hasWarned = false;
        window.addEventListener('beforeunload', (e) => {
            if (@json($this->hasChanges) && !hasWarned) {
                e.preventDefault();
                e.returnValue = '';
                return '';
            }
        });

        // Clear warning when form is submitted
        Livewire.on('slider-updated', () => {
            hasWarned = true;
        });
    });
</script>
