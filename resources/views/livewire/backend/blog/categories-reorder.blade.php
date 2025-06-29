{{-- แทนที่ส่วนของ categories-reorder.blade.php --}}

<div class="py-6" x-data="categoryReorder()" x-init="initSortable()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-4">
                        <li>
                            <div class="flex">
                                <a href="{{ route('administrator.blog.dashboard') }}"
                                    class="text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
                                    <svg class="flex-shrink-0 size-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                    </svg>
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 size-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <a href="{{ route('administrator.blog.categories.index') }}"
                                    class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">หมวดหมู่</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 size-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="ml-4 text-lg font-semibold text-gray-900">{{ $this->formTitle }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Action Buttons -->
            <div class="mt-4 flex md:mt-0 md:ml-4 gap-3">
                <button wire:click="cancel"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="-ml-1 mr-2 size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    กลับ
                </button>

                {{-- ปุ่ม Refresh --}}
                <button onclick="refreshCategoryTree()" title="รีเฟรชข้อมูล (Ctrl+R)"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="-ml-1 mr-2 size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    รีเฟรช
                </button>

                @if ($hasChanges)
                    {{-- ปุ่มรีเซ็ต --}}
                    <button onclick="resetChanges()"
                        class="inline-flex items-center px-4 py-2 border border-yellow-300 shadow-sm text-sm font-medium rounded-lg text-yellow-700 bg-yellow-50 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                        <svg class="-ml-1 mr-2 size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        รีเซ็ต
                    </button>

                    {{-- ปุ่มบันทึก --}}
                    <button onclick="saveChanges()" id="save-changes-btn" title="บันทึกการเปลี่ยนแปลง (Ctrl+S)"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 transition-colors">
                        <svg class="-ml-1 mr-2 size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span id="save-btn-text">บันทึกการเปลี่ยนแปลง</span>
                    </button>
                @endif
            </div>
        </div>

        {{-- Help Banner --}}
        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <svg class="size-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">วิธีใช้งาน</h3>
                    <div class="mt-1 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>ลากไอคอน <svg class="inline size-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 8h16M4 16h16" />
                                </svg> เพื่อเปลี่ยนลำดับหรือย้ายไปหมวดย่อย</li>
                            <li>การเปลี่ยนแปลงจะถูกบันทึกทันที และสามารถใช้ Ctrl+S เพื่อยืนยันการบันทึก</li>
                            <li>ใช้ปุ่มขยาย/ย่อทั้งหมด หรือคลิกลูกศรข้างหมวดหมู่เพื่อจัดการการแสดงผล</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-3">
                <!-- Categories Tree -->
                <div class="bg-white shadow-sm rounded-xl border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">โครงสร้างหมวดหมู่</h3>
                            <div class="flex items-center gap-2">
                                <button @click="expandAll()"
                                    class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                                    ขยายทั้งหมด
                                </button>
                                <span class="text-gray-300">|</span>
                                <button @click="collapseAll()"
                                    class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                                    ย่อทั้งหมด
                                </button>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">ลากและวางเพื่อจัดเรียงลำดับและโครงสร้างหมวดหมู่</p>
                    </div>

                    <div class="p-6">
                        @if (!empty($categoriesTree))
                            <div id="categories-tree" class="space-y-2">
                                @foreach ($categoriesTree as $category)
                                    @include('livewire.backend.blog.partials.category-tree-item', [
                                        'category' => $category,
                                        'level' => 0,
                                    ])
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto size-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">ไม่มีหมวดหมู่</h3>
                                <p class="mt-1 text-sm text-gray-500">เริ่มต้นสร้างหมวดหมู่แรกของคุณ</p>
                                <div class="mt-6">
                                    <a href="{{ route('administrator.blog.categories.create') }}"
                                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        เพิ่มหมวดหมู่
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Statistics -->
                <div class="bg-white shadow-sm rounded-xl border border-gray-200">
                    <div class="px-6 py-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">สถิติ</h3>

                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">หมวดหมู่ทั้งหมด</dt>
                                <dd class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">หมวดหลัก</dt>
                                <dd class="text-lg font-semibold text-blue-600">{{ $stats['root_categories'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">หมวดย่อย</dt>
                                <dd class="text-lg font-semibold text-green-600">{{ $stats['child_categories'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">ความลึกสูงสุด</dt>
                                <dd class="text-lg font-semibold text-purple-600">{{ $stats['max_depth'] }} ระดับ</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Auto Sort Options -->
                <div class="bg-white shadow-sm rounded-xl border border-gray-200">
                    <div class="px-6 py-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">จัดเรียงอัตโนมัติ</h3>

                        <div class="space-y-3">
                            <button wire:click="autoSort('alphabetical')"
                                class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                                <div class="flex items-center">
                                    <svg class="size-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                                    </svg>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">เรียงตามตัวอักษร</div>
                                        <div class="text-xs text-gray-500">A-Z ภายในแต่ละระดับ</div>
                                    </div>
                                </div>
                            </button>

                            <button wire:click="autoSort('posts_count')"
                                class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                                <div class="flex items-center">
                                    <svg class="size-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">เรียงตามจำนวนโพสต์</div>
                                        <div class="text-xs text-gray-500">มาก → น้อย</div>
                                    </div>
                                </div>
                            </button>

                            <button wire:click="autoSort('created_date')"
                                class="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                                <div class="flex items-center">
                                    <svg class="size-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">เรียงตามวันที่สร้าง</div>
                                        <div class="text-xs text-gray-500">ใหม่ → เก่า</div>
                                    </div>
                                </div>
                            </button>
                        </div>

                        <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                            <div class="flex">
                                <svg class="size-5 text-amber-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div class="ml-3">
                                    <p class="text-sm text-amber-800">การจัดเรียงอัตโนมัติจะเปลี่ยนแปลงลำดับปัจจุบัน
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Keyboard Shortcuts -->
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">แป้นพิมพ์ลัด</h3>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-600">บันทึก</dt>
                            <dd class="font-mono text-gray-900">Ctrl + S</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">รีเฟรช</dt>
                            <dd class="font-mono text-gray-900">Ctrl + R</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div wire:loading wire:target="saveOrder,autoSort" class="fixed inset-0 z-50 overflow-hidden">
            <div class="absolute inset-0 bg-gray-500/75 backdrop-blur-sm"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="bg-white rounded-xl p-6 shadow-xl border border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="animate-spin rounded-full size-6 border-2 border-blue-600 border-t-transparent">
                        </div>
                        <span class="text-gray-700 font-medium">กำลังประมวลผล...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        let sortableInstances = [];
        let dragChanges = []; // เก็บการเปลี่ยนแปลงจากการลาก
        let isProcessing = false; // ป้องกันการประมวลผลซ้ำ

        function categoryReorder() {
            return {
                initSortable() {
                    initializeSortable();
                },

                expandAll() {
                    expandAllCategories();
                },

                collapseAll() {
                    collapseAllCategories();
                }
            }
        }

        function destroySortableInstances() {
            sortableInstances.forEach(instance => {
                if (instance && typeof instance.destroy === 'function') {
                    instance.destroy();
                }
            });
            sortableInstances = [];
        }

        function initializeSortable() {
            if (isProcessing) return;

            destroySortableInstances();

            // Main tree sortable
            const treeElement = document.getElementById('categories-tree');
            if (treeElement) {
                treeElement.setAttribute('data-parent-id', '');

                const sortable = Sortable.create(treeElement, {
                    group: 'categories',
                    animation: 200,
                    handle: '.drag-handle',
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    onStart: (evt) => {
                        isProcessing = true;
                        evt.item.classList.add('dragging');
                        showDragFeedback('กำลังลาก...');
                    },
                    onEnd: (evt) => {
                        evt.item.classList.remove('dragging');
                        hideDragFeedback();
                        handleDragEnd(evt);
                    }
                });
                sortableInstances.push(sortable);
            }

            // Child categories sortable
            document.querySelectorAll('.children').forEach(childContainer => {
                const sortable = Sortable.create(childContainer, {
                    group: 'categories',
                    animation: 200,
                    handle: '.drag-handle',
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    onStart: (evt) => {
                        isProcessing = true;
                        evt.item.classList.add('dragging');
                        showDragFeedback('กำลังลาก...');
                    },
                    onEnd: (evt) => {
                        evt.item.classList.remove('dragging');
                        hideDragFeedback();
                        handleDragEnd(evt);
                    }
                });
                sortableInstances.push(sortable);
            });
        }

        function handleDragEnd(evt) {
            const categoryId = parseInt(evt.item.dataset.categoryId);
            const newParentId = evt.to.dataset.parentId ? parseInt(evt.to.dataset.parentId) : null;
            const newPosition = evt.newIndex;

            console.log('Drag ended:', {
                categoryId,
                newParentId,
                newPosition
            });

            // บันทึกการเปลี่ยนแปลง
            const changeIndex = dragChanges.findIndex(change => change.categoryId === categoryId);
            const change = {
                categoryId: categoryId,
                newParentId: newParentId,
                newPosition: newPosition,
                timestamp: Date.now()
            };

            if (changeIndex >= 0) {
                dragChanges[changeIndex] = change;
            } else {
                dragChanges.push(change);
            }

            // บันทึกการเปลี่ยนแปลงลงฐานข้อมูลทันที
            saveDragChange(change);

            console.log('Total drag changes:', dragChanges.length);
        }

        function saveDragChange(change) {
            showDragFeedback('กำลังบันทึก...');

            if (window.Livewire) {
                const component = window.Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));
                if (component) {
                    component.call('saveDragChanges', [change])
                        .then(() => {
                            console.log('Drag change saved successfully');
                            updateHasChangesFlag();
                            hideDragFeedback();
                            showToast('บันทึกการเปลี่ยนแปลงสำเร็จ', 'success');
                        })
                        .catch((error) => {
                            console.error('Error saving drag change:', error);
                            // ลบการเปลี่ยนแปลงที่ล้มเหลวออกจาก array
                            const index = dragChanges.findIndex(c => c.categoryId === change.categoryId);
                            if (index >= 0) {
                                dragChanges.splice(index, 1);
                            }
                            hideDragFeedback();
                            showToast('เกิดข้อผิดพลาดในการบันทึก', 'error');
                        })
                        .finally(() => {
                            setTimeout(() => {
                                isProcessing = false;
                            }, 100);
                        });
                }
            }
        }

        function updateHasChangesFlag() {
            if (window.Livewire) {
                const component = window.Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));
                if (component) {
                    component.set('hasChanges', dragChanges.length > 0);
                }
            }
        }

        // ฟังก์ชัน UI Feedback
        function showDragFeedback(message) {
            let feedback = document.getElementById('drag-feedback');
            if (!feedback) {
                feedback = document.createElement('div');
                feedback.id = 'drag-feedback';
                feedback.className = 'fixed top-4 right-4 z-50 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg';
                document.body.appendChild(feedback);
            }
            feedback.textContent = message;
            feedback.style.display = 'block';
        }

        function hideDragFeedback() {
            const feedback = document.getElementById('drag-feedback');
            if (feedback) {
                feedback.style.display = 'none';
            }
        }

        function showToast(message, type = 'info') {
            const colors = {
                success: 'bg-green-600',
                error: 'bg-red-600',
                info: 'bg-blue-600',
                warning: 'bg-yellow-600'
            };

            const toast = document.createElement('div');
            toast.className =
                `fixed top-4 right-4 z-50 ${colors[type]} text-white px-4 py-2 rounded-lg shadow-lg transform transition-all duration-300`;
            toast.textContent = message;
            document.body.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.classList.add('translate-x-0');
            }, 10);

            // Auto remove
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        }

        function expandAllCategories() {
            document.querySelectorAll('.children').forEach(children => {
                children.style.display = 'block';
            });
            document.querySelectorAll('.toggle-children svg').forEach(icon => {
                icon.style.transform = 'rotate(180deg)';
            });
            setTimeout(() => {
                initializeSortable();
            }, 100);
        }

        function collapseAllCategories() {
            document.querySelectorAll('.children').forEach(children => {
                children.style.display = 'none';
            });
            document.querySelectorAll('.toggle-children svg').forEach(icon => {
                icon.style.transform = 'rotate(0deg)';
            });
            setTimeout(() => {
                initializeSortable();
            }, 100);
        }

        // ฟังก์ชันรีเฟรช
        window.refreshCategoryTree = function() {
            showToast('กำลังรีเฟรชข้อมูล...', 'info');
            const component = window.Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));
            if (component) {
                dragChanges = [];
                component.call('refreshTree');
            }
        }

        // Override ปุ่มบันทึก
        window.saveChanges = function() {
            const btn = document.getElementById('save-changes-btn');
            const btnText = document.getElementById('save-btn-text');

            if (btn && btnText) {
                btn.disabled = true;
                btnText.textContent = 'กำลังบันทึก...';
            }

            if (window.Livewire) {
                const component = window.Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));
                if (component) {
                    component.call('saveOrder')
                        .then(() => {
                            dragChanges = [];
                            showToast('บันทึกเสร็จสิ้น', 'success');
                        })
                        .finally(() => {
                            if (btn && btnText) {
                                btn.disabled = false;
                                btnText.textContent = 'บันทึกการเปลี่ยนแปลง';
                            }
                        });
                }
            }
        }

        // Override ปุ่มรีเซ็ต
        window.resetChanges = function() {
            if (confirm('คุณต้องการรีเซ็ตการเปลี่ยนแปลงทั้งหมดหรือไม่?')) {
                dragChanges = [];
                if (window.Livewire) {
                    const component = window.Livewire.find(document.querySelector('[wire\\:id]').getAttribute(
                        'wire:id'));
                    if (component) {
                        component.call('resetOrder');
                        showToast('รีเซ็ตการเปลี่ยนแปลงเรียบร้อย', 'info');
                    }
                }
            }
        }

        // Livewire hooks
        document.addEventListener('livewire:init', () => {

            Livewire.on('orderSaved', () => {
                console.log('Order saved');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            });

            Livewire.on('autoSorted', (event) => {
                console.log('Auto sorted by:', event.type);
                dragChanges = [];
                setTimeout(() => {
                    initializeSortable();
                    showToast('จัดเรียงอัตโนมัติเสร็จสิ้น', 'success');
                }, 300);
            });

            Livewire.on('orderReset', () => {
                console.log('Order reset');
                dragChanges = [];
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            });
        });

        // Toggle children visibility
        document.addEventListener('click', function(e) {
            if (e.target.closest('.toggle-children')) {
                e.preventDefault();
                e.stopPropagation();

                const toggle = e.target.closest('.toggle-children');
                const categoryItem = toggle.closest('.category-item');
                const children = categoryItem.querySelector('.children');
                const icon = toggle.querySelector('svg');

                if (children && icon) {
                    if (children.style.display === 'none' || children.style.display === '') {
                        children.style.display = 'block';
                        icon.style.transform = 'rotate(180deg)';
                    } else {
                        children.style.display = 'none';
                        icon.style.transform = 'rotate(0deg)';
                    }

                    setTimeout(() => {
                        initializeSortable();
                    }, 100);
                }
            }

            // Override click events สำหรับปุ่ม Livewire
            if (e.target.closest('[wire\\:click="saveOrder"]')) {
                e.preventDefault();
                e.stopPropagation();
                saveChanges();
            }

            if (e.target.closest('[wire\\:click="resetOrder"]')) {
                e.preventDefault();
                e.stopPropagation();
                resetChanges();
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                initializeSortable();
                showToast('ระบบจัดเรียงหมวดหมู่พร้อมใช้งาน', 'info');
            }, 500);
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey || e.metaKey) {
                switch (e.key) {
                    case 's':
                        e.preventDefault();
                        if (dragChanges.length > 0) {
                            saveChanges();
                        }
                        break;
                    case 'r':
                        e.preventDefault();
                        refreshCategoryTree();
                        break;
                }
            }
        });
    </script>

    <!-- Enhanced Styles -->
    <style>
        .sortable-ghost {
            opacity: 0.4;
            background-color: #f3f4f6;
            transform: rotate(5deg);
        }

        .sortable-chosen {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .sortable-drag {
            transform: rotate(5deg);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .category-item {
            transition: all 0.2s ease;
        }

        .category-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .category-item.dragging {
            opacity: 0.8;
            transform: rotate(3deg);
        }

        .drag-handle {
            cursor: grab;
            opacity: 0.6;
            transition: opacity 0.2s ease;
        }

        .drag-handle:hover {
            opacity: 1;
        }

        .drag-handle:active {
            cursor: grabbing;
        }

        .toggle-children {
            transition: transform 0.2s ease;
        }

        .children {
            transition: all 0.3s ease;
        }

        /* Toast animations */
        .toast-enter {
            transform: translateX(100%);
            opacity: 0;
        }

        .toast-enter-active {
            transform: translateX(0);
            opacity: 1;
            transition: all 0.3s ease;
        }

        .toast-exit {
            transform: translateX(0);
            opacity: 1;
        }

        .toast-exit-active {
            transform: translateX(100%);
            opacity: 0;
            transition: all 0.3s ease;
        }
    </style>
</div>
