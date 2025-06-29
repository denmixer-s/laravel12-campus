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
                    <span class="text-gray-700 dark:text-gray-300">รวมแท็ก</span>
                </nav>

                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    รวมแท็กที่ซ้ำกัน
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    จัดระเบียบแท็กโดยการรวมแท็กที่ซ้ำกันหรือคล้ายกัน
                </p>
            </div>

            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <a href="{{ route('administrator.blog.tags.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                    ยกเลิก
                </a>
            </div>
        </div>
    </div>

    {{-- Progress Steps --}}
    <div class="mb-8">
        <nav aria-label="Progress">
            <ol class="flex items-center">
                <li
                    class="relative {{ $step === 'select' ? 'text-blue-600' : ($step === 'preview' || $step === 'confirm' || $step === 'complete' ? 'text-green-600' : 'text-gray-500') }}">
                    <div class="flex items-center">
                        <span
                            class="flex items-center justify-center w-8 h-8 {{ $step === 'select' ? 'bg-blue-100 border-2 border-blue-600' : ($step === 'preview' || $step === 'confirm' || $step === 'complete' ? 'bg-green-100 border-2 border-green-600' : 'bg-gray-100 border-2 border-gray-300') }} rounded-full">
                            @if ($step === 'preview' || $step === 'confirm' || $step === 'complete')
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            @else
                                <span class="text-sm font-medium">1</span>
                            @endif
                        </span>
                        <span class="ml-2 text-sm font-medium">เลือกแท็ก</span>
                    </div>
                </li>

                <li
                    class="relative ml-8 {{ $step === 'preview' ? 'text-blue-600' : ($step === 'confirm' || $step === 'complete' ? 'text-green-600' : 'text-gray-500') }}">
                    <div class="flex items-center">
                        <span
                            class="flex items-center justify-center w-8 h-8 {{ $step === 'preview' ? 'bg-blue-100 border-2 border-blue-600' : ($step === 'confirm' || $step === 'complete' ? 'bg-green-100 border-2 border-green-600' : 'bg-gray-100 border-2 border-gray-300') }} rounded-full">
                            @if ($step === 'confirm' || $step === 'complete')
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            @else
                                <span class="text-sm font-medium">2</span>
                            @endif
                        </span>
                        <span class="ml-2 text-sm font-medium">ตัวอย่าง</span>
                    </div>
                </li>

                <li
                    class="relative ml-8 {{ $step === 'confirm' ? 'text-blue-600' : ($step === 'complete' ? 'text-green-600' : 'text-gray-500') }}">
                    <div class="flex items-center">
                        <span
                            class="flex items-center justify-center w-8 h-8 {{ $step === 'confirm' ? 'bg-blue-100 border-2 border-blue-600' : ($step === 'complete' ? 'bg-green-100 border-2 border-green-600' : 'bg-gray-100 border-2 border-gray-300') }} rounded-full">
                            @if ($step === 'complete')
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            @else
                                <span class="text-sm font-medium">3</span>
                            @endif
                        </span>
                        <span class="ml-2 text-sm font-medium">ยืนยัน</span>
                    </div>
                </li>

                <li class="relative ml-8 {{ $step === 'complete' ? 'text-green-600' : 'text-gray-500' }}">
                    <div class="flex items-center">
                        <span
                            class="flex items-center justify-center w-8 h-8 {{ $step === 'complete' ? 'bg-green-100 border-2 border-green-600' : 'bg-gray-100 border-2 border-gray-300' }} rounded-full">
                            @if ($step === 'complete')
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            @else
                                <span class="text-sm font-medium">4</span>
                            @endif
                        </span>
                        <span class="ml-2 text-sm font-medium">เสร็จสิ้น</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    {{-- Step 1: Select Tags --}}
    @if ($step === 'select')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Smart Selection Tools --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            เครื่องมือเลือกอัตโนมัติ
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            ใช้เครื่องมือเหล่านี้เพื่อหาแท็กที่ควรรวมกัน
                        </p>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <button wire:click="autoSelectSimilar"
                                class="flex items-center justify-center px-4 py-3 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg text-blue-700 dark:text-blue-300 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                    </path>
                                </svg>
                                หาแท็กคล้ายกันอัตโนมัติ
                            </button>

                            <button wire:click="selectAllVisible"
                                class="flex items-center justify-center px-4 py-3 bg-green-50 hover:bg-green-100 dark:bg-green-900/20 dark:hover:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg text-green-700 dark:text-green-300 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                เลือกทั้งหมดในหน้า
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Search & Filters --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Search --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    ค้นหาแท็ก
                                </label>
                                <div class="relative">
                                    <input type="text" wire:model.live="search"
                                        placeholder="ค้นหาชื่อแท็ก, slug หรือคำอธิบาย..."
                                        class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Per Page --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    แสดงต่อหน้า
                                </label>
                                <select wire:model.live="perPage"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tags Table --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        เลือก
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                                        wire:click="sortBy('name')">
                                        <div class="flex items-center space-x-1">
                                            <span>ชื่อแท็ก</span>
                                            @if ($sortBy === 'name')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    @if ($sortDirection === 'asc')
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    @endif
                                                </svg>
                                            @endif
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600"
                                        wire:click="sortBy('posts_count')">
                                        <div class="flex items-center space-x-1">
                                            <span>จำนวนโพสต์</span>
                                            @if ($sortBy === 'posts_count')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    @if ($sortDirection === 'asc')
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    @endif
                                                </svg>
                                            @endif
                                        </div>
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        สถานะ
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($tags as $tag)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox"
                                                wire:click="toggleTagSelection({{ $tag->id }})"
                                                {{ in_array($tag->id, $selectedTags) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-4 h-4 rounded-full"
                                                    style="background-color: {{ $tag->color }}"></div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $tag->name }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $tag->slug }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ number_format($tag->posts_count) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tag->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                                {{ $tag->is_active ? 'ใช้งาน' : 'ไม่ใช้งาน' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                                    </path>
                                                </svg>
                                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                                                    ไม่พบแท็ก</h3>
                                                <p class="text-gray-500 dark:text-gray-400">
                                                    @if ($search)
                                                        ไม่พบแท็กที่ตรงกับการค้นหา "{{ $search }}"
                                                    @else
                                                        ไม่มีแท็กในระบบ
                                                    @endif
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if ($tags->hasPages())
                        <div
                            class="px-6 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                            {{ $tags->links() }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Selected Tags --}}
                @if (count($selectedTags) > 0)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    แท็กที่เลือก ({{ count($selectedTags) }})
                                </h3>
                                <button wire:click="clearSelection"
                                    class="text-sm text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                    ล้างการเลือก
                                </button>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="space-y-3 mb-4">
                                @foreach ($this->getSelectedTagsDetails() as $selectedTag)
                                    <div
                                        class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-3 h-3 rounded-full"
                                                style="background-color: {{ $selectedTag->color }}"></div>
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $selectedTag->name }}
                                            </span>
                                        </div>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $selectedTag->posts_count }} โพสต์
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                โพสต์ทั้งหมดที่ได้รับผลกระทบ: <span
                                    class="font-semibold">{{ number_format($totalPostsAffected) }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Target Selection --}}
                @if (count($selectedTags) > 0)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                แท็กปลายทาง
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                เลือกแท็กที่จะรวมเข้าด้วยกัน
                            </p>
                        </div>

                        <div class="p-6 space-y-4">
                            {{-- Create New Target Toggle --}}
                            <label class="flex items-center">
                                <input type="checkbox" wire:model.live="createNewTarget"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    สร้างแท็กใหม่
                                </span>
                            </label>

                            @if ($createNewTarget)
                                {{-- New Target Form --}}
                                <div
                                    class="space-y-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            ชื่อแท็กใหม่ <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" wire:model.live="newTargetName"
                                            placeholder="ชื่อแท็กใหม่"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm">
                                        @error('newTargetName')
                                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            คำอธิบาย
                                        </label>
                                        <textarea wire:model.live="newTargetDescription" rows="2" placeholder="คำอธิบายแท็ก (ไม่บังคับ)"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm resize-none"></textarea>
                                    </div>

                                    <div class="flex items-center space-x-3">
                                        <input type="color" wire:model.live="newTargetColor"
                                            class="w-8 h-8 rounded border border-gray-300 dark:border-gray-600">
                                        <span
                                            class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $newTargetColor }}</span>
                                    </div>
                                </div>
                            @else
                                {{-- Existing Target Selection --}}
                                <div class="space-y-2 max-h-48 overflow-y-auto">
                                    @forelse($availableTargets as $target)
                                        <label
                                            class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer {{ $targetTagId == $target->id ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-300 dark:border-blue-700' : '' }}">
                                            <input type="radio" wire:click="setTargetTag({{ $target->id }})"
                                                {{ $targetTagId == $target->id ? 'checked' : '' }}
                                                class="text-blue-600 focus:ring-blue-500">
                                            <div class="ml-3 flex items-center justify-between w-full">
                                                <div class="flex items-center space-x-2">
                                                    <div class="w-3 h-3 rounded-full"
                                                        style="background-color: {{ $target->color }}"></div>
                                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $target->name }}
                                                    </span>
                                                </div>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $target->posts_count }} โพสต์
                                                </span>
                                            </div>
                                        </label>
                                    @empty
                                        <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">
                                            ไม่มีแท็กที่สามารถเลือกเป็นปลายทางได้
                                        </p>
                                    @endforelse
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Action Buttons --}}
                @if (count($selectedTags) > 0)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                            <button wire:click="proceedToPreview"
                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ !$createNewTarget && !$targetTagId ? 'disabled' : '' }}>
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5-5 5M6 7l5 5-5 5"></path>
                                </svg>
                                ดูตัวอย่างการรวม
                            </button>
                        </div>
                    </div>
                @endif

                {{-- Statistics --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            สถิติแท็ก
                        </h3>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">แท็กทั้งหมด</span>
                            <span
                                class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ number_format($tags->total()) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">แท็กที่เลือก</span>
                            <span
                                class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ count($selectedTags) }}</span>
                        </div>
                        @if (count($selectedTags) > 0)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">โพสต์ที่ได้รับผลกระทบ</span>
                                <span
                                    class="text-sm font-semibold text-orange-600 dark:text-orange-400">{{ number_format($totalPostsAffected) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Step 2: Preview --}}
    @if ($step === 'preview')
        <div class="space-y-6">
            {{-- Preview Header --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        ตัวอย่างการรวมแท็ก
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        ตรวจสอบการเปลี่ยนแปลงก่อนดำเนินการ
                    </p>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Source Tags --}}
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                แท็กที่จะถูกรวม ({{ count($selectedTags) }} รายการ)
                            </h4>
                            <div class="space-y-3">
                                @foreach ($this->getSelectedTagsDetails() as $tag)
                                    <div
                                        class="flex items-center justify-between p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-4 h-4 rounded-full"
                                                style="background-color: {{ $tag->color }}"></div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $tag->name }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $tag->posts_count }} โพสต์
                                                </div>
                                            </div>
                                        </div>
                                        <span class="text-red-600 dark:text-red-400 text-sm font-medium">
                                            จะถูกลบ
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Target Tag --}}
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                แท็กปลายทาง
                            </h4>
                            <div
                                class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                @if ($createNewTarget)
                                    <div class="flex items-center space-x-3 mb-3">
                                        <div class="w-4 h-4 rounded-full"
                                            style="background-color: {{ $newTargetColor }}"></div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $newTargetName ?: 'ชื่อแท็กใหม่' }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                แท็กใหม่ที่จะสร้าง
                                            </div>
                                        </div>
                                    </div>
                                    @if ($newTargetDescription)
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">
                                            {{ $newTargetDescription }}</p>
                                    @endif
                                @else
                                    <div class="flex items-center space-x-3 mb-3">
                                        <div class="w-4 h-4 rounded-full"
                                            style="background-color: {{ $targetTag->color }}"></div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $targetTag->name }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $targetTag->posts_count }} โพสต์ปัจจุบัน
                                            </div>
                                        </div>
                                    </div>
                                    @if ($targetTag->description)
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">
                                            {{ $targetTag->description }}</p>
                                    @endif
                                @endif

                                <div class="text-green-600 dark:text-green-400 text-sm font-medium">
                                    จะได้รับโพสต์จำนวน {{ number_format($totalPostsAffected) }} รายการ
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Summary --}}
                    <div
                        class="mt-8 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <h5 class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2">
                            สรุปการเปลี่ยนแปลง
                        </h5>
                        <ul class="space-y-1 text-sm text-blue-800 dark:text-blue-200">
                            <li>• แท็กที่จะถูกลบ: {{ count($selectedTags) }} รายการ</li>
                            <li>• โพสต์ที่ได้รับผลกระทบ: {{ number_format($totalPostsAffected) }} รายการ</li>
                            @if ($createNewTarget)
                                <li>• สร้างแท็กใหม่: "{{ $newTargetName }}"</li>
                            @else
                                <li>• รวมเข้ากับแท็กที่มีอยู่: "{{ $targetTag->name }}"</li>
                            @endif
                            <li>• การดำเนินการนี้ไม่สามารถย้อนกลับได้</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-between">
                <button wire:click="backToSelect"
                    class="inline-flex items-center px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 17l-5-5 5-5M18 17l-5-5 5-5"></path>
                    </svg>
                    กลับไปแก้ไข
                </button>

                <button wire:click="confirmMerge"
                    class="inline-flex items-center px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    ดำเนินการรวมแท็ก
                </button>
            </div>
        </div>
    @endif

    {{-- Step 3: Confirm --}}
    @if ($step === 'confirm')
        <div class="space-y-6">
            {{-- Confirmation Warning --}}
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-6">
                <div class="flex items-center mb-4">
                    <svg class="w-8 h-8 text-red-600 dark:text-red-400 mr-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                    <h3 class="text-lg font-semibold text-red-900 dark:text-red-100">
                        ยืนยันการรวมแท็ก
                    </h3>
                </div>

                <div class="text-red-800 dark:text-red-200 space-y-2">
                    <p class="font-medium">คำเตือน: การดำเนินการนี้ไม่สามารถย้อนกลับได้!</p>
                    <ul class="list-disc list-inside space-y-1 text-sm">
                        <li>แท็ก {{ count($selectedTags) }} รายการจะถูกลบถาวร</li>
                        <li>โพสต์ {{ number_format($totalPostsAffected) }} รายการจะถูกกำหนดแท็กใหม่</li>
                        <li>ข้อมูลสถิติและประวัติการใช้งานจะถูกรีเซ็ต</li>
                    </ul>
                    <p class="text-sm font-medium mt-4">
                        กรุณาตรวจสอบให้แน่ใจก่อนดำเนินการ
                    </p>
                </div>
            </div>

            {{-- Final Summary --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        สรุปสุดท้าย
                    </h3>
                </div>

                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                            <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ count($selectedTags) }}
                            </div>
                            <div class="text-sm text-red-800 dark:text-red-200">แท็กที่จะถูกลบ</div>
                        </div>

                        <div class="text-center p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                            <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">
                                {{ number_format($totalPostsAffected) }}</div>
                            <div class="text-sm text-orange-800 dark:text-orange-200">โพสต์ที่ได้รับผลกระทบ</div>
                        </div>

                        <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">1</div>
                            <div class="text-sm text-green-800 dark:text-green-200">
                                แท็ก{{ $createNewTarget ? 'ใหม่' : 'ปลายทาง' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-between">
                <button wire:click="backToSelect"
                    class="inline-flex items-center px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 17l-5-5 5-5M18 17l-5-5 5-5"></path>
                    </svg>
                    ยกเลิก
                </button>

                <button wire:click="executeMerge" {{ $mergeInProgress ? 'disabled' : '' }}
                    class="inline-flex items-center px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    @if ($mergeInProgress)
                        <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        กำลังดำเนินการ...
                    @else
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        ยืนยันการรวมแท็ก
                    @endif
                </button>
            </div>
        </div>
    @endif

    {{-- Step 4: Complete --}}
    @if ($step === 'complete')
        <div class="space-y-6">
            {{-- Success Message --}}
            <div
                class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-6 text-center">
                <svg class="w-16 h-16 text-green-600 dark:text-green-400 mx-auto mb-4" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>

                <h3 class="text-2xl font-bold text-green-900 dark:text-green-100 mb-2">
                    รวมแท็กเรียบร้อยแล้ว!
                </h3>

                <p class="text-green-800 dark:text-green-200 mb-6">
                    ดำเนินการรวมแท็ก {{ count($selectedTags) }} รายการเรียบร้อยแล้ว
                    โพสต์ {{ number_format($totalPostsAffected) }} รายการได้รับการอัปเดตแท็ก
                </p>

                {{-- Result Summary --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg border border-green-200 dark:border-green-700 p-6 text-left">
                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">สรุปผลการดำเนินการ:</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">แท็กที่ถูกลบ:</span>
                            <span class="font-medium text-gray-900 dark:text-gray-100 ml-2">{{ count($selectedTags) }}
                                รายการ</span>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">โพสต์ที่อัปเดต:</span>
                            <span
                                class="font-medium text-gray-900 dark:text-gray-100 ml-2">{{ number_format($totalPostsAffected) }}
                                รายการ</span>
                        </div>
                        <div class="md:col-span-2">
                            <span class="text-gray-600 dark:text-gray-400">แท็กผลลัพธ์:</span>
                            @if ($createNewTarget)
                                <span class="font-medium text-gray-900 dark:text-gray-100 ml-2">"{{ $newTargetName }}"
                                    (สร้างใหม่)</span>
                            @else
                                <span
                                    class="font-medium text-gray-900 dark:text-gray-100 ml-2">"{{ $targetTag->name }}"
                                    (ที่มีอยู่)</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-center space-x-4">
                <button wire:click="startOver"
                    class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    รวมแท็กเพิ่มเติม
                </button>

                <a href="{{ route('administrator.blog.tags.index') }}"
                    class="inline-flex items-center px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    กลับสู่การจัดการแท็ก
                </a>
            </div>
        </div>
    @endif
</div>
