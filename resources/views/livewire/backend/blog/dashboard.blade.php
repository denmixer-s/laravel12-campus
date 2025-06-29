<div>
    <!-- Header Section -->
    <div class="mb-8">
        <div class="p-6 bg-white border rounded-lg shadow-sm border-slate-200">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="mb-2 text-3xl font-bold text-slate-800">Blog Management</h1>
                    <p class="text-slate-600">Manage your blog posts, categories, and content</p>
                </div>
                <div class="flex gap-3">
                    @can('blog.posts.create')
                        <a href="{{ route('administrator.blog.posts.create') }}"
                            class="inline-flex items-center px-4 py-2 font-medium text-white transition-colors duration-200 bg-blue-600 rounded-lg shadow-sm hover:bg-blue-700 hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            เขียนโพสต์ใหม่
                        </a>
                    @endcan
                    <button wire:click="quickAction('bulk_actions')"
                        class="inline-flex items-center px-4 py-2 font-medium text-gray-700 transition-colors bg-gray-100 rounded-lg hover:bg-gray-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Bulk Actions
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2 lg:grid-cols-4">
        {{-- Total Posts --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Posts</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $this->stats['total_posts'] }}</p>
                    <p class="mt-1 text-sm text-gray-500">
                        +{{ $this->stats['this_month_posts'] }} this month
                    </p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Published Posts --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Published</p>
                    <p class="text-3xl font-bold text-green-600">{{ $this->stats['published_posts'] }}</p>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ $this->stats['draft_posts'] }} drafts
                    </p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Comments --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Comments</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $this->stats['total_comments'] }}</p>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ $this->stats['pending_comments'] }} pending
                    </p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Views --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Views</p>
                    <p class="text-3xl font-bold text-orange-600">{{ number_format($this->stats['total_views']) }}</p>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ $this->stats['total_categories'] }} categories
                    </p>
                </div>
                <div class="p-3 bg-orange-100 rounded-full">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions & Charts Row --}}
    <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
        {{-- Quick Actions --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Quick Actions</h3>
            <div class="space-y-3">
                @can('blog.posts.create')
                    <button wire:click="quickAction('create_post')"
                        class="flex items-center justify-between w-full p-3 text-left transition-colors rounded-lg bg-gray-50 hover:bg-gray-100">
                        <div class="flex items-center">
                            <div class="p-2 mr-3 bg-blue-100 rounded-lg">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <span class="font-medium text-gray-900">Create New Post</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                @endcan

                <button wire:click="quickAction('bulk_actions')"
                    class="flex items-center justify-between w-full p-3 text-left transition-colors rounded-lg bg-gray-50 hover:bg-gray-100">
                    <div class="flex items-center">
                        <div class="p-2 mr-3 bg-green-100 rounded-lg">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <span class="font-medium text-gray-900">Bulk Actions</span>
                    </div>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                @can('blog.categories.view')
                    <button wire:click="quickAction('manage_categories')"
                        class="flex items-center justify-between w-full p-3 text-left transition-colors rounded-lg bg-gray-50 hover:bg-gray-100">
                        <div class="flex items-center">
                            <div class="p-2 mr-3 bg-purple-100 rounded-lg">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                                </svg>
                            </div>
                            <span class="font-medium text-gray-900">Manage Categories</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                @endcan

                @can('blog.comments.view')
                    <button wire:click="quickAction('manage_comments')"
                        class="flex items-center justify-between w-full p-3 text-left transition-colors rounded-lg bg-gray-50 hover:bg-gray-100">
                        <div class="flex items-center">
                            <div class="p-2 mr-3 bg-yellow-100 rounded-lg">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <span class="font-medium text-gray-900">Moderate Comments</span>
                        </div>
                        @if ($this->stats['pending_comments'] > 0)
                            <span class="px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">
                                {{ $this->stats['pending_comments'] }}
                            </span>
                        @else
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        @endif
                    </button>
                @endcan

                @can('blog.analytics.view')
                    <button wire:click="quickAction('view_analytics')"
                        class="flex items-center justify-between w-full p-3 text-left transition-colors rounded-lg bg-gray-50 hover:bg-gray-100">
                        <div class="flex items-center">
                            <div class="p-2 mr-3 bg-indigo-100 rounded-lg">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <span class="font-medium text-gray-900">View Analytics</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                @endcan
            </div>
        </div>

        {{-- Activity Chart --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Activity Overview</h3>
                <div class="flex items-center space-x-4 text-sm">
                    <div class="flex items-center">
                        <div class="w-3 h-3 mr-2 bg-blue-500 rounded-full"></div>
                        <span class="text-gray-600">Posts</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 mr-2 bg-green-500 rounded-full"></div>
                        <span class="text-gray-600">Comments</span>
                    </div>
                </div>
            </div>

            {{-- Simple Chart --}}
            <div class="space-y-3">
                @foreach ($this->chartData as $day)
                    <div class="flex items-center space-x-3">
                        <div class="w-12 text-xs text-gray-500">{{ $day['date'] }}</div>
                        <div class="flex flex-1 space-x-1">
                            <div class="flex items-center space-x-1">
                                <div class="h-6 bg-blue-200 rounded" style="width: {{ $day['posts'] * 20 + 5 }}px">
                                </div>
                                <span class="text-xs text-gray-600">{{ $day['posts'] }}</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <div class="h-6 bg-green-200 rounded"
                                    style="width: {{ $day['comments'] * 20 + 5 }}px"></div>
                                <span class="text-xs text-gray-600">{{ $day['comments'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
        {{-- Recent Posts --}}
        <div class="p-6 bg-white border border-gray-200 rounded-lg">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Recent Posts</h3>
                <a href="{{ route('administrator.blog.posts.index') }}"
                    class="text-sm text-blue-600 hover:text-blue-800">View all</a>
            </div>
            <div class="space-y-4">
                @forelse($this->recentPosts as $post)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            @if ($post->getFirstMediaUrl('featured_image'))
                                <img src="{{ $post->getFirstMediaUrl('featured_image', 'thumb') }}"
                                    alt="{{ $post->title }}" class="object-cover w-12 h-12 rounded-lg">
                            @else
                                <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-lg">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $post->title }}</p>
                            <div class="flex items-center mt-1 space-x-2">
                                <span
                                    class="text-xs text-gray-500">{{ $post->category->name ?? 'Uncategorized' }}</span>
                                <span class="text-xs text-gray-300">•</span>
                                <span class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($post->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-center">
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-sm text-gray-500">No posts yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Status Distribution --}}
    <div class="p-6 mb-4 bg-white border border-gray-200 rounded-lg">
        <h3 class="mb-4 text-lg font-semibold text-gray-900">Posts Status Distribution</h3>
        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 mb-2 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-2xl font-bold text-green-600">{{ $this->stats['published_posts'] }}</div>
                <div class="text-sm text-gray-500">Published</div>
            </div>

            <div class="text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 mb-2 bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div class="text-2xl font-bold text-yellow-600">{{ $this->stats['draft_posts'] }}</div>
                <div class="text-sm text-gray-500">Drafts</div>
            </div>

            <div class="text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 mb-2 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-2xl font-bold text-blue-600">{{ $this->stats['scheduled_posts'] }}</div>
                <div class="text-sm text-gray-500">Scheduled</div>
            </div>

            <div class="text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 mb-2 bg-gray-100 rounded-full">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8l6 6 6-6" />
                    </svg>
                </div>
                <div class="text-2xl font-bold text-gray-600">{{ $this->stats['archived_posts'] }}</div>
                <div class="text-sm text-gray-500">Archived</div>
            </div>
        </div>
    </div>


    {{-- Recent Comments --}}
    <div class="p-6 bg-white border border-gray-200 rounded-lg">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Recent Comments</h3>
            <a href="{{ route('administrator.blog.comments.index') }}"
                class="text-sm text-blue-600 hover:text-blue-800">View all</a>
        </div>
        <div class="space-y-4">
            @forelse($this->recentComments as $comment)
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full">
                            @if ($comment->user)
                                <span class="text-xs font-medium text-gray-600">
                                    {{ substr($comment->user->name, 0, 1) }}
                                </span>
                            @else
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            @endif
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900">{{ Str::limit($comment->content, 60) }}</p>
                        <div class="flex items-center mt-1 space-x-2">
                            <span class="text-xs text-gray-500">
                                {{ $comment->user ? $comment->user->name : $comment->guest_name }}
                            </span>
                            <span class="text-xs text-gray-300">•</span>
                            <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    {{ $comment->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($comment->status) }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="py-6 text-center">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <p class="text-sm text-gray-500">No comments yet</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
