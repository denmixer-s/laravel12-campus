{{-- resources/views/livewire/backend/document/document-create.blade.php --}}
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8 w-full">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <div
                                class="h-8 w-8 rounded-lg bg-gradient-to-r from-green-500 to-blue-600 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h1 class="text-3xl font-bold text-slate-800">เพิ่มเอกสารใหม่</h1>
                        </div>
                        <p class="text-slate-600">เพิ่มเอกสารใหม่เข้าสู่ระบบจัดการเอกสาร</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <button wire:click="cancel" type="button"
                            class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            ยกเลิก
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if ($showSuccessMessage)
            <div class="mb-6">
                <div class="bg-green-50 rounded-lg border border-green-200 p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-green-800 font-medium">{{ $successMessage }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form wire:submit="create">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-800 mb-6">ข้อมูลพื้นฐาน</h2>

                        <!-- Title Input -->
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-slate-700 mb-2">
                                ชื่อเอกสาร <span class="text-red-500">*</span>
                            </label>
                            <input wire:model.live.debounce.300ms="title" type="text" id="title"
                                placeholder="กรอกชื่อเอกสาร"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('title') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('title')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Description Input -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-2">
                                คำอธิบาย
                                <span class="text-slate-500 text-xs">(รายละเอียดเอกสาร)</span>
                            </label>
                            <textarea wire:model.live.debounce.500ms="description" id="description" rows="4"
                                placeholder="กรอกคำอธิบายเอกสาร..."
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('description') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"></textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Keywords and Reference Number -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="keywords" class="block text-sm font-medium text-slate-700 mb-2">
                                    คำสำคัญ
                                    <span class="text-slate-500 text-xs">(คั่นด้วยจุลภาค)</span>
                                </label>
                                <input wire:model.live.debounce.500ms="keywords" type="text" id="keywords"
                                    placeholder="คำสำคัญ1, คำสำคัญ2, คำสำคัญ3"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('keywords') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('keywords')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="reference-number" class="block text-sm font-medium text-slate-700 mb-2">
                                    เลขที่อ้างอิง
                                </label>
                                <input wire:model.live.debounce.500ms="reference_number" type="text" id="reference-number"
                                    placeholder="เลขที่อ้างอิงเอกสาร"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('reference_number') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('reference_number')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Document Classification Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-800 mb-6">การจัดหมวดหมู่</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Category Selection -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-slate-700 mb-2">
                                    หมวดหมู่เอกสาร <span class="text-red-500">*</span>
                                </label>
                                <select wire:model.live="document_category_id" id="category"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('document_category_id') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">เลือกหมวดหมู่...</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->parent ? '— ' : '' }}{{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('document_category_id')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Type Selection -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-slate-700 mb-2">
                                    ประเภทเอกสาร <span class="text-red-500">*</span>
                                </label>
                                <select wire:model.live="document_type_id" id="type"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('document_type_id') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">เลือกประเภท...</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                @error('document_type_id')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <!-- Department Selection (Admin Only) -->
                            @if ($this->canSelectDepartment)
                                <div>
                                    <label for="department" class="block text-sm font-medium text-slate-700 mb-2">
                                        หน่วยงาน
                                    </label>
                                    <select wire:model.live="department_id" id="department"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="">เลือกหน่วยงาน...</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            @else
                                <!-- Display Current User's Department (Read-only) -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">
                                        หน่วยงาน
                                    </label>
                                    <div class="w-full px-4 py-3 border border-slate-200 rounded-lg bg-slate-50 text-slate-600">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h2M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            {{ $this->currentUserDepartment?->name ?? 'ไม่มีหน่วยงาน' }}
                                        </div>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-500">
                                        เอกสารจะถูกสร้างในหน่วยงานของคุณโดยอัตโนมัติ
                                    </p>
                                </div>
                            @endif

                            <!-- Document Date -->
                            <div>
                                <label for="document-date" class="block text-sm font-medium text-slate-700 mb-2">
                                    วันที่เอกสาร <span class="text-red-500">*</span>
                                </label>
                                <input wire:model.live="document_date" type="date" id="document-date"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('document_date') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('document_date')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- File Upload Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-800 mb-6">
                            ไฟล์เอกสาร <span class="text-red-500">*</span>
                        </h2>

                        @if ($documentFile)
                            <div class="mb-4">
                                <div class="relative inline-block w-full">
                                    <div class="bg-slate-50 border border-slate-200 rounded-lg p-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-medium text-slate-900 truncate">
                                                    {{ $this->fileInfo['name'] ?? '' }}
                                                </h4>
                                                <p class="text-sm text-slate-500">
                                                    ขนาด: {{ $this->fileInfo['size'] ?? '' }}
                                                    @if (isset($this->fileInfo['type']))
                                                        • ประเภท: {{ $this->fileInfo['type'] }}
                                                    @endif
                                                </p>
                                            </div>
                                            <button wire:click="removeDocumentFile" type="button"
                                                class="flex-shrink-0 bg-red-100 hover:bg-red-200 text-red-600 rounded-full p-1 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div
                            class="border-2 border-dashed border-slate-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors @error('documentFile') border-red-300 @enderror">
                            <input wire:model="documentFile" type="file"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                                class="hidden" id="document-file-upload">

                            <label for="document-file-upload" class="cursor-pointer">
                                <div class="space-y-2">
                                    <svg class="w-12 h-12 text-slate-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <div>
                                        <p class="text-slate-600 font-medium">คลิกเพื่ออัพโหลดไฟล์เอกสาร</p>
                                        <p class="text-slate-500 text-sm">PDF, Word, Excel, PowerPoint สูงสุด 50MB</p>
                                    </div>
                                </div>
                            </label>
                        </div>

                        @error('documentFile')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror

                        <div wire:loading wire:target="documentFile" class="mt-2">
                            <div class="flex items-center text-sm text-blue-600">
                                <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                กำลังอัพโหลดไฟล์...
                            </div>
                        </div>
                    </div>

                    <!-- Tags Management Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">แท็ก</h3>

                        <div class="mb-4">
                            <div class="flex">
                                <input wire:model="newTag" wire:keydown.enter="addTag" type="text"
                                    placeholder="เพิ่มแท็กใหม่..."
                                    class="flex-1 px-4 py-2 border border-slate-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <button wire:click="addTag" type="button"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-r-lg transition-colors">
                                    เพิ่ม
                                </button>
                            </div>
                        </div>

                        @if (!empty($tags))
                            <div class="mb-4">
                                <p class="text-sm font-medium text-slate-700 mb-2">แท็กที่เลือก:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($tags as $index => $tag)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $tag }}
                                            <button wire:click="removeTag({{ $index }})" type="button"
                                                class="ml-2 inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-blue-200 transition-colors">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Publishing Options Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">ตัวเลือกการเผยแพร่</h3>

                        <div class="mb-4">
                            {{-- Progress Indicator --}}
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs text-slate-500">ความคืบหน้า:</span>
                                    <span class="text-xs text-slate-500">{{ $this->progress }}%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-green-500 to-blue-600 h-2 rounded-full transition-all duration-300"
                                        style="width: {{ $this->progress }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="status" class="block text-sm font-medium text-slate-700 mb-2">
                                สถานะ <span class="text-red-500">*</span>
                            </label>
                            <select wire:model.live="status" id="status"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="draft">📝 ร่าง</option>
                                <option value="published">🚀 เผยแพร่</option>
                                <option value="archived">📦 เก็บถาวร</option>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label for="access-level" class="block text-sm font-medium text-slate-700 mb-2">
                                ระดับการเข้าถึง
                            </label>
                            <select wire:model.live="access_level" id="access-level"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="public">🌐 สาธารณะ</option>
                                <option value="registered">🔒 สมาชิก</option>
                            </select>
                        </div>

                        @if ($status === 'published')
                            <div class="mb-6">
                                <label for="published-at" class="block text-sm font-medium text-slate-700 mb-2">
                                    วันที่เผยแพร่
                                </label>
                                <input wire:model.live="published_at" type="datetime-local" id="published-at"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                        @endif

                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input wire:model.live="is_featured" type="checkbox" id="is-featured"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                                <label for="is-featured" class="ml-3 block text-sm text-slate-700">
                                    ⭐ เอกสารแนะนำ
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input wire:model.live="is_new" type="checkbox" id="is-new"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                                <label for="is-new" class="ml-3 block text-sm text-slate-700">
                                    🆕 เอกสารใหม่
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Version Control Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">การควบคุมเวอร์ชัน</h3>

                        <div class="mb-4">
                            <label for="version" class="block text-sm font-medium text-slate-700 mb-2">
                                เวอร์ชัน <span class="text-red-500">*</span>
                            </label>
                            <input wire:model.live="version" type="text" id="version" placeholder="1.0"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('version') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('version')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="parent-document" class="block text-sm font-medium text-slate-700 mb-2">
                                เอกสารต้นฉบับ
                                <span class="text-slate-500 text-xs">(ถ้าเป็นเวอร์ชันใหม่)</span>
                            </label>
                            <select wire:model.live="parent_document_id" id="parent-document"
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">ไม่มีเอกสารต้นฉบับ</option>
                                @foreach ($parentDocuments as $parentDoc)
                                    <option value="{{ $parentDoc->id }}">
                                        {{ Str::limit($parentDoc->title, 50) }} (v{{ $parentDoc->version }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Auto-save Status -->
                    @if ($autoSaveEnabled)
                        <div class="bg-blue-50 rounded-lg border border-blue-200 p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-blue-800">Auto-save เปิดใช้งาน</p>
                                    @if ($lastSavedAt)
                                        <p class="text-xs text-blue-600">บันทึกล่าสุด: {{ $lastSavedAt->format('H:i:s') }}</p>
                                    @endif
                                </div>
                                <button wire:click="toggleAutoSave" type="button"
                                    class="ml-auto text-blue-600 hover:text-blue-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <button type="submit" wire:loading.attr="disabled" {{ !$this->canCreate ? 'disabled' : '' }}
                            class="w-full inline-flex justify-center items-center px-4 py-3 bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 disabled:from-slate-300 disabled:to-slate-400 text-white font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="create">
                                @if ($status === 'draft')
                                    📝 สร้างเอกสารร่าง
                                @elseif($status === 'published')
                                    🚀 เผยแพร่เอกสาร
                                @else
                                    💾 บันทึกเอกสาร
                                @endif
                            </span>
                            <span wire:loading wire:target="create" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                กำลังสร้าง...
                            </span>
                        </button>

                        @if (!$this->canCreate)
                            <p class="mt-2 text-xs text-slate-500 text-center">
                                กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน
                            </p>
                        @endif

                        <div class="mt-3 space-y-2">
                            <button wire:click="saveDraft" type="button" {{ $isProcessing ? 'disabled' : '' }}
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-amber-100 hover:bg-amber-200 text-amber-700 font-medium rounded-lg transition-colors duration-200 disabled:opacity-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                บันทึกร่าง
                            </button>

                            <button wire:click="resetForm" type="button"
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                รีเซ็ตฟอร์ม
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Loading Overlay -->
    <div wire:loading.flex wire:target="create,documentFile"
        class="fixed inset-0 z-50 bg-white bg-opacity-75 flex items-center justify-center">
        <div class="flex flex-col items-center space-y-4">
            <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                    stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <p class="text-sm text-slate-600 font-medium" wire:loading.remove wire:target="documentFile">
                กำลังสร้างเอกสาร...
            </p>
            <p class="text-sm text-slate-600 font-medium" wire:loading wire:target="documentFile">
                กำลังอัพโหลดไฟล์...
            </p>
        </div>
    </div>

    <!-- Success/Error Messages (Toast Style) -->
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

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 z-50 max-w-md">
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 shadow-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Inline Scripts -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            console.log('DocumentCreate component initialized');

            // Auto-hide success/error messages
            setTimeout(() => {
                const messages = document.querySelectorAll('.fixed.top-4.right-4');
                messages.forEach(msg => {
                    msg.style.transition = 'opacity 0.5s';
                    msg.style.opacity = '0';
                    setTimeout(() => msg.remove(), 500);
                });
            }, 5000);

            // File input validation
            const fileInput = document.getElementById('document-file-upload');
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        // Check file size (50MB = 52428800 bytes)
                        if (file.size > 52428800) {
                            alert('ไฟล์มีขนาดใหญ่เกินไป (สูงสุด 50MB)');
                            e.target.value = '';
                            return;
                        }

                        // Check file type
                        const allowedTypes = [
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-powerpoint',
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation'
                        ];

                        if (!allowedTypes.includes(file.type)) {
                            alert('ประเภทไฟล์ไม่ถูกต้อง กรุณาเลือกไฟล์ PDF, Word, Excel หรือ PowerPoint');
                            e.target.value = '';
                            return;
                        }
                    }
                });
            }

            // Auto-save functionality
            let autoSaveTimeout;
            window.addEventListener('scheduleAutoSave', function(e) {
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => {
                    @this.call('autoSaveDraft');
                }, e.detail.interval);
            });

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + S to save
                if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                    e.preventDefault();
                    @this.call('create');
                }

                // Ctrl/Cmd + D to save draft
                if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
                    e.preventDefault();
                    @this.call('saveDraft');
                }
            });
        });

        // Listen for auto-save events
        document.addEventListener('autoSaveSuccess', () => {
            // Show subtle success indicator
            const indicator = document.createElement('div');
            indicator.className = 'fixed bottom-4 right-4 bg-green-100 text-green-800 px-3 py-2 rounded-lg text-sm';
            indicator.textContent = 'บันทึกอัตโนมัติสำเร็จ';
            document.body.appendChild(indicator);

            setTimeout(() => {
                indicator.style.transition = 'opacity 0.3s';
                indicator.style.opacity = '0';
                setTimeout(() => indicator.remove(), 300);
            }, 2000);
        });

        document.addEventListener('autoSaveFailed', () => {
            console.warn('Auto-save failed');
        });
    </script>

    <!-- Inline Styles -->
    <style>
        .bg-gradient-to-br {
            background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 100%);
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        .shadow-sm {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .shadow-md {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .hover\:shadow-md:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .focus\:ring-2:focus {
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }

        /* File upload area styling */
        #document-file-upload:focus + label {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }

        /* Progress bar animation */
        .bg-gradient-to-r {
            transition: width 0.3s ease-in-out;
        }

        /* Tag styling improvements */
        .inline-flex.items-center.px-3.py-1 {
            transition: all 0.2s ease-in-out;
        }

        .inline-flex.items-center.px-3.py-1:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Loading states */
        .disabled\:opacity-50:disabled {
            opacity: 0.5;
        }

        .disabled\:cursor-not-allowed:disabled {
            cursor: not-allowed;
        }

        /* Toast message animations */
        .fixed.top-4.right-4 {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</div>
