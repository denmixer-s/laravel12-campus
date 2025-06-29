{{-- resources/views/livewire/backend/blog/posts-edit.blade.php --}}
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8 w-full">
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
                            <h1 class="text-3xl font-bold text-slate-800">
                                Edit Blog Post {{ $hasChanges ? '●' : '' }}
                            </h1>
                        </div>
                        <p class="text-slate-600">
                            Editing: <span class="font-medium">{{ $post->title }}</span>
                            @if ($hasChanges)
                                <span class="text-amber-600 text-sm ml-2">• Unsaved changes</span>
                            @endif
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Last Saved Indicator -->
                        @if ($lastSavedAt)
                            <div class="text-xs text-slate-500">
                                Last saved: {{ $lastSavedAt->diffForHumans() }}
                            </div>
                        @endif

                        <!-- Delete Button -->
                        <button wire:click="$dispatch('confirm-delete')" type="button"
                            class="inline-flex items-center px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete
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
                    <!-- Basic Information Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-800 mb-6">Post Details</h2>

                        <!-- Title Input -->
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-slate-700 mb-2">
                                Post Title <span class="text-red-500">*</span>
                            </label>
                            <input wire:model.live.debounce.300ms="title" type="text" id="title"
                                placeholder="Enter an engaging post title"
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
                                    Post Slug <span class="text-red-500">*</span>
                                </label>
                                <button type="button" wire:click="toggleAutoSlug"
                                    class="flex items-center gap-2 text-sm {{ $auto_slug ? 'text-blue-600 bg-blue-50' : 'text-slate-600 bg-slate-50' }} hover:bg-blue-100 transition-colors px-3 py-1 rounded-md border">
                                    @if ($auto_slug)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                        Auto-generate
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                        Manual edit
                                    @endif
                                </button>
                            </div>
                            <div class="relative">
                                <div class="flex">
                                    <span
                                        class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-slate-300 bg-slate-50 text-slate-500 text-sm">
                                        /blog/
                                    </span>
                                    <input wire:model.live.debounce.500ms="slug" type="text" id="slug"
                                        placeholder="{{ $auto_slug ? 'auto-generated-slug' : 'enter-your-slug' }}"
                                        {{ $auto_slug ? 'readonly' : '' }}
                                        class="flex-1 px-4 py-3 border border-slate-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $auto_slug ? 'bg-slate-50 text-slate-600' : 'bg-white' }} @error('slug') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                </div>
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
                            <div class="mt-2">
                                @if ($auto_slug)
                                    <p class="text-xs text-blue-600 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        🇹🇭 Thai titles will be auto-converted to English slug
                                    </p>
                                @else
                                    <p class="text-xs text-amber-600 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                        </svg>
                                        🇬🇧 Manual input: English letters, numbers, and hyphens only
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Excerpt Input -->
                        <div class="mb-6">
                            <label for="excerpt" class="block text-sm font-medium text-slate-700 mb-2">
                                Excerpt
                                <span class="text-slate-500 text-xs">(Brief description for previews)</span>
                            </label>
                            <textarea wire:model.live.debounce.500ms="excerpt" id="excerpt" rows="3"
                                placeholder="Write a compelling excerpt..."
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('excerpt') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">{{ $excerpt }}</textarea>
                            @error('excerpt')
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

                    <!-- Content Editor Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-800 mb-6">
                            Post Content <span class="text-red-500">*</span>
                        </h2>

                        <div class="mb-6">
                            <div wire:ignore>
                                <textarea id="tinymce-editor" class="w-full h-96" placeholder="Start writing your blog post content...">{{ $content }}</textarea>
                            </div>
                            <input type="hidden" wire:model="content">

                            @error('content')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror

                            <div class="mt-2 flex items-center justify-between text-xs text-slate-500">
                                <span>Use the toolbar above to format your content</span>
                                <div class="flex items-center space-x-4">
                                    <span>Words: {{ str_word_count(strip_tags($content)) }}</span>
                                    <span>Characters: {{ strlen(strip_tags($content)) }}</span>
                                    <span>Reading time: ~{{ $this->calculateReadingTime($content) }} min</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Featured Image Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-800 mb-6">Featured Image</h2>

                        <!-- Current Featured Image -->
                        @if ($currentFeaturedImage)
                            <div class="mb-4">
                                <p class="text-sm font-medium text-slate-700 mb-2">Current Featured Image:</p>
                                <div class="relative inline-block">
                                    <img src="{{ $currentFeaturedImage['url'] }}" alt="Current Featured Image"
                                        class="w-full max-w-md h-48 object-cover rounded-lg border border-slate-200">
                                    <button wire:click="removeCurrentFeaturedImage" type="button"
                                        class="absolute top-2 right-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-full p-1 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">{{ $currentFeaturedImage['name'] }}
                                    ({{ $currentFeaturedImage['size'] }})</p>
                            </div>
                        @endif

                        <!-- New Featured Image Upload -->
                        @if ($featuredImage)
                            <div class="mb-4">
                                <p class="text-sm font-medium text-slate-700 mb-2">New Featured Image:</p>
                                <div class="relative inline-block">
                                    @if (method_exists($featuredImage, 'temporaryUrl'))
                                        <img src="{{ $featuredImage->temporaryUrl() }}"
                                            alt="New Featured Image Preview"
                                            class="w-full max-w-md h-48 object-cover rounded-lg border border-green-200">
                                    @endif
                                    <button wire:click="removeFeaturedImage" type="button"
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
                                        <p class="text-slate-600 font-medium">
                                            {{ $currentFeaturedImage ? 'Replace featured image' : 'Upload featured image' }}
                                        </p>
                                        <p class="text-slate-500 text-sm">PNG, JPG, WebP up to 10MB</p>
                                    </div>
                                </div>
                            </label>
                        </div>

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

                        <!-- Current Gallery Images -->
                        @if (!empty($currentGalleryImages))
                            <div class="mb-6">
                                <p class="text-sm font-medium text-slate-700 mb-2">Current Gallery Images:</p>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                    @foreach ($currentGalleryImages as $index => $image)
                                        <div class="relative group">
                                            <img src="{{ $image['url'] }}" alt="Gallery Image {{ $index + 1 }}"
                                                class="w-full h-32 object-cover rounded-lg border border-slate-200">
                                            <button wire:click="removeCurrentGalleryImage({{ $index }})"
                                                type="button"
                                                class="absolute top-1 right-1 bg-red-100 hover:bg-red-200 text-red-600 rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- New Gallery Images -->
                        @if (!empty($galleryImages))
                            <div class="mb-6">
                                <p class="text-sm font-medium text-slate-700 mb-2">New Gallery Images:</p>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                    @foreach ($galleryImages as $index => $image)
                                        @if ($image && method_exists($image, 'temporaryUrl'))
                                            <div class="relative group">
                                                <img src="{{ $image->temporaryUrl() }}"
                                                    alt="New Gallery Image {{ $index + 1 }}"
                                                    class="w-full h-32 object-cover rounded-lg border border-green-200">
                                                <button wire:click="removeGalleryImage({{ $index }})"
                                                    type="button"
                                                    class="absolute top-1 right-1 bg-red-100 hover:bg-red-200 text-red-600 rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
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
                                        <p class="text-slate-600 font-medium">Add more gallery images</p>
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

                        <div wire:loading wire:target="galleryImages" class="mt-2">
                            <div class="flex items-center text-sm text-blue-600">
                                <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Uploading gallery images...
                            </div>
                        </div>
                    </div>

                    <!-- SEO Settings Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-800 mb-6">SEO Settings</h2>

                        <!-- Meta Title -->
                        <div class="mb-6">
                            <label for="meta-title" class="block text-sm font-medium text-slate-700 mb-2">
                                Meta Title
                                <span class="text-slate-500 text-xs">({{ strlen($meta_title) }}/60 chars)</span>
                            </label>
                            <input wire:model.live.debounce.500ms="meta_title" type="text" id="meta-title"
                                placeholder="SEO title for search engines..." maxlength="60"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('meta_title') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('meta_title')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Meta Description -->
                        <div class="mb-6">
                            <label for="meta-description" class="block text-sm font-medium text-slate-700 mb-2">
                                Meta Description
                                <span class="text-slate-500 text-xs">({{ strlen($meta_description) }}/160
                                    chars)</span>
                            </label>
                            <textarea wire:model.live.debounce.500ms="meta_description" id="meta-description" rows="3"
                                placeholder="Description for search engines..." maxlength="160"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('meta_description') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">{{ $meta_description }}</textarea>
                            @error('meta_description')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Meta Keywords -->
                        <div class="mb-6">
                            <label for="meta-keywords" class="block text-sm font-medium text-slate-700 mb-2">
                                Meta Keywords
                                <span class="text-slate-500 text-xs">(comma separated)</span>
                            </label>
                            <input wire:model.live.debounce.500ms="meta_keywords" type="text" id="meta-keywords"
                                placeholder="keyword1, keyword2, keyword3..."
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('meta_keywords') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('meta_keywords')
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
                    <!-- Publishing Options Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Publishing Options</h3>

                        <div class="items-center gap-3 mb-4">
                            {{-- Progress Indicator --}}
                            <div class="items-center gap-2">
                                <span class="text-xs text-slate-500">Completion:</span>
                                <div class="w-full bg-slate-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-amber-500 to-orange-600 h-2 rounded-full transition-all duration-300"
                                        style="width: {{ $this->progress }}%"></div>
                                </div>
                                <span class="text-xs text-slate-500">{{ $this->progress }}%</span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="status" class="block text-sm font-medium text-slate-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select wire:model.live="status" id="status"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="draft">📝 Draft</option>
                                <option value="published">🚀 Published</option>
                                <option value="scheduled">⏰ Scheduled</option>
                                <option value="archived">📦 Archived</option>
                            </select>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input wire:model.live="is_featured" type="checkbox" id="is-featured"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                                <label for="is-featured" class="ml-3 block text-sm text-slate-700">
                                    ⭐ Featured Post
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input wire:model.live="is_sticky" type="checkbox" id="is-sticky"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                                <label for="is-sticky" class="ml-3 block text-sm text-slate-700">
                                    📌 Sticky (Pin to top)
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input wire:model.live="allow_comments" type="checkbox" id="allow-comments"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                                <label for="allow-comments" class="ml-3 block text-sm text-slate-700">
                                    💬 Allow Comments
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Category Selection Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">
                            Category <span class="text-red-500">*</span>
                        </h3>

                        <select wire:model.live="blog_category_id"
                            class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Select category...</option>
                            @if ($availableCategories)
                                @foreach ($availableCategories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->parent_id ? '— ' : '' }}{{ $category->name }}
                                        ({{ $category->posts_count }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('blog_category_id')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Tags Management Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Tags</h3>

                        <div class="mb-4">
                            <div class="flex">
                                <input wire:model="newTag" wire:keydown.enter="addTag" type="text"
                                    placeholder="Add new tag..."
                                    class="flex-1 px-4 py-2 border border-slate-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <button wire:click="addTag" type="button"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-r-lg transition-colors">
                                    Add
                                </button>
                            </div>
                        </div>

                        @if (!empty($selectedTags) && $this->selectedTagsData->isNotEmpty())
                            <div class="mb-4">
                                <p class="text-sm font-medium text-slate-700 mb-2">Selected Tags:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($this->selectedTagsData as $tag)
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium text-white"
                                            style="background-color: {{ $tag->color }}">
                                            {{ $tag->name }}
                                            <button wire:click="removeTag({{ $tag->id }})" type="button"
                                                class="ml-2 inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-black hover:bg-opacity-20 transition-colors">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <button type="submit" wire:loading.attr="disabled" {{ !$this->canUpdate ? 'disabled' : '' }}
                            class="w-full inline-flex justify-center items-center px-4 py-3 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 disabled:from-slate-300 disabled:to-slate-400 text-white font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="update">
                                @if ($status === 'draft')
                                    📝 Update Draft
                                @elseif($status === 'published')
                                    🚀 Update & Publish
                                @elseif($status === 'scheduled')
                                    ⏰ Update & Schedule
                                @else
                                    💾 Update Post
                                @endif
                            </span>
                            <span wire:loading wire:target="update" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Updating...
                            </span>
                        </button>

                        @if (!$this->canUpdate)
                            <p class="mt-2 text-xs text-slate-500 text-center">
                                Please complete the required fields
                            </p>
                        @endif

                        <div class="mt-3 space-y-2">
                            <button wire:click="saveDraft" type="button" {{ $status === 'draft' ? 'disabled' : '' }}
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 disabled:bg-slate-50 text-slate-700 disabled:text-slate-400 font-medium rounded-lg transition-colors duration-200 disabled:cursor-not-allowed">
                                💾 Save as Draft
                            </button>

                            <button wire:click="resetForm" type="button"
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors duration-200">
                                🔄 Reset Changes
                            </button>
                        </div>
                    </div>

                    <!-- Post Info Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Post Information</h3>

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Created:</span>
                                <span class="text-slate-700">{{ $post->created_at->format('M j, Y g:i A') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Last Updated:</span>
                                <span class="text-slate-700">{{ $post->updated_at->format('M j, Y g:i A') }}</span>
                            </div>
                            @if ($post->published_at)
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Published:</span>
                                    <span
                                        class="text-slate-700">{{ $post->published_at->format('M j, Y g:i A') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-slate-500">Author:</span>
                                <span class="text-slate-700">{{ $post->user->name ?? 'Unknown' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Post ID:</span>
                                <span class="text-slate-700">#{{ $post->id }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Loading Overlay -->
    <div wire:loading.flex wire:target="update,featuredImage,galleryImages"
        class="fixed inset-0 z-50 bg-white bg-opacity-75 flex items-center justify-center">
        <div class="flex flex-col items-center space-y-4">
            <svg class="animate-spin h-8 w-8 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                    stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <p class="text-sm text-slate-600 font-medium" wire:loading.remove
                wire:target="featuredImage,galleryImages">Updating blog post...</p>
            <p class="text-sm text-slate-600 font-medium" wire:loading wire:target="featuredImage,galleryImages">
                Uploading images...</p>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <!-- Delete Confirmation Modal -->
    <div x-data="{ showDeleteModal: false }" @confirm-delete.window="showDeleteModal = true" x-show="showDeleteModal" x-cloak
        class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">

        <!-- Background Overlay -->
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" @click="showDeleteModal = false"
                class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Center alignment helper -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Panel -->
            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" @click.stop
                class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">

                <!-- Modal Content -->
                <div class="sm:flex sm:items-start">
                    <!-- Warning Icon -->
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>

                    <!-- Modal Text -->
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Delete Blog Post
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Are you sure you want to delete "<strong>{{ $post->title }}</strong>"?
                                This action cannot be undone and will permanently remove this blog post.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <!-- Delete Button -->
                    <button wire:click="deletePost" @click="showDeleteModal = false" type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        <span wire:loading.remove wire:target="deletePost">Delete Post</span>
                        <span wire:loading wire:target="deletePost" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Deleting...
                        </span>
                    </button>

                    <!-- Cancel Button -->
                    <button @click="showDeleteModal = false" type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors duration-200">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Scripts -->
    <script>
        // Enhanced Alpine.js loading with better error handling
        document.addEventListener('DOMContentLoaded', function() {
            // Check if Alpine is already loaded
            if (typeof window.Alpine === 'undefined') {
                const script = document.createElement('script');
                script.defer = true;
                script.src = 'https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js';
                script.onload = function() {
                    console.log('Alpine.js loaded successfully');
                };
                script.onerror = function() {
                    console.error('Failed to load Alpine.js');
                };
                document.head.appendChild(script);
            }
        });

        document.addEventListener('livewire:initialized', () => {
            console.log('Livewire initialized');

            // Listen for delete confirmation events
            Livewire.on('delete-confirmed', () => {
                console.log('Delete confirmed');
            });

            // Listen for delete success events
            Livewire.on('post-deleted', () => {
                console.log('Post deleted successfully');
                // You can add redirect logic here if needed
            });
            // Load TinyMCE
            // Load TinyMCE
            const script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.4.1/tinymce.min.js';
            script.onload = function() {
                // ปิด console warnings สำหรับ deprecated APIs
                const originalWarn = console.warn;
                console.warn = function(...args) {
                    if (args.some(arg =>
                            typeof arg === 'string' &&
                            (arg.includes('mozInputSource') ||
                                arg.includes('zoom') ||
                                arg.includes('non standard property'))
                        )) {
                        return; // ไม่แสดง warning เหล่านี้
                    }
                    originalWarn.apply(console, args);
                };

                tinymce.init({
                    selector: '#tinymce-editor',
                    height: 400,
                    menubar: false,
                    license_key: 'gpl',
                    readonly: false,

                    // แก้ไข zoom และ deprecated warnings
                    resize: false,
                    elementpath: false,
                    browser_spellcheck: true,
                    contextmenu: false,

                    plugins: [
                        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                        'insertdatetime', 'media', 'table', 'help', 'wordcount'
                    ],

                    toolbar: 'undo redo | blocks | bold italic backcolor | link unlink | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | code fullscreen | help',

                    // แก้ไข CSS เพื่อหลีกเลี่ยง zoom issues
                    content_style: `
                body {
                    font-family: -apple-system, BlinkMacSystemFont, "San Francisco", "Segoe UI", Roboto, "Helvetica Neue", sans-serif;
                    font-size: 14px;
                    line-height: 1.6;
                    color: #374151;
                    background-color: #ffffff;
                    margin: 10px;
                    /* แก้ไข zoom issue */
                    zoom: 1;
                    transform: scale(1);
                    transform-origin: 0 0;
                    /* ปรับปรุง responsive */
                    max-width: 100%;
                    overflow-wrap: break-word;
                }

                /* ปรับปรุง typography */
                h1, h2, h3, h4, h5, h6 {
                    color: #111827;
                    margin-top: 1.5em;
                    margin-bottom: 0.5em;
                    line-height: 1.3;
                }

                p {
                    margin-bottom: 1em;
                }

                /* แก้ไข image responsive */
                img {
                    max-width: 100%;
                    height: auto;
                }

                /* แก้ไข table responsive */
                table {
                    width: 100%;
                    border-collapse: collapse;
                }

                th, td {
                    border: 1px solid #d1d5db;
                    padding: 8px;
                    text-align: left;
                }
            `,

                    // กำหนด valid elements เพื่อป้องกัน deprecated tags
                    valid_elements: '*[*]',
                    extended_valid_elements: '*[*]',

                    // ปรับปรุง image handling
                    image_advtab: true,
                    image_caption: true,

                    // ปรับปรุง link handling
                    link_assume_external_targets: true,
                    target_list: [{
                            title: 'Same window',
                            value: '_self'
                        },
                        {
                            title: 'New window',
                            value: '_blank'
                        }
                    ],

                    setup: function(editor) {
                        editor.on('init', function() {
                            editor.mode.set('design');

                            // ปิด additional warnings
                            if (editor.getWin() && editor.getWin().console) {
                                const editorWin = editor.getWin();
                                const originalEditorWarn = editorWin.console.warn;
                                editorWin.console.warn = function(...args) {
                                    if (args.some(arg =>
                                            typeof arg === 'string' &&
                                            (arg.includes('mozInputSource') || arg
                                                .includes('zoom'))
                                        )) {
                                        return;
                                    }
                                    originalEditorWarn.apply(editorWin.console, args);
                                };
                            }
                        });

                        // เพิ่ม custom commands หรือ buttons ได้ที่นี่
                        editor.ui.registry.addButton('customSave', {
                            text: 'Save',
                            onAction: function() {
                                // ส่งข้อมูลไป Livewire
                                const content = editor.getContent();
                                const hiddenInput = document.querySelector(
                                    'input[wire\\:model="content"]');
                                if (hiddenInput) {
                                    hiddenInput.value = content;
                                    hiddenInput.dispatchEvent(new Event('input', {
                                        bubbles: true
                                    }));
                                }
                            }
                        });

                        editor.on('change keyup paste', function() {
                            const content = editor.getContent();
                            const hiddenInput = document.querySelector(
                                'input[wire\\:model="content"]');
                            if (hiddenInput) {
                                hiddenInput.value = content;
                                hiddenInput.dispatchEvent(new Event('input', {
                                    bubbles: true
                                }));
                            }
                        });

                        // เพิ่ม auto-save (ถ้าต้องการ)
                        let autoSaveTimeout;
                        editor.on('change', function() {
                            clearTimeout(autoSaveTimeout);
                            autoSaveTimeout = setTimeout(function() {
                                // Trigger Livewire auto-save
                                if (typeof Livewire !== 'undefined') {
                                    // สามารถเรียก auto-save method ได้ที่นี่
                                }
                            }, 5000); // auto-save ทุก 5 วินาที
                        });
                    },

                    // การจัดการ file upload (ถ้าต้องการ)
                    automatic_uploads: false,
                    file_picker_types: 'image',

                    // ปรับปรุง performance
                    cache_suffix: '?v=7.4.1',

                    // ปิด features ที่ไม่จำเป็นเพื่อลด warnings
                    promotion: false,
                    branding: false,
                });
            };
            document.head.appendChild(script);

            // Manual slug input improvements
            const slugInput = document.getElementById('slug');
            if (slugInput) {
                slugInput.addEventListener('input', function(e) {
                    // Real-time cleaning for manual input
                    if (!@json($auto_slug)) {
                        let value = e.target.value;
                        // Remove Thai characters and clean up
                        value = value.toLowerCase()
                            .replace(/[ก-๙]/g, '') // Remove Thai characters
                            .replace(/[^a-z0-9\-]/g, '-') // Replace invalid chars with hyphen
                            .replace(/-+/g, '-') // Remove consecutive hyphens
                            .replace(/^-|-$/g, ''); // Remove leading/trailing hyphens

                        if (value !== e.target.value) {
                            e.target.value = value;
                            // Trigger Livewire update
                            e.target.dispatchEvent(new Event('input', {
                                bubbles: true
                            }));
                        }
                    }
                });

                // Prevent Thai input entirely in manual mode
                slugInput.addEventListener('keydown', function(e) {
                    if (!@json($auto_slug)) {
                        // Block Thai character input
                        const char = e.key;
                        if (char && /[ก-๙]/.test(char)) {
                            e.preventDefault();
                            return false;
                        }
                    }
                });
            }
        });

        // Listen for auto-slug toggle changes
        document.addEventListener('livewire:updated', () => {
            const slugInput = document.getElementById('slug');
            if (slugInput) {
                // Update placeholder based on auto_slug state
                if (@json($auto_slug)) {
                    slugInput.placeholder = 'auto-generated-slug';
                } else {
                    slugInput.placeholder = 'enter-your-slug';
                }
            }
        });

        function testDeleteModal() {
            window.dispatchEvent(new CustomEvent('confirm-delete'));
        }
    </script>

    <!-- Styles -->
    <style>
        [x-cloak] {
            display: none !important;
        }

        .bg-gradient-to-br {
            background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 100%);
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .shadow-sm {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .shadow-md {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .hover\:shadow-md:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .focus\:ring-2:focus {
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }

        /* Manual slug input styling */
        #slug:not([readonly]) {
            background-color: #ffffff !important;
        }

        #slug[readonly] {
            background-color: #f8fafc !important;
            color: #64748b !important;
        }

        /* Toggle button animations */
        button[wire\:click="toggleAutoSlug"] {
            transition: all 0.2s ease-in-out;
        }

        button[wire\:click="toggleAutoSlug"]:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Ensure proper z-index stacking */
        .z-50 {
            z-index: 50;
        }

        /* Hide elements with x-cloak until Alpine.js loads */
        [x-cloak] {
            display: none !important;
        }

        /* Modal backdrop blur effect */
        .modal-backdrop {
            backdrop-filter: blur(4px);
        }

        /* Smooth transitions */
        .transition-colors {
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }

        /* Loading spinner animation */
        .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Focus styles for accessibility */
        button:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
        }

        /* Hover effects */
        button:hover {
            transform: translateY(-1px);
        }

        button:active {
            transform: translateY(0);
        }
    </style>
</div>
