<div class="max-w-7xl mx-auto p-6">
    {{-- Header Section --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <nav class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400 mb-2">
                    <a href="{{ route('administrator.blog.dashboard') }}"
                        class="hover:text-gray-700 dark:hover:text-gray-300">
                        บล็อก
                    </a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <a href="{{ route('administrator.blog.tags.index') }}"
                        class="hover:text-gray-700 dark:hover:text-gray-300">
                        จัดการแท็ก
                    </a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">
                        {{ $isEditing ? 'แก้ไขแท็ก' : 'เพิ่มแท็กใหม่' }}
                    </span>
                </nav>

                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $isEditing ? 'แก้ไขแท็ก' : 'เพิ่มแท็กใหม่' }}
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    {{ $isEditing ? 'แก้ไขข้อมูลแท็กบล็อก' : 'สร้างแท็กใหม่สำหรับจัดหมวดหมู่บทความ' }}
                </p>
            </div>

            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                @if ($isEditing)
                    <button wire:click="duplicate"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                            </path>
                        </svg>
                        ทำสำเนา
                    </button>
                @endif

                <button wire:click="cancel"
                    class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                    ยกเลิก
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Main Form --}}
        <div class="lg:col-span-2">
            <form wire:submit="save" class="space-y-6">
                {{-- Basic Information Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            ข้อมูลพื้นฐาน
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            กรอกข้อมูลพื้นฐานของแท็ก
                        </p>
                    </div>

                    <div class="p-6 space-y-6">
                        {{-- Tag Name --}}
                        <div>
                            <label for="name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                ชื่อแท็ก <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" wire:model.live="name"
                                placeholder="ใส่ชื่อแท็ก เช่น Technology, Travel, Food"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Slug --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="slug"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Slug <span class="text-red-500">*</span>
                                </label>
                                <button type="button" wire:click="toggleAutoSlug"
                                    class="inline-flex items-center text-xs {{ $auto_generate_slug ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' }} hover:text-green-700 dark:hover:text-green-300 transition-colors">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if ($auto_generate_slug)
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.102m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                            </path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 1.657-2.657 1.657-2.657A8 8 0 0118.657 17.657z">
                                            </path>
                                        @endif
                                    </svg>
                                    {{ $auto_generate_slug ? 'สร้างอัตโนมัติ' : 'สร้างเอง' }}
                                </button>
                            </div>

                            <div class="relative">
                                <input type="text" id="slug" wire:model.live="slug"
                                    placeholder="url-friendly-slug" {{ $auto_generate_slug ? 'readonly' : '' }}
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 {{ $auto_generate_slug ? 'bg-gray-50 dark:bg-gray-600' : '' }}">

                                @if (!$auto_generate_slug)
                                    <button type="button" wire:click="generateSlug"
                                        class="absolute right-2 top-2 px-3 py-1 text-xs bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 rounded hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors">
                                        สร้างใหม่
                                    </button>
                                @endif
                            </div>

                            @if ($slug)
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    URL: <span
                                        class="font-mono">{{ config('app.url') }}/tags/{{ $slug }}</span>
                                </p>
                            @endif

                            @error('slug')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                คำอธิบาย
                            </label>
                            <textarea id="description" wire:model.live="description" rows="4"
                                placeholder="อธิบายเกี่ยวกับแท็กนี้... (ไม่บังคับ)"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 resize-none"></textarea>

                            <div class="flex items-center justify-between mt-2">
                                @error('description')
                                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @else
                                    <div></div>
                                @enderror
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ strlen($description) }}/1000 ตัวอักษร
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Appearance Settings Card --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            รูปแบบการแสดงผล
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            กำหนดสีและสถานะของแท็ก
                        </p>
                    </div>

                    <div class="p-6 space-y-6">
                        {{-- Color Selection --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                สีแท็ก <span class="text-red-500">*</span>
                            </label>

                            {{-- Color Presets --}}
                            <div class="mb-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">สีที่แนะนำ</p>
                                <div class="grid grid-cols-6 gap-2">
                                    @foreach ($color_presets as $preset)
                                        <button type="button" wire:click="setPresetColor('{{ $preset }}')"
                                            class="w-10 h-10 rounded-lg border-2 transition-all hover:scale-110 {{ $color === $preset ? 'border-gray-400 dark:border-gray-300 shadow-md' : 'border-gray-200 dark:border-gray-600' }}"
                                            style="background-color: {{ $preset }}"
                                            title="{{ $preset }}">
                                            @if ($color === $preset)
                                                <svg class="w-4 h-4 mx-auto text-white drop-shadow"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Custom Color --}}
                            <div class="flex items-center space-x-3">
                                <input type="color" wire:model.live="color"
                                    class="w-12 h-12 rounded-lg border border-gray-300 dark:border-gray-600 cursor-pointer">
                                <div class="flex-1">
                                    <input type="text" wire:model.live="color" placeholder="#10B981"
                                        pattern="^#[0-9A-Fa-f]{6}$"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 font-mono text-sm">
                                </div>
                            </div>

                            @error('color')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status Toggle --}}
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model.live="is_active"
                                    class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500 focus:ring-offset-0">
                                <span class="ml-3 text-sm">
                                    <span class="font-medium text-gray-700 dark:text-gray-300">เปิดใช้งานแท็ก</span>
                                    <span
                                        class="block text-gray-500 dark:text-gray-400">แท็กที่เปิดใช้งานจะสามารถนำไปใช้กับบทความได้</span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div
                    class="flex items-center justify-between bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 px-6 py-4">
                    <div class="flex items-center space-x-3">
                        @if (!$isEditing)
                            <button type="button" wire:click="resetForm"
                                class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                รีเซ็ตฟอร์ม
                            </button>
                        @endif
                    </div>

                    <div class="flex items-center space-x-3">
                        <button type="button" wire:click="cancel"
                            class="inline-flex items-center px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                            ยกเลิก
                        </button>

                        <button type="submit"
                            class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ $isEditing ? 'อัปเดตแท็ก' : 'สร้างแท็ก' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Live Preview --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        ตัวอย่างแท็ก
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        ดูตัวอย่างแท็กแบบ real-time
                    </p>
                </div>

                <div class="p-6">
                    @php $preview = $this->previewTag() @endphp

                    <div class="space-y-4">
                        {{-- Tag Badge --}}
                        <div class="flex items-center space-x-2">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-white"
                                style="background-color: {{ $preview['color'] }}">
                                {{ $preview['name'] }}
                            </span>
                            @if (!$preview['is_active'])
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    ไม่ใช้งาน
                                </span>
                            @endif
                        </div>

                        {{-- Description Preview --}}
                        @if ($preview['description'])
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $preview['description'] }}
                            </div>
                        @endif

                        {{-- Color Info --}}
                        <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1">
                            <div>สี: <span class="font-mono">{{ $preview['color'] }}</span></div>
                            @if ($slug)
                                <div>URL: <span class="font-mono">/tags/{{ $slug }}</span></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistics --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        สถิติแท็ก
                    </h3>
                </div>

                <div class="p-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                            {{ number_format($this->getTagsCount()) }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            แท็กทั้งหมดในระบบ
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Tags --}}
            @if ($this->getRecentTags()->count() > 0)
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            แท็กล่าสุด
                        </h3>
                    </div>

                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach ($this->getRecentTags() as $recentTag)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-3 h-3 rounded-full"
                                            style="background-color: {{ $recentTag->color }}"></div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $recentTag->name }}
                                        </span>
                                    </div>
                                    @if ($recentTag->posts_count > 0)
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $recentTag->posts_count }} โพสต์
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('administrator.blog.tags.index') }}"
                                class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                                ดูแท็กทั้งหมด →
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Help Card --}}
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
                <div class="p-6">
                    <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2">
                        💡 เคล็ดลับ
                    </h4>
                    <div class="space-y-2 text-sm text-blue-800 dark:text-blue-200">
                        <p>• ใช้ชื่อแท็กที่สั้นและจำง่าย</p>
                        <p>• เลือกสีที่สื่อความหมายของหมวดหมู่</p>
                        <p>• เขียนคำอธิบายให้ชัดเจนเพื่อช่วย SEO</p>
                        <p>• Slug จะถูกสร้างอัตโนมัติจากชื่อแท็ก</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
