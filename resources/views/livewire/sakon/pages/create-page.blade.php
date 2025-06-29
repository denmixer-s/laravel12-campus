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
                            <h1 class="text-3xl font-bold text-slate-800">Create New Page</h1>
                        </div>
                        <p class="text-slate-600">Add a new page to your website homepage or frontend</p>
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
                        <h2 class="text-xl font-semibold text-slate-800 mb-6">Page Content</h2>

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

                        @if ($this->featuredImagePreview)
                            <!-- Featured Image Preview -->
                            <div class="mb-4">
                                <div class="relative inline-block">
                                    <img src="{{ $this->featuredImagePreview }}" alt="Featured Image Preview"
                                        class="w-full max-w-md h-48 object-cover rounded-lg border border-slate-200">
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

                        <!-- Featured Image Upload -->
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
                        @if (!empty($this->galleryImagePreviews))
                            <div class="mb-6">
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                    @foreach ($this->galleryImagePreviews as $index => $preview)
                                        @if ($preview)
                                            <div class="relative group">
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
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Progress Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Creation Progress</h3>

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
                                <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-300"
                                    style="width: {{ $this->progress }}%"></div>
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
                                Create Page
                            </span>
                            <span wire:loading wire:target="create" class="flex items-center">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                                Creating Page...
                            </span>
                        </button>

                        @if (!$this->canCreate)
                            <p class="mt-2 text-xs text-slate-500 text-center">
                                Please complete the required fields (title and slug)
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
                                <span>Use descriptive titles for better SEO</span>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Keep slugs short and meaningful</span>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Use WYSIWYG editor for rich content formatting</span>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Featured images appear in page previews</span>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Gallery images are great for showcasing multiple photos</span>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Images are automatically optimized for all devices</span>
                            </div>
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
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="text-sm text-slate-600 font-medium" wire:loading.remove
                wire:target="featuredImage,galleryImages">Creating page...</p>
            <p class="text-sm text-slate-600 font-medium" wire:loading wire:target="featuredImage,galleryImages">
                Uploading images...</p>
        </div>
    </div>
</div>

<!-- TinyMCE WYSIWYG Editor Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.4.1/tinymce.min.js"></script>

<script>
    document.addEventListener('livewire:initialized', () => {
        console.log('Livewire initialized for CreatePage component');

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
        Livewire.on('resetEditor', () => {
            if (tinymce.get('tinymce-editor')) {
                tinymce.get('tinymce-editor').setContent('');
            }
        });
    });

    // Cleanup TinyMCE when component is destroyed
    document.addEventListener('livewire:navigating', () => {
        if (tinymce.get('tinymce-editor')) {
            tinymce.remove('#tinymce-editor');
        }
    });
</script>
