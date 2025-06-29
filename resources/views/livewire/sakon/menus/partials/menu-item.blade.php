{{-- Menu Item Partial: partials/menu-item.blade.php --}}
@php
// This would be in a separate partial file
$partialContent = '
<div class="relative" 
     x-data="menuItem({ 
         id: ' . $menu->id . ', 
         hasChildren: ' . ($menu->children->isNotEmpty() ? 'true' : 'false') . ',
         isActive: ' . ($this->isMenuActive($menu) ? 'true' : 'false') . '
     })"
     role="none">
    @if($menu->children->isNotEmpty())
        <button type="button" 
                class="w-full flex items-center justify-between px-4 py-3 text-sm text-gray-700 hover:bg-' . $theme['accent'] . '-200 hover:text-' . $theme['primary'] . '-600 transition-colors duration-200 rounded-lg mx-1 focus:outline-none focus:ring-2 focus:ring-' . $theme['primary'] . '-500"
                @mouseenter="openDropdown()"
                @mouseleave="scheduleClose()"
                @click="toggleDropdown()"
                :aria-expanded="isOpen"
                aria-haspopup="true"
                role="menuitem">
            <div class="flex items-center">
                @if($menu->icon && $config[\'showIcons\'])
                    <i class="{{ $menu->icon }} mr-3 text-sm text-gray-500" aria-hidden="true"></i>
                @endif
                <span>{{ $menu->name }}</span>
            </div>
            <svg class="size-4 transition-transform duration-200 text-gray-400" 
                 :class="{ \'rotate-90\': isOpen }" 
                 fill="none" 
                 stroke="currentColor" 
                 viewBox="0 0 24 24"
                 aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
        
        {{-- Nested Dropdown --}}
        <div class="absolute left-full top-0 w-64 bg-white rounded-xl shadow-xl border border-gray-100 ml-1" 
             x-show="isOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             @mouseenter="cancelClose()"
             @mouseleave="scheduleClose()"
             role="menu">
            <div class="py-2" role="none">
                @foreach($menu->children as $subMenu)
                    <a href="{{ $subMenu->getUrl() }}" 
                       class="w-full flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-' . $theme['accent'] . '-200 hover:text-' . $theme['primary'] . '-600 transition-colors duration-200 rounded-lg mx-1 focus:outline-none focus:ring-2 focus:ring-' . $theme['primary'] . '-500 {{ $this->isMenuActive($subMenu) ? \'bg-\' . $theme[\'primary\'] . \'-50 text-\' . $theme[\'primary\'] . \'-700\' : \'\' }}"
                       @if($subMenu->target === \'_blank\') target="_blank" rel="noopener noreferrer" @endif
                       wire:click="handleMenuClick({{ $subMenu->id }}, \'{{ $subMenu->getUrl() }}\', \'{{ $subMenu->target }}\')"
                       role="menuitem">
                        @if($subMenu->icon && $config[\'showIcons\'])
                            <i class="{{ $subMenu->icon }} mr-3 text-sm text-gray-500" aria-hidden="true"></i>
                        @endif
                        <span>{{ $subMenu->name }}</span>
                        @if($subMenu->target === \'_blank\')
                            <i class="fas fa-external-link-alt ml-auto text-xs text-gray-400" aria-hidden="true"></i>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    @else
        <a href="{{ $menu->getUrl() }}" 
           class="w-full flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-' . $theme['accent'] . '-200 hover:text-' . $theme['primary'] . '-600 transition-colors duration-200 rounded-lg mx-1 focus:outline-none focus:ring-2 focus:ring-' . $theme['primary'] . '-500 {{ $this->isMenuActive($menu) ? \'bg-\' . $theme[\'primary\'] . \'-50 text-\' . $theme[\'primary\'] . \'-700\' : \'\' }}"
           @if($menu->target === \'_blank\') target="_blank" rel="noopener noreferrer" @endif
           wire:click="handleMenuClick({{ $menu->id }}, \'{{ $menu->getUrl() }}\', \'{{ $menu->target }}\')"
           role="menuitem">
            @if($menu->icon && $config[\'showIcons\'])
                <i class="{{ $menu->icon }} mr-3 text-sm text-gray-500" aria-hidden="true"></i>
            @endif
            <span>{{ $menu->name }}</span>
            @if($menu->target === \'_blank\')
                <i class="fas fa-external-link-alt ml-auto text-xs text-gray-400" aria-hidden="true"></i>
            @endif
        </a>
    @endif
</div>
';
@endphp