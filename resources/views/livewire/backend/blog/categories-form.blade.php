<div class="py-6">
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
            <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                <button wire:click="cancel"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    ยกเลิก
                </button>
                <button wire:click="save"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="-ml-1 mr-2 size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ $isEdit ? 'อัปเดต' : 'บันทึก' }}
                </button>
            </div>
        </div>

        <form wire:submit="save">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Basic Information -->
                    <div class="bg-white shadow-sm rounded-xl border border-gray-200">
                        <div class="px-6 py-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">ข้อมูลพื้นฐาน</h3>

                            <div class="grid grid-cols-1 gap-6">
                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        ชื่อหมวดหมู่ <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model.live="name" type="text" id="name"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm text-sm placeholder:text-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('name') border-red-300 ring-2 ring-red-500/20 @enderror">
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Slug -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label for="slug" class="block text-sm font-medium text-gray-700">URL
                                            Slug</label>
                                        <button type="button" wire:click="toggleAutoSlug"
                                            class="text-sm font-medium transition-colors {{ $autoGenerateSlug ? 'text-green-600 hover:text-green-700' : 'text-gray-500 hover:text-blue-600' }}">
                                            {{ $autoGenerateSlug ? 'สร้างอัตโนมัติ' : 'กำหนดเอง' }}
                                        </button>
                                    </div>
                                    <input wire:model.live="slug" type="text" id="slug"
                                        {{ $autoGenerateSlug ? 'readonly' : '' }}
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm text-sm placeholder:text-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('slug') border-red-300 ring-2 ring-red-500/20 @enderror {{ $autoGenerateSlug ? 'bg-gray-50 cursor-not-allowed' : '' }}">
                                    @error('slug')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    @if ($slug)
                                        <p class="mt-2 text-sm text-gray-500">URL: {{ url('/blog/category/' . $slug) }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Description -->
                                <div>
                                    <label for="description"
                                        class="block text-sm font-medium text-gray-700 mb-2">คำอธิบาย</label>
                                    <textarea wire:model.live="description" id="description" rows="4"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm text-sm placeholder:text-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('description') border-red-300 ring-2 ring-red-500/20 @enderror"
                                        placeholder="คำอธิบายสั้นๆ เกี่ยวกับหมวดหมู่นี้..."></textarea>
                                    @error('description')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Appearance -->
                    <div class="bg-white shadow-sm rounded-xl border border-gray-200">
                        <div class="px-6 py-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">รูปลักษณ์</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Color -->
                                <div>
                                    <label for="color"
                                        class="block text-sm font-medium text-gray-700 mb-2">สีหมวดหมู่</label>
                                    <div class="flex items-center gap-3">
                                        <input wire:model.live="color" type="color" id="color"
                                            class="size-10 border border-gray-300 rounded-lg cursor-pointer">
                                        <input wire:model.live="color" type="text"
                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm text-sm placeholder:text-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('color') border-red-300 ring-2 ring-red-500/20 @enderror"
                                            placeholder="#3B82F6">
                                        <div class="size-8 rounded-lg border border-gray-300 shadow-sm"
                                            style="background-color: {{ $color }}"></div>
                                    </div>
                                    @error('color')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Icon -->
                                <div>
                                    <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">Icon
                                        (FontAwesome)</label>
                                    <div class="flex items-center gap-3">
                                        <input wire:model.live="icon" type="text" id="icon"
                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg shadow-sm text-sm placeholder:text-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('icon') border-red-300 ring-2 ring-red-500/20 @enderror"
                                            placeholder="fas fa-tag">
                                        @if ($icon)
                                            <div class="size-8 flex items-center justify-center border border-gray-300 rounded-lg shadow-sm"
                                                style="color: {{ $color }}">
                                                <i class="{{ $icon }}"></i>
                                            </div>
                                        @endif
                                    </div>
                                    @error('icon')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-2 text-sm text-gray-500">เช่น: fas fa-tag, far fa-folder, fal fa-star
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEO Settings -->
                    <div class="bg-white shadow-sm rounded-xl border border-gray-200">
                        <div class="px-6 py-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-semibold text-gray-900">การตั้งค่า SEO</h3>
                                <button type="button" wire:click="$toggle('showAdvanced')"
                                    class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                                    {{ $showAdvanced ? 'ซ่อน' : 'แสดง' }} การตั้งค่าขั้นสูง
                                </button>
                            </div>

                            <div class="space-y-6">
                                <!-- Meta Title -->
                                <div>
                                    <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta
                                        Title</label>
                                    <input wire:model.live="meta_title" type="text" id="meta_title"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm text-sm placeholder:text-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('meta_title') border-red-300 ring-2 ring-red-500/20 @enderror"
                                        placeholder="{{ $name ?: 'จะใช้ชื่อหมวดหมู่หากไม่ระบุ' }}">
                                    @error('meta_title')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-2 text-sm text-gray-500">{{ strlen($meta_title) }}/60 ตัวอักษร</p>
                                </div>

                                @if ($showAdvanced)
                                    <!-- Meta Description -->
                                    <div>
                                        <label for="meta_description"
                                            class="block text-sm font-medium text-gray-700 mb-2">Meta
                                            Description</label>
                                        <textarea wire:model.live="meta_description" id="meta_description" rows="3"
                                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm text-sm placeholder:text-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('meta_description') border-red-300 ring-2 ring-red-500/20 @enderror"
                                            placeholder="คำอธิบายสำหรับ Search Engine..."></textarea>
                                        @error('meta_description')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-2 text-sm text-gray-500">{{ strlen($meta_description) }}/160
                                            ตัวอักษร</p>
                                    </div>

                                    <!-- Meta Keywords -->
                                    <div>
                                        <label for="meta_keywords"
                                            class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                                        <input wire:model.live="meta_keywords" type="text" id="meta_keywords"
                                            class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm text-sm placeholder:text-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('meta_keywords') border-red-300 ring-2 ring-red-500/20 @enderror"
                                            placeholder="คำสำคัญ, คั่นด้วยจุลภาค">
                                        @error('meta_keywords')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-2 text-sm text-gray-500">แยกคำสำคัญด้วยจุลภาค เช่น: เทคโนโลยี,
                                            นวัตกรรม, ข่าวสาร</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Media Files -->
                    @if ($showAdvanced)
                        <div class="bg-white shadow-sm rounded-xl border border-gray-200">
                            <div class="px-6 py-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-6">รูปภาพ</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Featured Image -->
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-3">รูปหน้าหมวดหมู่</label>

                                        @if ($isEdit && $category->getFeaturedImageUrl())
                                            <div class="mb-4">
                                                <img src="{{ $category->getFeaturedImageUrl('thumb') }}"
                                                    alt="Featured Image"
                                                    class="size-24 object-cover rounded-lg border border-gray-200 shadow-sm">
                                                <button type="button" wire:click="removeImage('featured_image')"
                                                    class="mt-2 text-sm font-medium text-red-600 hover:text-red-700 transition-colors">
                                                    ลบรูปภาพ
                                                </button>
                                            </div>
                                        @endif

                                        <input wire:model="featured_image" type="file" accept="image/*"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 file:transition-colors">
                                        @error('featured_image')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-2 text-sm text-gray-500">PNG, JPG, WebP (สูงสุด 5MB)</p>
                                    </div>

                                    <!-- Banner Image -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-3">รูป Banner</label>

                                        @if ($isEdit && $category->getBannerImageUrl())
                                            <div class="mb-4">
                                                <img src="{{ $category->getBannerImageUrl('banner') }}"
                                                    alt="Banner Image"
                                                    class="w-full h-16 object-cover rounded-lg border border-gray-200 shadow-sm">
                                                <button type="button" wire:click="removeImage('banner_image')"
                                                    class="mt-2 text-sm font-medium text-red-600 hover:text-red-700 transition-colors">
                                                    ลบรูปภาพ
                                                </button>
                                            </div>
                                        @endif

                                        <input wire:model="banner_image" type="file" accept="image/*"
                                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 file:transition-colors">
                                        @error('banner_image')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-2 text-sm text-gray-500">PNG, JPG, WebP (สูงสุด 10MB)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">

                    <!-- Status & Visibility -->
                    <div class="bg-white shadow-sm rounded-xl border border-gray-200">
                        <div class="px-6 py-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">สถานะ</h3>

                            <div class="space-y-4">
                                <!-- Active Status -->
                                <div class="flex items-center justify-between">
                                    <div>
                                        <label for="is_active"
                                            class="text-sm font-medium text-gray-700">เปิดใช้งาน</label>
                                        <p class="text-sm text-gray-500">แสดงหมวดหมู่ในหน้าเว็บ</p>
                                    </div>
                                    <div class="flex items-center">
                                        <input wire:model.live="is_active" type="checkbox" id="is_active"
                                            class="size-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hierarchy -->
                    <div class="bg-white shadow-sm rounded-xl border border-gray-200">
                        <div class="px-6 py-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">โครงสร้าง</h3>

                            <div class="space-y-4">
                                <!-- Parent Category -->
                                <div>
                                    <label for="parent_id"
                                        class="block text-sm font-medium text-gray-700 mb-2">หมวดหมู่แม่</label>
                                    <select wire:model.live="parent_id" id="parent_id"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('parent_id') border-red-300 ring-2 ring-red-500/20 @enderror">
                                        <option value="">ไม่มีหมวดหมู่แม่ (หมวดหลัก)</option>
                                        @foreach ($availableParents as $parent)
                                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('parent_id')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Sort Order -->
                                <div>
                                    <label for="sort_order"
                                        class="block text-sm font-medium text-gray-700 mb-2">ลำดับการแสดง</label>
                                    <input wire:model.live="sort_order" type="number" id="sort_order"
                                        min="0"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('sort_order') border-red-300 ring-2 ring-red-500/20 @enderror">
                                    @error('sort_order')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-2 text-sm text-gray-500">เลขที่น้อยกว่าจะแสดงก่อน</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preview -->
                    @if ($name)
                        <div class="bg-white shadow-sm rounded-xl border border-gray-200">
                            <div class="px-6 py-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-6">ตัวอย่าง</h3>

                                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50/50">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 size-10 rounded-lg flex items-center justify-center shadow-sm border border-gray-200"
                                            style="background-color: {{ $color }}20">
                                            @if ($icon)
                                                <i class="{{ $icon }}"
                                                    style="color: {{ $color }}"></i>
                                            @else
                                                <svg class="size-5" style="color: {{ $color }}"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900">{{ $name }}</p>
                                            @if ($slug)
                                                <p class="text-sm text-gray-500">{{ $slug }}</p>
                                            @endif
                                            @if ($description)
                                                <p class="text-xs text-gray-400 mt-1">
                                                    {{ Str::limit($description, 50) }}</p>
                                            @endif
                                        </div>
                                        <div class="flex-shrink-0">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bottom Action Bar -->
            <div
                class="bg-white border-t border-gray-200 px-6 py-4 mt-8 -mx-4 sm:-mx-6 lg:-mx-8 sticky bottom-0 shadow-lg">
                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="cancel"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        ยกเลิก
                    </button>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="-ml-1 mr-2 size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        {{ $isEdit ? 'อัปเดตหมวดหมู่' : 'สร้างหมวดหมู่' }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Loading Overlay -->
    <div wire:loading wire:target="save" class="fixed inset-0 z-50 overflow-hidden">
        <div class="absolute inset-0 bg-gray-500/75 backdrop-blur-sm"></div>
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="bg-white rounded-xl p-6 shadow-xl border border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="animate-spin rounded-full size-6 border-2 border-blue-600 border-t-transparent"></div>
                    <span class="text-gray-700 font-medium">{{ $isEdit ? 'กำลังอัปเดต...' : 'กำลังบันทึก...' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            // Auto-focus on name field when creating new category
            @if (!$isEdit)
                document.getElementById('name')?.focus();
            @endif
        });
    </script>
@endpush
