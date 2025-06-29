{{-- resources/views/livewire/backend/document/categories-list.blade.php --}}
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-800 mb-2">จัดการหมวดหมู่เอกสาร</h1>
                        <p class="text-slate-600">จัดการหมวดหมู่และหมวดหมู่ย่อยสำหรับเอกสาร</p>
                    </div>

                    @can('document-categories.create')
                    <button wire:click="openCreateModal"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        สร้างหมวดหมู่ใหม่
                    </button>
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
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-slate-600">หมวดหมู่ทั้งหมด</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $categories->total() }}</p>
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
                        <p class="text-sm text-slate-600">ใช้งานอยู่</p>
                        <p class="text-2xl font-bold text-slate-800">
                            {{ \App\Models\DocumentCategory::where('is_active', true)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-slate-600">หมวดหมู่หลัก</p>
                        <p class="text-2xl font-bold text-slate-800">
                            {{ \App\Models\DocumentCategory::whereNull('parent_id')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-amber-100 rounded-lg">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-slate-600">เอกสารทั้งหมด</p>
                        <p class="text-2xl font-bold text-slate-800">
                            {{ \App\Models\Document::count() }}</p>
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
                                placeholder="ค้นหาหมวดหมู่จากชื่อหรือคำอธิบาย..."
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
                        <select wire:model.live="statusFilter"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="all">ทุกสถานะ</option>
                            <option value="active">ใช้งานอยู่</option>
                            <option value="inactive">ปิดใช้งาน</option>
                        </select>
                    </div>

                    <!-- Parent Filter -->
                    <div class="lg:w-48">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model.live="showOnlyParents"
                                class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 mr-2">
                            <span class="text-sm text-slate-700">เฉพาะหมวดหมู่หลัก</span>
                        </label>
                    </div>

                    <!-- Clear Filters -->
                    @if ($search || $statusFilter !== 'all' || $showOnlyParents)
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

        <!-- Categories Table -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            @if ($categories->count() > 0)
            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <div class="grid grid-cols-12 gap-4 items-center text-sm font-medium text-slate-700">
                    <div class="col-span-1">ลำดับ</div>
                    <div class="col-span-4">
                        <button wire:click="sortBy('name')"
                            class="flex items-center hover:text-slate-900 transition-colors">
                            ชื่อหมวดหมู่
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                            </svg>
                        </button>
                    </div>
                    <div class="col-span-2">หมวดหมู่หลัก</div>
                    <div class="col-span-1 text-center">เอกสาร</div>
                    <div class="col-span-1 text-center">สถานะ</div>
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
                @foreach ($categories as $category)
                <div class="px-6 py-4 hover:bg-slate-50 transition-colors" wire:key="category-{{ $category->id }}">
                    <div class="grid grid-cols-12 gap-4 items-center">
                        <!-- Sort Order -->
                        <div class="col-span-1">
                            <div class="flex items-center space-x-1">
                                <span class="text-sm font-medium text-slate-600">{{ $category->sort_order }}</span>
                                @can('document-categories.edit')
                                <div class="flex flex-col space-y-0.5">
                                    <button wire:click="moveUp({{ $category->id }})"
                                        class="p-0.5 text-slate-400 hover:text-slate-600 transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7" />
                                        </svg>
                                    </button>
                                    <button wire:click="moveDown({{ $category->id }})"
                                        class="p-0.5 text-slate-400 hover:text-slate-600 transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </div>
                                @endcan
                            </div>
                        </div>

                        <!-- Category Name & Info -->
                        <div class="col-span-4">
                            <div class="flex items-center space-x-3">
                                <!-- Category Icon -->
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-medium text-slate-900">
                                        @if($category->parent_id)
                                        <span class="text-slate-400 mr-2">└</span>
                                        @endif
                                        {{ $category->name }}
                                    </h3>
                                    <div class="mt-1 flex items-center space-x-2">
                                        <span class="text-xs text-slate-500">/{{ $category->slug }}</span>
                                        @if ($category->children_count > 0)
                                        <span class="text-xs text-blue-600">{{ $category->children_count }}
                                            หมวดหมู่ย่อย</span>
                                        @endif
                                    </div>
                                    @if ($category->description)
                                    <p class="mt-1 text-xs text-slate-600 line-clamp-2">
                                        {{ Str::limit($category->description, 80) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Parent Category -->
                        <div class="col-span-2">
                            @if ($category->parent)
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                {{ $category->parent->name }}
                            </span>
                            @else
                            <span class="text-xs text-slate-500">หมวดหมู่หลัก</span>
                            @endif
                        </div>

                        <!-- Document Count -->
                        <div class="col-span-1">
                            <div class="text-center">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $category->documents_count }}
                                </span>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-span-1">
                            <div class="flex items-center justify-center">
                                @can('document-categories.edit')
                                <button wire:click="toggleStatus({{ $category->id }})" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium transition-colors
                                                {{ $category->is_active
                                                    ? 'bg-green-100 text-green-800 hover:bg-green-200'
                                                    : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                    {{ $category->is_active ? 'ใช้งาน' : 'ปิด' }}
                                </button>
                                @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                {{ $category->is_active
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-red-100 text-red-800' }}">
                                    {{ $category->is_active ? 'ใช้งาน' : 'ปิด' }}
                                </span>
                                @endcan
                            </div>
                        </div>

                        <!-- Created Date -->
                        <div class="col-span-2">
                            <div class="text-sm text-slate-900">{{ $category->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-slate-500">{{ $category->created_at->format('H:i') }}</div>
                        </div>

                        <!-- Actions -->
                        <div class="col-span-1">
                            <div class="flex items-center justify-center space-x-1">
                                @can('document-categories.edit')
                                <button wire:click="openEditModal({{ $category->id }})"
                                    class="inline-flex items-center px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded transition-colors"
                                    title="แก้ไขหมวดหมู่">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                @endcan
                                @can('document-categories.delete')
                                <button wire:click="openDeleteModal({{ $category->id }})"
                                    class="inline-flex items-center px-2 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-medium rounded transition-colors"
                                    title="ลบหมวดหมู่">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
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
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <h3 class="text-lg font-medium text-slate-900 mb-2">ไม่พบหมวดหมู่</h3>
                <p class="text-slate-500 mb-4">
                    {{ $search || $statusFilter !== 'all' ? 'ลองปรับเงื่อนไขการค้นหา' :
                    'เริ่มต้นด้วยการสร้างหมวดหมู่แรก' }}
                </p>
                @can('document-categories.create')
                <button wire:click="openCreateModal"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    สร้างหมวดหมู่แรก
                </button>
                @endcan
            </div>
            @endif
        </div>

        <!-- Pagination -->
        @if ($categories->hasPages())
        <div class="mt-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                {{ $categories->links() }}
            </div>
        </div>
        @endif

        <!-- Create Modal -->
        @if ($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4 shadow-xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-medium text-gray-900">สร้างหมวดหมู่ใหม่</h3>
                    <button wire:click="$set('showCreateModal', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="createCategory" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ชื่อหมวดหมู่ *</label>
                        <input wire:model="name" type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="ระบุชื่อหมวดหมู่">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">หมวดหมู่หลัก</label>
                        <select wire:model="parent_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">เลือกหมวดหมู่หลัก (ถ้ามี)</option>
                            @foreach ($parentCategories as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endforeach
                        </select>
                        @error('parent_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">คำอธิบาย</label>
                        <textarea wire:model="description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="คำอธิบายเกี่ยวกับหมวดหมู่"></textarea>
                        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">ลำดับ</label>
                                <input wire:model="sort_order" type="number" min="0"
                                    class="w-20 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('sort_order') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div class="flex items-center">
                                <input wire:model="is_active" type="checkbox" id="create_is_active"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="create_is_active" class="ml-2 text-sm text-gray-700">ใช้งานทันที</label>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" wire:click="$set('showCreateModal', false)"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                            ยกเลิก
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-medium rounded-lg transition-colors">
                            <span wire:loading.remove>สร้างหมวดหมู่</span>
                            <span wire:loading class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                กำลังสร้าง...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- Edit Modal -->
        @if ($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4 shadow-xl">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-medium text-gray-900">แก้ไขหมวดหมู่</h3>
                    <button wire:click="$set('showEditModal', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="updateCategory" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ชื่อหมวดหมู่ *</label>
                        <input wire:model="name" type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="ระบุชื่อหมวดหมู่">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">หมวดหมู่หลัก</label>
                        <select wire:model="parent_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">เลือกหมวดหมู่หลัก (ถ้ามี)</option>
                            @foreach ($parentCategories as $parent)
                            @if($parent->id !== $categoryId)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endif
                            @endforeach
                        </select>
                        @error('parent_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">คำอธิบาย</label>
                        <textarea wire:model="description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="คำอธิบายเกี่ยวกับหมวดหมู่"></textarea>
                        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">ลำดับ</label>
                                <input wire:model="sort_order" type="number" min="0"
                                    class="w-20 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('sort_order') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div class="flex items-center">
                                <input wire:model="is_active" type="checkbox" id="edit_is_active"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="edit_is_active" class="ml-2 text-sm text-gray-700">ใช้งาน</label>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" wire:click="$set('showEditModal', false)"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                            ยกเลิก
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-medium rounded-lg transition-colors">
                            <span wire:loading.remove>บันทึกการแก้ไข</span>
                            <span wire:loading class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                กำลังบันทึก...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- Delete Confirmation Modal -->
        @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 shadow-xl">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-medium text-gray-900">ยืนยันการลบหมวดหมู่</h3>
                </div>

                <div class="mb-6">
                    <p class="text-sm text-gray-600">
                        คุณแน่ใจหรือว่าต้องการลบหมวดหมู่นี้? การดำเนินการนี้ไม่สามารถยกเลิกได้
                    </p>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 mt-3">
                        <p class="text-sm text-red-800">
                            <strong>⚠️ คำเตือน:</strong> หากหมวดหมู่นี้มีหมวดหมู่ย่อยหรือเอกสาร จะไม่สามารถลบได้
                        </p>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button wire:click="$set('showDeleteModal', false)"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                        ยกเลิก
                    </button>
                    <button wire:click="deleteCategory" wire:loading.attr="disabled"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white font-medium rounded-lg transition-colors">
                        <span wire:loading.remove>ลบหมวดหมู่</span>
                        <span wire:loading class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            กำลังลบ...
                        </span>
                    </button>
                </div>
            </div>
        </div>
        @endif

        <!-- Loading Overlay -->
        <div wire:loading.flex wire:target="search,statusFilter,showOnlyParents,sortBy"
            class="fixed inset-0 z-40 bg-white bg-opacity-75 flex items-center justify-center">
            <div class="flex items-center space-x-2">
                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-600"></div>
                <span class="text-sm text-slate-600">กำลังโหลด...</span>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
    <div class="fixed top-4 right-4 z-50 max-w-sm">
        <div class="bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('message') }}
        </div>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="fixed top-4 right-4 z-50 max-w-sm">
        <div class="bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            {{ session('error') }}
        </div>
    </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        console.log('Livewire initialized for Document CategoriesList component');

        Livewire.on('categoryCreated', (event) => {
            console.log('Category created:', event);
        });

        Livewire.on('categoryUpdated', (event) => {
            console.log('Category updated:', event);
        });

        Livewire.on('categoryDeleted', (event) => {
            console.log('Category deleted:', event);
        });
    });

    // Auto-hide flash messages
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const messages = document.querySelectorAll('.fixed.top-4.right-4');
            messages.forEach(function(message) {
                message.style.transition = 'opacity 0.5s ease-out';
                message.style.opacity = '0';
                setTimeout(function() {
                    message.remove();
                }, 500);
            });
        }, 5000);
    });

    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (@json($showCreateModal)) {
                @this.call('$set', 'showCreateModal', false);
            }
            if (@json($showEditModal)) {
                @this.call('$set', 'showEditModal', false);
            }
            if (@json($showDeleteModal)) {
                @this.call('$set', 'showDeleteModal', false);
            }
        }
    });
</script>
