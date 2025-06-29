<div x-data="{ 
    isMobileOpen: @entangle('isMobileMenuOpen').live
}" class="relative">

    <!-- Desktop Navigation -->
    <nav class="hidden lg:block bg-primary-500 text-white">
        <div class="container mx-auto px-4">
            <ul class="flex items-center">
                @foreach($menuItems as $menu)
                    <li class="relative group"
                        x-data="{ isOpen: false }"
                        x-on:mouseenter="isOpen = true" 
                        x-on:mouseleave="isOpen = false">
                        
                        <!-- Main Menu Item -->
                        <a href="{{ $this->getMenuUrl($menu) }}" 
                           class="nav-link flex items-center px-6 py-4 hover:bg-primary-600 transition-colors duration-300 font-medium"
                           target="{{ $menu->target }}">
                            @if($menu->icon)
                                <i class="{{ $menu->icon }} mr-2"></i>
                            @endif
                            {{ $menu->name }}
                            
                            @if($this->hasChildren($menu))
                                <i class="fas fa-chevron-down ml-2 text-xs transition-transform duration-200"
                                   x-bind:class="{ 'rotate-180': isOpen }"></i>
                            @endif
                        </a>

                        <!-- Mega Menu / Dropdown -->
                        @if($this->hasChildren($menu))
                            @if($this->isMegaMenu($menu))
                                <!-- Mega Menu -->
                                <div x-show="isOpen"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform translate-y-[-10px]"
                                     x-transition:enter-end="opacity-100 transform translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 transform translate-y-0"
                                     x-transition:leave-end="opacity-0 transform translate-y-[-10px]"
                                     class="absolute top-full left-0 z-50 bg-white text-secondary-800 shadow-2xl border-t-4 border-primary-400"
                                     style="display: none; min-width: 500px; width: max-content;">
                                    
                                    <div class="px-6 py-6">
                                        @php
                                            $accessibleChildren = $this->getAccessibleChildren($menu);
                                            $columns = $this->getMegaMenuColumns($menu);
                                            $groupedChildren = $this->groupChildrenByGroup($accessibleChildren);
                                        @endphp
                                        
                                        @php
                                            // กำหนด grid template ตามจำนวนคอลัมน์ให้สมดุล
                                            $gridTemplate = '';
                                            $maxWidth = '';
                                            if ($columns == 2) {
                                                $gridTemplate = 'grid-template-columns: 1fr 1fr;';
                                                $maxWidth = 'max-width: 600px;';
                                            } elseif ($columns == 3) {
                                                $gridTemplate = 'grid-template-columns: 1fr 1fr 1fr;';
                                                $maxWidth = 'max-width: 750px;';
                                            } elseif ($columns == 4) {
                                                $gridTemplate = 'grid-template-columns: 1fr 1fr 1fr 1fr;';
                                                $maxWidth = 'max-width: 900px;';
                                            } else {
                                                $gridTemplate = 'grid-template-columns: repeat(' . $columns . ', 1fr);';
                                                $maxWidth = 'max-width: ' . ($columns * 200 + 100) . 'px;';
                                            }
                                        @endphp
                                        
                                        <div class="grid gap-6" style="{{ $gridTemplate }} {{ $maxWidth }}">
                                            @if($groupedChildren->count() > 1)
                                                <!-- Group-based layout with flexible widths -->
                                                @foreach($groupedChildren as $groupName => $groupItems)
                                                    <div>
                                                        <h3 class="font-bold text-accent-900 mb-4 flex items-center" style="font-size: 1rem !important;">
                                                            @if($groupItems->first() && $groupItems->first()->icon)
                                                                <i class="{{ $groupItems->first()->icon }} mr-2"></i>
                                                            @endif
                                                            {{ $groupName !== 'default' ? $groupName : $groupItems->first()->name }}
                                                        </h3>
                                                        
                                                        <ul class="space-y-2">
                                                            @foreach($groupItems as $groupItem)
                                                                @if($groupItem->children->isNotEmpty())
                                                                    @foreach($groupItem->children->where('is_active', true) as $child)
                                                                        @if($child->canAccess())
                                                                            <li>
                                                                                <a href="{{ $child->getResolvedUrl() }}" 
                                                                                   class="text-sm hover:text-accent-600 flex items-center transition-colors duration-200"
                                                                                   target="{{ $child->target }}">
                                                                                    <i class="fas fa-chevron-right mr-2 text-xs"></i>
                                                                                    {{ $child->name }}
                                                                                </a>
                                                                            </li>
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    <li>
                                                                        <a href="{{ $groupItem->getResolvedUrl() }}" 
                                                                           class="text-sm hover:text-accent-600 flex items-center transition-colors duration-200"
                                                                           target="{{ $groupItem->target }}">
                                                                            <i class="fas fa-chevron-right mr-2 text-xs"></i>
                                                                            {{ $groupItem->name }}
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endforeach
                                            @else
                                                <!-- Column-based layout -->
                                                @php
                                                    $allItems = $accessibleChildren;
                                                    $itemsPerColumn = ceil($allItems->count() / $columns);
                                                @endphp
                                                
                                                @for($col = 0; $col < $columns; $col++)
                                                    @php
                                                        $startIndex = $col * $itemsPerColumn;
                                                        $columnItems = $allItems->slice($startIndex, $itemsPerColumn);
                                                    @endphp
                                                    
                                                    @if($columnItems->isNotEmpty())
                                                        <div>
                                                            @foreach($columnItems as $child)
                                                                <div class="mb-4">
                                                                    <h3 class="font-bold text-accent-900 mb-3 flex items-center" style="font-size: 1rem !important;">
                                                                        @if($child->icon)
                                                                            <i class="{{ $child->icon }} mr-2"></i>
                                                                        @endif
                                                                        {{ $child->name }}
                                                                    </h3>
                                                                    
                                                                    @if($child->children->isNotEmpty())
                                                                        <ul class="space-y-2">
                                                                            @foreach($child->children->where('is_active', true) as $grandChild)
                                                                                @if($grandChild->canAccess())
                                                                                    <li>
                                                                                        <a href="{{ $grandChild->getResolvedUrl() }}" 
                                                                                           class="text-sm hover:text-accent-600 flex items-center transition-colors duration-200"
                                                                                           target="{{ $grandChild->target }}">
                                                                                            <i class="fas fa-chevron-right mr-2 text-xs"></i>
                                                                                            {{ $grandChild->name }}
                                                                                        </a>
                                                                                    </li>
                                                                                @endif
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                @endfor
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Regular Dropdown -->
                                <div x-show="isOpen"
                                     x-transition
                                     class="absolute top-full left-0 z-50 w-64 bg-white text-secondary-800 shadow-xl border-t-4 border-primary-400"
                                     style="display: none;">
                                    
                                    <ul class="py-2">
                                        @foreach($this->getAccessibleChildren($menu) as $child)
                                            <li>
                                                <a href="{{ $child->getResolvedUrl() }}" 
                                                   class="block px-4 py-2 hover:bg-secondary-50 transition-colors duration-200"
                                                   target="{{ $child->target }}">
                                                    @if($child->icon)
                                                        <i class="{{ $child->icon }} mr-2"></i>
                                                    @endif
                                                    {{ $child->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>

    <!-- Mobile Menu Button -->
    <div class="lg:hidden">
        <button wire:click="toggleMobileMenu" 
                class="p-2 rounded-md hover:bg-primary-100 transition-colors duration-200">
            <div class="relative w-6 h-6">
                <span x-show="!isMobileOpen" 
                      x-transition:enter="transition ease-in duration-150"
                      x-transition:enter-start="opacity-0 rotate-45"
                      x-transition:enter-end="opacity-100 rotate-0">
                    <i class="fas fa-bars text-xl text-primary-500"></i>
                </span>
                
                <span x-show="isMobileOpen" 
                      x-transition:enter="transition ease-in duration-150"
                      x-transition:enter-start="opacity-0 rotate-45"
                      x-transition:enter-end="opacity-100 rotate-0"
                      class="absolute inset-0">
                    <i class="fas fa-times text-xl text-primary-500"></i>
                </span>
            </div>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div x-show="isMobileOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="lg:hidden fixed inset-0 z-50 bg-white" 
         style="display: none;">
        
        <!-- Mobile Header -->
        <div class="bg-primary-500 text-white px-4 py-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold">เมนูหลัก</h2>
            <button wire:click="closeMobileMenu" 
                    class="p-2 hover:bg-primary-600 rounded-md transition-colors duration-200">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Mobile Menu Items -->
        <div class="bg-primary-600 text-white overflow-y-auto h-full pb-20">
            <ul class="py-4">
                @foreach($menuItems as $menu)
                    <li>
                        @if($this->hasChildren($menu))
                            <button wire:click="toggleDropdown({{ $menu->id }})" 
                                    class="w-full text-left px-6 py-3 border-b border-primary-500 hover:bg-primary-500 flex items-center justify-between transition-colors duration-200">
                                <span class="flex items-center">
                                    @if($menu->icon)
                                        <i class="{{ $menu->icon }} mr-3"></i>
                                    @endif
                                    {{ $menu->name }}
                                </span>
                                <i class="fas fa-chevron-down transition-transform duration-200"
                                   x-bind:class="{ 'rotate-180': $wire.activeDropdown === {{ $menu->id }} }"></i>
                            </button>

                            <!-- Mobile Submenu -->
                            <div x-show="$wire.activeDropdown === {{ $menu->id }}"
                                 x-transition
                                 class="bg-primary-500 overflow-hidden"
                                 style="display: none;">
                                
                                @foreach($this->getAccessibleChildren($menu) as $child)
                                    <a href="{{ $child->getResolvedUrl() }}" 
                                       class="block px-10 py-2 hover:bg-primary-400 transition-colors duration-200"
                                       target="{{ $child->target }}"
                                       wire:click="closeMobileMenu">
                                        {{ $child->name }}
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <a href="{{ $this->getMenuUrl($menu) }}" 
                               class="block px-6 py-3 border-b border-primary-500 hover:bg-primary-500 transition-colors duration-200"
                               target="{{ $menu->target }}"
                               wire:click="closeMobileMenu">
                                @if($menu->icon)
                                    <i class="{{ $menu->icon }} mr-3"></i>
                                @endif
                                {{ $menu->name }}
                            </a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Mobile Menu Backdrop -->
    <div x-show="isMobileOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-40"
         wire:click="closeMobileMenu"
         style="display: none;">
    </div>
</div>