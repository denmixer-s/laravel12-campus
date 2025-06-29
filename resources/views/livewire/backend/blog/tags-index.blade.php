<div class="p-6">
    {{-- Header Section --}}
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    จัดการแท็ก
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    จัดการแท็กสำหรับบทความบล็อก
                </p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('administrator.blog.tags.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    เพิ่มแท็กใหม่
                </a>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">แท็กทั้งหมด</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalTags) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">ใช้งาน</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($activeTags) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 dark:bg-red-900">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">ไม่ใช้งาน</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($inactiveTags) }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters & Search --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Search --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        ค้นหา
                    </label>
                    <div class="relative">
                        <input type="text" wire:model.live="search" placeholder="ค้นหาชื่อแท็กหรือคำอธิบาย..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Status Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        สถานะ
                    </label>
                    <select wire:model.live="statusFilter"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="all">ทั้งหมด</option>
                        <option value="active">ใช้งาน</option>
                        <option value="inactive">ไม่ใช้งาน</option>
                    </select>
                </div>

                {{-- Per Page --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        แสดงต่อหน้า
                    </label>
                    <select wire:model.live="perPage"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Bulk Actions --}}
    @if (count($selectedTags) > 0)
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <span class="text-sm font-medium text-blue-800 dark:text-blue-200">
                        เลือกแล้ว {{ count($selectedTags) }} รายการ
                    </span>
                </div>
                <div class="flex items-center space-x-3">
                    <select wire:model="bulkAction"
                        class="px-3 py-1 text-sm border border-blue-300 dark:border-blue-600 rounded focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <option value="">เลือกการดำเนินการ</option>
                        <option value="activate">เปิดใช้งาน</option>
                        <option value="deactivate">ปิดใช้งาน</option>
                        <option value="delete">ลบ</option>
                    </select>
                    <button wire:click="executeBulkAction"
                        class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition-colors">
                        ดำเนินการ
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Tags Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" wire:model.live="selectAll"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                            wire:click="sortBy('name')">
                            <div class="flex items-center space-x-1">
                                <span>ชื่อแท็ก</span>
                                @if ($sortBy === 'name')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if ($sortDirection === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        @endif
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            คำอธิบาย
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                            wire:click="sortBy('posts_count')">
                            <div class="flex items-center space-x-1">
                                <span>จำนวนโพสต์</span>
                                @if ($sortBy === 'posts_count')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if ($sortDirection === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        @endif
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            สถานะ
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                            wire:click="sortBy('created_at')">
                            <div class="flex items-center space-x-1">
                                <span>วันที่สร้าง</span>
                                @if ($sortBy === 'created_at')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if ($sortDirection === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        @endif
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            การดำเนินการ
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($tags as $tag)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" wire:model.live="selectedTags" value="{{ $tag->id }}"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($editingTag === $tag->id)
                                    <div class="space-y-2">
                                        <input type="text" wire:model="editForm.name"
                                            class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                        <div class="flex items-center space-x-2">
                                            <input type="color" wire:model="editForm.color"
                                                class="w-8 h-8 rounded border border-gray-300 dark:border-gray-600">
                                            <span
                                                class="text-xs text-gray-500 dark:text-gray-400">{{ $editForm['color'] ?? '#10B981' }}</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center space-x-3">
                                        <div class="w-4 h-4 rounded-full"
                                            style="background-color: {{ $tag->color }}"></div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $tag->name }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $tag->slug }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($editingTag === $tag->id)
                                    <textarea wire:model="editForm.description" rows="2"
                                        class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"></textarea>
                                @else
                                    <div class="text-sm text-gray-600 dark:text-gray-400 max-w-xs truncate">
                                        {{ $tag->description ?: '-' }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ number_format($tag->posts_count) }}
                                    </span>
                                    @if ($tag->posts_count > 0)
                                        <span class="ml-2 text-xs text-blue-600 dark:text-blue-400">โพสต์</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($editingTag === $tag->id)
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="editForm.is_active"
                                            class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">ใช้งาน</span>
                                    </label>
                                @else
                                    <button wire:click="toggleStatus({{ $tag->id }})"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors {{ $tag->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200 dark:bg-red-900 dark:text-red-200' }}">
                                        {{ $tag->is_active ? 'ใช้งาน' : 'ไม่ใช้งาน' }}
                                    </button>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $tag->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if ($editingTag === $tag->id)
                                    <div class="flex items-center justify-end space-x-2">
                                        <button wire:click="saveEdit"
                                            class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                            บันทึก
                                        </button>
                                        <button wire:click="cancelEdit"
                                            class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300">
                                            ยกเลิก
                                        </button>
                                    </div>
                                @else
                                    <div class="flex items-center justify-end space-x-2">
                                        <button wire:click="startEdit({{ $tag->id }})"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                            title="แก้ไข">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                        <a href="{{ route('administrator.blog.tags.edit', $tag) }}"
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                            title="แก้ไขรายละเอียด">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </a>
                                        <button wire:click="deleteTag({{ $tag->id }})"
                                            wire:confirm="คุณแน่ใจหรือไม่ที่จะลบแท็ก '{{ $tag->name }}' ?"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                            title="ลบ">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                        </path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">ไม่พบแท็ก
                                    </h3>
                                    <p class="text-gray-500 dark:text-gray-400 mb-4">
                                        @if ($search)
                                            ไม่พบแท็กที่ตรงกับการค้นหา "{{ $search }}"
                                        @else
                                            ยังไม่มีแท็กในระบบ
                                        @endif
                                    </p>
                                    <a href="{{ route('administrator.blog.tags.create') }}"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        เพิ่มแท็กแรก
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($tags->hasPages())
            <div class="px-6 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                {{ $tags->links() }}
            </div>
        @endif
    </div>
</div>
