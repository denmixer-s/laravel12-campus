<div x-data="carouselSlider({
    autoplay: @js($autoplay),
    autoplayDelay: @js($autoplayDelay),
    pauseOnHover: @js($pauseOnHover),
    transition: @js($transition),
    slideCount: @js($this->sliderCount)
})" x-init="init()" @mouseenter="pauseOnHover && pause()"
    @mouseleave="pauseOnHover && resume()" class="relative w-full overflow-hidden bg-gray-900" wire:ignore.self>
    @if ($this->hasSliders)
        <!-- Main Slider Container -->
        <div class="relative aspect-video lg:aspect-[21/9] xl:aspect-[2.5/1]">
            <!-- Slides -->
            <div class="relative w-full min-h-screen">
                @foreach ($sliders as $index => $slider)
                    <div class="absolute inset-0 transition-all duration-500 ease-in-out transform"
                        :class="{
                            'opacity-100 translate-x-0': currentSlide === {{ $index }},
                            'opacity-0 translate-x-full': currentSlide < {{ $index }},
                            'opacity-0 -translate-x-full': currentSlide > {{ $index }}
                        }"
                        x-show="currentSlide === {{ $index }}"
                        x-transition:enter="transition-all duration-500 ease-in-out"
                        x-transition:enter-start="opacity-0 transform scale-105"
                        x-transition:enter-end="opacity-100 transform scale-100">
                        <!-- Background Image -->
                        <div class="absolute inset-0">
                            <picture>
                                <!-- Desktop -->
                                <source media="(min-width: 1024px)"
                                    srcset="{{ $this->getResponsiveImageUrls($slider)['desktop'] }}">
                                <!-- Tablet -->
                                <source media="(min-width: 768px)"
                                    srcset="{{ $this->getResponsiveImageUrls($slider)['tablet'] }}">
                                <!-- Mobile -->
                                <img src="{{ $this->getResponsiveImageUrls($slider)['mobile'] }}"
                                    alt="{{ $slider->heading }}" class="w-full h-full object-cover"
                                    loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                            </picture>

                            <!-- Overlay for better text readability -->
                            <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/30 to-transparent">
                            </div>
                        </div>

                        <!-- Content Overlay -->
                        <div class="absolute inset-0 flex items-center">
                            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                                <div class="max-w-2xl lg:max-w-4xl">
                                    <!-- Display Location Badge -->

                                    <!-- Call to Action Button -->
                                    <div class="flex flex-col sm:flex-row gap-4">
                                        @if (filter_var($slider->link, FILTER_VALIDATE_URL))
                                            <a
                                                href="{{ $slider->link }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                wire:click="handleSliderClick({{ $slider->id }})"
                                                class="inline-flex items-center justify-center px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 hover:shadow-lg backdrop-blur-sm"
                                                @click="trackInteraction({{ $slider->id }}, 'external_link')"
                                            >
                                                <span>Visit Website</span>
                                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                            </a>
                                        @elseif(str_starts_with($slider->link, '/'))
                                            <a
                                                href="{{ $slider->link }}"
                                                wire:click="handleSliderClick({{ $slider->id }})"
                                                class="inline-flex items-center justify-center px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 hover:shadow-lg backdrop-blur-sm"
                                                @click="trackInteraction({{ $slider->id }}, 'internal_link')"
                                            >
                                                <span>Learn More</span>
                                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        @elseif(str_starts_with($slider->link, '#'))
                                            <button
                                                wire:click="handleSliderClick({{ $slider->id }})"
                                                @click="document.querySelector('{{ $slider->link }}')?.scrollIntoView({behavior: 'smooth'}); trackInteraction({{ $slider->id }}, 'anchor_link')"
                                                class="inline-flex items-center justify-center px-8 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 hover:shadow-lg backdrop-blur-sm"
                                            >
                                                <span>View Section</span>
                                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                                </svg>
                                            </button>
                                        @else
                                            <a
                                                href="/{{ ltrim($slider->link, '/') }}"
                                                wire:click="handleSliderClick({{ $slider->id }})"
                                                class="inline-flex items-center justify-center px-8 py-3 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 hover:shadow-lg backdrop-blur-sm"
                                                @click="trackInteraction({{ $slider->id }}, 'relative_link')"
                                            >
                                                <span>Explore</span>
                                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        @endif

                                        <!-- Secondary Action (Optional) -->
                                        <button
                                            class="inline-flex items-center justify-center px-8 py-3 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-lg transition-all duration-200 backdrop-blur-sm border border-white/30"
                                            @click="shareSlider({{ $slider->id }}, '{{ addslashes($slider->heading) }}')"
                                        >
                                            <span>Share</span>
                                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Slide Progress Bar -->
                        <div class="absolute bottom-0 left-0 w-full h-1 bg-white/20">
                            <div class="h-full bg-blue-500 transition-all duration-100 ease-linear"
                                :style="{ width: currentSlide === {{ $index }} ? (progress + '%') : '0%' }">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Navigation Arrows -->
        @if ($showNavigation && $this->sliderCount > 1)
            <div class="absolute inset-y-0 left-0 flex items-center">
                <button @click="previousSlide()"
                    class="ml-4 p-3 rounded-full bg-white/20 hover:bg-white/30 text-white transition-all duration-200 backdrop-blur-sm group"
                    aria-label="Previous slide">
                    <svg class="w-6 h-6 transform group-hover:-translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="absolute inset-y-0 right-0 flex items-center">
                <button @click="nextSlide()"
                    class="mr-4 p-3 rounded-full bg-white/20 hover:bg-white/30 text-white transition-all duration-200 backdrop-blur-sm group"
                    aria-label="Next slide">
                    <svg class="w-6 h-6 transform group-hover:translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Indicators -->
        @if ($showIndicators && $this->sliderCount > 1)
            <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2">
                <div class="flex space-x-2">
                    @foreach ($sliders as $index => $slider)
                        <button @click="goToSlide({{ $index }})"
                            class="w-10 h-10 rounded-full transition-all duration-200 border-2 border-white"
                            :class="{
                                'bg-white': currentSlide === {{ $index }},
                                'bg-white/30 hover:bg-white/50': currentSlide !== {{ $index }}
                            }"
                            aria-label="Go to slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Slide Counter -->
        <div class="absolute top-4 right-4">
            <div class="px-3 py-1 bg-black/30 text-white text-sm rounded-full backdrop-blur-sm">
                <span x-text="currentSlide + 1"></span> / <span>{{ $this->sliderCount }}</span>
            </div>
        </div>
    @else
        <!-- No Sliders State -->
        <div
            class="aspect-video lg:aspect-[21/9] xl:aspect-[2.5/1] bg-gradient-to-r from-gray-100 to-gray-200 flex items-center justify-center">
            <div class="text-center">
                <div class="w-24 h-24 bg-gray-300 rounded-lg mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No Sliders Available</h3>
                <p class="text-gray-500">There are currently no sliders to display for this section.</p>
            </div>
        </div>
    @endif

    <!-- Loading State -->
    <div x-show="!loaded" class="absolute inset-0 bg-gray-100 flex items-center justify-center"
        x-transition:leave="transition-opacity duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div class="text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-600">Loading .....</p>
        </div>
    </div>
