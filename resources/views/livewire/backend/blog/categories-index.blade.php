<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-4">
                        <li>
                            <div class="flex">
                                <a href="{{ route('administrator.blog.dashboard') }}"
                                    class="text-sm font-medium text-gray-500 hover:text-gray-700">
                                    <svg class="flex-shrink-0 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                    </svg>
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="ml-4 text-lg font-semibold text-gray-900">จัดการหมวดหมู่</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                @can('blog.categories.create')
                    <a href="{{ route('administrator.blog.categories.create') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        เพิ่มหมวดหมู่
                    </a>
                @endcan
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <!-- Search -->
                    <div class="sm:col-span-2">
                        <label for="search" class="sr-only">ค้นหา</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input wire:model.live.debounce.300ms="search" type="text"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="ค้นหาหมวดหมู่...">
                        </div>
                    </div>

                    <!-- Per Page -->
                    <div>
                        <label for="perPage" class="sr-only">จำนวนต่อหน้า</label>
                        <select wire:model.live="perPage"
                            class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="10">10 ต่อหน้า</option>
                            <option value="15">15 ต่อหน้า</option>
                            <option value="25">25 ต่อหน้า</option>
                            <option value="50">50 ต่อหน้า</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions Bar -->
        @if ($showBulkActions)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6" x-data="{ open: false }">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="text-sm font-medium text-blue-800">
                            เลือกแล้ว {{ count($selectedCategories) }} รายการ
                        </span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <select wire:model="bulkAction"
                            class="block w-full pl-3 pr-10 py-2 text-base border border-blue-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md bg-white">
                            <option value="">เลือกการดำเนินการ</option>
                            @can('blog.categories.edit')
                                <option value="change_parent">เปลี่ยนหมวดหมู่แม่</option>
                                <option value="activate">เปิดใช้งาน</option>
                                <option value="deactivate">ปิดใช้งาน</option>
                                <option value="merge">รวมหมวดหมู่</option>
                            @endcan
                            @can('blog.categories.delete')
                                <option value="delete">ลบ</option>
                            @endcan
                        </select>

                        <button wire:click="performBulkAction" :disabled="!$wire.bulkAction"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            ดำเนินการ
                        </button>

                        <button wire:click="resetBulkActions"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            ยกเลิก
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Categories Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 w-10">
                                <input type="checkbox" wire:model.live="selectAll"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                wire:click="sortBy('sort_order')">
                                <div class="flex items-center space-x-1">
                                    <span>ลำดับ</span>
                                    @if ($sortBy === 'sort_order')
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            @if ($sortDirection === 'asc')
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd" />
                                            @else
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            @endif
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                wire:click="sortBy('name')">
                                <div class="flex items-center space-x-1">
                                    <span>ชื่อหมวดหมู่</span>
                                    @if ($sortBy === 'name')
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            @if ($sortDirection === 'asc')
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd" />
                                            @else
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            @endif
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                หมวดหมู่แม่
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                wire:click="sortBy('posts_count')">
                                <div class="flex items-center space-x-1">
                                    <span>จำนวนโพสต์</span>
                                    @if ($sortBy === 'posts_count')
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            @if ($sortDirection === 'asc')
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd" />
                                            @else
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            @endif
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                สถานะ
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
                                wire:click="sortBy('created_at')">
                                <div class="flex items-center space-x-1">
                                    <span>วันที่สร้าง</span>
                                    @if ($sortBy === 'created_at')
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            @if ($sortDirection === 'asc')
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd" />
                                            @else
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            @endif
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">การจัดการ</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($categories as $category)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap w-10">
                                    <input type="checkbox" wire:model.live="selectedCategories"
                                        value="{{ $category->id }}"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-sm font-medium text-white"
                                        style="background-color: {{ $category->color ?? '#3B82F6' }}">
                                        {{ $category->sort_order ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if ($category->getFeaturedImageUrl('thumb'))
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-lg object-cover"
                                                    src="{{ $category->getFeaturedImageUrl('thumb') }}"
                                                    alt="{{ $category->name }}">
                                            </div>
                                        @else
                                            <div class="flex-shrink-0 h-10 w-10 rounded-lg flex items-center justify-center"
                                                style="background-color: {{ $category->color ?? '#3B82F6' }}20">
                                                @if ($category->icon)
                                                    <i class="{{ $category->icon }} text-lg"
                                                        style="color: {{ $category->color ?? '#3B82F6' }}"></i>
                                                @else
                                                    <svg class="h-6 w-6"
                                                        style="color: {{ $category->color ?? '#3B82F6' }}"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                    </svg>
                                                @endif
                                            </div>
                                        @endif
                                        <div class="ml-4">
                                            <div class="flex items-center">
                                                <div class="text-sm font-medium text-gray-900">{{ $category->name }}
                                                </div>
                                                @if ($category->parent_id)
                                                    <span class="ml-2 text-xs text-gray-400">→
                                                        {{ $category->parent->name ?? 'ไม่พบหมวดหมู่แม่' }}</span>
                                                @endif
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $category->slug }}</div>
                                            @if ($category->description)
                                                <div class="text-xs text-gray-400 mt-1">
                                                    {{ Str::limit($category->description, 50) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if ($category->parent)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                            style="background-color: {{ $category->parent->color ?? '#3B82F6' }}20; color: {{ $category->parent->color ?? '#3B82F6' }}">
                                            @if ($category->parent->icon)
                                                <i class="{{ $category->parent->icon }} mr-1"></i>
                                            @endif
                                            {{ $category->parent->name }}
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-blue-400" fill="currentColor"
                                                viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            หมวดหลัก
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $category->posts_count ?? 0 }}</div>
                                        @if ($category->children_count > 0)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                {{ $category->children_count }} หมวดย่อย
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($category->is_active)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor"
                                                viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            เปิดใช้งาน
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-red-400" fill="currentColor"
                                                viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            ปิดใช้งาน
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $category->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('blog.category', $category->slug) }}" target="_blank"
                                            class="text-gray-400 hover:text-gray-600" title="ดูหน้าหมวดหมู่">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>

                                        @can('blog.categories.edit')
                                            <a href="{{ route('administrator.blog.categories.edit', $category) }}"
                                                class="text-blue-600 hover:text-blue-900" title="แก้ไข">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                        @endcan

                                        @can('blog.categories.delete')
                                            @if ($category->posts()->count() === 0)
                                                <button
                                                    wire:click="$dispatch('confirmDelete', { id: {{ $category->id }} })"
                                                    class="text-red-600 hover:text-red-900" title="ลบ">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            @else
                                                <span class="text-gray-300"
                                                    title="ไม่สามารถลบได้ เนื่องจากมีโพสต์ในหมวดหมู่นี้">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </span>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">ไม่พบหมวดหมู่</h3>
                                        <p class="mt-1 text-sm text-gray-500">เริ่มต้นสร้างหมวดหมู่แรกของคุณ</p>
                                        @can('blog.categories.create')
                                            <div class="mt-6">
                                                <a href="{{ route('administrator.blog.categories.create') }}"
                                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                    เพิ่มหมวดหมู่
                                                </a>
                                            </div>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if ($categories->hasPages())
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 mt-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    @if ($categories->previousPageUrl())
                        <a href="{{ $categories->previousPageUrl() }}"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            ก่อนหน้า
                        </a>
                    @endif
                    @if ($categories->nextPageUrl())
                        <a href="{{ $categories->nextPageUrl() }}"
                            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            ถัดไป
                        </a>
                    @endif
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            แสดง
                            <span class="font-medium">{{ $categories->firstItem() }}</span>
                            ถึง
                            <span class="font-medium">{{ $categories->lastItem() }}</span>
                            จาก
                            <span class="font-medium">{{ $categories->total() }}</span>
                            รายการ
                        </p>
                    </div>
                    <div>
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{ show: @entangle('showDeleteModal') }" x-show="show" class="fixed inset-0 overflow-y-auto z-50" x-cloak>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="show" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L5.232 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">ยืนยันการลบหมวดหมู่</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                คุณแน่ใจหรือไม่ที่จะลบหมวดหมู่ที่เลือก? การดำเนินการนี้ไม่สามารถยกเลิกได้
                            </p>
                            <div class="mt-4">
                                <label for="deleteConfirmation" class="block text-sm font-medium text-gray-700">
                                    พิมพ์ <span class="font-bold text-red-600">DELETE</span> เพื่อยืนยัน
                                </label>
                                <input wire:model="deleteConfirmation" type="text" id="deleteConfirmation"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm"
                                    placeholder="DELETE">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button wire:click="confirmBulkDelete"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        ลบ
                    </button>
                    <button wire:click="$set('showDeleteModal', false)"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
                        ยกเลิก
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Merge Categories Modal -->
    <div x-data="{ show: @entangle('showMergeModal') }" x-show="show" class="fixed inset-0 overflow-y-auto z-50" x-cloak>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="show" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">รวมหมวดหมู่</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 mb-4">
                                เลือกหมวดหมู่ที่ต้องการรวมหมวดหมู่ที่เลือกเข้าด้วย
                            </p>
                            <div>
                                <label for="mergeIntoCategoryId" class="block text-sm font-medium text-gray-700">
                                    รวมเข้ากับหมวดหมู่
                                </label>
                                <select wire:model="mergeIntoCategoryId" id="mergeIntoCategoryId"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="">เลือกหมวดหมู่</option>
                                    @foreach ($this->availableMergeCategories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button wire:click="mergeCategories"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        รวมหมวดหมู่
                    </button>
                    <button wire:click="$set('showMergeModal', false)"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
                        ยกเลิก
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Parent Modal (shown when bulk action is change_parent) -->
    @if ($bulkAction === 'change_parent')
        <div class="fixed inset-0 overflow-y-auto z-50">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">เปลี่ยนหมวดหมู่แม่</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-4">
                                    เลือกหมวดหมู่แม่ใหม่สำหรับหมวดหมู่ที่เลือก
                                </p>
                                <div>
                                    <label for="newParentId" class="block text-sm font-medium text-gray-700">
                                        หมวดหมู่แม่
                                    </label>
                                    <select wire:model="newParentId" id="newParentId"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                        <option value="null">ไม่มีหมวดหมู่แม่ (หมวดหมู่หลัก)</option>
                                        @foreach ($this->availableParentCategories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button wire:click="changeParentCategory"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            อัปเดต
                        </button>
                        <button wire:click="resetBulkActions"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
                            ยกเลิก
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Loading Overlay -->
    <div wire:loading class="fixed inset-0 z-50 overflow-hidden">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="bg-white rounded-lg p-4">
                <div class="flex items-center space-x-3">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                    <span class="text-gray-700">กำลังดำเนินการ...</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('confirmDelete', (event) => {
                if (confirm('คุณแน่ใจหรือไม่ที่จะลบหมวดหมู่นี้?')) {
                    Livewire.dispatch('deleteCategory', {
                        id: event.id
                    });
                }
            });
        });
    </script>
@endpush
