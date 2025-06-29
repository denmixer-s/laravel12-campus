{{-- Simple Sidebar Link Component --}}
@props(['active' => false, 'href' => '#', 'icon' => null, 'badge' => null])

<li class="mb-1">
    <a href="{{ $href }}" @class([
        'group flex items-center px-3 py-2.5 text-sm rounded-lg transition-all duration-200 ease-in-out relative overflow-hidden',
        'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md transform scale-[0.98] font-semibold' => $active,
        'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white hover:shadow-sm hover:scale-[1.02] hover:translate-x-1' => !$active,
    ])>
        {{-- Icon --}}
        <div class="relative flex items-center justify-center flex-shrink-0 w-5 h-5 mr-3">
            @if ($icon)
                @svg($icon, [
                    'class' => $active ? 'w-5 h-5 text-white drop-shadow-sm' : 'w-5 h-5 text-slate-500 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200',
                ])
            @else
                <div
                    class="w-2 h-2 rounded-full {{ $active ? 'bg-white' : 'bg-slate-400 group-hover:bg-blue-500' }} transition-colors duration-200">
                </div>
            @endif

            {{-- Badge indicator --}}
            @if ($badge)
                <span
                    class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-500 rounded-full transform scale-75 shadow-lg">
                    {{ $badge }}
                </span>
            @endif
        </div>

        {{-- Text --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between">
                <span class="font-medium truncate">{{ $slot }}</span>
                @if ($badge && $badge !== 'true')
                    <span @class([
                        'inline-flex items-center justify-center px-2 py-0.5 ml-2 text-xs font-medium rounded-full flex-shrink-0',
                        'bg-white/20 text-white' => $active,
                        'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' => !$active,
                    ])>
                        {{ $badge }}
                    </span>
                @endif
            </div>
        </div>

        {{-- Active indicator --}}
        @if ($active)
            <div class="absolute top-0 bottom-0 right-0 w-1 rounded-l-full bg-white/30"></div>
        @endif
    </a>
</li>

{{-- ========================== --}}
