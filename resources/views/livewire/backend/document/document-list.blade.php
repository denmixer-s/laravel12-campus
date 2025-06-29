<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-800 mb-2">จัดการเอกสาร</h1>
                        <p class="text-slate-600">จัดการและตรวจสอบเอกสารทั้งหมดในระบบ</p>
                    </div>

                    @can('documents.create')
                        <a href="{{ route('administrator.documents.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            เพิ่มเอกสารใหม่
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
                        <p class="text-sm text-slate-600">เอกสารทั้งหมด</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $this->documents->total() }}</p>
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
                            {{ \App\Models\Document::where('status', 'published')->count() }}</p>
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
                            {{ \App\Models\Document::where('status', 'draft')->count() }}</p>
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
                        <p class="text-2xl font-bold text-slate-800">{{ count($selectedDocuments) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="grid grid-cols-1 lg:grid-cols-6 gap-4">
                    <!-- Search Input -->
                    <div class="lg:col-span-2">
                        <div class="relative">
                            <input wire:model.live.debounce.300ms="search" type="text"
                                placeholder="ค้นหาเอกสารจากชื่อ เลขที่เอกสาร หรือคำอธิบาย..."
                                class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <select wire:model.live="selectedStatus"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">ทุกสถานะ</option>
                            @foreach ($statusOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <select wire:model.live="selectedCategory"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">ทุกหมวดหมู่</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type Filter -->
                    <div>
                        <select wire:model.live="selectedType"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">ทุกประเภท</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Department Filter -->
                    <div>
                        <select wire:model.live="selectedDepartment"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">ทุกหน่วยงาน</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Clear Filters & Per Page -->
                    <div class="flex items-center space-x-2">
                        @if ($search || $selectedStatus || $selectedCategory || $selectedType || $selectedDepartment || $selectedAccessLevel)
                            <button wire:click="clearFilters"
                                class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition-colors"
                                title="ล้างตัวกรอง">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        @endif

                        <select wire:model.live="perPage"
                            class="px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>

                <!-- Access Level & Quick Actions -->
                <div class="flex flex-wrap items-center justify-between gap-4 mt-4 pt-4 border-t border-slate-200">
                    <div class="flex items-center space-x-4">
                        <select wire:model.live="selectedAccessLevel"
                            class="px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">ทุกระดับการเข้าถึง</option>
                            @foreach ($accessLevelOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>

                        <a href="{{ route('administrator.documents.trash') }}"
                            class="inline-flex items-center px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            ถังขยะ
                        </a>
                    </div>

                    <!-- Quick Actions -->
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('administrator.documents.categories') }}"
                            class="inline-flex items-center px-3 py-2 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg transition-colors text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14-7H3a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2z" />
                            </svg>
                            หมวดหมู่
                        </a>
                        <a href="{{ route('administrator.documents.types') }}"
                            class="inline-flex items-center px-3 py-2 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-lg transition-colors text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            ประเภท
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions Panel -->
        @if (!empty($selectedDocuments))
            <div class="mb-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <span class="text-sm font-medium text-blue-900">
                                เลือกแล้ว {{ count($selectedDocuments) }} รายการ
                            </span>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            @can('documents.publish')
                                <button wire:click="bulkPublish"
                                    class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    เผยแพร่
                                </button>
                            @endcan

                            @can('documents.archive')
                                <button wire:click="bulkArchive"
                                    class="inline-flex items-center px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 8l6 6 6-6" />
                                    </svg>
                                    เก็บถาวร
                                </button>
                            @endcan

                            @can('documents.delete')
                                <button wire:click="bulkDelete"
                                    class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    ลบ
                                </button>
                            @endcan

                            <button wire:click="$set('selectedDocuments', []); $set('selectAll', false)"
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

        <!-- Documents Table -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            @if ($this->documents->count() > 0)
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
                                ชื่อเอกสาร
                                @if ($sortBy === 'title')
                                    <svg class="w-4 h-4 ml-1 {{ $sortDirection === 'asc' ? 'transform rotate-180' : '' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 ml-1 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                @endif
                            </button>
                        </div>
                        <div class="col-span-2">หมวดหมู่/ประเภท</div>
                        <div class="col-span-1 text-center">สถานะ</div>
                        <div class="col-span-2">
                            <button wire:click="sortBy('created_at')"
                                class="flex items-center hover:text-slate-900 transition-colors">
                                วันที่สร้าง
                                @if ($sortBy === 'created_at')
                                    <svg class="w-4 h-4 ml-1 {{ $sortDirection === 'asc' ? 'transform rotate-180' : '' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 ml-1 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                @endif
                            </button>
                        </div>
                        <div class="col-span-2 text-center">การดำเนินการ</div>
                    </div>
                </div>

                <!-- Table Body -->
                <div class="divide-y divide-slate-200">
                    @foreach ($this->documents as $document)
                        <div class="px-6 py-4 hover:bg-slate-50 transition-colors"
                            wire:key="document-{{ $document->id }}">
                            <div class="grid grid-cols-12 gap-4 items-center">
                                <!-- Checkbox -->
                                <div class="col-span-1">
                                    <input type="checkbox" value="{{ $document->id }}"
                                        wire:model.live="selectedDocuments"
                                        class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                </div>

                                <!-- Document Info -->
                                <div class="col-span-4">
                                    <div class="flex items-start space-x-3">
                                        <!-- File Icon -->
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 bg-slate-100 rounded-lg border border-slate-200 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-medium text-slate-900 truncate">
                                                {{ $document->title }}
                                            </h3>
                                            <div class="mt-1 flex items-center space-x-2">
                                                <span class="text-xs text-slate-500">{{ $document->document_number }}</span>
                                                @if ($document->is_featured)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        แนะนำ
                                                    </span>
                                                @endif
                                                @if ($document->is_new)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        ใหม่
                                                    </span>
                                                @endif
                                            </div>
                                            @if ($document->description)
                                                <p class="mt-1 text-xs text-slate-600 line-clamp-2">
                                                    {{ Str::limit(strip_tags($document->description), 100) }}
                                                </p>
                                            @endif
                                            <div class="mt-1 text-xs text-slate-500">
                                                ขนาด: {{ $document->file_size_formatted }} |
                                                ดาวน์โหลด: {{ number_format($document->download_count) }} ครั้ง
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Category & Type -->
                                <div class="col-span-2">
                                    <div class="space-y-1">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $document->category->name ?? 'ไม่มีหมวดหมู่' }}
                                        </span>
                                        <br>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ $document->type->name ?? 'ไม่มีประเภท' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-span-1">
                                    <div class="flex flex-col items-center space-y-1">
                                        @php $statusBadge = $document->status_badge; @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusBadge['class'] }}">
                                            {{ $statusBadge['text'] }}
                                        </span>
                                        @php $accessBadge = $document->access_level_badge; @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $accessBadge['class'] }}">
                                            {{ $accessBadge['text'] }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Created Date -->
                                <div class="col-span-2">
                                    <div class="text-sm text-slate-900">{{ $document->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-slate-500">{{ $document->created_at->format('H:i') }}</div>
                                    @if ($document->creator)
                                        <div class="text-xs text-slate-500">โดย {{ $document->creator->name }}</div>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="col-span-2">
                                    <div class="flex items-center justify-center space-x-2">
                                        @can('documents.edit')
                                            <a href="{{ route('administrator.documents.edit', $document) }}"
                                                class="inline-flex items-center px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded transition-colors"
                                                title="แก้ไขเอกสาร">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                        @endcan

                                        @can('documents.publish')
                                            <button wire:click="toggleDocumentStatus({{ $document->id }})"
                                                class="inline-flex items-center px-2 py-1 {{ $document->status === 'published' ? 'bg-amber-100 hover:bg-amber-200 text-amber-700' : 'bg-green-100 hover:bg-green-200 text-green-700' }} text-xs font-medium rounded transition-colors"
                                                title="{{ $document->status === 'published' ? 'เก็บถาวร' : 'เผยแพร่' }}">
                                                @if ($document->status === 'published')
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M5 8l6 6 6-6" />
                                                    </svg>
                                                @else
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @endif
                                            </button>
                                        @endcan

                                        @can('documents.feature')
                                            <button wire:click="toggleFeatured({{ $document->id }})"
                                                class="inline-flex items-center px-2 py-1 {{ $document->is_featured ? 'bg-yellow-100 hover:bg-yellow-200 text-yellow-700' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }} text-xs font-medium rounded transition-colors"
                                                title="{{ $document->is_featured ? 'ยกเลิกแนะนำ' : 'ทำเป็นแนะนำ' }}">
                                                <svg class="w-3 h-3" fill="{{ $document->is_featured ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                </svg>
                                            </button>
                                        @endcan

                                        @can('documents.delete')
                                            <button wire:click="deleteDocument({{ $document->id }})"
                                                wire:confirm="คุณแน่ใจหรือไม่ว่าต้องการลบเอกสารนี้?"
                                                class="inline-flex items-center px-2 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-medium rounded transition-colors"
                                                title="ลบเอกสาร">
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
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-lg font-medium text-slate-900 mb-2">ไม่พบเอกสาร</h3>
                    <p class="text-slate-500 mb-4">
                        {{ $search || $selectedStatus || $selectedCategory || $selectedType || $selectedDepartment || $selectedAccessLevel ? 'ลองปรับเงื่อนไขการค้นหา' : 'เริ่มต้นด้วยการเพิ่มเอกสารแรก' }}
                    </p>
                    @can('documents.create')
                        <a href="{{ route('administrator.documents.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            เพิ่มเอกสารแรก
                        </a>
                    @endcan
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if ($this->documents->hasPages())
            <div class="mt-8">
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                    {{ $this->documents->links() }}
                </div>
            </div>
        @endif

        <!-- Loading Overlay -->
        <div wire:loading.flex wire:target="search,selectedStatus,selectedCategory,selectedType,selectedDepartment,selectedAccessLevel,perPage,sortBy"
            class="fixed inset-0 z-40 bg-white bg-opacity-75 flex items-center justify-center">
            <div class="flex items-center space-x-2">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                <span class="text-sm text-slate-600">กำลังโหลด...</span>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session()->has('success'))
            <div class="fixed top-4 right-4 z-50 max-w-md">
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 shadow-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="fixed top-4 right-4 z-50 max-w-md">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 shadow-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p class="text-sm text-red-800">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Debug Information (Remove in production) -->
    @if (app()->environment('local'))
        <div class="fixed bottom-4 right-4 bg-black bg-opacity-75 text-white p-3 rounded-lg text-xs max-w-sm">
            <div><strong>Debug Info:</strong></div>
            <div>Total Documents: {{ $this->documents->total() }}</div>
            <div>Selected: {{ count($selectedDocuments) }}</div>
            <div>Select All: {{ $selectAll ? 'true' : 'false' }}</div>
            <div>Search: "{{ $search }}"</div>
            <div>Sort: {{ $sortBy }} {{ $sortDirection }}</div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        console.log('Livewire initialized for DocumentList component');

        // Auto-hide success messages
        setTimeout(() => {
            const successMessages = document.querySelectorAll('.bg-green-50');
            successMessages.forEach(msg => {
                msg.style.transition = 'opacity 0.5s';
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 500);
            });
        }, 5000);

        // Auto-hide error messages
        setTimeout(() => {
            const errorMessages = document.querySelectorAll('.bg-red-50');
            errorMessages.forEach(msg => {
                msg.style.transition = 'opacity 0.5s';
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 500);
            });
        }, 7000);
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + A to select all
        if ((e.ctrlKey || e.metaKey) && e.key === 'a' && e.target.tagName !== 'INPUT') {
            e.preventDefault();
            @this.set('selectAll', true);
        }

        // ESC to clear selection
        if (e.key === 'Escape') {
            @this.set('selectedDocuments', []);
            @this.set('selectAll', false);
        }
    });
</script>
