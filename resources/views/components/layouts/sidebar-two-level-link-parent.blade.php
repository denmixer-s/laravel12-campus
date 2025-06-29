{{-- Simple Two-Level Parent Component --}}
@props(['active' => false, 'title' => '', 'icon' => 'fas-list', 'badge' => null])

<li x-data="{
    open: {{ $active ? 'true' : 'false' }}
}" class="mb-1">

    <button @click="open = !open"
    @class([
        'group w-full flex items-center justify-between px-3 py-2.5 text-sm rounded-lg transition-all duration-200 ease-in-out relative overflow-hidden',
        'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md font-semibold' => $active,
        'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white hover:shadow-sm hover:scale-[1.01]' => !$active,
    ])>

        <div class="flex items-center flex-1 min-w-0">
            {{-- Icon --}}
            <div class="relative flex items-center justify-center flex-shrink-0 w-5 h-5 mr-3">
                @svg($icon, [
                    'class' => $active
                        ? 'w-5 h-5 text-white drop-shadow-sm'
                        : 'w-5 h-5 text-slate-500 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200'
                ])

                @if($badge)
                    <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-500 rounded-full transform scale-75 shadow-lg">
                        {{ $badge }}
                    </span>
                @endif
            </div>

            {{-- Title --}}
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <span class="font-medium truncate">{{ $title }}</span>
                    @if($badge && $badge !== 'true')
                        <span @class([
                            'inline-flex items-center justify-center px-2 py-0.5 ml-2 text-xs font-medium rounded-full flex-shrink-0',
                            'bg-white/20 text-white' => $active,
                            'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' => !$active
                        ])>
                            {{ $badge }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Chevron Icon --}}
        <div>
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-4 h-4 transition-all duration-200 ease-in-out"
                 :class="{
                     'rotate-90': open,
                     'rotate-0': !open
                 }"
                 :style="{
                     color: {{ $active ? "'white'" : "null" }}
                 }"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </div>

        {{-- Active indicator --}}
        @if($active)
            <div class="absolute top-0 bottom-0 right-0 w-1 rounded-l-full bg-white/30"></div>
        @endif
    </button>

    {{-- Submenu --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="relative mt-2 space-y-1">

        {{-- Connection line --}}
        <div class="absolute top-0 bottom-0 w-px left-5 bg-gradient-to-b from-slate-300 to-transparent dark:from-slate-600"></div>

        {{-- Submenu content --}}
        <div class="pl-3 ml-6 space-y-1 border-l border-slate-200 dark:border-slate-700">
            {{ $slot }}
        </div>
    </div>
</li>

{{-- ========================== --}}
