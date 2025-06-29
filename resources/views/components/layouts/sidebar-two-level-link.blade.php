{{-- Simple Two-Level Link Component --}}
@props(['active' => false, 'href' => '#', 'icon' => 'fas-house', 'badge' => null])

<a href="{{ $href }}"
   @class([
       'group flex items-center px-3 py-2 text-sm rounded-lg transition-all duration-200 ease-in-out relative overflow-hidden mb-1',
       'bg-gradient-to-r from-blue-400 to-blue-500 text-white shadow-md transform scale-[0.98] font-medium' => $active,
       'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/30 hover:text-slate-900 dark:hover:text-white hover:shadow-sm hover:translate-x-1' => !$active,
   ])>
    {{-- Connection dot --}}
    <div class="absolute left-0 transform -translate-x-3 -translate-y-1/2 top-1/2">
        <div @class([
            'w-2 h-2 rounded-full transition-all duration-200',
            'bg-blue-500 shadow-md' => $active,
            'bg-slate-300 dark:bg-slate-600 group-hover:bg-blue-400' => !$active
        ])></div>
    </div>

    <div class="flex items-center w-full">
        {{-- Icon --}}
        <div class="relative flex items-center justify-center flex-shrink-0 w-4 h-4 mr-3">
            @if($icon)
                @svg($icon, [
                    'class' => $active
                        ? 'w-4 h-4 text-white drop-shadow-sm'
                        : 'w-4 h-4 text-slate-500 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200'
                ])
            @else
                <div class="w-1.5 h-1.5 rounded-full {{ $active ? 'bg-white' : 'bg-slate-400 group-hover:bg-blue-500' }} transition-colors duration-200"></div>
            @endif

            @if($badge)
                <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1 py-0.5 text-xs font-bold leading-none text-white bg-red-500 rounded-full transform scale-75 shadow-lg">
                    {{ $badge }}
                </span>
            @endif
        </div>

        {{-- Text content --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between">
                <span class="font-medium truncate">{{ $slot }}</span>

                @if($badge && $badge !== 'true')
                    <span @class([
                        'inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-medium rounded-full',
                        'bg-white/20 text-white' => $active,
                        'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' => !$active
                    ])>
                        {{ $badge }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Active indicator --}}
    @if($active)
        <div class="absolute top-0 bottom-0 right-0 w-1 rounded-l-full bg-white/30"></div>
    @endif
</a>

{{-- ========================== --}}
