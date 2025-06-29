<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <div
                                class="h-8 w-8 rounded-lg bg-gradient-to-r from-red-500 to-red-600 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </div>
                            <h1 class="text-3xl font-bold text-slate-800">ถังขยะเอกสาร</h1>
                        </div>
                        <p class="text-slate-600">จัดการเอกสารที่ถูกลบและสามารถกู้คืนได้</p>
                    </div>

                    <div class="flex items-center gap-3">
                        @if ($this->hasSelectedDocuments)
                            <button wire:click="restoreSelected"
                                class="inline-flex items-center px-4 py-2 bg-green-100 hover:bg-green-200 text-green-700 font-medium rounded-lg transition-colors duration-200">
                                กู้คืน ({{ $this->selectedCount }})
                            </button>

                            <button wire:click="deleteSelectedPermanently"
                                wire:confirm="คุณต้องการลบเอกสาร {{ $this->selectedCount }} รายการ ถาวรหรือไม่?"
                                class="inline-flex items-center px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 font-medium rounded-lg transition-colors duration-200">
                                ลบถาวร ({{ $this->selectedCount }})
                            </button>
                        @endif

                        @if ($totalTrashedDocuments > 0)
                            <button
                                onclick="if(confirm('คุณต้องการลบเอกสารในถังขยะทั้งหมด {{ $totalTrashedDocuments }} รายการ ถาวรหรือไม่? การดำเนินการนี้ไม่สามารถยกเลิกได้!')) { @this.call('directEmptyTrash') }"
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                ล้างถังขยะ
                            </button>
                        @endif

                        <a href="{{ route('administrator.documents.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors duration-200">
                            กลับไปรายการเอกสาร
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">เอกสารในถังขยะ</p>
                        <p class="text-2xl font-bold text-slate-900">{{ number_format($totalTrashedDocuments) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4m3 0V5a2 2 0 00-2-2H6a2 2 0 00-2 2v2m16 0v10a2 2 0 01-2 2H4a2 2 0 01-2-2V7h20z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">ลบในสัปดาห์นี้</p>
                        <p class="text-2xl font-bold text-slate-900">{{ number_format($trashedThisWeek) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">ลบในเดือนนี้</p>
                        <p class="text-2xl font-bold text-slate-900">{{ number_format($trashedThisMonth) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-slate-600">ขนาดรวม</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $this->formattedTotalSize }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Table -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            @if ($documents->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left">
                                    <input type="checkbox" wire:model.live="selectAll"
                                        class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    เอกสาร
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    หมวดหมู่/ประเภท
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    วันที่ลบ
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    การดำเนินการ
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @foreach ($documents as $document)
                                <tr class="hover:bg-slate-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" wire:model.live="selectedDocuments"
                                            value="{{ $document->id }}"
                                            class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-red-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-medium text-slate-900 truncate">
                                                    {{ $document->title }}
                                                </h4>
                                                <p class="text-sm text-slate-500">{{ $document->document_number }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm">
                                            <div class="text-slate-900">{{ $document->category?->name ?? '-' }}</div>
                                            <div class="text-slate-500">{{ $document->type?->name ?? '-' }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm">
                                            <div class="text-slate-900">{{ $document->deleted_at->format('d M Y') }}
                                            </div>
                                            <div class="text-slate-500">{{ $document->deleted_at->format('H:i น.') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <button wire:click="testMethod"
                                                class="px-4 py-2 bg-blue-500 text-white rounded">
                                                ทดสอบ
                                            </button>
                                            <button
                                                onclick="if(confirm('กู้คืนเอกสาร {{ $document->title }} หรือไม่?')) { @this.call('directRestore', {{ $document->id }}) }"
                                                class="inline-flex items-center px-3 py-1 bg-green-100 hover:bg-green-200 text-green-700 text-xs font-medium rounded transition-colors">
                                                กู้คืน
                                            </button>

                                            <button
                                                onclick="if(confirm('ลบเอกสาร {{ $document->title }} ถาวรหรือไม่?')) { @this.call('directDelete', {{ $document->id }}) }"
                                                class="inline-flex items-center px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-medium rounded transition-colors">
                                                ลบถาวร
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $documents->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <h3 class="text-lg font-medium text-slate-900 mb-2">ไม่มีเอกสารในถังขยะ</h3>
                    <p class="text-slate-500 mb-4">ถังขยะของคุณว่างเปล่า</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Restore Confirmation Modal -->
    @if ($confirmingRestore && $documentToRestore)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 shadow-xl">
                <h3 class="text-lg font-medium text-gray-900 mb-4">กู้คืนเอกสาร</h3>
                <p class="text-sm text-gray-600 mb-6">
                    คุณต้องการกู้คืนเอกสาร "<strong>{{ $documentToRestore->title }}</strong>" หรือไม่?
                </p>
                <div class="flex justify-end space-x-3">
                    <button wire:click="cancelRestore"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg">
                        ยกเลิก
                    </button>
                    <button wire:click="restoreDocument"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg">
                        กู้คืน
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Permanent Delete Confirmation Modal -->
    @if ($confirmingPermanentDelete && $documentToDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 shadow-xl">
                <h3 class="text-lg font-medium text-gray-900 mb-4">ลบเอกสารถาวร</h3>
                <p class="text-sm text-gray-600 mb-6">
                    คุณต้องการลบเอกสาร "<strong>{{ $documentToDelete->title }}</strong>" ถาวรหรือไม่?
                </p>
                <div class="flex justify-end space-x-3">
                    <button wire:click="cancelPermanentDelete"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg">
                        ยกเลิก
                    </button>
                    <button wire:click="permanentDeleteDocument"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg">
                        ลบถาวร
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Empty Trash Confirmation Modal -->
    @if ($confirmingEmptyTrash)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 shadow-xl">
                <h3 class="text-lg font-medium text-gray-900 mb-4">ล้างถังขยะ</h3>
                <p class="text-sm text-gray-600 mb-6">
                    คุณต้องการลบเอกสารในถังขยะทั้งหมด {{ number_format($totalTrashedDocuments) }} รายการ ถาวรหรือไม่?
                </p>
                <div class="flex justify-end space-x-3">
                    <button wire:click="closeModals"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg">
                        ยกเลิก
                    </button>
                    <button wire:click="emptyTrash"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg">
                        ล้างถังขยะ
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
