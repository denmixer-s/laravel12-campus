<div class="min-h-screen bg-gray-50" x-data="{ showMobileCategories: false }">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-l-8 border-orange-400">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-6">
            <div class="p-6 bg-white rounded-lg shadow-lg bg-gradient-to-r from-orange-300 to-gray-200 text-white border-l-8 border-orange-400">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h4 class="mb-2 text-3xl font-bold text-white">ระบบจัดการเอกสาร</h4>
                        <p class="text-gray-600">ค้นหาและดาวน์โหลดเอกสารที่คุณต้องการ</p>
                    </div>
                    <div class="flex gap-3">
                        <span class="mr-6">📄 เอกสารทั้งหมด {{ number_format($totalDocuments) }} รายการ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Section -->
    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8 bg-white rounded-lg shadow-lg p-6 border-l-8 border-orange-400">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <!-- Search Input -->
                <div class="lg:col-span-2">
                    <div class="relative">
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="ค้นหาเอกสาร..."
                            class="w-full pl-10 pr-4 py-2 border-0 rounded-lg shadow-md focus:ring-2 focus:ring-blue-500 focus:shadow-lg">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Category Filter -->
                <div>
                    <select wire:model.live="selectedCategory"
                        class="w-full py-2 px-3 border-0 rounded-lg shadow-md focus:ring-2 focus:ring-blue-500 focus:shadow-lg">
                        <option value="">ทุกหมวดหมู่</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->slug }}">{{ $category->name }}
                                ({{ $category->documents_count }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Type Filter -->
                <div>
                    <select wire:model.live="selectedType"
                        class="w-full py-2 px-3 border-0 rounded-lg shadow-md focus:ring-2 focus:ring-blue-500 focus:shadow-lg">
                        <option value="">ทุกประเภท</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->slug }}">{{ $type->name }} ({{ $type->documents_count }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Additional Filters -->
            <div class="flex flex-wrap gap-4 items-center justify-between">
                <div class="flex gap-4">
                    <label class="flex items-center">
                        <input wire:model.live="showFeatured" type="checkbox"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">เอกสารแนะนำ</span>
                    </label>
                    <label class="flex items-center">
                        <input wire:model.live="showNew" type="checkbox"
                            class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <span class="ml-2 text-sm text-gray-700">เอกสารใหม่</span>
                    </label>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Sort Options -->
                    <select wire:model.live="sortBy"
                        class="py-2 px-3 border-0 rounded-lg shadow-md focus:ring-2 focus:ring-blue-500 focus:shadow-lg">
                        @foreach ($sortOptions as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>

                    <!-- View Mode Toggle -->
                    <div class="flex border-0 rounded-lg shadow-md overflow-hidden">
                        <button wire:click="setViewMode('grid')"
                            class="px-3 py-2 {{ $viewMode === 'grid' ? 'bg-blue-500 text-white shadow-md' : 'bg-white text-gray-700 hover:shadow-lg' }} transition-all duration-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                        </button>
                        <button wire:click="setViewMode('list')"
                            class="px-3 py-2 {{ $viewMode === 'list' ? 'bg-blue-500 text-white shadow-md' : 'bg-white text-gray-700 hover:shadow-lg' }} transition-all duration-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>

                    @if ($activeFiltersCount > 0)
                        <button wire:click="clearFilters" class="px-3 py-2 text-sm text-red-600 hover:text-red-800 shadow-md hover:shadow-lg transition-all duration-200 rounded-lg">
                            ล้างตัวกรอง ({{ $activeFiltersCount }})
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Categories Sidebar (1/3 width ซ้าย) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-4 border-l-8 border-orange-400">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        หมวดหมู่เอกสาร
                    </h3>

                    <!-- All Categories Link -->
                    <div class="mb-2">
                        <button wire:click="setCategory('')"
                            class="w-full text-left px-3 py-2 rounded-lg shadow-md transition-all duration-200 flex items-center justify-between group {{ empty($selectedCategory) ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-500 shadow-lg' : 'text-gray-700 hover:bg-gray-50 hover:shadow-lg' }}">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                เอกสารทั้งหมด
                            </span>
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                                {{ number_format($totalDocuments) }}
                            </span>
                        </button>
                    </div>

                    <!-- Categories List -->
                    <div class="space-y-1">
                        @foreach ($categories as $category)
                            <div class="category-item">
                                <!-- Parent Category -->
                                <button wire:click="setCategory('{{ $category->slug }}')"
                                    class="w-full text-left px-3 py-2 rounded-lg shadow-md transition-all duration-200 flex items-center justify-between group {{ $selectedCategory === $category->slug ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-500 shadow-lg' : 'text-gray-700 hover:bg-gray-50 hover:shadow-lg' }}">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h2a2 2 0 012 2v2H8V5z"></path>
                                        </svg>
                                        {{ $category->name }}
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                                            {{ number_format($category->documents_count) }}
                                        </span>
                                        @if($category->children->count() > 0)
                                            <svg class="w-3 h-3 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        @endif
                                    </div>
                                </button>

                                <!-- Sub Categories -->
                                @if($category->children->count() > 0)
                                    <div class="ml-4 mt-1 space-y-1 border-l-2 border-gray-100 pl-3">
                                        @foreach($category->children as $child)
                                            <button wire:click="setCategory('{{ $child->slug }}')"
                                                class="w-full text-left px-2 py-1.5 rounded-md shadow-md transition-all duration-200 flex items-center justify-between text-sm {{ $selectedCategory === $child->slug ? 'bg-blue-50 text-blue-600 shadow-lg' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800 hover:shadow-lg' }}">
                                                <span class="flex items-center">
                                                    <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                    </svg>
                                                    {{ $child->name }}
                                                </span>
                                                <span class="text-xs bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded">
                                                    {{ number_format($child->documents_count) }}
                                                </span>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Category Statistics -->
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="text-xs text-gray-500 space-y-1">
                            <div class="flex justify-between">
                                <span>หมวดหมู่ทั้งหมด:</span>
                                <span class="font-medium">{{ $categories->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>เอกสารล่าสุด:</span>
                                <span class="font-medium">{{ $documents->first()?->created_at?->format('d/m/Y') ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Content (2/3 width ขวา) -->
            <div class="lg:col-span-2">
                <!-- Current Category Info -->
                @if($selectedCategory)
                    @php
                        $currentCategory = $categories->firstWhere('slug', $selectedCategory);
                    @endphp
                    @if($currentCategory)
                        <div class="bg-white rounded-lg shadow-lg p-4 mb-6 border-l-8 border-orange-400">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                    </svg>
                                    <div>
                                        <h2 class="text-lg font-semibold text-gray-900">{{ $currentCategory->name }}</h2>
                                        @if($currentCategory->description)
                                            <p class="text-sm text-gray-600">{{ $currentCategory->description }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ number_format($documents->total()) }} เอกสาร
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                <!-- Documents List -->
                <div class="space-y-6">
                    @forelse($documents as $index => $document)
                        <details class="shadow-lg rounded-lg border-l-8 border-orange-400 bg-white group transition-all duration-200 hover:shadow-xl">
                            <summary class="cursor-pointer w-full text-[15px] font-semibold text-left py-5 px-6 text-slate-800 flex items-center hover:bg-gray-50 transition-colors duration-200 list-none">
                                <!-- Document Icon -->
                                <div class="mr-4 shrink-0">
                                    <i class="{{ $document->type->icon_class }} text-2xl"></i>
                                </div>

                                <!-- Document Info -->
                                <div class="flex-1 mr-4">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h5 class="font-semibold text-gray-900">
                                            {{ Str::limit($document->title, 200, '...') }}
                                        </h5>

                                        <!-- Badges -->
                                        @if ($document->is_featured)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                ⭐ แนะนำ
                                            </span>
                                        @endif

                                        @if ($document->is_new)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                🆕 ใหม่
                                            </span>
                                        @endif

                                        <!-- Status Badge -->
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $document->status_badge['class'] }}">
                                            {{ $document->status_badge['text'] }}
                                        </span>
                                    </div>

                                    <div class="text-xs text-slate-600 mt-0.5 space-y-1">
                                        <div>{{ Str::limit($document->description, 100) }}</div>
                                        <!-- เพิ่มเส้นแยกระหว่างข้อมูล -->
                                        <div class="border-t border-gray-100 pt-2 flex items-center gap-4 text-gray-500">
                                            <span>📁 {{ $document->category->name }}</span>
                                            <span>📄 {{ $document->type->name }}</span>
                                            <span>📅 {{ $document->published_at?->format('d/m/Y') }}</span>
                                            <span>📏 {{ $document->file_size_formatted }}</span>
                                            <span>⬇️ {{ number_format($document->download_count) }}</span>
                                            <span>👁️ {{ number_format($document->view_count) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center gap-2 mr-4" onclick="event.stopPropagation();">
                                    <!-- Download Button -->
                                    <button wire:click="downloadDocument({{ $document->id }})"
                                        class="flex items-center gap-1 px-3 py-1.5 bg-green-500 text-white text-xs rounded-lg shadow-md hover:bg-green-600 hover:shadow-lg transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        ดาวน์โหลด
                                    </button>

                                    <!-- View Button -->
                                    <button wire:click="viewDocument({{ $document->id }})"
                                        class="flex items-center gap-1 px-3 py-1.5 bg-blue-500 text-white text-xs rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        ดูรายละเอียด
                                    </button>
                                </div>

                                <!-- Accordion Arrow -->
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-[14px] h-[14px] arrow fill-current ml-auto shrink-0 transition-all duration-300 group-open:rotate-180"
                                    viewBox="0 0 24 24">
                                    <path fill-rule="evenodd"
                                        d="M11.99997 18.1669a2.38 2.38 0 0 1-1.68266-.69733l-9.52-9.52a2.38 2.38 0 1 1 3.36532-3.36532l7.83734 7.83734 7.83734-7.83734a2.38 2.38 0 1 1 3.36532 3.36532l-9.52 9.52a2.38 2.38 0 0 1-1.68266.69734z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </summary>

                            <!-- Accordion Content -->
                            <div class="accordion-content overflow-hidden transition-all duration-300 ease-in-out">
                                <div class="pb-5 px-6 border-t border-gray-100">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                                        <!-- Document Details -->
                                        <div class="space-y-3">
                                            <h4 class="font-semibold text-gray-900 text-sm">ข้อมูลเอกสาร</h4>
                                            <div class="space-y-2 text-sm text-gray-600">
                                                <div><strong>เลขที่เอกสาร:</strong> {{ $document->document_number }}</div>
                                                <div><strong>วันที่เอกสาร:</strong>
                                                    {{ $document->document_date->format('d/m/Y') }}</div>
                                                <div><strong>เวอร์ชัน:</strong> {{ $document->version }}</div>
                                                @if ($document->reference_number)
                                                    <div><strong>เลขที่อ้างอิง:</strong> {{ $document->reference_number }}
                                                    </div>
                                                @endif
                                                @if ($document->keywords)
                                                    <div><strong>คำสำคัญ:</strong> {{ $document->keywords }}</div>
                                                @endif
                                                @if ($document->tags && count($document->tags) > 0)
                                                    <div class="flex flex-wrap gap-1 mt-2">
                                                        <strong class="w-full">แท็ก:</strong>
                                                        @foreach ($document->tags as $tag)
                                                            <span
                                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                {{ $tag }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Creator & Department Info -->
                                        <div class="space-y-3">
                                            <h4 class="font-semibold text-gray-900 text-sm">ข้อมูลผู้สร้าง</h4>
                                            <div class="space-y-2 text-sm text-gray-600">
                                                <div><strong>ผู้สร้าง:</strong> {{ $document->creator->name ?? 'ไม่ระบุ' }}
                                                </div>
                                                @if ($document->department)
                                                    <div><strong>หน่วยงาน:</strong> {{ $document->department->name }}</div>
                                                @endif
                                                <div><strong>วันที่สร้าง:</strong>
                                                    {{ $document->created_at->format('d/m/Y H:i') }}</div>
                                                <div><strong>วันที่อัปเดต:</strong>
                                                    {{ $document->updated_at->format('d/m/Y H:i') }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Full Description -->
                                    @if ($document->description)
                                        <div class="mt-6">
                                            <h4 class="font-semibold text-gray-900 text-sm mb-2">รายละเอียด</h4>
                                            <p class="text-sm text-gray-600 leading-relaxed">{{ $document->description }}
                                            </p>
                                        </div>
                                    @endif

                                    <!-- Additional Actions -->
                                    <div class="mt-6 pt-4 border-t border-gray-100 flex justify-between items-center">
                                        <div class="text-xs text-gray-500">
                                            อัปเดตล่าสุด: {{ $document->updated_at->diffForHumans() }}
                                        </div>

                                        <div class="flex gap-2">
                                            <button wire:click="downloadDocument({{ $document->id }})"
                                                class="flex items-center gap-1 px-4 py-2 bg-green-500 text-white text-sm rounded-lg shadow-md hover:bg-green-600 hover:shadow-lg transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                                ดาวน์โหลดเอกสาร
                                            </button>

                                            <button wire:click="viewDocument({{ $document->id }})"
                                                class="flex items-center gap-1 px-4 py-2 bg-blue-500 text-white text-sm rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                                    </path>
                                                </svg>
                                                ดูรายละเอียดเพิ่มเติม
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </details>
                    @empty
                        <div class="bg-white rounded-lg shadow-lg p-12 text-center border-l-8 border-orange-400">
                            <div class="text-gray-400 mb-4">
                                <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">ไม่พบเอกสาร</h3>
                            <p class="text-gray-500">ลองปรับเปลี่ยนเงื่อนไขการค้นหาหรือตัวกรอง</p>
                            @if($selectedCategory)
                                <button wire:click="setCategory('')"
                                    class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg transition-all duration-200">
                                    ดูเอกสารทั้งหมด
                                </button>
                            @endif
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if ($documents->hasPages())
                    <div class="mt-8">
                        {{ $documents->links() }}
                    </div>
                @endif

                <!-- Load More Button (Alternative to pagination) -->
                @if ($documents->hasMorePages() && $documents->count() >= $perPage)
                    <div class="text-center mt-8">
                        <button wire:click="loadMore"
                            class="px-6 py-3 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg transition-all duration-200">
                            โหลดเพิ่มเติม
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div wire:loading class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 flex items-center gap-3 shadow-xl">
            <svg class="animate-spin h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span class="text-gray-700">กำลังโหลด...</span>
        </div>
    </div>

    <!-- Responsive Mobile Menu for Categories -->
    <div class="lg:hidden fixed bottom-4 right-4 z-40">
        <button @click="showMobileCategories = !showMobileCategories"
            class="bg-blue-500 text-white p-3 rounded-full shadow-lg hover:bg-blue-600 hover:shadow-xl transition-all duration-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    <!-- Mobile Categories Overlay -->
    <div x-show="showMobileCategories"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-full"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-full"
         class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-xl p-6 max-h-96 overflow-y-auto shadow-xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">หมวดหมู่เอกสาร</h3>
                <button @click="showMobileCategories = false" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile Categories List -->
            <div class="space-y-2">
                <button wire:click="setCategory('')"
                    @click="showMobileCategories = false"
                    class="w-full text-left px-3 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 {{ empty($selectedCategory) ? 'bg-blue-50 text-blue-700' : 'text-gray-700' }}">
                    เอกสารทั้งหมด ({{ number_format($totalDocuments) }})
                </button>

                @foreach ($categories as $category)
                    <button wire:click="setCategory('{{ $category->slug }}')"
                        @click="showMobileCategories = false"
                        class="w-full text-left px-3 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 {{ $selectedCategory === $category->slug ? 'bg-blue-50 text-blue-700' : 'text-gray-700' }}">
                        {{ $category->name }} ({{ number_format($category->documents_count) }})
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Enhanced Shadow Effects Styles -->
    <style>
        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .shadow-xl {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .hover\:shadow-xl:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .hover\:shadow-lg:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .focus\:shadow-lg:focus {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>

    <script>
        // Ensure Alpine.js is loaded and available
        document.addEventListener('DOMContentLoaded', function() {
            // Additional initialization if needed
        });
    </script>
</div>
