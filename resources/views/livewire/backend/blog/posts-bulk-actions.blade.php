<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-800 mb-2">การจัดการโพสต์แบบกลุ่ม</h1>
                        <p class="text-slate-600">เลือกและจัดการโพสต์หลายรายการพร้อมกัน</p>
                    </div>

                    @can('blog.posts.create')
                        <a href="{{ route('administrator.blog.posts.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            เขียนโพสต์ใหม่
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-slate-600">โพสต์ทั้งหมด</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $this->posts->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-slate-600">เผยแพร่แล้ว</p>
                        <p class="text-2xl font-bold text-slate-800">
                            {{ \App\Models\BlogPost::where('status', 'published')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-amber-100 rounded-lg">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-slate-600">ร่าง</p>
                        <p class="text-2xl font-bold text-slate-800">
                            {{ \App\Models\BlogPost::where('status', 'draft')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-slate-600">เลือกแล้ว</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $this->selectedPostsCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="flex flex-col lg:flex-row gap-4">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <div class="relative">
                            <input wire:model.live.debounce.300ms="search" type="text"
                                placeholder="ค้นหาโพสต์จากชื่อเรื่อง เนื้อหา หรือบทสรุป..."
                                class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="lg:w-48">
                        <select wire:model.live="filterStatus"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">ทุกสถานะ</option>
                            <option value="draft">ร่าง</option>
                            <option value="published">เผยแพร่</option>
                            <option value="scheduled">กำหนดเผยแพร่</option>
                            <option value="archived">เก็บคลัง</option>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div class="lg:w-48">
                        <select wire:model.live="filterCategoryId"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">ทุกหมวดหมู่</option>
                            @foreach ($this->categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Per Page Selector -->
                    <div class="lg:w-32">
                        <select wire:model.live="perPage"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="5">5 per page</option>
                            <option value="10">10 per page</option>
                            <option value="15">15 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                        </select>
                    </div>

                    <!-- Clear Filters -->
                    @if ($search || $filterStatus || $filterCategoryId)
                        <button wire:click="clearFilters"
                            class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Bulk Actions Panel -->
        @if ($showBulkPanel)
            <div class="mb-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <span class="text-sm font-medium text-blue-900">
                                เลือกแล้ว {{ $this->selectedPostsCount }} รายการ
                            </span>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            @can('blog.posts.edit')
                                <button wire:click="setBulkAction('publish')"
                                    class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    เผยแพร่
                                </button>
                                <button wire:click="setBulkAction('unpublish')"
                                    class="inline-flex items-center px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 4h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17.294 15M10 14L4.5 9 9 4.25" />
                                    </svg>
                                    ยกเลิกเผยแพร่
                                </button>
                                <button wire:click="setBulkAction('archive')"
                                    class="inline-flex items-center px-3 py-2 bg-slate-600 hover:bg-slate-700 text-white text-sm font-medium rounded transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 8l6 6 6-6" />
                                    </svg>
                                    เก็บคลัง
                                </button>
                                <button wire:click="setBulkAction('change_category')"
                                    class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    เปลี่ยนหมวดหมู่
                                </button>
                            @endcan
                            @can('blog.posts.delete')
                                <button wire:click="setBulkAction('delete')"
                                    class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    ลบ
                                </button>
                            @endcan

                            <button wire:click="resetBulkAction"
                                class="inline-flex items-center px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                ยกเลิก
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Posts Table -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            @if ($this->posts->count() > 0)
                <!-- Table Header -->
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <div class="grid grid-cols-12 gap-4 items-center text-sm font-medium text-slate-700">
                        <div class="col-span-1">
                            <input type="checkbox" wire:model.live="selectAll"
                                class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        </div>
                        <div class="col-span-4">
                            <button wire:click="sortBy('title')"
                                class="flex items-center hover:text-slate-900 transition-colors">
                                ชื่อโพสต์
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </button>
                        </div>
                        <div class="col-span-2">หมวดหมู่</div>
                        <div class="col-span-2 text-center">สถานะ</div>
                        <div class="col-span-2">
                            <button wire:click="sortBy('created_at')"
                                class="flex items-center hover:text-slate-900 transition-colors">
                                วันที่สร้าง
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </button>
                        </div>
                        <div class="col-span-1 text-center">การดำเนินการ</div>
                    </div>
                </div>

                <!-- Table Body -->
                <div class="divide-y divide-slate-200">
                    @foreach ($this->posts as $post)
                        <div class="px-6 py-4 hover:bg-slate-50 transition-colors"
                            wire:key="post-{{ $post->id }}">
                            <div class="grid grid-cols-12 gap-4 items-center">
                                <!-- Checkbox -->
                                <div class="col-span-1">
                                    <input type="checkbox" value="{{ $post->id }}"
                                        wire:model.live="selectedPosts"
                                        class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                </div>

                                <!-- Post Title & Info -->
                                <div class="col-span-4">
                                    <div class="flex items-start space-x-3">
                                        <!-- Featured Image Thumbnail -->
                                        <div class="flex-shrink-0">
                                            @if ($post->getFirstMediaUrl('featured_image'))
                                                <img src="{{ $post->getFirstMediaUrl('featured_image', 'thumb') }}"
                                                    alt="{{ $post->title }}"
                                                    class="w-12 h-12 object-cover rounded-lg border border-slate-200">
                                            @else
                                                <div
                                                    class="w-12 h-12 bg-slate-100 rounded-lg border border-slate-200 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-slate-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-medium text-slate-900 truncate">
                                                {{ $post->title }}</h3>
                                            <div class="mt-1 flex items-center space-x-2">
                                                <span class="text-xs text-slate-500">/{{ $post->slug }}</span>
                                                <a href="{{ route('blog.show', $post->slug) }}" target="_blank"
                                                    class="text-xs text-blue-600 hover:text-blue-800 transition-colors"
                                                    title="ดูโพสต์">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                </a>
                                            </div>
                                            @if ($post->excerpt)
                                                <p class="mt-1 text-xs text-slate-600 line-clamp-2">
                                                    {{ Str::limit(strip_tags($post->excerpt), 80) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Category -->
                                <div class="col-span-2">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                        {{ $post->category->name ?? 'ไม่มีหมวดหมู่' }}
                                    </span>
                                </div>

                                <!-- Status -->
                                <div class="col-span-2">
                                    <div class="flex items-center justify-center">
                                        @php
                                            $statusConfig = [
                                                'draft' => [
                                                    'bg' => 'bg-slate-100',
                                                    'text' => 'text-slate-800',
                                                    'label' => 'ร่าง',
                                                ],
                                                'published' => [
                                                    'bg' => 'bg-green-100',
                                                    'text' => 'text-green-800',
                                                    'label' => 'เผยแพร่',
                                                ],
                                                'scheduled' => [
                                                    'bg' => 'bg-blue-100',
                                                    'text' => 'text-blue-800',
                                                    'label' => 'กำหนดเผยแพร่',
                                                ],
                                                'archived' => [
                                                    'bg' => 'bg-amber-100',
                                                    'text' => 'text-amber-800',
                                                    'label' => 'เก็บคลัง',
                                                ],
                                            ];
                                            $config = $statusConfig[$post->status] ?? $statusConfig['draft'];
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                                            {{ $config['label'] }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Created Date -->
                                <div class="col-span-2">
                                    <div class="text-sm text-slate-900">{{ $post->created_at->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-slate-500">{{ $post->created_at->format('H:i') }}</div>
                                </div>

                                <!-- Actions -->
                                <div class="col-span-1">
                                    <div class="flex items-center justify-center">
                                        @can('blog.posts.edit')
                                            <a href="{{ route('administrator.blog.posts.edit', $post) }}"
                                                class="inline-flex items-center px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded transition-colors"
                                                title="แก้ไขโพสต์">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-lg font-medium text-slate-900 mb-2">ไม่พบโพสต์</h3>
                    <p class="text-slate-500 mb-4">
                        {{ $search || $filterStatus || $filterCategoryId ? 'ลองปรับเงื่อนไขการค้นหา' : 'เริ่มต้นด้วยการสร้างโพสต์แรก' }}
                    </p>
                    @can('blog.posts.create')
                        <a href="{{ route('administrator.blog.posts.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            เขียนโพสต์แรก
                        </a>
                    @endcan
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if ($this->posts->hasPages())
            <div class="mt-8">
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                    {{ $this->posts->links() }}
                </div>
            </div>
        @endif

        <!-- Bulk Action Confirmation Modal -->
        @if ($confirmingBulkAction)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                wire:key="bulk-modal-{{ $bulkAction }}">
                <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 shadow-xl">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-medium text-gray-900">ยืนยันการดำเนินการ</h3>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-600 mb-3">{{ $confirmMessage }}</p>

                        <!-- Additional inputs for specific actions -->
                        @if ($bulkAction === 'change_category')
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">เลือกหมวดหมู่ใหม่</label>
                                <select wire:model="bulkCategoryId"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">เลือกหมวดหมู่</option>
                                    @foreach ($this->categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('bulkCategoryId')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        @if (in_array($bulkAction, ['add_tags', 'remove_tags']))
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">เลือกแท็ก</label>
                                <div class="max-h-40 overflow-y-auto border border-gray-300 rounded-lg p-2">
                                    @foreach ($this->tags as $tag)
                                        <label class="flex items-center py-1">
                                            <input type="checkbox" value="{{ $tag->id }}"
                                                wire:model="bulkTagIds" class="rounded border-gray-300 mr-2">
                                            <span class="text-sm text-gray-700">{{ $tag->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($bulkAction === 'delete')
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mt-3">
                                <p class="text-sm text-red-800">
                                    <strong>⚠️ คำเตือน:</strong> การดำเนินการนี้ไม่สามารถยกเลิกได้
                                    และจะลบโพสต์และไฟล์ที่เกี่ยวข้องออกอย่างถาวร
                                </p>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button wire:click="cancelBulkAction"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                            ยกเลิก
                        </button>
                        <button wire:click="executeBulkAction" wire:loading.attr="disabled"
                            wire:target="executeBulkAction"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white font-medium rounded-lg transition-colors disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="executeBulkAction">ยืนยัน</span>
                            <span wire:loading wire:target="executeBulkAction" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                กำลังดำเนินการ...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Loading Overlay -->
        <div wire:loading.flex wire:target="search,filterStatus,filterCategoryId,perPage,sortBy"
            class="fixed inset-0 z-40 bg-white bg-opacity-75 flex items-center justify-center">
            <div class="flex items-center space-x-2">
                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                <span class="text-sm text-slate-600">กำลังโหลด...</span>
            </div>
        </div>
    </div>

    <!-- Debug Information (Remove in production) -->
    @if (app()->environment('local'))
        <div class="fixed bottom-4 right-4 bg-black bg-opacity-75 text-white p-3 rounded-lg text-xs max-w-sm">
            <div><strong>Debug Info:</strong></div>
            <div>confirmingBulkAction: {{ $confirmingBulkAction ? 'true' : 'false' }}</div>
            <div>bulkAction: {{ $bulkAction ?? 'null' }}</div>
            <div>selectedPosts: {{ count($selectedPosts) }}</div>
            <div>showBulkPanel: {{ $showBulkPanel ? 'true' : 'false' }}</div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        console.log('Livewire initialized for PostsBulkActions component');

        Livewire.on('postsBulkActionCompleted', (event) => {
            console.log('Bulk action completed:', event);
        });
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && @json($confirmingBulkAction)) {
            @this.call('cancelBulkAction');
        }
    });
</script>
