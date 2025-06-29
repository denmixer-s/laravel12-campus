<div x-data="carouselSlider({
    autoplay: @js($autoplay),
    autoplayDelay: @js($autoplayDelay),
    pauseOnHover: @js($pauseOnHover),
    transition: @js($transition),
    slideCount: @js($this->sliderCount)
})" x-init="init()" 
    @mouseenter="config.pauseOnHover && pause()"
    @mouseleave="config.pauseOnHover && resume()" 
    class="relative w-full overflow-hidden bg-gray-900" wire:ignore.self>
    
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
                        
                        <!-- Background Image Container -->
                        <div class="absolute inset-0 {{ !empty(trim($slider->link ?? '')) ? 'cursor-pointer' : '' }}"
                             @if(!empty(trim($slider->link ?? '')))
                             @click="handleSliderClick({{ $slider->id }}, '{{ trim($slider->link) }}')"
                             title="Click to view: {{ $slider->heading }}"
                             @endif>
                            
                            <picture>
                                <!-- Desktop -->
                                <source media="(min-width: 1024px)"
                                    srcset="{{ $this->getResponsiveImageUrls($slider)['desktop'] }}">
                                <!-- Tablet -->
                                <source media="(min-width: 768px)"
                                    srcset="{{ $this->getResponsiveImageUrls($slider)['tablet'] }}">
                                <!-- Mobile -->
                                <img src="{{ $this->getResponsiveImageUrls($slider)['mobile'] }}"
                                    alt="{{ $slider->heading }}" 
                                    class="w-full h-full object-cover {{ !empty(trim($slider->link ?? '')) ? 'hover:scale-105 transition-transform duration-300' : '' }}"
                                    loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                            </picture>
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
                <h3 class="text-xl font-semibold text-gray-600 mb-2">ไม่มีสไลด์ให้แสดง</h3>
                <p class="text-gray-500">ขณะนี้ไม่มีสไลด์สำหรับแสดงในส่วนนี้</p>
            </div>
        </div>
    @endif

    <!-- Loading State -->
    <div x-show="!loaded" class="absolute inset-0 bg-gray-100 flex items-center justify-center"
        x-transition:leave="transition-opacity duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div class="text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-600">กำลังโหลด...</p>
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
            config: config, // เก็บ config ไว้ใช้งาน

            init() {
                this.loaded = true;
                if (this.config.autoplay && this.config.slideCount > 1) {
                    this.startAutoplay();
                }
                
                // Listen for Livewire events
                this.$el.addEventListener('open-external-link', (event) => {
                    window.open(event.detail.url, '_blank', 'noopener,noreferrer');
                });
                
                this.$el.addEventListener('navigate-to', (event) => {
                    window.location.href = event.detail.url;
                });
                
                this.$el.addEventListener('scroll-to-anchor', (event) => {
                    const element = document.querySelector(event.detail.anchor);
                    if (element) {
                        element.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            },

            // Handle slider click - FIXED
            handleSliderClick(sliderId, link) {
                console.log('Slider clicked:', { sliderId, link });
                
                // Track click
                this.$wire.call('trackImageClick', sliderId, link, 'image');
                
                // Handle navigation directly
                if (this.isExternalUrl(link)) {
                    window.open(link, '_blank', 'noopener,noreferrer');
                } else if (link.startsWith('#')) {
                    const element = document.querySelector(link);
                    if (element) {
                        element.scrollIntoView({ behavior: 'smooth' });
                    }
                } else if (link.startsWith('/') || !link.includes('://')) {
                    const url = link.startsWith('/') ? link : '/' + link.replace('./', '');
                    window.location.href = url;
                } else {
                    window.location.href = link;
                }
            },

            // Check if URL is external
            isExternalUrl(url) {
                try {
                    const parsedUrl = new URL(url, window.location.origin);
                    return parsedUrl.origin !== window.location.origin;
                } catch (e) {
                    return false;
                }
            },

            nextSlide() {
                if (this.config.slideCount <= 1) return;
                this.currentSlide = (this.currentSlide + 1) % this.config.slideCount;
                this.resetProgress();
                this.$wire.call('goToSlide', this.currentSlide);
            },

            previousSlide() {
                if (this.config.slideCount <= 1) return;
                this.currentSlide = this.currentSlide === 0 ? this.config.slideCount - 1 : this.currentSlide - 1;
                this.resetProgress();
                this.$wire.call('goToSlide', this.currentSlide);
            },

            goToSlide(index) {
                if (index < 0 || index >= this.config.slideCount) return;
                this.currentSlide = index;
                this.resetProgress();
                this.$wire.call('goToSlide', index);
            },

            startAutoplay() {
                if (!this.config.autoplay || this.config.slideCount <= 1) return;

                this.autoplayInterval = setInterval(() => {
                    if (!this.isPaused) {
                        this.nextSlide();
                    }
                }, this.config.autoplayDelay);

                this.startProgress();
            },

            startProgress() {
                this.resetProgress();
                this.progressInterval = setInterval(() => {
                    if (!this.isPaused && this.progress < 100) {
                        this.progress += (100 / (this.config.autoplayDelay / 100));
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
            },

            shareSlider(sliderId, title) {
                if (navigator.share) {
                    navigator.share({
                        title: title,
                        url: window.location.href
                    });
                } else {
                    navigator.clipboard.writeText(window.location.href);
                    alert('ลิงค์ถูกคัดลอกแล้ว!');
                }
            }
        }
    }
</script>