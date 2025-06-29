{{-- resources/views/livewire/backend/document/types-list.blade.php --}}
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-800 mb-2">จัดการประเภทเอกสาร</h1>
                        <p class="text-slate-600">กำหนดประเภทเอกสารและรูปแบบไฟล์ที่รองรับ</p>
                    </div>

                    @can('document-types.create')
                        <button wire:click="openCreateModal"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            สร้างประเภทใหม่
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
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-slate-600">ประเภททั้งหมด</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $types->total() }}</p>
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
                            {{ \App\Models\DocumentType::where('is_active', true)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-slate-600">รูปแบบไฟล์</p>
                        <p class="text-2xl font-bold text-slate-800">{{ count($availableExtensions) }}</p>
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
                                placeholder="ค้นหาประเภทเอกสารจากชื่อหรือคำอธิบาย..."
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

                    <!-- Clear Filters -->
                    @if ($search || $statusFilter !== 'all')
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

        <!-- Types Table -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            @if ($types->count() > 0)
                <!-- Table Header -->
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <div class="grid grid-cols-12 gap-4 items-center text-sm font-medium text-slate-700">
                        <div class="col-span-3">
                            <button wire:click="sortBy('name')"
                                class="flex items-center hover:text-slate-900 transition-colors">
                                ชื่อประเภท
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </button>
                        </div>
                        <div class="col-span-3">คำอธิบาย</div>
                        <div class="col-span-2">รูปแบบไฟล์</div>
                        <div class="col-span-1 text-center">เอกสาร</div>
                        <div class="col-span-1 text-center">สถานะ</div>
                        <div class="col-span-1">
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
                    @foreach ($types as $type)
                        <div class="px-6 py-4 hover:bg-slate-50 transition-colors"
                            wire:key="type-{{ $type->id }}">
                            <div class="grid grid-cols-12 gap-4 items-center">
                                <!-- Type Name & Slug -->
                                <div class="col-span-3">
                                    <div class="flex items-center space-x-3">
                                        <!-- Type Icon -->
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-green-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-medium text-slate-900">{{ $type->name }}</h3>
                                            <div class="mt-1">
                                                <span class="text-xs text-slate-500">/{{ $type->slug }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-span-3">
                                    @if ($type->description)
                                        <p class="text-sm text-slate-600 line-clamp-2">
                                            {{ Str::limit($type->description, 100) }}
                                        </p>
                                    @else
                                        <span class="text-xs text-slate-400">ไม่มีคำอธิบาย</span>
                                    @endif
                                </div>

                                <!-- Allowed Extensions -->
                                <div class="col-span-2">
                                    @if ($type->allowed_extensions && count($type->allowed_extensions) > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach (array_slice($type->allowed_extensions, 0, 3) as $ext)
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    .{{ $ext }}
                                                </span>
                                            @endforeach
                                            @if (count($type->allowed_extensions) > 3)
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-slate-100 text-slate-600">
                                                    +{{ count($type->allowed_extensions) - 3 }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400">ทุกรูปแบบ</span>
                                    @endif
                                </div>

                                <!-- Document Count -->
                                <div class="col-span-1">
                                    <div class="text-center">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $type->documents_count }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-span-1">
                                    <div class="flex items-center justify-center">
                                        @can('document-types.edit')
                                            <button wire:click="toggleStatus({{ $type->id }})"
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium transition-colors
                                                {{ $type->is_active
                                                    ? 'bg-green-100 text-green-800 hover:bg-green-200'
                                                    : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                                {{ $type->is_active ? 'ใช้งาน' : 'ปิด' }}
                                            </button>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                {{ $type->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $type->is_active ? 'ใช้งาน' : 'ปิด' }}
                                            </span>
                                        @endcan
                                    </div>
                                </div>

                                <!-- Created Date -->
                                <div class="col-span-1">
                                    <div class="text-sm text-slate-900">{{ $type->created_at->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-slate-500">{{ $type->created_at->format('H:i') }}</div>
                                </div>

                                <!-- Actions -->
                                <div class="col-span-1">
                                    <div class="flex items-center justify-center space-x-1">
                                        @can('document-types.edit')
                                            <button wire:click="openEditModal({{ $type->id }})"
                                                class="inline-flex items-center px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded transition-colors"
                                                title="แก้ไขประเภท">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                        @endcan
                                        @can('document-types.delete')
                                            <button wire:click="openDeleteModal({{ $type->id }})"
                                                class="inline-flex items-center px-2 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-medium rounded transition-colors"
                                                title="ลบประเภท">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
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
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <h3 class="text-lg font-medium text-slate-900 mb-2">ไม่พบประเภทเอกสาร</h3>
                    <p class="text-slate-500 mb-4">
                        {{ $search || $statusFilter !== 'all' ? 'ลองปรับเงื่อนไขการค้นหา' : 'เริ่มต้นด้วยการสร้างประเภทแรก' }}
                    </p>
                    @can('document-types.create')
                        <button wire:click="openCreateModal"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            สร้างประเภทแรก
                        </button>
                    @endcan
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if ($types->hasPages())
            <div class="mt-8">
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                    {{ $types->links() }}
                </div>
            </div>
        @endif

        <!-- Create Modal -->
        @if ($showCreateModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-lg p-6 max-w-4xl w-full mx-4 shadow-xl max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-medium text-gray-900">สร้างประเภทเอกสารใหม่</h3>
                        <button wire:click="$set('showCreateModal', false)" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form wire:submit="createType" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">ชื่อประเภทเอกสาร *</label>
                                <input wire:model="name" type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="เช่น PDF Document, รูปภาพ">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="flex items-center justify-center">
                                <label class="flex items-center">
                                    <input wire:model="is_active" type="checkbox" id="create_is_active"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">ใช้งานทันที</span>
                                </label>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">คำอธิบาย</label>
                            <textarea wire:model="description" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="คำอธิบายเกี่ยวกับประเภทเอกสารนี้"></textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- File Extensions -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">รูปแบบไฟล์ที่รองรับ</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2">
                                @foreach ($availableExtensions as $ext => $label)
                                    <label
                                        class="flex items-center p-2 border rounded-lg hover:bg-gray-50 cursor-pointer
                                        {{ in_array($ext, $allowed_extensions) ? 'border-blue-500 bg-blue-50' : 'border-gray-300' }}">
                                        <input type="checkbox" wire:click="toggleExtension('{{ $ext }}')"
                                            {{ in_array($ext, $allowed_extensions) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">.{{ $ext }}</div>
                                            <div class="text-xs text-gray-500">{{ $label }}</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <p class="mt-2 text-xs text-gray-500">ไม่เลือกไฟล์ใดๆ = อนุญาตทุกรูปแบบไฟล์</p>
                            @error('allowed_extensions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-3 pt-4 border-t">
                            <button type="button" wire:click="$set('showCreateModal', false)"
                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                                ยกเลิก
                            </button>
                            <button type="submit" wire:loading.attr="disabled"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-medium rounded-lg transition-colors">
                                <span wire:loading.remove>สร้างประเภท</span>
                                <span wire:loading class="flex items-center">
                                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
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
                <div class="bg-white rounded-lg p-6 max-w-4xl w-full mx-4 shadow-xl max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-medium text-gray-900">แก้ไขประเภทเอกสาร</h3>
                        <button wire:click="$set('showEditModal', false)" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form wire:submit="updateType" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">ชื่อประเภทเอกสาร *</label>
                                <input wire:model="name" type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="เช่น PDF Document, รูปภาพ">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="flex items-center justify-center">
                                <label class="flex items-center">
                                    <input wire:model="is_active" type="checkbox" id="edit_is_active"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">ใช้งาน</span>
                                </label>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">คำอธิบาย</label>
                            <textarea wire:model="description" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="คำอธิบายเกี่ยวกับประเภทเอกสารนี้"></textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- File Extensions -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">รูปแบบไฟล์ที่รองรับ</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2">
                                @foreach ($availableExtensions as $ext => $label)
                                    <label
                                        class="flex items-center p-2 border rounded-lg hover:bg-gray-50 cursor-pointer
                                        {{ in_array($ext, $allowed_extensions) ? 'border-blue-500 bg-blue-50' : 'border-gray-300' }}">
                                        <input type="checkbox" wire:click="toggleExtension('{{ $ext }}')"
                                            {{ in_array($ext, $allowed_extensions) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">.{{ $ext }}</div>
                                            <div class="text-xs text-gray-500">{{ $label }}</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <p class="mt-2 text-xs text-gray-500">ไม่เลือกไฟล์ใดๆ = อนุญาตทุกรูปแบบไฟล์</p>
                            @error('allowed_extensions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-3 pt-4 border-t">
                            <button type="button" wire:click="$set('showEditModal', false)"
                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                                ยกเลิก
                            </button>
                            <button type="submit" wire:loading.attr="disabled"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-medium rounded-lg transition-colors">
                                <span wire:loading.remove>บันทึกการแก้ไข</span>
                                <span wire:loading class="flex items-center">
                                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-600"></div>
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
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <h3 class="ml-3 text-lg font-medium text-gray-900">ยืนยันการลบประเภทเอกสาร</h3>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-600">
                            คุณแน่ใจหรือว่าต้องการลบประเภทเอกสารนี้? การดำเนินการนี้ไม่สามารถยกเลิกได้
                        </p>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3 mt-3">
                            <p class="text-sm text-red-800">
                                <strong>⚠️ คำเตือน:</strong> หากประเภทนี้มีเอกสาร จะไม่สามารถลบได้
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button wire:click="$set('showDeleteModal', false)"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                            ยกเลิก
                        </button>
                        <button wire:click="deleteType" wire:loading.attr="disabled"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white font-medium rounded-lg transition-colors">
                            <span wire:loading.remove>ลบประเภท</span>
                            <span wire:loading class="flex items-center">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                                กำลังลบ...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Loading Overlay -->
        <div wire:loading.flex wire:target="search,statusFilter,sortBy"
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
                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                {{ session('message') }}
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 z-50 max-w-sm">
            <div class="bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center">
                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                {{ session('error') }}
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        console.log('Livewire initialized for Document TypesList component');

        Livewire.on('typeCreated', (event) => {
            console.log('Type created:', event);
        });

        Livewire.on('typeUpdated', (event) => {
            console.log('Type updated:', event);
        });

        Livewire.on('typeDeleted', (event) => {
            console.log('Type deleted:', event);
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
