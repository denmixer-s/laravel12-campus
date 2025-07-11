<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-800 mb-2">
                            {{ $isEdit ? 'Edit University' : 'Create New University' }}
                        </h1>
                        <p class="text-slate-600">
                            {{ $isEdit ? 'Update university information' : 'Add a new university to your organization' }}
                        </p>
                    </div>

                    <!-- Breadcrumbs -->
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            @foreach ($breadcrumbs as $breadcrumb)
                                <li class="inline-flex items-center">
                                    @if (!$loop->last)
                                        <a href="{{ $breadcrumb['url'] }}"
                                           class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600">
                                            {{ $breadcrumb['name'] }}
                                        </a>
                                        <svg class="w-3 h-3 text-slate-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    @else
                                        <span class="ml-1 text-sm font-medium text-slate-500 md:ml-2">
                                            {{ $breadcrumb['name'] }}
                                        </span>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-slate-700">Form Progress</span>
                    <span class="text-sm text-slate-500">{{ $formStats['filled'] }}/{{ $formStats['total'] }} fields completed</span>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                         style="width: {{ $formStats['percentage'] }}%"></div>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <form wire:submit.prevent="save">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Main Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h2 class="text-lg font-semibold text-slate-800 mb-4">Basic Information</h2>

                        <!-- University Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                                University Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   wire:model.live="name"
                                   class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('name') border-red-500 @enderror"
                                   placeholder="Enter university name">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- URL Slug -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <label for="slug" class="block text-sm font-medium text-slate-700">
                                    URL Slug <span class="text-red-500">*</span>
                                </label>

                                @if (!$isEdit)
                                    <div class="flex items-center space-x-2">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox"
                                                   @checked($autoGenerateSlug)
                                                   wire:change="toggleAutoSlug"
                                                   class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            <span class="ml-2 text-xs text-slate-600">Auto-generate</span>
                                        </label>

                                        @if (!$autoGenerateSlug)
                                            <button type="button"
                                                    wire:click="generateSlug"
                                                    class="text-xs text-blue-600 hover:text-blue-800 underline">
                                                Generate from name
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="relative">
                                <input type="text"
                                       id="slug"
                                       wire:model="slug"
                                       class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('slug') border-red-500 @enderror"
                                       placeholder="university-slug"
                                       {{ $autoGenerateSlug && !$isEdit ? 'readonly' : '' }}>
                                @if ($autoGenerateSlug && !$isEdit)
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            @if ($slug)
                                <p class="mt-1 text-xs text-slate-500">
                                    URL: {{ url('/' . $slug) }}
                                </p>
                            @endif
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- University Code -->
                        <div class="mb-4">
                            <label for="code" class="block text-sm font-medium text-slate-700 mb-2">
                                University Code
                            </label>
                            <input type="text"
                                   id="code"
                                   wire:model.live="code"
                                   class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('code') border-red-500 @enderror"
                                   placeholder="e.g., RMUTI, CU, TU">
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-2">
                                Description
                            </label>
                            <textarea id="description"
                                      wire:model.live="description"
                                      rows="4"
                                      class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('description') border-red-500 @enderror"
                                      placeholder="Enter university description..."></textarea>
                            <div class="mt-1 flex justify-between">
                                <span></span>
                                <span class="text-xs text-slate-500">{{ strlen($description) }}/1000 characters</span>
                            </div>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h2 class="text-lg font-semibold text-slate-800 mb-4">Contact Information</h2>

                        <!-- Address -->
                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-slate-700 mb-2">
                                Address
                            </label>
                            <textarea id="address"
                                      wire:model.live="address"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('address') border-red-500 @enderror"
                                      placeholder="Enter university address..."></textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">
                                    Phone Number
                                </label>
                                <input type="text"
                                       id="phone"
                                       wire:model.live="phone"
                                       class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('phone') border-red-500 @enderror"
                                       placeholder="e.g., +66-2-123-4567">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                                    Email Address
                                </label>
                                <input type="email"
                                       id="email"
                                       wire:model.live="email"
                                       class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 @enderror"
                                       placeholder="info@university.ac.th">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Website -->
                        <div class="mt-4">
                            <label for="website" class="block text-sm font-medium text-slate-700 mb-2">
                                Website URL
                            </label>
                            <input type="url"
                                   id="website"
                                   wire:model.live="website"
                                   class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('website') border-red-500 @enderror"
                                   placeholder="https://www.university.ac.th">
                            @error('website')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Right Column - Sidebar -->
                <div class="space-y-6">
                    <!-- Status Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Status</h3>

                        <div class="space-y-4">
                            <!-- Active Status -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <label for="is_active" class="text-sm font-medium text-slate-700">
                                        University Status
                                    </label>
                                    <p class="text-xs text-slate-500">
                                        {{ $is_active ? 'University is currently active' : 'University is currently inactive' }}
                                    </p>
                                </div>
                                <button type="button"
                                        wire:click="toggleActive"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 {{ $is_active ? 'bg-blue-600' : 'bg-slate-200' }}">
                                    <span class="sr-only">Toggle active status</span>
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                            </div>

                            <!-- Status Badge -->
                            <div class="pt-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <span class="w-1.5 h-1.5 mr-1.5 rounded-full {{ $is_active ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                    {{ $is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Changes Summary -->
                    @if ($hasChanges)
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-amber-800">Unsaved Changes</h3>
                                    <p class="text-sm text-amber-700 mt-1">You have unsaved changes. Don't forget to save your work.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Form Actions -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Actions</h3>

                        <div class="space-y-3">
                            <!-- Save Button -->
                            <button type="submit"
                                    wire:loading.attr="disabled"
                                    wire:target="save"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-medium rounded-lg transition-colors disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="save">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ $isEdit ? 'Update University' : 'Create University' }}
                                </span>
                                <span wire:loading wire:target="save" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    {{ $isEdit ? 'Updating...' : 'Creating...' }}
                                </span>
                            </button>

                            <!-- Reset Button -->
                            <button type="button"
                                    wire:click="resetForm"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Reset Form
                            </button>

                            <!-- Cancel Button -->
                            <button type="button"
                                    wire:click="cancel"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel
                            </button>
                        </div>
                    </div>

                    <!-- Help Card -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Help & Tips</h3>
                                <div class="text-sm text-blue-700 mt-1 space-y-1">
                                    <p>• URL slug should be unique and URL-friendly</p>
                                    <p>• University code is optional but recommended</p>
                                    <p>• All contact information is optional</p>
                                    @if (!$isEdit)
                                        <p>• Auto-generate slug can be toggled on/off</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Loading Overlay -->
        <div wire:loading.flex wire:target="save"
             class="fixed inset-0 z-50 bg-white bg-opacity-75 flex items-center justify-center">
            <div class="flex items-center space-x-3">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="text-lg text-slate-600">
                    {{ $isEdit ? 'Updating university...' : 'Creating university...' }}
                </span>
            </div>
        </div>

        <!-- JavaScript for enhanced UX -->
        <script>
            document.addEventListener('livewire:initialized', () => {
                console.log('UniversitiesForm component initialized');
            });

            // Handle browser back/forward with unsaved changes
            window.addEventListener('beforeunload', function (e) {
                @if ($hasChanges)
                    e.preventDefault();
                    e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
                    return 'You have unsaved changes. Are you sure you want to leave?';
                @endif
            });
        </script>
    </div>
</div>
