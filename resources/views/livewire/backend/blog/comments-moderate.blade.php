{{-- resources/views/livewire/backend/blog/comments-moderate.blade.php --}}
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Comments Moderation</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                Manage and moderate blog comments from users
            </p>
        </div>
        <div class="mt-4 sm:mt-0 sm:flex-none">
            <a href="{{ route('administrator.blog.dashboard') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Total Comments
                            </dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ number_format($stats['total']) }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Pending Review
                            </dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ number_format($stats['pending']) }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Approved
                            </dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ number_format($stats['approved']) }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Rejected
                            </dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ number_format($stats['rejected']) }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Spam
                            </dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ number_format($stats['spam']) }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters & Search --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                {{-- Search --}}
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Search Comments
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search"
                               type="text"
                               id="search"
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                               placeholder="Search by content, author, or post...">
                    </div>
                </div>

                {{-- Status Filter --}}
                <div>
                    <label for="statusFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Status
                    </label>
                    <select wire:model.live="statusFilter"
                            id="statusFilter"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Post Filter --}}
                <div>
                    <label for="postFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Blog Post
                    </label>
                    <select wire:model.live="postFilter"
                            id="postFilter"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="all">All Posts</option>
                        @foreach($posts as $post)
                            <option value="{{ $post->id }}">{{ Str::limit($post->title, 40) }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Per Page --}}
                <div>
                    <label for="perPage" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Per Page
                    </label>
                    <select wire:model.live="perPage"
                            id="perPage"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>

            {{-- Clear Filters --}}
            @if($search || $statusFilter !== 'all' || $postFilter !== 'all')
                <div class="mt-4">
                    <button wire:click="clearFilters"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Clear Filters
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- Bulk Actions --}}
    @if(count($selectedComments) > 0)
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                            {{ count($selectedComments) }} comment(s) selected
                        </h3>
                    </div>
                </div>
                <div class="flex space-x-3">
                    @can('blog.comments.approve')
                        <button wire:click="initiateBulkAction('approve')"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Approve
                        </button>
                        <button wire:click="initiateBulkAction('reject')"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reject
                        </button>
                        <button wire:click="initiateBulkAction('spam')"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            Spam
                        </button>
                    @endcan
                    @can('blog.comments.delete')
                        <button wire:click="initiateBulkAction('delete')"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                    @endcan
                </div>
            </div>
        </div>
    @endif

    {{-- Comments Table --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <input wire:model.live="selectAll"
                                   type="checkbox"
                                   class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <button wire:click="sortBy('content')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-300">
                                <span>Comment</span>
                                @if($sortBy === 'content')
                                    @if($sortDir === 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    @endif
                                @endif
                            </button>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Author
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Post
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <button wire:click="sortBy('status')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-300">
                                <span>Status</span>
                                @if($sortBy === 'status')
                                    @if($sortDir === 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    @endif
                                @endif
                            </button>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <button wire:click="sortBy('created_at')" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-300">
                                <span>Date</span>
                                @if($sortBy === 'created_at')
                                    @if($sortDir === 'asc')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    @endif
                                @endif
                            </button>
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($comments as $comment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input wire:model.live="selectedComments"
                                       value="{{ $comment->id }}"
                                       type="checkbox"
                                       class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    <p class="text-sm text-gray-900 dark:text-white line-clamp-3">
                                        {{ Str::limit($comment->content, 120) }}
                                    </p>
                                    @if($comment->parent_id)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                            </svg>
                                            Reply to comment
                                        </p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $comment->author_name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $comment->author_email }}
                                        </div>
                                        @if($comment->user)
                                            <div class="text-xs text-blue-600 dark:text-blue-400">Registered User</div>
                                        @else
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Guest</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ Str::limit($comment->post->title, 40) }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $comment->post->created_at->format('M j, Y') }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @switch($comment->status)
                                    @case('pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-300">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Pending
                                        </span>
                                        @break
                                    @case('approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-300">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Approved
                                        </span>
                                        @break
                                    @case('rejected')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-300">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Rejected
                                        </span>
                                        @break
                                    @case('spam')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 dark:bg-orange-900/20 text-orange-800 dark:text-orange-300">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                            </svg>
                                            Spam
                                        </span>
                                        @break
                                @endswitch
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <div>
                                    {{ $comment->created_at->format('M j, Y') }}
                                </div>
                                <div class="text-xs">
                                    {{ $comment->created_at->format('g:i A') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    {{-- View Comment --}}
                                    <button wire:click="viewComment({{ $comment->id }})"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                            title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>

                                    {{-- Quick Actions based on status --}}
                                    @if($comment->status === 'pending')
                                        @can('blog.comments.approve')
                                            <button wire:click="approveComment({{ $comment->id }})"
                                                    class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                                    title="Approve">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                            <button wire:click="rejectComment({{ $comment->id }})"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                    title="Reject">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                            <button wire:click="markAsSpam({{ $comment->id }})"
                                                    class="text-orange-600 hover:text-orange-900 dark:text-orange-400 dark:hover:text-orange-300"
                                                    title="Mark as Spam">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                                </svg>
                                            </button>
                                        @endcan
                                    @elseif($comment->status === 'approved')
                                        @can('blog.comments.approve')
                                            <button wire:click="rejectComment({{ $comment->id }})"
                                                    class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300"
                                                    title="Unapprove">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
                                                </svg>
                                            </button>
                                        @endcan
                                    @else
                                        @can('blog.comments.approve')
                                            <button wire:click="approveComment({{ $comment->id }})"
                                                    class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                                    title="Approve">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                        @endcan
                                    @endif

                                    {{-- Delete --}}
                                    @can('blog.comments.delete')
                                        <button wire:click="deleteComment({{ $comment->id }})"
                                                wire:confirm="Are you sure you want to delete this comment?"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No comments found</h3>
                                    <p class="text-gray-500 dark:text-gray-400">
                                        @if($search || $statusFilter !== 'all' || $postFilter !== 'all')
                                            No comments match your current filters.
                                        @else
                                            No comments have been submitted yet.
                                        @endif
                                    </p>
                                    @if($search || $statusFilter !== 'all' || $postFilter !== 'all')
                                        <button wire:click="clearFilters"
                                                class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/40">
                                            Clear filters
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($comments->hasPages())
            <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                {{ $comments->links() }}
            </div>
        @endif
    </div>

    {{-- Comment Detail Modal --}}
    @if($showCommentModal && $selectedComment)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeCommentModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                        Comment Details
                                    </h3>
                                    <button wire:click="closeCommentModal"
                                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    {{-- Comment Content --}}
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Comment Content
                                            </label>
                                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                                <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $selectedComment->content }}</p>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Blog Post
                                            </label>
                                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                                <h4 class="font-medium text-gray-900 dark:text-white">{{ $selectedComment->post->title }}</h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                    Published: {{ $selectedComment->post->created_at->format('M j, Y g:i A') }}
                                                </p>
                                            </div>
                                        </div>

                                        @if($selectedComment->parent)
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    Replying To
                                                </label>
                                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ Str::limit($selectedComment->parent->content, 100) }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                        By {{ $selectedComment->parent->author_name }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Comment Meta --}}
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Author Information
                                            </label>
                                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 space-y-2">
                                                <div class="flex justify-between">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Name:</span>
                                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedComment->author_name }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Email:</span>
                                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedComment->author_email }}</span>
                                                </div>
                                                @if($selectedComment->guest_website)
                                                    <div class="flex justify-between">
                                                        <span class="text-sm text-gray-500 dark:text-gray-400">Website:</span>
                                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedComment->guest_website }}</span>
                                                    </div>
                                                @endif
                                                <div class="flex justify-between">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Type:</span>
                                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $selectedComment->user ? 'Registered User' : 'Guest' }}
                                                    </span>
                                                </div>
                                                @if($selectedComment->ip_address)
                                                    <div class="flex justify-between">
                                                        <span class="text-sm text-gray-500 dark:text-gray-400">IP Address:</span>
                                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedComment->ip_address }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Status & Dates
                                            </label>
                                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 space-y-2">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Status:</span>
                                                    @switch($selectedComment->status)
                                                        @case('pending')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-300">
                                                                Pending
                                                            </span>
                                                            @break
                                                        @case('approved')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-300">
                                                                Approved
                                                            </span>
                                                            @break
                                                        @case('rejected')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-300">
                                                                Rejected
                                                            </span>
                                                            @break
                                                        @case('spam')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 dark:bg-orange-900/20 text-orange-800 dark:text-orange-300">
                                                                Spam
                                                            </span>
                                                            @break
                                                    @endswitch
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Submitted:</span>
                                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedComment->created_at->format('M j, Y g:i A') }}</span>
                                                </div>
                                                @if($selectedComment->approved_at)
                                                    <div class="flex justify-between">
                                                        <span class="text-sm text-gray-500 dark:text-gray-400">Approved:</span>
                                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedComment->approved_at->format('M j, Y g:i A') }}</span>
                                                    </div>
                                                @endif
                                                @if($selectedComment->approvedBy)
                                                    <div class="flex justify-between">
                                                        <span class="text-sm text-gray-500 dark:text-gray-400">Approved By:</span>
                                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedComment->approvedBy->name }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Statistics
                                            </label>
                                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 space-y-2">
                                                <div class="flex justify-between">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Likes:</span>
                                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ number_format($selectedComment->likes_count) }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Replies:</span>
                                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ number_format($selectedComment->replies_count) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        @can('blog.comments.approve')
                            @if($selectedComment->status === 'pending')
                                <button wire:click="approveComment({{ $selectedComment->id }})"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Approve Comment
                                </button>
                                <button wire:click="rejectComment({{ $selectedComment->id }})"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Reject Comment
                                </button>
                            @elseif($selectedComment->status === 'approved')
                                <button wire:click="rejectComment({{ $selectedComment->id }})"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Unapprove
                                </button>
                            @else
                                <button wire:click="approveComment({{ $selectedComment->id }})"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Approve Comment
                                </button>
                            @endif
                        @endcan
                        <button wire:click="closeCommentModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Bulk Action Confirmation Modal --}}
    @if($showBulkModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeBulkModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 dark:bg-orange-900/20 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                    {{ $bulkConfirmAction }}
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $bulkConfirmText }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="executeBulkAction"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Confirm {{ $bulkConfirmAction }}
                        </button>
                        <button wire:click="closeBulkModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
