<div data-blog-posts-list class="blog-posts-container" x-data="{
    confirmingDeletion: @entangle('confirmingDeletion'),
    confirmingBulkAction: @entangle('confirmingBulkAction'),
    init() {
        console.log('PostsList component initialized');

        // Keyboard shortcuts
        this.$nextTick(() => {
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    if (this.confirmingDeletion) {
                        $wire.call('cancelDelete');
                    } else if (this.confirmingBulkAction) {
                        $wire.call('cancelBulkAction');
                    }
                }
            });
        });
    }
}">

    {{-- Page Header --}}
    <div class="mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-slate-800 mb-2">Blog Posts</h1>
                    <p class="text-slate-600">Manage your blog posts, categories, and content</p>
                </div>

                @can('blog.posts.create')
                    <div class="mt-4 sm:mt-0">
                        <a href="{{ route('administrator.blog.posts.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Create New Post
                        </a>
                    </div>
                @endcan
            </div>
        </div>
    </div>

    {{-- Status Tabs --}}
    <div class="blog-status-tabs mb-6">
        <div class="flex flex-wrap border-b border-gray-200 dark:border-gray-700">
            <button wire:click="$set('status', '')"
                class="px-6 py-3 text-sm font-medium {{ empty($status) ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50 dark:bg-blue-900/20' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }} transition-colors">
                All Posts
                <span
                    class="ml-2 px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full">{{ $statusCounts['all'] }}</span>
            </button>
            <button wire:click="$set('status', 'published')"
                class="px-6 py-3 text-sm font-medium {{ $status === 'published' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50 dark:bg-blue-900/20' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }} transition-colors">
                Published
                <span
                    class="ml-2 px-2 py-1 text-xs bg-green-100 dark:bg-green-800 text-green-600 dark:text-green-100 rounded-full">{{ $statusCounts['published'] }}</span>
            </button>
            <button wire:click="$set('status', 'draft')"
                class="px-6 py-3 text-sm font-medium {{ $status === 'draft' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50 dark:bg-blue-900/20' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }} transition-colors">
                Drafts
                <span
                    class="ml-2 px-2 py-1 text-xs bg-yellow-100 dark:bg-yellow-800 text-yellow-600 dark:text-yellow-100 rounded-full">{{ $statusCounts['draft'] }}</span>
            </button>
            <button wire:click="$set('status', 'scheduled')"
                class="px-6 py-3 text-sm font-medium {{ $status === 'scheduled' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50 dark:bg-blue-900/20' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }} transition-colors">
                Scheduled
                <span
                    class="ml-2 px-2 py-1 text-xs bg-purple-100 dark:bg-purple-800 text-purple-600 dark:text-purple-100 rounded-full">{{ $statusCounts['scheduled'] }}</span>
            </button>
            <button wire:click="$set('status', 'archived')"
                class="px-6 py-3 text-sm font-medium {{ $status === 'archived' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50 dark:bg-blue-900/20' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }} transition-colors">
                Archived
                <span
                    class="ml-2 px-2 py-1 text-xs bg-red-100 dark:bg-red-800 text-red-600 dark:text-red-100 rounded-full">{{ $statusCounts['archived'] ?? 0 }}</span>
            </button>
        </div>
    </div>

    {{-- Search and Filters --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                {{-- Search --}}
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search posts..."
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        @if ($search)
                            <button wire:click="$set('search', '')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg class="h-4 w-4 text-gray-400 hover:text-gray-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Filter Toggle --}}
                <div class="flex items-center gap-3">
                    <button wire:click="toggleFilters"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z">
                            </path>
                        </svg>
                        Filters
                        @if ($category || $author || $dateRange)
                            <span
                                class="ml-2 inline-flex items-center justify-center w-5 h-5 text-xs font-medium text-white bg-blue-600 rounded-full">
                                {{ collect([$category, $author, $dateRange])->filter()->count() }}
                            </span>
                        @endif
                    </button>

                    @can('blog.posts.export')
                        <button
                            class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Export
                        </button>
                    @endcan
                </div>
            </div>

            {{-- Advanced Filters --}}
            @if ($showingFilters)
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- Category Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select wire:model.live="category"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Categories</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Author Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Author</label>
                            <select wire:model.live="author"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Authors</option>
                                @foreach ($authors as $auth)
                                    <option value="{{ $auth->id }}">{{ $auth->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Date Range --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                            <input wire:model.live="dateRange" type="text" placeholder="YYYY-MM-DD to YYYY-MM-DD"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Per Page --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Show</label>
                            <select wire:model.live="perPage"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="10">10 posts</option>
                                <option value="25">25 posts</option>
                                <option value="50">50 posts</option>
                                <option value="100">100 posts</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <button wire:click="clearFilters"
                            class="inline-flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-800 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            Clear Filters
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Bulk Actions --}}
    @if (count($selectedPosts) > 0)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="text-sm text-blue-800">
                    <strong>{{ count($selectedPosts) }}</strong> post(s) selected
                </div>
                <div class="flex items-center gap-3">
                    <select wire:model="bulkAction"
                        class="px-3 py-2 border border-blue-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Choose Action...</option>
                        @can('blog.posts.edit')
                            <option value="publish">Publish</option>
                            <option value="draft">Move to Draft</option>
                        @endcan
                        @can('blog.posts.delete')
                            <option value="archived">Move to Archive</option>
                            <option value="delete">Delete Permanently</option>
                        @endcan
                    </select>
                    <button wire:click="confirmBulkAction"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition-colors">
                        Apply
                    </button>
                    <button wire:click="$set('selectedPosts', [])"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm font-medium transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Posts Table --}}
    <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        @if ($posts->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="w-12 px-6 py-3">
                                <input type="checkbox" wire:model.live="selectAll"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="px-6 py-3 text-left">
                                <button wire:click="sortBy('title')"
                                    class="group inline-flex items-center text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                    Title
                                    @if ($sortBy === 'title')
                                        <svg class="ml-2 w-3 h-3 {{ $sortDirection === 'asc' ? 'rotate-180' : '' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <button wire:click="sortBy('status')"
                                    class="group inline-flex items-center text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                    Status
                                    @if ($sortBy === 'status')
                                        <svg class="ml-2 w-3 h-3 {{ $sortDirection === 'asc' ? 'rotate-180' : '' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <button wire:click="sortBy('user_id')"
                                    class="group inline-flex items-center text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                    Author
                                    @if ($sortBy === 'user_id')
                                        <svg class="ml-2 w-3 h-3 {{ $sortDirection === 'asc' ? 'rotate-180' : '' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Category & Tags
                            </th>
                            <th class="px-6 py-3 text-left">
                                <button wire:click="sortBy('views_count')"
                                    class="group inline-flex items-center text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                    Views
                                    @if ($sortBy === 'views_count')
                                        <svg class="ml-2 w-3 h-3 {{ $sortDirection === 'asc' ? 'rotate-180' : '' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left">
                                <button wire:click="sortBy('created_at')"
                                    class="group inline-flex items-center text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                    Date
                                    @if ($sortBy === 'created_at')
                                        <svg class="ml-2 w-3 h-3 {{ $sortDirection === 'asc' ? 'rotate-180' : '' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                        </svg>
                                    @endif
                                </button>
                            </th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($posts as $post)
                            <tr
                                class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ in_array($post->id, $selectedPosts) ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                                {{-- Checkbox --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" wire:model.live="selectedPosts"
                                        value="{{ $post->id }}"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>

                                {{-- Title with Featured Badge --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-start">
                                        @if ($post->getFirstMedia('featured_image'))
                                            <img src="{{ $post->getFirstMedia('featured_image')->getUrl('featured_thumb') }}"
                                                alt="{{ $post->title }}"
                                                class="w-12 h-12 rounded-lg object-cover mr-3 flex-shrink-0">
                                        @else
                                            <div
                                                class="w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded-lg mr-3 flex-shrink-0 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            @if ($quickEditingPost === $post->id)
                                                <div class="space-y-2">
                                                    <input wire:model="quickEditData.title" type="text"
                                                        class="block w-full px-3 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                    <div class="flex items-center gap-2">
                                                        <button wire:click="saveQuickEdit"
                                                            class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                                            Save
                                                        </button>
                                                        <button wire:click="cancelQuickEdit"
                                                            class="px-3 py-1 bg-gray-600 text-white text-xs rounded hover:bg-gray-700">
                                                            Cancel
                                                        </button>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="flex items-center">
                                                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        <a href="{{ route('administrator.blog.posts.edit', $post) }}"
                                                            class="hover:text-blue-600 transition-colors hover:underline"
                                                            title="{{ $post->title }}">
                                                            {{ mb_strlen($post->title) > 220 ? mb_substr($post->title, 0, 220) . '...' : $post->title }}
                                                        </a>
                                                    </h3>
                                                    @if ($post->is_featured)
                                                        <span
                                                            class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            Featured
                                                        </span>
                                                    @endif
                                                </div>
                                                @if ($post->excerpt)
                                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                                                        title="{{ $post->excerpt }}">
                                                        {{ mb_strlen($post->excerpt) > 220 ? mb_substr($post->excerpt, 0, 220) . '...' : $post->excerpt }}
                                                    </p>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($quickEditingPost === $post->id)
                                        <select wire:model="quickEditData.status"
                                            class="px-2 py-1 text-xs border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                                            <option value="draft">Draft</option>
                                            <option value="published">Published</option>
                                            <option value="scheduled">Scheduled</option>
                                            <option value="archived">Archived</option>
                                        </select>
                                    @else
                                        @php
                                            $statusClasses = [
                                                'published' =>
                                                    'bg-green-100 text-green-800 dark:bg-green-800/20 dark:text-green-400',
                                                'draft' =>
                                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-800/20 dark:text-yellow-400',
                                                'scheduled' =>
                                                    'bg-purple-100 text-purple-800 dark:bg-purple-800/20 dark:text-purple-400',
                                                'archived' =>
                                                    'bg-red-100 text-red-800 dark:bg-red-800/20 dark:text-red-400',
                                            ];
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$post->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($post->status) }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Author --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-xs font-medium text-gray-700">
                                                {{ strtoupper(substr($post->author->name, 0, 2)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $post->author->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $post->author->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Categories & Tags --}}
                                <td class="px-6 py-4">
                                    @if ($quickEditingPost === $post->id)
                                        <div class="space-y-2">
                                            <label
                                                class="block text-xs font-medium text-gray-700 dark:text-gray-300">Category</label>
                                            <select wire:model="quickEditData.category"
                                                class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                                <option value="">Select Category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                        <div class="space-y-2">
                                            {{-- Category --}}
                                            @if ($post->category)
                                                <div>
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800/20 dark:text-blue-400">
                                                        {{ $post->category->name }}
                                                    </span>
                                                </div>
                                            @endif

                                            {{-- Tags --}}
                                            @if ($post->tags && $post->tags->count() > 0)
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach ($post->tags->take(3) as $tag)
                                                        <span
                                                            class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700/50 dark:text-gray-300">
                                                            #{{ $tag->name }}
                                                        </span>
                                                    @endforeach
                                                    @if ($post->tags->count() > 3)
                                                        <span
                                                            class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700/50 dark:text-gray-400">
                                                            +{{ $post->tags->count() - 3 }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif

                                            @if (!$post->category && (!$post->tags || $post->tags->count() === 0))
                                                <span class="text-sm text-gray-400 dark:text-gray-500">No
                                                    category/tags</span>
                                            @endif
                                        </div>
                                    @endif
                                </td>

                                {{-- Views --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        {{ number_format($post->views_count ?? 0) }}
                                    </div>
                                </td>

                                {{-- Date --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <div>
                                        <div class="font-medium">{{ $post->created_at->format('M j, Y') }}</div>
                                        <div class="text-gray-500 dark:text-gray-400">
                                            {{ $post->created_at->format('g:i A') }}</div>
                                    </div>
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- Quick Edit --}}
                                        @if ($quickEditingPost !== $post->id)
                                            @can('blog.posts.edit')
                                                <button wire:click="quickEdit({{ $post->id }})"
                                                    class="text-blue-600 hover:text-blue-900 transition-colors"
                                                    title="Quick Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </button>
                                            @endcan

                                            {{-- Toggle Featured --}}
                                            @can('blog.posts.edit')
                                                <button wire:click="toggleFeatured({{ $post->id }})"
                                                    class="{{ $post->is_featured ? 'text-yellow-600 hover:text-yellow-800' : 'text-gray-400 hover:text-yellow-600' }} transition-colors"
                                                    title="{{ $post->is_featured ? 'Remove from Featured' : 'Mark as Featured' }}">
                                                    <svg class="w-4 h-4"
                                                        fill="{{ $post->is_featured ? 'currentColor' : 'none' }}"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                                        </path>
                                                    </svg>
                                                </button>
                                            @endcan

                                            {{-- Duplicate --}}
                                            @can('blog.posts.create')
                                                <button wire:click="duplicatePost({{ $post->id }})"
                                                    class="text-gray-600 hover:text-gray-900 transition-colors"
                                                    title="Duplicate Post">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                </button>
                                            @endcan

                                            {{-- View Post --}}
                                            <a href="{{ route('blog.show', $post->slug) }}" target="_blank"
                                                class="text-green-600 hover:text-green-900 transition-colors"
                                                title="View Post">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                    </path>
                                                </svg>
                                            </a>

                                            {{-- Delete --}}
                                            @can('blog.posts.delete')
                                                <button wire:click="confirmDelete({{ $post->id }})"
                                                    class="text-red-600 hover:text-red-900 transition-colors"
                                                    title="Delete Post">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            @endcan
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                {{ $posts->links() }}
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No posts found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    @if ($search || $status || $category || $author || $dateRange)
                        Try adjusting your search criteria or filters.
                    @else
                        Get started by creating your first blog post.
                    @endif
                </p>
                @if (!$search && !$status && !$category && !$author && !$dateRange)
                    @can('blog.posts.create')
                        <div class="mt-6">
                            <a href="{{ route('administrator.blog.posts.create') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create Your First Post
                            </a>
                        </div>
                    @endcan
                @endif
            </div>
        @endif
    </div>

    {{-- Delete Confirmation Modal --}}
    @if ($confirmingDeletion)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                    <h3 class="text-base font-semibold leading-6 text-gray-900">Delete Post</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            Are you sure you want to delete this post? This action cannot be undone.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button wire:click="deletePost"
                                class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                                Delete
                            </button>
                            <button wire:click="cancelDelete"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Bulk Action Confirmation Modal --}}
    @if ($confirmingBulkAction)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50">
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                    <h3 class="text-base font-semibold leading-6 text-gray-900">Confirm Bulk Action
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            Are you sure you want to <strong>{{ $bulkAction }}</strong>
                                            {{ count($selectedPosts) }} selected post(s)?
                                            @if (in_array($bulkAction, ['delete', 'archived']))
                                                This action cannot be undone.
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button wire:click="executeBulkAction"
                                class="inline-flex w-full justify-center rounded-md {{ in_array($bulkAction, ['delete', 'archived']) ? 'bg-red-600 hover:bg-red-500' : 'bg-blue-600 hover:bg-blue-500' }} px-3 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto">
                                {{ ucfirst($bulkAction) }}
                            </button>
                            <button wire:click="cancelBulkAction"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Loading States --}}
    <div wire:loading wire:target="search,status,category,author,dateRange,sortBy,perPage"
        class="fixed top-4 right-4 z-50">
        <div class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg flex items-center">
            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                    stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            Loading...
        </div>
    </div>

    {{-- Toast Notifications --}}
    <div x-data="{ show: false, message: '' }"
        x-on:post-deleted.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 3000)"
        x-on:post-updated.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 3000)"
        x-on:post-duplicated.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 3000)"
        x-on:posts-updated.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 3000)">
        <div x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2" class="fixed top-4 right-4 z-50 max-w-sm">
            <div class="bg-green-600 text-white px-6 py-4 rounded-lg shadow-lg flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span x-text="message"></span>
                <button @click="show = false" class="ml-4 text-green-200 hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
