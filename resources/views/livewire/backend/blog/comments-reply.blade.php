{{-- resources/views/livewire/backend/blog/comments-reply.blade.php --}}
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Reply to Comment</h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                Reply to comment on "{{ $comment->post->title }}"
            </p>
        </div>
        <div class="mt-4 sm:mt-0 sm:flex sm:space-x-3">
            <a href="{{ route('administrator.blog.comments.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                Comments List
            </a>
            <a href="{{ route('administrator.blog.dashboard') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Dashboard
            </a>
        </div>
    </div>

    {{-- Thread Stats --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Total Replies
                            </dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ number_format($stats['total_replies']) }}
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Approved
                            </dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ number_format($stats['approved_replies']) }}
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Pending
                            </dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ number_format($stats['pending_replies']) }}
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                Rejected
                            </dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ number_format($stats['rejected_replies']) }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Reply Form --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                Write a Reply
            </h3>

            <form wire:submit="submitReply" class="space-y-6">
                {{-- Error Messages --}}
                @if ($errors->any())
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                    There were errors with your submission
                                </h3>
                                <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Reply As User/Guest Toggle --}}
                <div class="flex items-center space-x-4">
                    <label class="flex items-center">
                        <input wire:model.live="replyAsUser" type="radio" value="1" name="reply_type"
                            class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Reply as
                            {{ auth()->user()->name }}</span>
                    </label>
                    <label class="flex items-center">
                        <input wire:model.live="replyAsUser" type="radio" value="0" name="reply_type"
                            class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Reply as guest</span>
                    </label>
                </div>

                {{-- Guest Information (when not replying as user) --}}
                @if (!$replyAsUser)
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div>
                            <label for="guestName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Name <span class="text-red-500">*</span>
                            </label>
                            <input wire:model="guestName" type="text" id="guestName"
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Your name">
                            @error('guestName')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="guestEmail"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input wire:model="guestEmail" type="email" id="guestEmail"
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="your@email.com">
                            @error('guestEmail')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="guestWebsite"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Website
                            </label>
                            <input wire:model="guestWebsite" type="url" id="guestWebsite"
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="https://yourwebsite.com">
                            @error('guestWebsite')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endif

                {{-- Reply Content --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="replyContent" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Reply Content <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center space-x-2">
                            <button wire:click="togglePreview" type="button"
                                class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                                {{ $showPreview ? 'Hide Preview' : 'Show Preview' }}
                            </button>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ strlen($replyContent) }}/5000
                            </span>
                        </div>
                    </div>

                    @if ($showPreview && $previewContent)
                        <div
                            class="mb-4 p-4 border border-gray-200 dark:border-gray-700 rounded-md bg-gray-50 dark:bg-gray-700">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preview:</h4>
                            <div class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">
                                {{ $previewContent }}</div>
                        </div>
                    @endif

                    <textarea wire:model.live="replyContent" id="replyContent" rows="6"
                        class="block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="Write your reply here..."></textarea>
                    @error('replyContent')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Advanced Options --}}
                <div>
                    <button wire:click="toggleAdvancedOptions" type="button"
                        class="flex items-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                        <svg class="w-4 h-4 mr-1 transform {{ $showAdvancedOptions ? 'rotate-90' : '' }} transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        {{ $showAdvancedOptions ? 'Hide' : 'Show' }} Advanced Options
                    </button>

                    @if ($showAdvancedOptions)
                        <div
                            class="mt-4 space-y-4 p-4 border border-gray-200 dark:border-gray-700 rounded-md bg-gray-50 dark:bg-gray-700">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div class="flex items-center">
                                    <input wire:model="notifyAuthor" id="notifyAuthor" type="checkbox"
                                        class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <label for="notifyAuthor" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                        Notify original comment author
                                    </label>
                                </div>

                                @can('blog.comments.approve')
                                    <div class="flex items-center">
                                        <input wire:model="autoApprove" id="autoApprove" type="checkbox"
                                            class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <label for="autoApprove" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                            Auto-approve this reply
                                        </label>
                                    </div>
                                @endcan
                            </div>

                            @can('blog.comments.approve')
                                <div>
                                    <label for="replyStatus"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Reply Status
                                    </label>
                                    <select wire:model="replyStatus" id="replyStatus"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                        <option value="pending">Pending Review</option>
                                        <option value="approved">Approved</option>
                                    </select>
                                </div>
                            @endcan
                        </div>
                    @endif
                </div>

                {{-- Submit Buttons --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <button type="submit" wire:loading.attr="disabled"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove>
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                Submit Reply
                            </span>
                            <span wire:loading>
                                <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Submitting...
                            </span>
                        </button>

                        <button wire:click="quickReply('Thanks for your comment!')" type="button"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Quick Thanks
                        </button>
                    </div>

                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Press Ctrl+Enter to submit
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Original Comment & Thread --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    Comment Thread
                </h3>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <label for="perPage" class="text-sm text-gray-700 dark:text-gray-300">Show:</label>
                        <select wire:model.live="perPage" id="perPage"
                            class="text-sm border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                    <div class="flex items-center space-x-2">
                        <label class="text-sm text-gray-700 dark:text-gray-300">Sort:</label>
                        <button wire:click="sortThread('created_at')"
                            class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                            Date {{ $threadSortBy === 'created_at' ? ($threadSortDir === 'asc' ? '↑' : '↓') : '' }}
                        </button>
                        <button wire:click="sortThread('status')"
                            class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                            Status {{ $threadSortBy === 'status' ? ($threadSortDir === 'asc' ? '↑' : '↓') : '' }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                @forelse($thread as $threadComment)
                    <div
                        class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 {{ $getReplyDepthClass($threadComment) }}">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-8 h-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ strtoupper(substr($getCommentAuthorName($threadComment), 0, 1)) }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $getCommentAuthorName($threadComment) }}
                                        </span>
                                        @if ($threadComment->user)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-300">
                                                Registered
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-900/20 text-gray-800 dark:text-gray-300">
                                                Guest
                                            </span>
                                        @endif

                                        {{-- Status Badge --}}
                                        @switch($threadComment->status)
                                            @case('pending')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-300">
                                                    Pending
                                                </span>
                                            @break

                                            @case('approved')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-300">
                                                    Approved
                                                </span>
                                            @break

                                            @case('rejected')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-300">
                                                    Rejected
                                                </span>
                                            @break

                                            @case('spam')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 dark:bg-orange-900/20 text-orange-800 dark:text-orange-300">
                                                    Spam
                                                </span>
                                            @break
                                        @endswitch

                                        @if ($threadComment->id === $comment->id)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 dark:bg-purple-900/20 text-purple-800 dark:text-purple-300">
                                                Original
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $threadComment->created_at->format('M j, Y g:i A') }}
                                        </span>

                                        {{-- Action Buttons --}}
                                        @if ($canModerateComment($threadComment))
                                            <div class="flex items-center space-x-1">
                                                @if ($threadComment->status === 'pending')
                                                    @can('blog.comments.approve')
                                                        <button wire:click="approveComment({{ $threadComment->id }})"
                                                            class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-200"
                                                            title="Approve">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </button>
                                                        <button wire:click="rejectComment({{ $threadComment->id }})"
                                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200"
                                                            title="Reject">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    @endcan
                                                @elseif($threadComment->status === 'approved')
                                                    @can('blog.comments.approve')
                                                        <button wire:click="rejectComment({{ $threadComment->id }})"
                                                            class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-200"
                                                            title="Unapprove">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                                                            </svg>
                                                        </button>
                                                    @endcan
                                                @else
                                                    @can('blog.comments.approve')
                                                        <button wire:click="approveComment({{ $threadComment->id }})"
                                                            class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-200"
                                                            title="Approve">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </button>
                                                    @endcan
                                                @endif

                                                @can('blog.comments.delete')
                                                    <button wire:click="deleteComment({{ $threadComment->id }})"
                                                        wire:confirm="Are you sure you want to delete this comment?"
                                                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200"
                                                        title="Delete">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                @endcan
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <p class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">
                                        {{ $threadComment->content }}</p>
                                </div>

                                @if ($threadComment->guest_email && !$threadComment->user)
                                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                        Email: {{ $threadComment->guest_email }}
                                        @if ($threadComment->guest_website)
                                            • Website: <a href="{{ $threadComment->guest_website }}" target="_blank"
                                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">{{ $threadComment->guest_website }}</a>
                                        @endif
                                    </div>
                                @endif

                                @if ($threadComment->approved_at && $threadComment->approvedBy)
                                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                        Approved by {{ $threadComment->approvedBy->name }} on
                                        {{ $threadComment->approved_at->format('M j, Y g:i A') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No comments found</h3>
                            <p class="text-gray-500 dark:text-gray-400">This comment thread appears to be empty.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if ($thread->hasPages())
                    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                        {{ $thread->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Quick Reply Modal --}}
        @if ($showQuickReplyModal)
            <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                        wire:click="closeQuickReplyModal"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div
                        class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <form wire:submit="submitQuickReply">
                            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="w-full">
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                                id="modal-title">
                                                Quick Reply
                                            </h3>
                                            <button wire:click="closeQuickReplyModal" type="button"
                                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div>
                                            <label for="quickReplyContent"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Reply Content
                                            </label>
                                            <textarea wire:model="replyContent" id="quickReplyContent" rows="4"
                                                class="block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                                placeholder="Write your quick reply..."></textarea>
                                            @error('replyContent')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Submit Reply
                                </button>
                                <button wire:click="closeQuickReplyModal" type="button"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        {{-- Original Comment Context (Fixed at bottom) --}}
        <div
            class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-4 shadow-lg lg:static lg:shadow-none lg:border-0 lg:bg-transparent lg:dark:bg-transparent">
            <div class="max-w-7xl mx-auto">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-2">
                        Replying to comment by {{ $getCommentAuthorName($comment) }}:
                    </h4>
                    <div
                        class="text-sm text-blue-800 dark:text-blue-200 bg-white dark:bg-gray-800 rounded p-3 border border-blue-200 dark:border-blue-700">
                        {{ Str::limit($comment->content, 150) }}
                    </div>
                    <div class="mt-2 text-xs text-blue-700 dark:text-blue-300">
                        Posted {{ $comment->created_at->diffForHumans() }} on "{{ $comment->post->title }}"
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Keyboard Shortcuts --}}
    <script>
        document.addEventListener('livewire:init', () => {
            // Ctrl+Enter to submit reply
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                    e.preventDefault();
                    @this.call('submitReply');
                }
            });
        });
    </script>
</div>
