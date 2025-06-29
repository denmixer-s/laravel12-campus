{{-- resources/views/livewire/backend/blog/partials/category-tree-item.blade.php --}}
@if($level < 10) {{-- Prevent infinite loop by limiting depth --}}
<div class="category-item bg-white border border-gray-200 rounded-lg hover:border-gray-300 transition-all duration-200"
     data-category-id="{{ $category['id'] }}"
     data-parent-id="{{ $category['parent_id'] ?? '' }}"
     style="margin-left: {{ $level * 20 }}px">

    <div class="flex items-center gap-3 p-4">
        <!-- Drag Handle -->
        <div class="drag-handle flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors p-1 rounded">
            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
            </svg>
        </div>

        <!-- Level Indicator -->
        @if($level > 0)
            <div class="flex items-center text-gray-300">
                @for($i = 0; $i < $level; $i++)
                    <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                @endfor
            </div>
        @endif

        <!-- Category Icon/Color -->
        <div class="flex-shrink-0 size-10 rounded-lg flex items-center justify-center shadow-sm border border-gray-200 transition-transform hover:scale-105"
             style="background: linear-gradient(135deg, {{ $category['color'] ?? '#3B82F6' }}20, {{ $category['color'] ?? '#3B82F6' }}10)">
            @if(isset($category['icon']) && $category['icon'])
                <i class="{{ $category['icon'] }} text-lg" style="color: {{ $category['color'] ?? '#3B82F6' }}"></i>
            @else
                <svg class="size-5" style="color: {{ $category['color'] ?? '#3B82F6' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            @endif
        </div>

        <!-- Category Info -->
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1">
                <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $category['name'] }}</h4>
                @if(!($category['is_active'] ?? true))
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        ปิดใช้งาน
                    </span>
                @endif
            </div>
            
            <div class="flex items-center gap-3 text-xs text-gray-500">
                <span class="font-mono bg-gray-100 px-2 py-0.5 rounded">{{ $category['slug'] ?? '' }}</span>
                <span class="flex items-center gap-1">
                    <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                    </svg>
                    {{ $category['sort_order'] ?? 0 }}
                </span>
                @if(isset($category['created_at']))
                    <span class="flex items-center gap-1">
                        <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ \Carbon\Carbon::parse($category['created_at'])->format('d/m/Y') }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Category Stats -->
        <div class="flex items-center gap-2">
            <!-- Posts Count -->
            <div class="flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium"
                 style="background-color: {{ $category['color'] ?? '#3B82F6' }}10; color: {{ $category['color'] ?? '#3B82F6' }}">
                <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ $category['posts_count'] ?? 0 }}
            </div>

            <!-- Children Count -->
            @if(isset($category['children']) && is_array($category['children']) && count($category['children']) > 0)
                <div class="flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                    <svg class="size-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    {{ count($category['children']) }}
                </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-1">
            <!-- Edit Button -->
            <a href="{{ route('administrator.blog.categories.edit', $category['id']) }}"
               class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
               title="แก้ไขหมวดหมู่">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </a>

            <!-- Toggle Children Button -->
            @if(isset($category['children']) && is_array($category['children']) && count($category['children']) > 0)
                <button class="toggle-children p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-lg transition-colors"
                        title="ขยาย/ย่อหมวดย่อย">
                    <svg class="size-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            @endif

            <!-- Status Indicator -->
            <div class="size-3 rounded-full {{ ($category['is_active'] ?? true) ? 'bg-green-400' : 'bg-red-400' }}"
                 title="{{ ($category['is_active'] ?? true) ? 'ใช้งานอยู่' : 'ปิดใช้งาน' }}">
            </div>
        </div>
    </div>

    <!-- Children Container -->
    @if(isset($category['children']) && is_array($category['children']) && count($category['children']) > 0 && $level < 9)
        <div class="children ml-6 pb-2 space-y-2 border-l-2 border-gray-100" 
             data-parent-id="{{ $category['id'] }}" 
             style="display: none;">
            @foreach($category['children'] as $child)
                @if(is_array($child) && isset($child['id']) && $child['id'] !== $category['id'])
                    @include('livewire.backend.blog.partials.category-tree-item', [
                        'category' => $child, 
                        'level' => $level + 1
                    ])
                @endif
            @endforeach
        </div>
    @endif
</div>
@endif