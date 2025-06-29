<div class="space-y-6">
    {{-- Header Section --}}
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Media Upload</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                Upload and manage your media files for blog posts
            </p>
        </div>
        <div class="mt-4 sm:mt-0 sm:flex sm:space-x-3">
            <a href="{{ route('administrator.blog.media.gallery') ?? '#' }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Media Gallery
            </a>
            <a href="{{ route('administrator.blog.dashboard') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Dashboard
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                Upload Settings
            </h3>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label for="collection" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Collection
                    </label>
                    <select wire:model.live="collection"
                            id="collection"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        @foreach($collections as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('collection') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="folder" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Folder (Optional)
                    </label>
                    <input wire:model="folder"
                           type="text"
                           id="folder"
                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="e.g., 2024/january">
                    @error('folder') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="uploadMode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Upload Mode
                    </label>
                    <select wire:model.live="uploadMode"
                            id="uploadMode"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="single">Single File</option>
                        <option value="multiple">Multiple Files</option>
                        <option value="bulk">Bulk Upload</option>
                    </select>
                </div>

                <div>
                    <label for="maxFiles" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Max Files: {{ $maxFiles }}
                    </label>
                    <input wire:model.live="maxFiles"
                           type="range"
                           id="maxFiles"
                           min="1"
                           max="50"
                           class="mt-1 block w-full">
                </div>
            </div>

            <div class="mt-6 flex flex-wrap items-center gap-4">
                <label class="flex items-center">
                    <input wire:model.live="autoUpload"
                           type="checkbox"
                           class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Auto Upload</span>
                </label>

                <label class="flex items-center">
                    <input wire:model.live="showPreview"
                           type="checkbox"
                           class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Show Preview</span>
                </label>

                <label class="flex items-center">
                    <input wire:model.live="optimizeImages"
                           type="checkbox"
                           class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Optimize Images</span>
                </label>

                <label class="flex items-center">
                    <input wire:model.live="generateThumbnails"
                           type="checkbox"
                           class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Generate Thumbnails</span>
                </label>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                File Metadata
            </h3>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label for="tags" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Tags
                    </label>
                    <input wire:model="tags"
                           type="text"
                           id="tags"
                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="tag1, tag2, tag3">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Separate tags with commas</p>
                    @error('tags') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="altText" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Alt Text
                    </label>
                    <input wire:model="altText"
                           type="text"
                           id="altText"
                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="Describe the image for accessibility">
                    @error('altText') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="caption" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Caption
                    </label>
                    <input wire:model="caption"
                           type="text"
                           id="caption"
                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="Optional caption text">
                    @error('caption') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            @if(!empty($failedFiles))
                <div class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                Upload Errors ({{ count($failedFiles) }} files)
                            </h3>
                            <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                @foreach($failedFiles as $failed)
                                    <div class="mb-2">
                                        <strong>{{ $failed['file']->getClientOriginalName() }}:</strong>
                                        <ul class="list-disc pl-5">
                                            @foreach($failed['errors'] as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-3">
                                <button wire:click="retryFailed"
                                        class="text-sm bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-200 px-3 py-1 rounded-md hover:bg-red-200 dark:hover:bg-red-900/60">
                                    Retry Failed Uploads
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($isUploading)
                <div class="mb-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="animate-spin h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                Uploading: {{ $currentFile }}
                            </p>
                            <div class="mt-2 bg-blue-200 dark:bg-blue-800 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                     style="width: {{ $totalFiles > 0 ? ($processedFiles / $totalFiles) * 100 : 0 }}%"></div>
                            </div>
                            <p class="mt-1 text-xs text-blue-600 dark:text-blue-300">
                                {{ $processedFiles }} of {{ $totalFiles }} files processed
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if($uploadComplete && !empty($uploadedFiles))
                <div class="mb-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                Upload completed! {{ count($uploadedFiles) }} file(s) uploaded successfully.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="upload-zone">
                <div class="relative">
                    <div id="dropzone"
                         class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-8 text-center transition-all duration-300 ease-in-out transform hover:border-gray-400 dark:hover:border-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800/50 cursor-pointer">

                        <input wire:model="files"
                               type="file"
                               id="fileInput"
                               multiple="{{ $uploadMode !== 'single' ? 'true' : 'false' }}"
                               accept="{{ $collection === 'featured' ? 'image/*' : '*' }}"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                               style="z-index: 10;">

                        <div class="space-y-4 pointer-events-none">
                            <div class="mx-auto h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                <svg class="h-8 w-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Drop files here or
                                    <span class="text-blue-600 dark:text-blue-400 underline hover:text-blue-500 dark:hover:text-blue-300 cursor-pointer">browse</span>
                                </h3>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    Upload up to {{ $maxFiles }} files at once
                                </p>
                                <div class="mt-4 text-xs text-gray-400 dark:text-gray-500">
                                    <p class="font-medium mb-2">Supported formats:</p>
                                    <div class="flex flex-wrap gap-2 justify-center">
                                        @foreach($allowedTypes as $type)
                                            @if($type === 'image')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800">
                                                    Images
                                                </span>
                                            @elseif($type === 'video')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                                    Videos
                                                </span>
                                            @elseif($type === 'document')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                                    Documents
                                                </span>
                                            @elseif($type === 'audio')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300 border border-orange-200 dark:border-orange-800">
                                                    Audio
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(!empty($files) && $showPreview)
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">
                        Selected Files ({{ count($files) }})
                        @if(count($files) > 0)
                            <button wire:click="clearAll"
                                    class="ml-2 text-xs text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200">
                                Clear All
                            </button>
                        @endif
                    </h4>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        @foreach($files as $index => $file)
                            <div class="relative border border-gray-200 dark:border-gray-700 rounded-lg p-3 bg-gray-50 dark:bg-gray-700">
                                <button wire:click="removeFile({{ $index }})"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                    ×
                                </button>

                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        @if($getFilePreview($file))
                                            <img src="{{ $getFilePreview($file) }}"
                                                 alt="Preview"
                                                 class="w-12 h-12 object-cover rounded">
                                        @else
                                            <div class="w-12 h-12 bg-gray-200 dark:bg-gray-600 rounded flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $file->getClientOriginalName() }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $this->formatFileSize($file->getSize()) }}
                                        </p>

                                        @if(isset($uploadProgress[$index]))
                                            <div class="mt-2">
                                                <div class="bg-gray-200 dark:bg-gray-600 rounded-full h-1">
                                                    <div class="bg-blue-600 h-1 rounded-full transition-all duration-300"
                                                         style="width: {{ $uploadProgress[$index] }}%"></div>
                                                </div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ $uploadProgress[$index] }}%
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!empty($files) && !$isUploading)
                <div class="mt-6 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <button wire:click="startUpload"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            Upload {{ count($files) }} File(s)
                        </button>

                        <button wire:click="clearAll"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Clear All
                        </button>
                    </div>

                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Ready to upload {{ count($files) }} file(s)
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeMediaUpload();
        });

        document.addEventListener('livewire:navigated', function() {
            setTimeout(initializeMediaUpload, 100);
        });

        function initializeMediaUpload() {
            // Copy to clipboard functionality
            window.copyToClipboard = function(text) {
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(text).then(function() {
                        showNotification('URL copied to clipboard!', 'success');
                    }).catch(function() {
                        fallbackCopy(text);
                    });
                } else {
                    fallbackCopy(text);
                }
            };

            function fallbackCopy(text) {
                const textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.style.position = 'fixed';
                textArea.style.opacity = '0';
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    document.execCommand('copy');
                    showNotification('URL copied to clipboard!', 'success');
                } catch (err) {
                    console.error('Copy failed:', err);
                }
                document.body.removeChild(textArea);
            }

            function showNotification(message, type = 'success') {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 px-4 py-2 rounded-md shadow-lg z-50 text-white ${
                    type === 'success' ? 'bg-green-500' : 'bg-red-500'
                }`;
                notification.textContent = message;
                document.body.appendChild(notification);

                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 3000);
            }

            // Initialize drag and drop
            const dropzone = document.getElementById('dropzone');
            if (dropzone) {
                let isDragging = false;

                function handleDragEnter(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    isDragging = true;
                    dropzone.classList.add('border-blue-500', 'bg-blue-50');
                    dropzone.classList.remove('border-gray-300');
                }

                function handleDragOver(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                function handleDragLeave(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (!dropzone.contains(e.relatedTarget)) {
                        isDragging = false;
                        dropzone.classList.remove('border-blue-500', 'bg-blue-50');
                        dropzone.classList.add('border-gray-300');
                    }
                }

                function handleDrop(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    isDragging = false;
                    dropzone.classList.remove('border-blue-500', 'bg-blue-50');
                    dropzone.classList.add('border-gray-300');

                    const files = Array.from(e.dataTransfer.files);
                    if (files.length > 0) {
                        // Trigger Livewire to update files
                        const fileInput = document.getElementById('fileInput');
                        if (fileInput && window.Livewire) {
                            // Create a new FileList-like object
                            const dt = new DataTransfer();
                            files.forEach(file => dt.items.add(file));
                            fileInput.files = dt.files;

                            // Trigger change event
                            fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    }
                }

                // Add event listeners
                dropzone.addEventListener('dragenter', handleDragEnter);
                dropzone.addEventListener('dragover', handleDragOver);
                dropzone.addEventListener('dragleave', handleDragLeave);
                dropzone.addEventListener('drop', handleDrop);
            }

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'u') {
                    e.preventDefault();
                    const fileInput = document.getElementById('fileInput');
                    if (fileInput) {
                        fileInput.click();
                    }
                }

                if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                    e.preventDefault();
                    const component = document.querySelector('[wire\\:id]');
                    if (component && window.Livewire) {
                        const componentId = component.getAttribute('wire:id');
                        if (componentId) {
                            Livewire.find(componentId).call('startUpload');
                        }
                    }
                }

                if (e.key === 'Escape') {
                    const component = document.querySelector('[wire\\:id]');
                    if (component && window.Livewire) {
                        const componentId = component.getAttribute('wire:id');
                        if (componentId) {
                            Livewire.find(componentId).call('clearAll');
                        }
                    }
                }
            });
        }
    </script>
    @endpush

    @push('styles')
    <style>
        .upload-zone {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

    @push('styles')
    <style>
        .upload-zone {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .upload-zone:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .file-preview {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .file-preview:hover {
            transform: translateY(-1px) scale(1.02);
        }

        .progress-bar {
            background: linear-gradient(90deg, #3B82F6, #1D4ED8);
            border-radius: 9999px;
            transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            transition-property: background-color, border-color, color;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }
    </style>
    @endpush
</div>
