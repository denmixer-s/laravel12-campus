{{-- Mobile Menu Item Partial: partials/mobile-menu-item.blade.php --}}
@php
$mobilePartialContent = '
<div x-data="mobileMenuItem({ 
         id: ' . $menu->id . ', 
         hasChildren: ' . ($menu->children->isNotEmpty() ? 'true' : 'false') . ',
         isActive: ' . ($this->isMenuActive($menu) ? 'true' : 'false') . ',
         level: ' . $level . '
     })"
     class="menu-mobile-item-level-{{ $level }}">
    @if($menu->children->isNotEmpty())
        <button type="button" 
                class="w-full flex items-center justify-between px-4 py-3 text-base font-medium rounded-xl transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-' . $theme['primary'] . '-500 {{ $this->isMenuActive($menu) ? \'text-\' . $theme[\'primary\'] . \'-700 bg-\' . $theme[\'primary\'] . \'-50\' : \'text-gray-900 hover:bg-\' . $theme[\'accent\'] . \'-200\' }}"
                @click="toggle()"
                :aria-expanded="isExpanded"
                aria-haspopup="true">
            <div class="flex items-center">
                @if($menu->icon && $config[\'showIcons\'])
                    <i class="{{ $menu->icon }} mr-3 text-lg" aria-hidden="true"></i>
                @endif
                <span>{{ $menu->name }}</span>
            </div>
            <svg class="size-5 transition-transform duration-200" 
                 :class="{ \'rotate-180\': isExpanded }" 
                 fill="none" 
                 stroke="currentColor" 
                 viewBox="0 0 24 24"
                 aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        
        <div class="ml-4 mt-1 space-y-1" x-show="isExpanded" x-collapse>
            @foreach($menu->children as $childMenu)
                @include(\'livewire.sakon.menus.partials.mobile-menu-item\', [
                    \'menu\' => $childMenu,
                    \'level\' => $level + 1
                ])
            @endforeach
        </div>
    @else
        <a href="{{ $menu->getUrl() }}" 
           class="w-full flex items-center px-4 py-3 text-base font-medium rounded-xl transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-' . $theme['primary'] . '-500 {{ $this->isMenuActive($menu) ? \'text-\' . $theme[\'primary\'] . \'-700 bg-\' . $theme[\'primary\'] . \'-50\' : \'text-gray-900 hover:bg-\' . $theme[\'accent\'] . \'-200\' }}"
           @if($menu->target === \'_blank\') target="_blank" rel="noopener noreferrer" @endif
           wire:click="handleMenuClick({{ $menu->id }}, \'{{ $menu->getUrl() }}\', \'{{ $menu->target }}\')"
           @click="$dispatch(\'close-mobile-menu\')">
            @if($menu->icon && $config[\'showIcons\'])
                <i class="{{ $menu->icon }} mr-3 text-lg" aria-hidden="true"></i>
            @endif
            <span>{{ $menu->name }}</span>
            @if($menu->target === \'_blank\')
                <i class="fas fa-external-link-alt ml-auto text-sm text-gray-400" aria-hidden="true"></i>
            @endif
        </a>
    @endif
</div>
';
@endphp