<div class="min-h-screen bg-gray-50" x-data="{ showShareModal: @entangle('showShareModal'), showReportModal: @entangle('showReportModal') }">
    <!-- Breadcrumb -->
    <div class="bg-white border-b border-gray-200 border-l-8 border-orange-400 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    @foreach($this->breadcrumbs as $index => $breadcrumb)
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
                                <span class="text-gray-900 text-sm font-medium">{{ Str::limit($breadcrumb['name'], 50) }}</span>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="lg:grid lg:grid-cols-12 lg:gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-8">
                <!-- Document Header -->
                <div class="bg-white rounded-lg shadow-lg border-l-8 border-orange-400 overflow-hidden hover:shadow-xl transition-all duration-200">
                    <!-- Header Section -->
                    <div class="px-6 py-6 border-b border-gray-200">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <!-- Title -->
                                <h1 class="text-2xl font-bold text-gray-900 mb-3">{{ $document->title }}</h1>

                                <!-- Badges -->
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @if($document->is_featured)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                            ⭐ แนะนำ
                                        </span>
                                    @endif
                                    @if($document->is_new)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            🆕 ใหม่
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $document->status_badge['class'] }}">
                                        {{ $document->status_badge['text'] }}
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $document->access_level_badge['class'] }}">
                                        {{ $document->access_level_badge['text'] }}
                                    </span>
                                </div>

                                <!-- Document Meta -->
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                        </svg>
                                        <span>{{ $document->category->name }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>{{ $document->type->name }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a1 1 0 011 1v1a1 1 0 01-1 1H5a1 1 0 01-1-1V8a1 1 0 011-1h3z"></path>
                                        </svg>
                                        <span>{{ $this->documentStats['file_size'] }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a1 1 0 011 1v1a1 1 0 01-1 1H5a1 1 0 01-1-1V8a1 1 0 011-1h3z"></path>
                                        </svg>
                                        <span>{{ $this->documentStats['published_date'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row gap-2 ml-4">
                                <button wire:click="downloadDocument"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg shadow-md hover:bg-green-700 hover:shadow-lg transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    ดาวน์โหลด
                                </button>
                                <button @click="showShareModal = true"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow-md hover:bg-blue-700 hover:shadow-lg transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                    </svg>
                                    แชร์
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Document Stats -->
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                            <div>
                                <div class="text-2xl font-bold text-gray-900">{{ $this->documentStats['views'] }}</div>
                                <div class="text-sm text-gray-600">ครั้งที่เปิดดู</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900">{{ $this->documentStats['downloads'] }}</div>
                                <div class="text-sm text-gray-600">ครั้งที่ดาวน์โหลด</div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $document->version }}</div>
                                <div class="text-sm text-gray-600">เวอร์ชัน</div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $this->documentStats['last_updated'] }}</div>
                                <div class="text-sm text-gray-600">อัปเดตล่าสุด</div>
                            </div>
                        </div>
                    </div>

                    <!-- Document Content -->
                    <div class="px-6 py-6">
                        @if($document->description)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">รายละเอียดเอกสาร</h3>
                                <div class="prose max-w-none text-gray-700">
                                    {!! nl2br(e($document->description)) !!}
                                </div>
                            </div>
                        @endif

                        <!-- Document Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Information -->
                            <div>
                                <h4 class="text-md font-semibold text-gray-900 mb-3">ข้อมูลพื้นฐาน</h4>
                                <dl class="space-y-2">
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">เลขที่เอกสาร:</dt>
                                        <dd class="text-sm font-medium text-gray-900">{{ $document->document_number }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">วันที่เอกสาร:</dt>
                                        <dd class="text-sm font-medium text-gray-900">{{ $document->document_date->format('d/m/Y') }}</dd>
                                    </div>
                                    @if($document->reference_number)
                                        <div class="flex justify-between">
                                            <dt class="text-sm text-gray-600">เลขที่อ้างอิง:</dt>
                                            <dd class="text-sm font-medium text-gray-900">{{ $document->reference_number }}</dd>
                                        </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">ขนาดไฟล์:</dt>
                                        <dd class="text-sm font-medium text-gray-900">{{ $this->documentStats['file_size'] }}</dd>
                                    </div>
                                    @if($document->original_filename)
                                        <div class="flex justify-between">
                                            <dt class="text-sm text-gray-600">ชื่อไฟล์ต้นฉบับ:</dt>
                                            <dd class="text-sm font-medium text-gray-900">{{ $document->original_filename }}</dd>
                                        </div>
                                    @endif
                                </dl>
                            </div>

                            <!-- Creator & Organization -->
                            <div>
                                <h4 class="text-md font-semibold text-gray-900 mb-3">ข้อมูลผู้จัดทำ</h4>
                                <dl class="space-y-2">
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">ผู้สร้าง:</dt>
                                        <dd class="text-sm font-medium text-gray-900">{{ $document->creator->name ?? 'ไม่ระบุ' }}</dd>
                                    </div>
                                    @if($document->department)
                                        <div class="flex justify-between">
                                            <dt class="text-sm text-gray-600">หน่วยงาน:</dt>
                                            <dd class="text-sm font-medium text-gray-900">{{ $document->department->name }}</dd>
                                        </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">วันที่สร้าง:</dt>
                                        <dd class="text-sm font-medium text-gray-900">{{ $document->created_at->format('d/m/Y H:i') }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">วันที่เผยแพร่:</dt>
                                        <dd class="text-sm font-medium text-gray-900">{{ $document->published_at?->format('d/m/Y H:i') }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Keywords and Tags -->
                        @if($document->keywords || ($document->tags && count($document->tags) > 0))
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                @if($document->keywords)
                                    <div class="mb-4">
                                        <h4 class="text-md font-semibold text-gray-900 mb-2">คำสำคัญ</h4>
                                        <p class="text-sm text-gray-700">{{ $document->keywords }}</p>
                                    </div>
                                @endif

                                @if($document->tags && count($document->tags) > 0)
                                    <div>
                                        <h4 class="text-md font-semibold text-gray-900 mb-2">แท็ก</h4>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($document->tags as $tag)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                    {{ $tag }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Additional Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <div class="flex flex-wrap gap-2">
                            <button wire:click="addToFavorites"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border-0 rounded-lg shadow-md hover:bg-gray-50 hover:shadow-lg transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                เพิ่มในรายการโปรด
                            </button>
                            <button wire:click="printDocument"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border-0 rounded-lg shadow-md hover:bg-gray-50 hover:shadow-lg transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                พิมพ์
                            </button>
                            <button @click="showReportModal = true"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-700 bg-white border-0 rounded-lg shadow-md hover:bg-red-50 hover:shadow-lg transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                รายงานปัญหา
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Related Documents -->
                @if($this->relatedDocuments->count() > 0)
                    <div class="mt-8 bg-white rounded-lg shadow-lg border-l-8 border-orange-400 overflow-hidden hover:shadow-xl transition-all duration-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">เอกสารที่เกี่ยวข้อง</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($this->relatedDocuments as $relatedDoc)
                                    <div class="border-0 border-gray-200 rounded-lg shadow-md p-4 hover:shadow-lg transition-all duration-200">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 mr-3">
                                                <i class="{{ $relatedDoc->type->icon_class }} text-xl text-gray-600"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-medium text-gray-900 mb-1">
                                                    <a href="{{ route('documents.show', $relatedDoc) }}" class="hover:text-blue-600">
                                                        {{ Str::limit($relatedDoc->title, 60) }}
                                                    </a>
                                                </h4>
                                                <p class="text-xs text-gray-600 mb-2">{{ Str::limit($relatedDoc->description, 80) }}</p>
                                                <div class="flex items-center text-xs text-gray-500">
                                                    <span>{{ $relatedDoc->category->name }}</span>
                                                    <span class="mx-2">•</span>
                                                    <span>{{ $relatedDoc->published_at?->format('d/m/Y') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-4 mt-8 lg:mt-0">
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-lg border-l-8 border-orange-400 p-6 mb-6 hover:shadow-xl transition-all duration-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">การดำเนินการ</h3>
                    <div class="space-y-3">
                        <button wire:click="downloadDocument"
                            class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white text-sm font-medium rounded-lg shadow-md hover:bg-green-700 hover:shadow-lg transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            ดาวน์โหลดเอกสาร
                        </button>
                        <button wire:click="viewInCategory"
                            class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow-md hover:bg-blue-700 hover:shadow-lg transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            </svg>
                            ดูเอกสารในหมวดหมู่นี้
                        </button>
                        <button wire:click="goBack"
                            class="w-full flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg shadow-md hover:bg-gray-700 hover:shadow-lg transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            กลับ
                        </button>
                    </div>
                </div>

                <!-- Document Info -->
                <div class="bg-white rounded-lg shadow-lg border-l-8 border-orange-400 p-6 mb-6 hover:shadow-xl transition-all duration-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">ข้อมูลเอกสาร</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-600">หมวดหมู่</dt>
                            <dd class="text-sm text-gray-900 mt-1">
                                <button wire:click="viewInCategory" class="text-blue-600 hover:text-blue-800">
                                    {{ $document->category->name }}
                                </button>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-600">ประเภทเอกสาร</dt>
                            <dd class="text-sm text-gray-900 mt-1">
                                <button wire:click="viewByType" class="text-blue-600 hover:text-blue-800">
                                    {{ $document->type->name }}
                                </button>
                            </dd>
                        </div>
                        @if($document->department)
                            <div>
                                <dt class="text-sm font-medium text-gray-600">หน่วยงาน</dt>
                                <dd class="text-sm text-gray-900 mt-1">
                                    <button wire:click="viewByDepartment" class="text-blue-600 hover:text-blue-800">
                                        {{ $document->department->name }}
                                    </button>
                                </dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-600">เวอร์ชัน</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $document->version }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-600">ขนาดไฟล์</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $this->documentStats['file_size'] }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Same Category Documents -->
                @if($this->sameCategoryDocuments->count() > 0)
                    <div class="bg-white rounded-lg shadow-lg border-l-8 border-orange-400 p-6 hover:shadow-xl transition-all duration-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">เอกสารในหมวดหมู่เดียวกัน</h3>
                        <div class="space-y-3">
                            @foreach($this->sameCategoryDocuments->take(5) as $sameDoc)
                                <div class="border-b border-gray-200 pb-3 last:border-b-0 last:pb-0">
                                    <h4 class="text-sm font-medium text-gray-900 mb-1">
                                        <a href="{{ route('documents.show', $sameDoc) }}" class="hover:text-blue-600">
                                            {{ Str::limit($sameDoc->title, 50) }}
                                        </a>
                                    </h4>
                                    <div class="flex items-center text-xs text-gray-500">
                                        <span>{{ $sameDoc->type->name }}</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ $sameDoc->published_at?->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($this->sameCategoryDocuments->count() > 5)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <button wire:click="viewInCategory" class="text-sm text-blue-600 hover:text-blue-800">
                                    ดูเอกสารทั้งหมดในหมวดหมู่นี้ ({{ $this->sameCategoryDocuments->count() }} รายการ)
                                </button>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Share Modal -->
    <div x-show="showShareModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.away="showShareModal = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 transform translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 transform translate-y-4 sm:translate-y-0 sm:scale-95"
             class="bg-white rounded-lg shadow-xl p-6 m-4 max-w-md w-full border-l-8 border-orange-400">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">แชร์เอกสาร</h3>
                <button @click="showShareModal = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <button wire:click="shareVia('facebook')"
                    class="flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 hover:shadow-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    Facebook
                </button>

                <button wire:click="shareVia('twitter')"
                    class="flex items-center justify-center px-4 py-3 bg-blue-400 text-white rounded-lg shadow-md hover:bg-blue-500 hover:shadow-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                    </svg>
                    Twitter
                </button>

                <button wire:click="shareVia('line')"
                    class="flex items-center justify-center px-4 py-3 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600 hover:shadow-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 10.304c0-5.369-5.383-9.738-12-9.738S0 4.935 0 10.304c0 4.839 4.295 8.89 10.105 9.605.394.085.928.259 1.063.594.123.305.081.784.04 1.093l-.172 1.03c-.053.303-.242 1.186 1.039.647 1.281-.54 6.911-4.069 9.428-6.967C23.295 14.191 24 12.345 24 10.304z"/>
                    </svg>
                    LINE
                </button>

                <button wire:click="shareVia('email')"
                    class="flex items-center justify-center px-4 py-3 bg-gray-600 text-white rounded-lg shadow-md hover:bg-gray-700 hover:shadow-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    อีเมล
                </button>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex items-center">
                    <input type="text" value="{{ $this->shareUrl }}" readonly
                        class="flex-1 px-3 py-2 border-0 rounded-l-lg bg-gray-50 text-sm shadow-md focus:shadow-lg">
                    <button wire:click="shareVia('copy')"
                        class="px-4 py-2 bg-blue-600 text-white rounded-r-lg shadow-md hover:bg-blue-700 hover:shadow-lg transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Modal -->
    <div x-show="showReportModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div @click.away="showReportModal = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 transform translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 transform translate-y-4 sm:translate-y-0 sm:scale-95"
             class="bg-white rounded-lg shadow-xl p-6 m-4 max-w-md w-full border-l-8 border-orange-400">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">รายงานปัญหา</h3>
                <button @click="showReportModal = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form wire:submit="submitReport">
                <div class="mb-4">
                    <label for="reportReason" class="block text-sm font-medium text-gray-700 mb-2">
                        เหตุผลในการรายงาน
                    </label>
                    <textarea wire:model="reportReason" id="reportReason" rows="4"
                        class="w-full px-3 py-2 border-0 rounded-lg shadow-md focus:ring-2 focus:ring-blue-500 focus:shadow-lg"
                        placeholder="กรุณาอธิบายปัญหาที่พบ..."></textarea>
                    @error('reportReason')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" @click="showReportModal = false"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border-0 rounded-lg shadow-md hover:bg-gray-50 hover:shadow-lg transition-all duration-200">
                        ยกเลิก
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg shadow-md hover:bg-red-700 hover:shadow-lg transition-all duration-200">
                        ส่งรายงาน
                    </button>
                </div>
            </form>
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

        // SEO and Social Meta Tags
        document.addEventListener('DOMContentLoaded', function() {
            // Update page title dynamically
            document.title = '{{ $document->title }} - ระบบจัดการเอกสาร';

            // Add meta description
            let metaDescription = document.querySelector('meta[name="description"]');
            if (!metaDescription) {
                metaDescription = document.createElement('meta');
                metaDescription.name = 'description';
                document.head.appendChild(metaDescription);
            }
            metaDescription.content = '{{ Str::limit(strip_tags($document->description), 160) }}';

            // Add Open Graph meta tags
            const ogTags = [
                { property: 'og:title', content: '{{ $document->title }}' },
                { property: 'og:description', content: '{{ Str::limit(strip_tags($document->description), 160) }}' },
                { property: 'og:url', content: '{{ $this->shareUrl }}' },
                { property: 'og:type', content: 'article' },
                { property: 'og:site_name', content: 'ระบบจัดการเอกสาร' }
            ];

            ogTags.forEach(tag => {
                let metaTag = document.querySelector(`meta[property="${tag.property}"]`);
                if (!metaTag) {
                    metaTag = document.createElement('meta');
                    metaTag.property = tag.property;
                    document.head.appendChild(metaTag);
                }
                metaTag.content = tag.content;
            });
        });
    </script>
</div>
