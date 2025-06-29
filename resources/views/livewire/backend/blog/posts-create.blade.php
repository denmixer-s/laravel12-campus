{{-- resources/views/livewire/backend/blog/posts-create.blade.php --}}
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8 w-full">
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
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </div>
                            <h1 class="text-3xl font-bold text-slate-800">Create New Blog Post</h1>
                        </div>
                        <p class="text-slate-600">Create engaging content for your blog readers</p>
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
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('excerpt') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"></textarea>
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

                        @if ($featuredImage)
                            <div class="mb-4">
                                <div class="relative inline-block">
                                    @if (method_exists($featuredImage, 'temporaryUrl'))
                                        <img src="{{ $featuredImage->temporaryUrl() }}" alt="Featured Image Preview"
                                            class="w-full max-w-md h-48 object-cover rounded-lg border border-slate-200">
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
                                        <p class="text-slate-600 font-medium">Click to upload featured image</p>
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

                        <!-- Gallery Images Previews -->
                        @if (!empty($galleryImages))
                            <div class="mb-6">
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                    @foreach ($galleryImages as $index => $image)
                                        @if ($image && method_exists($image, 'temporaryUrl'))
                                            <div class="relative group">
                                                <img src="{{ $image->temporaryUrl() }}"
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
                                        <p class="text-slate-600 font-medium">Click to upload gallery images</p>
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
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('meta_description') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"></textarea>
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
                        <div class="items-center gap-3">
                            {{-- Progress Indicator --}}
                            <div class="items-center gap-2">
                                <span class="text-xs text-slate-500">Progress:</span>
                                <div class="w-full bg-slate-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-300"
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

                        @if (!empty($selectedTags) && isset($this->selectedTagsData))
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
                        <button type="submit" wire:loading.attr="disabled" {{ !$this->canCreate ? 'disabled' : '' }}
                            class="w-full inline-flex justify-center items-center px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 disabled:from-slate-300 disabled:to-slate-400 text-white font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="create">
                                @if ($status === 'draft')
                                    📝 Create Draft
                                @elseif($status === 'published')
                                    🚀 Publish Post
                                @elseif($status === 'scheduled')
                                    ⏰ Schedule Post
                                @else
                                    💾 Save Post
                                @endif
                            </span>
                            <span wire:loading wire:target="create" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Creating...
                            </span>
                        </button>

                        @if (!$this->canCreate)
                            <p class="mt-2 text-xs text-slate-500 text-center">
                                Please complete the required fields
                            </p>
                        @endif

                        <div class="mt-3 space-y-2">
                            <button wire:click="resetForm" type="button"
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors duration-200">
                                Reset Form
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Loading Overlay -->

    <div wire:loading.flex wire:target="create,featuredImage,galleryImages"
        class="fixed inset-0 z-50 bg-white bg-opacity-75 flex items-center justify-center">
        <div class="flex flex-col items-center space-y-4">
            <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                    stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <p class="text-sm text-slate-600 font-medium" wire:loading.remove
                wire:target="featuredImage,galleryImages">Creating blog post...</p>
            <p class="text-sm text-slate-600 font-medium" wire:loading wire:target="featuredImage,galleryImages">
                Uploading images...</p>
        </div>
    </div>

    <!-- Inline Scripts -->
    <script>
        document.addEventListener('livewire:initialized', () => {

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
    </script>

    <!-- Inline Styles -->
    <style>
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
    </style>
</div>
