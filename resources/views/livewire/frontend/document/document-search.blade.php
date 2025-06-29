<!-- Single Root Element -->
<div class="min-h-screen bg-gray-50">
    <!-- Page Header -->
    <div class="bg-white shadow-sm border-b border-l-8 border-orange-400"> <!-- เพิ่ม border-l-8 border-orange-400 -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">ค้นหาเอกสาร</h1>
                    <p class="mt-1 text-sm text-gray-600">ค้นหาเอกสารแบบละเอียดด้วยเงื่อนไขหลากหลาย</p>
                </div>

                <!-- Quick Stats -->
                <div class="hidden md:flex items-center space-x-4 text-sm text-gray-500">
                    @if ($total_results > 0)
                        <span>พบ <strong class="text-gray-900">{{ number_format($total_results) }}</strong> เอกสาร</span>
                        <span>ใช้เวลา <strong>{{ $search_time }}</strong> ms</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
        <!-- เปลี่ยนจาก max-w-7xl mx-auto เป็น w-full เพื่อให้เต็มความกว้าง 100% -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8"> <!-- เปลี่ยนจาก lg:grid-cols-4 เป็น lg:grid-cols-3 -->

            <!-- Search Sidebar (ย้ายมาซ้าย) -->
            <div class="lg:col-span-1"> <!-- ขนาดเท่าเดิม แต่พื้นที่รวมเล็กลง ทำให้ sidebar ใหญ่ขึ้น -->
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-8 border-l-8 border-orange-400">
                    <!-- เพิ่ม border-l-8 border-orange-400 -->

                    <!-- Main Search Box -->
                    <div class="mb-6">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                            ค้นหาเอกสาร
                        </label>
                        <div class="relative">
                            <input type="text" id="search" wire:model.live.debounce.300ms="query"
                                wire:keydown.enter="search" placeholder="ป้อนชื่อเอกสารหรือคำค้นหา..."
                                class="w-full pl-10 pr-4 py-2 border-0 rounded-lg shadow-md focus:ring-2 focus:ring-blue-500 focus:shadow-lg"
                                autocomplete="off">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>

                            <!-- Loading Indicator -->
                            <div wire:loading wire:target="query"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg class="animate-spin h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </div>
                        </div>

                        <!-- Search Suggestions -->
                        @if ($show_suggestions && count($suggestions) > 0)
                            <div
                                class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base overflow-auto">
                                @foreach ($suggestions as $suggestion)
                                    <div wire:click="selectSuggestion('{{ $suggestion }}')"
                                        class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-gray-50">
                                        {{ $suggestion }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Search Actions -->
                    <div class="flex space-x-2 mb-6">
                        <button wire:click="search"
                            class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 hover:shadow-lg focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                            🔍 ค้นหา
                        </button>
                        <button wire:click="clearSearch" title="ล้างเงื่อนไข"
                            class="px-4 py-2 text-gray-600 bg-gray-50 rounded-lg shadow-md hover:bg-gray-100 hover:shadow-lg focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200">
                            🗑️
                        </button>
                    </div>

                    <!-- Filters Toggle -->
                    <div class="mb-4">
                        <button wire:click="toggleFilters"
                            class="flex items-center justify-between w-full text-left text-sm font-medium text-gray-700 hover:text-gray-900">
                            <span>ตัวกรองขั้นสูง</span>
                            <div class="flex items-center space-x-2">
                                @if ($this->hasActiveFilters())
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                        {{ $this->getActiveFiltersCount() }}
                                    </span>
                                @endif
                                <svg class="h-4 w-4 transform transition-transform {{ $show_filters ? 'rotate-180' : '' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </button>
                    </div>

                    <!-- Advanced Filters -->
                    @if ($show_filters)
                        <div class="space-y-4 border-t pt-4">

                            <!-- Category Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">หมวดหมู่</label>
                                <select wire:model.live="category_id"
                                    class="w-full border-0 rounded-lg shadow-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:shadow-lg">
                                    <option value="">-- ทุกหมวดหมู่ --</option>
                                    @foreach ($hierarchical_categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Type Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ประเภทเอกสาร</label>
                                <select wire:model.live="type_id"
                                    class="w-full border-0 rounded-lg shadow-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:shadow-lg">
                                    <option value="">-- ทุกประเภท --</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Department Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">แผนก/หน่วยงาน</label>
                                <select wire:model.live="department_id"
                                    class="w-full border-0 rounded-lg shadow-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:shadow-lg">
                                    <option value="">-- ทุกแผนก --</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Date Range -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ช่วงวันที่</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="date" wire:model.live="date_from"
                                        class="border-0 rounded-lg shadow-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:shadow-lg"
                                        placeholder="จาก">
                                    <input type="date" wire:model.live="date_to"
                                        class="border-0 rounded-lg shadow-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:shadow-lg"
                                        placeholder="ถึง">
                                </div>
                            </div>

                            <!-- Access Level -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ระดับการเข้าถึง</label>
                                <select wire:model.live="access_level"
                                    class="w-full border-0 rounded-lg shadow-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:shadow-lg">
                                    @foreach ($this->getAccessLevelOptions() as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tags -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">แท็ก</label>
                                <div class="flex space-x-2 mb-2">
                                    <input type="text" wire:model="tag_input" wire:keydown.enter="addTag"
                                        placeholder="เพิ่มแท็ก..."
                                        class="flex-1 border-0 rounded-lg shadow-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:shadow-lg">
                                    <button wire:click="addTag"
                                        class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg shadow-md hover:bg-gray-200 hover:shadow-lg transition-all duration-200">
                                        เพิ่ม
                                    </button>
                                </div>

                                <!-- Tag List -->
                                @if (!empty($tags))
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($tags as $index => $tag)
                                            <span
                                                class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                                {{ $tag }}
                                                <button wire:click="removeTag({{ $index }})"
                                                    class="ml-1 text-blue-600 hover:text-blue-800">
                                                    ×
                                                </button>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                        </div>
                    @endif

                    <!-- Recent Searches -->
                    @if (!empty($recent_searches) && empty($query))
                        <hr class="my-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">ค้นหาล่าสุด</h4>
                            <div class="space-y-1">
                                @foreach (array_slice($recent_searches, 0, 5) as $search)
                                    <button wire:click="useRecentSearch('{{ $search }}')"
                                        class="block w-full text-left text-sm text-gray-600 hover:text-gray-900 hover:bg-gray-50 px-2 py-1 rounded">
                                        🕒 {{ $search }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            <!-- Results Section (ย้ายมาขวา) -->
            <div class="lg:col-span-2"> <!-- เปลี่ยนจาก lg:col-span-3 เป็น lg:col-span-2 -->

                <!-- Search Summary & Controls -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6 border-l-8 border-orange-400">
                    <!-- เพิ่ม border-l-8 border-orange-400 -->

                    <!-- Results Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            @if ($total_results > 0)
                                <h2 class="text-lg font-semibold text-gray-900">
                                    ผลการค้นหา ({{ number_format($total_results) }} เอกสาร)
                                </h2>
                                @if ($this->getSearchSummary())
                                    <p class="text-sm text-gray-600 mt-1">{{ $this->getSearchSummary() }}</p>
                                @endif
                            @else
                                <h2 class="text-lg font-semibold text-gray-900">
                                    {{ empty($query) && !$this->hasActiveFilters() ? 'เริ่มต้นค้นหาเอกสาร' : 'ไม่พบเอกสารที่ตรงกับเงื่อนไข' }}
                                </h2>
                            @endif
                        </div>

                        <!-- View & Sort Controls -->
                        @if ($total_results > 0)
                            <div class="flex items-center space-x-4">
                                <!-- Sort Dropdown -->
                                <div class="relative">
                                    <select wire:model.live="sort_by"
                                        class="appearance-none bg-white border-0 rounded-lg shadow-md px-4 py-2 pr-8 text-sm focus:ring-2 focus:ring-blue-500 focus:shadow-lg">
                                        @foreach ($this->getSortOptions() as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <button wire:click="setSortBy('{{ $sort_by }}')"
                                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        @if ($sort_direction === 'asc')
                                            ↑
                                        @else
                                            ↓
                                        @endif
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>

                <!-- Loading State -->
                <div wire:loading
                    wire:target="search,query,category_id,type_id,department_id,date_from,date_to,access_level,sort_by"
                    class="fixed inset-0 bg-black bg-opacity-25 flex items-center justify-center z-50">
                    <div class="bg-white rounded-lg p-6 shadow-xl">
                        <div class="flex items-center space-x-3">
                            <svg class="animate-spin h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span class="text-gray-900 font-medium">กำลังค้นหา...</span>
                        </div>
                    </div>
                </div>

                <!-- Results -->
                @if ($total_results > 0)

                    <!-- Accordion Style Documents List -->
                    <div class="space-y-6">
                        @foreach ($documents as $document)
                            <details
                                class="shadow-lg rounded-lg border-l-8 border-blue-400 bg-white group transition-all duration-200 hover:shadow-xl">
                                <!-- เปลี่ยนจาก border-blue-700 เป็น border-orange-400 -->
                                <summary
                                    class="cursor-pointer w-full text-[15px] font-semibold text-left py-5 px-6 text-slate-800 flex items-center hover:bg-gray-50 transition-colors duration-200 list-none">
                                    <!-- Document Icon -->
                                    <div class="mr-4 shrink-0">
                                        <i
                                            class="{{ $document->type->icon_class ?? 'fas fa-file text-gray-500' }} text-2xl"></i>
                                    </div>

                                    <!-- Document Info -->
                                    <div class="flex-1 mr-4">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h5 class="font-semibold text-gray-900">
                                                @if ($query)
                                                    {!! str_ireplace(
                                                        $query,
                                                        '<mark class="bg-yellow-200 px-1 rounded">' . $query . '</mark>',
                                                        e(Str::limit($document->title, 200, '...')),
                                                    ) !!}
                                                @else
                                                    {{ Str::limit($document->title, 200, '...') }}
                                                @endif
                                            </h5>

                                            <!-- Badges -->
                                            @if ($document->is_featured)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    ⭐ แนะนำ
                                                </span>
                                            @endif

                                            @if ($document->is_new)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    🆕 ใหม่
                                                </span>
                                            @endif

                                            <!-- Status Badge -->
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $document->status_badge['class'] }}">
                                                {{ $document->status_badge['text'] }}
                                            </span>
                                        </div>

                                        <div class="text-xs text-slate-600 mt-0.5 space-y-1">
                                            <div>
                                                @if ($query && $document->description)
                                                    {!! str_ireplace(
                                                        $query,
                                                        '<mark class="bg-yellow-200 px-1 rounded">' . $query . '</mark>',
                                                        e(Str::limit($document->description, 100)),
                                                    ) !!}
                                                @else
                                                    {{ Str::limit($document->description, 100) }}
                                                @endif
                                            </div>
                                            <!-- เพิ่มเส้นแยกระหว่างข้อมูล -->
                                            <div
                                                class="border-t border-gray-100 pt-2 flex items-center gap-4 text-gray-500">
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
                                        <a href="{{ route('documents.show', $document) }}"
                                            class="flex items-center gap-1 px-3 py-1.5 bg-green-500 text-white text-xs rounded-lg shadow-md hover:bg-green-600 hover:shadow-lg transition-all duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            ดาวน์โหลด
                                        </a>

                                        <!-- View Button -->
                                        <a href="{{ route('documents.show', $document) }}"
                                            class="flex items-center gap-1 px-3 py-1.5 bg-blue-500 text-white text-xs rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg transition-all duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                            ดูรายละเอียด
                                        </a>
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
                                                    <div><strong>เลขที่เอกสาร:</strong>
                                                        {{ $document->document_number }}</div>
                                                    <div><strong>วันที่เอกสาร:</strong>
                                                        {{ $document->document_date->format('d/m/Y') }}</div>
                                                    <div><strong>เวอร์ชัน:</strong> {{ $document->version }}</div>
                                                    @if ($document->reference_number)
                                                        <div><strong>เลขที่อ้างอิง:</strong>
                                                            {{ $document->reference_number }}
                                                        </div>
                                                    @endif
                                                    @if ($document->keywords)
                                                        <div><strong>คำสำคัญ:</strong>
                                                            @if ($query)
                                                                {!! str_ireplace(
                                                                    $query,
                                                                    '<mark class="bg-yellow-200 px-1 rounded">' . $query . '</mark>',
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
                                                                    @if ($query)
                                                                        {!! str_ireplace($query, '<mark class="bg-yellow-200 px-1 rounded">' . $query . '</mark>', e($tag)) !!}
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
                                                    <div><strong>ผู้สร้าง:</strong>
                                                        {{ $document->creator->name ?? 'ไม่ระบุ' }}
                                                    </div>
                                                    @if ($document->department)
                                                        <div><strong>หน่วยงาน:</strong>
                                                            {{ $document->department->name }}</div>
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
                                                    @if ($query)
                                                        {!! str_ireplace(
                                                            $query,
                                                            '<mark class="bg-yellow-200 px-1 rounded">' . $query . '</mark>',
                                                            e($document->description),
                                                        ) !!}
                                                    @else
                                                        {{ $document->description }}
                                                    @endif
                                                </p>
                                            </div>
                                        @endif

                                        <!-- Additional Actions -->
                                        <div
                                            class="mt-6 pt-4 border-t border-gray-100 flex justify-between items-center">
                                            <div class="text-xs text-gray-500">
                                                อัปเดตล่าสุด: {{ $document->updated_at->diffForHumans() }}
                                            </div>

                                            <div class="flex gap-2">
                                                <a href="{{ route('documents.show', $document) }}"
                                                    class="flex items-center gap-1 px-4 py-2 bg-green-500 text-white text-sm rounded-lg shadow-md hover:bg-green-600 hover:shadow-lg transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                        </path>
                                                    </svg>
                                                    ดาวน์โหลดเอกสาร
                                                </a>

                                                <a href="{{ route('documents.show', $document) }}"
                                                    class="flex items-center gap-1 px-4 py-2 bg-blue-500 text-white text-sm rounded-lg shadow-md hover:bg-blue-600 hover:shadow-lg transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                                        </path>
                                                    </svg>
                                                    ดูรายละเอียดเพิ่มเติม
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </details>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $documents->links() }}
                    </div>
                @elseif($query || $this->hasActiveFilters())
                    <!-- No Results -->
                    <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                        <div class="text-6xl mb-4">🔍</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">ไม่พบเอกสารที่ตรงกับเงื่อนไข</h3>
                        <p class="text-gray-600 mb-6">ลองปรับเงื่อนไขการค้นหาหรือใช้คำค้นหาอื่น</p>

                        <div class="flex justify-center space-x-4">
                            <button wire:click="clearSearch"
                                class="px-6 py-3 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 hover:shadow-lg transition-all duration-200">
                                ล้างเงื่อนไขทั้งหมด
                            </button>
                            <a href="{{ route('documents.index') }}"
                                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg shadow-md hover:bg-gray-50 hover:shadow-lg transition-all duration-200">
                                ดูเอกสารทั้งหมด
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                        <div class="text-6xl mb-4">📚</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">เริ่มต้นค้นหาเอกสาร</h3>
                        <p class="text-gray-600 mb-6">ป้อนคำค้นหาหรือเลือกเงื่อนไขเพื่อค้นหาเอกสารที่ต้องการ</p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-lg mx-auto">
                            <div class="text-center p-4 border-0 rounded-lg shadow-md">
                                <div class="text-2xl mb-2">🔤</div>
                                <div class="text-sm text-gray-600">ค้นหาจากชื่อ</div>
                            </div>
                            <div class="text-center p-4 border-0 rounded-lg shadow-md">
                                <div class="text-2xl mb-2">📁</div>
                                <div class="text-sm text-gray-600">เลือกหมวดหมู่</div>
                            </div>
                            <div class="text-center p-4 border-0 rounded-lg shadow-md">
                                <div class="text-2xl mb-2">🏷️</div>
                                <div class="text-sm text-gray-600">ใช้แท็ก</div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        <!-- JavaScript and Styles -->
        <script>
            // Handle share window opening
            document.addEventListener('livewire:init', () => {
                Livewire.on('open-share-window', (url) => {
                    window.open(url, '_blank', 'width=600,height=400');
                });

                Livewire.on('copy-to-clipboard', (text) => {
                    navigator.clipboard.writeText(text).then(() => {
                        // Success handled by Livewire notification
                    }).catch(err => {
                        console.error('Failed to copy: ', err);
                    });
                });

                Livewire.on('print-document', () => {
                    window.print();
                });
            });
        </script>

        <style>
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .line-clamp-3 {
                display: -webkit-box;
                -webkit-line-clamp: 3;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            mark {
                background-color: #fef08a;
                padding: 0.125rem 0.25rem;
                border-radius: 0.25rem;
            }

            /* Enhanced Shadow Effects */
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

            /* Focus Shadow Effects */
            .focus\:shadow-lg:focus {
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            }
        </style>
    </div>
