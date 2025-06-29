{{-- Simple Three-Level Parent Component --}}
@props(['active' => false, 'title' => '', 'icon' => 'fas-list', 'badge' => null])

<div x-data="{
    subOpen: {{ $active ? 'true' : 'false' }}
}" class="mb-1">

    <button @click="subOpen = !subOpen"
    @class([
        'group w-full flex items-center justify-between px-2 py-2 text-sm rounded-lg transition-all duration-200 ease-in-out relative overflow-hidden',
        'bg-gradient-to-r from-indigo-500 to-indigo-600 text-white shadow-md font-medium' => $active,
        'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/30 hover:text-slate-900 dark:hover:text-white hover:shadow-sm hover:scale-[1.01]' => !$active,
    ])>

        <div class="flex items-center flex-1 min-w-0">
            {{-- Icon --}}
            <div class="relative flex items-center justify-center flex-shrink-0 w-4 h-4 mr-2">
                @svg($icon, [
                    'class' => $active
                        ? 'w-4 h-4 text-white drop-shadow-sm'
                        : 'w-4 h-4 text-slate-500 dark:text-slate-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200'
                ])

                @if($badge)
                    <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1 py-0.5 text-xs font-bold leading-none text-white bg-red-500 rounded-full transform scale-75 shadow-lg">
                        {{ $badge }}
                    </span>
                @endif
            </div>

            {{-- Title --}}
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium truncate">{{ $title }}</span>
                    @if($badge && $badge !== 'true')
                        <span @class([
                            'inline-flex items-center justify-center px-1.5 py-0.5 ml-1 text-xs font-medium rounded-full flex-shrink-0',
                            'bg-white/20 text-white' => $active,
                            'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300' => !$active
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
                 class="w-3 h-3 transition-all duration-200 ease-in-out"
                 :class="{
                     'rotate-90': subOpen,
                     'rotate-0': !subOpen
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
    <div x-show="subOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 transform -translate-y-2 scale-95"
         class="relative mt-1">

        {{-- Connection line --}}
        <div class="absolute top-0 bottom-0 w-px left-3 bg-gradient-to-b from-slate-300 to-transparent dark:from-slate-600"></div>

        {{-- Submenu content --}}
        <div class="ml-4 pl-2 space-y-0.5 border-l border-slate-200 dark:border-slate-700">
            {{ $slot }}
        </div>
    </div>
</div>

{{-- ========================== --}}
