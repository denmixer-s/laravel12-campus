{{-- Final Optimized Frontend Menu Component --}}
<div
    class="{{ $cssClasses['container'] ?? 'menu-container' }} menu-location-{{ $config['location'] }} menu-variant-{{ $config['variant'] }}"
    x-data="{
        showMobile: @js($config['showMobileMenu']),
        theme: @js($theme),
        location: @js($config['location']),

        init() {
            this.$watch('showMobile', (value) => {
                if (this.$wire) this.$wire.showMobileMenu = value;
                this.handleBodyScroll(value);
            });
        },

        toggleMobile() {
            this.showMobile = !this.showMobile;
            if (this.$wire?.toggleMobileMenu) this.$wire.toggleMobileMenu();
        },

        closeMobile() {
            this.showMobile = false;
            if (this.$wire) this.$wire.showMobileMenu = false;
        },

        handleBodyScroll(isOpen) {
            document.body.style.overflow = isOpen ? 'hidden' : '';
        }
    }"
    @keydown.escape.window="if (showMobile) closeMobile()"
    @resize.window.debounce.300ms="if (window.innerWidth >= 1024 && showMobile) closeMobile()"
>
    {{-- Breadcrumbs --}}
    @if($config['showBreadcrumbs'] && isset($breadcrumbs) && $breadcrumbs->isNotEmpty())
        <nav class="flex mb-4 px-4 py-2 bg-gray-50 rounded-lg" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3" role="list">
                @foreach($breadcrumbs as $index => $crumb)
                    <li class="inline-flex items-center" role="listitem">
                        @if($index > 0)
                            <svg class="size-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 111.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                        @if($loop->last)
                            <span class="text-sm font-medium text-gray-500" aria-current="page">{{ $crumb->name }}</span>
                        @else
                            <a href="{{ method_exists($crumb, 'getResolvedUrl') ? $crumb->getResolvedUrl() : $crumb->getUrl() }}"
                               class="text-sm font-medium text-{{ $theme['primary'] }}-600 hover:text-{{ $theme['primary'] }}-800 transition-colors"
                               wire:click="handleMenuClick({{ $crumb->id }}, '{{ method_exists($crumb, 'getResolvedUrl') ? $crumb->getResolvedUrl() : $crumb->getUrl() }}', '{{ $crumb->target }}')">
                                {{ $crumb->name }}
                            </a>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
    @endif

    {{-- Desktop Navigation --}}
    <nav class="hidden lg:flex lg:items-center lg:space-x-1 {{ $cssClasses['desktop'] ?? 'menu-desktop' }}" role="navigation" aria-label="Main navigation">
        @if($menuTree->isNotEmpty())
            <ul class="flex items-center space-x-1 menu-list" role="menubar">
                @foreach($menuTree as $menu)
                    <li class="relative group menu-item-wrapper" role="none">
                        @if($menu->children->isNotEmpty())
                            {{-- Dropdown Menu --}}
                            <div x-data="{ isOpen: false, timeout: null }"
                                 @mouseenter="clearTimeout(timeout); isOpen = true"
                                 @mouseleave="timeout = setTimeout(() => isOpen = false, 300)">
                                <button type="button"
                                    class="flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 group focus:outline-none focus:ring-2 focus:ring-{{ $theme['primary'] }}-500
                                        {{ $this->isMenuActive($menu)
                                            ? 'text-' . $theme['primary'] . '-600 bg-' . $theme['primary'] . '-50 shadow-sm'
                                            : 'text-gray-700 hover:bg-' . $theme['accent'] . '-200 hover:text-' . $theme['primary'] . '-600' }}"
                                    @click="isOpen = !isOpen"
                                    :aria-expanded="isOpen"
                                    aria-haspopup="true"
                                    role="menuitem">

                                    @if($config['showIcons'] && $menu->icon)
                                        <i class="{{ $menu->icon }} mr-2 text-sm" aria-hidden="true"></i>
                                    @endif
                                    <span>{{ $menu->name }}</span>
                                    <svg class="ml-1 size-4 transition-transform duration-200 text-gray-500"
                                         :class="{ 'rotate-180': isOpen }"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                {{-- Dropdown Panel --}}
                                <div class="absolute left-0 top-full mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-100 z-50"
                                     x-show="isOpen"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 transform scale-100"
                                     x-transition:leave-end="opacity-0 transform scale-95"
                                     role="menu">
                                    <div class="py-2">
                                        @foreach($menu->children as $childMenu)
                                            @if($childMenu->children->isNotEmpty())
                                                <div class="relative" x-data="{ subOpen: false }" @mouseenter="subOpen = true" @mouseleave="subOpen = false">
                                                    <button type="button"
                                                            class="w-full flex items-center justify-between px-4 py-3 text-sm text-gray-700 hover:bg-{{ $theme['accent'] }}-200 hover:text-{{ $theme['primary'] }}-600 transition-colors rounded-lg mx-1 focus:outline-none focus:ring-2 focus:ring-{{ $theme['primary'] }}-500"
                                                            @click="subOpen = !subOpen">
                                                        <div class="flex items-center">
                                                            @if($childMenu->icon && $config['showIcons'])
                                                                <i class="{{ $childMenu->icon }} mr-3 text-sm text-gray-500"></i>
                                                            @endif
                                                            <span>{{ $childMenu->name }}</span>
                                                        </div>
                                                        <svg class="size-4 transition-transform duration-200 text-gray-400"
                                                             :class="{ 'rotate-90': subOpen }"
                                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                        </svg>
                                                    </button>

                                                    <div class="absolute left-full top-0 w-64 bg-white rounded-xl shadow-xl border border-gray-100 ml-1"
                                                         x-show="subOpen"
                                                         x-transition>
                                                        <div class="py-2">
                                                            @foreach($childMenu->children as $subMenu)
                                                                <a href="{{ method_exists($subMenu, 'getResolvedUrl') ? $subMenu->getResolvedUrl() : $subMenu->getUrl() }}"
                                                                   class="w-full flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-{{ $theme['accent'] }}-200 hover:text-{{ $theme['primary'] }}-600 transition-colors rounded-lg mx-1 {{ $this->isMenuActive($subMenu) ? 'bg-' . $theme['primary'] . '-50 text-' . $theme['primary'] . '-700' : '' }}"
                                                                   @if($subMenu->target === '_blank') target="_blank" @endif
                                                                   wire:click="handleMenuClick({{ $subMenu->id }}, '{{ method_exists($subMenu, 'getResolvedUrl') ? $subMenu->getResolvedUrl() : $subMenu->getUrl() }}', '{{ $subMenu->target }}')">
                                                                    @if($subMenu->icon && $config['showIcons'])
                                                                        <i class="{{ $subMenu->icon }} mr-3 text-sm text-gray-500"></i>
                                                                    @endif
                                                                    <span>{{ $subMenu->name }}</span>
                                                                    @if($subMenu->target === '_blank')
                                                                        <i class="fas fa-external-link-alt ml-auto text-xs text-gray-400"></i>
                                                                    @endif
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <a href="{{ method_exists($childMenu, 'getResolvedUrl') ? $childMenu->getResolvedUrl() : $childMenu->getUrl() }}"
                                                   class="w-full flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-{{ $theme['accent'] }}-200 hover:text-{{ $theme['primary'] }}-600 transition-colors rounded-lg mx-1 {{ $this->isMenuActive($childMenu) ? 'bg-' . $theme['primary'] . '-50 text-' . $theme['primary'] . '-700' : '' }}"
                                                   @if($childMenu->target === '_blank') target="_blank" @endif
                                                   wire:click="handleMenuClick({{ $childMenu->id }}, '{{ method_exists($childMenu, 'getResolvedUrl') ? $childMenu->getResolvedUrl() : $childMenu->getUrl() }}', '{{ $childMenu->target }}')">
                                                    @if($childMenu->icon && $config['showIcons'])
                                                        <i class="{{ $childMenu->icon }} mr-3 text-sm text-gray-500"></i>
                                                    @endif
                                                    <span>{{ $childMenu->name }}</span>
                                                    @if($childMenu->target === '_blank')
                                                        <i class="fas fa-external-link-alt ml-auto text-xs text-gray-400"></i>
                                                    @endif
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Simple Menu --}}
                            <a href="{{ method_exists($menu, 'getResolvedUrl') ? $menu->getResolvedUrl() : $menu->getUrl() }}"
                               class="flex items-center px-6 py-2 text-sm font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-{{ $theme['primary'] }}-500
                                   {{ $this->isMenuActive($menu)
                                       ? 'text-' . $theme['primary'] . '-600 bg-' . $theme['primary'] . '-50 shadow-sm'
                                       : 'text-gray-700 hover:bg-' . $theme['accent'] . '-200 hover:text-' . $theme['primary'] . '-600' }}"
                               @if($menu->target === '_blank') target="_blank" @endif
                               wire:click="handleMenuClick({{ $menu->id }}, '{{ method_exists($menu, 'getResolvedUrl') ? $menu->getResolvedUrl() : $menu->getUrl() }}', '{{ $menu->target }}')"
                               role="menuitem">
                                @if($config['showIcons'] && $menu->icon)
                                    <i class="{{ $menu->icon }} mr-2 text-sm"></i>
                                @endif
                                <span>{{ $menu->name }}</span>
                                @if($menu->target === '_blank')
                                    <i class="fas fa-external-link-alt ml-auto text-xs text-gray-400"></i>
                                @endif
                            </a>
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-sm text-gray-500">No menu items available</div>
        @endif
    </nav>

    {{-- Mobile Menu Button --}}
    <div class="lg:hidden">
        <button type="button"
                class="inline-flex items-center justify-center p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-{{ $theme['primary'] }}-500 transition-all"
                @click="toggleMobile()"
                :aria-expanded="showMobile"
                aria-label="Toggle mobile menu">
            <svg class="size-6" :class="{ 'hidden': showMobile, 'block': !showMobile }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            <svg class="size-6" :class="{ 'block': showMobile, 'hidden': !showMobile }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    {{-- Mobile Menu Panel --}}
    <div class="lg:hidden fixed inset-0 z-50" x-show="showMobile" x-cloak>
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-black bg-opacity-50"
             x-show="showMobile"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="closeMobile()"></div>

        {{-- Mobile Panel --}}
        <div class="fixed top-0 right-0 bottom-0 w-full max-w-sm bg-white shadow-xl"
             x-show="showMobile"
             x-transition:enter="transform transition ease-in-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in-out duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             role="dialog"
             aria-modal="true"
             aria-labelledby="mobile-menu-title">

            {{-- Mobile Header --}}
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-{{ $theme['primary'] }}-50 to-{{ $theme['secondary'] }}-50">
                <h2 id="mobile-menu-title" class="text-lg font-semibold text-gray-900">Navigation</h2>
                <button type="button"
                        class="p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors"
                        @click="closeMobile()"
                        aria-label="Close menu">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- Mobile Menu Content --}}
            <div class="flex-1 overflow-y-auto p-4" role="menu">
                @if($menuTree->isNotEmpty())
                    <nav class="space-y-1">
                        @foreach($menuTree as $menu)
                            <div x-data="{ expanded: {{ $this->isMenuActive($menu) ? 'true' : 'false' }} }">
                                @if($menu->children->isNotEmpty())
                                    <button type="button"
                                            class="w-full flex items-center justify-between px-4 py-3 text-base font-medium rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-{{ $theme['primary'] }}-500 {{ $this->isMenuActive($menu) ? 'text-' . $theme['primary'] . '-700 bg-' . $theme['primary'] . '-50' : 'text-gray-900 hover:bg-' . $theme['accent'] . '-200' }}"
                                            @click="expanded = !expanded"
                                            :aria-expanded="expanded">
                                        <div class="flex items-center">
                                            @if($menu->icon && $config['showIcons'])
                                                <i class="{{ $menu->icon }} mr-3 text-lg"></i>
                                            @endif
                                            <span>{{ $menu->name }}</span>
                                        </div>
                                        <svg class="size-5 transition-transform duration-200"
                                             :class="{ 'rotate-180': expanded }"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>

                                    <div class="ml-4 mt-1 space-y-1" x-show="expanded" x-collapse>
                                        @foreach($menu->children as $childMenu)
                                            @if($childMenu->children->isNotEmpty())
                                                <div x-data="{ subExpanded: false }">
                                                    <button type="button"
                                                            class="w-full flex items-center justify-between px-4 py-3 text-base font-medium rounded-xl transition-colors {{ $this->isMenuActive($childMenu) ? 'text-' . $theme['primary'] . '-700 bg-' . $theme['primary'] . '-50' : 'text-gray-900 hover:bg-' . $theme['accent'] . '-200' }}"
                                                            @click="subExpanded = !subExpanded">
                                                        <div class="flex items-center">
                                                            @if($childMenu->icon && $config['showIcons'])
                                                                <i class="{{ $childMenu->icon }} mr-3 text-lg"></i>
                                                            @endif
                                                            <span>{{ $childMenu->name }}</span>
                                                        </div>
                                                        <svg class="size-5 transition-transform duration-200"
                                                             :class="{ 'rotate-180': subExpanded }"
                                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                    </button>

                                                    <div class="ml-4 mt-1 space-y-1" x-show="subExpanded" x-collapse>
                                                        @foreach($childMenu->children as $subMenu)
                                                            <a href="{{ method_exists($subMenu, 'getResolvedUrl') ? $subMenu->getResolvedUrl() : $subMenu->getUrl() }}"
                                                               class="w-full flex items-center px-4 py-3 text-base font-medium rounded-xl transition-colors {{ $this->isMenuActive($subMenu) ? 'text-' . $theme['primary'] . '-700 bg-' . $theme['primary'] . '-50' : 'text-gray-900 hover:bg-' . $theme['accent'] . '-200' }}"
                                                               @if($subMenu->target === '_blank') target="_blank" @endif
                                                               wire:click="handleMenuClick({{ $subMenu->id }}, '{{ method_exists($subMenu, 'getResolvedUrl') ? $subMenu->getResolvedUrl() : $subMenu->getUrl() }}', '{{ $subMenu->target }}')"
                                                               @click="closeMobile()">
                                                                @if($subMenu->icon && $config['showIcons'])
                                                                    <i class="{{ $subMenu->icon }} mr-3 text-lg"></i>
                                                                @endif
                                                                <span>{{ $subMenu->name }}</span>
                                                                @if($subMenu->target === '_blank')
                                                                    <i class="fas fa-external-link-alt ml-auto text-sm text-gray-400"></i>
                                                                @endif
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @else
                                                <a href="{{ method_exists($childMenu, 'getResolvedUrl') ? $childMenu->getResolvedUrl() : $childMenu->getUrl() }}"
                                                   class="w-full flex items-center px-4 py-3 text-base font-medium rounded-xl transition-colors {{ $this->isMenuActive($childMenu) ? 'text-' . $theme['primary'] . '-700 bg-' . $theme['primary'] . '-50' : 'text-gray-900 hover:bg-' . $theme['accent'] . '-200' }}"
                                                   @if($childMenu->target === '_blank') target="_blank" @endif
                                                   wire:click="handleMenuClick({{ $childMenu->id }}, '{{ method_exists($childMenu, 'getResolvedUrl') ? $childMenu->getResolvedUrl() : $childMenu->getUrl() }}', '{{ $childMenu->target }}')"
                                                   @click="closeMobile()">
                                                    @if($childMenu->icon && $config['showIcons'])
                                                        <i class="{{ $childMenu->icon }} mr-3 text-lg"></i>
                                                    @endif
                                                    <span>{{ $childMenu->name }}</span>
                                                    @if($childMenu->target === '_blank')
                                                        <i class="fas fa-external-link-alt ml-auto text-sm text-gray-400"></i>
                                                    @endif
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <a href="{{ method_exists($menu, 'getResolvedUrl') ? $menu->getResolvedUrl() : $menu->getUrl() }}"
                                       class="w-full flex items-center px-4 py-3 text-base font-medium rounded-xl transition-colors {{ $this->isMenuActive($menu) ? 'text-' . $theme['primary'] . '-700 bg-' . $theme['primary'] . '-50' : 'text-gray-900 hover:bg-' . $theme['accent'] . '-200' }}"
                                       @if($menu->target === '_blank') target="_blank" @endif
                                       wire:click="handleMenuClick({{ $menu->id }}, '{{ method_exists($menu, 'getResolvedUrl') ? $menu->getResolvedUrl() : $menu->getUrl() }}', '{{ $menu->target }}')"
                                       @click="closeMobile()">
                                        @if($menu->icon && $config['showIcons'])
                                            <i class="{{ $menu->icon }} mr-3 text-lg"></i>
                                        @endif
                                        <span>{{ $menu->name }}</span>
                                        @if($menu->target === '_blank')
                                            <i class="fas fa-external-link-alt ml-auto text-sm text-gray-400"></i>
                                        @endif
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </nav>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <p class="text-gray-500 text-sm">No menu items available</p>
                    </div>
                @endif
            </div>

            {{-- Mobile Footer CTA --}}
            <div class="border-t border-gray-200 p-4 bg-gray-50">
                <a href="/contact"
                   class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-{{ $theme['primary'] }}-600 to-{{ $theme['secondary'] }}-600 text-white rounded-xl font-semibold hover:from-{{ $theme['primary'] }}-700 hover:to-{{ $theme['secondary'] }}-700 transition-all duration-300 shadow-lg"
                   @click="closeMobile()">
                    <i class="fas fa-rocket mr-2"></i>
                    Get Started
                </a>
            </div>
        </div>
    </div>

    {{-- Minimal CSS for performance --}}
    <style>
    .menu-container { @apply relative; }
    [x-cloak] { display: none !important; }
    .menu-item-wrapper { transition: all 0.2s ease; }
    .menu-item-wrapper:hover .menu-dropdown { opacity: 1; visibility: visible; }
    </style>
</div>
