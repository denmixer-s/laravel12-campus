{{-- Simple Three-Level Link Component --}}
@props(['active' => false, 'href' => '#', 'badge' => null, 'isExternal' => false])

<a href="{{ $href }}"
   @if($isExternal) target="_blank" rel="noopener noreferrer" @endif
   @class([
       'group flex items-center px-2 py-1.5 text-xs rounded-md transition-all duration-200 ease-in-out relative overflow-hidden mb-0.5',
       'bg-gradient-to-r from-purple-400 to-purple-500 text-white shadow-sm transform scale-[0.98] font-medium' => $active,
       'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/20 hover:text-slate-900 dark:hover:text-white hover:shadow-sm hover:translate-x-0.5' => !$active,
   ])>
    {{-- Connection dot --}}
    <div class="absolute left-0 transform -translate-x-2 -translate-y-1/2 top-1/2">
        <div @class([
            'w-1.5 h-1.5 rounded-full transition-all duration-200',
            'bg-purple-500 shadow-sm' => $active,
            'bg-slate-300 dark:bg-slate-600 group-hover:bg-purple-400' => !$active
        ])></div>
    </div>

    <div class="flex items-center w-full">
        {{-- Icon or bullet point --}}
        <div class="relative flex items-center justify-center flex-shrink-0 w-3 h-3 mr-2">
            <div class="w-1 h-1 rounded-full {{ $active ? 'bg-white' : 'bg-slate-400 group-hover:bg-purple-500' }} transition-colors duration-200"></div>

            @if($badge)
                <span class="absolute -top-0.5 -right-0.5 inline-flex items-center justify-center px-1 py-0.5 text-xs font-bold leading-none text-white bg-red-500 rounded-full transform scale-75 shadow-lg">
                    {{ $badge }}
                </span>
            @endif
        </div>

        {{-- Text content --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between">
                <span class="font-medium truncate">{{ $slot }}</span>

                {{-- Indicators --}}
                <div class="flex items-center flex-shrink-0 ml-1 space-x-1">
                    @if($isExternal)
                        <svg class="w-2.5 h-2.5 {{ $active ? 'text-white' : 'text-slate-400' }} opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    @endif

                    @if($badge && $badge !== 'true')
                        <span @class([
                            'inline-flex items-center justify-center px-1 py-0.5 text-xs font-medium rounded-full',
                            'bg-white/20 text-white' => $active,
                            'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' => !$active
                        ])>
                            {{ $badge }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Active indicator --}}
    @if($active)
        <div class="absolute right-0 top-0 bottom-0 w-0.5 bg-white/30 rounded-l-full"></div>
    @endif
</a>