</div>

<script>
    function carouselSlider(config) {
        return {
            currentSlide: 0,
            autoplayInterval: null,
            progress: 0,
            progressInterval: null,
            loaded: false,
            isPaused: false,

            init() {
                this.loaded = true;
                if (config.autoplay && config.slideCount > 1) {
                    this.startAutoplay();
                }
            },

            nextSlide() {
                if (config.slideCount <= 1) return;
                this.currentSlide = (this.currentSlide + 1) % config.slideCount;
                this.resetProgress();
                this.$wire.call('goToSlide', this.currentSlide);
            },

            previousSlide() {
                if (config.slideCount <= 1) return;
                this.currentSlide = this.currentSlide === 0 ? config.slideCount - 1 : this.currentSlide - 1;
                this.resetProgress();
                this.$wire.call('goToSlide', this.currentSlide);
            },

            goToSlide(index) {
                if (index < 0 || index >= config.slideCount) return;
                this.currentSlide = index;
                this.resetProgress();
                this.$wire.call('goToSlide', index);
            },

            startAutoplay() {
                if (!config.autoplay || config.slideCount <= 1) return;

                this.autoplayInterval = setInterval(() => {
                    if (!this.isPaused) {
                        this.nextSlide();
                    }
                }, config.autoplayDelay);

                this.startProgress();
            },

            startProgress() {
                this.resetProgress();
                this.progressInterval = setInterval(() => {
                    if (!this.isPaused && this.progress < 100) {
                        this.progress += (100 / (config.autoplayDelay / 100));
                    } else if (this.progress >= 100) {
                        this.resetProgress();
                    }
                }, 100);
            },

            resetProgress() {
                this.progress = 0;
            },

            pause() {
                this.isPaused = true;
            },

            resume() {
                this.isPaused = false;
            },

            trackInteraction(sliderId, type) {
                console.log('Slider interaction:', {
                    sliderId,
                    type
                });
                // You can add analytics tracking here
            },

            shareSlider(sliderId, title) {
                if (navigator.share) {
                    navigator.share({
                        title: title,
                        url: window.location.href
                    });
                } else {
                    // Fallback - copy to clipboard
                    navigator.clipboard.writeText(window.location.href);
                    alert('Link copied to clipboard!');
                }
            }
        }
    }
</script>
