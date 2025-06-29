<x-layouts.frontend :title="__('RSTC')">

   <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full w-full flex-1 overflow-hidden rounded-x">
                @include('frontend.carousel')
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-gray-100/20" />
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl">
                @include('frontend.content')
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl">
                @include('frontend.blog')
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl">
                @include('frontend.hero')
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-gray-100/20" />
        </div>

        <div class="relative h-full flex-1 overflow-hidden rounded-xl  border-gray-200 dark:border-gray-700">
                @include('frontend.accordion-icons')
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl  border-gray-200 dark:border-gray-700">
                @include('frontend.newsletter')
        </div>
    </div>

</x-layouts.frontend>
