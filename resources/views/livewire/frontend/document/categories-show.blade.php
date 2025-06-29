<div class="min-h-screen bg-gray-50" x-data="{ showMobileFilters: false }">
    <!-- Breadcrumb -->
    <div class="bg-white border-b border-gray-200 border-l-8 border-orange-400 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    @foreach($breadcrumbs as $index => $breadcrumb)
                        <li class="flex items-center">
                            @if($index > 0)
                                <svg class="w-4 h-4 text-gray-400 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            @endif
                            @if($breadcrumb['url'])
                                <a href="{{ $breadcrumb['url'] }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    {{ $breadcrumb['name'] }}
                                </a>
                            @else
                                <span class="text-gray-900 text-sm font-medium">{{ $breadcrumb['name'] }}</span>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        </div>
    </div>

    <!-- Category Header -->
    <div class="bg-white shadow-sm border-b border-l-8 border-orange-400">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
            <div class="p-6 bg-white rounded-lg shadow-lg bg-gradient-to-r from-orange-300 to-gray-200 text-white border-l-8 border-orange-400">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <div class="flex items-center mb-2">
                            <svg class="w-8 h-8 mr-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            </svg>
                            <h1 class="text-3xl font-bold text-white">{{ $category->name }}</h1>
                        </div>
                        @if($category->description)
                            <p class="text-gray-600">{{ $category->description }}</p>
                        @else
                            <p class="text-gray-600">เอกสารในหมวดหมู่ {{ $category->name }}</p>
                        @endif
                    </div>
                    <div class="flex gap-3">
                        <span class="mr-6">📄 เอกสารทั้งหมด {{ number_format($totalDocuments) }} รายการ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subcategories (if any) -->
    @if($subcategories->count() > 0)
        <div class="w-full px-4 sm:px-6 lg:px-8 py-6">
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-8 border-orange-400">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    หมวดหมู่ย่อย
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($subcategories as $subcategory)
                        <button wire:click="viewSubcategory('{{ $subcategory->slug }}')"
                            class="p-4 bg-gray-50 rounded-lg shadow-md hover:shadow-lg hover:bg-blue-50 transition-all duration-200 text-left group">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3 text-blue-500 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                    </svg>
                                    <div>
                                        <h3 class="font-medium text-gray-900 group-hover:text-blue-600">{{ $subcategory->name }}</h3>
                                        @if($subcategory->description)
                                            <p class="text-xs text-gray-500 mt-1">{{ Str::limit($subcategory->description, 50) }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-medium text-gray-600">{{ number_format($subcategory->documents_count) }}</span>
                                    <p class="text-xs text-gray-500">เอกสาร</p>
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Search & Filter Section -->
    <div class="w-full px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-8 bg-white rounded-lg shadow-lg p-6 border-l-8 border-orange-400">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <!-- Search Input -->
                <div class="lg:col-span-2">
                    <div class="relative">
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="ค้นหาเอกสารในหมวดหมู่นี้..."
                            class="w-full pl-10 pr-4 py-2 border-0 rounded-lg shadow-md focus:ring-2 focus:ring-blue-500 focus:shadow-lg">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
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

                <!-- Department Filter -->
                <div>
                    <select wire:model.live="selectedDepartment"
                        class="w-full py-2 px-3 border-0 rounded-lg shadow-md focus:ring-2 focus:ring-blue-500 focus:shadow-lg">
                        <option value="">ทุกแผนก</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->slug }}">{{ $department->name }} ({{ $department->documents_count }})
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
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            <!-- Sidebar (1/4 width ซ้าย) -->
            <div class="lg:col-span-1">
                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6 border-l-8 border-orange-400 hover:shadow-xl transition-all duration-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">สถิติ</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">เอกสารทั้งหมด:</span>
                            <span class="text-sm font-medium text-gray-900">{{ number_format($totalDocuments) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">ประเภทเอกสาร:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $types->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">หน่วยงาน:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $departments->count() }}</span>
                        </div>
                        @if($subcategories->count() > 0)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">หมวดหมู่ย่อย:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $subcategories->count() }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Featured Documents -->
                @if($featuredDocuments->count() > 0)
                    <div class="bg-white rounded-lg shadow-lg p-6 mb-6 border-l-8 border-orange-400 hover:shadow-xl transition-all duration-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">เอกสารแนะนำ</h3>
                        <div class="space-y-3">
                            @foreach($featuredDocuments->take(3) as $doc)
                                <div class="border-b border-gray-200 pb-3 last:border-b-0 last:pb-0">
                                    <h4 class="text-sm font-medium text-gray-900 mb-1">
                                        <a href="{{ route('documents.show', $doc) }}" class="hover:text-blue-600">
                                            {{ Str::limit($doc->title, 50) }}
                                        </a>
                                    </h4>
                                    <div class="flex items-center text-xs text-gray-500">
                                        <span>{{ $doc->type->name }}</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ $doc->published_at?->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Recent Documents -->
                @if($recentDocuments->count() > 0)
                    <div class="bg-white rounded-lg shadow-lg p-6 border-l-8 border-orange-400 hover:shadow-xl transition-all duration-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">เอกสารล่าสุด</h3>
                        <div class="space-y-3">
                            @foreach($recentDocuments->take(3) as $doc)
                                <div class="border-b border-gray-200 pb-3 last:border-b-0 last:pb-0">
                                    <h4 class="text-sm font-medium text-gray-900 mb-1">
                                        <a href="{{ route('documents.show', $doc) }}" class="hover:text-blue-600">
                                            {{ Str::limit($doc->title, 50) }}
                                        </a>
                                    </h4>
                                    <div class="flex items-center text-xs text-gray-500">
                                        <span>{{ $doc->type->name }}</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ $doc->published_at?->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Documents Content (3/4 width ขวา) -->
            <div class="lg:col-span-3">
                <!-- Results Summary -->
                <div class="bg-white rounded-lg shadow-lg p-4 mb-6 border-l-8 border-orange-400">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">
                                @if($documents->total() > 0)
                                    พบ {{ number_format($documents->total()) }} เอกสาร
                                @else
                                    ไม่พบเอกสาร
                                @endif
                            </h2>
                            @if($search || $selectedType || $selectedDepartment || $showFeatured || $showNew)
                                <p class="text-sm text-gray-600 mt-1">
                                    ผลการค้นหาและกรอง
                                    @if($search) "{{ $search }}" @endif
                                    @if($selectedType) ประเภท: {{ $types->firstWhere('slug', $selectedType)?->name }} @endif
                                    @if($selectedDepartment) แผนก: {{ $departments->firstWhere('slug', $selectedDepartment)?->name }} @endif
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Documents List -->
                @if($viewMode === 'list')
                    <div class="space-y-6">
                        @forelse($documents as $document)
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
                                                @if ($search)
                                                    {!! str_ireplace(
                                                        $search,
                                                        '<mark class="bg-yellow-200 px-1 rounded">' . $search . '</mark>',
                                                        e(Str::limit($document->title, 200, '...')),
                                                    ) !!}
                                                @else
                                                    {{ Str::limit($document->title, 200, '...') }}
                                                @endif
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
                                            <div>
                                                @if ($search && $document->description)
                                                    {!! str_ireplace(
                                                        $search,
                                                        '<mark class="bg-yellow-200 px-1 rounded">' . $search . '</mark>',
                                                        e(Str::limit($document->description, 100)),
                                                    ) !!}
                                                @else
                                                    {{ Str::limit($document->description, 100) }}
                                                @endif
                                            </div>
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
                                                        <div><strong>คำสำคัญ:</strong>
                                                            @if ($search)
                                                                {!! str_ireplace(
                                                                    $search,
                                                                    '<mark class="bg-yellow-200 px-1 rounded">' . $search . '</mark>',
                                                                    e($document->keywords),
                                                                ) !!}
                                                            @else
                                                                {{ $document->keywords }}
                                                            @endif
                                                        </div>
                                                    @endif
                                                    @if ($document->tags && count($document->tags) > 0)
                                                        <div class="flex flex-wrap gap-1 mt-2">
                                                            <strong class="w-full">แท็ก:</strong>
                                                            @foreach ($document->tags as $tag)
                                                                <span
                                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                    @if ($search)
                                                                        {!! str_ireplace($search, '<mark class="bg-yellow-200 px-1 rounded">' . $search . '</mark>', e($tag)) !!}
                                                                    @else
                                                                        {{ $tag }}
                                                                    @endif
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
                                                <p class="text-sm text-gray-600 leading-relaxed">
                                                    @if ($search)
                                                        {!! str_ireplace(
                                                            $search,
                                                            '<mark class="bg-yellow-200 px-1 rounded">' . $search . '</mark>',
                                                            e($document->description),
                                                        ) !!}
                                                    @else
                                                        {{ $document->description }}
                                                    @endif
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
                                <h3 class="text-lg font-medium text-gray-900 mb-2">ไม่พบเอกสารในหมวดหมู่นี้</h3>
                                <p class="text-gray-500">ลองปรับเปลี่ยนเงื่อนไขการค้นหาหรือตัวกรอง</p>
                                @if($search || $selectedType || $selectedDepartment || $showFeatured || $showNew)
                                    <button wire:click="clearFilters"
                                        class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg transition-all duration-200">
                                        ล้างตัวกรอง
                                    </button>
                                @endif
                            </div>
                        @endforelse
                    </div>
                @else
                    <!-- Grid View -->
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @forelse($documents as $document)
                            <div class="bg-white rounded-lg shadow-lg border-l-8 border-orange-400 overflow-hidden hover:shadow-xl transition-all duration-200">
                                <!-- Document Header -->
                                <div class="p-4 border-b border-gray-200">
                                    <div class="flex items-start">
                                        <div class="mr-3 shrink-0">
                                            <i class="{{ $document->type->icon_class }} text-2xl"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold text-gray-900 mb-2">
                                                <a href="{{ route('documents.show', $document) }}" class="hover:text-blue-600">
                                                    @if ($search)
                                                        {!! str_ireplace(
                                                            $search,
                                                            '<mark class="bg-yellow-200 px-1 rounded">' . $search . '</mark>',
                                                            e(Str::limit($document->title, 80)),
                                                        ) !!}
                                                    @else
                                                        {{ Str::limit($document->title, 80) }}
                                                    @endif
                                                </a>
                                            </h3>

                                            <!-- Badges -->
                                            <div class="flex flex-wrap gap-1 mb-2">
                                                @if ($document->is_featured)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        ⭐ แนะนำ
                                                    </span>
                                                @endif
                                                @if ($document->is_new)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        🆕 ใหม่
                                                    </span>
                                                @endif
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $document->status_badge['class'] }}">
                                                    {{ $document->status_badge['text'] }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Document Content -->
                                <div class="p-4">
                                    @if($document->description)
                                        <p class="text-sm text-gray-600 mb-3">
                                            @if ($search)
                                                {!! str_ireplace(
                                                    $search,
                                                    '<mark class="bg-yellow-200 px-1 rounded">' . $search . '</mark>',
                                                    e(Str::limit($document->description, 100)),
                                                ) !!}
                                            @else
                                                {{ Str::limit($document->description, 100) }}
                                            @endif
                                        </p>
                                    @endif

                                    <!-- Document Meta -->
                                    <div class="text-xs text-gray-500 space-y-1 mb-4">
                                        <div class="flex items-center gap-4">
                                            <span>📁 {{ $document->category->name }}</span>
                                            <span>📄 {{ $document->type->name }}</span>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span>📅 {{ $document->published_at?->format('d/m/Y') }}</span>
                                            <span>📏 {{ $document->file_size_formatted }}</span>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span>⬇️ {{ number_format($document->download_count) }}</span>
                                            <span>👁️ {{ number_format($document->view_count) }}</span>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex gap-2">
                                        <button wire:click="downloadDocument({{ $document->id }})"
                                            class="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-green-500 text-white text-xs rounded-lg shadow-md hover:bg-green-600 hover:shadow-lg transition-all duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            ดาวน์โหลด
                                        </button>

                                        <button wire:click="viewDocument({{ $document->id }})"
                                            class="flex-1 flex items-center justify-center gap-1 px-3 py-2 bg-blue-500 text-white text-xs rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg transition-all duration-200">
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
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full bg-white rounded-lg shadow-lg p-12 text-center border-l-8 border-orange-400">
                                <div class="text-gray-400 mb-4">
                                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">ไม่พบเอกสารในหมวดหมู่นี้</h3>
                                <p class="text-gray-500">ลองปรับเปลี่ยนเงื่อนไขการค้นหาหรือตัวกรอง</p>
                                @if($search || $selectedType || $selectedDepartment || $showFeatured || $showNew)
                                    <button wire:click="clearFilters"
                                        class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg transition-all duration-200">
                                        ล้างตัวกรอง
                                    </button>
                                @endif
                            </div>
                        @endforelse
                    </div>
                @endif

                <!-- Pagination -->
                @if ($documents->hasPages())
                    <div class="mt-8">
                        {{ $documents->links() }}
                    </div>
                @endif

                <!-- Load More Button -->
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
        <div class="bg-white rounded-lg p-6 flex items-center gap-3 shadow-xl border-l-8 border-orange-400">
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

    <!-- Mobile Filter Button -->
    <div class="lg:hidden fixed bottom-4 right-4 z-40">
        <button @click="showMobileFilters = !showMobileFilters"
            class="bg-blue-500 text-white p-3 rounded-full shadow-lg hover:bg-blue-600 hover:shadow-xl transition-all duration-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
        </button>
    </div>

    <!-- Mobile Filter Overlay -->
    <div x-show="showMobileFilters"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-full"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-full"
         class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-xl p-6 max-h-96 overflow-y-auto shadow-xl border-l-8 border-orange-400">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">ตัวกรองเอกสาร</h3>
                <button @click="showMobileFilters = false" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile Filter Content -->
            <div class="space-y-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ค้นหา</label>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="w-full border-0 rounded-lg shadow-md focus:ring-2 focus:ring-blue-500"
                        placeholder="ค้นหาเอกสาร...">
                </div>

                <!-- Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ประเภท</label>
                    <select wire:model.live="selectedType" class="w-full border-0 rounded-lg shadow-md">
                        <option value="">ทุกประเภท</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->slug }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Department -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">แผนก</label>
                    <select wire:model.live="selectedDepartment" class="w-full border-0 rounded-lg shadow-md">
                        <option value="">ทุกแผนก</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->slug }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Checkboxes -->
                <div class="flex gap-4">
                    <label class="flex items-center">
                        <input wire:model.live="showFeatured" type="checkbox" class="rounded">
                        <span class="ml-2 text-sm">เอกสารแนะนำ</span>
                    </label>
                    <label class="flex items-center">
                        <input wire:model.live="showNew" type="checkbox" class="rounded">
                        <span class="ml-2 text-sm">เอกสารใหม่</span>
                    </label>
                </div>

                <!-- Actions -->
                <div class="flex gap-2 pt-4">
                    <button wire:click="clearFilters" @click="showMobileFilters = false"
                        class="flex-1 px-4 py-2 bg-gray-500 text-white rounded-lg shadow-md hover:bg-gray-600">
                        ล้างตัวกรอง
                    </button>
                    <button @click="showMobileFilters = false"
                        class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600">
                        ปิด
                    </button>
                </div>
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

        mark {
            background-color: #fef08a;
            padding: 0.125rem 0.25rem;
            border-radius: 0.25rem;
        }
    </style>

    <script>
        // Ensure Alpine.js is loaded and available
        document.addEventListener('DOMContentLoaded', function() {
            // Additional initialization if needed
        });
    </script>
</div>
