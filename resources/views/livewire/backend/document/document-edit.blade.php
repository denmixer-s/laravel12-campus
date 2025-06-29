{{-- resources/views/livewire/backend/document/document-edit.blade.php --}}
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8 w-full">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <div
                                class="h-8 w-8 rounded-lg bg-gradient-to-r from-amber-500 to-orange-600 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <h1 class="text-3xl font-bold text-slate-800">แก้ไขเอกสาร</h1>
                        </div>
                        <p class="text-slate-600">แก้ไขข้อมูลเอกสาร: {{ $document->document_number }}</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('documents.show', $document) }}" target="_blank"
                            class="inline-flex items-center px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            ดูเอกสาร
                        </a>
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

        <form wire:submit="update">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Document Info Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-slate-800">ข้อมูลเอกสาร</h2>
                            <div class="text-sm text-slate-500">
                                <span class="font-medium">เลขที่:</span> {{ $document->document_number }}
                            </div>
                        </div>

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
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
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
                                <input wire:model.live.debounce.500ms="reference_number" type="text"
                                    id="reference-number" placeholder="เลขที่อ้างอิงเอกสาร"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('reference_number') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('reference_number')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
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
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
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
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
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
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            @else
                                <!-- Display Current Document's Department (Read-only) -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">
                                        หน่วยงาน
                                    </label>
                                    <div
                                        class="w-full px-4 py-3 border border-slate-200 rounded-lg bg-slate-50 text-slate-600">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-slate-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h2M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            {{ $document->department?->name ?? 'ไม่มีหน่วยงาน' }}
                                        </div>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-500">
                                        เฉพาะผู้ดูแลระบบเท่านั้นที่สามารถเปลี่ยนหน่วยงานได้
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
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- File Management Card -->
                    <!-- File Management Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h2 class="text-xl font-semibold text-slate-800 mb-6">จัดการไฟล์เอกสาร</h2>

                        <!-- Upload New File or Show Current File -->
                        @if ($documentFile)
                            <!-- New File Selected -->
                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-slate-700 mb-3">ไฟล์ใหม่ที่เลือก:</h4>
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <svg class="w-10 h-10 text-green-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
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
                                                    • <span class="text-green-600 font-medium">ไฟล์ใหม่</span>
                                                </p>
                                            </div>
                                        </div>
                                        <button wire:click="removeDocumentFile" type="button"
                                            class="inline-flex items-center px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-medium rounded transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            ยกเลิก
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @elseif ($existingFile && !$fileMarkedForRemoval)
                            <!-- Existing File Display -->
                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-slate-700 mb-3">ไฟล์ปัจจุบัน:</h4>
                                <div class="bg-slate-50 border border-slate-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <svg class="w-10 h-10 text-blue-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-medium text-slate-900 truncate">
                                                    {{ $existingFile['name'] ?? '' }}
                                                </h4>
                                                <p class="text-sm text-slate-500">
                                                    ขนาด: {{ $existingFile['size'] ?? '' }}
                                                    @if (isset($existingFile['type']))
                                                        • ประเภท: {{ $existingFile['type'] }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            @if (isset($existingFile['url']))
                                                <a href="{{ $existingFile['url'] }}" target="_blank"
                                                    class="inline-flex items-center px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-medium rounded transition-colors">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    ดาวน์โหลด
                                                </a>
                                            @endif
                                            <button wire:click="removeExistingFile" type="button"
                                                class="inline-flex items-center px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-medium rounded transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                ลบไฟล์
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif ($fileMarkedForRemoval)
                            <!-- File Removed State -->
                            <div class="mb-6">
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-red-500 mr-2" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.996-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                            </svg>
                                            <span
                                                class="text-sm font-medium text-red-800">ไฟล์จะถูกลบเมื่อบันทึกเอกสาร</span>
                                        </div>
                                        <button wire:click="restoreExistingFile" type="button"
                                            class="inline-flex items-center px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-medium rounded transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                            </svg>
                                            เรียกคืน
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Upload New File Area -->
                        @if (!$documentFile)
                            <div
                                class="border-2 border-dashed border-slate-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors @error('documentFile') border-red-300 @enderror">
                                <input wire:model="documentFile" type="file"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" class="hidden"
                                    id="document-file-upload">

                                <label for="document-file-upload" class="cursor-pointer">
                                    <div class="space-y-2">
                                        <svg class="w-12 h-12 text-slate-400 mx-auto" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <div>
                                            @if ($existingFile && !$fileMarkedForRemoval)
                                                <p class="text-slate-600 font-medium">คลิกเพื่ออัพโหลดไฟล์ใหม่
                                                    (แทนที่ไฟล์เดิม)</p>
                                            @else
                                                <p class="text-slate-600 font-medium">คลิกเพื่ออัพโหลดไฟล์เอกสาร</p>
                                            @endif
                                            <p class="text-slate-500 text-sm">PDF, Word, Excel, PowerPoint สูงสุด 50MB
                                            </p>
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
                        @endif
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
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $tag }}
                                            <button wire:click="removeTag({{ $index }})" type="button"
                                                class="ml-2 inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-blue-200 transition-colors">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
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
                                    <div class="bg-gradient-to-r from-amber-500 to-orange-600 h-2 rounded-full transition-all duration-300"
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
                            @error('parent_document_id')
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

                    <!-- Document History -->
                    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">ข้อมูลเอกสาร</h3>

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-600">สร้างเมื่อ:</span>
                                <span class="text-slate-900">{{ $document->created_at->format('d M Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">แก้ไขล่าสุด:</span>
                                <span class="text-slate-900">{{ $document->updated_at->format('d M Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">ผู้สร้าง:</span>
                                <span class="text-slate-900">{{ $document->creator?->name ?? 'ไม่ระบุ' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">ดาวน์โหลด:</span>
                                <span class="text-slate-900">{{ number_format($document->download_count) }}
                                    ครั้ง</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">เปิดดู:</span>
                                <span class="text-slate-900">{{ number_format($document->view_count) }} ครั้ง</span>
                            </div>
                        </div>
                    </div>

                    <!-- Auto-save Status -->
                    @if ($autoSaveEnabled)
                        <div class="bg-blue-50 rounded-lg border border-blue-200 p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-blue-800">Auto-save เปิดใช้งาน</p>
                                    @if ($lastSavedAt)
                                        <p class="text-xs text-blue-600">บันทึกล่าสุด:
                                            {{ $lastSavedAt->format('H:i:s') }}</p>
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
                        <button type="submit" wire:loading.attr="disabled" {{ !$this->canUpdate ? 'disabled' : '' }}
                            class="w-full inline-flex justify-center items-center px-4 py-3 bg-gradient-to-r
                            from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700
                            disabled:from-slate-300 disabled:to-slate-400 text-white font-semibold rounded-lg
                            transition-all duration-200 shadow-sm hover:shadow-md disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="update">
                                @if ($status === 'draft')
                                    📝 อัพเดทร่าง
                                @elseif($status === 'published')
                                    🚀 อัพเดทและเผยแพร่
                                @else
                                    💾 อัพเดทเอกสาร
                                @endif
                            </span>
                            <span wire:loading wire:target="update" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                กำลังอัพเดท...
                            </span>
                        </button>

                        @if (!$this->canUpdate)
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

                            <button wire:click="autoSaveDocument" type="button"
                                {{ $isProcessing || $isDraftSaving ? 'disabled' : '' }}
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-100 hover:bg-green-200 text-green-700 font-medium rounded-lg transition-colors duration-200 disabled:opacity-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                <span wire:loading.remove wire:target="autoSaveDocument">บันทึกเร็ว</span>
                                <span wire:loading wire:target="autoSaveDocument">กำลังบันทึก...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Loading Overlay -->
    <div wire:loading.flex wire:target="update,documentFile"
        class="fixed inset-0 z-50 bg-white bg-opacity-75 flex items-center justify-center">
        <div class="flex flex-col items-center space-y-4">
            <svg class="animate-spin h-8 w-8 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                    stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 0 1 4 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <p class="text-sm text-slate-600 font-medium" wire:loading.remove wire:target="documentFile">
                กำลังอัพเดทเอกสาร...
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
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
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

    <!-- Scripts and Styles -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            console.log('DocumentEdit component initialized');

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
                    @this.call('autoSaveDocument');
                }, e.detail.interval);
            });

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + S to save
                if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                    e.preventDefault();
                    @this.call('update');
                }

                // Ctrl/Cmd + D to save draft
                if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
                    e.preventDefault();
                    @this.call('saveDraft');
                }
            });
        });

        // Listen for file management events
        document.addEventListener('fileRemoved', () => {
            // Show removal feedback
            const indicator = document.createElement('div');
            indicator.className = 'fixed bottom-4 right-4 bg-red-100 text-red-800 px-3 py-2 rounded-lg text-sm';
            indicator.textContent = 'ไฟล์จะถูกลบเมื่อบันทึก';
            document.body.appendChild(indicator);

            setTimeout(() => {
                indicator.style.transition = 'opacity 0.3s';
                indicator.style.opacity = '0';
                setTimeout(() => indicator.remove(), 300);
            }, 3000);
        });

        document.addEventListener('fileRestored', () => {
            // Show restoration feedback
            const indicator = document.createElement('div');
            indicator.className = 'fixed bottom-4 right-4 bg-green-100 text-green-800 px-3 py-2 rounded-lg text-sm';
            indicator.textContent = 'เรียกคืนไฟล์สำเร็จ';
            document.body.appendChild(indicator);

            setTimeout(() => {
                indicator.style.transition = 'opacity 0.3s';
                indicator.style.opacity = '0';
                setTimeout(() => indicator.remove(), 300);
            }, 2000);
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

    <!-- Debug Information (Remove in production) -->
    @if (app()->environment('local'))
        <div class="fixed bottom-4 left-4 bg-black bg-opacity-75 text-white p-3 rounded-lg text-xs max-w-sm">
            <div><strong>Debug Info:</strong></div>
            <div>fileMarkedForRemoval: {{ $fileMarkedForRemoval ? 'true' : 'false' }}</div>
            <div>existingFile: {{ $existingFile ? 'exists' : 'null' }}</div>
            <div>documentFile: {{ $documentFile ? 'exists' : 'null' }}</div>
            <div>canUpdate: {{ $this->canUpdate ? 'true' : 'false' }}</div>
        </div>
    @endif

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
        #document-file-upload:focus+label {
            outline: 2px solid #f97316;
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

        /* Edit-specific styling */
        .bg-gradient-to-r.from-amber-500.to-orange-600 {
            background: linear-gradient(to right, #f59e0b, #ea580c);
        }

        .hover\:from-amber-700.hover\:to-orange-700:hover {
            background: linear-gradient(to right, #b45309, #c2410c);
        }

        /* Color variants */
        .bg-slate-50 {
            background-color: #f8fafc;
        }

        .border-slate-200 {
            border-color: #e2e8f0;
        }

        .text-slate-600 {
            color: #475569;
        }

        .text-slate-900 {
            color: #0f172a;
        }

        .bg-blue-50 {
            background-color: #eff6ff;
        }

        .border-blue-200 {
            border-color: #bfdbfe;
        }

        .text-blue-800 {
            color: #1e40af;
        }

        .text-blue-600 {
            color: #2563eb;
        }

        .bg-red-50 {
            background-color: #fef2f2;
        }

        .border-red-200 {
            border-color: #fecaca;
        }

        .text-red-800 {
            color: #991b1b;
        }

        .text-red-500 {
            color: #ef4444;
        }

        /* Hover effects */
        .hover\:bg-blue-200:hover {
            background-color: #dbeafe;
        }

        .hover\:bg-red-200:hover {
            background-color: #fecaca;
        }

        .text-blue-700 {
            color: #1d4ed8;
        }

        .text-red-700 {
            color: #b91c1c;
        }

        /* Spacing utilities */
        .space-y-3>*+* {
            margin-top: 0.75rem;
        }

        /* Transition utilities */
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        .duration-200 {
            transition-duration: 200ms;
        }
    </style>
</div>
