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
                            <h1 class="text-3xl font-bold text-slate-800">Edit Page</h1>
                        </div>
                        <p class="text-slate-600">Update page content and media for "{{ $page->title }}"</p>
                    </div>

                    <div class="flex items-center gap-3">
                        @if ($this->hasChanges)
                            <div class="flex items-center text-amber-600 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                Unsaved changes
                            </div>
                        @endif

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

        <!-- Debug Information (Remove after fixing) -->
        @if (app()->environment('local'))
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-sm">
                <strong>Debug Info:</strong><br>
                Page ID: {{ $page->id ?? 'null' }}<br>
                Title: "{{ $title }}"<br>
                Slug: "{{ $slug }}"<br>
                Content Length: {{ strlen($content) }}<br>
                User ID: {{ $page->user_id ?? 'null' }}<br>
                Existing Featured: {{ $existingFeaturedImage ? 'Yes' : 'No' }}<br>
                Existing Gallery Count: {{ count($existingGalleryImages) }}
            </div>
        @endif

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
                    <!-- Basic Information Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-800 mb-6">Page Details</h2>

                        <!-- Title Input -->
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-slate-700 mb-2">
                                Page Title <span class="text-red-500">*</span>
                            </label>
                            <input wire:model.live.debounce.300ms="title" type="text" id="title"
                                placeholder="Enter page title"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('title') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('title')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Slug Input -->
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-2">
                                <label for="slug" class="block text-sm font-medium text-slate-700">
                                    Page Slug <span class="text-red-500">*</span>
                                </label>
                                <button type="button" wire:click="toggleAutoSlug"
                                    class="text-sm {{ $auto_slug ? 'text-blue-600' : 'text-slate-500' }} hover:text-blue-700 transition-colors">
                                    {{ $auto_slug ? '🔗 Auto-generate' : '✏️ Manual edit' }}
                                </button>
                            </div>
                            <div class="relative">
                                <input wire:model.live.debounce.300ms="slug" type="text" id="slug"
                                    placeholder="page-slug" {{ $auto_slug ? 'readonly' : '' }}
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $auto_slug ? 'bg-slate-50' : '' }} @error('slug') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                @if ($slug)
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <span class="text-xs text-slate-500 bg-slate-100 px-2 py-1 rounded">
                                            /{{ $slug }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            @error('slug')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-1 text-xs text-slate-500">
                                The slug will be used in the page URL. Only lowercase letters, numbers, and hyphens are
                                allowed.
                            </p>
                        </div>
                    </div>

                    <!-- Content Editor Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-slate-800">Page Content</h2>

                            @if (!empty($availableGalleryImages))
                                <button type="button" wire:click="openGallerySelector"
                                    class="inline-flex items-center px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Insert Gallery Image
                                </button>
                            @endif
                        </div>

                        <!-- WYSIWYG Editor -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Content <span class="text-slate-500">(Optional)</span>
                            </label>

                            <!-- TinyMCE Editor Container -->
                            <div wire:ignore>
                                <textarea id="tinymce-editor" class="w-full h-96" placeholder="Start writing your page content...">{{ $content }}</textarea>
                            </div>

                            <!-- Hidden input for Livewire binding -->
                            <input type="hidden" wire:model="content">

                            <div class="mt-2 flex items-center justify-between text-xs text-slate-500">
                                <span>Use the toolbar above to format your content</span>
                                <span>{{ strlen($content) }} characters</span>
                            </div>
                        </div>
                    </div>

                    <!-- Featured Image Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-800 mb-6">Featured Image</h2>

                        <!-- Existing Featured Image -->
                        @if ($existingFeaturedImage && !$shouldRemoveFeaturedImage && !$featuredImage)
                            <div class="mb-4">
                                <div class="relative inline-block">
                                    <img src="{{ $existingFeaturedImage->getUrl('featured_medium') }}"
                                        alt="{{ $page->title }}"
                                        class="w-full max-w-md h-48 object-cover rounded-lg border border-slate-200">
                                    <button wire:click="removeFeaturedImage" type="button"
                                        class="absolute top-2 right-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-full p-1 transition-colors"
                                        title="Remove featured image">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-xs text-slate-500 mt-2">Current featured image</p>
                            </div>
                        @endif

                        <!-- New Featured Image Preview -->
                        @if ($this->featuredImagePreview)
                            <div class="mb-4">
                                <div class="relative inline-block">
                                    <img src="{{ $this->featuredImagePreview }}" alt="New Featured Image Preview"
                                        class="w-full max-w-md h-48 object-cover rounded-lg border border-slate-200">
                                    <button wire:click="keepExistingFeaturedImage" type="button"
                                        class="absolute top-2 right-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-full p-1 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-xs text-green-600 mt-2">New featured image (will replace current)</p>
                            </div>
                        @endif

                        <!-- Removal Notice -->
                        @if ($shouldRemoveFeaturedImage && !$featuredImage)
                            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                    <span class="text-red-800 text-sm">Featured image will be removed when you
                                        save</span>
                                    <button wire:click="keepExistingFeaturedImage" type="button"
                                        class="ml-auto text-red-600 hover:text-red-800 text-sm underline">
                                        Undo
                                    </button>
                                </div>
                            </div>
                        @endif

                        <!-- Featured Image Upload -->
                        @if (!$this->featuredImagePreview && !($existingFeaturedImage && !$shouldRemoveFeaturedImage))
                            <div
                                class="border-2 border-dashed border-slate-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors @error('featuredImage') border-red-300 @enderror">
                                <input wire:model="featuredImage" type="file" accept="image/*" class="hidden"
                                    id="featured-image-upload">

                                <label for="featured-image-upload" class="cursor-pointer">
                                    <div class="space-y-2">
                                        <svg class="w-12 h-12 text-slate-400 mx-auto" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <div>
                                            <p class="text-slate-600 font-medium">Click to upload featured image</p>
                                            <p class="text-slate-500 text-sm">PNG, JPG, WebP up to 10MB</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        @endif

                        @error('featuredImage')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Gallery Images Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-800 mb-6">Gallery Images</h2>

                        <!-- Simple Copy Interface - Add below existing gallery -->
                        @if (!empty($this->availableGalleryImages))
                            <div class="mb-6 p-4 bg-blue-50 border-2 border-blue-300 rounded-lg">
                                <h4 class="text-lg font-bold text-blue-800 mb-4">📋 Quick Copy Image URLs</h4>
                                <p class="text-sm text-blue-700 mb-4">Click any button below to copy the image URL,
                                    then paste it into your content editor.</p>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach ($this->availableGalleryImages as $index => $image)
                                        <div class="bg-white p-4 rounded-lg border border-blue-200 shadow-sm">
                                            <div class="flex items-center gap-3 mb-3">
                                                <img src="{{ $image['urls']['thumb'] }}" alt="{{ $image['name'] }}"
                                                    class="w-12 h-12 object-cover rounded border">
                                                <div class="flex-1 min-w-0">
                                                    <h6 class="font-medium text-gray-900 truncate">
                                                        {{ $image['name'] }}</h6>
                                                    <p class="text-xs text-gray-500">Image {{ $index + 1 }}</p>
                                                </div>
                                            </div>

                                            <!-- Size selection and copy buttons -->
                                            <div class="space-y-2">
                                                <select id="size-select-{{ $index }}"
                                                    class="w-full text-sm border border-gray-300 rounded px-2 py-1">
                                                    <option value="{{ $image['urls']['thumb'] }}">Thumbnail</option>
                                                    <option value="{{ $image['urls']['medium'] }}" selected>Medium
                                                        (Recommended)</option>
                                                    <option value="{{ $image['urls']['large'] }}">Large</option>
                                                    <option value="{{ $image['urls']['original'] }}">Original</option>
                                                </select>

                                                <div class="grid grid-cols-2 gap-2">
                                                    <button onclick="copyImageUrlSimple({{ $index }}, 'url')"
                                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2 px-3 rounded transition-colors">
                                                        📋 Copy URL
                                                    </button>
                                                    <button
                                                        onclick="copyImageUrlSimple({{ $index }}, 'html', '{{ $image['name'] }}')"
                                                        class="w-full bg-green-600 hover:bg-green-700 text-white text-xs font-bold py-2 px-3 rounded transition-colors">
                                                        🏷️ Copy HTML
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- DEBUG: Test if file is updated -->
                        <div class="mb-4 p-2 bg-yellow-100 border border-yellow-300 rounded text-sm">
                            <strong>DEBUG:</strong> File updated at {{ date('Y-m-d H:i:s') }} - If you see this, the
                            blade file is being read.<br>
                            <strong>Gallery Images Count:</strong> {{ count($existingGalleryImages) }}<br>
                            <strong>Gallery Images Empty:</strong>
                            {{ empty($existingGalleryImages) ? 'Yes' : 'No' }}<br>
                            <strong>Available Gallery Images Count:</strong> {{ count($this->availableGalleryImages) }}
                        </div>

                        <!-- Force Show New Layout for Testing -->
                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded">
                            <h4 class="text-sm font-medium text-slate-700 mb-3">🧪 TEST: New Layout (Forced Display)
                            </h4>
                            @if (!empty($existingGalleryImages))
                                @php $testImage = $existingGalleryImages[0] ?? null; @endphp
                                @if ($testImage)
                                    <div class="border border-slate-200 rounded-lg p-4 bg-white">
                                        <div class="flex gap-4">
                                            <!-- Image Thumbnail -->
                                            <div class="flex-shrink-0">
                                                <img src="{{ $testImage['urls']['thumb'] }}"
                                                    alt="{{ $testImage['name'] }}"
                                                    class="w-20 h-20 object-cover rounded border">
                                            </div>

                                            <!-- Image Details and Actions -->
                                            <div class="flex-1 min-w-0">
                                                <h5 class="text-sm font-medium text-slate-900 truncate mb-2">
                                                    {{ $testImage['name'] }}</h5>

                                                <!-- Copy Buttons -->
                                                <div class="flex gap-2 mb-2">
                                                    <button
                                                        onclick="copyToClipboard('{{ $testImage['urls']['medium'] }}', 'url')"
                                                        type="button"
                                                        class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-1 rounded transition-colors">
                                                        📋 Copy URL
                                                    </button>
                                                    <button
                                                        onclick="copyToClipboard('{{ $testImage['urls']['medium'] }}', 'html', '{{ $testImage['name'] }}')"
                                                        type="button"
                                                        class="bg-green-500 hover:bg-green-600 text-white text-sm px-3 py-1 rounded transition-colors">
                                                        🏷️ Copy HTML
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-red-600">No test image available</p>
                                @endif
                            @else
                                <p class="text-red-600">$existingGalleryImages is empty</p>
                            @endif
                        </div>

                        <!-- Existing Gallery Images -->
                        @if (!empty($existingGalleryImages))
                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-slate-700 mb-3">Current Gallery Images</h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                    @foreach ($existingGalleryImages as $image)
                                        <div class="relative group border border-slate-200 rounded-lg overflow-hidden"
                                            wire:key="existing-gallery-{{ $image['id'] }}">
                                            <img src="{{ $image['urls']['thumb'] }}" alt="{{ $image['name'] }}"
                                                class="w-full h-32 object-cover">

                                            <!-- Quick action buttons below image -->
                                            <div class="p-2 bg-white border-t border-slate-200">
                                                <div class="flex gap-1">
                                                    <button
                                                        wire:click="openGallerySelector; selectGalleryImageForContent({{ json_encode($image) }})"
                                                        type="button"
                                                        class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs px-2 py-1 rounded transition-colors"
                                                        title="Insert into content">
                                                        📝 Insert
                                                    </button>
                                                    <button
                                                        wire:click="removeExistingGalleryImage({{ $image['id'] }})"
                                                        type="button"
                                                        class="bg-red-100 hover:bg-red-200 text-red-700 text-xs px-2 py-1 rounded transition-colors"
                                                        title="Remove image">
                                                        🗑️
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Removed Gallery Images Notice -->
                        @if (!empty($removedGalleryImages))
                            <div class="mb-6 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                    <span class="text-red-800 text-sm">{{ count($removedGalleryImages) }} gallery
                                        image(s) will be removed when you save</span>
                                </div>
                            </div>
                        @endif

                        <!-- New Gallery Images Previews -->
                        @if (!empty($this->galleryImagePreviews))
                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-slate-700 mb-3">New Gallery Images</h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                    @foreach ($this->galleryImagePreviews as $index => $preview)
                                        @if ($preview)
                                            <div class="relative group" wire:key="new-gallery-{{ $index }}">
                                                <img src="{{ $preview }}"
                                                    alt="Gallery Image {{ $index + 1 }}"
                                                    class="w-full h-32 object-cover rounded-lg border border-slate-200">
                                                <button wire:click="removeGalleryImage({{ $index }})"
                                                    type="button"
                                                    class="absolute top-1 right-1 bg-red-100 hover:bg-red-200 text-red-600 rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                                <div
                                                    class="absolute bottom-1 left-1 bg-green-500 text-white text-xs px-1 rounded">
                                                    New</div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Gallery Images Upload -->
                        <div
                            class="border-2 border-dashed border-slate-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors @error('galleryImages.*') border-red-300 @enderror">
                            <input wire:model="galleryImages" type="file" accept="image/*" multiple
                                class="hidden" id="gallery-images-upload">

                            <label for="gallery-images-upload" class="cursor-pointer">
                                <div class="space-y-2">
                                    <svg class="w-12 h-12 text-slate-400 mx-auto" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <div>
                                        <p class="text-slate-600 font-medium">Click to add more gallery images</p>
                                        <p class="text-slate-500 text-sm">Select multiple images • PNG, JPG, WebP up to
                                            10MB each</p>
                                    </div>
                                </div>
                            </label>
                        </div>

                        @error('galleryImages.*')
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

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Progress Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Edit Progress</h3>

                        <div class="space-y-4">
                            <!-- Title Status -->
                            <div class="flex items-center">
                                @if (trim($title))
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <div class="w-5 h-5 bg-slate-300 rounded-full mr-3"></div>
                                @endif
                                <span class="text-sm {{ trim($title) ? 'text-green-700' : 'text-slate-600' }}">
                                    Page title
                                </span>
                            </div>

                            <!-- Slug Status -->
                            <div class="flex items-center">
                                @if (trim($slug) && $slug === \Str::slug($slug))
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <div class="w-5 h-5 bg-slate-300 rounded-full mr-3"></div>
                                @endif
                                <span
                                    class="text-sm {{ trim($slug) && $slug === \Str::slug($slug) ? 'text-green-700' : 'text-slate-600' }}">
                                    Valid slug
                                </span>
                            </div>

                            <!-- Content Status -->
                            <div class="flex items-center">
                                @if (trim($content))
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <div class="w-5 h-5 bg-amber-400 rounded-full mr-3"></div>
                                @endif
                                <span class="text-sm {{ trim($content) ? 'text-green-700' : 'text-amber-600' }}">
                                    Page content (optional)
                                </span>
                            </div>

                            <!-- Images Status -->
                            <div class="flex items-center">
                                @if ($this->totalImagesCount > 0)
                                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <div class="w-5 h-5 bg-amber-400 rounded-full mr-3"></div>
                                @endif
                                <span
                                    class="text-sm {{ $this->totalImagesCount > 0 ? 'text-green-700' : 'text-amber-600' }}">
                                    Images ({{ $this->totalImagesCount }}) (optional)
                                </span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mt-4">
                            <div class="flex justify-between text-xs text-slate-600 mb-1">
                                <span>Completion</span>
                                <span>{{ $this->progress }}%</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-amber-500 to-orange-600 h-2 rounded-full transition-all duration-300"
                                    style="width: {{ $this->progress }}%"></div>
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
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Update Page
                            </span>
                            <span wire:loading wire:target="update" class="flex items-center">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                                Updating Page...
                            </span>
                        </button>

                        @if (!$this->canUpdate)
                            <p class="mt-2 text-xs text-slate-500 text-center">
                                Please complete the required fields (title and slug)
                            </p>
                        @endif

                        @if ($this->hasChanges)
                            <button wire:click="resetChanges" type="button"
                                class="w-full mt-3 inline-flex justify-center items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Reset Changes
                            </button>
                        @endif
                    </div>

                    <!-- Page Info Card -->
                    <div class="bg-gradient-to-r from-slate-50 to-blue-50 rounded-lg border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">📄 Page Info</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-600">Created:</span>
                                <span
                                    class="text-slate-800 font-medium">{{ $page->created_at ? $page->created_at->format('M d, Y') : 'Unknown' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Last Updated:</span>
                                <span
                                    class="text-slate-800 font-medium">{{ $page->updated_at ? $page->updated_at->format('M d, Y') : 'Never' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Author:</span>
                                <span
                                    class="text-slate-800 font-medium">{{ $page->user ? $page->user->name : 'Unknown User' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Live URL:</span>
                                <a href="{{ url('/' . $page->slug) }}" target="_blank"
                                    class="text-blue-600 hover:text-blue-800 font-medium">
                                    /{{ $page->slug }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Gallery Image Selector Modal -->
    @if ($showGallerySelector)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
            wire:key="gallery-selector-modal">
            <div class="bg-white rounded-lg max-w-4xl w-full mx-4 shadow-xl max-h-[80vh] overflow-hidden">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b border-slate-200">
                    <h3 class="text-xl font-semibold text-slate-800">Select Gallery Image</h3>
                    <button wire:click="closeGallerySelector" type="button"
                        class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 overflow-y-auto max-h-[60vh]">
                    @if (!empty($availableGalleryImages))
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach ($availableGalleryImages as $image)
                                <div class="relative group cursor-pointer border-2 border-transparent hover:border-blue-400 rounded-lg transition-all"
                                    wire:click="selectGalleryImageForContent({{ json_encode($image) }})"
                                    wire:key="gallery-selector-{{ $image['id'] }}">
                                    <img src="{{ $image['urls']['medium'] }}" alt="{{ $image['name'] }}"
                                        class="w-full h-32 object-cover rounded-lg">

                                    <!-- Selection overlay -->
                                    <div
                                        class="absolute inset-0 bg-blue-600 bg-opacity-0 group-hover:bg-opacity-20 rounded-lg transition-all flex items-center justify-center">
                                        <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                            <div class="bg-white rounded-full p-2 shadow-lg">
                                                <svg class="w-6 h-6 text-blue-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Image info -->
                                    <div
                                        class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent rounded-b-lg p-2">
                                        <p class="text-white text-xs truncate">{{ $image['name'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Selected Image Preview -->
                        @if ($selectedGalleryImageForContent)
                            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <h4 class="text-sm font-medium text-blue-800 mb-3">Selected Image</h4>
                                <div class="flex items-start space-x-4">
                                    <img src="{{ $selectedGalleryImageForContent['urls']['thumb'] }}"
                                        alt="{{ $selectedGalleryImageForContent['name'] }}"
                                        class="w-20 h-20 object-cover rounded-lg border border-blue-200">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-slate-800">
                                            {{ $selectedGalleryImageForContent['name'] }}</p>
                                        <p class="text-xs text-slate-600 mt-1">
                                            {{ $selectedGalleryImageForContent['file_name'] }}</p>

                                        <!-- Size options -->
                                        <div class="mt-3 space-y-2">
                                            <p class="text-xs text-slate-600">Choose size to insert:</p>
                                            <div class="flex flex-wrap gap-2">
                                                <button wire:click="insertGalleryImageToContent('thumb')"
                                                    type="button"
                                                    class="px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs rounded-full transition-colors">
                                                    Thumbnail
                                                </button>
                                                <button wire:click="insertGalleryImageToContent('medium')"
                                                    type="button"
                                                    class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-full transition-colors">
                                                    Medium (recommended)
                                                </button>
                                                <button wire:click="insertGalleryImageToContent('large')"
                                                    type="button"
                                                    class="px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs rounded-full transition-colors">
                                                    Large
                                                </button>
                                                <button wire:click="insertGalleryImageToContent('original')"
                                                    type="button"
                                                    class="px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs rounded-full transition-colors">
                                                    Original
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <h3 class="text-lg font-medium text-slate-900 mb-2">No gallery images</h3>
                            <p class="text-slate-500">Add some gallery images first to use them in your content.</p>
                        </div>
                    @endif
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end p-6 border-t border-slate-200">
                    <button wire:click="closeGallerySelector" type="button"
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Loading Overlay -->
    <div wire:loading.flex wire:target="update,featuredImage,galleryImages"
        class="fixed inset-0 z-50 bg-white bg-opacity-75 flex items-center justify-center">
        <div class="flex flex-col items-center space-y-4">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="text-sm text-slate-600 font-medium" wire:loading.remove
                wire:target="featuredImage,galleryImages">Updating page...</p>
            <p class="text-sm text-slate-600 font-medium" wire:loading wire:target="featuredImage,galleryImages">
                Uploading images...</p>
        </div>
    </div>
</div>

<!-- TinyMCE WYSIWYG Editor Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.4.1/tinymce.min.js"></script>

<script>
    // Simple copy function for the new quick copy interface
    function copyImageUrlSimple(imageIndex, type, imageName = '') {
        const selectElement = document.getElementById('size-select-' + imageIndex);
        const imageUrl = selectElement.value;

        let textToCopy = '';
        let message = '';

        if (type === 'url') {
            textToCopy = imageUrl;
            message = '✅ Image URL copied!';
        } else if (type === 'html') {
            textToCopy = `<img src="${imageUrl}" alt="${imageName}" style="max-width: 100%; height: auto;">`;
            message = '✅ Image HTML copied!';
        }

        // Copy to clipboard
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(textToCopy).then(function() {
                alert(message + '\n\n' + textToCopy);
            }).catch(function(err) {
                alert('Copy failed! Manual copy:\n\n' + textToCopy);
            });
        } else {
            // Fallback
            const textarea = document.createElement('textarea');
            textarea.value = textToCopy;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            alert(message + '\n\n' + textToCopy);
        }
    }

    // Add copy function first for immediate testing
    function copyToClipboard(url, type, imageName = '') {
        let textToCopy = '';
        let successMessage = '';

        if (type === 'url') {
            textToCopy = url;
            successMessage = '✅ Image URL copied to clipboard!';
        } else if (type === 'html') {
            textToCopy = `<img src="${url}" alt="${imageName}" style="max-width: 100%; height: auto;">`;
            successMessage = '✅ Image HTML copied to clipboard!';
        }

        // Simple copy with alert feedback
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(textToCopy).then(function() {
                alert(successMessage + '\n\nCopied: ' + textToCopy.substring(0, 100) + (textToCopy.length >
                    100 ? '...' : ''));
            }).catch(function(err) {
                console.error('Clipboard failed:', err);
                alert('Copy failed! Please copy manually:\n\n' + textToCopy);
            });
        } else {
            // Fallback
            alert('Copy failed! Please copy manually:\n\n' + textToCopy);
        }
    }

    function updateImageData(selectElement) {
        const selectedSize = selectElement.value;
        const imageUrl = selectElement.getAttribute('data-' + selectedSize);
        const imageName = selectElement.getAttribute('data-name');

        // Update the copy buttons in the same container
        const container = selectElement.closest('.border');
        const copyUrlBtn = container.querySelector('button[onclick*="copyToClipboard"][onclick*="url"]');
        const copyHtmlBtn = container.querySelector('button[onclick*="copyToClipboard"][onclick*="html"]');
        const currentUrlSpan = container.querySelector('.current-url');

        if (copyUrlBtn) {
            copyUrlBtn.setAttribute('onclick', `copyToClipboard('${imageUrl}', 'url')`);
            copyUrlBtn.setAttribute('data-url', imageUrl);
        }
        if (copyHtmlBtn) {
            copyHtmlBtn.setAttribute('onclick', `copyToClipboard('${imageUrl}', 'html', '${imageName}')`);
            copyHtmlBtn.setAttribute('data-url', imageUrl);
        }
        if (currentUrlSpan) {
            currentUrlSpan.textContent = imageUrl;
        }
    }

    document.addEventListener('livewire:initialized', () => {
        console.log('Livewire initialized for EditPage component');

        // Initialize TinyMCE editor
        tinymce.init({
            selector: '#tinymce-editor',
            height: 400,
            menubar: false,
            license_key: 'gpl',
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic backcolor | link unlink | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | code fullscreen | help',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',

            // Link configuration
            link_context_toolbar: true,
            link_assume_external_targets: true,
            link_title: false,
            target_list: [{
                    title: 'Same window',
                    value: ''
                },
                {
                    title: 'New window',
                    value: '_blank'
                }
            ],

            setup: function(editor) {
                editor.on('init', function() {
                    console.log('TinyMCE editor initialized');
                });

                editor.on('change keyup', function() {
                    const content = editor.getContent();
                    // Update hidden input for Livewire
                    const hiddenInput = document.querySelector(
                        'input[wire\\:model="content"]');
                    if (hiddenInput) {
                        hiddenInput.value = content;
                        hiddenInput.dispatchEvent(new Event('input', {
                            bubbles: true
                        }));
                    }
                });
            }
        });

        // Listen for reset editor event
        Livewire.on('resetEditor', (content) => {
            if (tinymce.get('tinymce-editor')) {
                tinymce.get('tinymce-editor').setContent(content || '');
            }
        });

        // Listen for insert image event
        Livewire.on('insertImageIntoEditor', (imageHtml) => {
            if (tinymce.get('tinymce-editor')) {
                tinymce.get('tinymce-editor').insertContent(imageHtml);
                console.log('Image inserted into editor:', imageHtml);
            }
        });
    });

    // Cleanup TinyMCE when component is destroyed
    document.addEventListener('livewire:navigating', () => {
        if (tinymce.get('tinymce-editor')) {
            tinymce.remove('#tinymce-editor');
        }
    });

    // Close gallery selector with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && @json($showGallerySelector)) {
            @this.call('closeGallerySelector');
        }
    });

    // Gallery image copy functions - SIMPLIFIED
    function copyToClipboard(url, type, imageName = '') {
        let textToCopy = '';
        let successMessage = '';

        if (type === 'url') {
            textToCopy = url;
            successMessage = 'Image URL copied to clipboard!';
        } else if (type === 'html') {
            textToCopy = `<img src="${url}" alt="${imageName}" style="max-width: 100%; height: auto;">`;
            successMessage = 'Image HTML copied to clipboard!';
        }

        // Use modern clipboard API
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(textToCopy).then(function() {
                showNotification(successMessage, textToCopy);
                console.log('Copied to clipboard:', textToCopy);
            }).catch(function(err) {
                console.error('Clipboard failed:', err);
                fallbackCopy(textToCopy, successMessage);
            });
        } else {
            // Fallback for older browsers
            fallbackCopy(textToCopy, successMessage);
        }
    }

    function updateImageData(selectElement) {
        const selectedSize = selectElement.value;
        const imageUrl = selectElement.getAttribute('data-' + selectedSize);
        const imageName = selectElement.getAttribute('data-name');

        // Update the copy buttons in the same container
        const container = selectElement.closest('.border');
        const copyUrlBtn = container.querySelector('button[onclick*="copyToClipboard"][onclick*="url"]');
        const copyHtmlBtn = container.querySelector('button[onclick*="copyToClipboard"][onclick*="html"]');
        const currentUrlSpan = container.querySelector('.current-url');

        if (copyUrlBtn) {
            copyUrlBtn.setAttribute('onclick', `copyToClipboard('${imageUrl}', 'url')`);
            copyUrlBtn.setAttribute('data-url', imageUrl);
        }
        if (copyHtmlBtn) {
            copyHtmlBtn.setAttribute('onclick', `copyToClipboard('${imageUrl}', 'html', '${imageName}')`);
            copyHtmlBtn.setAttribute('data-url', imageUrl);
        }
        if (currentUrlSpan) {
            currentUrlSpan.textContent = imageUrl;
        }
    }

    function showNotification(message, content) {
        // Remove existing notification
        const existing = document.getElementById('copy-notification');
        if (existing) {
            existing.remove();
        }

        // Create new notification
        const notification = document.createElement('div');
        notification.id = 'copy-notification';
        notification.className = 'fixed top-4 right-4 z-50 max-w-sm';

        const shortContent = content.length > 50 ? content.substring(0, 50) + '...' : content;

        notification.innerHTML = `
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 shadow-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <div>
                        <p class="text-green-800 font-medium text-sm">${message}</p>
                        <p class="text-green-600 text-xs mt-1 font-mono break-all">${shortContent}</p>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(notification);

        // Auto-hide after 4 seconds
        setTimeout(() => {
            if (notification && notification.parentNode) {
                notification.remove();
            }
        }, 4000);
    }

    function fallbackCopy(text, successMessage) {
        // Create temporary textarea for copying
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.left = '-999999px';
        textarea.style.top = '-999999px';
        document.body.appendChild(textarea);

        try {
            textarea.focus();
            textarea.select();
            const success = document.execCommand('copy');

            if (success) {
                showNotification(successMessage, text);
            } else {
                throw new Error('Copy command failed');
            }
        } catch (err) {
            console.error('Fallback copy failed:', err);
            // Last resort - show manual copy dialog
            const result = prompt('Copy this text manually (Ctrl+C):', text);
            if (result !== null) {
                showNotification('Please copy manually', text);
            }
        } finally {
            document.body.removeChild(textarea);
        }
    }
</script>
